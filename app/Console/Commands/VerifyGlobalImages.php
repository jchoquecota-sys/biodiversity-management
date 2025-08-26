<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BiodiversityCategory;
use Illuminate\Support\Facades\Storage;

class VerifyGlobalImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'biodiversity:verify-global-images 
                            {--limit=10 : Límite de especies a mostrar}
                            {--check-files : Verificar que los archivos existen físicamente}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar las imágenes actualizadas desde fuentes globales';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = (int) $this->option('limit');
        $checkFiles = $this->option('check-files');
        
        $this->info('🔍 Verificando imágenes actualizadas desde fuentes globales...');
        
        // Obtener especies con imágenes que contienen 'global' en la ruta
        $speciesWithGlobalImages = BiodiversityCategory::where(function($query) {
            $query->where('image_path', 'LIKE', '%global%')
                  ->orWhere('image_path_2', 'LIKE', '%global%')
                  ->orWhere('image_path_3', 'LIKE', '%global%')
                  ->orWhere('image_path_4', 'LIKE', '%global%');
        })
        ->limit($limit)
        ->get();
        
        if ($speciesWithGlobalImages->isEmpty()) {
            $this->warn('⚠️  No se encontraron especies con imágenes globales.');
            return 0;
        }
        
        $this->info("📊 Encontradas {$speciesWithGlobalImages->count()} especies con imágenes globales:");
        $this->newLine();
        
        $totalImages = 0;
        $validFiles = 0;
        $invalidFiles = 0;
        
        foreach ($speciesWithGlobalImages as $species) {
            $this->info("🌿 {$species->name} ({$species->scientific_name})");
            $this->line("   ID: {$species->id}");
            
            $imagePaths = [
                'image_path' => $species->image_path,
                'image_path_2' => $species->image_path_2,
                'image_path_3' => $species->image_path_3,
                'image_path_4' => $species->image_path_4,
            ];
            
            foreach ($imagePaths as $field => $path) {
                if ($path) {
                    $totalImages++;
                    $this->line("   {$field}: {$path}");
                    
                    if ($checkFiles) {
                        if (Storage::disk('public')->exists($path)) {
                            $size = Storage::disk('public')->size($path);
                            $sizeKB = round($size / 1024, 2);
                            $this->line("     ✅ Archivo existe ({$sizeKB} KB)");
                            $validFiles++;
                        } else {
                            $this->error("     ❌ Archivo no encontrado");
                            $invalidFiles++;
                        }
                    }
                }
            }
            
            $this->newLine();
        }
        
        // Estadísticas generales
        $this->info('📈 Estadísticas generales:');
        
        $totalSpeciesWithImages = BiodiversityCategory::where(function($query) {
            $query->whereNotNull('image_path')
                  ->orWhereNotNull('image_path_2')
                  ->orWhereNotNull('image_path_3')
                  ->orWhereNotNull('image_path_4');
        })->count();
        
        $globalImageSpecies = BiodiversityCategory::where(function($query) {
            $query->where('image_path', 'LIKE', '%global%')
                  ->orWhere('image_path_2', 'LIKE', '%global%')
                  ->orWhere('image_path_3', 'LIKE', '%global%')
                  ->orWhere('image_path_4', 'LIKE', '%global%');
        })->count();
        
        $this->table(
            ['Métrica', 'Cantidad'],
            [
                ['Total especies con imágenes', $totalSpeciesWithImages],
                ['Especies con imágenes globales', $globalImageSpecies],
                ['Imágenes globales encontradas', $totalImages],
                $checkFiles ? ['Archivos válidos', $validFiles] : null,
                $checkFiles ? ['Archivos inválidos', $invalidFiles] : null,
            ]
        );
        
        if ($checkFiles && $invalidFiles > 0) {
            $this->warn("⚠️  Se encontraron {$invalidFiles} archivos faltantes.");
        }
        
        return 0;
    }
}