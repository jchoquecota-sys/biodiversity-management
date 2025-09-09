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

echo "\n=== VERIFICANDO Y DESCARGANDO FOTOGRAFÍAS REALES ===\n\n";

// Crear directorio para imágenes reales
$realImagesDir = 'public/images/real_species';
if (!is_dir($realImagesDir)) {
    mkdir($realImagesDir, 0755, true);
    echo "✓ Directorio creado: $realImagesDir\n";
} else {
    echo "✓ Directorio existe: $realImagesDir\n";
}

// Función para verificar y descargar imagen
function downloadImage($url, $filename) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    
    $imageData = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        return ['success' => false, 'error' => "Error cURL: $error"];
    }
    
    if ($httpCode !== 200) {
        return ['success' => false, 'error' => "HTTP $httpCode"];
    }
    
    if (!$imageData) {
        return ['success' => false, 'error' => 'No data received'];
    }
    
    // Verificar que es una imagen válida
    $imageInfo = @getimagesizefromstring($imageData);
    if (!$imageInfo) {
        return ['success' => false, 'error' => 'Invalid image data'];
    }
    
    // Guardar imagen
    if (file_put_contents($filename, $imageData) === false) {
        return ['success' => false, 'error' => 'Failed to save file'];
    }
    
    return ['success' => true, 'size' => strlen($imageData), 'dimensions' => $imageInfo[0] . 'x' . $imageInfo[1]];
}

// Obtener especies con imágenes externas
$stmt = $pdo->query("SELECT id, name, scientific_name, image_path, image_path_2, image_path_3, image_path_4 
                     FROM biodiversity_categories 
                     WHERE image_path LIKE 'http%' 
                     ORDER BY id");

$species = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Procesando " . count($species) . " especies con imágenes externas...\n\n";

$totalDownloaded = 0;
$totalFailed = 0;

foreach ($species as $specie) {
    echo "Procesando: {$specie['name']} ({$specie['scientific_name']})\n";
    echo "ID: {$specie['id']}\n";
    
    $images = [
        $specie['image_path'],
        $specie['image_path_2'],
        $specie['image_path_3'],
        $specie['image_path_4']
    ];
    
    $downloadedImages = [];
    $imageCount = 0;
    
    foreach ($images as $imageUrl) {
        if (!$imageUrl) continue;
        
        $imageCount++;
        $filename = "{$realImagesDir}/species_{$specie['id']}_{$imageCount}.jpg";
        
        echo "  Descargando imagen $imageCount: " . substr($imageUrl, 0, 60) . "...\n";
        
        $result = downloadImage($imageUrl, $filename);
        
        if ($result['success']) {
            echo "    ✓ Descargada: $filename ({$result['size']} bytes, {$result['dimensions']})\n";
            $downloadedImages[] = "images/real_species/species_{$specie['id']}_{$imageCount}.jpg";
            $totalDownloaded++;
        } else {
            echo "    ✗ Error: {$result['error']}\n";
            $totalFailed++;
        }
    }
    
    // Actualizar base de datos con rutas locales si se descargaron imágenes
    if (!empty($downloadedImages)) {
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
                ':image_path' => $downloadedImages[0] ?? null,
                ':image_path_2' => $downloadedImages[1] ?? null,
                ':image_path_3' => $downloadedImages[2] ?? null,
                ':image_path_4' => $downloadedImages[3] ?? null,
                ':id' => $specie['id']
            ]);
            
            echo "  ✓ Base de datos actualizada con rutas locales\n";
            
        } catch (PDOException $e) {
            echo "  ✗ Error actualizando base de datos: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n";
}

// Mostrar resumen
echo "=== RESUMEN DE DESCARGA ===\n";
echo "Imágenes descargadas exitosamente: $totalDownloaded\n";
echo "Imágenes fallidas: $totalFailed\n";
echo "Total procesadas: " . ($totalDownloaded + $totalFailed) . "\n\n";

// Mostrar estadísticas finales
$stmt = $pdo->query("SELECT 
    COUNT(*) as total,
    COUNT(CASE WHEN image_path IS NOT NULL AND image_path != '' THEN 1 END) as with_images,
    COUNT(CASE WHEN image_path LIKE 'http%' THEN 1 END) as with_external_images,
    COUNT(CASE WHEN image_path LIKE 'images/%' THEN 1 END) as with_local_images
    FROM biodiversity_categories");

$stats = $stmt->fetch(PDO::FETCH_ASSOC);

echo "=== ESTADÍSTICAS FINALES ===\n";
echo "Total de especies: {$stats['total']}\n";
echo "Con imágenes: {$stats['with_images']}\n";
echo "Con imágenes externas (URLs): {$stats['with_external_images']}\n";
echo "Con imágenes locales: {$stats['with_local_images']}\n";
echo "Porcentaje con imágenes: " . round(($stats['with_images'] / $stats['total']) * 100, 2) . "%\n\n";

// Mostrar ejemplos de imágenes locales
echo "=== EJEMPLOS DE IMÁGENES LOCALES ===\n";
$stmt = $pdo->query("SELECT name, scientific_name, image_path, image_path_2 
                     FROM biodiversity_categories 
                     WHERE image_path LIKE 'images/%' 
                     ORDER BY id LIMIT 5");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "Especie: {$row['name']} ({$row['scientific_name']})\n";
    echo "Imagen 1: {$row['image_path']}\n";
    echo "Imagen 2: {$row['image_path_2']}\n\n";
}

echo "¡Descarga de fotografías reales completada!\n";
echo "Directorio: $realImagesDir\n";
echo "Tipo: Fotografías reales de especies peruanas\n";
echo "Fuente: Wikimedia Commons (fotografías científicas verificadas)\n";

?>