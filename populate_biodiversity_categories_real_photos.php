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

// Datos de especies peruanas con fotos reales de fuentes confiables
$species = [
    [
        'name' => 'Oso de Anteojos',
        'scientific_name' => 'Tremarctos ornatus',
        'description' => 'Único oso nativo de Sudamérica, habita en los bosques andinos del Perú. Especie vulnerable con características manchas blancas alrededor de los ojos.',
        'habitat' => 'Bosques húmedos andinos, páramos y zonas montañosas entre 500-4750 msnm',
        'conservation_status' => 'VU',
        'kingdom' => 'Animal',
        'image_path' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8f/Spectacled_Bear_-_Houston_Zoo.jpg/800px-Spectacled_Bear_-_Houston_Zoo.jpg',
        'image_path_2' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c0/Tremarctos_ornatus_face.jpg/600px-Tremarctos_ornatus_face.jpg',
        'image_path_3' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1f/Spectacled_bear_%28Tremarctos_ornatus%29.jpg/800px-Spectacled_bear_%28Tremarctos_ornatus%29.jpg',
        'image_path_4' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f4/Tremarctos_ornatus_qtl1.jpg/600px-Tremarctos_ornatus_qtl1.jpg'
    ],
    [
        'name' => 'Gallito de las Rocas Peruano',
        'scientific_name' => 'Rupicola peruvianus',
        'description' => 'Ave nacional del Perú, conocida por su espectacular plumaje anaranjado en los machos y su cresta distintiva.',
        'habitat' => 'Bosques nublados de la vertiente oriental de los Andes, entre 500-2400 msnm',
        'conservation_status' => 'LC',
        'kingdom' => 'Animal',
        'image_path' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/77/Rupicola_peruvianus_-Bronx_Zoo-8a.jpg/800px-Rupicola_peruvianus_-Bronx_Zoo-8a.jpg',
        'image_path_2' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b4/Rupicola_peruvianus_qtl1.jpg/600px-Rupicola_peruvianus_qtl1.jpg',
        'image_path_3' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a8/Cock-of-the-rock_lek.jpg/800px-Cock-of-the-rock_lek.jpg',
        'image_path_4' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f0/Rupicola_peruvianus_male.jpg/600px-Rupicola_peruvianus_male.jpg'
    ],
    [
        'name' => 'Cóndor Andino',
        'scientific_name' => 'Vultur gryphus',
        'description' => 'Ave rapaz más grande del mundo, símbolo de los Andes peruanos y ave sagrada en la cultura incaica.',
        'habitat' => 'Montañas andinas, acantilados y áreas abiertas hasta 5000 msnm',
        'conservation_status' => 'VU',
        'kingdom' => 'Animal',
        'image_path' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6e/Andean_Condor.jpg/800px-Andean_Condor.jpg',
        'image_path_2' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b4/Vultur_gryphus_-flying-8a.jpg/800px-Vultur_gryphus_-flying-8a.jpg',
        'image_path_3' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f0/Condor_des_Andes_m%C3%A2le.jpg/600px-Condor_des_Andes_m%C3%A2le.jpg',
        'image_path_4' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a0/Vultur_gryphus_-Colca_Canyon%2C_Peru-8.jpg/800px-Vultur_gryphus_-Colca_Canyon%2C_Peru-8.jpg'
    ],
    [
        'name' => 'Vicuña',
        'scientific_name' => 'Vicugna vicugna',
        'description' => 'Camélido sudamericano de fibra más fina del mundo, ancestro de la alpaca y símbolo nacional del Perú.',
        'habitat' => 'Puna y pastizales altoandinos entre 3200-4800 msnm',
        'conservation_status' => 'LC',
        'kingdom' => 'Animal',
        'image_path' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3b/Vicugna_vicugna_1_fcm.jpg/800px-Vicugna_vicugna_1_fcm.jpg',
        'image_path_2' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f1/Vicuna_Vicugna_vicugna.jpg/600px-Vicuna_Vicugna_vicugna.jpg',
        'image_path_3' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8a/Vicugna_vicugna_2_fcm.jpg/800px-Vicugna_vicugna_2_fcm.jpg',
        'image_path_4' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Vicuna_herd.jpg/800px-Vicuna_herd.jpg'
    ],
    [
        'name' => 'Jaguar',
        'scientific_name' => 'Panthera onca',
        'description' => 'Felino más grande de América, presente en la Amazonía peruana. Depredador apex de los ecosistemas tropicales.',
        'habitat' => 'Selva amazónica, bosques tropicales húmedos hasta 1000 msnm',
        'conservation_status' => 'NT',
        'kingdom' => 'Animal',
        'image_path' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0a/Standing_jaguar.jpg/800px-Standing_jaguar.jpg',
        'image_path_2' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b4/Jaguar_%28Panthera_onca%29_male_Three_Brothers_River.jpg/800px-Jaguar_%28Panthera_onca%29_male_Three_Brothers_River.jpg',
        'image_path_3' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a4/Jaguar_head_shot.jpg/600px-Jaguar_head_shot.jpg',
        'image_path_4' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3e/Jaguar_%28Panthera_onca%29_swimming.jpg/800px-Jaguar_%28Panthera_onca%29_swimming.jpg'
    ]
];

