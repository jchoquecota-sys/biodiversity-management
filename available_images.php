<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$itemsPerPage = 12;
$offset = ($page - 1) * $itemsPerPage;

// Directorio base de imágenes
$baseDir = 'public/images/especies';
$categories = ['reptiles', 'anfibios', 'mamiferos', 'aves', 'peces', 'plantas', 'otros'];

$allImages = [];

// Escanear todas las categorías
foreach ($categories as $category) {
    $categoryDir = $baseDir . '/' . $category;
    
    if (is_dir($categoryDir)) {
        $files = glob($categoryDir . '/*');
        
        foreach ($files as $file) {
            if (is_file($file)) {
                $filename = basename($file);
                $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                
                // Solo incluir archivos de imagen
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'])) {
                    $relativePath = str_replace('public/', '', $file);
                    $webPath = str_replace('\\', '/', $relativePath);
                    
                    $allImages[] = [
                        'filename' => $filename,
                        'category' => $category,
                        'path' => $webPath,
                        'relative_path' => $relativePath,
                        'full_path' => $file,
                        'size' => filesize($file),
                        'modified' => filemtime($file),
                        'extension' => $extension
                    ];
                }
            }
        }
    }
}

// Ordenar por fecha de modificación (más recientes primero)
usort($allImages, function($a, $b) {
    return $b['modified'] - $a['modified'];
});

$totalImages = count($allImages);
$totalPages = ceil($totalImages / $itemsPerPage);

// Paginar resultados
$paginatedImages = array_slice($allImages, $offset, $itemsPerPage);

// Formatear fechas y tamaños
$formattedImages = array_map(function($img) {
    return [
        'filename' => $img['filename'],
        'category' => $img['category'],
        'path' => $img['path'],
        'relative_path' => $img['relative_path'],
        'extension' => $img['extension'],
        'size_formatted' => formatBytes($img['size']),
        'size_bytes' => $img['size'],
        'modified_formatted' => date('d/m/Y H:i', $img['modified']),
        'modified_timestamp' => $img['modified']
    ];
}, $paginatedImages);

echo json_encode([
    'success' => true,
    'images' => $formattedImages,
    'pagination' => [
        'currentPage' => $page,
        'totalPages' => $totalPages,
        'totalItems' => $totalImages,
        'itemsPerPage' => $itemsPerPage
    ],
    'currentPage' => $page,
    'totalPages' => $totalPages,
    'stats' => [
        'total_images' => $totalImages,
        'by_category' => getCategoryStats($allImages)
    ]
]);

function formatBytes($size, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB'];
    
    for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
        $size /= 1024;
    }
    
    return round($size, $precision) . ' ' . $units[$i];
}

function getCategoryStats($images) {
    $stats = [];
    
    foreach ($images as $img) {
        $category = $img['category'];
        if (!isset($stats[$category])) {
            $stats[$category] = 0;
        }
        $stats[$category]++;
    }
    
    return $stats;
}
?>