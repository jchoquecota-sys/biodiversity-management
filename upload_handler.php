<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'biodiversity_management';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión a la base de datos: ' . $e->getMessage()]);
    exit;
}

// Función para generar nombre de archivo seguro
function generateSafeFilename($originalName, $speciesName = '') {
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    
    if (!empty($speciesName)) {
        // Usar nombre de especie si está disponible
        $baseName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $speciesName);
        $baseName = trim($baseName, '_');
    } else {
        // Usar nombre original limpio
        $baseName = pathinfo($originalName, PATHINFO_FILENAME);
        $baseName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $baseName);
    }
    
    return strtolower($baseName) . '.' . $extension;
}

// Función para determinar categoría basada en nombre científico
function determineCategory($scientificName, $commonName) {
    $name = strtolower($scientificName . ' ' . $commonName);
    
    // Patrones para cada categoría
    $patterns = [
        'reptiles' => ['gecko', 'iguana', 'lizard', 'snake', 'boa', 'liolaemus', 'microlophus', 'stenocercus', 'tropidurus'],
        'anfibios' => ['frog', 'toad', 'salamander', 'rhinella', 'telmatobius', 'gastrotheca', 'pristimantis'],
        'mamiferos' => ['mammal', 'bat', 'mouse', 'bear', 'cat', 'vicugna', 'lama', 'chinchilla', 'oso', 'gato'],
        'aves' => ['bird', 'eagle', 'condor', 'hummingbird', 'vultur', 'falco', 'gallito', 'colibrí'],
        'peces' => ['fish', 'shark', 'ray', 'salmon', 'trout', 'pez', 'tiburón'],
        'plantas' => ['plant', 'tree', 'flower', 'grass', 'fern', 'planta', 'árbol', 'flor']
    ];
    
    foreach ($patterns as $category => $keywords) {
        foreach ($keywords as $keyword) {
            if (strpos($name, $keyword) !== false) {
                return $category;
            }
        }
    }
    
    return 'otros';
}

