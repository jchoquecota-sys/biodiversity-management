<?php
/**
 * International Species Image Downloader
 * Descarga imágenes de especies desde múltiples fuentes internacionales
 * Fuentes: iNaturalist, GBIF, Wikimedia Commons, BHL Flickr
 */

require_once 'config/database.php';

class InternationalImageDownloader {
    private $pdo;
    private $imageDir;
    private $logFile;
    private $downloadStats;
    
    // APIs y configuraciones
    private $apis = [
        'inaturalist' => [
            'base_url' => 'https://api.inaturalist.org/v1/',
            'rate_limit' => 100, // requests per minute
            'last_request' => 0
        ],
        'gbif' => [
            'base_url' => 'https://api.gbif.org/v1/',
            'rate_limit' => 100,
            'last_request' => 0
        ],
        'wikimedia' => [
            'base_url' => 'https://commons.wikimedia.org/w/api.php',
            'rate_limit' => 200,
            'last_request' => 0
        ],
        'flickr_bhl' => [
            'base_url' => 'https://api.flickr.com/services/rest/',
            'api_key' => '', // Requiere API key de Flickr
            'rate_limit' => 3600, // per hour
            'last_request' => 0
        ]
    ];
    
    public function __construct() {
        // Conexión a base de datos
        $database = new Database();
        $this->pdo = $database->getConnection();
        
        // Configurar directorios
        $this->imageDir = __DIR__ . '/public/images/especies';
        $this->logFile = __DIR__ . '/logs/international_download.log';
        
        // Crear directorio de logs si no existe
        if (!file_exists(dirname($this->logFile))) {
            mkdir(dirname($this->logFile), 0755, true);
        }
        
        // Inicializar estadísticas
        $this->downloadStats = [
            'total_species' => 0,
            'images_downloaded' => 0,
            'sources_used' => [],
            'errors' => []
        ];
        
        $this->log("=== Iniciando descarga internacional de imágenes ===");
    }
    
