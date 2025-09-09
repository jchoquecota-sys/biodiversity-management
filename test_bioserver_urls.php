<?php

echo "=== PROBANDO URLs DEL SERVIDOR BIOSERVER_GRT ===\n\n";

// URLs posibles para probar
$possibleUrls = [
    'http://localhost:8000/storage/',
    'http://localhost:8000/public/storage/',
    'http://localhost:8000/storage/app/public/',
    'http://localhost:8000/public/',
    'http://localhost:8000/',
    'http://localhost:8080/storage/',
    'http://localhost:8080/public/storage/',
    'http://localhost:8080/storage/app/public/',
    'http://localhost:8080/public/',
    'http://localhost:8080/',
    'http://localhost:3000/storage/',
    'http://localhost:3000/public/storage/',
    'http://localhost:3000/storage/app/public/',
    'http://localhost:3000/public/',
    'http://localhost:3000/',
];

// Imagen de prueba (una de las rutas encontradas)
$testImagePath = 'biodiversidad/FOiZ3Q4iwdYOo0Lld3SC5m07IJ1bzTKY6RNN8HyP.jpg';

echo "Probando imagen: $testImagePath\n\n";

foreach ($possibleUrls as $baseUrl) {
    $fullUrl = $baseUrl . $testImagePath;
    echo "Probando: $fullUrl\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $fullUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_NOBODY, true); // Solo HEAD request para verificar
    
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "  ✗ Error: $error\n";
    } elseif ($httpCode == 200) {
        echo "  ✓ ¡ÉXITO! HTTP 200 - URL válida\n";
        echo "  URL BASE RECOMENDADA: $baseUrl\n\n";
        
        // Probar descarga real
        echo "  Probando descarga real...\n";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fullUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $imageData = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            echo "    ✗ Error descargando: $error\n";
        } elseif ($httpCode == 200 && $imageData) {
            $imageInfo = @getimagesizefromstring($imageData);
            if ($imageInfo) {
                echo "    ✓ Descarga exitosa: " . strlen($imageData) . " bytes, {$imageInfo[0]}x{$imageInfo[1]}\n";
                echo "    ✓ Esta URL funciona correctamente para la migración\n\n";
                
                // Guardar URL base en archivo de configuración
                file_put_contents('bioserver_config.txt', $baseUrl);
                echo "    ✓ URL base guardada en bioserver_config.txt\n";
                break;
            } else {
                echo "    ✗ Datos recibidos pero no es una imagen válida\n";
            }
        } else {
            echo "    ✗ Error HTTP: $httpCode\n";
        }
    } else {
        echo "  ✗ HTTP $httpCode\n";
    }
    echo "\n";
}

echo "=== INSTRUCCIONES ===\n";
echo "1. Si encontraste una URL que funciona, úsala en el script de migración\n";
echo "2. Si ninguna funciona, verifica que el servidor bioserver_grt esté ejecutándose\n";
echo "3. Puedes modificar el script migrate_images_from_bioserver.php con la URL correcta\n";
echo "4. También puedes probar con URLs externas si el servidor está en otro dominio\n\n";

echo "=== URLs ALTERNATIVAS PARA PROBAR ===\n";
echo "Si tu servidor bioserver_grt está en otro dominio, prueba con:\n";
echo "- http://tu-dominio.com/storage/\n";
echo "- http://tu-dominio.com/public/storage/\n";
echo "- http://tu-dominio.com/storage/app/public/\n";
echo "- http://tu-dominio.com/public/\n";
echo "- http://tu-dominio.com/\n\n";

?>
