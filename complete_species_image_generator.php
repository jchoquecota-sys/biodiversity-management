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

// Crear directorio para imágenes si no existe
$imageDir = 'public/images/species';
if (!is_dir($imageDir)) {
    mkdir($imageDir, 0755, true);
    echo "Directorio creado: $imageDir\n";
}

echo "\n=== GENERADOR COMPLETO DE IMÁGENES DE ESPECIES ===\n\n";

// Función para crear imagen de especie con diseño mejorado
function createSpeciesImage($filename, $speciesName, $scientificName, $type = 'default') {
    echo "Creando imagen: $speciesName\n";
    
    // Crear imagen de 800x600 pixels
    $image = imagecreate(800, 600);
    
    // Colores base
    $bgColor = imagecolorallocate($image, 248, 249, 250);
    $textColor = imagecolorallocate($image, 33, 37, 41);
    $borderColor = imagecolorallocate($image, 108, 117, 125);
    
    // Colores específicos por tipo de especie
    $colors = [
        'mammal' => ['primary' => [139, 69, 19], 'secondary' => [160, 82, 45]],      // Marrón
        'bird' => ['primary' => [0, 100, 200], 'secondary' => [30, 144, 255]],       // Azul
        'reptile' => ['primary' => [0, 150, 0], 'secondary' => [34, 197, 94]],       // Verde
        'amphibian' => ['primary' => [128, 0, 128], 'secondary' => [147, 51, 234]],  // Púrpura
        'marine' => ['primary' => [0, 150, 255], 'secondary' => [59, 130, 246]],     // Azul marino
        'fish' => ['primary' => [255, 165, 0], 'secondary' => [251, 191, 36]],        // Naranja
        'insect' => ['primary' => [255, 193, 7], 'secondary' => [250, 204, 21]],      // Amarillo
        'plant' => ['primary' => [40, 167, 69], 'secondary' => [34, 197, 94]],        // Verde planta
        'default' => ['primary' => [108, 117, 125], 'secondary' => [134, 142, 150]]  // Gris
    ];
    
    $colorScheme = $colors[$type] ?? $colors['default'];
    $primaryColor = imagecolorallocate($image, $colorScheme['primary'][0], $colorScheme['primary'][1], $colorScheme['primary'][2]);
    $secondaryColor = imagecolorallocate($image, $colorScheme['secondary'][0], $colorScheme['secondary'][1], $colorScheme['secondary'][2]);
    
    // Rellenar fondo
    imagefill($image, 0, 0, $bgColor);
    
    // Dibujar borde decorativo
    imagerectangle($image, 0, 0, 799, 599, $borderColor);
    imagerectangle($image, 5, 5, 794, 594, $primaryColor);
    
    // Dibujar forma central (círculo o elipse)
    $centerX = 400;
    $centerY = 250;
    $width = 180;
    $height = 120;
    
    // Forma diferente según el tipo
    switch ($type) {
        case 'bird':
            // Forma de ave (elipse)
            imagefilledellipse($image, $centerX, $centerY, $width, $height, $primaryColor);
            break;
        case 'mammal':
            // Forma de mamífero (rectángulo redondeado)
            imagefilledrectangle($image, $centerX - $width/2, $centerY - $height/2, $centerX + $width/2, $centerY + $height/2, $primaryColor);
            break;
        case 'marine':
            // Forma de ola
            $points = [
                $centerX - $width/2, $centerY + $height/4,
                $centerX - $width/4, $centerY - $height/4,
                $centerX, $centerY + $height/4,
                $centerX + $width/4, $centerY - $height/4,
                $centerX + $width/2, $centerY + $height/4
            ];
            imagefilledpolygon($image, $points, 5, $primaryColor);
            break;
        default:
            // Círculo por defecto
            imagefilledellipse($image, $centerX, $centerY, $width, $height, $primaryColor);
    }
    
    // Agregar patrón decorativo
    for ($i = 0; $i < 5; $i++) {
        $x = $centerX + rand(-80, 80);
        $y = $centerY + rand(-60, 60);
        imagefilledellipse($image, $x, $y, 8, 8, $secondaryColor);
    }
    
    // Agregar texto principal
    $fontSize = 5;
    $text = $speciesName;
    $textWidth = imagefontwidth($fontSize) * strlen($text);
    $textX = (800 - $textWidth) / 2;
    $textY = 400;
    
    imagestring($image, $fontSize, $textX, $textY, $text, $textColor);
    
    // Agregar nombre científico
    $sciText = "($scientificName)";
    $sciTextWidth = imagefontwidth(3) * strlen($sciText);
    $sciTextX = (800 - $sciTextWidth) / 2;
    $sciTextY = 430;
    
    imagestring($image, 3, $sciTextX, $sciTextY, $sciText, $textColor);
    
    // Agregar información adicional
    imagestring($image, 3, 350, 460, "Especie Peruana", $textColor);
    imagestring($image, 3, 350, 480, "Biodiversidad", $textColor);
    imagestring($image, 3, 350, 500, "Conservación", $textColor);
    
    // Agregar marca de agua
    imagestring($image, 2, 10, 570, "Peru Biodiversity", $borderColor);
    
    // Guardar imagen
    if (imagejpeg($image, $filename, 90)) {
        echo "✓ Imagen creada: $filename\n";
        imagedestroy($image);
        return true;
    } else {
        echo "✗ Error al crear imagen\n";
        imagedestroy($image);
        return false;
    }
}

