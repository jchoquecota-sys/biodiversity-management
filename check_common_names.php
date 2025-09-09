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

echo "\n=== VERIFICANDO NOMBRES COMUNES EN LA BASE DE DATOS ===\n\n";

// Mostrar las primeras 20 especies con sus nombres comunes
$stmt = $pdo->query("SELECT id, name, scientific_name, common_name 
                     FROM biodiversity_categories 
                     WHERE common_name IS NOT NULL AND common_name != '' 
                     ORDER BY id LIMIT 20");

echo "Primeras 20 especies con nombres comunes:\n";
echo "ID | Nombre | Nombre Científico | Nombre Común\n";
echo "---|--------|-------------------|-------------\n";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "{$row['id']} | {$row['name']} | {$row['scientific_name']} | {$row['common_name']}\n";
}

echo "\n";

// Contar especies con nombres comunes
$stmt = $pdo->query("SELECT COUNT(*) as total FROM biodiversity_categories WHERE common_name IS NOT NULL AND common_name != ''");
$result = $stmt->fetch(PDO::FETCH_ASSOC);
echo "Total de especies con nombres comunes: {$result['total']}\n";

// Mostrar estadísticas de nombres comunes
$stmt = $pdo->query("SELECT 
    COUNT(*) as total,
    COUNT(CASE WHEN common_name IS NOT NULL AND common_name != '' THEN 1 END) as with_common_names,
    COUNT(CASE WHEN common_name IS NULL OR common_name = '' THEN 1 END) as without_common_names
    FROM biodiversity_categories");

$stats = $stmt->fetch(PDO::FETCH_ASSOC);

echo "\n=== ESTADÍSTICAS DE NOMBRES COMUNES ===\n";
echo "Total de especies: {$stats['total']}\n";
echo "Con nombres comunes: {$stats['with_common_names']}\n";
echo "Sin nombres comunes: {$stats['without_common_names']}\n";
echo "Porcentaje con nombres comunes: " . round(($stats['with_common_names'] / $stats['total']) * 100, 2) . "%\n";

?>
