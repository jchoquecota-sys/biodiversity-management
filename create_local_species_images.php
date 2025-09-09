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

echo "\n=== CREANDO IMÁGENES LOCALES DE ESPECIES ===\n\n";

// Función para crear imagen de prueba
function createTestImage($filename, $speciesName, $color = 'green') {
    echo "Creando imagen de prueba: $speciesName\n";
    
    // Crear imagen de 800x600 pixels
    $image = imagecreate(800, 600);
    
    // Colores
    $bgColor = imagecolorallocate($image, 240, 240, 240);
    $textColor = imagecolorallocate($image, 0, 0, 0);
    $borderColor = imagecolorallocate($image, 100, 100, 100);
    
    // Color específico por tipo de especie
    switch ($color) {
        case 'mammal':
            $speciesColor = imagecolorallocate($image, 139, 69, 19); // Marrón
            break;
        case 'bird':
            $speciesColor = imagecolorallocate($image, 0, 100, 200); // Azul
            break;
        case 'reptile':
            $speciesColor = imagecolorallocate($image, 0, 150, 0); // Verde
            break;
        case 'marine':
            $speciesColor = imagecolorallocate($image, 0, 150, 255); // Azul marino
            break;
        default:
            $speciesColor = imagecolorallocate($image, 100, 100, 100); // Gris
    }
    
    // Rellenar fondo
    imagefill($image, 0, 0, $bgColor);
    
    // Dibujar borde
    imagerectangle($image, 0, 0, 799, 599, $borderColor);
    
    // Dibujar círculo central
    imagefilledellipse($image, 400, 300, 200, 200, $speciesColor);
    
    // Agregar texto
    $fontSize = 5;
    $text = $speciesName;
    $textWidth = imagefontwidth($fontSize) * strlen($text);
    $textX = (800 - $textWidth) / 2;
    $textY = 450;
    
    imagestring($image, $fontSize, $textX, $textY, $text, $textColor);
    
    // Agregar texto adicional
    imagestring($image, 3, 350, 500, "Especie Peruana", $textColor);
    imagestring($image, 3, 350, 520, "Biodiversidad", $textColor);
    
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

// Obtener especies de la base de datos
$stmt = $pdo->query("SELECT id, name, scientific_name FROM biodiversity_categories ORDER BY id LIMIT 20");
$species = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Creando imágenes para " . count($species) . " especies...\n\n";

$createdCount = 0;
$updatedCount = 0;

foreach ($species as $specie) {
    echo "=== {$specie['name']} ({$specie['scientific_name']}) ===\n";
    
    $imagePaths = [];
    $successCount = 0;
    
    // Determinar tipo de especie por nombre científico
    $scientificName = strtolower($specie['scientific_name']);
    $color = 'default';
    
    if (strpos($scientificName, 'vicugna') !== false || strpos($scientificName, 'puma') !== false || strpos($scientificName, 'chinchilla') !== false) {
        $color = 'mammal';
    } elseif (strpos($scientificName, 'vultur') !== false || strpos($scientificName, 'phoenicoparrus') !== false || strpos($scientificName, 'pelecanus') !== false) {
        $color = 'bird';
    } elseif (strpos($scientificName, 'liolaemus') !== false || strpos($scientificName, 'microlophus') !== false) {
        $color = 'reptile';
    } elseif (strpos($scientificName, 'arctocephalus') !== false || strpos($scientificName, 'delphinus') !== false || strpos($scientificName, 'tursiops') !== false) {
        $color = 'marine';
    }
    
    // Crear 4 imágenes para cada especie
    for ($i = 1; $i <= 4; $i++) {
        $filename = "species_{$specie['id']}_{$i}.jpg";
        $filepath = "$imageDir/$filename";
        
        if (createTestImage($filepath, $specie['name'], $color)) {
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
}

echo "=== RESUMEN DE CREACIÓN DE IMÁGENES ===\n";
echo "Especies procesadas: " . count($species) . "\n";
echo "Imágenes creadas: $createdCount\n";
echo "Especies actualizadas: $updatedCount\n\n";

// Mostrar estadísticas finales
$stmt = $pdo->query("SELECT 
    COUNT(*) as total,
    COUNT(CASE WHEN image_path IS NOT NULL AND image_path != '' THEN 1 END) as with_images,
    COUNT(CASE WHEN image_path LIKE 'images/%' THEN 1 END) as with_local_images
    FROM biodiversity_categories");

$stats = $stmt->fetch(PDO::FETCH_ASSOC);

echo "=== ESTADÍSTICAS FINALES ===\n";
echo "Total de especies: {$stats['total']}\n";
echo "Con imágenes: {$stats['with_images']}\n";
echo "Con imágenes locales: {$stats['with_local_images']}\n";
echo "Porcentaje con imágenes: " . round(($stats['with_images'] / $stats['total']) * 100, 2) . "%\n\n";

// Mostrar ejemplos de imágenes creadas
echo "=== EJEMPLOS DE IMÁGENES CREADAS ===\n";
$stmt = $pdo->query("SELECT name, scientific_name, image_path, image_path_2 
                     FROM biodiversity_categories 
                     WHERE image_path LIKE 'images/%' 
                     ORDER BY id LIMIT 5");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "Especie: {$row['name']} ({$row['scientific_name']})\n";
    echo "Imagen 1: {$row['image_path']}\n";
    echo "Imagen 2: {$row['image_path_2']}\n\n";
}

echo "¡Sistema de creación de imágenes locales completado!\n";
echo "Imágenes guardadas en: $imageDir\n";
echo "Tipo: Imágenes de prueba generadas localmente\n";
echo "Formato: JPEG 800x600 pixels\n";

?>
