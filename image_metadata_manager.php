<?php
/**
 * Gestor de Metadatos de Imágenes
 * Sistema para rastrear fuente, licencia y metadatos de cada imagen de especies
 */

require_once 'config/database.php';

class ImageMetadataManager {
    private $pdo;
    private $logFile;
    
    // Tipos de licencias comunes
    private $license_types = [
        'CC0' => [
            'name' => 'Creative Commons Zero',
            'description' => 'Dominio público, sin restricciones',
            'commercial_use' => true,
            'attribution_required' => false,
            'url' => 'https://creativecommons.org/publicdomain/zero/1.0/'
        ],
        'CC_BY' => [
            'name' => 'Creative Commons Attribution',
            'description' => 'Uso libre con atribución',
            'commercial_use' => true,
            'attribution_required' => true,
            'url' => 'https://creativecommons.org/licenses/by/4.0/'
        ],
        'CC_BY_SA' => [
            'name' => 'Creative Commons Attribution-ShareAlike',
            'description' => 'Uso libre con atribución y compartir igual',
            'commercial_use' => true,
            'attribution_required' => true,
            'url' => 'https://creativecommons.org/licenses/by-sa/4.0/'
        ],
        'CC_BY_NC' => [
            'name' => 'Creative Commons Attribution-NonCommercial',
            'description' => 'Uso no comercial con atribución',
            'commercial_use' => false,
            'attribution_required' => true,
            'url' => 'https://creativecommons.org/licenses/by-nc/4.0/'
        ],
        'CC_BY_NC_SA' => [
            'name' => 'Creative Commons Attribution-NonCommercial-ShareAlike',
            'description' => 'Uso no comercial con atribución y compartir igual',
            'commercial_use' => false,
            'attribution_required' => true,
            'url' => 'https://creativecommons.org/licenses/by-nc-sa/4.0/'
        ],
        'RIGHTS_MANAGED' => [
            'name' => 'Derechos Gestionados',
            'description' => 'Uso restringido, requiere permiso',
            'commercial_use' => false,
            'attribution_required' => true,
            'url' => null
        ],
        'UNKNOWN' => [
            'name' => 'Licencia Desconocida',
            'description' => 'Licencia no especificada',
            'commercial_use' => false,
            'attribution_required' => true,
            'url' => null
        ]
    ];
    
    // Fuentes de imágenes conocidas
    private $image_sources = [
        'inaturalist' => [
            'name' => 'iNaturalist',
            'base_url' => 'https://www.inaturalist.org',
            'api_url' => 'https://api.inaturalist.org/v1',
            'default_license' => 'CC_BY_NC',
            'attribution_format' => '{photographer} via iNaturalist'
        ],
        'gbif' => [
            'name' => 'GBIF',
            'base_url' => 'https://www.gbif.org',
            'api_url' => 'https://api.gbif.org/v1',
            'default_license' => 'CC_BY',
            'attribution_format' => '{photographer} via GBIF'
        ],
        'wikimedia' => [
            'name' => 'Wikimedia Commons',
            'base_url' => 'https://commons.wikimedia.org',
            'api_url' => 'https://commons.wikimedia.org/w/api.php',
            'default_license' => 'CC_BY_SA',
            'attribution_format' => '{photographer} via Wikimedia Commons'
        ],
        'flickr_bhl' => [
            'name' => 'Flickr BHL',
            'base_url' => 'https://www.flickr.com/photos/biodivlibrary',
            'api_url' => 'https://api.flickr.com/services/rest',
            'default_license' => 'CC_BY',
            'attribution_format' => 'Biodiversity Heritage Library via Flickr'
        ],
        'eol' => [
            'name' => 'Encyclopedia of Life',
            'base_url' => 'https://eol.org',
            'api_url' => 'https://eol.org/api',
            'default_license' => 'CC_BY_NC_SA',
            'attribution_format' => '{photographer} via Encyclopedia of Life'
        ],
        'manual' => [
            'name' => 'Carga Manual',
            'base_url' => null,
            'api_url' => null,
            'default_license' => 'UNKNOWN',
            'attribution_format' => '{photographer}'
        ]
    ];
    
