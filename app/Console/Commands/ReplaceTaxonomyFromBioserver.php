<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Clase;
use App\Models\Orden;
use App\Models\Familia;
use App\Models\Reino;
use Exception;

class ReplaceTaxonomyFromBioserver extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'replace:taxonomy-bioserver 
                            {--dry-run : Ejecutar en modo de prueba sin guardar cambios}
                            {--force : Forzar el reemplazo sin confirmación}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Reemplazar completamente los datos de taxonomía (clases, órdenes, familias) desde la base de datos bioserver_grt';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $isForced = $this->option('force');

        $this->info('=== REEMPLAZO COMPLETO DE TAXONOMÍA DESDE BIOSERVER_GRT ===');
        
        if ($isDryRun) {
            $this->warn('MODO DE PRUEBA - No se guardarán cambios');
        } else {
            $this->warn('¡ATENCIÓN! Este comando ELIMINARÁ todos los datos existentes de taxonomía.');
            $this->warn('Se reemplazarán completamente las tablas: clases, ordens, familias');
            
            if (!$isForced && !$this->confirm('¿Está seguro de que desea continuar?')) {
                $this->info('Operación cancelada.');
                return 0;
            }
        }

        try {
            // Verificar conexión a bioserver_grt
            $this->info('Verificando conexión a bioserver_grt...');
            $this->verifyBioserverConnection();
            $this->info('✓ Conexión exitosa');

            // Mostrar estadísticas actuales
            $this->showCurrentStats();

            if (!$isDryRun) {
                // Truncar tablas en orden correcto (respetando foreign keys)
                $this->info('\nTruncando tablas existentes...');
                $this->truncateTables();
                $this->info('✓ Tablas truncadas');
            }

            // Migrar datos
            $this->info('\nIniciando migración de datos...');
            
            $clasesStats = $this->migrateClases($isDryRun);
            $ordenesStats = $this->migrateOrdenes($isDryRun);
            $familiasStats = $this->migrateFamilias($isDryRun);

            // Mostrar resumen
            $this->info('\n=== RESUMEN DE MIGRACIÓN ===');
            $this->info("Clases - Migradas: {$clasesStats['migrated']}, Omitidas: {$clasesStats['skipped']}");
            $this->info("Órdenes - Migrados: {$ordenesStats['migrated']}, Omitidos: {$ordenesStats['skipped']}");
            $this->info("Familias - Migradas: {$familiasStats['migrated']}, Omitidas: {$familiasStats['skipped']}");
            
            if (!$isDryRun) {
                $this->showFinalStats();
            }
            
            $this->info('\n✓ Reemplazo de taxonomía completado exitosamente!');
            
        } catch (Exception $e) {
            $this->error('Error durante la migración: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Verificar conexión a bioserver_grt
     */
    private function verifyBioserverConnection()
    {
        $config = [
            'driver' => 'mysql',
            'host' => env('BIOSERVER_DB_HOST', '127.0.0.1'),
            'port' => env('BIOSERVER_DB_PORT', '3306'),
            'database' => env('BIOSERVER_DB_DATABASE', 'bioserver_grt'),
            'username' => env('BIOSERVER_DB_USERNAME', 'root'),
            'password' => env('BIOSERVER_DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ];

        config(['database.connections.bioserver' => $config]);
        
        try {
            DB::connection('bioserver')->getPdo();
        } catch (Exception $e) {
            throw new Exception("Error al conectar con bioserver_grt: {$e->getMessage()}");
        }

        // Verificar que las tablas existan
        $tables = ['clases', 'ordens', 'familias'];
        foreach ($tables as $table) {
            $exists = DB::connection('bioserver')
                ->select("SHOW TABLES LIKE '{$table}'");
            
            if (empty($exists)) {
                throw new Exception("No se encontró la tabla '{$table}' en bioserver_grt");
            }
        }
    }

    /**
     * Mostrar estadísticas actuales
     */
    private function showCurrentStats()
    {
        $this->info('\n=== ESTADÍSTICAS ACTUALES ===');
        $this->info('Clases actuales: ' . Clase::count());
        $this->info('Órdenes actuales: ' . Orden::count());
        $this->info('Familias actuales: ' . Familia::count());
    }

    /**
     * Mostrar estadísticas finales
     */
    private function showFinalStats()
    {
        $this->info('\n=== ESTADÍSTICAS FINALES ===');
        $this->info('Clases totales: ' . Clase::count());
        $this->info('Órdenes totales: ' . Orden::count());
        $this->info('Familias totales: ' . Familia::count());
    }

    /**
     * Truncar tablas en orden correcto
     */
    private function truncateTables()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        try {
            // Truncar en orden inverso a las dependencias
            DB::table('familias')->truncate();
            $this->info('  ✓ Tabla familias truncada');
            
            DB::table('ordens')->truncate();
            $this->info('  ✓ Tabla ordens truncada');
            
            DB::table('clases')->truncate();
            $this->info('  ✓ Tabla clases truncada');
            
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    /**
     * Migrar clases desde bioserver_grt
     */
    private function migrateClases($isDryRun = false)
    {
        $this->info('\nMigrando clases...');
        
        $clasesBioserver = DB::connection('bioserver')
            ->table('clases')
            ->select('idclase', 'nombre', 'definicion')
            ->get();

        $migrated = 0;
        $skipped = 0;
        $idMapping = [];

        foreach ($clasesBioserver as $claseBioserver) {
            $claseData = [
                'nombre' => $claseBioserver->nombre,
                'definicion' => $claseBioserver->definicion ?: 'Migrado desde bioserver_grt',
                'idreino' => null, // No existe en bioserver_grt
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (!$isDryRun) {
                $newClase = Clase::create($claseData);
                $idMapping[$claseBioserver->idclase] = $newClase->idclase;
                $this->info("  ✓ Clase: {$claseBioserver->nombre}");
            } else {
                $this->info("  [PRUEBA] Clase: {$claseBioserver->nombre}");
            }
            
            $migrated++;
        }

        // Guardar mapeo para uso posterior
        $this->clasesMapping = $idMapping;

        return ['migrated' => $migrated, 'skipped' => $skipped];
    }

    /**
     * Migrar órdenes desde bioserver_grt
     */
    private function migrateOrdenes($isDryRun = false)
    {
        $this->info('\nMigrando órdenes...');
        
        $ordenesBioserver = DB::connection('bioserver')
            ->table('ordens')
            ->select('idorden', 'nombre', 'definicion', 'idclase')
            ->get();

        $migrated = 0;
        $skipped = 0;
        $idMapping = [];

        foreach ($ordenesBioserver as $ordenBioserver) {
            // Mapear clase
            $idclase = null;
            if (isset($this->clasesMapping[$ordenBioserver->idclase])) {
                $idclase = $this->clasesMapping[$ordenBioserver->idclase];
            } else {
                $this->warn("  No se pudo mapear la clase para el orden '{$ordenBioserver->nombre}'");
                $skipped++;
                continue;
            }

            $ordenData = [
                'nombre' => $ordenBioserver->nombre,
                'definicion' => $ordenBioserver->definicion ?: 'Migrado desde bioserver_grt',
                'idclase' => $idclase,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (!$isDryRun) {
                $newOrden = Orden::create($ordenData);
                $idMapping[$ordenBioserver->idorden] = $newOrden->idorden;
                $this->info("  ✓ Orden: {$ordenBioserver->nombre}");
            } else {
                $this->info("  [PRUEBA] Orden: {$ordenBioserver->nombre}");
            }
            
            $migrated++;
        }

        // Guardar mapeo para uso posterior
        $this->ordenesMapping = $idMapping;

        return ['migrated' => $migrated, 'skipped' => $skipped];
    }

    /**
     * Migrar familias desde bioserver_grt
     */
    private function migrateFamilias($isDryRun = false)
    {
        $this->info('\nMigrando familias...');
        
        $familiasBioserver = DB::connection('bioserver')
            ->table('familias')
            ->select('idfamilia', 'nombre', 'definicion', 'idorden')
            ->get();

        $migrated = 0;
        $skipped = 0;

        foreach ($familiasBioserver as $familiaBioserver) {
            // Mapear orden
            $idorden = null;
            if (isset($this->ordenesMapping[$familiaBioserver->idorden])) {
                $idorden = $this->ordenesMapping[$familiaBioserver->idorden];
            } else {
                $this->warn("  No se pudo mapear el orden para la familia '{$familiaBioserver->nombre}'");
                $skipped++;
                continue;
            }

            $familiaData = [
                'nombre' => $familiaBioserver->nombre,
                'definicion' => $familiaBioserver->definicion ?: 'Migrado desde bioserver_grt',
                'idorden' => $idorden,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (!$isDryRun) {
                Familia::create($familiaData);
                $this->info("  ✓ Familia: {$familiaBioserver->nombre}");
            } else {
                $this->info("  [PRUEBA] Familia: {$familiaBioserver->nombre}");
            }
            
            $migrated++;
        }

        return ['migrated' => $migrated, 'skipped' => $skipped];
    }

    /**
     * Mapeos de IDs para mantener relaciones
     */
    private $clasesMapping = [];
    private $ordenesMapping = [];
}