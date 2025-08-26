<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Exception;

class InspectProblematicRecord extends Command
{
    protected $signature = 'inspect:problematic-record 
                            {--host= : Host de la base de datos externa}
                            {--port=3306 : Puerto de la base de datos externa}
                            {--database=bioserver_grt : Nombre de la base de datos externa}
                            {--username= : Usuario de la base de datos externa}
                            {--password= : Contraseña de la base de datos externa}
                            {--id=499 : ID del registro a inspeccionar}';

    protected $description = 'Inspecciona un registro específico de biodiversidads';

    public function handle()
    {
        $host = $this->option('host') ?? 'localhost';
        $username = $this->option('username') ?? 'root';
        $password = $this->option('password') ?? '';
        $database = $this->option('database') ?? 'bioserver_grt';
        $port = $this->option('port') ?? '3306';
        $recordId = $this->option('id');
        
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
            
            // Obtener el registro específico
            $record = DB::connection('external')
                ->table('biodiversidads')
                ->where('idbiodiversidad', $recordId)
                ->first();
            
            if (!$record) {
                $this->error('No se encontró el registro con ID: ' . $recordId);
                return 1;
            }
            
            $this->info('\n=== REGISTRO ID: ' . $recordId . ' ===');
            
            foreach ((array)$record as $field => $value) {
                $length = strlen($value ?? '');
                $this->line(sprintf('%-20s: %s (longitud: %d)', $field, 
                    $length > 100 ? substr($value, 0, 100) . '...' : $value, 
                    $length
                ));
            }
            
            // Verificar campos problemáticos
            $this->info('\n=== ANÁLISIS DE LONGITUDES ===');
            $problematicFields = [];
            
            $fieldsToCheck = [
                'nombrecomun' => 255,
                'especie' => 255,
                'categoria' => 255
            ];
            
            foreach ($fieldsToCheck as $field => $maxLength) {
                $value = $record->$field ?? '';
                $length = strlen($value);
                if ($length > $maxLength) {
                    $problematicFields[] = $field;
                    $this->error(sprintf('%s: %d caracteres (máximo: %d)', $field, $length, $maxLength));
                } else {
                    $this->info(sprintf('%s: %d caracteres (OK)', $field, $length));
                }
            }
            
            if (empty($problematicFields)) {
                $this->info('No se encontraron campos problemáticos en este registro.');
            }
            
        } catch (Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}