<?php

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'biodiversity_management';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa a la base de datos.\n";
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Crear directorio para imágenes si no existe
$imageDir = 'public/images/especies';
if (!file_exists($imageDir)) {
    mkdir($imageDir, 0755, true);
    echo "Directorio creado: $imageDir\n";
}

// Función para descargar imagen
function downloadImage($url, $destination) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $data = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200 && $data !== false) {
        file_put_contents($destination, $data);
        return true;
    }
    return false;
}

// Función para generar nombre de archivo seguro
function generateSafeFilename($scientificName, $imageIndex) {
    $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $scientificName);
    return strtolower($safeName) . '_' . $imageIndex . '.jpg';
}

// Especies con imágenes de Wikimedia Commons (URLs públicas y libres)
$speciesWithImages = [
    [
        'id' => 302, // Vultur gryphus
        'name' => 'Vultur gryphus',
        'images' => [
            'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3f/Andean_Condor_%28Vultur_gryphus%29_male_in_flight.jpg/800px-Andean_Condor_%28Vultur_gryphus%29_male_in_flight.jpg',
            'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8f/Vultur_gryphus_-Colca_Canyon%2C_Peru-8a.jpg/800px-Vultur_gryphus_-Colca_Canyon%2C_Peru-8a.jpg',
            'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f0/Andean_Condor_soaring.jpg/800px-Andean_Condor_soaring.jpg'
        ]
    ],
    [
        'id' => 64, // Vicugna vicugna
        'name' => 'Vicugna vicugna',
        'images' => [
            'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3b/Vicugna_vicugna_-Andes%2C_Peru-8a.jpg/800px-Vicugna_vicugna_-Andes%2C_Peru-8a.jpg',
            'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1a/Vicuna_Vicugna_vicugna.jpg/800px-Vicuna_Vicugna_vicugna.jpg',
            'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8c/Vicugna_vicugna_family.jpg/800px-Vicugna_vicugna_family.jpg'
        ]
    ]
];

// Agregar algunas especies más comunes que probablemente estén en la base de datos
$additionalSpecies = [
    // Buscar Lama glama (Llama)
    'Lama glama' => [
        'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f6/Llama_lying_down.jpg/800px-Llama_lying_down.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3e/Llama_portrait.jpg/800px-Llama_portrait.jpg'
    ],
    // Buscar Chinchilla chinchilla
    'Chinchilla chinchilla' => [
        'https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Chinchilla_lanigera.jpg/800px-Chinchilla_lanigera.jpg'
    ]
];

// Buscar especies adicionales en la base de datos
foreach ($additionalSpecies as $scientificName => $images) {
    $stmt = $pdo->prepare("SELECT id, name, scientific_name FROM biodiversity_categories WHERE scientific_name LIKE ?");
    $stmt->execute(["%$scientificName%"]);
    
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $speciesWithImages[] = [
            'id' => $row['id'],
            'name' => $row['scientific_name'],
            'images' => $images
        ];
        echo "Agregada especie encontrada: {$row['name']} (ID: {$row['id']})\n";
    }
}

echo "\n=== DESCARGANDO IMÁGENES ===\n";
$downloadedCount = 0;
$errorCount = 0;

foreach ($speciesWithImages as $species) {
    echo "\nProcesando: {$species['name']} (ID: {$species['id']})\n";
    
    $imagePaths = ['', '', '', ''];
    
    foreach ($species['images'] as $index => $imageUrl) {
        if ($index >= 4) break; // Máximo 4 imágenes
        
        $filename = generateSafeFilename($species['name'], $index + 1);
        $localPath = $imageDir . '/' . $filename;
        
        echo "  Descargando imagen " . ($index + 1) . ": $imageUrl\n";
        
        if (downloadImage($imageUrl, $localPath)) {
            $imagePaths[$index] = 'images/especies/' . $filename;
            echo "    ✓ Descargada: $filename\n";
            $downloadedCount++;
        } else {
            echo "    ✗ Error al descargar imagen " . ($index + 1) . "\n";
            $errorCount++;
        }
    }
    
    // Actualizar base de datos
    $updateQuery = "UPDATE biodiversity_categories 
                    SET image_path = ?,
                        image_path_2 = ?,
                        image_path_3 = ?,
                        image_path_4 = ?
                    WHERE id = ?";
    
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->execute([
        $imagePaths[0] ?: null,
        $imagePaths[1] ?: null,
        $imagePaths[2] ?: null,
        $imagePaths[3] ?: null,
        $species['id']
    ]);
    
    echo "  Base de datos actualizada.\n";
}

echo "\n=== RESUMEN ===\n";
echo "Imágenes descargadas exitosamente: $downloadedCount\n";
echo "Errores de descarga: $errorCount\n";
echo "Especies procesadas: " . count($speciesWithImages) . "\n";
echo "Proceso completado.\n";

?>