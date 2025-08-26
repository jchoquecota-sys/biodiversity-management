<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Exception;

class InspectBiodiversidadsTable extends Command
{
    protected $signature = 'inspect:biodiversidads-table 
                            {--host= : Host de la base de datos externa}
                            {--port=3306 : Puerto de la base de datos externa}
                            {--database=bioserver_grt : Nombre de la base de datos externa}
                            {--username= : Usuario de la base de datos externa}
                            {--password= : Contraseña de la base de datos externa}';

    protected $description = 'Inspecciona la estructura de la tabla biodiversidads';

    public function handle()
    {
        $host = $this->option('host') ?? 'localhost';
        $username = $this->option('username') ?? 'root';
        $password = $this->option('password') ?? '';
        $database = $this->option('database') ?? 'bioserver_grt';
        $port = $this->option('port') ?? '3306';
        
        // Configurar conexión externa
        Config::set('database.connections.external', [
            'driver' => 'mysql',
            'host' => $host,
            'port' => $port,
            'database' => $database,
            'username' => $username,
            'password' => $password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        
        try {
            // Probar conexión
            DB::connection('external')->getPdo();
            $this->info('✓ Conexión establecida correctamente.');
            
            // Obtener estructura de la tabla
            $this->info('\n=== ESTRUCTURA DE LA TABLA biodiversidads ===');
            $columns = DB::connection('external')->select('DESCRIBE biodiversidads');
            
            $headers = ['Campo', 'Tipo', 'Nulo', 'Clave', 'Default', 'Extra'];
            $rows = [];
            
            foreach ($columns as $column) {
                $rows[] = [
                    $column->Field,
                    $column->Type,
                    $column->Null,
                    $column->Key ?? '',
                    $column->Default ?? 'NULL',
                    $column->Extra ?? ''
                ];
            }
            
            $this->table($headers, $rows);
            
            // Obtener algunos registros de muestra
            $this->info('\n=== MUESTRA DE DATOS ===');
            $sample = DB::connection('external')
                ->table('biodiversidads')
                ->limit(3)
                ->get();
            
            if ($sample->isNotEmpty()) {
                $firstRecord = $sample->first();
                $sampleHeaders = array_keys((array)$firstRecord);
                $sampleRows = [];
                
                foreach ($sample as $record) {
                    $sampleRows[] = array_values((array)$record);
                }
                
                $this->table($sampleHeaders, $sampleRows);
            } else {
                $this->warn('No hay datos en la tabla.');
            }
            
        } catch (Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}