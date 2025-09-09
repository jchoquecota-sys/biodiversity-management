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

// Crear estructura de directorios organizados
function createDirectoryStructure() {
    $baseDir = 'public/images/especies';
    $categories = [
        'reptiles' => 'Reptiles',
        'anfibios' => 'Anfibios', 
        'mamiferos' => 'Mamíferos',
        'aves' => 'Aves',
        'peces' => 'Peces',
        'plantas' => 'Plantas',
        'otros' => 'Otros'
    ];
    
    foreach ($categories as $folder => $name) {
        $dir = $baseDir . '/' . $folder;
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
            echo "Directorio creado: $dir\n";
        }
    }
    
    return $categories;
}

// Función para determinar categoría basada en el nombre científico
function determineCategory($scientificName, $commonName) {
    $name = strtolower($scientificName . ' ' . $commonName);
    
    // Reptiles
    if (preg_match('/(liolaemus|microlophus|phyllodactylus|tachymenis|pseudalsophis)/i', $name)) {
        return 'reptiles';
    }
    
    // Anfibios
    if (preg_match('/(rhinella|telmatobius|pleurodema|bufo)/i', $name)) {
        return 'anfibios';
    }
    
    // Mamíferos
    if (preg_match('/(vicugna|lama|chinchilla|panthera|tremarctos)/i', $name)) {
        return 'mamiferos';
    }
    
    // Aves
    if (preg_match('/(vultur|rupicola|condor|gallito)/i', $name)) {
        return 'aves';
    }
    
    // Plantas
    if (preg_match('/(pinus|cupressus|equisetum|thelypteris|lippia|aloysia|urtica|nolana)/i', $name)) {
        return 'plantas';
    }
    
    return 'otros';
}

// Función para mover archivos a la estructura organizada
function organizeImages($pdo) {
    $baseDir = 'public/images/especies';
    
    // Obtener todas las especies con imágenes
    $stmt = $pdo->query("SELECT id, name, scientific_name, image_path, image_path_2, image_path_3, image_path_4 FROM biodiversity_categories WHERE image_path IS NOT NULL AND image_path != ''");
    $species = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $movedCount = 0;
    $updatedCount = 0;
    
    foreach ($species as $specie) {
        $category = determineCategory($specie['scientific_name'], $specie['name']);
        $categoryDir = $baseDir . '/' . $category;
        
        echo "Procesando: {$specie['name']} -> Categoría: $category\n";
        
        $imageFields = ['image_path', 'image_path_2', 'image_path_3', 'image_path_4'];
        $newPaths = [];
        
        foreach ($imageFields as $field) {
            if (!empty($specie[$field]) && strpos($specie[$field], 'images/especies/') === 0) {
                $filename = basename($specie[$field]);
                $oldPath = $specie[$field];
                $newPath = 'images/especies/' . $category . '/' . $filename;
                
                // Mover archivo físico
                $oldFullPath = $baseDir . '/' . $filename;
                $newFullPath = $categoryDir . '/' . $filename;
                
                if (file_exists($oldFullPath)) {
                    if (rename($oldFullPath, $newFullPath)) {
                        $newPaths[$field] = $newPath;
                        echo "  ✓ Movido: $filename -> $category/\n";
                        $movedCount++;
                    } else {
                        $newPaths[$field] = $oldPath; // Mantener ruta original si falla
                        echo "  ✗ Error moviendo: $filename\n";
                    }
                } else {
                    $newPaths[$field] = $oldPath;
                }
            } else {
                $newPaths[$field] = $specie[$field];
            }
        }
        
        // Actualizar base de datos si hay cambios
        if ($newPaths != array_intersect_key($specie, $newPaths)) {
            $updateQuery = "UPDATE biodiversity_categories 
                            SET image_path = ?,
                                image_path_2 = ?,
                                image_path_3 = ?,
                                image_path_4 = ?
                            WHERE id = ?";
            
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->execute([
                $newPaths['image_path'],
                $newPaths['image_path_2'],
                $newPaths['image_path_3'],
                $newPaths['image_path_4'],
                $specie['id']
            ]);
            
            $updatedCount++;
        }
    }
    
    return [$movedCount, $updatedCount];
}

// Función para generar reporte de imágenes
function generateImageReport($pdo) {
    echo "\n=== REPORTE DE IMÁGENES ===\n";
    
    // Contar especies por categoría
    $categories = ['reptiles', 'anfibios', 'mamiferos', 'aves', 'peces', 'plantas', 'otros'];
    
    foreach ($categories as $category) {
        $dir = "public/images/especies/$category";
        if (is_dir($dir)) {
            $files = glob($dir . '/*');
            $count = count($files);
            echo "$category: $count imágenes\n";
        }
    }
    
    // Estadísticas generales
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM biodiversity_categories");
    $totalSpecies = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as with_images FROM biodiversity_categories WHERE image_path IS NOT NULL AND image_path != ''");
    $speciesWithImages = $stmt->fetch(PDO::FETCH_ASSOC)['with_images'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as without_images FROM biodiversity_categories WHERE image_path IS NULL OR image_path = ''");
    $speciesWithoutImages = $stmt->fetch(PDO::FETCH_ASSOC)['without_images'];
    
    echo "\nEstadísticas generales:\n";
    echo "Total de especies: $totalSpecies\n";
    echo "Especies con imágenes: $speciesWithImages\n";
    echo "Especies sin imágenes: $speciesWithoutImages\n";
    echo "Porcentaje con imágenes: " . round(($speciesWithImages / $totalSpecies) * 100, 2) . "%\n";
}

echo "\n=== SISTEMA DE GESTIÓN DE IMÁGENES ===\n";

// Crear estructura de directorios
echo "\n1. Creando estructura de directorios...\n";
$categories = createDirectoryStructure();

// Organizar imágenes existentes
echo "\n2. Organizando imágenes existentes...\n";
list($movedCount, $updatedCount) = organizeImages($pdo);

// Generar reporte
echo "\n3. Generando reporte...\n";
generateImageReport($pdo);

echo "\n=== RESUMEN FINAL ===\n";
echo "Archivos movidos: $movedCount\n";
echo "Registros actualizados: $updatedCount\n";
echo "Estructura de directorios creada exitosamente.\n";
echo "\nEstructura de carpetas:\n";
foreach ($categories as $folder => $name) {
    echo "- public/images/especies/$folder/ ($name)\n";
}

echo "\nPuedes agregar imágenes reales en las carpetas correspondientes manteniendo los nombres de archivo existentes.\n";

?>