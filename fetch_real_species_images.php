<?php

require_once __DIR__ . '/vendor/autoload.php';

// Configurar la aplicación Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\BiodiversityCategory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

echo "=== Descargando imágenes reales de especies desde fuentes mundiales ===\n\n";

// Obtener todas las especies peruanas
$species = BiodiversityCategory::all();
echo "Total de especies encontradas: " . $species->count() . "\n\n";

$updatedCount = 0;
$errorCount = 0;

// Función para limpiar nombres científicos para búsqueda
function cleanScientificName($name) {
    // Remover texto entre paréntesis y limpiar
    $cleaned = preg_replace('/\([^)]*\)/', '', $name);
    $cleaned = trim($cleaned);
    $cleaned = preg_replace('/\s+/', ' ', $cleaned);
    return $cleaned;
}

// Función para buscar imágenes en iNaturalist
function searchINaturalistImages($scientificName, $commonName = null) {
    try {
        // Buscar por nombre científico primero
        $searchTerms = [$scientificName];
        if ($commonName) {
            $searchTerms[] = $commonName;
        }
        
        foreach ($searchTerms as $searchTerm) {
            echo "  Buscando en iNaturalist: $searchTerm\n";
            
            // Buscar taxones en iNaturalist
            $taxonResponse = Http::timeout(30)->get('https://api.inaturalist.org/v1/taxa', [
                'q' => $searchTerm,
                'per_page' => 5,
                'locale' => 'es'
            ]);
            
            if ($taxonResponse->successful()) {
                $taxonData = $taxonResponse->json();
                
                if (!empty($taxonData['results'])) {
                    $taxon = $taxonData['results'][0];
                    $taxonId = $taxon['id'];
                    
                    echo "    Taxón encontrado: {$taxon['name']} (ID: $taxonId)\n";
                    
                    // Buscar observaciones con fotos
                    $obsResponse = Http::timeout(30)->get('https://api.inaturalist.org/v1/observations', [
                        'taxon_id' => $taxonId,
                        'photos' => 'true',
                        'quality_grade' => 'research',
                        'per_page' => 10,
                        'order' => 'votes',
                        'license' => 'cc-by,cc-by-nc,cc-by-sa,cc-by-nc-sa,cc0'
                    ]);
                    
                    if ($obsResponse->successful()) {
                        $obsData = $obsResponse->json();
                        
                        if (!empty($obsData['results'])) {
                            $images = [];
                            
                            foreach ($obsData['results'] as $obs) {
                                if (!empty($obs['photos'])) {
                                    foreach ($obs['photos'] as $photo) {
                                        if (isset($photo['url']) && count($images) < 4) {
                                            // Usar la URL de tamaño mediano
                                            $imageUrl = str_replace('square', 'medium', $photo['url']);
                                            $images[] = [
                                                'url' => $imageUrl,
                                                'license' => $photo['license_code'] ?? 'cc-by-nc',
                                                'attribution' => $photo['attribution'] ?? 'iNaturalist user'
                                            ];
                                        }
                                    }
                                }
                                if (count($images) >= 4) break;
                            }
                            
                            if (!empty($images)) {
                                echo "    Encontradas " . count($images) . " imágenes\n";
                                return $images;
                            }
                        }
                    }
                }
            }
            
            // Pequeña pausa entre búsquedas
            sleep(1);
        }
        
        return [];
    } catch (Exception $e) {
        echo "    Error en búsqueda: " . $e->getMessage() . "\n";
        return [];
    }
}

// Función para descargar y guardar imagen
function downloadAndSaveImage($imageUrl, $filename) {
    try {
        $response = Http::timeout(30)->get($imageUrl);
        
        if ($response->successful()) {
            $imageData = $response->body();
            $filepath = public_path("images/peru/real/$filename");
            
            // Crear directorio si no existe
            $directory = dirname($filepath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            
            file_put_contents($filepath, $imageData);
            return "images/peru/real/$filename";
        }
        
        return null;
    } catch (Exception $e) {
        echo "    Error descargando imagen: " . $e->getMessage() . "\n";
        return null;
    }
}

// Procesar cada especie
foreach ($species as $specie) {
    echo "\n--- Procesando: {$specie->name} ---\n";
    echo "Nombre científico: {$specie->scientific_name}\n";
    
    // Limpiar nombre científico
    $cleanedScientificName = cleanScientificName($specie->scientific_name);
    
    // Buscar imágenes
    $images = searchINaturalistImages($cleanedScientificName, $specie->name);
    
    if (!empty($images)) {
        $imagePaths = [];
        
        foreach ($images as $index => $imageInfo) {
            $imageNumber = $index + 1;
            $extension = 'jpg'; // iNaturalist generalmente usa JPG
            
            // Crear nombre de archivo seguro
            $safeSpecieName = preg_replace('/[^a-zA-Z0-9_-]/', '_', strtolower($specie->name));
            $filename = "{$safeSpecieName}_{$imageNumber}.{$extension}";
            
            echo "  Descargando imagen $imageNumber...\n";
            $savedPath = downloadAndSaveImage($imageInfo['url'], $filename);
            
            if ($savedPath) {
                $imagePaths[] = $savedPath;
                echo "    Guardada: $savedPath\n";
            }
            
            // Pausa entre descargas
            sleep(1);
        }
        
        // Actualizar la base de datos
        if (!empty($imagePaths)) {
            $updateData = [];
            
            for ($i = 0; $i < min(4, count($imagePaths)); $i++) {
                $field = $i === 0 ? 'image_path' : 'image_path_' . ($i + 1);
                $updateData[$field] = $imagePaths[$i];
            }
            
            $specie->update($updateData);
            $updatedCount++;
            
            echo "  ✓ Actualizada con " . count($imagePaths) . " imágenes\n";
        }
    } else {
        echo "  ✗ No se encontraron imágenes\n";
        $errorCount++;
    }
    
    // Pausa entre especies para no sobrecargar la API
    sleep(2);
}

echo "\n=== RESUMEN ===\n";
echo "Especies actualizadas: $updatedCount\n";
echo "Especies sin imágenes: $errorCount\n";
echo "Total procesadas: " . ($updatedCount + $errorCount) . "\n";
echo "\n=== Proceso completado ===\n";

?>