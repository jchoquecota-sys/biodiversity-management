<?php

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'biodiversity_management';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa a la base de datos.\n\n";
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Verificar estructura de la tabla
echo "=== ESTRUCTURA DE LA TABLA ===\n";
$stmt = $pdo->query("DESCRIBE biodiversity_categories");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}

echo "\n=== ESPECIES EN LA BASE DE DATOS ===\n";
$stmt = $pdo->query("SELECT COUNT(*) as total FROM biodiversity_categories");
$total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
echo "Total de especies: $total\n\n";

// Mostrar primeras 10 especies
$stmt = $pdo->query("SELECT id, name, scientific_name, image_path, image_path_2, image_path_3, image_path_4 FROM biodiversity_categories LIMIT 10");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: {$row['id']} - {$row['name']}\n";
    echo "  Científico: {$row['scientific_name']}\n";
    echo "  Imagen 1: " . ($row['image_path'] ?? 'NULL') . "\n";
    echo "  Imagen 2: " . ($row['image_path_2'] ?? 'NULL') . "\n";
    echo "  Imagen 3: " . ($row['image_path_3'] ?? 'NULL') . "\n";
    echo "  Imagen 4: " . ($row['image_path_4'] ?? 'NULL') . "\n";
    echo "\n";
}

// Verificar especies con URLs externas
echo "=== ESPECIES CON URLs EXTERNAS ===\n";
$stmt = $pdo->query("SELECT COUNT(*) as total FROM biodiversity_categories WHERE image_path LIKE 'http%' OR image_path_2 LIKE 'http%' OR image_path_3 LIKE 'http%' OR image_path_4 LIKE 'http%'");
$totalExternal = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
echo "Especies con URLs externas: $totalExternal\n";

if ($totalExternal > 0) {
    $stmt = $pdo->query("SELECT id, name, image_path, image_path_2, image_path_3, image_path_4 FROM biodiversity_categories WHERE image_path LIKE 'http%' OR image_path_2 LIKE 'http%' OR image_path_3 LIKE 'http%' OR image_path_4 LIKE 'http%' LIMIT 5");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "- {$row['name']}\n";
        if (strpos($row['image_path'], 'http') === 0) echo "  URL 1: {$row['image_path']}\n";
        if (strpos($row['image_path_2'], 'http') === 0) echo "  URL 2: {$row['image_path_2']}\n";
        if (strpos($row['image_path_3'], 'http') === 0) echo "  URL 3: {$row['image_path_3']}\n";
        if (strpos($row['image_path_4'], 'http') === 0) echo "  URL 4: {$row['image_path_4']}\n";
    }
}

?>