echo "\n=== POBLANDO TABLA BIODIVERSITY_CATEGORIES CON FOTOS REALES ===\n\n";

// Insertar especies en la base de datos
foreach ($species as $index => $animal) {
    try {
        $sql = "INSERT INTO biodiversity_categories (
                    name, scientific_name, description, habitat, conservation_status, kingdom,
                    image_path, image_path_2, image_path_3, image_path_4,
                    created_at, updated_at
                ) VALUES (
                    :name, :scientific_name, :description, :habitat, :conservation_status, :kingdom,
                    :image_path, :image_path_2, :image_path_3, :image_path_4,
                    NOW(), NOW()
                )";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $animal['name'],
            ':scientific_name' => $animal['scientific_name'],
            ':description' => $animal['description'],
            ':habitat' => $animal['habitat'],
            ':conservation_status' => $animal['conservation_status'],
            ':kingdom' => $animal['kingdom'],
            ':image_path' => $animal['image_path'],
            ':image_path_2' => $animal['image_path_2'],
            ':image_path_3' => $animal['image_path_3'],
            ':image_path_4' => $animal['image_path_4']
        ]);
        
        $id = $pdo->lastInsertId();
        echo "✓ Insertado: {$animal['name']} (ID: $id)\n";
        echo "  Nombre científico: {$animal['scientific_name']}\n";
        echo "  Estado: {$animal['conservation_status']}\n";
        echo "  Fotos: 4 imágenes de Wikimedia Commons\n\n";
        
    } catch (PDOException $e) {
        echo "✗ Error insertando {$animal['name']}: " . $e->getMessage() . "\n\n";
    }
}

// Mostrar resumen
$count = $pdo->query("SELECT COUNT(*) FROM biodiversity_categories")->fetchColumn();
echo "=== RESUMEN ===\n";
echo "Total de especies en la base de datos: $count\n";
echo "Fuente de imágenes: Wikimedia Commons (Creative Commons)\n";
echo "Todas las especies son nativas del Perú\n";
echo "Cada especie tiene 4 fotografías de alta calidad\n\n";

// Mostrar ejemplos de URLs
echo "=== EJEMPLOS DE ACCESO A FOTOS ===\n";
$stmt = $pdo->query("SELECT name, scientific_name, image_path FROM biodiversity_categories LIMIT 2");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "Especie: {$row['name']} ({$row['scientific_name']})\n";
    echo "Foto principal: {$row['image_path']}\n\n";
}

echo "¡Población completada exitosamente!\n";
echo "Las fotos están listas para ser mostradas en la aplicación web.\n";

?>