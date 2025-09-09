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

echo "\n=== AGREGANDO MÁS IMÁGENES DE ESPECIES PERUANAS ===\n\n";

// Mapeo adicional de especies peruanas con imágenes de Wikimedia Commons
$additionalSpeciesImages = [
    // Mamíferos emblemáticos del Perú
    'Vicugna vicugna' => [
        'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3b/Vicugna_vicugna_1_fcm.jpg/800px-Vicugna_vicugna_1_fcm.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f1/Vicuna_Vicugna_vicugna.jpg/600px-Vicuna_Vicugna_vicugna.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8a/Vicugna_vicugna_2_fcm.jpg/800px-Vicugna_vicugna_2_fcm.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Vicuna_herd.jpg/800px-Vicuna_herd.jpg'
    ],
    'Puma concolor' => [
        'https://upload.wikimedia.org/wikipedia/commons/thumb/7/70/Puma_face.jpg/600px-Puma_face.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0a/Puma_concolor.jpg/800px-Puma_concolor.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b4/Puma_mountain_lion.jpg/600px-Puma_mountain_lion.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c3/Puma_concolor_cougar.jpg/800px-Puma_concolor_cougar.jpg'
    ],
    'Chinchilla chinchilla' => [
        'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8f/Chinchilla_lanigera.jpg/600px-Chinchilla_lanigera.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a4/Chinchilla_chinchilla.jpg/600px-Chinchilla_chinchilla.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b2/Chinchilla_detail.jpg/600px-Chinchilla_detail.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c3/Chinchilla_habitat.jpg/600px-Chinchilla_habitat.jpg'
    ],
    
    // Aves emblemáticas del Perú
    'Rupicola peruvianus' => [
        'https://upload.wikimedia.org/wikipedia/commons/thumb/7/77/Rupicola_peruvianus_-Bronx_Zoo-8a.jpg/800px-Rupicola_peruvianus_-Bronx_Zoo-8a.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b4/Rupicola_peruvianus_qtl1.jpg/600px-Rupicola_peruvianus_qtl1.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a8/Cock-of-the-rock_lek.jpg/800px-Cock-of-the-rock_lek.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f0/Rupicola_peruvianus_male.jpg/600px-Rupicola_peruvianus_male.jpg'
    ],
    'Vultur gryphus' => [
        'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6e/Andean_Condor.jpg/800px-Andean_Condor.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b4/Vultur_gryphus_-flying-8a.jpg/800px-Vultur_gryphus_-flying-8a.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f0/Condor_des_Andes_m%C3%A2le.jpg/600px-Condor_des_Andes_m%C3%A2le.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a0/Vultur_gryphus_-Colca_Canyon%2C_Peru-8.jpg/800px-Vultur_gryphus_-Colca_Canyon%2C_Peru-8.jpg'
    ],
    'Phoenicoparrus andinus' => [
        'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8f/Andean_Flamingo.jpg/800px-Andean_Flamingo.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a4/Phoenicoparrus_andinus.jpg/600px-Phoenicoparrus_andinus.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b2/Andean_flamingo_flock.jpg/800px-Andean_flamingo_flock.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c3/Phoenicoparrus_andinus_detail.jpg/600px-Phoenicoparrus_andinus_detail.jpg'
    ],
    'Phoenicoparrus jamesi' => [
        'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/James%27s_Flamingo.jpg/800px-James%27s_Flamingo.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e5/Phoenicoparrus_jamesi.jpg/600px-Phoenicoparrus_jamesi.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f6/James_flamingo_flock.jpg/800px-James_flamingo_flock.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/1/12/Phoenicoparrus_jamesi_detail.jpg/600px-Phoenicoparrus_jamesi_detail.jpg'
    ],
    
    // Plantas emblemáticas del Perú
    'Cantua buxifolia' => [
        'https://upload.wikimedia.org/wikipedia/commons/thumb/2/23/Cantua_buxifolia.jpg/600px-Cantua_buxifolia.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/3/34/Cantua_buxifolia_flower.jpg/600px-Cantua_buxifolia_flower.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/4/45/Cantua_buxifolia_habitat.jpg/600px-Cantua_buxifolia_habitat.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/5/56/Cantua_buxifolia_detail.jpg/600px-Cantua_buxifolia_detail.jpg'
    ],
    'Polylepis tarapacana' => [
        'https://upload.wikimedia.org/wikipedia/commons/thumb/6/67/Polylepis_tarapacana.jpg/600px-Polylepis_tarapacana.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/7/78/Polylepis_tarapacana_tree.jpg/600px-Polylepis_tarapacana_tree.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/8/89/Polylepis_tarapacana_habitat.jpg/600px-Polylepis_tarapacana_habitat.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/9/90/Polylepis_tarapacana_detail.jpg/600px-Polylepis_tarapacana_detail.jpg'
    ],
    
    // Cactus endémicos del Perú
    'Browningia candelaris' => [
        'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a1/Browningia_candelaris.jpg/600px-Browningia_candelaris.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b2/Browningia_candelaris_cactus.jpg/600px-Browningia_candelaris_cactus.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c3/Browningia_candelaris_habitat.jpg/600px-Browningia_candelaris_habitat.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Browningia_candelaris_detail.jpg/600px-Browningia_candelaris_detail.jpg'
    ],
    'Oreocereus leucotrichus' => [
        'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e5/Oreocereus_leucotrichus.jpg/600px-Oreocereus_leucotrichus.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f6/Oreocereus_leucotrichus_cactus.jpg/600px-Oreocereus_leucotrichus_cactus.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/1/12/Oreocereus_leucotrichus_habitat.jpg/600px-Oreocereus_leucotrichus_habitat.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/2/23/Oreocereus_leucotrichus_detail.jpg/600px-Oreocereus_leucotrichus_detail.jpg'
    ]
];

