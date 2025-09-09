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

// Función para descargar imagen
function downloadImage($url, $destination) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $data = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200 && $data !== false) {
        file_put_contents($destination, $data);
        return true;
    }
    return false;
}

// Función para generar nombre de archivo seguro
function generateSafeFilename($scientificName, $imageIndex, $url) {
    $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
    if (empty($extension)) {
        $extension = 'jpg'; // Extensión por defecto
    }
    
    $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $scientificName);
    return strtolower($safeName) . '_' . $imageIndex . '.' . $extension;
}

// Obtener todas las especies con URLs externas
$query = "SELECT id, name, scientific_name, image_path, image_path_2, image_path_3, image_path_4 
          FROM biodiversity_categories 
          WHERE image_path LIKE 'http%' 
             OR image_path_2 LIKE 'http%' 
             OR image_path_3 LIKE 'http%' 
             OR image_path_4 LIKE 'http%'";

$stmt = $pdo->query($query);
$species = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Encontradas " . count($species) . " especies con imágenes externas.\n\n";

$downloadedCount = 0;
$errorCount = 0;

foreach ($species as $specie) {
    echo "Procesando: {$specie['name']} ({$specie['scientific_name']})\n";
    
    $imageFields = ['image_path', 'image_path_2', 'image_path_3', 'image_path_4'];
    $newPaths = [];
    
    foreach ($imageFields as $index => $field) {
        if (!empty($specie[$field]) && strpos($specie[$field], 'http') === 0) {
            $imageIndex = $index + 1;
            $filename = generateSafeFilename($specie['scientific_name'], $imageIndex, $specie[$field]);
            $localPath = $imageDir . '/' . $filename;
            
            echo "  Descargando imagen $imageIndex: {$specie[$field]}\n";
            
            if (downloadImage($specie[$field], $localPath)) {
                $newPaths[$field] = 'images/especies/' . $filename;
                echo "    ✓ Descargada: $filename\n";
                $downloadedCount++;
            } else {
                echo "    ✗ Error al descargar imagen $imageIndex\n";
                $newPaths[$field] = $specie[$field]; // Mantener URL original si falla
                $errorCount++;
            }
        } else {
            $newPaths[$field] = $specie[$field]; // Mantener valor original
        }
    }
    
    // Actualizar base de datos con las nuevas rutas
    $updateQuery = "UPDATE biodiversity_categories 
                    SET image_path = :image_path,
                        image_path_2 = :image_path_2,
                        image_path_3 = :image_path_3,
                        image_path_4 = :image_path_4
                    WHERE id = :id";
    
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->execute([
        ':image_path' => $newPaths['image_path'],
        ':image_path_2' => $newPaths['image_path_2'],
        ':image_path_3' => $newPaths['image_path_3'],
        ':image_path_4' => $newPaths['image_path_4'],
        ':id' => $specie['id']
    ]);
    
    echo "  Base de datos actualizada.\n\n";
}

echo "=== RESUMEN ===\n";
echo "Imágenes descargadas exitosamente: $downloadedCount\n";
echo "Errores de descarga: $errorCount\n";
echo "Proceso completado.\n";

?>