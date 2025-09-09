<?php

/**
 * EJEMPLO DE USO - MOSTRAR FOTOS DE ESPECIES PERUANAS
 * 
 * Este archivo demuestra cómo usar las fotos reales agregadas a la tabla biodiversity_categories
 * Incluye ejemplos de consultas, visualización y manejo de imágenes externas
 */

require_once 'vendor/autoload.php';

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'biodiversity_management';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa a la base de datos\n\n";
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

echo "=== EJEMPLO DE USO: FOTOS DE ESPECIES PERUANAS ===\n\n";

// 1. CONSULTAR ESPECIES CON FOTOS REALES
echo "1. ESPECIES CON FOTOS EXTERNAS (URLs):\n";
echo "----------------------------------------\n";

$sql = "SELECT id, name, scientific_name, conservation_status, kingdom, 
               image_path, image_path_2, image_path_3, image_path_4
        FROM biodiversity_categories 
        WHERE (image_path LIKE 'https://%' OR image_path LIKE 'http://%')
        ORDER BY id DESC 
        LIMIT 10";

$stmt = $pdo->query($sql);
$speciesWithPhotos = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($speciesWithPhotos as $species) {
    echo "ID: {$species['id']}\n";
    echo "Nombre: {$species['name']}\n";
    echo "Nombre científico: {$species['scientific_name']}\n";
    echo "Estado: {$species['conservation_status']}\n";
    echo "Reino: {$species['kingdom']}\n";
    
    // Contar imágenes disponibles
    $imageCount = 0;
    $images = [$species['image_path'], $species['image_path_2'], $species['image_path_3'], $species['image_path_4']];
    foreach ($images as $img) {
        if (!empty($img)) $imageCount++;
    }
    
    echo "Imágenes disponibles: $imageCount\n";
    echo "Foto principal: {$species['image_path']}\n";
    echo "---\n";
}

// 2. EJEMPLO DE GALERÍA HTML
echo "\n2. GENERANDO GALERÍA HTML:\n";
echo "---------------------------\n";

$htmlGallery = "<!DOCTYPE html>\n<html lang='es'>\n<head>\n";
$htmlGallery .= "    <meta charset='UTF-8'>\n";
$htmlGallery .= "    <meta name='viewport' content='width=device-width, initial-scale=1.0'>\n";
$htmlGallery .= "    <title>Galería de Especies Peruanas</title>\n";
$htmlGallery .= "    <style>\n";
$htmlGallery .= "        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5; }\n";
$htmlGallery .= "        .gallery { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }\n";
$htmlGallery .= "        .species-card { background: white; border-radius: 10px; padding: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }\n";
$htmlGallery .= "        .species-card h3 { color: #2c5530; margin-top: 0; }\n";
$htmlGallery .= "        .scientific-name { font-style: italic; color: #666; margin-bottom: 10px; }\n";
$htmlGallery .= "        .main-image { width: 100%; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 10px; }\n";
$htmlGallery .= "        .thumbnail-gallery { display: flex; gap: 5px; margin-top: 10px; }\n";
$htmlGallery .= "        .thumbnail { width: 60px; height: 60px; object-fit: cover; border-radius: 4px; cursor: pointer; border: 2px solid transparent; }\n";
$htmlGallery .= "        .thumbnail:hover { border-color: #2c5530; }\n";
$htmlGallery .= "        .status-badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }\n";
$htmlGallery .= "        .status-VU { background-color: #ffeaa7; color: #d63031; }\n";
$htmlGallery .= "        .status-LC { background-color: #a8e6cf; color: #00b894; }\n";
$htmlGallery .= "        .status-NT { background-color: #fdcb6e; color: #e17055; }\n";
$htmlGallery .= "        .status-EN { background-color: #fab1a0; color: #e17055; }\n";
$htmlGallery .= "        .status-CR { background-color: #ff7675; color: #d63031; }\n";
$htmlGallery .= "        .image-source { font-size: 11px; color: #999; margin-top: 5px; }\n";
$htmlGallery .= "    </style>\n";
$htmlGallery .= "</head>\n<body>\n";
$htmlGallery .= "    <h1>🦋 Galería de Especies Peruanas</h1>\n";
$htmlGallery .= "    <p>Especies nativas del Perú con fotografías de alta calidad de Wikimedia Commons</p>\n";
$htmlGallery .= "    <div class='gallery'>\n";