    public function __construct() {
        $database = new Database();
        $this->pdo = $database->getConnection();
        
        $this->logFile = __DIR__ . '/logs/metadata_manager.log';
        
        if (!file_exists(dirname($this->logFile))) {
            mkdir(dirname($this->logFile), 0755, true);
        }
        
        $this->createMetadataTables();
        $this->log("=== Iniciando gestor de metadatos de imágenes ===");
    }
    
    /**
     * Procesar solicitud AJAX
     */
    public function handleAjaxRequest() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(['error' => 'Método no permitido']);
        }
        
        $action = $_POST['action'] ?? '';
        
        switch ($action) {
            case 'get_metadata_stats':
                return $this->jsonResponse($this->getMetadataStats());
                
            case 'scan_missing_metadata':
                return $this->jsonResponse($this->scanMissingMetadata());
                
            case 'update_image_metadata':
                $image_id = intval($_POST['image_id'] ?? 0);
                $metadata = $_POST['metadata'] ?? [];
                return $this->jsonResponse($this->updateImageMetadata($image_id, $metadata));
                
            case 'bulk_update_metadata':
                $source = $_POST['source'] ?? '';
                return $this->jsonResponse($this->bulkUpdateMetadata($source));
                
            case 'get_license_info':
                $license_code = $_POST['license_code'] ?? '';
                return $this->jsonResponse($this->getLicenseInfo($license_code));
                
            case 'generate_attribution':
                $image_id = intval($_POST['image_id'] ?? 0);
                return $this->jsonResponse($this->generateAttribution($image_id));
                
            case 'export_metadata_report':
                return $this->jsonResponse($this->exportMetadataReport());
                
            default:
                return $this->jsonResponse(['error' => 'Acción no válida']);
        }
    }
    
    /**
     * Obtener estadísticas de metadatos
     */
    public function getMetadataStats() {
        $stats = [
            'total_images' => $this->getTotalImagesCount(),
            'images_with_metadata' => $this->getImagesWithMetadataCount(),
            'images_missing_metadata' => 0,
            'license_distribution' => $this->getLicenseDistribution(),
            'source_distribution' => $this->getSourceDistribution(),
            'attribution_coverage' => $this->getAttributionCoverage()
        ];
        
        $stats['images_missing_metadata'] = $stats['total_images'] - $stats['images_with_metadata'];
        $stats['metadata_coverage_percentage'] = $stats['total_images'] > 0 ? 
            round(($stats['images_with_metadata'] / $stats['total_images']) * 100, 2) : 0;
        
        return [
            'success' => true,
            'stats' => $stats,
            'license_types' => $this->license_types,
            'image_sources' => $this->image_sources
        ];
    }
    
    /**
     * Escanear imágenes sin metadatos
     */
    public function scanMissingMetadata() {
        $sql = "SELECT bc.id, bc.nombre_cientifico, bc.imagen_url, bc.fuente_datos
                FROM biodiversity_categories bc
                LEFT JOIN image_metadata im ON bc.id = im.species_id
                WHERE bc.imagen_url IS NOT NULL 
                AND bc.imagen_url != ''
                AND bc.imagen_url NOT LIKE '%placeholder%'
                AND im.id IS NULL
                ORDER BY bc.id
                LIMIT 100";
        
        $stmt = $this->pdo->query($sql);
        $missing_images = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $processed = 0;
        $results = [];
        
        foreach ($missing_images as $image) {
            $metadata = $this->extractMetadataFromSource($image);
            
            if ($this->saveImageMetadata($image['id'], $metadata)) {
                $processed++;
                $results[] = [
                    'species_id' => $image['id'],
                    'scientific_name' => $image['nombre_cientifico'],
                    'metadata' => $metadata
                ];
            }
        }
        
        $this->log("Escaneadas {$processed} imágenes sin metadatos");
        
        return [
            'success' => true,
            'processed' => $processed,
            'total_found' => count($missing_images),
            'results' => $results
        ];
    }
    
    /**
     * Extraer metadatos de la fuente
     */
    private function extractMetadataFromSource($image_data) {
        $source = strtolower($image_data['fuente_datos'] ?? 'manual');
        $image_url = $image_data['imagen_url'];
        
        // Determinar fuente basada en URL si no está especificada
        if ($source === 'manual' || empty($source)) {
            $source = $this->detectSourceFromUrl($image_url);
        }
        
        $metadata = [
            'source' => $source,
            'source_url' => $image_url,
            'license_type' => $this->image_sources[$source]['default_license'] ?? 'UNKNOWN',
            'photographer' => 'Desconocido',
            'date_taken' => null,
            'location' => null,
            'original_url' => $image_url,
            'download_date' => date('Y-m-d H:i:s'),
            'file_size' => $this->getImageFileSize($image_url),
            'image_dimensions' => $this->getImageDimensions($image_url),
            'quality_score' => null,
            'usage_rights' => $this->license_types[$this->image_sources[$source]['default_license'] ?? 'UNKNOWN']['description'] ?? 'Desconocido'
        ];
        
        // Intentar obtener metadatos específicos de la fuente
        switch ($source) {
            case 'inaturalist':
                $metadata = array_merge($metadata, $this->getINaturalistMetadata($image_url));
                break;
            case 'gbif':
                $metadata = array_merge($metadata, $this->getGBIFMetadata($image_url));
                break;
            case 'wikimedia':
                $metadata = array_merge($metadata, $this->getWikimediaMetadata($image_url));
                break;
        }
        
        return $metadata;
    }
    
    /**
     * Detectar fuente desde URL
     */
    private function detectSourceFromUrl($url) {
        if (strpos($url, 'inaturalist') !== false) {
            return 'inaturalist';
        } elseif (strpos($url, 'gbif') !== false) {
            return 'gbif';
        } elseif (strpos($url, 'wikimedia') !== false || strpos($url, 'wikipedia') !== false) {
            return 'wikimedia';
        } elseif (strpos($url, 'flickr') !== false && strpos($url, 'biodivlibrary') !== false) {
            return 'flickr_bhl';
        } elseif (strpos($url, 'eol.org') !== false) {
            return 'eol';
        }
        
        return 'manual';
    }
    
    /**
     * Obtener metadatos de iNaturalist
     */
    private function getINaturalistMetadata($image_url) {
        // Extraer ID de observación de la URL
        preg_match('/\/photos\/(\d+)/', $image_url, $matches);
        
        if (!isset($matches[1])) {
            return [];
        }
        
        $photo_id = $matches[1];
        $api_url = "https://api.inaturalist.org/v1/photos/{$photo_id}";
        
        $response = $this->makeAPIRequest($api_url);
        
        if (!$response || !isset($response['results'][0])) {
            return [];
        }
        
        $photo_data = $response['results'][0];
        
        return [
            'photographer' => $photo_data['attribution'] ?? 'Usuario de iNaturalist',
            'license_type' => $this->mapINaturalistLicense($photo_data['license_code'] ?? null),
            'date_taken' => $photo_data['created_at'] ?? null,
            'original_url' => $photo_data['url'] ?? $image_url
        ];
    }
    
    /**
     * Obtener metadatos de GBIF
     */
    private function getGBIFMetadata($image_url) {
        // GBIF metadata extraction (simplified)
        return [
            'photographer' => 'Contribuidor GBIF',
            'license_type' => 'CC_BY',
            'usage_rights' => 'Uso libre con atribución requerida'
        ];
    }
    
    /**
     * Obtener metadatos de Wikimedia
     */
    private function getWikimediaMetadata($image_url) {
        // Wikimedia metadata extraction (simplified)
        return [
            'photographer' => 'Contribuidor Wikimedia',
            'license_type' => 'CC_BY_SA',
            'usage_rights' => 'Uso libre con atribución y compartir igual'
        ];
    }
    
    /**
     * Mapear licencia de iNaturalist
     */
    private function mapINaturalistLicense($license_code) {
        $license_map = [
            'cc0' => 'CC0',
            'cc-by' => 'CC_BY',
            'cc-by-sa' => 'CC_BY_SA',
            'cc-by-nc' => 'CC_BY_NC',
            'cc-by-nc-sa' => 'CC_BY_NC_SA'
        ];
        
        return $license_map[$license_code] ?? 'CC_BY_NC';
    }
    
    /**
     * Guardar metadatos de imagen
     */
    public function saveImageMetadata($species_id, $metadata) {
        try {
            $sql = "INSERT INTO image_metadata 
                    (species_id, source, source_url, license_type, photographer, date_taken, 
                     location, original_url, download_date, file_size, image_width, image_height, 
                     quality_score, usage_rights, attribution_text, created_at) 
                    VALUES 
                    (:species_id, :source, :source_url, :license_type, :photographer, :date_taken,
                     :location, :original_url, :download_date, :file_size, :image_width, :image_height,
                     :quality_score, :usage_rights, :attribution_text, NOW())
                    ON DUPLICATE KEY UPDATE
                    source = VALUES(source),
                    source_url = VALUES(source_url),
                    license_type = VALUES(license_type),
                    photographer = VALUES(photographer),
                    date_taken = VALUES(date_taken),
                    location = VALUES(location),
                    original_url = VALUES(original_url),
                    file_size = VALUES(file_size),
                    image_width = VALUES(image_width),
                    image_height = VALUES(image_height),
                    quality_score = VALUES(quality_score),
                    usage_rights = VALUES(usage_rights),
                    attribution_text = VALUES(attribution_text),
                    updated_at = NOW()";
            
            $stmt = $this->pdo->prepare($sql);
            
            $dimensions = $metadata['image_dimensions'] ?? [];
            $attribution = $this->generateAttributionText($metadata);
            
            $stmt->execute([
                ':species_id' => $species_id,
                ':source' => $metadata['source'],
                ':source_url' => $metadata['source_url'],
                ':license_type' => $metadata['license_type'],
                ':photographer' => $metadata['photographer'],
                ':date_taken' => $metadata['date_taken'],
                ':location' => $metadata['location'],
                ':original_url' => $metadata['original_url'],
                ':download_date' => $metadata['download_date'],
                ':file_size' => $metadata['file_size'],
                ':image_width' => $dimensions['width'] ?? null,
                ':image_height' => $dimensions['height'] ?? null,
                ':quality_score' => $metadata['quality_score'],
                ':usage_rights' => $metadata['usage_rights'],
                ':attribution_text' => $attribution
            ]);
            
            return true;
            
        } catch (Exception $e) {
            $this->log("Error guardando metadatos para especie {$species_id}: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Generar texto de atribución
     */
    private function generateAttributionText($metadata) {
        $source = $metadata['source'];
        $photographer = $metadata['photographer'];
        
        if (!isset($this->image_sources[$source])) {
            return $photographer;
        }
        
        $format = $this->image_sources[$source]['attribution_format'];
        
        return str_replace('{photographer}', $photographer, $format);
    }
    
    /**
     * Obtener tamaño de archivo de imagen
     */
    private function getImageFileSize($image_url) {
        $full_path = $this->getFullImagePath($image_url);
        
        if (file_exists($full_path)) {
            return filesize($full_path);
        }
        
        return null;
    }
    
    /**
     * Obtener dimensiones de imagen
     */
    private function getImageDimensions($image_url) {
        $full_path = $this->getFullImagePath($image_url);
        
        if (file_exists($full_path)) {
            $image_info = getimagesize($full_path);
            
            if ($image_info) {
                return [
                    'width' => $image_info[0],
                    'height' => $image_info[1]
                ];
            }
        }
        
        return [];
    }
    
    /**
     * Obtener ruta completa de imagen
     */
    private function getFullImagePath($relative_path) {
        return __DIR__ . '/public/' . ltrim($relative_path, '/');
    }
    
    /**
     * Obtener conteo total de imágenes
     */
    private function getTotalImagesCount() {
        $sql = "SELECT COUNT(*) FROM biodiversity_categories 
                WHERE imagen_url IS NOT NULL 
                AND imagen_url != '' 
                AND imagen_url NOT LIKE '%placeholder%'";
        
        return $this->pdo->query($sql)->fetchColumn();
    }
    
    /**
     * Obtener conteo de imágenes con metadatos
     */
    private function getImagesWithMetadataCount() {
        $sql = "SELECT COUNT(*) FROM image_metadata";
        return $this->pdo->query($sql)->fetchColumn();
    }
    
    /**
     * Obtener distribución de licencias
     */
    private function getLicenseDistribution() {
        $sql = "SELECT license_type, COUNT(*) as count 
                FROM image_metadata 
                GROUP BY license_type 
                ORDER BY count DESC";
        
        $stmt = $this->pdo->query($sql);
        $distribution = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $license_info = $this->license_types[$row['license_type']] ?? ['name' => $row['license_type']];
            $distribution[] = [
                'license_type' => $row['license_type'],
                'license_name' => $license_info['name'],
                'count' => $row['count']
            ];
        }
        
        return $distribution;
    }
    
    /**
     * Obtener distribución de fuentes
     */
    private function getSourceDistribution() {
        $sql = "SELECT source, COUNT(*) as count 
                FROM image_metadata 
                GROUP BY source 
                ORDER BY count DESC";
        
        $stmt = $this->pdo->query($sql);
        $distribution = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $source_info = $this->image_sources[$row['source']] ?? ['name' => $row['source']];
            $distribution[] = [
                'source' => $row['source'],
                'source_name' => $source_info['name'],
                'count' => $row['count']
            ];
        }
        
        return $distribution;
    }
    
    /**
     * Obtener cobertura de atribución
     */
    private function getAttributionCoverage() {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN photographer != 'Desconocido' THEN 1 ELSE 0 END) as with_photographer,
                    SUM(CASE WHEN attribution_text IS NOT NULL THEN 1 ELSE 0 END) as with_attribution
                FROM image_metadata";
        
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return [
            'total' => $result['total'],
            'with_photographer' => $result['with_photographer'],
            'with_attribution' => $result['with_attribution'],
            'photographer_percentage' => $result['total'] > 0 ? 
                round(($result['with_photographer'] / $result['total']) * 100, 2) : 0,
            'attribution_percentage' => $result['total'] > 0 ? 
                round(($result['with_attribution'] / $result['total']) * 100, 2) : 0
        ];
    }
    
    /**
     * Crear tablas de metadatos
     */
    private function createMetadataTables() {
        $sql = "CREATE TABLE IF NOT EXISTS image_metadata (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    species_id INT NOT NULL,
                    source VARCHAR(50) NOT NULL,
                    source_url TEXT,
                    license_type VARCHAR(20) DEFAULT 'UNKNOWN',
                    photographer VARCHAR(255) DEFAULT 'Desconocido',
                    date_taken DATETIME NULL,
                    location VARCHAR(255) NULL,
                    original_url TEXT,
                    download_date DATETIME,
                    file_size BIGINT NULL,
                    image_width INT NULL,
                    image_height INT NULL,
                    quality_score DECIMAL(5,2) NULL,
                    usage_rights TEXT,
                    attribution_text TEXT,
                    notes TEXT,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    UNIQUE KEY unique_species (species_id),
                    INDEX idx_source (source),
                    INDEX idx_license (license_type),
                    INDEX idx_photographer (photographer),
                    FOREIGN KEY (species_id) REFERENCES biodiversity_categories(id) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $this->pdo->exec($sql);
        
        // Tabla de historial de cambios de metadatos
        $sql = "CREATE TABLE IF NOT EXISTS metadata_change_log (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    species_id INT NOT NULL,
                    field_name VARCHAR(50) NOT NULL,
                    old_value TEXT,
                    new_value TEXT,
                    changed_by VARCHAR(100) DEFAULT 'System',
                    change_reason VARCHAR(255),
                    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    INDEX idx_species (species_id),
                    INDEX idx_field (field_name),
                    INDEX idx_date (changed_at)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $this->pdo->exec($sql);
    }
    
    /**
     * Realizar solicitud API
     */
    private function makeAPIRequest($url) {
        $context = stream_context_create([
            'http' => [
                'timeout' => 30,
                'user_agent' => 'BiodiversityManager/1.0'
            ]
        ]);
        
        $response = file_get_contents($url, false, $context);
        
        if ($response === false) {
            return null;
        }
        
        return json_decode($response, true);
    }
    
    /**
     * Respuesta JSON
     */
    private function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    private function log($message) {
        $timestamp = date('Y-m-d H:i:s');
        $log_entry = "[{$timestamp}] {$message}" . PHP_EOL;
        file_put_contents($this->logFile, $log_entry, FILE_APPEND | LOCK_EX);
    }
}

