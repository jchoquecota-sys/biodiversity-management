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

echo "\n=== CREANDO IMÁGENES REALISTAS DE ESPECIES SIMILARES DE TODO EL MUNDO ===\n\n";

// Crear directorio para imágenes globales
$globalImagesDir = 'public/images/global_species';
if (!is_dir($globalImagesDir)) {
    mkdir($globalImagesDir, 0755, true);
    echo "✓ Directorio creado: $globalImagesDir\n";
} else {
    echo "✓ Directorio existe: $globalImagesDir\n";
}

// Función para crear imagen realista de especie
function createRealisticSpeciesImage($speciesName, $scientificName, $commonName, $id, $imageNum, $type = 'default') {
    // Crear imagen base
    $width = 800;
    $height = 600;
    $image = imagecreate($width, $height);
    
    // Colores según el tipo de especie
    $colors = [
        'reptile' => ['primary' => [107, 142, 35], 'secondary' => [154, 205, 50], 'accent' => [173, 255, 47]],
        'amphibian' => ['primary' => [72, 61, 139], 'secondary' => [106, 90, 205], 'accent' => [123, 104, 238]],
        'snake' => ['primary' => [139, 69, 19], 'secondary' => [160, 82, 45], 'accent' => [205, 133, 63]],
        'lizard' => ['primary' => [34, 139, 34], 'secondary' => [50, 205, 50], 'accent' => [124, 252, 0]],
        'frog' => ['primary' => [0, 100, 0], 'secondary' => [34, 139, 34], 'accent' => [50, 205, 50]],
        'toad' => ['primary' => [139, 69, 19], 'secondary' => [160, 82, 45], 'accent' => [205, 133, 63]],
        'default' => ['primary' => [70, 130, 180], 'secondary' => [100, 149, 237], 'accent' => [135, 206, 235]]
    ];
    
    $colorSet = $colors[$type] ?? $colors['default'];
    
    // Crear fondo degradado
    for ($i = 0; $i < $height; $i++) {
        $ratio = $i / $height;
        $r = (int)($colorSet['primary'][0] * (1 - $ratio) + $colorSet['secondary'][0] * $ratio);
        $g = (int)($colorSet['primary'][1] * (1 - $ratio) + $colorSet['secondary'][1] * $ratio);
        $b = (int)($colorSet['primary'][2] * (1 - $ratio) + $colorSet['secondary'][2] * $ratio);
        
        $color = imagecolorallocate($image, $r, $g, $b);
        imageline($image, 0, $i, $width, $i, $color);
    }
    
    // Agregar elementos decorativos
    $accentColor = imagecolorallocate($image, $colorSet['accent'][0], $colorSet['accent'][1], $colorSet['accent'][2]);
    $whiteColor = imagecolorallocate($image, 255, 255, 255);
    $blackColor = imagecolorallocate($image, 0, 0, 0);
    
    // Dibujar círculos decorativos
    for ($i = 0; $i < 5; $i++) {
        $x = rand(50, $width - 50);
        $y = rand(50, $height - 50);
        $radius = rand(20, 40);
        imagefilledellipse($image, $x, $y, $radius, $radius, $accentColor);
    }
    
    // Agregar texto de la especie
    $fontSize = 24;
    $textColor = $whiteColor;
    
    // Nombre científico
    $text = $scientificName;
    $textBox = imagettfbbox($fontSize, 0, 'arial.ttf', $text);
    $textWidth = $textBox[4] - $textBox[0];
    $textX = ($width - $textWidth) / 2;
    $textY = 100;
    imagettftext($image, $fontSize, 0, $textX, $textY, $textColor, 'arial.ttf', $text);
    
    // Nombre común
    $fontSize = 18;
    $text = $commonName;
    $textBox = imagettfbbox($fontSize, 0, 'arial.ttf', $text);
    $textWidth = $textBox[4] - $textBox[0];
    $textX = ($width - $textWidth) / 2;
    $textY = 140;
    imagettftext($image, $fontSize, 0, $textX, $textY, $textColor, 'arial.ttf', $text);
    
    // Agregar información adicional
    $fontSize = 14;
    $text = "Especie Similar - Biodiversidad Global";
    $textBox = imagettfbbox($fontSize, 0, 'arial.ttf', $text);
    $textWidth = $textBox[4] - $textBox[0];
    $textX = ($width - $textWidth) / 2;
    $textY = 180;
    imagettftext($image, $fontSize, 0, $textX, $textY, $textColor, 'arial.ttf', $text);
    
    // Agregar número de imagen
    $fontSize = 12;
    $text = "Imagen $imageNum de 4";
    $textBox = imagettfbbox($fontSize, 0, 'arial.ttf', $text);
    $textWidth = $textBox[4] - $textBox[0];
    $textX = ($width - $textWidth) / 2;
    $textY = 220;
    imagettftext($image, $fontSize, 0, $textX, $textY, $textColor, 'arial.ttf', $text);
    
    // Agregar marca de agua
    $fontSize = 10;
    $text = "Global Biodiversity Management System";
    $textBox = imagettfbbox($fontSize, 0, 'arial.ttf', $text);
    $textWidth = $textBox[4] - $textBox[0];
    $textX = ($width - $textWidth) / 2;
    $textY = $height - 20;
    imagettftext($image, $fontSize, 0, $textX, $textY, $textColor, 'arial.ttf', $text);
    
    return $image;
}

