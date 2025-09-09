<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Exception;

class MigrateRelationsBiodiversityPublications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:biodiversity-relations {--force : Force migration without confirmation} {--clear : Clear existing relations before migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate relations from detalle_estudio_general_biodiversidads to biodiversity_category_publication table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Biodiversity Relations Migration ===');
        
        try {
            // Step 1: Fetch source relations
            $this->info('Step 1: Fetching relations from external_bioserver...');
            $relations = $this->fetchSourceRelations();
            $this->info("Found {$relations->count()} relations to migrate.");
            
            if ($relations->isEmpty()) {
                $this->warn('No relations found to migrate.');
                return 0;
            }
            
            // Step 2: Clear existing relations if requested
            if ($this->option('clear')) {
                $this->clearExistingRelations();
            }
            
            // Step 3: Migrate relations
            $this->info('Step 2: Migrating relations to biodiversity_category_publication table...');
            $migrated = $this->migrateRelations($relations);
            
            // Step 4: Summary
            $this->displaySummary($migrated, $relations->count());
            
            $this->info('✅ Relations migration completed successfully!');
            return 0;
            
        } catch (Exception $e) {
            $this->error('Relations migration failed: ' . $e->getMessage());
            return 1;
        }
    }
    
    /**
     * Fetch source relations from detalle_estudio_general_biodiversidads table
     */
    private function fetchSourceRelations()
    {
        return DB::connection('external_bioserver')
            ->table('detalle_estudio_general_biodiversidads as d')
            ->join('estudio_generals as e', 'd.idestudiogeneral', '=', 'e.idestudiogeneral')
            ->select(
                'd.iddetalle_estudio_biodiversidad',
                'd.idbiodiversidad',
                'd.idestudiogeneral',
                'e.titulo as estudio_titulo',
                'd.created_at',
                'd.updated_at'
            )
            ->orderBy('d.iddetalle_estudio_biodiversidad')
            ->get();
    }
    
    /**
     * Clear existing relations
     */
    private function clearExistingRelations()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('This will delete all existing biodiversity-publication relations. Are you sure?')) {
                $this->info('Migration cancelled.');
                exit(0);
            }
        }
        
        $this->info('Clearing existing relations...');
        DB::table('biodiversity_category_publication')->truncate();
        $this->info('Existing relations cleared.');
    }
    
    /**
     * Migrate relations from source to local table
     */
    private function migrateRelations($relations)
    {
        $migrated = 0;
        $errors = [];
        $skipped = 0;
        
        foreach ($relations as $relation) {
            try {
                // Check if publication exists locally (using original estudio ID)
                $publicationExists = DB::table('publications')
                    ->where('id', $relation->idestudiogeneral)
                    ->exists();
                
                if (!$publicationExists) {
                    $this->warn("Publication with ID {$relation->idestudiogeneral} not found locally. Skipping relation.");
                    $skipped++;
                    continue;
                }
                
                // Check if biodiversity category exists locally
                $categoryExists = DB::table('biodiversity_categories')
                    ->where('id', $relation->idbiodiversidad)
                    ->exists();
                
                if (!$categoryExists) {
                    $this->warn("Biodiversity category with ID {$relation->idbiodiversidad} not found locally. Skipping relation.");
                    $skipped++;
                    continue;
                }
                
                // Check if relation already exists
                $existingRelation = DB::table('biodiversity_category_publication')
                    ->where('biodiversity_category_id', $relation->idbiodiversidad)
                    ->where('publication_id', $relation->idestudiogeneral)
                    ->first();
                
                if ($existingRelation) {
                    $this->warn("Relation between category {$relation->idbiodiversidad} and publication {$relation->idestudiogeneral} already exists. Skipping.");
                    $skipped++;
                    continue;
                }
                
                // Prepare relation data for insertion
                $relationData = [
                    'biodiversity_category_id' => $relation->idbiodiversidad,
                    'publication_id' => $relation->idestudiogeneral,
                    'relevant_excerpt' => null, // Can be filled later
                    'page_reference' => null,   // Can be filled later
                    'created_at' => $relation->created_at ?? now(),
                    'updated_at' => $relation->updated_at ?? now(),
                ];
                
                // Insert relation
                DB::table('biodiversity_category_publication')->insert($relationData);
                
                $migrated++;
                $this->info("Migrated relation: Category {$relation->idbiodiversidad} ↔ Publication {$relation->idestudiogeneral} ({$relation->estudio_titulo})");
                
            } catch (Exception $e) {
                $errors[] = "Error migrating relation ID {$relation->iddetalle_estudio_biodiversidad}: " . $e->getMessage();
                $this->error("Error migrating relation ID {$relation->iddetalle_estudio_biodiversidad}: " . $e->getMessage());
            }
        }
        
        if (!empty($errors)) {
            $this->warn('\n=== Migration Errors ===');
            foreach ($errors as $error) {
                $this->error($error);
            }
        }
        
        if ($skipped > 0) {
            $this->warn("Skipped {$skipped} relations due to missing references.");
        }
        
        return $migrated;
    }
    
    /**
     * Display migration summary
     */
    private function displaySummary($migrated, $total)
    {
        $this->info('\n=== Relations Migration Summary ===');
        $this->info("Total relations processed: {$total}");
        $this->info("Successfully migrated: {$migrated}");
        $this->info("Errors/Skipped: " . ($total - $migrated));
        
        // Show final count in relations table
        $finalCount = DB::table('biodiversity_category_publication')->count();
        $this->info("Final relations count: {$finalCount}");
        
        // Show some statistics
        if ($finalCount > 0) {
            $uniqueCategories = DB::table('biodiversity_category_publication')
                ->distinct('biodiversity_category_id')
                ->count('biodiversity_category_id');
            $uniquePublications = DB::table('biodiversity_category_publication')
                ->distinct('publication_id')
                ->count('publication_id');
            
            $this->info("Unique categories linked: {$uniqueCategories}");
            $this->info("Unique publications linked: {$uniquePublications}");
        }
    }
}