// Generar tarjetas para cada especie
foreach ($speciesWithPhotos as $species) {
    $images = array_filter([$species['image_path'], $species['image_path_2'], $species['image_path_3'], $species['image_path_4']]);
    
    if (!empty($images)) {
        $mainImage = $images[0];
        $statusClass = 'status-' . $species['conservation_status'];
        
        $htmlGallery .= "        <div class='species-card'>\n";
        $htmlGallery .= "            <h3>{$species['name']}</h3>\n";
        $htmlGallery .= "            <div class='scientific-name'>{$species['scientific_name']}</div>\n";
        $htmlGallery .= "            <img src='$mainImage' alt='{$species['name']}' class='main-image' id='main-{$species['id']}'>\n";
        $htmlGallery .= "            <div>\n";
        $htmlGallery .= "                <span class='status-badge $statusClass'>{$species['conservation_status']}</span>\n";
        $htmlGallery .= "                <span style='margin-left: 10px; color: #666;'>Reino: {$species['kingdom']}</span>\n";
        $htmlGallery .= "            </div>\n";
        
        // Miniaturas si hay múltiples imágenes
        if (count($images) > 1) {
            $htmlGallery .= "            <div class='thumbnail-gallery'>\n";
            foreach ($images as $index => $img) {
                $htmlGallery .= "                <img src='$img' alt='Foto " . ($index + 1) . "' class='thumbnail' onclick=\"document.getElementById('main-{$species['id']}').src='$img'\">\n";
            }
            $htmlGallery .= "            </div>\n";
        }
        
        $htmlGallery .= "            <div class='image-source'>📸 Fuente: Wikimedia Commons (Creative Commons)</div>\n";
        $htmlGallery .= "        </div>\n";
    }
}

$htmlGallery .= "    </div>\n";
$htmlGallery .= "    <script>\n";
$htmlGallery .= "        // Funcionalidad adicional para la galería\n";
$htmlGallery .= "        document.addEventListener('DOMContentLoaded', function() {\n";
$htmlGallery .= "            console.log('Galería de especies peruanas cargada exitosamente');\n";
$htmlGallery .= "        });\n";
$htmlGallery .= "    </script>\n";
$htmlGallery .= "</body>\n</html>";

// Guardar el archivo HTML
file_put_contents('galeria_especies_peruanas.html', $htmlGallery);
echo "✓ Galería HTML generada: galeria_especies_peruanas.html\n";

// 3. EJEMPLO DE API JSON
echo "\n3. EJEMPLO DE API JSON:\n";
echo "----------------------\n";

$apiData = [
    'status' => 'success',
    'message' => 'Especies peruanas con fotos reales',
    'data' => [
        'total_species' => count($speciesWithPhotos),
        'source' => 'Wikimedia Commons',
        'license' => 'Creative Commons',
        'species' => []
    ]
];

foreach ($speciesWithPhotos as $species) {
    $images = array_filter([$species['image_path'], $species['image_path_2'], $species['image_path_3'], $species['image_path_4']]);
    
    $apiData['data']['species'][] = [
        'id' => (int)$species['id'],
        'name' => $species['name'],
        'scientific_name' => $species['scientific_name'],
        'conservation_status' => $species['conservation_status'],
        'kingdom' => $species['kingdom'],
        'images' => [
            'count' => count($images),
            'urls' => array_values($images),
            'main' => $images[0] ?? null
        ]
    ];
}

$jsonOutput = json_encode($apiData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
file_put_contents('api_especies_peruanas.json', $jsonOutput);
echo "✓ API JSON generada: api_especies_peruanas.json\n";
echo "Ejemplo de respuesta JSON:\n";
echo substr($jsonOutput, 0, 500) . "...\n";

// 4. ESTADÍSTICAS
echo "\n4. ESTADÍSTICAS DE IMÁGENES:\n";
echo "----------------------------\n";

$stats = [
    'total_species_with_photos' => 0,
    'total_images' => 0,
    'external_urls' => 0,
    'conservation_status_count' => [],
    'image_sources' => []
];

foreach ($speciesWithPhotos as $species) {
    $images = array_filter([$species['image_path'], $species['image_path_2'], $species['image_path_3'], $species['image_path_4']]);
    
    if (!empty($images)) {
        $stats['total_species_with_photos']++;
        $stats['total_images'] += count($images);
        
        // Contar URLs externas
        foreach ($images as $img) {
            if (str_starts_with($img, 'http')) {
                $stats['external_urls']++;
                
                // Detectar fuente
                if (str_contains($img, 'wikimedia.org')) {
                    $stats['image_sources']['Wikimedia Commons'] = ($stats['image_sources']['Wikimedia Commons'] ?? 0) + 1;
                }
            }
        }
        
        // Contar por estado de conservación
        $status = $species['conservation_status'];
        $stats['conservation_status_count'][$status] = ($stats['conservation_status_count'][$status] ?? 0) + 1;
    }
}

echo "📊 Especies con fotos: {$stats['total_species_with_photos']}\n";
echo "📸 Total de imágenes: {$stats['total_images']}\n";
echo "🌐 URLs externas: {$stats['external_urls']}\n";
echo "\n📋 Por estado de conservación:\n";
foreach ($stats['conservation_status_count'] as $status => $count) {
    echo "   $status: $count especies\n";
}
echo "\n🔗 Fuentes de imágenes:\n";
foreach ($stats['image_sources'] as $source => $count) {
    echo "   $source: $count imágenes\n";
}

echo "\n=== EJEMPLO COMPLETADO ===\n";
echo "✓ Archivos generados:\n";
echo "  - galeria_especies_peruanas.html (Galería visual)\n";
echo "  - api_especies_peruanas.json (API de datos)\n";
echo "\n🎯 Las fotos están listas para ser utilizadas en la aplicación web!\n";
echo "📝 Recuerda: Todas las imágenes son de Wikimedia Commons con licencia Creative Commons\n";

?>