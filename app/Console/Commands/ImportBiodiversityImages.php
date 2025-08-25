<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BiodiversityCategory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ImportBiodiversityImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'biodiversity:import-images 
                            {--source=url : Source type: url, local, or csv}
                            {--file= : CSV file path with species and image URLs}
                            {--directory= : Local directory with images}
                            {--dry-run : Show what would be imported without actually importing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importar imágenes reales para el catálogo de biodiversidad desde URLs, archivos locales o CSV';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $source = $this->option('source');
        $dryRun = $this->option('dry-run');
        
        $this->info('🌿 Iniciando importación de imágenes de biodiversidad...');
        
        if ($dryRun) {
            $this->warn('⚠️  Modo DRY-RUN activado - No se realizarán cambios reales');
        }
        
        switch ($source) {
            case 'csv':
                return $this->importFromCsv($dryRun);
            case 'local':
                return $this->importFromLocalDirectory($dryRun);
            case 'url':
            default:
                return $this->importFromUrls($dryRun);
        }
    }
    
    /**
     * Importar imágenes desde un archivo CSV
     */
    private function importFromCsv($dryRun = false)
    {
        $csvFile = $this->option('file');
        
        if (!$csvFile || !file_exists($csvFile)) {
            $this->error('❌ Archivo CSV no encontrado. Use --file=ruta/al/archivo.csv');
            return 1;
        }
        
        $this->info("📄 Procesando archivo CSV: {$csvFile}");
        
        $handle = fopen($csvFile, 'r');
        $imported = 0;
        $errors = 0;
        $lineNumber = 0;
        $isMultipleFormat = false;
        
        while (($data = fgetcsv($handle, 5000, ',')) !== FALSE) {
            $lineNumber++;
            
            if ($lineNumber === 1) {
                // Validar headers - soportar tanto formato simple como múltiple
                $simpleHeaders = ['name', 'scientific_name', 'image_url'];
                $multipleHeaders = ['name', 'scientific_name', 'image_url_1', 'image_url_2', 'image_url_3', 'image_url_4'];
                
                // Debug: mostrar headers leídos
                $this->info('Headers encontrados: ' . implode(', ', $data));
                
                if ($data === $simpleHeaders) {
                    $isMultipleFormat = false;
                } elseif ($data === $multipleHeaders) {
                    $isMultipleFormat = true;
                } else {
                    $this->error('❌ Headers incorrectos. Se esperan: ' . implode(', ', $simpleHeaders) . ' o ' . implode(', ', $multipleHeaders));
                    $this->error('Headers recibidos: ' . implode(', ', $data));
                    return 1;
                }
                continue;
            }
            
            if ($isMultipleFormat) {
                $data = array_combine(['name', 'scientific_name', 'image_url_1', 'image_url_2', 'image_url_3', 'image_url_4'], $data);
            } else {
                $data = array_combine(['name', 'scientific_name', 'image_url'], $data);
            }
            
            // Buscar especie por nombre científico o común
            $species = BiodiversityCategory::where('scientific_name', $data['scientific_name'])
                ->orWhere('name', $data['name'])
                ->first();
                
            if (!$species) {
                $this->warn("⚠️  Especie no encontrada: {$data['scientific_name']}");
                $errors++;
                continue;
            }
            
            // Procesar múltiples URLs si es formato múltiple
            if ($isMultipleFormat) {
                $imageUrls = [];
                for ($i = 1; $i <= 4; $i++) {
                    $urlKey = "image_url_{$i}";
                    if (!empty($data[$urlKey])) {
                        $imageUrls[] = $data[$urlKey];
                    }
                }
                
                $successCount = 0;
                foreach ($imageUrls as $index => $imageUrl) {
                    if ($this->downloadAndSaveImage($species, $imageUrl, $dryRun, $index + 1)) {
                        $successCount++;
                    }
                }
                
                if ($successCount > 0) {
                    $imported++;
                    $this->info("✅ {$successCount} imagen(es) importada(s) para: {$species->name}");
                } else {
                    $errors++;
                }
            } else {
                // Formato simple con una sola URL
                if ($this->downloadAndSaveImage($species, $data['image_url'], $dryRun)) {
                    $imported++;
                    $this->info("✅ Imagen importada para: {$species->name}");
                } else {
                    $errors++;
                }
            }
        }
        
        fclose($handle);
        
        $this->info("🎉 Importación completada: {$imported} imágenes importadas, {$errors} errores");
        return 0;
    }
    
    /**
     * Importar imágenes desde directorio local
     */
    private function importFromLocalDirectory($dryRun = false)
    {
        $directory = $this->option('directory');
        
        if (!$directory || !is_dir($directory)) {
            $this->error('❌ Directorio no encontrado. Use --directory=ruta/al/directorio');
            return 1;
        }
        
        $this->info("📁 Procesando directorio: {$directory}");
        
        $files = glob($directory . '/*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
        $imported = 0;
        $errors = 0;
        
        foreach ($files as $file) {
            $filename = pathinfo($file, PATHINFO_FILENAME);
            
            // Buscar especie por nombre de archivo (puede ser nombre científico o común)
            $species = BiodiversityCategory::where('scientific_name', 'LIKE', "%{$filename}%")
                ->orWhere('name', 'LIKE', "%{$filename}%")
                ->first();
                
            if (!$species) {
                $this->warn("⚠️  No se encontró especie para el archivo: {$filename}");
                $errors++;
                continue;
            }
            
            if ($this->copyLocalImage($species, $file, $dryRun)) {
                $imported++;
                $this->info("✅ Imagen copiada para: {$species->name}");
            } else {
                $errors++;
            }
        }
        
        $this->info("🎉 Importación completada: {$imported} imágenes importadas, {$errors} errores");
        return 0;
    }
    
    /**
     * Importar imágenes desde URLs predefinidas
     */
    private function importFromUrls($dryRun = false)
    {
        // URLs de ejemplo para especies comunes (esto se puede expandir)
        $imageUrls = [
            'Panthera onca' => 'https://upload.wikimedia.org/wikipedia/commons/0/0a/Standing_jaguar.jpg',
            'Ara macao' => 'https://upload.wikimedia.org/wikipedia/commons/c/c4/Ara_macao_-flying-8a.jpg',
            'Cecropia peltata' => 'https://upload.wikimedia.org/wikipedia/commons/8/8f/Cecropia_peltata_leaves.jpg',
        ];
        
        $imported = 0;
        $errors = 0;
        
        foreach ($imageUrls as $scientificName => $imageUrl) {
            $species = BiodiversityCategory::where('scientific_name', $scientificName)->first();
            
            if (!$species) {
                $this->warn("⚠️  Especie no encontrada: {$scientificName}");
                $errors++;
                continue;
            }
            
            if ($this->downloadAndSaveImage($species, $imageUrl, $dryRun)) {
                $imported++;
                $this->info("✅ Imagen descargada para: {$species->name}");
            } else {
                $errors++;
            }
        }
        
        $this->info("🎉 Importación completada: {$imported} imágenes importadas, {$errors} errores");
        return 0;
    }
    
    /**
     * Descargar y guardar imagen desde URL
     */
    private function downloadAndSaveImage($species, $imageUrl, $dryRun = false, $imageIndex = null)
    {
        try {
            if ($dryRun) {
                $this->line("[DRY-RUN] Descargaría imagen de {$imageUrl} para {$species->name}");
                return true;
            }
            
            // Descargar imagen con headers apropiados
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
            ])->timeout(30)->get($imageUrl);
            
            if (!$response->successful()) {
                $this->error("❌ Error al descargar imagen ({$response->status()}): {$imageUrl}");
                return false;
            }
            
            // Generar nombre único para la imagen
            $urlPath = parse_url($imageUrl, PHP_URL_PATH);
            $extension = pathinfo($urlPath, PATHINFO_EXTENSION) ?: 'jpg';
            // Limpiar extensión de parámetros
            $extension = explode('?', $extension)[0];
            if (empty($extension) || !in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $extension = 'jpg';
            }
            
            $baseFilename = Str::slug($species->scientific_name);
            if ($imageIndex !== null) {
                $baseFilename .= "_{$imageIndex}";
            }
            $filename = $baseFilename . '_' . time() . '.' . $extension;
            $path = 'biodiversity/especies/' . $filename;
            
            // Guardar imagen
            Storage::disk('public')->put($path, $response->body());
            
            // Actualizar el campo correspondiente según el índice de imagen
             $updateData = [];
             
             if ($imageIndex === null || $imageIndex === 1) {
                 // Eliminar imagen anterior si existe
                 if ($species->image_path && Storage::disk('public')->exists($species->image_path)) {
                     Storage::disk('public')->delete($species->image_path);
                 }
                 $updateData['image_path'] = $path;
             } elseif ($imageIndex === 2) {
                 if ($species->image_path_2 && Storage::disk('public')->exists($species->image_path_2)) {
                     Storage::disk('public')->delete($species->image_path_2);
                 }
                 $updateData['image_path_2'] = $path;
             } elseif ($imageIndex === 3) {
                 if ($species->image_path_3 && Storage::disk('public')->exists($species->image_path_3)) {
                     Storage::disk('public')->delete($species->image_path_3);
                 }
                 $updateData['image_path_3'] = $path;
             } elseif ($imageIndex === 4) {
                 if ($species->image_path_4 && Storage::disk('public')->exists($species->image_path_4)) {
                     Storage::disk('public')->delete($species->image_path_4);
                 }
                 $updateData['image_path_4'] = $path;
             }
             
             // Actualizar registro en base de datos
             $species->update($updateData);
            
            return true;
            
        } catch (\Exception $e) {
            $this->error("❌ Error procesando {$species->name}: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Copiar imagen desde archivo local
     */
    private function copyLocalImage($species, $localFile, $dryRun = false, $imageIndex = null)
    {
        try {
            if ($dryRun) {
                $this->line("[DRY-RUN] Copiaría imagen de {$localFile} para {$species->name}");
                return true;
            }
            
            // Generar nombre único para la imagen
            $extension = pathinfo($localFile, PATHINFO_EXTENSION);
            $baseFilename = Str::slug($species->scientific_name);
            if ($imageIndex !== null) {
                $baseFilename .= "_{$imageIndex}";
            }
            $filename = $baseFilename . '_' . time() . '.' . $extension;
            $path = 'biodiversity/especies/' . $filename;
            
            // Copiar imagen
            $imageContent = file_get_contents($localFile);
            Storage::disk('public')->put($path, $imageContent);
            
            // Eliminar imagen anterior si existe
            if ($species->image_path && Storage::disk('public')->exists($species->image_path)) {
                Storage::disk('public')->delete($species->image_path);
            }
            
            // Actualizar registro en base de datos
            $species->update(['image_path' => $path]);
            
            return true;
            
        } catch (\Exception $e) {
            $this->error("❌ Error procesando {$species->name}: " . $e->getMessage());
            return false;
        }
    }
}
