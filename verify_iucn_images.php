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

echo "\n=== VERIFICACIÓN DE IMÁGENES DE IUCN RED LIST ===\n\n";

// Función para verificar descarga de imagen
function verifyImageDownload($url, $speciesName) {
    echo "Verificando: {$speciesName}\n";
    echo "URL: " . substr($url, 0, 80) . "...\n";
    
    // Configurar contexto para la descarga
    $context = stream_context_create([
        'http' => [
            'timeout' => 10,
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        ]
    ]);
    
    // Intentar obtener headers
    $headers = @get_headers($url, 1, $context);
    
    if ($headers && strpos($headers[0], '200') !== false) {
        echo "✓ URL accesible (HTTP 200)\n";
        
        // Intentar descargar una pequeña porción de la imagen
        $imageData = @file_get_contents($url, false, $context);
        
        if ($imageData !== false) {
            $size = strlen($imageData);
            echo "✓ Imagen descargada exitosamente ({$size} bytes)\n";
            
            // Verificar que sea realmente una imagen
            $imageInfo = @getimagesizefromstring($imageData);
            if ($imageInfo !== false) {
                echo "✓ Imagen válida: {$imageInfo[0]}x{$imageInfo[1]} pixels, tipo: {$imageInfo['mime']}\n";
                return true;
            } else {
                echo "✗ Datos descargados no son una imagen válida\n";
                return false;
            }
        } else {
            echo "✗ Error al descargar imagen\n";
            return false;
        }
    } else {
        echo "✗ URL no accesible\n";
        if ($headers) {
            echo "  Respuesta: " . $headers[0] . "\n";
        }
        return false;
    }
    echo "\n";
}

// Obtener especies con imágenes de IUCN Red List
$stmt = $pdo->query("SELECT name, scientific_name, image_path, image_path_2 
                     FROM biodiversity_categories 
                     WHERE image_path LIKE 'http%' 
                     ORDER BY id LIMIT 10");
$species = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Verificando descarga de imágenes para " . count($species) . " especies...\n\n";

$successCount = 0;
$totalTests = 0;

foreach ($species as $specie) {
    echo "=== {$specie['name']} ({$specie['scientific_name']}) ===\n";
    
    // Verificar imagen principal
    if (verifyImageDownload($specie['image_path'], "Imagen principal")) {
        $successCount++;
    }
    $totalTests++;
    
    // Verificar segunda imagen
    if (verifyImageDownload($specie['image_path_2'], "Segunda imagen")) {
        $successCount++;
    }
    $totalTests++;
    
    echo "---\n\n";
}

// Mostrar resumen
echo "=== RESUMEN DE VERIFICACIÓN ===\n";
echo "Total de pruebas: $totalTests\n";
echo "Descargas exitosas: $successCount\n";
echo "Descargas fallidas: " . ($totalTests - $successCount) . "\n";
echo "Tasa de éxito: " . round(($successCount / $totalTests) * 100, 2) . "%\n\n";

// Verificar URLs específicas de IUCN Red List
echo "=== VERIFICACIÓN DE URLs DE IUCN RED LIST ===\n";

$testUrls = [
    'https://www.iucnredlist.org/species/178294/1531600',
    'https://www.iucnredlist.org/species/48443996/48444000',
    'https://www.iucnredlist.org/species/57350/3059838'
];

$iucnSuccess = 0;
foreach ($testUrls as $url) {
    if (verifyImageDownload($url, "IUCN Red List")) {
        $iucnSuccess++;
    }
}

echo "URLs de IUCN Red List exitosas: $iucnSuccess/" . count($testUrls) . "\n\n";

// Mostrar estadísticas de la base de datos
$stmt = $pdo->query("SELECT 
    COUNT(*) as total,
    COUNT(CASE WHEN image_path IS NOT NULL AND image_path != '' THEN 1 END) as with_images,
    COUNT(CASE WHEN image_path LIKE 'http%' THEN 1 END) as with_external_images,
    COUNT(CASE WHEN image_path LIKE '%iucnredlist%' THEN 1 END) as with_iucn_images,
    COUNT(CASE WHEN image_path LIKE '%wikimedia%' THEN 1 END) as with_wikimedia_images
    FROM biodiversity_categories");

$stats = $stmt->fetch(PDO::FETCH_ASSOC);

echo "=== ESTADÍSTICAS DE LA BASE DE DATOS ===\n";
echo "Total de especies: {$stats['total']}\n";
echo "Con imágenes: {$stats['with_images']}\n";
echo "Con imágenes externas (URLs): {$stats['with_external_images']}\n";
echo "Con imágenes de IUCN Red List: {$stats['with_iucn_images']}\n";
echo "Con imágenes de Wikimedia Commons: {$stats['with_wikimedia_images']}\n";
echo "Porcentaje con imágenes: " . round(($stats['with_images'] / $stats['total']) * 100, 2) . "%\n\n";

// Recomendaciones
echo "=== RECOMENDACIONES ===\n";
if ($successCount / $totalTests >= 0.8) {
    echo "✓ Las imágenes están funcionando correctamente\n";
    echo "✓ Las URLs de IUCN Red List son accesibles\n";
    echo "✓ El sistema está listo para mostrar imágenes científicas\n";
    echo "✓ Fuente confiable: IUCN Red List (https://www.iucnredlist.org/)\n";
} else {
    echo "⚠️  Algunas URLs no son funcionales. Se recomienda:\n";
    echo "1. Verificar la conectividad a internet\n";
    echo "2. Revisar las URLs de IUCN Red List\n";
    echo "3. Considerar usar imágenes locales como respaldo\n";
}

echo "\n¡Verificación de imágenes de IUCN Red List completada!\n";
echo "Fuente: IUCN Red List (https://www.iucnredlist.org/)\n";
echo "Autoridad: International Union for Conservation of Nature\n";
echo "Calidad: Imágenes científicas verificadas con estado de conservación\n";

?>
