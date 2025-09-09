<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\BiodiversityCategory;
use App\Models\Publication;

echo "=== Ejemplo de Uso: Fotos Reales en biodiversity_category_publication ===\n\n";

// 1. Registrar fotos usando DB directo
echo "1. REGISTRAR FOTOS USANDO DB DIRECTO\n";
echo "=====================================\n\n";

$sampleRelationId = DB::table('biodiversity_category_publication')->first()->id ?? null;

if ($sampleRelationId) {
    echo "Actualizando relación ID: {$sampleRelationId}\n";
    
    $updated = DB::table('biodiversity_category_publication')
        ->where('id', $sampleRelationId)
        ->update([
            'image_path' => 'https://www.sernanp.gob.pe/documents/especies/jaguar-manu.jpg',
            'image_path2' => 'https://www.inaturalist.org/photos/jaguar-peru-wild.jpg',
            'image_path3' => 'https://www.gbif.org/occurrence/media/panthera-onca-amazon.jpg',
            'image_path4' => 'https://www.worldwildlife.org/species/jaguar/photos/jaguar-habitat.jpg',
            'updated_at' => now()
        ]);
    
    if ($updated) {
        echo "✓ Fotos registradas exitosamente\n\n";
        
        // Verificar la actualización
        $relation = DB::table('biodiversity_category_publication')
            ->where('id', $sampleRelationId)
            ->first();
        
        echo "Fotos registradas:\n";
        echo "- Principal: {$relation->image_path}\n";
        echo "- Segunda: {$relation->image_path2}\n";
        echo "- Tercera: {$relation->image_path3}\n";
        echo "- Cuarta: {$relation->image_path4}\n\n";
    }
} else {
    echo "No se encontraron relaciones para actualizar\n\n";
}

// 2. Acceder a fotos usando Eloquent
echo "2. ACCEDER A FOTOS USANDO ELOQUENT\n";
echo "==================================\n\n";

$category = BiodiversityCategory::with('publications')->first();

if ($category && $category->publications->count() > 0) {
    echo "Categoría: {$category->name}\n";
    echo "Nombre científico: {$category->scientific_name}\n\n";
    
    foreach ($category->publications as $publication) {
        echo "Publicación: {$publication->title}\n";
        echo "Año: {$publication->publication_year}\n";
        
        // Acceder a las fotos del pivot
        $pivot = $publication->pivot;
        
        echo "Fotos disponibles:\n";
        if ($pivot->image_path) {
            echo "  ✓ Imagen principal: {$pivot->image_path}\n";
        }
        if ($pivot->image_path2) {
            echo "  ✓ Segunda imagen: {$pivot->image_path2}\n";
        }
        if ($pivot->image_path3) {
            echo "  ✓ Tercera imagen: {$pivot->image_path3}\n";
        }
        if ($pivot->image_path4) {
            echo "  ✓ Cuarta imagen: {$pivot->image_path4}\n";
        }
        
        if (!$pivot->image_path && !$pivot->image_path2 && !$pivot->image_path3 && !$pivot->image_path4) {
            echo "  ⚠ No hay fotos registradas\n";
        }
        
        echo "\n";
        break; // Solo mostrar la primera publicación
    }
}

// 3. Buscar relaciones con fotos
echo "3. BUSCAR RELACIONES CON FOTOS\n";
echo "==============================\n\n";

$relationsWithPhotos = DB::table('biodiversity_category_publication as bcp')
    ->join('biodiversity_categories as bc', 'bcp.biodiversity_category_id', '=', 'bc.id')
    ->join('publications as p', 'bcp.publication_id', '=', 'p.id')
    ->whereNotNull('bcp.image_path')
    ->select(
        'bcp.id',
        'bc.name as category_name',
        'bc.scientific_name',
        'p.title as publication_title',
        'bcp.image_path',
        'bcp.image_path2',
        'bcp.image_path3',
        'bcp.image_path4'
    )
    ->limit(5)
    ->get();

