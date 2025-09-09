<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

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

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$itemsPerPage = 10;
$offset = ($page - 1) * $itemsPerPage;

$whereClause = '';
$params = [];

// Determinar tipo de búsqueda
if (isset($_GET['without_images']) && $_GET['without_images'] == '1') {
    // Especies sin imágenes
    $whereClause = 'WHERE (image_path IS NULL OR image_path = "")';
} elseif (isset($_GET['all']) && $_GET['all'] == '1') {
    // Todas las especies
    $whereClause = '';
} elseif (isset($_GET['q']) && !empty($_GET['q'])) {
    // Búsqueda por texto
    $query = '%' . $_GET['q'] . '%';
    $whereClause = 'WHERE (scientific_name LIKE ? OR common_name LIKE ?)';
    $params = [$query, $query];
}

try {
    // Contar total de resultados
    $countSql = "SELECT COUNT(*) as total FROM biodiversity_categories $whereClause";
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute($params);
    $totalItems = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalItems / $itemsPerPage);
    
    // Obtener resultados paginados
    $sql = "SELECT id, scientific_name, common_name, image_path, conservation_status, habitat 
            FROM biodiversity_categories 
            $whereClause 
            ORDER BY scientific_name ASC 
            LIMIT $itemsPerPage OFFSET $offset";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $species = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Formatear resultados
    $formattedSpecies = array_map(function($sp) {
        return [
            'id' => $sp['id'],
            'scientific_name' => $sp['scientific_name'],
            'common_name' => $sp['common_name'],
            'image_path' => $sp['image_path'],
            'conservation_status' => $sp['conservation_status'],
            'habitat' => $sp['habitat'],
            'has_image' => !empty($sp['image_path'])
        ];
    }, $species);
    
    echo json_encode([
        'success' => true,
        'species' => $formattedSpecies,
        'pagination' => [
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'itemsPerPage' => $itemsPerPage
        ],
        'currentPage' => $page,
        'totalPages' => $totalPages
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
}
?>