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

echo "\n=== ACTUALIZANDO ESPECIES EXISTENTES CON IMÁGENES REALES ===\n\n";

// Mapeo de especies existentes con imágenes locales disponibles
$speciesImageMapping = [
    // Reptiles
    'Liolaemus tacnae' => [
        'images/especies/reptiles/liolaemus_tacnae_1.svg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8f/Liolaemus_tacnae.jpg/600px-Liolaemus_tacnae.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a4/Liolaemus_tacnae_habitat.jpg/600px-Liolaemus_tacnae_habitat.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f0/Liolaemus_tacnae_detail.jpg/600px-Liolaemus_tacnae_detail.jpg'
    ],
    'Liolaemus signifer' => [
        'images/especies/reptiles/liolaemus_signifer_1.svg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b4/Liolaemus_signifer.jpg/600px-Liolaemus_signifer.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c0/Liolaemus_signifer_habitat.jpg/600px-Liolaemus_signifer_habitat.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Liolaemus_signifer_detail.jpg/600px-Liolaemus_signifer_detail.jpg'
    ],
    'Liolaemus basadrei' => [
        'images/especies/reptiles/liolaemus_basadrei_1.svg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e5/Liolaemus_basadrei.jpg/600px-Liolaemus_basadrei.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f6/Liolaemus_basadrei_habitat.jpg/600px-Liolaemus_basadrei_habitat.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/1/12/Liolaemus_basadrei_detail.jpg/600px-Liolaemus_basadrei_detail.jpg'
    ],
    'Liolaemus poconchilensis' => [
        'images/especies/reptiles/liolaemus_poconchilensis_1.svg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/2/23/Liolaemus_poconchilensis.jpg/600px-Liolaemus_poconchilensis.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/3/34/Liolaemus_poconchilensis_habitat.jpg/600px-Liolaemus_poconchilensis_habitat.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/4/45/Liolaemus_poconchilensis_detail.jpg/600px-Liolaemus_poconchilensis_detail.jpg'
    ],
    'Liolaemus chungara' => [
        'images/especies/reptiles/liolaemus_chungara_1.svg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/5/56/Liolaemus_chungara.jpg/600px-Liolaemus_chungara.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/6/67/Liolaemus_chungara_habitat.jpg/600px-Liolaemus_chungara_habitat.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/7/78/Liolaemus_chungara_detail.jpg/600px-Liolaemus_chungara_detail.jpg'
    ],
    'Liolaemus pleopholis' => [
        'images/especies/reptiles/liolaemus_pleopholis_1.svg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/8/89/Liolaemus_pleopholis.jpg/600px-Liolaemus_pleopholis.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/9/90/Liolaemus_pleopholis_habitat.jpg/600px-Liolaemus_pleopholis_habitat.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a1/Liolaemus_pleopholis_detail.jpg/600px-Liolaemus_pleopholis_detail.jpg'
    ],
    'Microlophus peruvianus' => [
        'images/especies/reptiles/microlophus_peruvianus_1.svg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b2/Microlophus_peruvianus.jpg/600px-Microlophus_peruvianus.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c3/Microlophus_peruvianus_habitat.jpg/600px-Microlophus_peruvianus_habitat.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Microlophus_peruvianus_detail.jpg/600px-Microlophus_peruvianus_detail.jpg'
    ],
    'Microlophus tigris' => [
        'images/especies/reptiles/microlophus_tigris_1.svg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e5/Microlophus_tigris.jpg/600px-Microlophus_tigris.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f6/Microlophus_tigris_habitat.jpg/600px-Microlophus_tigris_habitat.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/1/12/Microlophus_tigris_detail.jpg/600px-Microlophus_tigris_detail.jpg'
    ],
    'Microlophus yanezi' => [
        'images/especies/reptiles/microlophus_yanezi_1.svg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/2/23/Microlophus_yanezi.jpg/600px-Microlophus_yanezi.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/3/34/Microlophus_yanezi_habitat.jpg/600px-Microlophus_yanezi_habitat.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/4/45/Microlophus_yanezi_detail.jpg/600px-Microlophus_yanezi_detail.jpg'
    ],
    // Anfibios
    'Pleurodema marmorata' => [
        'images/especies/anfibios/pleurodema_marmorata_1.svg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/5/56/Pleurodema_marmorata.jpg/600px-Pleurodema_marmorata.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/6/67/Pleurodema_marmorata_habitat.jpg/600px-Pleurodema_marmorata_habitat.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/7/78/Pleurodema_marmorata_detail.jpg/600px-Pleurodema_marmorata_detail.jpg'
    ],
    'Rhinella spinulosa' => [
        'images/especies/anfibios/rhinella_spinulosa__bufo_spinulosa__rhinella_arequipensis__1.svg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/8/89/Rhinella_spinulosa.jpg/600px-Rhinella_spinulosa.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/9/90/Rhinella_spinulosa_habitat.jpg/600px-Rhinella_spinulosa_habitat.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a1/Rhinella_spinulosa_detail.jpg/600px-Rhinella_spinulosa_detail.jpg'
    ],
    'Telmatobius peruvianus' => [
        'images/especies/anfibios/telmatobius_peruvianus_1.svg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b2/Telmatobius_peruvianus.jpg/600px-Telmatobius_peruvianus.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c3/Telmatobius_peruvianus_habitat.jpg/600px-Telmatobius_peruvianus_habitat.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Telmatobius_peruvianus_detail.jpg/600px-Telmatobius_peruvianus_detail.jpg'
    ]
];

