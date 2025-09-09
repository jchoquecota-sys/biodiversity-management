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
    echo json_encode(['error' => 'Error de conexión: ' . $e->getMessage()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

// Leer datos JSON del cuerpo de la petición
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['species_id']) || !isset($input['image_path'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Faltan parámetros requeridos: species_id e image_path']);
    exit;
}

$speciesId = intval($input['species_id']);
$imagePath = $input['image_path'];

// Validar que la especie existe
try {
    $stmt = $pdo->prepare("SELECT id, scientific_name, common_name, image_path FROM biodiversity_categories WHERE id = ?");
    $stmt->execute([$speciesId]);
    $species = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$species) {
        http_response_code(404);
        echo json_encode(['error' => 'Especie no encontrada']);
        exit;
    }
    
    // Validar que el archivo de imagen existe
    $fullImagePath = 'public/' . $imagePath;
    if (!file_exists($fullImagePath)) {
        http_response_code(404);
        echo json_encode(['error' => 'Archivo de imagen no encontrado: ' . $imagePath]);
        exit;
    }
    
    // Guardar imagen anterior si existe
    $previousImage = $species['image_path'];
    
    // Actualizar la especie con la nueva imagen
    $updateStmt = $pdo->prepare("UPDATE biodiversity_categories SET image_path = ?, updated_at = NOW() WHERE id = ?");
    $updateStmt->execute([$imagePath, $speciesId]);
    
    // Registrar la asociación en un log (opcional)
    $logStmt = $pdo->prepare("
        INSERT INTO image_associations_log (species_id, species_name, image_path, previous_image_path, associated_at) 
        VALUES (?, ?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE 
        image_path = VALUES(image_path), 
        previous_image_path = VALUES(previous_image_path), 
        associated_at = VALUES(associated_at)
    ");
    
    // Crear tabla de log si no existe
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS image_associations_log (
            id INT AUTO_INCREMENT PRIMARY KEY,
            species_id INT NOT NULL,
            species_name VARCHAR(255) NOT NULL,
            image_path VARCHAR(500) NOT NULL,
            previous_image_path VARCHAR(500) NULL,
            associated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_species_id (species_id),
            INDEX idx_associated_at (associated_at)
        )
    ");
    
    $logStmt->execute([
        $speciesId,
        $species['scientific_name'],
        $imagePath,
        $previousImage
    ]);
    
    // Obtener información actualizada de la especie
    $stmt->execute([$speciesId]);
    $updatedSpecies = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'message' => 'Imagen asociada exitosamente',
        'species' => [
            'id' => $updatedSpecies['id'],
            'scientific_name' => $updatedSpecies['scientific_name'],
            'common_name' => $updatedSpecies['common_name'],
            'image_path' => $updatedSpecies['image_path']
        ],
        'association' => [
            'image_path' => $imagePath,
            'previous_image' => $previousImage,
            'timestamp' => date('Y-m-d H:i:s')
        ]
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Error en la base de datos: ' . $e->getMessage(),
        'success' => false
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Error del servidor: ' . $e->getMessage(),
        'success' => false
    ]);
}
?>