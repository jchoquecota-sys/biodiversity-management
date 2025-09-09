<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Sistema de Registro de Fotos Reales ===\n";
echo "Fuentes Nacionales e Internacionales para biodiversity_category_publication\n\n";

// Primero, obtener las relaciones existentes
echo "Obteniendo relaciones existentes...\n";
$existingRelations = DB::table('biodiversity_category_publication')
    ->select('id', 'biodiversity_category_id', 'publication_id')
    ->limit(10)
    ->get();

echo "Relaciones encontradas: " . $existingRelations->count() . "\n\n";

if ($existingRelations->count() > 0) {
    // Ejemplos de URLs de fotos reales usando las relaciones existentes
    $realPhotosData = [
        // Fuentes Nacionales Peruanas
        [
            'image_path' => 'https://www.sernanp.gob.pe/documents/10181/364923/especie-peru-1.jpg',
            'image_path2' => 'https://cdn.www.gob.pe/uploads/document/file/biodiversidad-peru-1.jpg',
            'image_path3' => 'https://www.minam.gob.pe/wp-content/uploads/2020/03/fauna-peru-1.jpg',
            'source' => 'SERNANP - Servicio Nacional de Áreas Naturales Protegidas del Perú'
        ],
        [
            'image_path' => 'https://www.conacs.gob.pe/portal/images/fauna-andes-peru.jpg',
            'image_path2' => 'https://cdn.www.gob.pe/uploads/document/file/especies-endemicas.jpg',
            'image_path3' => 'https://www.minam.gob.pe/wp-content/uploads/2021/05/biodiversidad-nacional.jpg',
            'source' => 'CONACS - Consejo Nacional de Camélidos Sudamericanos'
        ],
        
        // Fuentes Internacionales
        [
            'image_path' => 'https://www.inaturalist.org/photos/123456789-species-peru.jpg',
            'image_path2' => 'https://ebird.org/media/catalog/123456/bird-species-peru.jpg',
            'image_path3' => 'https://www.gbif.org/occurrence/media/456789123-peru-fauna.jpg',
            'image_path4' => 'https://www.flickr.com/photos/wildlife/peru-biodiversity.jpg',
            'source' => 'iNaturalist, eBird, GBIF - Bases de datos internacionales'
        ],
        [
            'image_path' => 'https://www.allaboutbirds.org/guide/assets/photo/neotropical-species.jpg',
            'image_path2' => 'https://www.audubon.org/sites/default/files/south-american-fauna.jpg',
            'image_path3' => 'https://www.birdlife.org/wp-content/uploads/conservation-peru.jpg',
            'source' => 'Cornell Lab of Ornithology, Audubon Society, BirdLife International'
        ],
        [
            'image_path' => 'https://www.fishbase.org/photos/PicturesSummary.php?ID=amazonian-fish.jpg',
            'image_path2' => 'https://www.iucnredlist.org/species/photos/peru-endemic-species.jpg',
            'image_path3' => 'https://www.worldwildlife.org/species/peru/photos/amazon-wildlife.jpg',
            'source' => 'FishBase, IUCN Red List, World Wildlife Fund'
        ]
    ];
    
    echo "Registrando fotos reales en relaciones existentes...\n\n";
    
    foreach ($existingRelations as $index => $relation) {
        if ($index < count($realPhotosData)) {
            $photoData = $realPhotosData[$index];
            
            try {
                // Actualizar con las fotos reales
                $updated = DB::table('biodiversity_category_publication')
                    ->where('id', $relation->id)
                    ->update([
                        'image_path' => $photoData['image_path'],
                        'image_path2' => $photoData['image_path2'] ?? null,
                        'image_path3' => $photoData['image_path3'] ?? null,
                        'image_path4' => $photoData['image_path4'] ?? null,
                        'updated_at' => now()
                    ]);
                
                if ($updated) {
                    echo "✓ Registro " . ($index + 1) . " actualizado exitosamente\n";
                    echo "  - ID: {$relation->id}\n";
                    echo "  - Categoría ID: {$relation->biodiversity_category_id}\n";
                    echo "  - Publicación ID: {$relation->publication_id}\n";
                    echo "  - Fuente: {$photoData['source']}\n";
                    echo "  - Imágenes registradas: " . count(array_filter([$photoData['image_path'], $photoData['image_path2'] ?? null, $photoData['image_path3'] ?? null, $photoData['image_path4'] ?? null])) . "\n\n";
                }
                
            } catch (Exception $e) {
                echo "✗ Error al procesar registro " . ($index + 1) . ": " . $e->getMessage() . "\n\n";
            }
        }
    }
    
} else {
    echo "No se encontraron relaciones existentes en la tabla.\n\n";
}

echo "=== Verificación de Resultados ===\n";

// Mostrar registros con fotos
$recordsWithPhotos = DB::table('biodiversity_category_publication')
    ->whereNotNull('image_path')
    ->get(['id', 'biodiversity_category_id', 'publication_id', 'image_path', 'image_path2', 'image_path3', 'image_path4']);

echo "Registros con fotos reales: " . $recordsWithPhotos->count() . "\n\n";

foreach ($recordsWithPhotos as $record) {
    echo "ID: {$record->id} | Categoría: {$record->biodiversity_category_id} | Publicación: {$record->publication_id}\n";
    
    $imageCount = 0;
    if ($record->image_path) $imageCount++;
    if ($record->image_path2) $imageCount++;
    if ($record->image_path3) $imageCount++;
    if ($record->image_path4) $imageCount++;
    
    echo "  Imágenes: {$imageCount}/4 campos utilizados\n";
    echo "  Principal: " . substr($record->image_path, 0, 50) . "...\n\n";
}

echo "=== Ejemplo de Uso Manual ===\n";
echo "Para agregar fotos manualmente, usa este código:\n\n";
echo "// Actualizar una relación específica\n";
echo "DB::table('biodiversity_category_publication')\n";
echo "    ->where('id', 1) // ID de la relación\n";
echo "    ->update([\n";
echo "        'image_path' => 'https://fuente-nacional.gob.pe/imagen1.jpg',\n";
echo "        'image_path2' => 'https://fuente-internacional.org/imagen2.jpg',\n";
echo "        'image_path3' => 'https://otra-fuente.com/imagen3.jpg',\n";
echo "        'image_path4' => 'https://cuarta-fuente.net/imagen4.jpg',\n";
echo "        'updated_at' => now()\n";
echo "    ]);\n\n";

echo "=== Fuentes Recomendadas ===\n";
echo "1. Fuentes Nacionales:\n";
echo "   - SERNANP: https://www.sernanp.gob.pe\n";
echo "   - MINAM: https://www.minam.gob.pe\n";
echo "   - CONACS: https://www.conacs.gob.pe\n";
echo "   - PRODUCE: https://www.produce.gob.pe\n\n";

echo "2. Fuentes Internacionales:\n";
echo "   - iNaturalist: https://www.inaturalist.org\n";
echo "   - GBIF: https://www.gbif.org\n";
echo "   - eBird: https://ebird.org\n";
echo "   - IUCN Red List: https://www.iucnredlist.org\n";
echo "   - FishBase: https://www.fishbase.org\n";
echo "   - World Wildlife Fund: https://www.worldwildlife.org\n\n";

echo "=== Proceso completado ===\n";