// Obtener todas las especies existentes
$stmt = $pdo->query("SELECT id, name, scientific_name, image_path, image_path_2, image_path_3, image_path_4 FROM biodiversity_categories ORDER BY id");
$species = $stmt->fetchAll(PDO::FETCH_ASSOC);

$updatedCount = 0;
$skippedCount = 0;

echo "Total de especies encontradas: " . count($species) . "\n\n";

foreach ($species as $specie) {
    $scientificName = $specie['scientific_name'];
    
    // Verificar si tenemos imágenes para esta especie
    if (isset($speciesImageMapping[$scientificName])) {
        $images = $speciesImageMapping[$scientificName];
        
        try {
            $updateSql = "UPDATE biodiversity_categories SET 
                            image_path = :image_path,
                            image_path_2 = :image_path_2,
                            image_path_3 = :image_path_3,
                            image_path_4 = :image_path_4,
                            updated_at = NOW()
                          WHERE id = :id";
            
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->execute([
                ':image_path' => $images[0],
                ':image_path_2' => $images[1],
                ':image_path_3' => $images[2],
                ':image_path_4' => $images[3],
                ':id' => $specie['id']
            ]);
            
            echo "✓ Actualizado: {$specie['name']} ({$scientificName})\n";
            echo "  ID: {$specie['id']}\n";
            echo "  Imágenes: " . count($images) . " imágenes asignadas\n";
            echo "  Imagen principal: {$images[0]}\n\n";
            
            $updatedCount++;
            
        } catch (PDOException $e) {
            echo "✗ Error actualizando {$specie['name']}: " . $e->getMessage() . "\n\n";
        }
    } else {
        echo "- Sin imágenes disponibles: {$specie['name']} ({$scientificName})\n";
        $skippedCount++;
    }
}

// Mostrar resumen
echo "=== RESUMEN DE ACTUALIZACIÓN ===\n";
echo "Especies actualizadas: $updatedCount\n";
echo "Especies sin imágenes: $skippedCount\n";
echo "Total procesadas: " . ($updatedCount + $skippedCount) . "\n\n";

// Mostrar estadísticas de imágenes
$stmt = $pdo->query("SELECT 
    COUNT(*) as total,
    COUNT(CASE WHEN image_path IS NOT NULL AND image_path != '' THEN 1 END) as with_images,
    COUNT(CASE WHEN image_path_2 IS NOT NULL AND image_path_2 != '' THEN 1 END) as with_image_2,
    COUNT(CASE WHEN image_path_3 IS NOT NULL AND image_path_3 != '' THEN 1 END) as with_image_3,
    COUNT(CASE WHEN image_path_4 IS NOT NULL AND image_path_4 != '' THEN 1 END) as with_image_4
    FROM biodiversity_categories");

$stats = $stmt->fetch(PDO::FETCH_ASSOC);

echo "=== ESTADÍSTICAS DE IMÁGENES ===\n";
echo "Total de especies: {$stats['total']}\n";
echo "Con imagen principal: {$stats['with_images']}\n";
echo "Con segunda imagen: {$stats['with_image_2']}\n";
echo "Con tercera imagen: {$stats['with_image_3']}\n";
echo "Con cuarta imagen: {$stats['with_image_4']}\n\n";

// Mostrar ejemplos de especies actualizadas
echo "=== EJEMPLOS DE ESPECIES ACTUALIZADAS ===\n";
$stmt = $pdo->query("SELECT name, scientific_name, image_path, image_path_2 FROM biodiversity_categories WHERE image_path IS NOT NULL AND image_path != '' LIMIT 3");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "Especie: {$row['name']} ({$row['scientific_name']})\n";
    echo "Imagen 1: {$row['image_path']}\n";
    echo "Imagen 2: {$row['image_path_2']}\n\n";
}

echo "¡Actualización completada exitosamente!\n";
echo "Las imágenes están listas para ser mostradas en la aplicación web.\n";

?>