// Manejar solicitudes AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $manager = new ImageMetadataManager();
    $manager->handleAjaxRequest();
    exit;
}

$manager = new ImageMetadataManager();
$metadata_stats = $manager->getMetadataStats();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Metadatos de Imágenes - Sistema de Biodiversidad</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }
        
        .header h1 {
            color: #333;
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #666;
            font-size: 1.1em;
        }
        
        .stats-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card h3 {
            font-size: 1.1em;
            margin-bottom: 10px;
            opacity: 0.9;
        }
        
        .stat-card .number {
            font-size: 2.2em;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .stat-card .percentage {
            font-size: 0.9em;
            opacity: 0.8;
        }
        
        .main-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .section h2 {
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }
        
        .distribution-chart {
            margin-bottom: 20px;
        }
        
        .chart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            margin-bottom: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        
        .chart-label {
            font-weight: bold;
            color: #333;
        }
        
        .chart-value {
            background: #667eea;
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.9em;
        }
        
        .actions-panel {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .action-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .action-card h3 {
            color: #333;
            margin-bottom: 15px;
        }
        
        .action-card p {
            color: #666;
            margin-bottom: 15px;
            font-size: 0.95em;
        }
        
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            width: 100%;
            text-align: center;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        
        .progress-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-top: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            display: none;
        }
        
        .progress-bar {
            width: 100%;
            height: 20px;
            background: #f0f0f0;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea, #764ba2);
            width: 0%;
            transition: width 0.3s ease;
        }
        
        .license-info {
            background: #e3f2fd;
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
        }
        
        .license-badge {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.85em;
            margin-right: 10px;
            margin-bottom: 5px;
        }
        
        .license-badge.commercial {
            background: #28a745;
        }
        
        .license-badge.non-commercial {
            background: #ffc107;
            color: #333;
        }
        
        .license-badge.attribution {
            background: #17a2b8;
        }
        
        .navigation {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #eee;
        }
        
        .navigation .btn {
            width: auto;
            margin: 0 10px;
        }
        
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 10px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-info {
            background: #cce7ff;
            color: #004085;
            border: 1px solid #b3d7ff;
        }
        
        @media (max-width: 768px) {
            .main-content {
                grid-template-columns: 1fr;
            }
            
            .stats-overview {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 Gestor de Metadatos de Imágenes</h1>
            <p>Sistema para rastrear fuente, licencia y metadatos de cada imagen de especies</p>
        </div>
        
        <div class="stats-overview">
            <div class="stat-card">
                <h3>Total de Imágenes</h3>
                <div class="number"><?php echo number_format($metadata_stats['stats']['total_images']); ?></div>
            </div>
            <div class="stat-card">
                <h3>Con Metadatos</h3>
                <div class="number"><?php echo number_format($metadata_stats['stats']['images_with_metadata']); ?></div>
                <div class="percentage"><?php echo $metadata_stats['stats']['metadata_coverage_percentage']; ?>% cobertura</div>
            </div>
            <div class="stat-card">
                <h3>Sin Metadatos</h3>
                <div class="number"><?php echo number_format($metadata_stats['stats']['images_missing_metadata']); ?></div>
            </div>
            <div class="stat-card">
                <h3>Con Atribución</h3>
                <div class="number"><?php echo number_format($metadata_stats['stats']['attribution_coverage']['with_attribution']); ?></div>
                <div class="percentage"><?php echo $metadata_stats['stats']['attribution_coverage']['attribution_percentage']; ?>%</div>
            </div>
        </div>
        
        <div class="main-content">
            <div class="section">
                <h2>📄 Distribución de Licencias</h2>
                <div class="distribution-chart">
                    <?php foreach ($metadata_stats['stats']['license_distribution'] as $license): ?>
                    <div class="chart-item">
                        <span class="chart-label"><?php echo htmlspecialchars($license['license_name']); ?></span>
                        <span class="chart-value"><?php echo number_format($license['count']); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="license-info">
                    <h4>Tipos de Licencias:</h4>
                    <?php foreach ($metadata_stats['license_types'] as $code => $info): ?>
                    <span class="license-badge <?php echo $info['commercial_use'] ? 'commercial' : 'non-commercial'; ?>">
                        <?php echo $code; ?>
                    </span>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="section">
                <h2>🌐 Distribución de Fuentes</h2>
                <div class="distribution-chart">
                    <?php foreach ($metadata_stats['stats']['source_distribution'] as $source): ?>
                    <div class="chart-item">
                        <span class="chart-label"><?php echo htmlspecialchars($source['source_name']); ?></span>
                        <span class="chart-value"><?php echo number_format($source['count']); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <div class="actions-panel">
            <h2>🔧 Acciones de Gestión</h2>
            <div class="actions-grid">
                <div class="action-card">
                    <h3>🔍 Escanear Metadatos Faltantes</h3>
                    <p>Buscar imágenes sin metadatos y extraer información automáticamente de las fuentes.</p>
                    <button class="btn" onclick="scanMissingMetadata()">Iniciar Escaneo</button>
                </div>
                
                <div class="action-card">
                    <h3>📝 Actualización Masiva</h3>
                    <p>Actualizar metadatos de todas las imágenes de una fuente específica.</p>
                    <select id="bulk-source" style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #ddd;">
                        <option value="">Seleccionar fuente...</option>
                        <?php foreach ($metadata_stats['image_sources'] as $code => $info): ?>
                        <option value="<?php echo $code; ?>"><?php echo htmlspecialchars($info['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn btn-secondary" onclick="bulkUpdateMetadata()">Actualizar Fuente</button>
                </div>
                
                <div class="action-card">
                    <h3>📊 Generar Reporte</h3>
                    <p>Crear un reporte completo de metadatos y licencias para exportar.</p>
                    <button class="btn btn-success" onclick="generateReport()">Generar Reporte</button>
                </div>
                
                <div class="action-card">
                    <h3>🔄 Actualizar Estadísticas</h3>
                    <p>Recalcular todas las estadísticas de metadatos y cobertura.</p>
                    <button class="btn" onclick="refreshStats()">Actualizar</button>
                </div>
            </div>
        </div>
        
        <div class="progress-container" id="progress-container">
            <h3>Progreso de Procesamiento</h3>
            <div class="progress-bar">
                <div class="progress-fill" id="progress-fill"></div>
            </div>
            <div id="progress-text">Preparando...</div>
            
            <div id="results-container" style="margin-top: 20px;"></div>
        </div>
        
        <div class="navigation">
            <a href="index.php" class="btn">🏠 Volver al Inicio</a>
            <a href="international_downloader_interface.php" class="btn">📥 Descargador Internacional</a>
            <a href="regional_species_manager.php" class="btn">🌍 Gestor Regional</a>
            <a href="image_quality_validator.php" class="btn">✅ Validador de Calidad</a>
        </div>
    </div>
    
    <script>
        function scanMissingMetadata() {
            showProgress('Escaneando imágenes sin metadatos...');
            
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=scan_missing_metadata'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateProgress(100, `Escaneo completado: ${data.processed} imágenes procesadas de ${data.total_found} encontradas`);
                    displayResults(data.results);
                    showAlert(`Metadatos extraídos para ${data.processed} imágenes`, 'success');
                } else {
                    showAlert('Error: ' + (data.error || 'Error desconocido'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error de conexión: ' + error.message, 'error');
            })
            .finally(() => {
                setTimeout(refreshStats, 2000);
            });
        }
        
        function bulkUpdateMetadata() {
            const source = document.getElementById('bulk-source').value;
            
            if (!source) {
                alert('Por favor selecciona una fuente.');
                return;
            }
            
            showProgress(`Actualizando metadatos de ${source}...`);
            
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=bulk_update_metadata&source=${source}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateProgress(100, `Actualización completada para ${source}`);
                    showAlert(`Metadatos actualizados para fuente ${source}`, 'success');
                } else {
                    showAlert('Error: ' + (data.error || 'Error desconocido'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error de conexión: ' + error.message, 'error');
            })
            .finally(() => {
                setTimeout(refreshStats, 2000);
            });
        }
        
        function generateReport() {
            showProgress('Generando reporte de metadatos...');
            
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=export_metadata_report'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateProgress(100, 'Reporte generado exitosamente');
                    
                    // Crear enlace de descarga
                    const downloadLink = document.createElement('a');
                    downloadLink.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(data.csv_content);
                    downloadLink.download = `metadata_report_${new Date().toISOString().split('T')[0]}.csv`;
                    downloadLink.click();
                    
                    showAlert('Reporte descargado exitosamente', 'success');
                } else {
                    showAlert('Error: ' + (data.error || 'Error desconocido'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error de conexión: ' + error.message, 'error');
            });
        }
        
        function refreshStats() {
            location.reload();
        }
        
        function showProgress(message) {
            const progressContainer = document.getElementById('progress-container');
            const progressText = document.getElementById('progress-text');
            
            progressContainer.style.display = 'block';
            progressText.textContent = message;
            
            updateProgress(0);
            
            progressContainer.scrollIntoView({ behavior: 'smooth' });
        }
        
        function updateProgress(percentage, message = null) {
            const progressFill = document.getElementById('progress-fill');
            const progressText = document.getElementById('progress-text');
            
            progressFill.style.width = percentage + '%';
            
            if (message) {
                progressText.textContent = message;
            }
        }
        
        function displayResults(results) {
            const resultsContainer = document.getElementById('results-container');
            
            if (!results || results.length === 0) {
                resultsContainer.innerHTML = '<p>No se encontraron resultados.</p>';
                return;
            }
            
            let html = '<h4>Resultados del Procesamiento:</h4><div style="max-height: 300px; overflow-y: auto;">';
            
            results.forEach(result => {
                html += `
                    <div style="background: #f8f9fa; padding: 10px; margin-bottom: 10px; border-radius: 5px; border-left: 3px solid #667eea;">
                        <strong>${result.scientific_name}</strong><br>
                        <small>Fuente: ${result.metadata.source} | Licencia: ${result.metadata.license_type}</small>
                    </div>
                `;
            });
            
            html += '</div>';
            resultsContainer.innerHTML = html;
        }
        
        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.textContent = message;
            
            const container = document.querySelector('.container');
            container.insertBefore(alertDiv, container.firstChild);
            
            // Remover después de 5 segundos
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }
    </script>
</body>
</html>