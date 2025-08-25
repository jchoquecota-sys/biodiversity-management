<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BiodiversityCategory;
use Illuminate\Support\Facades\Storage;

class CheckBiodiversityImages extends Command
{
    protected $signature = 'biodiversity:check-images';
    protected $description = 'Check biodiversity images in database and storage';

    public function handle()
    {
        $this->info('🔍 Verificando imágenes de biodiversidad...');
        
        // Verificar especies con imágenes en la base de datos
        $speciesWithImages = BiodiversityCategory::whereNotNull('image_path')
            ->select('id', 'name', 'scientific_name', 'image_path')
            ->get();
            
        $this->info("📊 Especies con imágenes en BD: {$speciesWithImages->count()}");
        
        foreach ($speciesWithImages as $species) {
            $exists = Storage::disk('public')->exists($species->image_path);
            $status = $exists ? '✅' : '❌';
            $this->line("{$status} {$species->name} - {$species->image_path}");
        }
        
        // Verificar archivos en el directorio
        $files = Storage::disk('public')->files('biodiversity/especies');
        $this->info("\n📁 Archivos en storage: " . count($files));
        
        foreach ($files as $file) {
            $this->line("📄 {$file}");
        }
        
        return 0;
    }
}