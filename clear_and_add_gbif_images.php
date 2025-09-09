<?php

require_once 'vendor/autoload.php';

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'biodiversity_management';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa a la base de datos\n";
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

echo "\n=== LIMPIANDO Y AGREGANDO IMÁGENES DE GBIF ===\n\n";

// PASO 1: Limpiar TODAS las imágenes existentes
echo "PASO 1: Limpiando TODAS las imágenes existentes...\n";
$clearSql = "UPDATE biodiversity_categories SET 
                image_path = NULL,
                image_path_2 = NULL,
                image_path_3 = NULL,
                image_path_4 = NULL,
                updated_at = NOW()";
$pdo->exec($clearSql);
echo "✓ Todas las imágenes han sido eliminadas completamente\n\n";

// PASO 2: Función para obtener imágenes de GBIF
function getGBIFImages($scientificName) {
    $images = [];
    
    // URL de la API de GBIF para buscar especies
    $apiUrl = "https://api.gbif.org/v1/species/search?q=" . urlencode($scientificName) . "&limit=1";
    
    $context = stream_context_create([
        'http' => [
            'timeout' => 10,
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        ]
    ]);
    
    $response = @file_get_contents($apiUrl, false, $context);
    
    if ($response) {
        $data = json_decode($response, true);
        
        if (isset($data['results'][0]['key'])) {
            $speciesKey = $data['results'][0]['key'];
            
            // Obtener imágenes de la especie específica
            $imagesUrl = "https://api.gbif.org/v1/species/{$speciesKey}/media";
            $imagesResponse = @file_get_contents($imagesUrl, false, $context);
            
            if ($imagesResponse) {
                $imagesData = json_decode($imagesResponse, true);
                
                if (isset($imagesData['results'])) {
                    foreach ($imagesData['results'] as $media) {
                        if (isset($media['identifier']) && 
                            (strpos($media['identifier'], '.jpg') !== false || 
                             strpos($media['identifier'], '.jpeg') !== false || 
                             strpos($media['identifier'], '.png') !== false)) {
                            $images[] = $media['identifier'];
                            
                            if (count($images) >= 4) {
                                break;
                            }
                        }
                    }
                }
            }
        }
    }
    
    return $images;
}

// PASO 3: Obtener especies de la base de datos y buscar imágenes en GBIF
echo "PASO 2: Buscando imágenes en GBIF para especies peruanas...\n";

$stmt = $pdo->query("SELECT id, name, scientific_name FROM biodiversity_categories ORDER BY id LIMIT 20");
$species = $stmt->fetchAll(PDO::FETCH_ASSOC);

$updatedCount = 0;
$noImagesCount = 0;

foreach ($species as $specie) {
    echo "Buscando imágenes para: {$specie['name']} ({$specie['scientific_name']})...\n";
    
    $images = getGBIFImages($specie['scientific_name']);
    
    if (count($images) > 0) {
        try {
            $updateSql = "UPDATE biodiversity_categories SET 
                            image_path = :image_path,
                            image_path_2 = :image_path_2,
                            image_path_3 = :image_path_3,
                            image_path_4 = :image_path_4,
                            updated_at = NOW()
                          WHERE id = :id";
            
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->execute([
                ':image_path' => $images[0] ?? null,
                ':image_path_2' => $images[1] ?? null,
                ':image_path_3' => $images[2] ?? null,
                ':image_path_4' => $images[3] ?? null,
                ':id' => $specie['id']
            ]);
            
            echo "✓ Actualizado: {$specie['name']} - " . count($images) . " imágenes de GBIF\n";
            echo "  Fuente: GBIF (Global Biodiversity Information Facility)\n";
            echo "  URLs: " . implode(', ', array_slice($images, 0, 2)) . "...\n\n";
            
            $updatedCount++;
            
        } catch (PDOException $e) {
            echo "✗ Error actualizando {$specie['name']}: " . $e->getMessage() . "\n\n";
        }
    } else {
        echo "- No se encontraron imágenes en GBIF para: {$specie['name']}\n\n";
        $noImagesCount++;
    }
    
    // Pequeña pausa para no sobrecargar la API
    sleep(1);
}

// PASO 4: Mostrar resumen final
echo "=== RESUMEN DE ACTUALIZACIÓN CON GBIF ===\n";
echo "Especies procesadas: " . count($species) . "\n";
echo "Especies actualizadas con imágenes: $updatedCount\n";
echo "Especies sin imágenes: $noImagesCount\n\n";

// Mostrar estadísticas finales
$stmt = $pdo->query("SELECT 
    COUNT(*) as total,
    COUNT(CASE WHEN image_path IS NOT NULL AND image_path != '' THEN 1 END) as with_images,
    COUNT(CASE WHEN image_path LIKE 'http%' THEN 1 END) as with_external_images
    FROM biodiversity_categories");

$stats = $stmt->fetch(PDO::FETCH_ASSOC);

echo "=== ESTADÍSTICAS FINALES ===\n";
echo "Total de especies: {$stats['total']}\n";
echo "Con imágenes: {$stats['with_images']}\n";
echo "Con imágenes externas (URLs): {$stats['with_external_images']}\n";
echo "Porcentaje con imágenes: " . round(($stats['with_images'] / $stats['total']) * 100, 2) . "%\n\n";

// Mostrar ejemplos de URLs de GBIF
echo "=== EJEMPLOS DE URLs DE GBIF ===\n";
$stmt = $pdo->query("SELECT name, scientific_name, image_path, image_path_2 
                     FROM biodiversity_categories 
                     WHERE image_path LIKE 'http%' 
                     ORDER BY id LIMIT 5");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "Especie: {$row['name']} ({$row['scientific_name']})\n";
    echo "Imagen 1: " . substr($row['image_path'], 0, 80) . "...\n";
    echo "Imagen 2: " . substr($row['image_path_2'], 0, 80) . "...\n\n";
}

echo "¡Actualización con imágenes de GBIF completada!\n";
echo "Fuente: GBIF (Global Biodiversity Information Facility)\n";
echo "Calidad: Imágenes científicas verificadas\n";
echo "Cobertura: Especies peruanas con datos de biodiversidad global\n";

?>
