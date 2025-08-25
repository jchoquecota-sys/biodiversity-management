<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BiodiversityCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BackupBiodiversityData extends Command
{
    protected $signature = 'biodiversity:backup';
    protected $description = 'Crear respaldo de los datos de biodiversidad actuales';

    public function handle()
    {
        $this->info('Creando respaldo de datos de biodiversidad...');

        try {
            // Obtener todos los datos de biodiversidad
            $biodiversityData = BiodiversityCategory::with(['conservationStatus', 'publications'])->get();
            
            // Obtener datos de tablas relacionadas
            $pivotData = DB::table('biodiversity_category_publication')->get();
            
            $backup = [
                'timestamp' => now()->toISOString(),
                'biodiversity_categories' => $biodiversityData->toArray(),
                'biodiversity_category_publication' => $pivotData->toArray()
            ];

            // Guardar el respaldo en un archivo JSON
            $filename = 'biodiversity_backup_' . now()->format('Y_m_d_H_i_s') . '.json';
            Storage::disk('local')->put($filename, json_encode($backup, JSON_PRETTY_PRINT));

            $this->info("Respaldo creado exitosamente: storage/app/{$filename}");
            $this->info("Total de especies respaldadas: " . $biodiversityData->count());
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Error al crear el respaldo: ' . $e->getMessage());
            return 1;
        }
    }
}