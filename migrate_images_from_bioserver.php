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

echo "\n=== MIGRACIÓN DE IMÁGENES DESDE BIOSERVER_GRT ===\n\n";

// CONFIGURACIÓN: URL base del servidor bioserver_grt
// Cambiar esta URL por la correcta de tu servidor
$BIOSERVER_BASE_URL = 'http://localhost:8000/storage/';
echo "URL base configurada: $BIOSERVER_BASE_URL\n";
echo "IMPORTANTE: Si las imágenes no se descargan, ajusta esta URL en el script\n\n";

// Verificar estructura de tabla biodiversidad_imagens
echo "PASO 1: Verificando estructura de tabla biodiversidad_imagens...\n";
try {
    $stmt = $bioserver_pdo->query("DESCRIBE biodiversidad_imagens");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Estructura de biodiversidad_imagens:\n";
    foreach ($columns as $column) {
        echo "  - {$column['Field']} ({$column['Type']})\n";
    }
    
    // Contar registros
    $stmt = $bioserver_pdo->query("SELECT COUNT(*) as total FROM biodiversidad_imagens");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "\nTotal de registros en biodiversidad_imagens: {$count['total']}\n";
    
} catch (PDOException $e) {
    echo "Error verificando tabla: " . $e->getMessage() . "\n";
    die();
}

// Verificar estructura de tabla biodiversity_categories
echo "\nPASO 2: Verificando estructura de tabla biodiversity_categories...\n";
try {
    $stmt = $biodiversity_pdo->query("DESCRIBE biodiversity_categories");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Estructura de biodiversity_categories:\n";
    foreach ($columns as $column) {
        echo "  - {$column['Field']} ({$column['Type']})\n";
    }
    
    // Contar registros
    $stmt = $biodiversity_pdo->query("SELECT COUNT(*) as total FROM biodiversity_categories");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "\nTotal de registros en biodiversity_categories: {$count['total']}\n";
    
} catch (PDOException $e) {
    echo "Error verificando tabla: " . $e->getMessage() . "\n";
    die();
}

// Crear directorio para imágenes migradas
$migratedImagesDir = 'public/images/migrated_from_bioserver';
if (!is_dir($migratedImagesDir)) {
    mkdir($migratedImagesDir, 0755, true);
    echo "\n✓ Directorio creado: $migratedImagesDir\n";
} else {
    echo "\n✓ Directorio existe: $migratedImagesDir\n";
}

// Función para descargar imagen desde URL
function downloadImageFromUrl($url, $filename) {
    if (empty($url)) {
        return ['success' => false, 'error' => 'Empty URL'];
    }
    
    // Si la URL no es completa, construir URL completa
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        // Asumir que es una ruta relativa y construir URL completa
        global $BIOSERVER_BASE_URL;
        $url = $BIOSERVER_BASE_URL . $url;
    }
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
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

// PASO 3: Obtener datos de biodiversidad_imagens
echo "\nPASO 3: Obteniendo datos de biodiversidad_imagens...\n";
try {
    $stmt = $bioserver_pdo->query("SELECT biodiversidad_id, ruta_imagen FROM biodiversidad_imagens WHERE ruta_imagen IS NOT NULL AND ruta_imagen != ''");
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Imágenes encontradas: " . count($images) . "\n";
    
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

// PASO 4: Migrar imágenes
echo "\nPASO 4: Migrando imágenes...\n";
$totalDownloaded = 0;
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
    
    // Descargar imagen
    $filename = "{$migratedImagesDir}/species_{$biodiversidad_id}.jpg";
    echo "  Descargando imagen...\n";
    
    $result = downloadImageFromUrl($ruta_imagen, $filename);
    
    if ($result['success']) {
        echo "    ✓ Descargada: $filename ({$result['size']} bytes, {$result['dimensions']})\n";
        
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
            $totalDownloaded++;
            
        } catch (PDOException $e) {
            echo "    ✗ Error actualizando base de datos: " . $e->getMessage() . "\n";
        }
        
    } else {
        echo "    ✗ Error descargando imagen: {$result['error']}\n";
        $totalFailed++;
    }
    
    $processedSpecies[] = $biodiversidad_id;
    echo "\n";
}

// Mostrar resumen
echo "=== RESUMEN DE MIGRACIÓN ===\n";
echo "Especies procesadas: " . count($processedSpecies) . "\n";
echo "Especies actualizadas: $speciesUpdated\n";
echo "Imágenes descargadas exitosamente: $totalDownloaded\n";
echo "Imágenes fallidas: $totalFailed\n";
echo "Total procesadas: " . ($totalDownloaded + $totalFailed) . "\n\n";

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
echo "Tipo: Imágenes reales migradas desde bioserver_grt\n";
echo "Fuente: Tabla biodiversidad_imagens\n";
echo "Formato: Archivos locales (no URLs externas)\n";
echo "Relación: biodiversidad_id -> biodiversity_categories.id\n\n";

?>
