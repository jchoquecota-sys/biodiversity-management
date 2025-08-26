<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\BiodiversityCategory;
use App\Models\Publication;

class AnalyzeDetalleEstudioBiodiversidad extends Command
{
    protected $signature = 'biodiversity:analyze-detalle-estudio
                            {--limit=10 : Limitar número de registros a analizar}
                            {--dry-run : Solo mostrar análisis sin crear relaciones}';

    protected $description = 'Analiza la tabla detalle_estudio_general_biodiversidads y su relación con biodiversity_categories';

    public function handle()
    {
        $this->info('🔍 Analizando tabla detalle_estudio_general_biodiversidads...');
        
        // Verificar conexión a base de datos externa
        if (!$this->testExternalConnection()) {
            return 1;
        }

        // Analizar estructura de la tabla detalle
        $this->analyzeDetalleTable();
        
        // Obtener datos de muestra
        $this->analyzeDetalleData();
        
        // Analizar relación con categorías de biodiversidad
        $this->analyzeRelationWithCategories();
        
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

    private function analyzeDetalleTable()
    {
        try {
            $this->info('\n📋 Analizando estructura de detalle_estudio_general_biodiversidads...');
            
            // Verificar si la tabla existe
            $tables = DB::connection('external_bioserver')
                ->select("SHOW TABLES LIKE 'detalle_estudio_general_biodiversidads'");
            
            if (empty($tables)) {
                $this->warn('⚠️  Tabla detalle_estudio_general_biodiversidads no encontrada.');
                
                // Buscar tablas similares
                $this->info('🔍 Buscando tablas similares...');
                $allTables = DB::connection('external_bioserver')->select('SHOW TABLES');
                
                foreach ($allTables as $table) {
                    $tableName = array_values((array)$table)[0];
                    if (stripos($tableName, 'detalle') !== false || stripos($tableName, 'biodiversidad') !== false) {
                        $this->line('   - ' . $tableName);
                    }
                }
                return;
            }
            
            // Mostrar estructura de la tabla
            $columns = DB::connection('external_bioserver')
                ->select('DESCRIBE detalle_estudio_general_biodiversidads');
            
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

    private function analyzeDetalleData()
    {
        try {
            $limit = $this->option('limit');
            
            $this->info("\n📊 Obteniendo datos de muestra (límite: {$limit})...");
            
            $detalles = DB::connection('external_bioserver')
                ->table('detalle_estudio_general_biodiversidads')
                ->limit($limit)
                ->get();
            
            if ($detalles->isEmpty()) {
                $this->warn('⚠️  No se encontraron datos en la tabla.');
                return;
            }
            
            $this->info("📈 Se encontraron {$detalles->count()} registros de muestra.");
            
            // Mostrar muestra de datos
            foreach ($detalles->take(3) as $index => $detalle) {
                $this->line("\n--- Registro " . ($index + 1) . " ---");
                foreach ($detalle as $field => $value) {
                    $displayValue = is_null($value) ? '(null)' : (strlen($value) > 100 ? substr($value, 0, 100) . '...' : $value);
                    $this->line("  {$field}: {$displayValue}");
                }
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Error obteniendo datos: ' . $e->getMessage());
        }
    }

    private function analyzeRelationWithCategories()
    {
        try {
            $this->info('\n🔗 Analizando relación con categorías de biodiversidad...');
            
            // Obtener categorías de biodiversidad locales
            $localCategories = BiodiversityCategory::select('id', 'scientific_name', 'common_name')
                ->get();
            
            $this->info("📋 Categorías locales encontradas: {$localCategories->count()}");
            
            if ($localCategories->count() > 0) {
                $this->line('\n🌿 Muestra de categorías locales:');
                foreach ($localCategories->take(5) as $category) {
                    $this->line("   - ID: {$category->id} | Científico: {$category->scientific_name} | Común: {$category->common_name}");
                }
            }
            
            // Verificar si existe algún campo que pueda relacionarse
            $this->info('\n🔍 Buscando posibles campos de relación...');
            
            $detalles = DB::connection('external_bioserver')
                ->table('detalle_estudio_general_biodiversidads')
                ->limit(5)
                ->get();
            
            if (!$detalles->isEmpty()) {
                $firstRecord = $detalles->first();
                $possibleFields = [];
                
                foreach ($firstRecord as $field => $value) {
                    if (stripos($field, 'especie') !== false || 
                        stripos($field, 'categoria') !== false ||
                        stripos($field, 'biodiversidad') !== false ||
                        stripos($field, 'taxonom') !== false ||
                        stripos($field, 'scientific') !== false ||
                        stripos($field, 'nombre') !== false) {
                        $possibleFields[] = $field;
                    }
                }
                
                if (!empty($possibleFields)) {
                    $this->info('🎯 Campos potenciales para relación encontrados:');
                    foreach ($possibleFields as $field) {
                        $this->line("   - {$field}");
                    }
                } else {
                    $this->warn('⚠️  No se encontraron campos obvios para relacionar.');
                }
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Error analizando relaciones: ' . $e->getMessage());
        }
    }
}