echo "Relaciones con fotos encontradas: " . $relationsWithPhotos->count() . "\n\n";

foreach ($relationsWithPhotos as $relation) {
    echo "ID: {$relation->id}\n";
    echo "Especie: {$relation->category_name} ({$relation->scientific_name})\n";
    echo "Publicación: {$relation->publication_title}\n";
    
    $imageCount = 0;
    if ($relation->image_path) $imageCount++;
    if ($relation->image_path2) $imageCount++;
    if ($relation->image_path3) $imageCount++;
    if ($relation->image_path4) $imageCount++;
    
    echo "Fotos: {$imageCount}/4\n";
    echo "---\n";
}

// 4. Código de ejemplo para desarrolladores
echo "\n4. CÓDIGO DE EJEMPLO PARA DESARROLLADORES\n";
echo "=========================================\n\n";

echo "// Registrar fotos en una relación específica\n";
echo "DB::table('biodiversity_category_publication')\n";
echo "    ->where('biodiversity_category_id', 1)\n";
echo "    ->where('publication_id', 39)\n";
echo "    ->update([\n";
echo "        'image_path' => 'https://sernanp.gob.pe/especies/oso-anteojos.jpg',\n";
echo "        'image_path2' => 'https://inaturalist.org/photos/tremarctos-ornatus.jpg',\n";
echo "        'image_path3' => 'https://gbif.org/media/spectacled-bear.jpg',\n";
echo "        'image_path4' => 'https://wwf.org/species/bear/oso-anteojos.jpg',\n";
echo "        'updated_at' => now()\n";
echo "    ]);\n\n";

echo "// Acceder a fotos usando Eloquent\n";
echo "\$category = BiodiversityCategory::with('publications')->find(1);\n";
echo "foreach (\$category->publications as \$publication) {\n";
echo "    \$photos = [\n";
echo "        \$publication->pivot->image_path,\n";
echo "        \$publication->pivot->image_path2,\n";
echo "        \$publication->pivot->image_path3,\n";
echo "        \$publication->pivot->image_path4\n";
echo "    ];\n";
echo "    \$photos = array_filter(\$photos); // Remover nulls\n";
echo "    // Usar las fotos...\n";
echo "}\n\n";

echo "// Buscar especies con fotos de fuentes específicas\n";
echo "\$speciesWithNationalPhotos = DB::table('biodiversity_category_publication')\n";
echo "    ->where('image_path', 'like', '%sernanp.gob.pe%')\n";
echo "    ->orWhere('image_path', 'like', '%minam.gob.pe%')\n";
echo "    ->orWhere('image_path', 'like', '%conacs.gob.pe%')\n";
echo "    ->get();\n\n";

echo "\$speciesWithInternationalPhotos = DB::table('biodiversity_category_publication')\n";
echo "    ->where('image_path', 'like', '%inaturalist.org%')\n";
echo "    ->orWhere('image_path', 'like', '%gbif.org%')\n";
echo "    ->orWhere('image_path', 'like', '%ebird.org%')\n";
echo "    ->get();\n\n";

echo "=== FUENTES RECOMENDADAS ===\n";
echo "\nNacionales:\n";
echo "- SERNANP: https://www.sernanp.gob.pe\n";
echo "- MINAM: https://www.minam.gob.pe\n";
echo "- CONACS: https://www.conacs.gob.pe\n";
echo "- PRODUCE: https://www.produce.gob.pe\n";

echo "\nInternacionales:\n";
echo "- iNaturalist: https://www.inaturalist.org\n";
echo "- GBIF: https://www.gbif.org\n";
echo "- eBird: https://ebird.org\n";
echo "- IUCN Red List: https://www.iucnredlist.org\n";
echo "- FishBase: https://www.fishbase.org\n";
echo "- World Wildlife Fund: https://www.worldwildlife.org\n\n";

echo "=== Ejemplo completado ===\n";