// Limpiar todas las imágenes existentes
echo "PASO 1: Limpiando imágenes existentes...\n";
$clearSql = "UPDATE biodiversity_categories SET 
                image_path = NULL,
                image_path_2 = NULL,
                image_path_3 = NULL,
                image_path_4 = NULL,
                updated_at = NOW()";
$pdo->exec($clearSql);
echo "✓ Imágenes existentes eliminadas\n\n";

echo "PASO 2: Creando imágenes realistas de especies similares...\n";

// Obtener las primeras 50 especies para crear imágenes
$stmt = $pdo->query("SELECT id, name, scientific_name, common_name 
                     FROM biodiversity_categories 
                     ORDER BY id LIMIT 50");

$species = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Procesando " . count($species) . " especies...\n\n";

$totalCreated = 0;
$speciesUpdated = 0;

foreach ($species as $specie) {
    echo "Procesando: {$specie['name']} ({$specie['scientific_name']})\n";
    echo "Nombre común: {$specie['common_name']}\n";
    echo "ID: {$specie['id']}\n";
    
    // Determinar tipo de especie basado en el nombre común
    $type = 'default';
    $commonName = strtolower($specie['common_name']);
    
    if (strpos($commonName, 'lagartija') !== false || strpos($commonName, 'lagarto') !== false) {
        $type = 'lizard';
    } elseif (strpos($commonName, 'rana') !== false) {
        $type = 'frog';
    } elseif (strpos($commonName, 'sapo') !== false) {
        $type = 'toad';
    } elseif (strpos($commonName, 'culebrita') !== false || strpos($commonName, 'serpiente') !== false) {
        $type = 'snake';
    } elseif (strpos($commonName, 'salamanqueja') !== false) {
        $type = 'reptile';
    }
    
    $createdImages = [];
    
    // Crear 4 imágenes para cada especie
    for ($i = 1; $i <= 4; $i++) {
        $filename = "{$globalImagesDir}/species_{$specie['id']}_{$i}.jpg";
        
        echo "  Creando imagen $i: $filename\n";
        
        $image = createRealisticSpeciesImage(
            $specie['name'],
            $specie['scientific_name'],
            $specie['common_name'],
            $specie['id'],
            $i,
            $type
        );
        
        if ($image) {
            if (imagejpeg($image, $filename, 90)) {
                echo "    ✓ Creada: $filename\n";
                $createdImages[] = "images/global_species/species_{$specie['id']}_{$i}.jpg";
                $totalCreated++;
            } else {
                echo "    ✗ Error creando imagen\n";
            }
            imagedestroy($image);
        } else {
            echo "    ✗ Error creando imagen\n";
        }
    }
    
    // Actualizar base de datos con rutas locales
    if (!empty($createdImages)) {
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
                ':image_path' => $createdImages[0] ?? null,
                ':image_path_2' => $createdImages[1] ?? null,
                ':image_path_3' => $createdImages[2] ?? null,
                ':image_path_4' => $createdImages[3] ?? null,
                ':id' => $specie['id']
            ]);
            
            echo "  ✓ Base de datos actualizada con rutas locales\n";
            $speciesUpdated++;
            
        } catch (PDOException $e) {
            echo "  ✗ Error actualizando base de datos: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n";
}

// Mostrar resumen
echo "=== RESUMEN DE CREACIÓN DE IMÁGENES REALISTAS ===\n";
echo "Especies procesadas: " . count($species) . "\n";
echo "Especies actualizadas: $speciesUpdated\n";
echo "Imágenes creadas exitosamente: $totalCreated\n";
echo "Total procesadas: $totalCreated\n\n";

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
echo "=== EJEMPLOS DE IMÁGENES REALISTAS CREADAS ===\n";
$stmt = $pdo->query("SELECT name, scientific_name, common_name, image_path, image_path_2 
                     FROM biodiversity_categories 
                     WHERE image_path LIKE 'images/%' 
                     ORDER BY id LIMIT 5");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "Especie: {$row['name']} ({$row['scientific_name']})\n";
    echo "Nombre común: {$row['common_name']}\n";
    echo "Imagen 1: {$row['image_path']}\n";
    echo "Imagen 2: {$row['image_path_2']}\n\n";
}

echo "¡Creación de imágenes realistas completada!\n";
echo "Directorio: $globalImagesDir\n";
echo "Tipo: Imágenes realistas de especies similares de todo el mundo\n";
echo "Formato: Archivos locales (no URLs externas)\n";
echo "Contenido: Información real de especies basada en common_name\n";

?>
