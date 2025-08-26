<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class MigrateEstudioGenerals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'biodiversity:migrate-estudio-generals 
                            {--limit=100 : Número máximo de registros a migrar}
                            {--dry-run : Ejecutar en modo de prueba sin insertar datos}
                            {--force : Forzar migración incluso si ya existen registros}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migra datos de la tabla estudio_generals a la tabla publications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Iniciando migración de estudio_generals a publications...');
        
        $limit = $this->option('limit');
        $dryRun = $this->option('dry-run');
        $force = $this->option('force');
        
        try {
            // Verificar conexión a base de datos externa
            $this->info('📡 Verificando conexión a base de datos externa...');
            $this->testExternalConnection();
            
            // Obtener estructura de la tabla estudio_generals
            $this->info('🔍 Analizando estructura de tabla estudio_generals...');
            $this->analyzeTableStructure();
            
            // Obtener datos de estudio_generals
            $this->info('📊 Obteniendo datos de estudio_generals...');
            $estudios = $this->getEstudioGeneralsData($limit);
            
            if ($estudios->isEmpty()) {
                $this->warn('⚠️  No se encontraron datos en la tabla estudio_generals.');
                return 0;
            }
            
            $this->info("📈 Se encontraron {$estudios->count()} registros para migrar.");
            
            if ($dryRun) {
                $this->info('🧪 Modo de prueba activado - No se insertarán datos.');
                $this->displaySampleData($estudios->take(5));
                return 0;
            }
            
            // Verificar si ya existen publicaciones
            $existingCount = DB::table('publications')->count();
            if ($existingCount > 0 && !$force) {
                $this->warn("⚠️  Ya existen {$existingCount} publicaciones. Use --force para continuar.");
                return 1;
            }
            
            // Migrar datos
            $this->info('🚀 Iniciando migración de datos...');
            $migrated = $this->migrateData($estudios);
            
            $this->info("✅ Migración completada exitosamente: {$migrated} registros migrados.");
            
        } catch (Exception $e) {
            $this->error('❌ Error durante la migración: ' . $e->getMessage());
            Log::error('Error en migración estudio_generals', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
        
        return 0;
    }
    
    /**
     * Probar conexión a base de datos externa
     */
    private function testExternalConnection()
    {
        try {
            $connection = DB::connection('external_bioserver');
            $connection->getPdo();
            $this->info('✅ Conexión a base de datos externa exitosa.');
        } catch (Exception $e) {
            throw new Exception('No se pudo conectar a la base de datos externa: ' . $e->getMessage());
        }
    }
    
    /**
     * Analizar estructura de la tabla estudio_generals
     */
    private function analyzeTableStructure()
    {
        try {
            $columns = DB::connection('external_bioserver')
                ->select('DESCRIBE estudio_generals');
            
            $this->info('📋 Estructura de tabla estudio_generals:');
            $headers = ['Campo', 'Tipo', 'Nulo', 'Clave', 'Default', 'Extra'];
            $rows = [];
            
            foreach ($columns as $column) {
                $rows[] = [
                    $column->Field,
                    $column->Type,
                    $column->Null,
                    $column->Key ?? '',
                    $column->Default ?? '',
                    $column->Extra ?? ''
                ];
            }
            
            $this->table($headers, $rows);
            
        } catch (Exception $e) {
            throw new Exception('Error al analizar estructura de tabla: ' . $e->getMessage());
        }
    }
    
    /**
     * Obtener datos de estudio_generals
     */
    private function getEstudioGeneralsData($limit)
    {
        return DB::connection('external_bioserver')
            ->table('estudio_generals')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Mostrar datos de muestra
     */
    private function displaySampleData($sample)
    {
        $this->info('📋 Muestra de datos a migrar:');
        
        foreach ($sample as $index => $record) {
            $this->info("\n--- Registro " . ($index + 1) . " ---");
            foreach ($record as $field => $value) {
                $this->line("  {$field}: {$value}");
            }
        }
    }
    
    /**
     * Migrar datos a la tabla publications
     */
    private function migrateData($estudios)
    {
        $migrated = 0;
        $bar = $this->output->createProgressBar($estudios->count());
        $bar->start();
        
        foreach ($estudios as $estudio) {
            try {
                $publicationData = $this->mapEstudioToPublication($estudio);
                
                DB::table('publications')->insert($publicationData);
                $migrated++;
                
            } catch (Exception $e) {
                $this->error("\n❌ Error al migrar registro ID {$estudio->id}: " . $e->getMessage());
                Log::error('Error al migrar registro individual', [
                    'estudio_id' => $estudio->id ?? 'unknown',
                    'error' => $e->getMessage()
                ]);
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        
        return $migrated;
    }
    
    /**
     * Mapear campos de estudio_generals a publications
     */
    private function mapEstudioToPublication($estudio)
    {
        // Mapeo basado en la estructura real de estudio_generals
        $abstract = $this->generateAbstract($estudio);
        
        return [
            'title' => $estudio->titulo ?? 'Título no disponible',
            'abstract' => $abstract,
            'publication_year' => $estudio->anio ?? date('Y'),
            'author' => $estudio->autor ?? 'Autor no especificado',
            'journal' => $estudio->institucion ?? null,
            'doi' => null, // No disponible en estudio_generals
            'pdf_path' => $estudio->ruta ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
    
    /**
     * Generar abstract combinando información disponible
     */
    private function generateAbstract($estudio)
    {
        $parts = [];
        
        if (!empty($estudio->descripcion) && $estudio->descripcion !== '(1).pdf' && $estudio->descripcion !== '(2).pdf' && $estudio->descripcion !== '(3).pdf' && $estudio->descripcion !== '(4).pdf' && $estudio->descripcion !== '(5).pdf') {
            $parts[] = $estudio->descripcion;
        }
        
        if (!empty($estudio->lugar)) {
            $parts[] = "Lugar de estudio: {$estudio->lugar}";
        }
        
        if (!empty($estudio->anio)) {
            $parts[] = "Año de publicación: {$estudio->anio}";
        }
        
        if (!empty($estudio->edicion)) {
            $parts[] = "Edición: {$estudio->edicion}";
        }
        
        if (empty($parts)) {
            return 'Estudio de biodiversidad realizado en el marco de investigación científica.';
        }
        
        return implode('. ', $parts) . '.';
    }
}