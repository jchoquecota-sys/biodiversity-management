<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class ReplaceBiodiversityFromBioserver extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'replace:biodiversity-bioserver {--force : Execute the replacement without confirmation}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Replace all biodiversity_categories records with data from bioserver_grt.biodiversidads table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Show warning about data replacement
            $this->warn('¡ADVERTENCIA! Este comando reemplazará TODOS los registros existentes en biodiversity_categories.');
            $this->warn('Se perderán todos los datos actuales de la tabla biodiversity_categories.');
            
            if (!$this->option('force')) {
                if (!$this->confirm('¿Está seguro de que desea continuar?')) {
                    $this->info('Operación cancelada.');
                    return 0;
                }
            }

            $this->info('Iniciando reemplazo de datos de biodiversidad...');

            // Start transaction
            DB::beginTransaction();

            try {
                // Step 1: Get data from bioserver_grt
                $this->info('Paso 1: Obteniendo datos de bioserver_grt.biodiversidads...');
                $biodiversidadsData = $this->getBiodiversidadsData();
                $this->info("Encontrados {$biodiversidadsData->count()} registros en bioserver_grt.biodiversidads");

                // Step 2: Disable foreign key checks and truncate table
                $this->info('Paso 2: Limpiando tabla biodiversity_categories...');
                $this->truncateBiodiversityCategories();

                // Step 3: Insert new data
                $this->info('Paso 3: Insertando nuevos datos...');
                $this->insertBiodiversityData($biodiversidadsData);

                // Step 4: Re-enable foreign key checks
                DB::statement('SET FOREIGN_KEY_CHECKS=1');

                DB::commit();
                $this->info('¡Reemplazo de datos de biodiversidad completado exitosamente!');
                
                // Show summary
                $this->showSummary();
                
                return 0;

            } catch (Exception $e) {
                DB::rollBack();
                DB::statement('SET FOREIGN_KEY_CHECKS=1'); // Re-enable in case of error
                throw $e;
            }

        } catch (Exception $e) {
            $this->error('Error durante el reemplazo: ' . $e->getMessage());
            Log::error('Error en ReplaceBiodiversityFromBioserver: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }

    /**
     * Get biodiversidads data from bioserver_grt
     */
    private function getBiodiversidadsData()
    {
        return DB::connection('external_bioserver')
            ->table('biodiversidads')
            ->select([
                'idbiodiversidad',
                'idclase',
                'idorden', 
                'idfamilia',
                'especie',
                'nombrecomun',
                'categoria',
                'estado',
                'resumenespecie',
                'created_at',
                'updated_at'
            ])
            ->get();
    }

    /**
     * Safely truncate biodiversity_categories table
     */
    private function truncateBiodiversityCategories()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Truncate the table
        DB::table('biodiversity_categories')->truncate();
        
        $this->info('Tabla biodiversity_categories limpiada exitosamente.');
    }

    /**
     * Insert biodiversity data with proper field mapping
     */
    private function insertBiodiversityData($biodiversidadsData)
    {
        $insertedCount = 0;
        $skippedCount = 0;
        
        foreach ($biodiversidadsData as $record) {
            try {
                // Map fields from biodiversidads to biodiversity_categories
                $mappedData = [
                    'name' => $record->especie, // especie -> name
                    'scientific_name' => $record->especie, // especie -> scientific_name
                    'common_name' => $record->nombrecomun, // nombrecomun -> common_name
                    'description' => $record->resumenespecie, // resumenespecie -> description
                    'conservation_status' => $this->mapConservationStatus($record->categoria), // categoria -> conservation_status (mapped)
                    'kingdom' => 'Animal', // Default kingdom
                    'idreino' => null, // Will need to be mapped based on taxonomy
                    'conservation_status_id' => null, // Will need to be mapped if conservation_statuses table exists
                    'idfamilia' => $record->idfamilia, // Direct mapping
                    'idclase' => $record->idclase, // Direct mapping
                    'idorden' => $record->idorden, // Direct mapping
                    'habitat' => null, // No direct mapping in source
                    'image_path' => null, // No direct mapping in source
                    'image_path_2' => null,
                    'image_path_3' => null,
                    'image_path_4' => null,
                    'created_at' => $record->created_at,
                    'updated_at' => $record->updated_at
                ];

                DB::table('biodiversity_categories')->insert($mappedData);
                $insertedCount++;
                
                if ($insertedCount % 50 == 0) {
                    $this->info("Procesados {$insertedCount} registros...");
                }
                
            } catch (Exception $e) {
                $skippedCount++;
                $this->warn("Error insertando registro ID {$record->idbiodiversidad}: {$e->getMessage()}");
                Log::warning("Error insertando biodiversidad ID {$record->idbiodiversidad}", [
                    'error' => $e->getMessage(),
                    'record' => $record
                ]);
            }
        }
        
        $this->info("Insertados: {$insertedCount} registros");
        if ($skippedCount > 0) {
            $this->warn("Omitidos: {$skippedCount} registros (ver logs para detalles)");
        }
    }

    /**
     * Map conservation status from bioserver format to 2-character code
     */
    private function mapConservationStatus($categoria)
    {
        if (empty($categoria)) {
            return 'LC'; // Default to Least Concern
        }
        
        $categoria = strtoupper($categoria);
        
        // Map common IUCN categories
        if (strpos($categoria, 'CR') !== false || strpos($categoria, 'CRITICALLY ENDANGERED') !== false) {
            return 'CR';
        }
        if (strpos($categoria, 'EN') !== false || strpos($categoria, 'ENDANGERED') !== false) {
            return 'EN';
        }
        if (strpos($categoria, 'VU') !== false || strpos($categoria, 'VULNERABLE') !== false) {
            return 'VU';
        }
        if (strpos($categoria, 'NT') !== false || strpos($categoria, 'NEAR THREATENED') !== false) {
            return 'NT';
        }
        if (strpos($categoria, 'LC') !== false || strpos($categoria, 'LEAST CONCERN') !== false) {
            return 'LC';
        }
        if (strpos($categoria, 'DD') !== false || strpos($categoria, 'DATA DEFICIENT') !== false) {
            return 'DD';
        }
        if (strpos($categoria, 'EX') !== false || strpos($categoria, 'EXTINCT') !== false) {
            return 'EX';
        }
        if (strpos($categoria, 'EW') !== false || strpos($categoria, 'EXTINCT IN THE WILD') !== false) {
            return 'EW';
        }
        
        // Default fallback
        return 'LC';
    }

    /**
     * Show migration summary
     */
    private function showSummary()
    {
        $this->info('\n=== RESUMEN DE MIGRACIÓN ===');
        
        // Count records in biodiversity_categories
        $totalCategories = DB::table('biodiversity_categories')->count();
        $this->info("Total registros en biodiversity_categories: {$totalCategories}");
        
        // Count by conservation status
        $statusCounts = DB::table('biodiversity_categories')
            ->selectRaw('conservation_status, COUNT(*) as count')
            ->whereNotNull('conservation_status')
            ->groupBy('conservation_status')
            ->get();
            
        $this->info('\nDistribución por estado de conservación:');
        foreach ($statusCounts as $status) {
            $this->info("  {$status->conservation_status}: {$status->count}");
        }
        
        // Count records with taxonomy relationships
        $withClase = DB::table('biodiversity_categories')->whereNotNull('idclase')->count();
        $withOrden = DB::table('biodiversity_categories')->whereNotNull('idorden')->count();
        $withFamilia = DB::table('biodiversity_categories')->whereNotNull('idfamilia')->count();
        
        $this->info('\nRelaciones taxonómicas:');
        $this->info("  Con idclase: {$withClase}");
        $this->info("  Con idorden: {$withOrden}");
        $this->info("  Con idfamilia: {$withFamilia}");
    }
}