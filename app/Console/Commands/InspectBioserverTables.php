<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Exception;

class InspectBioserverTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inspect:bioserver-tables {table?} {--count : Mostrar conteo de registros}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inspeccionar estructura y datos de las tablas en bioserver_grt';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Inspeccionando tablas en bioserver_grt...');
        
        $table = $this->argument('table');
        $showCount = $this->option('count');

        try {
            // Configurar conexión a bioserver_grt
            $this->setupBioserverConnection();
            
            // Verificar conexión
            $this->verifyConnection();
            
            if ($table) {
                $this->inspectTable($table, $showCount);
            } else {
                $this->inspectAllTables($showCount);
            }
            
        } catch (Exception $e) {
            $this->error('Error durante la inspección: ' . $e->getMessage());
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
            DB::connection('bioserver_grt')->getPdo();
            $this->info('✓ Conexión establecida correctamente.');
            
        } catch (Exception $e) {
            throw new Exception('Error al conectar con bioserver_grt: ' . $e->getMessage());
        }
    }
    
    /**
     * Inspeccionar todas las tablas relevantes
     */
    private function inspectAllTables($showCount = false)
    {
        $tables = ['reinos', 'clases', 'ordens', 'familias'];
        
        foreach ($tables as $table) {
            $this->inspectTable($table, $showCount);
            $this->line('');
        }
    }
    
    /**
     * Inspeccionar una tabla específica
     */
    private function inspectTable($tableName, $showCount = false)
    {
        $this->info("=== Tabla: {$tableName} ===");
        
        try {
            // Verificar si la tabla existe
            $tableExists = DB::connection('bioserver_grt')
                ->select("SHOW TABLES LIKE '{$tableName}'");
                
            if (empty($tableExists)) {
                $this->warn("La tabla '{$tableName}' no existe en bioserver_grt");
                return;
            }
            
            // Mostrar estructura de la tabla
            $this->showTableStructure($tableName);
            
            // Mostrar conteo si se solicita
            if ($showCount) {
                $this->showRecordCount($tableName);
            }
            
            // Mostrar algunos registros de ejemplo
            $this->showSampleRecords($tableName);
            
        } catch (Exception $e) {
            $this->error("Error al inspeccionar tabla '{$tableName}': " . $e->getMessage());
        }
    }
    
    /**
     * Mostrar estructura de la tabla
     */
    private function showTableStructure($tableName)
    {
        $this->line('Estructura:');
        
        $columns = DB::connection('bioserver_grt')
            ->select("DESCRIBE {$tableName}");
            
        $headers = ['Campo', 'Tipo', 'Nulo', 'Clave', 'Default', 'Extra'];
        $rows = [];
        
        foreach ($columns as $column) {
            $rows[] = [
                $column->Field,
                $column->Type,
                $column->Null,
                $column->Key,
                $column->Default ?? 'NULL',
                $column->Extra
            ];
        }
        
        $this->table($headers, $rows);
    }
    
    /**
     * Mostrar conteo de registros
     */
    private function showRecordCount($tableName)
    {
        $count = DB::connection('bioserver_grt')
            ->table($tableName)
            ->count();
            
        $this->info("Total de registros: {$count}");
    }
    
    /**
     * Mostrar registros de ejemplo
     */
    private function showSampleRecords($tableName)
    {
        $this->line('Registros de ejemplo (primeros 5):');
        
        $records = DB::connection('bioserver_grt')
            ->table($tableName)
            ->limit(5)
            ->get();
            
        if ($records->isEmpty()) {
            $this->warn('No hay registros en esta tabla');
            return;
        }
        
        // Convertir a array para mostrar en tabla
        $headers = array_keys((array) $records->first());
        $rows = [];
        
        foreach ($records as $record) {
            $row = [];
            foreach ($headers as $header) {
                $value = $record->$header;
                // Truncar valores largos
                if (is_string($value) && strlen($value) > 50) {
                    $value = substr($value, 0, 47) . '...';
                }
                $row[] = $value;
            }
            $rows[] = $row;
        }
        
        $this->table($headers, $rows);
    }
}