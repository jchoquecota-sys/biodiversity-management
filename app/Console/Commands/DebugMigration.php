<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\BiodiversityCategory;

class DebugMigration extends Command
{
    protected $signature = 'debug:migration {--host=localhost} {--port=3306} {--database=bioserver_grt} {--username=root} {--password=} {--id=499}';
    protected $description = 'Debug migration data for a specific record';

    public function handle()
    {
        $this->setupExternalConnection();
        
        $recordId = $this->option('id');
        $this->info("Debugging record ID: {$recordId}");
        
        // Get raw data from external DB
        $rawData = DB::connection('external')->table('biodiversidads')
            ->where('idbiodiversidad', $recordId)
            ->where('estado', 'activo')
            ->first();
            
        if (!$rawData) {
            $this->error('Record not found');
            return 1;
        }
        
        $this->info('=== RAW DATA ===');
        foreach ($rawData as $key => $value) {
            $length = strlen($value ?? '');
            $this->line("{$key}: '{$value}' (length: {$length})");
        }
        
        // Process data like in migration
        $processedData = [
            'name' => $this->truncateString($rawData->nombrecomun ?? '', 255),
            'scientific_name' => $this->truncateString($rawData->especie ?? '', 255),
            'common_name' => $this->truncateString($rawData->nombrecomun ?? '', 255),
            'conservation_status' => $this->truncateString($rawData->categoria ?? '', 255),
            'description' => $rawData->resumenespecie ?? '',
            'kingdom' => $this->truncateString('Animalia', 255),
            'habitat' => '?',
            'image_path' => '?',
            'image_path_2' => '?',
            'image_path_3' => '?',
            'image_path_4' => '?',
        ];
        
        $this->info('\n=== PROCESSED DATA ===');
        foreach ($processedData as $key => $value) {
            $length = strlen($value ?? '');
            $this->line("{$key}: '{$value}' (length: {$length})");
        }
        
        // Try to create the record
        $this->info('\n=== ATTEMPTING TO CREATE RECORD ===');
        try {
            $category = BiodiversityCategory::create($processedData);
            $this->info('✓ Record created successfully with ID: ' . $category->id);
        } catch (\Exception $e) {
            $this->error('✗ Error creating record: ' . $e->getMessage());
        }
        
        return 0;
    }
    
    private function setupExternalConnection(): void
    {
        $host = $this->option('host');
        $port = $this->option('port');
        $database = $this->option('database');
        $username = $this->option('username');
        $password = $this->option('password');
        
        config([
            'database.connections.external' => [
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
            ]
        ]);
    }
    
    private function truncateString(?string $value, int $maxLength): string
    {
        if (empty($value)) {
            return '';
        }
        
        return strlen($value) > $maxLength ? substr($value, 0, $maxLength) : $value;
    }
}