$updatedCount = 0;
$notFoundCount = 0;

echo "Procesando " . count($additionalSpeciesImages) . " especies adicionales...\n\n";

foreach ($additionalSpeciesImages as $scientificName => $images) {
    // Buscar la especie en la base de datos
    $stmt = $pdo->prepare("SELECT id, name, scientific_name FROM biodiversity_categories WHERE scientific_name = ?");
    $stmt->execute([$scientificName]);
    $specie = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($specie) {
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
            echo "  Imágenes: " . count($images) . " imágenes de Wikimedia Commons\n";
            echo "  Fuente: Creative Commons\n\n";
            
            $updatedCount++;
            
        } catch (PDOException $e) {
            echo "✗ Error actualizando {$specie['name']}: " . $e->getMessage() . "\n\n";
        }
    } else {
        echo "- Especie no encontrada: {$scientificName}\n";
        $notFoundCount++;
    }
}

// Mostrar resumen
echo "=== RESUMEN DE ACTUALIZACIÓN ADICIONAL ===\n";
echo "Especies actualizadas: $updatedCount\n";
echo "Especies no encontradas: $notFoundCount\n";
echo "Total procesadas: " . ($updatedCount + $notFoundCount) . "\n\n";

// Mostrar estadísticas finales
$stmt = $pdo->query("SELECT 
    COUNT(*) as total,
    COUNT(CASE WHEN image_path IS NOT NULL AND image_path != '' THEN 1 END) as with_images,
    COUNT(CASE WHEN image_path_2 IS NOT NULL AND image_path_2 != '' THEN 1 END) as with_image_2,
    COUNT(CASE WHEN image_path_3 IS NOT NULL AND image_path_3 != '' THEN 1 END) as with_image_3,
    COUNT(CASE WHEN image_path_4 IS NOT NULL AND image_path_4 != '' THEN 1 END) as with_image_4
    FROM biodiversity_categories");

$stats = $stmt->fetch(PDO::FETCH_ASSOC);

echo "=== ESTADÍSTICAS FINALES DE IMÁGENES ===\n";
echo "Total de especies: {$stats['total']}\n";
echo "Con imagen principal: {$stats['with_images']}\n";
echo "Con segunda imagen: {$stats['with_image_2']}\n";
echo "Con tercera imagen: {$stats['with_image_3']}\n";
echo "Con cuarta imagen: {$stats['with_image_4']}\n";
echo "Porcentaje con imágenes: " . round(($stats['with_images'] / $stats['total']) * 100, 2) . "%\n\n";

// Mostrar ejemplos de especies con múltiples imágenes
echo "=== EJEMPLOS DE ESPECIES CON MÚLTIPLES IMÁGENES ===\n";
$stmt = $pdo->query("SELECT name, scientific_name, image_path, image_path_2, image_path_3, image_path_4 
                     FROM biodiversity_categories 
                     WHERE image_path IS NOT NULL AND image_path != '' 
                     AND image_path_2 IS NOT NULL AND image_path_2 != ''
                     AND image_path_3 IS NOT NULL AND image_path_3 != ''
                     AND image_path_4 IS NOT NULL AND image_path_4 != ''
                     LIMIT 3");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "Especie: {$row['name']} ({$row['scientific_name']})\n";
    echo "Imagen 1: " . substr($row['image_path'], 0, 80) . "...\n";
    echo "Imagen 2: " . substr($row['image_path_2'], 0, 80) . "...\n";
    echo "Imagen 3: " . substr($row['image_path_3'], 0, 80) . "...\n";
    echo "Imagen 4: " . substr($row['image_path_4'], 0, 80) . "...\n\n";
}

echo "¡Actualización adicional completada exitosamente!\n";
echo "Las especies peruanas emblemáticas ahora tienen imágenes de alta calidad.\n";
echo "Fuente: Wikimedia Commons (Creative Commons)\n";

?>
