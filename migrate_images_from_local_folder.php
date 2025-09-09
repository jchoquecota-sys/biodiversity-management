<?php

require_once 'vendor/autoload.php';

// Configuración de ambas bases de datos
$bioserver_host = 'localhost';
$bioserver_dbname = 'bioserver_grt';
$bioserver_username = 'root';
$bioserver_password = '';

$biodiversity_host = 'localhost';
$biodiversity_dbname = 'biodiversity_management';
$biodiversity_username = 'root';
$biodiversity_password = '';

try {
    // Conexión a bioserver_grt
    $bioserver_pdo = new PDO("mysql:host=$bioserver_host;dbname=$bioserver_dbname;charset=utf8mb4", $bioserver_username, $bioserver_password);
    $bioserver_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✓ Conexión exitosa a bioserver_grt\n";
    
    // Conexión a biodiversity_management
    $biodiversity_pdo = new PDO("mysql:host=$biodiversity_host;dbname=$biodiversity_dbname;charset=utf8mb4", $biodiversity_username, $biodiversity_password);
    $biodiversity_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✓ Conexión exitosa a biodiversity_management\n";
    
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

echo "\n=== MIGRACIÓN DE IMÁGENES DESDE CARPETA LOCAL ===\n\n";

// Ruta de la carpeta local con las imágenes
$localImagesPath = 'C:\trae_py\Files Biodiversidad\biodiversidad';
echo "Ruta de imágenes locales: $localImagesPath\n";

// Verificar que la carpeta existe
if (!is_dir($localImagesPath)) {
    die("❌ Error: La carpeta '$localImagesPath' no existe.\n");
}
echo "✓ Carpeta de imágenes encontrada\n";

// Crear directorio para imágenes migradas
$migratedImagesDir = 'public/images/migrated_from_bioserver';
if (!is_dir($migratedImagesDir)) {
    mkdir($migratedImagesDir, 0755, true);
    echo "✓ Directorio creado: $migratedImagesDir\n";
} else {
    echo "✓ Directorio existe: $migratedImagesDir\n";
}

// Función para copiar imagen desde archivo local
function copyImageFromLocal($sourcePath, $destinationPath) {
    if (!file_exists($sourcePath)) {
        return ['success' => false, 'error' => 'Source file not found'];
    }
    
    // Verificar que es una imagen válida
    $imageInfo = @getimagesize($sourcePath);
    if (!$imageInfo) {
        return ['success' => false, 'error' => 'Invalid image file'];
    }
    
    // Copiar archivo
    if (copy($sourcePath, $destinationPath)) {
        return ['success' => true, 'size' => filesize($destinationPath), 'dimensions' => $imageInfo[0] . 'x' . $imageInfo[1]];
    } else {
        return ['success' => false, 'error' => 'Failed to copy file'];
    }
}

// PASO 1: Obtener datos de biodiversidad_imagens
echo "\nPASO 1: Obteniendo datos de biodiversidad_imagens...\n";
try {
    $stmt = $bioserver_pdo->query("SELECT biodiversidad_id, ruta_imagen FROM biodiversidad_imagens WHERE ruta_imagen IS NOT NULL AND ruta_imagen != ''");
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Imágenes encontradas en base de datos: " . count($images) . "\n";
    
    if (empty($images)) {
        echo "No se encontraron imágenes para migrar.\n";
        exit;
    }
    
    // Mostrar algunos ejemplos
    echo "\nEjemplos de imágenes encontradas:\n";
    for ($i = 0; $i < min(5, count($images)); $i++) {
        echo "  - ID: {$images[$i]['biodiversidad_id']}, Ruta: {$images[$i]['ruta_imagen']}\n";
    }
    
} catch (PDOException $e) {
    echo "Error obteniendo imágenes: " . $e->getMessage() . "\n";
    die();
}

// PASO 2: Verificar archivos locales
echo "\nPASO 2: Verificando archivos locales...\n";
$localFiles = [];
$filesInDir = scandir($localImagesPath);
foreach ($filesInDir as $file) {
    if ($file != '.' && $file != '..' && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file)) {
        $localFiles[] = $file;
    }
}
echo "Archivos de imagen encontrados localmente: " . count($localFiles) . "\n";

if (empty($localFiles)) {
    echo "No se encontraron archivos de imagen en la carpeta local.\n";
    exit;
}

// Mostrar algunos archivos locales
echo "\nEjemplos de archivos locales:\n";
for ($i = 0; $i < min(5, count($localFiles)); $i++) {
    echo "  - {$localFiles[$i]}\n";
}

