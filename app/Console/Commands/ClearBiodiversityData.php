<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClearBiodiversityData extends Command
{
    protected $signature = 'biodiversity:clear {--force : Forzar la eliminación sin confirmación}';
    protected $description = 'Vaciar todas las tablas de biodiversidad y sus relaciones';

    public function handle()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('¿Estás seguro de que quieres eliminar todos los datos de biodiversidad? Esta acción no se puede deshacer.')) {
                $this->info('Operación cancelada.');
                return 0;
            }
        }

        $this->info('Eliminando datos de biodiversidad...');

        try {
            // Deshabilitar verificación de claves foráneas temporalmente
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Vaciar tablas en el orden correcto para evitar conflictos de claves foráneas
            $tables = [
                'biodiversity_category_publication',
                'biodiversity_publication', 
                'biodiversity_categories'
            ];

            foreach ($tables as $table) {
                if (Schema::hasTable($table)) {
                    DB::table($table)->truncate();
                    $this->info("Tabla {$table} vaciada.");
                } else {
                    $this->warn("Tabla {$table} no existe.");
                }
            }

            // Rehabilitar verificación de claves foráneas
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $this->info('Todos los datos de biodiversidad han sido eliminados exitosamente.');
            
            return 0;
        } catch (\Exception $e) {
            // Rehabilitar verificación de claves foráneas en caso de error
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->error('Error al eliminar los datos: ' . $e->getMessage());
            return 1;
        }
    }
}