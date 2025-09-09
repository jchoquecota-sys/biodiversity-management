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

echo "\n=== PROBANDO DESCARGA DE IMÁGENES ===\n\n";

// Función para probar descarga de imagen
function testImageDownload($url, $speciesName) {
    echo "Probando: {$speciesName}\n";
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

// Obtener algunas especies con imágenes para probar
$stmt = $pdo->query("SELECT name, scientific_name, image_path, image_path_2 
                     FROM biodiversity_categories 
                     WHERE image_path LIKE 'http%' 
                     ORDER BY id LIMIT 5");
$species = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Probando descarga de imágenes para " . count($species) . " especies...\n\n";

$successCount = 0;
$totalTests = 0;

foreach ($species as $specie) {
    echo "=== {$specie['name']} ({$specie['scientific_name']}) ===\n";
    
    // Probar imagen principal
    if (testImageDownload($specie['image_path'], "Imagen principal")) {
        $successCount++;
    }
    $totalTests++;
    
    // Probar segunda imagen
    if (testImageDownload($specie['image_path_2'], "Segunda imagen")) {
        $successCount++;
    }
    $totalTests++;
    
    echo "---\n\n";
}

// Mostrar resumen
echo "=== RESUMEN DE PRUEBAS ===\n";
echo "Total de pruebas: $totalTests\n";
echo "Descargas exitosas: $successCount\n";
echo "Descargas fallidas: " . ($totalTests - $successCount) . "\n";
echo "Tasa de éxito: " . round(($successCount / $totalTests) * 100, 2) . "%\n\n";

// Probar algunas URLs específicas conocidas de Wikimedia Commons
echo "=== PROBANDO URLs ESPECÍFICAS DE WIKIMEDIA COMMONS ===\n";

$testUrls = [
    'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3b/Vicugna_vicugna_1_fcm.jpg/800px-Vicugna_vicugna_1_fcm.jpg',
    'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6e/Andean_Condor.jpg/800px-Andean_Condor.jpg',
    'https://upload.wikimedia.org/wikipedia/commons/thumb/7/70/Puma_face.jpg/600px-Puma_face.jpg'
];

$wikimediaSuccess = 0;
foreach ($testUrls as $url) {
    if (testImageDownload($url, "Wikimedia Commons")) {
        $wikimediaSuccess++;
    }
}

echo "URLs de Wikimedia Commons exitosas: $wikimediaSuccess/" . count($testUrls) . "\n\n";

// Recomendaciones
echo "=== RECOMENDACIONES ===\n";
if ($successCount / $totalTests < 0.5) {
    echo "⚠️  Muchas URLs no son funcionales. Se recomienda:\n";
    echo "1. Usar URLs reales de Wikimedia Commons\n";
    echo "2. Verificar que las imágenes existan antes de agregarlas\n";
    echo "3. Considerar usar imágenes locales como respaldo\n";
} else {
    echo "✓ Las URLs están funcionando correctamente\n";
    echo "✓ Las imágenes se pueden descargar sin problemas\n";
    echo "✓ El sistema está listo para mostrar imágenes\n";
}

echo "\n¡Prueba de descarga completada!\n";

?>
