<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Publication;
use Exception;

class MigrateEstudioGeneralsToPublications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:estudio-generals {--force : Force migration without confirmation} {--clear : Clear existing publications before migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate data from estudio_generals table (bioserver_grt) to publications table preserving original IDs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Estudio Generals to Publications Migration ===');
        
        try {
            // Step 1: Fetch source data
            $this->info('Step 1: Fetching source data from external_bioserver...');
            $estudios = $this->fetchSourceData();
            $this->info("Found {$estudios->count()} records to migrate.");
            
            if ($estudios->isEmpty()) {
                $this->warn('No records found to migrate.');
                return 0;
            }
            
            // Step 2: Clear existing data if requested
            if ($this->option('clear')) {
                $this->clearExistingData();
            }
            
            // Step 3: Migrate data
            $this->info('Step 2: Migrating data to publications table...');
            $migrated = $this->migrateData($estudios);
            
            // Step 4: Summary
            $this->displaySummary($migrated, $estudios->count());
            
            $this->info('✅ Migration completed successfully!');
            return 0;
            
        } catch (Exception $e) {
            $this->error('Migration failed: ' . $e->getMessage());
            return 1;
        }
    }
    
    /**
     * Fetch source data from estudio_generals table
     */
    private function fetchSourceData()
    {
        return DB::connection('external_bioserver')
            ->table('estudio_generals')
            ->orderBy('idestudiogeneral')
            ->get();
    }
    
    /**
     * Clear existing publications data
     */
    private function clearExistingData()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('This will delete all existing publications. Are you sure?')) {
                $this->info('Migration cancelled.');
                exit(0);
            }
        }
        
        $this->info('Clearing existing publications data...');
        DB::table('publications')->truncate();
        $this->info('Existing data cleared.');
    }
    
    /**
     * Migrate data from estudio_generals to publications
     */
    private function migrateData($estudios)
    {
        $migrated = 0;
        $errors = [];
        
        // Disable auto-increment temporarily to preserve IDs
        DB::statement('SET SESSION sql_mode = "NO_AUTO_VALUE_ON_ZERO"');
        
        foreach ($estudios as $estudio) {
            try {
                // Check if publication already exists with this ID
                $existingPublication = DB::table('publications')
                    ->where('id', $estudio->idestudiogeneral)
                    ->first();
                
                if ($existingPublication) {
                    $this->warn("Publication with ID {$estudio->idestudiogeneral} already exists. Skipping.");
                    continue;
                }
                
                // Prepare data for insertion
                $publicationData = [
                    'id' => $estudio->idestudiogeneral, // Preserve original ID
                    'title' => $estudio->titulo,
                    'abstract' => $estudio->descripcion ?? 'No abstract available',
                    'publication_year' => $estudio->anio,
                    'author' => $estudio->autor,
                    'journal' => $estudio->institucion,
                    'doi' => null,
                    'pdf_path' => $estudio->ruta,
                    'created_at' => $estudio->created_at ?? now(),
                    'updated_at' => $estudio->updated_at ?? now(),
                ];
                
                // Insert publication
                DB::table('publications')->insert($publicationData);
                
                $migrated++;
                $this->info("Migrated: {$estudio->titulo} (ID: {$estudio->idestudiogeneral})");
                
            } catch (Exception $e) {
                $errors[] = "Error migrating ID {$estudio->idestudiogeneral}: " . $e->getMessage();
                $this->error("Error migrating ID {$estudio->idestudiogeneral}: " . $e->getMessage());
            }
        }
        
        // Re-enable normal auto-increment behavior
        DB::statement('SET SESSION sql_mode = ""');
        
        if (!empty($errors)) {
            $this->warn('\n=== Migration Errors ===');
            foreach ($errors as $error) {
                $this->error($error);
            }
        }
        
        return $migrated;
    }
    
    /**
     * Display migration summary
     */
    private function displaySummary($migrated, $total)
    {
        $this->info('\n=== Migration Summary ===');
        $this->info("Total records processed: {$total}");
        $this->info("Successfully migrated: {$migrated}");
        $this->info("Errors: " . ($total - $migrated));
        
        // Show final count in publications table
        $finalCount = DB::table('publications')->count();
        $this->info("Final publications count: {$finalCount}");
        
        // Show ID range
        if ($finalCount > 0) {
            $minId = DB::table('publications')->min('id');
            $maxId = DB::table('publications')->max('id');
            $this->info("ID range: {$minId} - {$maxId}");
        }
    }
}
