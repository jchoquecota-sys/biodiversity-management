<?php
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Configurar Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== MIGRACIÓN MÚLTIPLE DE IMÁGENES DESDE BIOSERVER ===\n";

// Configuración
$localImagesPath = 'C:\trae_py\Files Biodiversidad\biodiversidad';
$migratedImagesDir = 'public/images/migrated_from_bioserver';

// Crear directorio de destino si no existe
if (!is_dir($migratedImagesDir)) {
    mkdir($migratedImagesDir, 0755, true);
    echo "📁 Directorio creado: $migratedImagesDir\n";
}

// Verificar que existe el directorio fuente
if (!is_dir($localImagesPath)) {
    echo "❌ Error: No existe el directorio fuente: $localImagesPath\n";
    exit(1);
}

echo "📂 Directorio fuente: $localImagesPath\n";
echo "📂 Directorio destino: $migratedImagesDir\n\n";

try {
    // Conectar a la base de datos external_bioserver
    $bioserverConfig = config('database.connections.external_bioserver');
    $bioserver_pdo = new PDO(
        "mysql:host={$bioserverConfig['host']};dbname={$bioserverConfig['database']};charset=utf8",
        $bioserverConfig['username'],
        $bioserverConfig['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "✅ Conectado a bioserver_grt\n";
    
    // Obtener todas las imágenes de bioserver_grt.biodiversidad_imagens
    $stmt = $bioserver_pdo->query("SELECT biodiversidad_id, ruta_imagen FROM biodiversidad_imagens ORDER BY biodiversidad_id");
    $bioserverImages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "📊 Total de imágenes encontradas en bioserver: " . count($bioserverImages) . "\n\n";
    
    // Agrupar imágenes por biodiversidad_id
    $groupedImages = [];
    foreach ($bioserverImages as $image) {
        $groupedImages[$image['biodiversidad_id']][] = $image['ruta_imagen'];
    }
    
    echo "📊 Especies únicas con imágenes: " . count($groupedImages) . "\n\n";
    
    $migratedCount = 0;
    $errorCount = 0;
    $speciesWithMultipleImages = 0;
    
    // Procesar cada grupo de imágenes
    foreach ($groupedImages as $biodiversidadId => $imagePaths) {
        echo "🔄 Procesando biodiversidad_id: $biodiversidadId (".count($imagePaths)." imágenes)\n";
        
        // Verificar que existe el registro en biodiversity_categories
        $category = DB::table('biodiversity_categories')->where('id', $biodiversidadId)->first();
        
        if (!$category) {
            echo "⚠️  No existe registro con id=$biodiversidadId en biodiversity_categories\n";
            $errorCount++;
            continue;
        }
        
        $imageFields = ['image_path', 'image_path_2', 'image_path_3', 'image_path_4'];
        $updateData = [];
        $imagesProcessed = 0;
        
        // Procesar hasta 4 imágenes por especie
        for ($i = 0; $i < min(4, count($imagePaths)); $i++) {
            $imagePath = $imagePaths[$i];
            $filename = basename($imagePath);
            $sourceFilePath = $localImagesPath . DIRECTORY_SEPARATOR . $filename;
            $destinationFilePath = $migratedImagesDir . DIRECTORY_SEPARATOR . 'species_' . $biodiversidadId . '_' . ($i + 1) . '.jpg';
            
            if (file_exists($sourceFilePath)) {
                if (copy($sourceFilePath, $destinationFilePath)) {
                    $updateData[$imageFields[$i]] = $destinationFilePath;
                    $imagesProcessed++;
                    echo "  ✅ Imagen " . ($i + 1) . ": $filename → species_{$biodiversidadId}_" . ($i + 1) . ".jpg\n";
                } else {
                    echo "  ❌ Error copiando: $filename\n";
                }
            } else {
                echo "  ⚠️  Archivo no encontrado: $filename\n";
            }
        }
        
        // Actualizar la base de datos si se procesaron imágenes
        if ($imagesProcessed > 0) {
            DB::table('biodiversity_categories')
                ->where('id', $biodiversidadId)
                ->update($updateData);
            
            $migratedCount++;
            if ($imagesProcessed > 1) {
                $speciesWithMultipleImages++;
            }
            
            echo "  📝 Actualizado en BD: " . implode(', ', array_keys($updateData)) . "\n";
        } else {
            $errorCount++;
        }
        
        echo "\n";
    }
    
    echo "=== RESUMEN DE MIGRACIÓN ===\n";
    echo "✅ Especies migradas exitosamente: $migratedCount\n";
    echo "📸 Especies con múltiples imágenes: $speciesWithMultipleImages\n";
    echo "❌ Errores encontrados: $errorCount\n";
    echo "📂 Imágenes guardadas en: $migratedImagesDir\n";
    
    // Mostrar algunas estadísticas
    echo "\n=== ESTADÍSTICAS DETALLADAS ===\n";
    
    $stats = DB::table('biodiversity_categories')
        ->selectRaw('
            COUNT(*) as total,
            COUNT(image_path) as con_image_path,
            COUNT(image_path_2) as con_image_path_2,
            COUNT(image_path_3) as con_image_path_3,
            COUNT(image_path_4) as con_image_path_4
        ')
        ->first();
    
    echo "📊 Total de especies: {$stats->total}\n";
    echo "🖼️  Con image_path: {$stats->con_image_path}\n";
    echo "🖼️  Con image_path_2: {$stats->con_image_path_2}\n";
    echo "🖼️  Con image_path_3: {$stats->con_image_path_3}\n";
    echo "🖼️  Con image_path_4: {$stats->con_image_path_4}\n";
    
} catch (Exception $e) {
    echo "❌ Error durante la migración: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n=== MIGRACIÓN COMPLETADA ===\n";