    /**
     * Obtener especies sin imágenes de la base de datos
     */
    public function getSpeciesWithoutImages($limit = 100) {
        $sql = "SELECT id, nombre_cientifico, nombre_comun, categoria_taxonomica 
                FROM biodiversity_categories 
                WHERE (imagen_url IS NULL OR imagen_url = '' OR imagen_url LIKE '%placeholder%') 
                AND nombre_cientifico IS NOT NULL 
                AND nombre_cientifico != ''
                ORDER BY RAND() 
                LIMIT :limit";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Descargar imágenes para una lista de especies
     */
    public function downloadImagesForSpecies($species_list) {
        $this->downloadStats['total_species'] = count($species_list);
        
        foreach ($species_list as $species) {
            $this->log("Procesando: {$species['nombre_cientifico']}");
            
            $downloaded = false;
            
            // Intentar descargar desde cada fuente
            $sources = ['inaturalist', 'gbif', 'wikimedia'];
            
            foreach ($sources as $source) {
                if ($downloaded) break;
                
                try {
                    $images = $this->searchImages($species, $source);
                    
                    if (!empty($images)) {
                        $result = $this->downloadBestImage($species, $images, $source);
                        if ($result) {
                            $downloaded = true;
                            $this->downloadStats['images_downloaded']++;
                            $this->downloadStats['sources_used'][$source] = 
                                ($this->downloadStats['sources_used'][$source] ?? 0) + 1;
                        }
                    }
                } catch (Exception $e) {
                    $this->log("Error con {$source} para {$species['nombre_cientifico']}: " . $e->getMessage());
                    $this->downloadStats['errors'][] = [
                        'species' => $species['nombre_cientifico'],
                        'source' => $source,
                        'error' => $e->getMessage()
                    ];
                }
                
                // Respetar rate limits
                $this->respectRateLimit($source);
            }
            
            if (!$downloaded) {
                $this->log("No se encontraron imágenes para: {$species['nombre_cientifico']}");
            }
        }
        
        $this->generateReport();
    }
    
    /**
     * Buscar imágenes en una fuente específica
     */
    private function searchImages($species, $source) {
        switch ($source) {
            case 'inaturalist':
                return $this->searchINaturalist($species);
            case 'gbif':
                return $this->searchGBIF($species);
            case 'wikimedia':
                return $this->searchWikimedia($species);
            default:
                return [];
        }
    }
    
    /**
     * Buscar en iNaturalist
     */
    private function searchINaturalist($species) {
        $scientific_name = urlencode($species['nombre_cientifico']);
        $url = $this->apis['inaturalist']['base_url'] . "observations?taxon_name={$scientific_name}&photos=true&quality_grade=research&per_page=5";
        
        $response = $this->makeAPIRequest($url);
        if (!$response) return [];
        
        $data = json_decode($response, true);
        $images = [];
        
        if (isset($data['results'])) {
            foreach ($data['results'] as $observation) {
                if (isset($observation['photos'])) {
                    foreach ($observation['photos'] as $photo) {
                        $images[] = [
                            'url' => $photo['url'],
                            'medium_url' => str_replace('square', 'medium', $photo['url']),
                            'license' => $photo['license_code'] ?? 'unknown',
                            'attribution' => $photo['attribution'] ?? '',
                            'quality_score' => $this->calculateQualityScore($observation, 'inaturalist')
                        ];
                    }
                }
            }
        }
        
        return $images;
    }
    
    /**
     * Buscar en GBIF
     */
    private function searchGBIF($species) {
        // Primero buscar el taxon key
        $scientific_name = urlencode($species['nombre_cientifico']);
        $url = $this->apis['gbif']['base_url'] . "species/match?name={$scientific_name}";
        
        $response = $this->makeAPIRequest($url);
        if (!$response) return [];
        
        $taxon_data = json_decode($response, true);
        if (!isset($taxon_data['usageKey'])) return [];
        
        // Buscar observaciones con imágenes
        $taxon_key = $taxon_data['usageKey'];
        $url = $this->apis['gbif']['base_url'] . "occurrence/search?taxonKey={$taxon_key}&mediaType=StillImage&limit=5";
        
        $response = $this->makeAPIRequest($url);
        if (!$response) return [];
        
        $data = json_decode($response, true);
        $images = [];
        
        if (isset($data['results'])) {
            foreach ($data['results'] as $occurrence) {
                if (isset($occurrence['media'])) {
                    foreach ($occurrence['media'] as $media) {
                        if ($media['type'] === 'StillImage') {
                            $images[] = [
                                'url' => $media['identifier'],
                                'medium_url' => $media['identifier'],
                                'license' => $media['license'] ?? 'unknown',
                                'attribution' => $media['rightsHolder'] ?? '',
                                'quality_score' => $this->calculateQualityScore($occurrence, 'gbif')
                            ];
                        }
                    }
                }
            }
        }
        
        return $images;
    }
    
    /**
     * Buscar en Wikimedia Commons
     */
    private function searchWikimedia($species) {
        $scientific_name = urlencode($species['nombre_cientifico']);
        
        // Buscar por nombre científico
        $url = $this->apis['wikimedia']['base_url'] . "?action=query&format=json&generator=search&gsrsearch={$scientific_name}&gsrnamespace=6&gsrlimit=5&prop=imageinfo&iiprop=url|size|mime";
        
        $response = $this->makeAPIRequest($url);
        if (!$response) return [];
        
        $data = json_decode($response, true);
        $images = [];
        
        if (isset($data['query']['pages'])) {
            foreach ($data['query']['pages'] as $page) {
                if (isset($page['imageinfo'][0])) {
                    $info = $page['imageinfo'][0];
                    if (strpos($info['mime'], 'image/') === 0) {
                        $images[] = [
                            'url' => $info['url'],
                            'medium_url' => $info['url'],
                            'license' => 'CC-BY-SA', // Wikimedia Commons default
                            'attribution' => 'Wikimedia Commons',
                            'quality_score' => $this->calculateQualityScore($info, 'wikimedia')
                        ];
                    }
                }
            }
        }
        
        return $images;
    }
    
    /**
     * Calcular puntuación de calidad de imagen
     */
    private function calculateQualityScore($data, $source) {
        $score = 50; // Base score
        
        switch ($source) {
            case 'inaturalist':
                if (isset($data['quality_grade']) && $data['quality_grade'] === 'research') {
                    $score += 30;
                }
                if (isset($data['faves_count'])) {
                    $score += min($data['faves_count'] * 2, 20);
                }
                break;
                
            case 'gbif':
                if (isset($data['issues']) && empty($data['issues'])) {
                    $score += 20;
                }
                break;
                
            case 'wikimedia':
                if (isset($data['size']) && $data['size'] > 100000) {
                    $score += 20;
                }
                break;
        }
        
        return min($score, 100);
    }
    
    /**
     * Descargar la mejor imagen disponible
     */
    private function downloadBestImage($species, $images, $source) {
        // Ordenar por calidad
        usort($images, function($a, $b) {
            return $b['quality_score'] - $a['quality_score'];
        });
        
        $best_image = $images[0];
        
        // Determinar categoría taxonómica
        $category = $this->determineTaxonomicCategory($species);
        
        // Crear directorio si no existe
        $category_dir = $this->imageDir . '/' . $category;
        if (!file_exists($category_dir)) {
            mkdir($category_dir, 0755, true);
        }
        
        // Generar nombre de archivo seguro
        $safe_name = $this->generateSafeFilename($species['nombre_cientifico']);
        $extension = $this->getImageExtension($best_image['medium_url']);
        $filename = $safe_name . '.' . $extension;
        $filepath = $category_dir . '/' . $filename;
        
        // Descargar imagen
        $image_data = $this->downloadImage($best_image['medium_url']);
        if (!$image_data) {
            return false;
        }
        
        // Guardar imagen
        if (file_put_contents($filepath, $image_data)) {
            // Actualizar base de datos
            $relative_path = 'images/especies/' . $category . '/' . $filename;
            $this->updateSpeciesImage($species['id'], $relative_path, $source, $best_image);
            
            $this->log("Imagen descargada: {$filename} desde {$source}");
            return true;
        }
        
        return false;
    }
    
    /**
     * Determinar categoría taxonómica
     */
    private function determineTaxonomicCategory($species) {
        $categoria = strtolower($species['categoria_taxonomica'] ?? '');
        $nombre = strtolower($species['nombre_cientifico'] ?? '');
        $comun = strtolower($species['nombre_comun'] ?? '');
        
        // Mapeo de categorías
        $categories = [
            'aves' => ['ave', 'bird', 'pájaro', 'gallus', 'falco', 'aquila'],
            'mamiferos' => ['mammal', 'mamífero', 'homo', 'canis', 'felis', 'mus'],
            'reptiles' => ['reptil', 'reptile', 'serpiente', 'lagarto', 'gecko', 'iguana'],
            'anfibios' => ['anfibio', 'amphibian', 'rana', 'sapo', 'salamandra'],
            'peces' => ['pez', 'fish', 'tiburón', 'atún'],
            'plantas' => ['planta', 'plant', 'árbol', 'flor', 'hierba']
        ];
        
        foreach ($categories as $cat => $keywords) {
            foreach ($keywords as $keyword) {
                if (strpos($categoria, $keyword) !== false || 
                    strpos($nombre, $keyword) !== false || 
                    strpos($comun, $keyword) !== false) {
                    return $cat;
                }
            }
        }
        
        return 'otros';
    }
    
    /**
     * Actualizar imagen de especie en base de datos
     */
    private function updateSpeciesImage($species_id, $image_path, $source, $image_data) {
        $sql = "UPDATE biodiversity_categories 
                SET imagen_url = :image_path, 
                    updated_at = NOW() 
                WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':image_path', $image_path);
        $stmt->bindParam(':id', $species_id);
        $stmt->execute();
        
        // Registrar metadatos de la imagen
        $this->logImageMetadata($species_id, $source, $image_data);
    }
    
