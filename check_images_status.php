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

echo "\n=== VERIFICACIÓN DEL ESTADO DE IMÁGENES ===\n\n";

// Estadísticas generales
$stmt = $pdo->query("SELECT 
    COUNT(*) as total,
    COUNT(CASE WHEN image_path IS NOT NULL AND image_path != '' THEN 1 END) as with_images,
    COUNT(CASE WHEN image_path_2 IS NOT NULL AND image_path_2 != '' THEN 1 END) as with_image_2,
    COUNT(CASE WHEN image_path_3 IS NOT NULL AND image_path_3 != '' THEN 1 END) as with_image_3,
    COUNT(CASE WHEN image_path_4 IS NOT NULL AND image_path_4 != '' THEN 1 END) as with_image_4
    FROM biodiversity_categories");

$stats = $stmt->fetch(PDO::FETCH_ASSOC);

echo "=== ESTADÍSTICAS GENERALES ===\n";
echo "Total de especies: {$stats['total']}\n";
echo "Con imagen principal: {$stats['with_images']}\n";
echo "Con segunda imagen: {$stats['with_image_2']}\n";
echo "Con tercera imagen: {$stats['with_image_3']}\n";
echo "Con cuarta imagen: {$stats['with_image_4']}\n";
echo "Porcentaje con imágenes: " . round(($stats['with_images'] / $stats['total']) * 100, 2) . "%\n\n";

// Especies con imágenes locales (SVG)
echo "=== ESPECIES CON IMÁGENES LOCALES (SVG) ===\n";
$stmt = $pdo->query("SELECT id, name, scientific_name, image_path 
                     FROM biodiversity_categories 
                     WHERE image_path LIKE 'images/especies/%' 
                     ORDER BY id LIMIT 10");
$localImages = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($localImages as $specie) {
    echo "ID: {$specie['id']} - {$specie['name']} ({$specie['scientific_name']})\n";
    echo "  Imagen: {$specie['image_path']}\n\n";
}

// Especies con imágenes externas (URLs)
echo "=== ESPECIES CON IMÁGENES EXTERNAS (URLs) ===\n";
$stmt = $pdo->query("SELECT id, name, scientific_name, image_path 
                     FROM biodiversity_categories 
                     WHERE image_path LIKE 'http%' 
                     ORDER BY id LIMIT 10");
$externalImages = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($externalImages as $specie) {
    echo "ID: {$specie['id']} - {$specie['name']} ({$specie['scientific_name']})\n";
    echo "  Imagen: " . substr($specie['image_path'], 0, 80) . "...\n\n";
}

// Especies sin imágenes
echo "=== ESPECIES SIN IMÁGENES ===\n";
$stmt = $pdo->query("SELECT id, name, scientific_name 
                     FROM biodiversity_categories 
                     WHERE image_path IS NULL OR image_path = '' 
                     ORDER BY id LIMIT 10");
$noImages = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($noImages as $specie) {
    echo "ID: {$specie['id']} - {$specie['name']} ({$specie['scientific_name']})\n";
}

echo "\nTotal sin imágenes: " . count($noImages) . "\n\n";

// Resumen por tipo de imagen
echo "=== RESUMEN POR TIPO DE IMAGEN ===\n";
$stmt = $pdo->query("SELECT 
    COUNT(CASE WHEN image_path LIKE 'images/especies/%' THEN 1 END) as local_images,
    COUNT(CASE WHEN image_path LIKE 'http%' THEN 1 END) as external_images,
    COUNT(CASE WHEN image_path IS NULL OR image_path = '' THEN 1 END) as no_images
    FROM biodiversity_categories");
$imageTypes = $stmt->fetch(PDO::FETCH_ASSOC);

echo "Imágenes locales (SVG): {$imageTypes['local_images']}\n";
echo "Imágenes externas (URLs): {$imageTypes['external_images']}\n";
echo "Sin imágenes: {$imageTypes['no_images']}\n\n";

echo "¡Verificación completada!\n";

?>