// Función para redimensionar imagen
function resizeImage($sourcePath, $targetPath, $maxWidth = 800, $maxHeight = 600) {
    $imageInfo = getimagesize($sourcePath);
    if (!$imageInfo) return false;
    
    $sourceWidth = $imageInfo[0];
    $sourceHeight = $imageInfo[1];
    $mimeType = $imageInfo['mime'];
    
    // Calcular nuevas dimensiones manteniendo proporción
    $ratio = min($maxWidth / $sourceWidth, $maxHeight / $sourceHeight);
    $newWidth = round($sourceWidth * $ratio);
    $newHeight = round($sourceHeight * $ratio);
    
    // Crear imagen desde el archivo fuente
    switch ($mimeType) {
        case 'image/jpeg':
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case 'image/png':
            $sourceImage = imagecreatefrompng($sourcePath);
            break;
        case 'image/gif':
            $sourceImage = imagecreatefromgif($sourcePath);
            break;
        default:
            return false;
    }
    
    if (!$sourceImage) return false;
    
    // Crear nueva imagen redimensionada
    $targetImage = imagecreatetruecolor($newWidth, $newHeight);
    
    // Preservar transparencia para PNG
    if ($mimeType == 'image/png') {
        imagealphablending($targetImage, false);
        imagesavealpha($targetImage, true);
        $transparent = imagecolorallocatealpha($targetImage, 255, 255, 255, 127);
        imagefilledrectangle($targetImage, 0, 0, $newWidth, $newHeight, $transparent);
    }
    
    // Redimensionar
    imagecopyresampled($targetImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $sourceWidth, $sourceHeight);
    
    // Guardar imagen redimensionada
    $result = false;
    switch ($mimeType) {
        case 'image/jpeg':
            $result = imagejpeg($targetImage, $targetPath, 85);
            break;
        case 'image/png':
            $result = imagepng($targetImage, $targetPath, 8);
            break;
        case 'image/gif':
            $result = imagegif($targetImage, $targetPath);
            break;
    }
    
    // Limpiar memoria
    imagedestroy($sourceImage);
    imagedestroy($targetImage);
    
    return $result;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

if (!isset($_FILES['images']) || !isset($_POST['category'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Faltan parámetros requeridos']);
    exit;
}

$category = $_POST['category'];
$allowedCategories = ['reptiles', 'anfibios', 'mamiferos', 'aves', 'peces', 'plantas', 'otros'];

if (!in_array($category, $allowedCategories)) {
    http_response_code(400);
    echo json_encode(['error' => 'Categoría no válida']);
    exit;
}

// Crear directorio si no existe
$uploadDir = "public/images/especies/$category/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$results = [];
$files = $_FILES['images'];

// Manejar múltiples archivos
if (is_array($files['name'])) {
    $fileCount = count($files['name']);
    for ($i = 0; $i < $fileCount; $i++) {
        $results[] = processFile([
            'name' => $files['name'][$i],
            'type' => $files['type'][$i],
            'tmp_name' => $files['tmp_name'][$i],
            'error' => $files['error'][$i],
            'size' => $files['size'][$i]
        ], $category, $uploadDir, $pdo);
    }
} else {
    $results[] = processFile($files, $category, $uploadDir, $pdo);
}

echo json_encode([
    'success' => true,
    'results' => $results,
    'summary' => [
        'total' => count($results),
        'successful' => count(array_filter($results, function($r) { return $r['success']; })),
        'failed' => count(array_filter($results, function($r) { return !$r['success']; }))
    ]
]);

function processFile($file, $category, $uploadDir, $pdo) {
    // Validar archivo
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Error en la subida del archivo', 'file' => $file['name']];
    }
    
    // Validar tipo de archivo
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'];
    if (!in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'error' => 'Tipo de archivo no permitido', 'file' => $file['name']];
    }
    
    // Validar tamaño (máximo 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        return ['success' => false, 'error' => 'Archivo demasiado grande (máximo 5MB)', 'file' => $file['name']];
    }
    
    $originalName = $file['name'];
    $tempPath = $file['tmp_name'];
    
    // Generar nombre de archivo seguro
    $safeFilename = generateSafeFilename($originalName);
    $targetPath = $uploadDir . $safeFilename;
    
    try {
        // Si es SVG, copiar directamente
        if ($file['type'] === 'image/svg+xml') {
            if (!move_uploaded_file($tempPath, $targetPath)) {
                return ['success' => false, 'error' => 'Error al mover el archivo SVG', 'file' => $originalName];
            }
        } else {
            // Para otros formatos, redimensionar y optimizar
            if (!resizeImage($tempPath, $targetPath)) {
                // Si falla el redimensionamiento, intentar mover el archivo original
                if (!move_uploaded_file($tempPath, $targetPath)) {
                    return ['success' => false, 'error' => 'Error al procesar la imagen', 'file' => $originalName];
                }
            }
        }
        
        // Buscar especies que podrían corresponder a esta imagen
        $baseFilename = pathinfo($safeFilename, PATHINFO_FILENAME);
        
        // Intentar encontrar especie por nombre de archivo
        $stmt = $pdo->prepare("
            SELECT id, scientific_name, common_name 
            FROM biodiversity_categories 
            WHERE LOWER(REPLACE(scientific_name, ' ', '_')) LIKE ? 
               OR LOWER(REPLACE(common_name, ' ', '_')) LIKE ?
               OR LOWER(REPLACE(CONCAT(scientific_name, '_', common_name), ' ', '_')) LIKE ?
            LIMIT 1
        ");
        
        $searchPattern = '%' . str_replace('_', '%', $baseFilename) . '%';
        $stmt->execute([$searchPattern, $searchPattern, $searchPattern]);
        $species = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $relativePath = "images/especies/$category/$safeFilename";
        
        if ($species) {
            // Actualizar especie específica
            $updateStmt = $pdo->prepare("UPDATE biodiversity_categories SET image_path = ? WHERE id = ?");
            $updateStmt->execute([$relativePath, $species['id']]);
            
            return [
                'success' => true,
                'file' => $originalName,
                'saved_as' => $safeFilename,
                'path' => $relativePath,
                'species_updated' => $species['scientific_name'],
                'action' => 'updated_specific_species'
            ];
        } else {
            // Imagen subida pero no asociada a especie específica
            return [
                'success' => true,
                'file' => $originalName,
                'saved_as' => $safeFilename,
                'path' => $relativePath,
                'species_updated' => null,
                'action' => 'uploaded_without_species_match',
                'note' => 'Imagen guardada. Puedes asociarla manualmente a una especie.'
            ];
        }
        
    } catch (Exception $e) {
        return ['success' => false, 'error' => 'Error del servidor: ' . $e->getMessage(), 'file' => $originalName];
    }
}
?>