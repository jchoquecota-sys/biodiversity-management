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

// Buscar las especies que agregamos anteriormente
echo "=== BUSCANDO ESPECIES AGREGADAS ===\n";
$speciesNames = [
    'Oso de Anteojos',
    'Gallito de las Rocas Peruano', 
    'Cóndor Andino',
    'Vicuña',
    'Jaguar'
];

foreach ($speciesNames as $speciesName) {
    $stmt = $pdo->prepare("SELECT id, name, scientific_name, image_path, image_path_2, image_path_3, image_path_4 FROM biodiversity_categories WHERE name LIKE ?");
    $stmt->execute(["%$speciesName%"]);
    
    $found = false;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $found = true;
        echo "✓ Encontrada: {$row['name']} ({$row['scientific_name']})\n";
        echo "  ID: {$row['id']}\n";
        echo "  Imagen 1: " . ($row['image_path'] ?? 'NULL') . "\n";
        echo "  Imagen 2: " . ($row['image_path_2'] ?? 'NULL') . "\n";
        echo "  Imagen 3: " . ($row['image_path_3'] ?? 'NULL') . "\n";
        echo "  Imagen 4: " . ($row['image_path_4'] ?? 'NULL') . "\n";
        echo "\n";
    }
    
    if (!$found) {
        echo "✗ No encontrada: $speciesName\n\n";
    }
}

// Buscar por nombres científicos también
echo "=== BUSCANDO POR NOMBRES CIENTÍFICOS ===\n";
$scientificNames = [
    'Tremarctos ornatus',
    'Rupicola peruvianus',
    'Vultur gryphus',
    'Vicugna vicugna',
    'Panthera onca'
];

foreach ($scientificNames as $scientificName) {
    $stmt = $pdo->prepare("SELECT id, name, scientific_name, image_path, image_path_2, image_path_3, image_path_4 FROM biodiversity_categories WHERE scientific_name LIKE ?");
    $stmt->execute(["%$scientificName%"]);
    
    $found = false;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $found = true;
        echo "✓ Encontrada: {$row['name']} ({$row['scientific_name']})\n";
        echo "  ID: {$row['id']}\n";
        echo "  Imagen 1: " . ($row['image_path'] ?? 'NULL') . "\n";
        echo "  Imagen 2: " . ($row['image_path_2'] ?? 'NULL') . "\n";
        echo "  Imagen 3: " . ($row['image_path_3'] ?? 'NULL') . "\n";
        echo "  Imagen 4: " . ($row['image_path_4'] ?? 'NULL') . "\n";
        echo "\n";
    }
    
    if (!$found) {
        echo "✗ No encontrada: $scientificName\n\n";
    }
}

// Verificar las últimas especies agregadas
echo "=== ÚLTIMAS 10 ESPECIES AGREGADAS ===\n";
$stmt = $pdo->query("SELECT id, name, scientific_name, image_path, created_at FROM biodiversity_categories ORDER BY id DESC LIMIT 10");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: {$row['id']} - {$row['name']} ({$row['scientific_name']})\n";
    echo "  Imagen: " . ($row['image_path'] ?? 'NULL') . "\n";
    echo "  Creado: " . ($row['created_at'] ?? 'NULL') . "\n\n";
}

?>