// Función para determinar tipo de especie
function determineSpeciesType($scientificName, $commonName) {
    $scientificName = strtolower($scientificName);
    $commonName = strtolower($commonName);
    
    // Mamíferos
    if (strpos($scientificName, 'vicugna') !== false || strpos($scientificName, 'puma') !== false || 
        strpos($scientificName, 'chinchilla') !== false || strpos($scientificName, 'abrocoma') !== false ||
        strpos($scientificName, 'abrothrix') !== false || strpos($scientificName, 'akodon') !== false ||
        strpos($scientificName, 'amorphochilus') !== false || strpos($scientificName, 'arctocephalus') !== false ||
        strpos($scientificName, 'conepatus') !== false || strpos($scientificName, 'ctenomys') !== false) {
        return 'mammal';
    }
    
    // Aves
    if (strpos($scientificName, 'vultur') !== false || strpos($scientificName, 'phoenicoparrus') !== false ||
        strpos($scientificName, 'pelecanus') !== false || strpos($scientificName, 'phalacrocorax') !== false ||
        strpos($scientificName, 'sula') !== false || strpos($scientificName, 'rhea') !== false) {
        return 'bird';
    }
    
    // Reptiles
    if (strpos($scientificName, 'liolaemus') !== false || strpos($scientificName, 'microlophus') !== false ||
        strpos($scientificName, 'phyllodactylus') !== false || strpos($scientificName, 'tachymenis') !== false ||
        strpos($scientificName, 'pseudalsophis') !== false) {
        return 'reptile';
    }
    
    // Anfibios
    if (strpos($scientificName, 'rhinella') !== false || strpos($scientificName, 'telmatobius') !== false ||
        strpos($scientificName, 'pleurodema') !== false) {
        return 'amphibian';
    }
    
    // Especies marinas
    if (strpos($scientificName, 'delphinus') !== false || strpos($scientificName, 'tursiops') !== false ||
        strpos($scientificName, 'megaptera') !== false) {
        return 'marine';
    }
    
    return 'default';
}

