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

echo "\n=== ANÁLISIS DE ESPECIES EN LA TABLA ===\n\n";

// Contar total de especies
$stmt = $pdo->query("SELECT COUNT(*) as total FROM biodiversity_categories");
$result = $stmt->fetch(PDO::FETCH_ASSOC);
echo "Total de especies en la tabla: " . $result['total'] . "\n\n";

// Mostrar algunas especies de ejemplo
$stmt = $pdo->query("SELECT id, name, scientific_name FROM biodiversity_categories ORDER BY id LIMIT 10");
$species = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Primeras 10 especies:\n";
foreach ($species as $specie) {
    echo "- ID: {$specie['id']} | {$specie['name']} ({$specie['scientific_name']})\n";
}

echo "\n";

// Contar especies con y sin imágenes
$stmt = $pdo->query("SELECT 
    COUNT(*) as total,
    COUNT(CASE WHEN image_path IS NOT NULL AND image_path != '' THEN 1 END) as with_images,
    COUNT(CASE WHEN image_path IS NULL OR image_path = '' THEN 1 END) as without_images
    FROM biodiversity_categories");

$stats = $stmt->fetch(PDO::FETCH_ASSOC);

echo "=== ESTADÍSTICAS DE IMÁGENES ===\n";
echo "Total de especies: {$stats['total']}\n";
echo "Con imágenes: {$stats['with_images']}\n";
echo "Sin imágenes: {$stats['without_images']}\n";
echo "Porcentaje con imágenes: " . round(($stats['with_images'] / $stats['total']) * 100, 2) . "%\n\n";

?>
