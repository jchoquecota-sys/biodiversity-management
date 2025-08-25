<?php

// Script para generar imágenes placeholder para especies peruanas

$species = [
    'oso_anteojos' => 'Oso de Anteojos - Tremarctos ornatus',
    'vicuna' => 'Vicuña - Vicugna vicugna',
    'jaguar' => 'Jaguar - Panthera onca',
    'mono_choro' => 'Mono Choro de Cola Amarilla - Oreonax flavicauda',
    'nutria_gigante' => 'Nutria Gigante - Pteronura brasiliensis',
    'gallito_rocas' => 'Gallito de las Rocas - Rupicola peruvianus',
    'condor' => 'Cóndor Andino - Vultur gryphus',
    'colibri_espatula' => 'Colibrí Cola de Espátula - Loddigesia mirabilis',
    'guacamayo_rojo' => 'Guacamayo Rojo - Ara macao',
    'pinguino_humboldt' => 'Pingüino de Humboldt - Spheniscus humboldti',
    'anaconda' => 'Anaconda Verde - Eunectes murinus',
    'iguana_verde' => 'Iguana Verde - Iguana iguana',
    'rana_venenosa' => 'Rana Venenosa Dorada - Phyllobates aurotaenia',
    'paiche' => 'Paiche - Arapaima gigas',
    'delfin_rosado' => 'Delfín Rosado - Inia geoffrensis',
    'puya_raimondi' => 'Puya Raimondi - Puya raimondii',
    'orquidea_waqanki' => 'Orquídea Waqanki - Masdevallia veitchiana',
    'cantuta' => 'Cantuta - Cantua buxifolia',
    'quinua' => 'Quinua - Chenopodium quinoa',
    'cacao' => 'Cacao Peruano - Theobroma cacao',
    'morpho_azul' => 'Mariposa Morpho Azul - Morpho menelaus',
    'tarantula_goliath' => 'Tarántula Goliath - Theraphosa blondi',
    'lobo_marino' => 'Lobo Marino Sudamericano - Otaria flavescens',
    'anchoveta' => 'Anchoveta Peruana - Engraulis ringens',
    'chinchilla' => 'Chinchilla de Cola Larga - Chinchilla chinchilla',
    'sachavaca' => 'Sachavaca - Tapirus terrestris',
    'rana_titicaca' => 'Rana Gigante del Titicaca - Telmatobius culeus',
    'maca' => 'Maca - Lepidium meyenii',
    'una_gato' => 'Uña de Gato - Uncaria tomentosa'
];

$colors = [
    ['#FF6B6B', '#4ECDC4', '#45B7D1'],
    ['#96CEB4', '#FFEAA7', '#DDA0DD'],
    ['#74B9FF', '#A29BFE', '#FD79A8'],
    ['#00B894', '#FDCB6E', '#E17055']
];

foreach ($species as $key => $name) {
    $parts = explode(' - ', $name);
    $commonName = $parts[0];
    $scientificName = $parts[1];
    
    for ($i = 1; $i <= 4; $i++) {
        $colorSet = $colors[($i - 1) % count($colors)];
        $primaryColor = $colorSet[0];
        $secondaryColor = $colorSet[1];
        $accentColor = $colorSet[2];
        
        $svg = generatePlaceholderSVG($commonName, $scientificName, $primaryColor, $secondaryColor, $accentColor, $i);
        
        $filename = "public/images/peru/{$key}_{$i}.svg";
        file_put_contents($filename, $svg);
        echo "Generado: {$filename}\n";
    }
}

function generatePlaceholderSVG($commonName, $scientificName, $primary, $secondary, $accent, $variant) {
    $patterns = [
        1 => 'circle',
        2 => 'ellipse', 
        3 => 'polygon',
        4 => 'rect'
    ];
    
    $pattern = $patterns[$variant];
    $rotation = ($variant - 1) * 15;
    
    return "
<svg width='400' height='300' xmlns='http://www.w3.org/2000/svg'>
  <defs>
    <linearGradient id='bgGrad{$variant}' x1='0%' y1='0%' x2='100%' y2='100%'>
      <stop offset='0%' style='stop-color:{$secondary};stop-opacity:1' />
      <stop offset='100%' style='stop-color:{$primary};stop-opacity:1' />
    </linearGradient>
    <radialGradient id='mainGrad{$variant}' cx='50%' cy='50%' r='50%'>
      <stop offset='0%' style='stop-color:{$accent};stop-opacity:1' />
      <stop offset='100%' style='stop-color:{$primary};stop-opacity:1' />
    </radialGradient>
  </defs>
  
  <!-- Fondo -->
  <rect width='400' height='300' fill='url(#bgGrad{$variant})'/>
  
  <!-- Forma principal -->
  " . generateShape($pattern, $variant, $rotation) . "
  
  <!-- Detalles decorativos -->
  <circle cx='100' cy='80' r='20' fill='{$accent}' opacity='0.6'/>
  <circle cx='320' cy='100' r='15' fill='{$secondary}' opacity='0.8'/>
  <circle cx='80' cy='220' r='25' fill='{$primary}' opacity='0.4'/>
  
  <!-- Marco decorativo -->
  <rect x='10' y='10' width='380' height='280' fill='none' stroke='{$accent}' stroke-width='3' rx='15'/>
  
  <!-- Texto -->
  <rect x='20' y='250' width='360' height='40' fill='rgba(0,0,0,0.7)' rx='5'/>
  <text x='200' y='270' font-family='Arial, sans-serif' font-size='14' font-weight='bold' text-anchor='middle' fill='#FFF'>{$commonName}</text>
  <text x='200' y='285' font-family='Arial, sans-serif' font-size='10' text-anchor='middle' fill='#DDD' font-style='italic'>{$scientificName}</text>
</svg>";
}

function generateShape($pattern, $variant, $rotation) {
    switch ($pattern) {
        case 'circle':
            return "<circle cx='200' cy='150' r='60' fill='url(#mainGrad{$variant})' transform='rotate({$rotation} 200 150)'/>";
        case 'ellipse':
            return "<ellipse cx='200' cy='150' rx='80' ry='50' fill='url(#mainGrad{$variant})' transform='rotate({$rotation} 200 150)'/>";
        case 'polygon':
            return "<polygon points='200,90 260,130 240,190 160,190 140,130' fill='url(#mainGrad{$variant})' transform='rotate({$rotation} 200 150)'/>";
        case 'rect':
            return "<rect x='140' y='100' width='120' height='100' fill='url(#mainGrad{$variant})' rx='15' transform='rotate({$rotation} 200 150)'/>";
        default:
            return "<circle cx='200' cy='150' r='60' fill='url(#mainGrad{$variant})'/>";
    }
}

echo "\n¡Todas las imágenes placeholder han sido generadas exitosamente!\n";
?>