// Obtener todas las especies que no tienen imágenes locales
$stmt = $pdo->query("SELECT id, name, scientific_name FROM biodiversity_categories 
                     WHERE (image_path IS NULL OR image_path = '' OR image_path LIKE 'http%') 
                     ORDER BY id");
$species = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Procesando " . count($species) . " especies sin imágenes locales...\n\n";

$createdCount = 0;
$updatedCount = 0;
$processedCount = 0;

foreach ($species as $specie) {
    $processedCount++;
    echo "=== [$processedCount/" . count($species) . "] {$specie['name']} ({$specie['scientific_name']}) ===\n";
    
    // Determinar tipo de especie
    $type = determineSpeciesType($specie['scientific_name'], $specie['name']);
    echo "Tipo detectado: $type\n";
    
    $imagePaths = [];
    $successCount = 0;
    
    // Crear 4 imágenes para cada especie
    for ($i = 1; $i <= 4; $i++) {
        $filename = "species_{$specie['id']}_{$i}.jpg";
        $filepath = "$imageDir/$filename";
        
        if (createSpeciesImage($filepath, $specie['name'], $specie['scientific_name'], $type)) {
            $imagePaths[] = "images/species/$filename";
            $successCount++;
        }
        
        echo "\n";
    }
    
    // Actualizar base de datos con rutas locales
    if ($successCount > 0) {
        $updateSql = "UPDATE biodiversity_categories SET 
                        image_path = :image_path,
                        image_path_2 = :image_path_2,
                        image_path_3 = :image_path_3,
                        image_path_4 = :image_path_4,
                        updated_at = NOW()
                      WHERE id = :id";
        
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->execute([
            ':image_path' => $imagePaths[0] ?? null,
            ':image_path_2' => $imagePaths[1] ?? null,
            ':image_path_3' => $imagePaths[2] ?? null,
            ':image_path_4' => $imagePaths[3] ?? null,
            ':id' => $specie['id']
        ]);
        
        echo "✓ Base de datos actualizada con $successCount imágenes\n";
        $updatedCount++;
    }
    
    $createdCount += $successCount;
    echo "---\n\n";
    
    // Pausa cada 10 especies para evitar sobrecarga
    if ($processedCount % 10 == 0) {
        echo "Pausa de procesamiento... ($processedCount especies procesadas)\n\n";
        sleep(1);
    }
}

echo "=== RESUMEN COMPLETO DE GENERACIÓN ===\n";
echo "Especies procesadas: $processedCount\n";
echo "Imágenes creadas: $createdCount\n";
echo "Especies actualizadas: $updatedCount\n\n";

// Mostrar estadísticas finales
$stmt = $pdo->query("SELECT 
    COUNT(*) as total,
    COUNT(CASE WHEN image_path IS NOT NULL AND image_path != '' THEN 1 END) as with_images,
    COUNT(CASE WHEN image_path LIKE 'images/%' THEN 1 END) as with_local_images,
    COUNT(CASE WHEN image_path LIKE 'http%' THEN 1 END) as with_external_images
    FROM biodiversity_categories");

$stats = $stmt->fetch(PDO::FETCH_ASSOC);

echo "=== ESTADÍSTICAS FINALES ===\n";
echo "Total de especies: {$stats['total']}\n";
echo "Con imágenes: {$stats['with_images']}\n";
echo "Con imágenes locales: {$stats['with_local_images']}\n";
echo "Con imágenes externas: {$stats['with_external_images']}\n";
echo "Porcentaje con imágenes: " . round(($stats['with_images'] / $stats['total']) * 100, 2) . "%\n\n";

// Contar imágenes por tipo
$stmt = $pdo->query("SELECT 
    COUNT(CASE WHEN scientific_name LIKE '%vicugna%' OR scientific_name LIKE '%puma%' OR scientific_name LIKE '%chinchilla%' THEN 1 END) as mammals,
    COUNT(CASE WHEN scientific_name LIKE '%vultur%' OR scientific_name LIKE '%phoenicoparrus%' OR scientific_name LIKE '%pelecanus%' THEN 1 END) as birds,
    COUNT(CASE WHEN scientific_name LIKE '%liolaemus%' OR scientific_name LIKE '%microlophus%' THEN 1 END) as reptiles,
    COUNT(CASE WHEN scientific_name LIKE '%rhinella%' OR scientific_name LIKE '%telmatobius%' OR scientific_name LIKE '%pleurodema%' THEN 1 END) as amphibians,
    COUNT(CASE WHEN scientific_name LIKE '%delphinus%' OR scientific_name LIKE '%tursiops%' OR scientific_name LIKE '%megaptera%' THEN 1 END) as marine
    FROM biodiversity_categories 
    WHERE image_path LIKE 'images/%'");

$typeStats = $stmt->fetch(PDO::FETCH_ASSOC);

echo "=== DISTRIBUCIÓN POR TIPO ===\n";
echo "Mamíferos: {$typeStats['mammals']}\n";
echo "Aves: {$typeStats['birds']}\n";
echo "Reptiles: {$typeStats['reptiles']}\n";
echo "Anfibios: {$typeStats['amphibians']}\n";
echo "Especies marinas: {$typeStats['marine']}\n\n";

echo "¡Generador completo de imágenes de especies finalizado!\n";
echo "Imágenes guardadas en: $imageDir\n";
echo "Total de imágenes creadas: $createdCount\n";
echo "Cobertura: " . round(($stats['with_images'] / $stats['total']) * 100, 2) . "% de especies\n";

?>
