<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\BiodiversityCategory;

class AnalyzeBiodiversidadTable extends Command
{
    protected $signature = 'biodiversity:analyze-biodiversidads
                            {--limit=10 : Limitar número de registros a analizar}';

    protected $description = 'Analiza la tabla biodiversidads de la base de datos externa';

    public function handle()
    {
        $this->info('🔍 Analizando tabla biodiversidads...');
        
        // Verificar conexión a base de datos externa
        if (!$this->testExternalConnection()) {
            return 1;
        }

        // Analizar estructura de la tabla biodiversidads
        $this->analyzeBiodiversidadsTable();
        
        // Obtener datos de muestra
        $this->analyzeBiodiversidadsData();
        
        // Buscar coincidencias con categorías locales
        $this->findMatches();
        
        return 0;
    }

    private function testExternalConnection()
    {
        try {
            DB::connection('external_bioserver')->getPdo();
            $this->info('✅ Conexión a base de datos externa exitosa.');
            return true;
        } catch (\Exception $e) {
            $this->error('❌ Error conectando a base de datos externa: ' . $e->getMessage());
            return false;
        }
    }

    private function analyzeBiodiversidadsTable()
    {
        try {
            $this->info('\n📋 Analizando estructura de biodiversidads...');
            
            // Verificar si la tabla existe
            $tables = DB::connection('external_bioserver')
                ->select("SHOW TABLES LIKE 'biodiversidads'");
            
            if (empty($tables)) {
                $this->warn('⚠️  Tabla biodiversidads no encontrada.');
                
                // Buscar tablas similares
                $this->info('🔍 Buscando tablas similares...');
                $allTables = DB::connection('external_bioserver')->select('SHOW TABLES');
                
                foreach ($allTables as $table) {
                    $tableName = array_values((array)$table)[0];
                    if (stripos($tableName, 'biodiversidad') !== false) {
                        $this->line('   - ' . $tableName);
                    }
                }
                return;
            }
            
            // Mostrar estructura de la tabla
            $columns = DB::connection('external_bioserver')
                ->select('DESCRIBE biodiversidads');
            
            $this->table(
                ['Campo', 'Tipo', 'Nulo', 'Clave', 'Default', 'Extra'],
                collect($columns)->map(function ($col) {
                    return [
                        $col->Field,
                        $col->Type,
                        $col->Null,
                        $col->Key,
                        $col->Default,
                        $col->Extra
                    ];
                })->toArray()
            );
            
        } catch (\Exception $e) {
            $this->error('❌ Error analizando estructura: ' . $e->getMessage());
        }
    }

    private function analyzeBiodiversidadsData()
    {
        try {
            $limit = $this->option('limit');
            
            $this->info("\n📊 Obteniendo datos de muestra (límite: {$limit})...");
            
            $biodiversidads = DB::connection('external_bioserver')
                ->table('biodiversidads')
                ->limit($limit)
                ->get();
            
            if ($biodiversidads->isEmpty()) {
                $this->warn('⚠️  No se encontraron datos en la tabla.');
                return;
            }
            
            $this->info("📈 Se encontraron {$biodiversidads->count()} registros de muestra.");
            
            // Mostrar muestra de datos
            foreach ($biodiversidads->take(5) as $index => $biodiversidad) {
                $this->line("\n--- Registro " . ($index + 1) . " ---");
                foreach ($biodiversidad as $field => $value) {
                    $displayValue = is_null($value) ? '(null)' : (strlen($value) > 100 ? substr($value, 0, 100) . '...' : $value);
                    $this->line("  {$field}: {$displayValue}");
                }
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Error obteniendo datos: ' . $e->getMessage());
        }
    }

    private function findMatches()
    {
        try {
            $this->info('\n🔍 Buscando coincidencias con categorías locales...');
            
            // Obtener algunas biodiversidads externas
            $externalBiodiversidads = DB::connection('external_bioserver')
                ->table('biodiversidads')
                ->limit(20)
                ->get();
            
            if ($externalBiodiversidads->isEmpty()) {
                $this->warn('⚠️  No hay datos para comparar.');
                return;
            }
            
            $matches = [];
            $possibleMatches = [];
            
            foreach ($externalBiodiversidads as $external) {
                // Buscar coincidencias exactas por nombre científico
                if (isset($external->nombre_cientifico)) {
                    $localMatch = BiodiversityCategory::where('scientific_name', 'LIKE', '%' . $external->nombre_cientifico . '%')
                        ->orWhere('scientific_name', 'LIKE', '%' . trim($external->nombre_cientifico) . '%')
                        ->first();
                    
                    if ($localMatch) {
                        $matches[] = [
                            'external_id' => $external->idbiodiversidad ?? $external->id,
                            'external_name' => $external->nombre_cientifico,
                            'local_id' => $localMatch->id,
                            'local_name' => $localMatch->scientific_name,
                            'match_type' => 'exact'
                        ];
                    }
                }
                
                // Buscar coincidencias por nombre común
                if (isset($external->nombre_comun)) {
                    $localMatch = BiodiversityCategory::where('common_name', 'LIKE', '%' . $external->nombre_comun . '%')
                        ->first();
                    
                    if ($localMatch) {
                        $possibleMatches[] = [
                            'external_id' => $external->idbiodiversidad ?? $external->id,
                            'external_name' => $external->nombre_comun,
                            'local_id' => $localMatch->id,
                            'local_name' => $localMatch->common_name,
                            'match_type' => 'common_name'
                        ];
                    }
                }
            }
            
            if (!empty($matches)) {
                $this->info("\n✅ Coincidencias exactas encontradas: " . count($matches));
                foreach ($matches as $match) {
                    $this->line("   🎯 Externa ID: {$match['external_id']} ({$match['external_name']}) -> Local ID: {$match['local_id']} ({$match['local_name']})");
                }
            } else {
                $this->warn('⚠️  No se encontraron coincidencias exactas.');
            }
            
            if (!empty($possibleMatches)) {
                $this->info("\n🔍 Posibles coincidencias por nombre común: " . count($possibleMatches));
                foreach (array_slice($possibleMatches, 0, 5) as $match) {
                    $this->line("   📝 Externa ID: {$match['external_id']} ({$match['external_name']}) -> Local ID: {$match['local_id']} ({$match['local_name']})");
                }
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Error buscando coincidencias: ' . $e->getMessage());
        }
    }
}