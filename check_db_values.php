<?php

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'biodiversity_management';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa a la base de datos\n\n";
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Verificar valores únicos de conservation_status
echo "=== VALORES DE CONSERVATION_STATUS ===\n";
$stmt = $pdo->query("SELECT DISTINCT conservation_status FROM biodiversity_categories WHERE conservation_status IS NOT NULL LIMIT 10");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "- '{$row['conservation_status']}'\n";
}

// Verificar valores únicos de kingdom
echo "\n=== VALORES DE KINGDOM ===\n";
$stmt = $pdo->query("SELECT DISTINCT kingdom FROM biodiversity_categories WHERE kingdom IS NOT NULL LIMIT 10");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "- '{$row['kingdom']}'\n";
}

// Verificar estructura de la tabla
echo "\n=== ESTRUCTURA DE LA TABLA ===\n";
$stmt = $pdo->query("DESCRIBE biodiversity_categories");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "{$row['Field']} - {$row['Type']} - {$row['Null']} - {$row['Default']}\n";
}

// Verificar si existen códigos de conservation_status
echo "\n=== TABLA CONSERVATION_STATUSES ===\n";
try {
    $stmt = $pdo->query("SELECT code, name FROM conservation_statuses LIMIT 10");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "Código: '{$row['code']}' - Nombre: '{$row['name']}'\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

?>