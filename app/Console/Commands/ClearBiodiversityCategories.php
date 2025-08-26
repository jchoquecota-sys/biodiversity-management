<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BiodiversityCategory;

class ClearBiodiversityCategories extends Command
{
    protected $signature = 'clear:biodiversity-categories {--force : Forzar limpieza sin confirmación}';
    protected $description = 'Limpia todos los registros de la tabla biodiversity_categories';

    public function handle()
    {
        $count = BiodiversityCategory::count();
        
        if ($count === 0) {
            $this->info('La tabla biodiversity_categories ya está vacía.');
            return 0;
        }
        
        $this->info("Se encontraron {$count} registros en biodiversity_categories.");
        
        if (!$this->option('force') && !$this->confirm('¿Desea eliminar todos los registros?')) {
            $this->info('Operación cancelada.');
            return 0;
        }
        
        BiodiversityCategory::query()->delete();
        $this->info('✓ Tabla biodiversity_categories limpiada correctamente.');
        
        return 0;
    }
}