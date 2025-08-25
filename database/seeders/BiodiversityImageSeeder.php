<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BiodiversityCategory;
use Illuminate\Support\Facades\Storage;

class BiodiversityImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear directorio de imágenes de ejemplo si no existe
        $imageDir = storage_path('app/public/sample-images');
        if (!file_exists($imageDir)) {
            mkdir($imageDir, 0755, true);
        }

        // Obtener todas las categorías de biodiversidad
        $categories = BiodiversityCategory::all();

        // Plantillas SVG para diferentes tipos de especies
        $svgTemplates = [
            'animal' => '<svg width="400" height="300" xmlns="http://www.w3.org/2000/svg"><rect width="400" height="300" fill="#e8f5e8"/><circle cx="200" cy="150" r="80" fill="#4a90a4" opacity="0.8"/><text x="200" y="160" text-anchor="middle" font-family="Arial" font-size="16" fill="#2c3e50">{{name}}</text><text x="200" y="180" text-anchor="middle" font-family="Arial" font-size="12" fill="#7f8c8d" font-style="italic">{{scientific_name}}</text></svg>',
            'plant' => '<svg width="400" height="300" xmlns="http://www.w3.org/2000/svg"><rect width="400" height="300" fill="#f0f8e8"/><rect x="180" y="100" width="40" height="120" fill="#8b4513"/><ellipse cx="200" cy="80" rx="60" ry="40" fill="#228b22"/><text x="200" y="250" text-anchor="middle" font-family="Arial" font-size="16" fill="#2c3e50">{{name}}</text><text x="200" y="270" text-anchor="middle" font-family="Arial" font-size="12" fill="#7f8c8d" font-style="italic">{{scientific_name}}</text></svg>',
            'fungi' => '<svg width="400" height="300" xmlns="http://www.w3.org/2000/svg"><rect width="400" height="300" fill="#faf0e6"/><ellipse cx="200" cy="120" rx="50" ry="30" fill="#cd853f"/><rect x="190" y="120" width="20" height="60" fill="#deb887"/><text x="200" y="220" text-anchor="middle" font-family="Arial" font-size="16" fill="#2c3e50">{{name}}</text><text x="200" y="240" text-anchor="middle" font-family="Arial" font-size="12" fill="#7f8c8d" font-style="italic">{{scientific_name}}</text></svg>',
            'default' => '<svg width="400" height="300" xmlns="http://www.w3.org/2000/svg"><rect width="400" height="300" fill="#f8f9fa"/><circle cx="200" cy="150" r="60" fill="#6c757d" opacity="0.6"/><text x="200" y="160" text-anchor="middle" font-family="Arial" font-size="16" fill="#2c3e50">{{name}}</text><text x="200" y="180" text-anchor="middle" font-family="Arial" font-size="12" fill="#7f8c8d" font-style="italic">{{scientific_name}}</text></svg>'
        ];

        foreach ($categories as $category) {
            // Solo agregar imagen si no tiene una ya
            if (!$category->getFirstMedia('images')) {
                try {
                    // Determinar el tipo de plantilla basado en el reino
                    $template = 'default';
                    if (stripos($category->kingdom, 'animalia') !== false) {
                        $template = 'animal';
                    } elseif (stripos($category->kingdom, 'plantae') !== false) {
                        $template = 'plant';
                    } elseif (stripos($category->kingdom, 'fungi') !== false) {
                        $template = 'fungi';
                    }
                    
                    // Generar SVG personalizado
                    $svgContent = str_replace(
                        ['{{name}}', '{{scientific_name}}'],
                        [substr($category->name, 0, 20), substr($category->scientific_name ?? '', 0, 25)],
                        $svgTemplates[$template]
                    );
                    
                    // Guardar archivo SVG temporal
                    $fileName = 'category_' . $category->id . '.svg';
                    $tempPath = $imageDir . '/' . $fileName;
                    file_put_contents($tempPath, $svgContent);
                    
                    // Agregar imagen a la categoría usando Spatie Media Library
                    $category->addMedia($tempPath)
                        ->usingName($category->name)
                        ->usingFileName($fileName)
                        ->toMediaCollection('images');
                    
                    $this->command->info("Imagen SVG agregada para: {$category->name}");
                    
                    // Eliminar archivo temporal
                    unlink($tempPath);
                    
                } catch (\Exception $e) {
                    $this->command->warn("No se pudo agregar imagen para {$category->name}: {$e->getMessage()}");
                }
            } else {
                $this->command->info("La categoría {$category->name} ya tiene una imagen.");
            }
        }
        
        // Limpiar directorio temporal si está vacío
        if (is_dir($imageDir) && count(scandir($imageDir)) == 2) {
            rmdir($imageDir);
        }
        
        $this->command->info('Proceso de agregado de imágenes SVG completado.');
    }
}