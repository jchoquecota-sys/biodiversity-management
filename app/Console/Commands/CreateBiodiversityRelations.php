<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\BiodiversityCategory;
use App\Models\Publication;

class CreateBiodiversityRelations extends Command
{
    protected $signature = 'biodiversity:create-relations
                            {--dry-run : Solo mostrar análisis sin crear relaciones}
                            {--limit=50 : Limitar número de registros a procesar}
                            {--force : Forzar creación de relaciones}';

    protected $description = 'Crea relaciones entre detalle_estudio_general_biodiversidads y biodiversity_category_publication';

    public function handle()
    {
        $this->info('🔗 Creando relaciones entre tablas...');
        
        if (!$this->testExternalConnection()) {
            return 1;
        }

        // Analizar datos disponibles
        $this->analyzeAvailableData();
        
        // Crear mapeo de especies
        $speciesMapping = $this->createSpeciesMapping();
        
        if (empty($speciesMapping)) {
            $this->warn('⚠️  No se pudo crear mapeo de especies. Abortando.');
            return 1;
        }
        
        // Procesar relaciones
        $this->processRelations($speciesMapping);
        
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

    private function analyzeAvailableData()
    {
        $this->info('\n📊 Analizando datos disponibles...');
        
        // Contar registros en detalle_estudio_general_biodiversidads
        $detalleCount = DB::connection('external_bioserver')
            ->table('detalle_estudio_general_biodiversidads')
            ->count();
        
        $this->info("📋 Registros en detalle_estudio_general_biodiversidads: {$detalleCount}");
        
        // Contar publicaciones migradas
        $publicationsCount = Publication::count();
        $this->info("📚 Publicaciones locales: {$publicationsCount}");
        
        // Contar categorías de biodiversidad locales
        $categoriesCount = BiodiversityCategory::count();
        $this->info("🌿 Categorías de biodiversidad locales: {$categoriesCount}");
        
        // Contar relaciones existentes
        $existingRelations = DB::table('biodiversity_category_publication')->count();
        $this->info("🔗 Relaciones existentes: {$existingRelations}");
    }

    private function createSpeciesMapping()
    {
        $this->info('\n🗺️  Creando mapeo de especies...');
        
        $mapping = [];
        
        try {
            // Obtener especies de la base externa
            $externalSpecies = DB::connection('external_bioserver')
                ->table('biodiversidads')
                ->select('idbiodiversidad', 'especie', 'nombrecomun')
                ->where('estado', 'activo')
                ->get();
            
            $this->info("🔍 Especies externas encontradas: {$externalSpecies->count()}");
            
            foreach ($externalSpecies as $external) {
                // Buscar coincidencia por nombre científico
                $localCategory = BiodiversityCategory::where('scientific_name', 'LIKE', '%' . trim($external->especie) . '%')
                    ->first();
                
                if ($localCategory) {
                    $mapping[$external->idbiodiversidad] = $localCategory->id;
                    continue;
                }
                
                // Buscar por nombre común si no hay coincidencia científica
                if (!empty($external->nombrecomun)) {
                    $localCategory = BiodiversityCategory::where('common_name', 'LIKE', '%' . trim($external->nombrecomun) . '%')
                        ->first();
                    
                    if ($localCategory) {
                        $mapping[$external->idbiodiversidad] = $localCategory->id;
                    }
                }
            }
            
            $this->info("✅ Mapeo creado: " . count($mapping) . " coincidencias encontradas");
            
            // Mostrar algunas coincidencias
            if (!empty($mapping)) {
                $this->info('\n📋 Muestra de mapeo:');
                $count = 0;
                foreach ($mapping as $externalId => $localId) {
                    if ($count >= 5) break;
                    
                    $external = $externalSpecies->where('idbiodiversidad', $externalId)->first();
                    $local = BiodiversityCategory::find($localId);
                    
                    if ($external && $local) {
                        $this->line("   🎯 Externa: {$external->especie} -> Local: {$local->scientific_name}");
                        $count++;
                    }
                }
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Error creando mapeo: ' . $e->getMessage());
            return [];
        }
        
        return $mapping;
    }

    private function processRelations($speciesMapping)
    {
        $this->info('\n🔄 Procesando relaciones...');
        
        $limit = $this->option('limit');
        $isDryRun = $this->option('dry-run');
        
        try {
            // Obtener detalles con sus estudios
            $detalles = DB::connection('external_bioserver')
                ->table('detalle_estudio_general_biodiversidads as d')
                ->join('estudio_generals as e', 'd.idestudiogeneral', '=', 'e.idestudiogeneral')
                ->select('d.*', 'e.titulo')
                ->limit($limit)
                ->get();
            
            $this->info("📋 Procesando {$detalles->count()} registros...");
            
            $processed = 0;
            $created = 0;
            $skipped = 0;
            
            foreach ($detalles as $detalle) {
                $processed++;
                
                // Verificar si tenemos mapeo para esta biodiversidad
                if (!isset($speciesMapping[$detalle->idbiodiversidad])) {
                    $skipped++;
                    continue;
                }
                
                $localCategoryId = $speciesMapping[$detalle->idbiodiversidad];
                
                // Buscar la publicación local correspondiente
                $publication = Publication::where('title', 'LIKE', '%' . substr($detalle->titulo, 0, 50) . '%')
                    ->first();
                
                if (!$publication) {
                    $skipped++;
                    continue;
                }
                
                // Verificar si la relación ya existe
                $existingRelation = DB::table('biodiversity_category_publication')
                    ->where('biodiversity_category_id', $localCategoryId)
                    ->where('publication_id', $publication->id)
                    ->exists();
                
                if ($existingRelation) {
                    $skipped++;
                    continue;
                }
                
                if ($isDryRun) {
                    $this->line("   🧪 [DRY RUN] Crearía relación: Categoría {$localCategoryId} -> Publicación {$publication->id} ({$publication->title})");
                } else {
                    // Crear la relación
                    DB::table('biodiversity_category_publication')->insert([
                        'biodiversity_category_id' => $localCategoryId,
                        'publication_id' => $publication->id,
                        'relevant_excerpt' => 'Relación migrada desde detalle_estudio_general_biodiversidads',
                        'page_reference' => null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    
                    $created++;
                }
            }
            
            // Mostrar resumen
            $this->info('\n📊 Resumen del procesamiento:');
            $this->line("   📋 Registros procesados: {$processed}");
            $this->line("   ✅ Relaciones creadas: {$created}");
            $this->line("   ⏭️  Registros omitidos: {$skipped}");
            
            if ($isDryRun) {
                $this->warn('\n🧪 Modo de prueba activado - No se crearon relaciones reales.');
                $this->info('💡 Ejecuta sin --dry-run para crear las relaciones.');
            } else {
                $this->info('\n✅ Proceso completado exitosamente.');
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Error procesando relaciones: ' . $e->getMessage());
        }
    }
}