// PASO 3: Migrar imágenes
echo "\nPASO 3: Migrando imágenes...\n";
$totalCopied = 0;
$totalFailed = 0;
$speciesUpdated = 0;
$processedSpecies = [];

foreach ($images as $image) {
    $biodiversidad_id = $image['biodiversidad_id'];
    $ruta_imagen = $image['ruta_imagen'];
    
    // Verificar si ya procesamos esta especie
    if (in_array($biodiversidad_id, $processedSpecies)) {
        continue;
    }
    
    echo "Procesando especie ID: $biodiversidad_id\n";
    echo "  Ruta imagen: $ruta_imagen\n";
    
    // Verificar si existe la especie en biodiversity_categories
    $stmt = $biodiversity_pdo->prepare("SELECT id, name, scientific_name, common_name FROM biodiversity_categories WHERE id = ?");
    $stmt->execute([$biodiversidad_id]);
    $specie = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$specie) {
        echo "  ✗ Especie no encontrada en biodiversity_categories\n";
        continue;
    }
    
    echo "  ✓ Especie encontrada: {$specie['name']} ({$specie['scientific_name']})\n";
    
    // Construir ruta completa del archivo local
    $localImagePath = $localImagesPath . DIRECTORY_SEPARATOR . basename($ruta_imagen);
    echo "  Buscando archivo local: " . basename($ruta_imagen) . "\n";
    
    if (!file_exists($localImagePath)) {
        echo "    ✗ Archivo no encontrado: $localImagePath\n";
        $totalFailed++;
        continue;
    }
    
    // Copiar imagen
    $filename = "{$migratedImagesDir}/species_{$biodiversidad_id}.jpg";
    echo "  Copiando imagen...\n";
    
    $result = copyImageFromLocal($localImagePath, $filename);
    
    if ($result['success']) {
        echo "    ✓ Copiada: $filename ({$result['size']} bytes, {$result['dimensions']})\n";
        
        // Actualizar biodiversity_categories con ruta local
        try {
            $updateSql = "UPDATE biodiversity_categories SET 
                            image_path = :image_path,
                            updated_at = NOW()
                          WHERE id = :id";
            
            $updateStmt = $biodiversity_pdo->prepare($updateSql);
            $updateStmt->execute([
                ':image_path' => "images/migrated_from_bioserver/species_{$biodiversidad_id}.jpg",
                ':id' => $biodiversidad_id
            ]);
            
            echo "    ✓ Base de datos actualizada con ruta local\n";
            $speciesUpdated++;
            $totalCopied++;
            
        } catch (PDOException $e) {
            echo "    ✗ Error actualizando base de datos: " . $e->getMessage() . "\n";
        }
        
    } else {
        echo "    ✗ Error copiando imagen: {$result['error']}\n";
        $totalFailed++;
    }
    
    $processedSpecies[] = $biodiversidad_id;
    echo "\n";
}

// Mostrar resumen
echo "=== RESUMEN DE MIGRACIÓN ===\n";
echo "Especies procesadas: " . count($processedSpecies) . "\n";
echo "Especies actualizadas: $speciesUpdated\n";
echo "Imágenes copiadas exitosamente: $totalCopied\n";
echo "Imágenes fallidas: $totalFailed\n";
echo "Total procesadas: " . ($totalCopied + $totalFailed) . "\n\n";

// Mostrar estadísticas finales
$stmt = $biodiversity_pdo->query("SELECT 
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

// Mostrar ejemplos de imágenes migradas
echo "=== EJEMPLOS DE IMÁGENES MIGRADAS ===\n";
$stmt = $biodiversity_pdo->query("SELECT name, scientific_name, common_name, image_path 
                                 FROM biodiversity_categories 
                                 WHERE image_path LIKE 'images/migrated_from_bioserver/%' 
                                 ORDER BY id LIMIT 10");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "Especie: {$row['name']} ({$row['scientific_name']})\n";
    echo "Nombre común: {$row['common_name']}\n";
    echo "Imagen: {$row['image_path']}\n\n";
}

echo "¡Migración de imágenes completada!\n";
echo "Directorio: $migratedImagesDir\n";
echo "Tipo: Imágenes reales migradas desde carpeta local\n";
echo "Fuente: C:\\trae_py\\Files Biodiversidad\\biodiversidad\n";
echo "Formato: Archivos locales (no URLs externas)\n";
echo "Relación: biodiversidad_id -> biodiversity_categories.id\n\n";

?>
