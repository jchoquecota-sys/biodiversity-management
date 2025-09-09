<?php

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'biodiversity_management';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa a la base de datos.\n";
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Crear directorio para imágenes si no existe
$imageDir = 'public/images/especies';
if (!file_exists($imageDir)) {
    mkdir($imageDir, 0755, true);
    echo "Directorio creado: $imageDir\n";
}

// Función para crear imagen placeholder SVG
function createPlaceholderSVG($speciesName, $scientificName, $filename) {
    $colors = [
        '#2E8B57', // Sea Green
        '#228B22', // Forest Green
        '#32CD32', // Lime Green
        '#6B8E23', // Olive Drab
        '#9ACD32', // Yellow Green
        '#8FBC8F', // Dark Sea Green
        '#90EE90', // Light Green
        '#98FB98'  // Pale Green
    ];
    
    $color = $colors[array_rand($colors)];
    $initials = '';
    
    // Obtener iniciales del nombre científico
    $words = explode(' ', $scientificName);
    foreach ($words as $word) {
        if (!empty($word)) {
            $initials .= strtoupper($word[0]);
        }
        if (strlen($initials) >= 2) break;
    }
    
    $svg = '<?xml version="1.0" encoding="UTF-8"?>
';
    $svg .= '<svg width="400" height="300" xmlns="http://www.w3.org/2000/svg">
';
    $svg .= '<rect width="400" height="300" fill="' . $color . '"/>
';
    $svg .= '<circle cx="200" cy="120" r="40" fill="white" opacity="0.3"/>
';
    $svg .= '<text x="200" y="130" font-family="Arial, sans-serif" font-size="24" font-weight="bold" text-anchor="middle" fill="white">' . htmlspecialchars($initials) . '</text>
';
    $svg .= '<text x="200" y="200" font-family="Arial, sans-serif" font-size="14" font-weight="bold" text-anchor="middle" fill="white">' . htmlspecialchars($speciesName) . '</text>
';
    $svg .= '<text x="200" y="220" font-family="Arial, sans-serif" font-size="12" font-style="italic" text-anchor="middle" fill="white" opacity="0.8">' . htmlspecialchars($scientificName) . '</text>
';
    $svg .= '<text x="200" y="280" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="white" opacity="0.6">Imagen no disponible</text>
';
    $svg .= '</svg>';
    
    return file_put_contents($filename, $svg);
}

// Función para generar nombre de archivo seguro
function generateSafeFilename($scientificName, $imageIndex) {
    $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $scientificName);
    return strtolower($safeName) . '_' . $imageIndex . '.svg';
}

echo "\n=== CREANDO IMÁGENES PLACEHOLDER ===\n";

// Obtener especies sin imágenes (primeras 20 para ejemplo)
$stmt = $pdo->query("SELECT id, name, scientific_name FROM biodiversity_categories WHERE (image_path IS NULL OR image_path = '') LIMIT 20");
$species = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Encontradas " . count($species) . " especies sin imágenes.\n\n";

$createdCount = 0;

foreach ($species as $specie) {
    echo "Procesando: {$specie['name']} ({$specie['scientific_name']})\n";
    
    // Crear imagen placeholder
    $filename = generateSafeFilename($specie['scientific_name'], 1);
    $localPath = $imageDir . '/' . $filename;
    
    if (createPlaceholderSVG($specie['name'], $specie['scientific_name'], $localPath)) {
        $imagePath = 'images/especies/' . $filename;
        
        // Actualizar base de datos
        $updateQuery = "UPDATE biodiversity_categories SET image_path = ? WHERE id = ?";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute([$imagePath, $specie['id']]);
        
        echo "  ✓ Creada imagen placeholder: $filename\n";
        $createdCount++;
    } else {
        echo "  ✗ Error al crear imagen placeholder\n";
    }
}

echo "\n=== RESUMEN ===\n";
echo "Imágenes placeholder creadas: $createdCount\n";
echo "Proceso completado.\n";
echo "\nLas imágenes se encuentran en: $imageDir\n";
echo "Puedes reemplazar estos archivos SVG con imágenes reales (JPG, PNG) manteniendo los mismos nombres.\n";

?>