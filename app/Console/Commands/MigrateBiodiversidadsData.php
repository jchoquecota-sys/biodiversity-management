<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Models\BiodiversityCategory;
use Exception;

class MigrateBiodiversidadsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:biodiversidads-data 
                            {--host= : Host de la base de datos externa}
                            {--port=3306 : Puerto de la base de datos externa}
                            {--database=bioserver_grt : Nombre de la base de datos externa}
                            {--username= : Usuario de la base de datos externa}
                            {--password= : Contraseña de la base de datos externa}
                            {--preview : Solo mostrar vista previa sin migrar}
                            {--force : Forzar migración sin confirmación}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migra datos de la tabla biodiversidads de bioserver_grt a biodiversity_categories';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== MIGRACIÓN DE DATOS: bioserver_grt.biodiversidads -> biodiversity_categories ===');
        
        // Configurar conexión a base de datos externa
        if (!$this->setupExternalConnection()) {
            return 1;
        }
        
        try {
            // Obtener datos de la tabla externa
            $externalData = $this->getExternalData();
            
            if ($externalData->isEmpty()) {
                $this->warn('No se encontraron datos en la tabla biodiversidads.');
                return 0;
            }
            
            $this->info("Se encontraron {$externalData->count()} registros para migrar.");
            
            // Mostrar vista previa
            if ($this->option('preview')) {
                $this->showPreview($externalData);
                return 0;
            }
            
            // Confirmar migración
            if (!$this->option('force') && !$this->confirm('¿Desea proceder con la migración?')) {
                $this->info('Migración cancelada.');
                return 0;
            }
            
            // Ejecutar migración
            $this->executeMigration($externalData);
            
        } catch (Exception $e) {
            $this->error('Error durante la migración: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
    
    /**
     * Configura la conexión a la base de datos externa
     */
    private function setupExternalConnection(): bool
    {
        $host = $this->option('host') ?? 'localhost';
        $username = $this->option('username') ?? 'root';
        $password = $this->option('password') ?? '';
        $database = $this->option('database') ?? 'bioserver_grt';
        $port = $this->option('port') ?? '3306';
        
        if (!$host || !$username) {
            $this->error('Host y usuario son requeridos.');
            return false;
        }
        
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
        
        // Probar conexión
        try {
            DB::connection('external')->getPdo();
            $this->info('✓ Conexión a base de datos externa establecida correctamente.');
            return true;
        } catch (Exception $e) {
            $this->error('✗ Error al conectar con la base de datos externa: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtiene los datos de la tabla externa
     */
    private function getExternalData()
    {
        $this->info('Obteniendo datos de bioserver_grt.biodiversidads...');
        
        return DB::connection('external')
            ->table('biodiversidads')
            ->select('*')
            ->get();
    }
    
    /**
     * Muestra vista previa de los datos a migrar
     */
    private function showPreview($data)
    {
        $this->info('\n=== VISTA PREVIA DE MIGRACIÓN ===');
        
        $headers = ['ID', 'Especie', 'Nombre Común', 'Categoría', 'Estado'];
        $rows = [];
        
        foreach ($data->take(10) as $record) {
            $rows[] = [
                $record->idbiodiversidad ?? 'N/A',
                $record->especie ?? 'N/A',
                $record->nombrecomun ?? 'N/A',
                $record->categoria ?? 'N/A',
                $record->estado ?? 'N/A'
            ];
        }
        
        $this->table($headers, $rows);
        
        if ($data->count() > 10) {
            $this->info('... y ' . ($data->count() - 10) . ' registros más.');
        }
        
        $this->info('\n=== MAPEO DE CAMPOS ===');
        $this->line('biodiversidads.idbiodiversidad -> biodiversity_categories.id (no se usa)');
        $this->line('biodiversidads.especie -> biodiversity_categories.scientific_name');
        $this->line('biodiversidads.nombrecomun -> biodiversity_categories.name');
        $this->line('biodiversidads.nombrecomun -> biodiversity_categories.common_name');
        $this->line('biodiversidads.categoria -> biodiversity_categories.conservation_status');
        $this->line('biodiversidads.resumenespecie -> biodiversity_categories.description');
        $this->line('biodiversidads.estado -> usado para filtrar registros activos');
    }
    
    /**
     * Ejecuta la migración de datos
     */
    private function executeMigration($data)
    {
        $this->info('\n=== INICIANDO MIGRACIÓN ===');
        
        $bar = $this->output->createProgressBar($data->count());
        $bar->start();
        
        $migrated = 0;
        $skipped = 0;
        $errors = 0;
        
        DB::beginTransaction();
        
        try {
            foreach ($data as $record) {
                try {
                    // Solo migrar registros activos
                    if ($record->estado !== 'activo') {
                        $skipped++;
                        continue;
                    }
                    
                    // Verificar si ya existe
                    $existing = BiodiversityCategory::where('scientific_name', $record->especie)
                        ->orWhere('name', $record->nombrecomun)
                        ->first();
                    
                    if ($existing) {
                        $skipped++;
                    } else {
                        // Crear nuevo registro
                        // Función para truncar campos de texto
                $truncateString = function($value, $maxLength = 255) {
                    if (!$value) return $value;
                    return strlen($value) > $maxLength ? substr($value, 0, $maxLength) : $value;
                };
                        
                        $name = $truncateString($record->nombrecomun ?? 'Sin nombre');
                        $scientificName = $truncateString($record->especie ?? 'Sin nombre científico');
                        $commonName = $truncateString($record->nombrecomun);
                        // Mapear estado de conservación
                        $conservationStatusId = $this->mapConservationStatus($record->categoria ?? 'No evaluado');
                        $kingdom = $truncateString('Animalia');
                        
                        // Obtener código de conservación para el campo varchar(2)
                        $conservationCode = $this->getConservationCode($record->categoria ?? 'No evaluado');
                        
                        BiodiversityCategory::create([
                            'name' => $name,
                            'scientific_name' => $scientificName,
                            'common_name' => $commonName,
                            'description' => $record->resumenespecie ?? null, // text field, no limit
                            'conservation_status' => $conservationCode,
                            'conservation_status_id' => $conservationStatusId,
                            'kingdom' => $kingdom,
                            'habitat' => null,
                            'image_path' => null,
                            'image_path_2' => null,
                            'image_path_3' => null,
                            'image_path_4' => null,
                        ]);
                        $migrated++;
                    }
                } catch (Exception $e) {
                    $errors++;
                    $this->newLine();
                    $this->error('Error migrando registro ID ' . $record->idbiodiversidad . ': ' . $e->getMessage());
                }
                
                $bar->advance();
            }
            
            DB::commit();
            $bar->finish();
            
            $this->newLine(2);
            $this->info('=== MIGRACIÓN COMPLETADA ===');
            $this->info('✓ Registros migrados: ' . $migrated);
            $this->info('- Registros omitidos (ya existían): ' . $skipped);
            
            if ($errors > 0) {
                $this->warn('⚠ Errores encontrados: ' . $errors);
            }
            
        } catch (Exception $e) {
            DB::rollback();
            $bar->finish();
            throw $e;
        }
    }
    
    /**
     * Mapea el estado de conservación de la base externa al ID correspondiente
     */
    private function mapConservationStatus($categoria)
    {
        // Mapeo básico basado en los códigos disponibles
        $mapping = [
            'LC' => 7, // Preocupación Menor
            'NT' => 6, // Casi Amenazado
            'VU' => 5, // Vulnerable
            'EN' => 4, // En Peligro
            'CR' => 3, // En Peligro Crítico
            'EW' => 2, // Extinto en Estado Silvestre
            'EX' => 1, // Extinto
            'DD' => 8, // Datos Insuficientes
            'NE' => 9, // No Evaluado
        ];
        
        // Extraer código de la categoría (ej: "IUCN 2024: LC" -> "LC")
        if (preg_match('/\b([A-Z]{2})\b/', $categoria, $matches)) {
            $code = $matches[1];
            return $mapping[$code] ?? 9; // Default: No Evaluado
        }
        
        return 9; // Default: No Evaluado
     }
     
     /**
      * Extrae el código de conservación de 2 caracteres
      */
     private function getConservationCode($categoria)
     {
         // Extraer código de la categoría (ej: "IUCN 2024: LC" -> "LC")
         if (preg_match('/\b([A-Z]{2})\b/', $categoria, $matches)) {
             return $matches[1];
         }
         
         return 'NE'; // Default: No Evaluado
     }
}
