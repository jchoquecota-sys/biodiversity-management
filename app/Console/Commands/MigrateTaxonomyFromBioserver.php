<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Reino;
use App\Models\Clase;
use App\Models\Orden;
use App\Models\Familia;
use Exception;

class MigrateTaxonomyFromBioserver extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:taxonomy-bioserver {--dry-run : Ejecutar en modo de prueba sin guardar cambios}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrar datos de taxonomía (clases, órdenes, familias) desde la base de datos bioserver_grt';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando migración de taxonomía desde bioserver_grt...');
        
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->warn('MODO DE PRUEBA ACTIVADO - No se guardarán cambios');
        }

        try {
            // Configurar conexión a bioserver_grt
            $this->setupBioserverConnection();
            
            // Verificar conexión
            $this->verifyConnection();
            
            // Migrar datos
            $this->migrateClases($dryRun);
            $this->migrateOrdens($dryRun);
            $this->migrateFamilias($dryRun);
            
            $this->info('Migración completada exitosamente!');
            
        } catch (Exception $e) {
            $this->error('Error durante la migración: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
    
    /**
     * Configurar conexión a bioserver_grt
     */
    private function setupBioserverConnection()
    {
        config([
            'database.connections.bioserver_grt' => [
                'driver' => 'mysql',
                'host' => env('BIOSERVER_DB_HOST', 'localhost'),
                'port' => env('BIOSERVER_DB_PORT', '3306'),
                'database' => env('BIOSERVER_DB_DATABASE', 'bioserver_grt'),
                'username' => env('BIOSERVER_DB_USERNAME', 'root'),
                'password' => env('BIOSERVER_DB_PASSWORD', ''),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => null,
            ]
        ]);
    }
    
    /**
     * Verificar conexión a bioserver_grt
     */
    private function verifyConnection()
    {
        $this->info('Verificando conexión a bioserver_grt...');
        
        try {
            $tables = DB::connection('bioserver_grt')
                ->select('SHOW TABLES LIKE "clases"');
                
            if (empty($tables)) {
                throw new Exception('No se encontró la tabla "clases" en bioserver_grt');
            }
            
            $this->info('Conexión establecida correctamente.');
            
        } catch (Exception $e) {
            throw new Exception('Error al conectar con bioserver_grt: ' . $e->getMessage());
        }
    }
    
    /**
     * Migrar clases desde bioserver_grt
     */
    private function migrateClases($dryRun = false)
    {
        $this->info('Migrando clases...');
        
        // Obtener clases desde bioserver_grt
        $clasesSource = DB::connection('bioserver_grt')
            ->table('clases')
            ->select('*')
            ->get();
            
        $this->info("Encontradas {$clasesSource->count()} clases en bioserver_grt");
        
        $migrated = 0;
        $skipped = 0;
        
        foreach ($clasesSource as $claseSource) {
            // Verificar si ya existe
            $existing = Clase::where('nombre', $claseSource->nombre)->first();
            
            if ($existing) {
                $this->warn("Clase '{$claseSource->nombre}' ya existe, omitiendo...");
                $skipped++;
                continue;
            }
            
            if (!$dryRun) {
                // Crear nueva clase
                Clase::create([
                    'nombre' => $claseSource->nombre,
                    'definicion' => $claseSource->definicion ?? 'Migrado desde bioserver_grt',
                    'idreino' => $this->mapReinoId($claseSource)
                ]);
            }
            
            $this->line("✓ Clase: {$claseSource->nombre}");
            $migrated++;
        }
        
        $this->info("Clases - Migradas: {$migrated}, Omitidas: {$skipped}");
    }
    
    /**
     * Migrar órdenes desde bioserver_grt
     */
    private function migrateOrdens($dryRun = false)
    {
        $this->info('Migrando órdenes...');
        
        // Obtener órdenes desde bioserver_grt
        $ordensSource = DB::connection('bioserver_grt')
            ->table('ordens')
            ->select('*')
            ->get();
            
        $this->info("Encontrados {$ordensSource->count()} órdenes en bioserver_grt");
        
        $migrated = 0;
        $skipped = 0;
        
        foreach ($ordensSource as $ordenSource) {
            // Verificar si ya existe
            $existing = Orden::where('nombre', $ordenSource->nombre)->first();
            
            if ($existing) {
                $this->warn("Orden '{$ordenSource->nombre}' ya existe, omitiendo...");
                $skipped++;
                continue;
            }
            
            // Buscar la clase correspondiente
            $claseId = $this->mapClaseId($ordenSource);
            
            if (!$claseId) {
                $this->error("No se pudo mapear la clase para el orden '{$ordenSource->nombre}'");
                $skipped++;
                continue;
            }
            
            if (!$dryRun) {
                // Crear nuevo orden
                Orden::create([
                    'nombre' => $ordenSource->nombre,
                    'definicion' => $ordenSource->definicion ?? 'Migrado desde bioserver_grt',
                    'idclase' => $claseId
                ]);
            }
            
            $this->line("✓ Orden: {$ordenSource->nombre}");
            $migrated++;
        }
        
        $this->info("Órdenes - Migrados: {$migrated}, Omitidos: {$skipped}");
    }
    
    /**
     * Migrar familias desde bioserver_grt
     */
    private function migrateFamilias($dryRun = false)
    {
        $this->info('Migrando familias...');
        
        // Obtener familias desde bioserver_grt
        $familiasSource = DB::connection('bioserver_grt')
            ->table('familias')
            ->select('*')
            ->get();
            
        $this->info("Encontradas {$familiasSource->count()} familias en bioserver_grt");
        
        $migrated = 0;
        $skipped = 0;
        
        foreach ($familiasSource as $familiaSource) {
            // Verificar si ya existe
            $existing = Familia::where('nombre', $familiaSource->nombre)->first();
            
            if ($existing) {
                $this->warn("Familia '{$familiaSource->nombre}' ya existe, omitiendo...");
                $skipped++;
                continue;
            }
            
            // Buscar el orden correspondiente
            $ordenId = $this->mapOrdenId($familiaSource);
            
            if (!$ordenId) {
                $this->error("No se pudo mapear el orden para la familia '{$familiaSource->nombre}'");
                $skipped++;
                continue;
            }
            
            if (!$dryRun) {
                // Crear nueva familia
                Familia::create([
                    'nombre' => $familiaSource->nombre,
                    'definicion' => $familiaSource->definicion ?? 'Migrado desde bioserver_grt',
                    'idorden' => $ordenId
                ]);
            }
            
            $this->line("✓ Familia: {$familiaSource->nombre}");
            $migrated++;
        }
        
        $this->info("Familias - Migradas: {$migrated}, Omitidas: {$skipped}");
    }
    
    /**
     * Mapear ID de reino desde bioserver_grt al proyecto actual
     */
    private function mapReinoId($claseSource)
    {
        // Si existe campo idreino en bioserver_grt, intentar mapear
        if (isset($claseSource->idreino)) {
            // Obtener nombre del reino desde bioserver_grt
            $reinoSource = DB::connection('bioserver_grt')
                ->table('reinos')
                ->where('id', $claseSource->idreino)
                ->first();
                
            if ($reinoSource) {
                // Buscar reino correspondiente en el proyecto actual
                $reino = Reino::where('nombre', $reinoSource->nombre)->first();
                return $reino ? $reino->id : null;
            }
        }
        
        return null; // Reino por defecto o null
    }
    
    /**
     * Mapear ID de clase desde bioserver_grt al proyecto actual
     */
    private function mapClaseId($ordenSource)
    {
        if (isset($ordenSource->idclase)) {
            // Obtener nombre de la clase desde bioserver_grt
            $claseSource = DB::connection('bioserver_grt')
                ->table('clases')
                ->where('idclase', $ordenSource->idclase)
                ->first();
                
            if ($claseSource) {
                // Buscar clase correspondiente en el proyecto actual
                $clase = Clase::where('nombre', $claseSource->nombre)->first();
                return $clase ? $clase->idclase : null;
            }
        }
        
        return null;
    }
    
    /**
     * Mapear ID de orden desde bioserver_grt al proyecto actual
     */
    private function mapOrdenId($familiaSource)
    {
        if (isset($familiaSource->idorden)) {
            // Obtener nombre del orden desde bioserver_grt
            $ordenSource = DB::connection('bioserver_grt')
                ->table('ordens')
                ->where('idorden', $familiaSource->idorden)
                ->first();
                
            if ($ordenSource) {
                // Buscar orden correspondiente en el proyecto actual
                $orden = Orden::where('nombre', $ordenSource->nombre)->first();
                return $orden ? $orden->idorden : null;
            }
        }
        
        return null;
    }
}