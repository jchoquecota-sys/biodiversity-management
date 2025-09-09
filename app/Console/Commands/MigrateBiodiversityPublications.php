<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\BiodiversityCategory;
use App\Models\Publication;
use Exception;

class MigrateBiodiversityPublications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:biodiversity-publications {--force : Force migration without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate biodiversity-publication relationships from bioserver_grt.detalle_estudio_general_biodiversidads to biodiversity_category_publication';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Biodiversity Publications Migration ===');
        
        if (!$this->option('force')) {
            if (!$this->confirm('This will migrate data from bioserver_grt.detalle_estudio_general_biodiversidads to biodiversity_category_publication. Continue?')) {
                $this->info('Migration cancelled.');
                return 0;
            }
        }
        
        try {
            DB::beginTransaction();
            
            // Step 1: Get source data
            $this->info('Step 1: Fetching source data from bioserver_grt...');
            $sourceData = $this->getSourceData();
            $this->info("Found {$sourceData->count()} records to migrate.");
            
            // Step 2: Create publications from estudio_generals if they don't exist
            $this->info('Step 2: Creating publications from estudio_generals...');
            $publicationMap = $this->createPublications();
            $this->info("Created/found {$publicationMap->count()} publications.");
            
            // Step 3: Map biodiversity categories
            $this->info('Step 3: Mapping biodiversity categories...');
            $biodiversityMap = $this->mapBiodiversityCategories($sourceData);
            $this->info("Mapped {$biodiversityMap->count()} biodiversity categories.");
            
            // Step 4: Clear existing data (optional)
            if ($this->confirm('Clear existing biodiversity_category_publication data?', false)) {
                DB::table('biodiversity_category_publication')->truncate();
                $this->info('Existing data cleared.');
            }
            
            // Step 5: Insert new relationships
            $this->info('Step 5: Inserting biodiversity-publication relationships...');
            $inserted = $this->insertRelationships($sourceData, $publicationMap, $biodiversityMap);
            
            DB::commit();
            
            $this->info('=== Migration Summary ===');
            $this->info("Total relationships inserted: {$inserted}");
            $this->info("Publications created/used: {$publicationMap->count()}");
            $this->info("Biodiversity categories mapped: {$biodiversityMap->count()}");
            
            $this->info('✅ Migration completed successfully!');
            
        } catch (Exception $e) {
            DB::rollBack();
            $this->error('Migration failed: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return 1;
        }
        
        return 0;
    }
    
    /**
     * Get source data from bioserver_grt
     */
    private function getSourceData()
    {
        return DB::connection('external_bioserver')
            ->table('detalle_estudio_general_biodiversidads')
            ->select('idbiodiversidad', 'idestudiogeneral', 'created_at', 'updated_at')
            ->get();
    }
    
    /**
     * Create publications from estudio_generals table
     */
    private function createPublications()
    {
        $estudios = DB::connection('external_bioserver')
            ->table('estudio_generals')
            ->select('idestudiogeneral', 'titulo', 'autor', 'anio', 'institucion', 'lugar', 'descripcion')
            ->where('estadoestudio', 'activo')
            ->get();
        
        $publicationMap = collect();
        
        foreach ($estudios as $estudio) {
            // Check if publication already exists
            $existingPublication = Publication::where('title', $estudio->titulo)
                ->where('author', $estudio->autor)
                ->where('publication_year', $estudio->anio)
                ->first();
            
            if ($existingPublication) {
                $publicationMap->put($estudio->idestudiogeneral, $existingPublication->id);
            } else {
                // Create new publication
                $publication = Publication::create([
                    'title' => $estudio->titulo,
                    'author' => $estudio->autor,
                    'publication_year' => $estudio->anio,
                    'publisher' => $estudio->institucion,
                    'location' => $estudio->lugar,
                    'description' => $estudio->descripcion,
                    'type' => 'study', // Assuming these are studies
                    'status' => 'published'
                ]);
                
                $publicationMap->put($estudio->idestudiogeneral, $publication->id);
                $this->line("Created publication: {$estudio->titulo}");
            }
        }
        
        return $publicationMap;
    }
    
    /**
     * Map biodiversity categories from bioserver to local IDs
     */
    private function mapBiodiversityCategories($sourceData)
    {
        $biodiversityIds = $sourceData->pluck('idbiodiversidad')->unique();
        $biodiversityMap = collect();
        
        foreach ($biodiversityIds as $bioserverId) {
            // Try to find matching biodiversity category
            // We'll use a simple approach first - you may need to adjust this logic
            $localCategory = BiodiversityCategory::where('id', $bioserverId)->first();
            
            if (!$localCategory) {
                // Try to find by other criteria if direct ID match fails
                // This is a fallback - you might need to implement more sophisticated matching
                $this->warn("Could not find biodiversity category for bioserver ID: {$bioserverId}");
                continue;
            }
            
            $biodiversityMap->put($bioserverId, $localCategory->id);
        }
        
        return $biodiversityMap;
    }
    
    /**
     * Insert biodiversity-publication relationships
     */
    private function insertRelationships($sourceData, $publicationMap, $biodiversityMap)
    {
        $inserted = 0;
        $skipped = 0;
        
        foreach ($sourceData as $record) {
            $biodiversityId = $biodiversityMap->get($record->idbiodiversidad);
            $publicationId = $publicationMap->get($record->idestudiogeneral);
            
            if (!$biodiversityId || !$publicationId) {
                $skipped++;
                continue;
            }
            
            // Check if relationship already exists
            $exists = DB::table('biodiversity_category_publication')
                ->where('biodiversity_category_id', $biodiversityId)
                ->where('publication_id', $publicationId)
                ->exists();
            
            if (!$exists) {
                DB::table('biodiversity_category_publication')->insert([
                    'biodiversity_category_id' => $biodiversityId,
                    'publication_id' => $publicationId,
                    'relevant_excerpt' => null, // Could be populated later
                    'page_reference' => null, // Could be populated later
                    'created_at' => $record->created_at ?? now(),
                    'updated_at' => $record->updated_at ?? now()
                ]);
                
                $inserted++;
            }
        }
        
        if ($skipped > 0) {
            $this->warn("Skipped {$skipped} records due to missing mappings.");
        }
        
        return $inserted;
    }
}
