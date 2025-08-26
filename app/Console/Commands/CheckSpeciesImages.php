<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BiodiversityCategory;

class CheckSpeciesImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'biodiversity:check-species-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar el estado de las imágenes de todas las especies';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('📊 Verificando estado de imágenes de especies...');
        
        $totalSpecies = BiodiversityCategory::count();
        $speciesWithImages = BiodiversityCategory::whereNotNull('image_path')->count();
        $speciesWithoutImages = $totalSpecies - $speciesWithImages;
        
        // Especies con múltiples imágenes
        $speciesWithMultipleImages = BiodiversityCategory::where(function($query) {
            $query->whereNotNull('image_path_2')
                  ->orWhereNotNull('image_path_3')
                  ->orWhereNotNull('image_path_4');
        })->count();
        
        // Especies con imágenes globales
        $speciesWithGlobalImages = BiodiversityCategory::where(function($query) {
            $query->where('image_path', 'LIKE', '%global%')
                  ->orWhere('image_path_2', 'LIKE', '%global%')
                  ->orWhere('image_path_3', 'LIKE', '%global%')
                  ->orWhere('image_path_4', 'LIKE', '%global%');
        })->count();
        
        $this->newLine();
        $this->info('📈 Estadísticas de imágenes:');
        $this->table(
            ['Métrica', 'Cantidad', 'Porcentaje'],
            [
                ['Total especies', $totalSpecies, '100%'],
                ['Especies con imágenes', $speciesWithImages, $totalSpecies > 0 ? round(($speciesWithImages / $totalSpecies) * 100, 1) . '%' : '0%'],
                ['Especies sin imágenes', $speciesWithoutImages, $totalSpecies > 0 ? round(($speciesWithoutImages / $totalSpecies) * 100, 1) . '%' : '0%'],
                ['Especies con múltiples imágenes', $speciesWithMultipleImages, $totalSpecies > 0 ? round(($speciesWithMultipleImages / $totalSpecies) * 100, 1) . '%' : '0%'],
                ['Especies con imágenes globales', $speciesWithGlobalImages, $totalSpecies > 0 ? round(($speciesWithGlobalImages / $totalSpecies) * 100, 1) . '%' : '0%'],
            ]
        );
        
        if ($speciesWithoutImages > 0) {
            $this->newLine();
            $this->warn("⚠️  Hay {$speciesWithoutImages} especies sin imágenes.");
            $this->info('💡 Puedes usar el comando: php artisan biodiversity:update-global-images --source=all');
        } else {
            $this->newLine();
            $this->info('🎉 ¡Todas las especies tienen al menos una imagen!');
        }
        
        return 0;
    }
}