    /**
     * Registrar metadatos de imagen
     */
    private function logImageMetadata($species_id, $source, $image_data) {
        $sql = "INSERT INTO image_metadata 
                (species_id, source, license, attribution, quality_score, downloaded_at) 
                VALUES (:species_id, :source, :license, :attribution, :quality_score, NOW())
                ON DUPLICATE KEY UPDATE 
                source = VALUES(source), 
                license = VALUES(license), 
                attribution = VALUES(attribution), 
                quality_score = VALUES(quality_score), 
                downloaded_at = VALUES(downloaded_at)";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':species_id', $species_id);
            $stmt->bindParam(':source', $source);
            $stmt->bindParam(':license', $image_data['license']);
            $stmt->bindParam(':attribution', $image_data['attribution']);
            $stmt->bindParam(':quality_score', $image_data['quality_score']);
            $stmt->execute();
        } catch (PDOException $e) {
            // Crear tabla si no existe
            $this->createImageMetadataTable();
            // Reintentar
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':species_id', $species_id);
            $stmt->bindParam(':source', $source);
            $stmt->bindParam(':license', $image_data['license']);
            $stmt->bindParam(':attribution', $image_data['attribution']);
            $stmt->bindParam(':quality_score', $image_data['quality_score']);
            $stmt->execute();
        }
    }
    
    /**
     * Crear tabla de metadatos si no existe
     */
    private function createImageMetadataTable() {
        $sql = "CREATE TABLE IF NOT EXISTS image_metadata (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    species_id INT NOT NULL,
                    source VARCHAR(50) NOT NULL,
                    license VARCHAR(100),
                    attribution TEXT,
                    quality_score INT DEFAULT 0,
                    downloaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    UNIQUE KEY unique_species (species_id),
                    FOREIGN KEY (species_id) REFERENCES biodiversity_categories(id) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $this->pdo->exec($sql);
    }
    
    /**
     * Utilidades
     */
    private function makeAPIRequest($url) {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => [
                    'User-Agent: BiodiversityManagement/1.0 (Educational Purpose)',
                    'Accept: application/json'
                ],
                'timeout' => 30
            ]
        ]);
        
        return @file_get_contents($url, false, $context);
    }
    
    private function downloadImage($url) {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => 'User-Agent: BiodiversityManagement/1.0',
                'timeout' => 60
            ]
        ]);
        
        return @file_get_contents($url, false, $context);
    }
    
    private function respectRateLimit($source) {
        $now = time();
        $last_request = $this->apis[$source]['last_request'];
        $rate_limit = $this->apis[$source]['rate_limit'];
        
        $min_interval = 60 / $rate_limit; // seconds between requests
        $elapsed = $now - $last_request;
        
        if ($elapsed < $min_interval) {
            $sleep_time = $min_interval - $elapsed;
            sleep((int)$sleep_time + 1);
        }
        
        $this->apis[$source]['last_request'] = time();
    }
    
    private function generateSafeFilename($scientific_name) {
        return preg_replace('/[^a-zA-Z0-9_-]/', '_', $scientific_name);
    }
    
    private function getImageExtension($url) {
        $path = parse_url($url, PHP_URL_PATH);
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        return $extension ?: 'jpg';
    }
    
    private function log($message) {
        $timestamp = date('Y-m-d H:i:s');
        $log_entry = "[{$timestamp}] {$message}" . PHP_EOL;
        file_put_contents($this->logFile, $log_entry, FILE_APPEND | LOCK_EX);
        echo $log_entry;
    }
    
    /**
     * Generar reporte final
     */
    private function generateReport() {
        $this->log("\n=== REPORTE FINAL ===");
        $this->log("Especies procesadas: {$this->downloadStats['total_species']}");
        $this->log("Imágenes descargadas: {$this->downloadStats['images_downloaded']}");
        
        if (!empty($this->downloadStats['sources_used'])) {
            $this->log("\nFuentes utilizadas:");
            foreach ($this->downloadStats['sources_used'] as $source => $count) {
                $this->log("- {$source}: {$count} imágenes");
            }
        }
        
        $success_rate = $this->downloadStats['total_species'] > 0 ? 
            round(($this->downloadStats['images_downloaded'] / $this->downloadStats['total_species']) * 100, 2) : 0;
        
        $this->log("\nTasa de éxito: {$success_rate}%");
        
        if (!empty($this->downloadStats['errors'])) {
            $this->log("\nErrores encontrados: " . count($this->downloadStats['errors']));
        }
        
        $this->log("=== FIN DEL REPORTE ===");
    }
}

// Ejecutar si se llama directamente
if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
    $downloader = new InternationalImageDownloader();
    
    // Obtener especies sin imágenes
    $species_list = $downloader->getSpeciesWithoutImages(50); // Procesar 50 especies
    
    if (empty($species_list)) {
        echo "No se encontraron especies sin imágenes.\n";
    } else {
        echo "Procesando " . count($species_list) . " especies...\n";
        $downloader->downloadImagesForSpecies($species_list);
    }
}
?>