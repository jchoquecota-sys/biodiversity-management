<?php
/**
 * Gestor de Especies Regionales
 * Sistema para expandir la base de datos con especies de diferentes regiones geográficas
 */

require_once 'config/database.php';
require_once 'config/api_config.php';

class RegionalSpeciesManager {
    private $pdo;
    private $apiConfig;
    private $logFile;
    
    // Regiones objetivo con sus códigos y características
    private $target_regions = [
        'north_america' => [
            'name' => 'América del Norte',
            'countries' => ['US', 'CA', 'MX'],
            'gbif_country_codes' => ['US', 'CA', 'MX'],
            'priority_taxa' => ['Aves', 'Mammalia', 'Reptilia', 'Amphibia'],
            'ecosystems' => ['temperate_forest', 'boreal_forest', 'prairie', 'desert']
        ],
        'europe' => [
            'name' => 'Europa',
            'countries' => ['GB', 'FR', 'DE', 'IT', 'ES', 'NL', 'SE', 'NO'],
            'gbif_country_codes' => ['GB', 'FR', 'DE', 'IT', 'ES', 'NL', 'SE', 'NO'],
            'priority_taxa' => ['Aves', 'Mammalia', 'Plantae', 'Insecta'],
            'ecosystems' => ['temperate_forest', 'mediterranean', 'alpine', 'tundra']
        ],
        'asia_pacific' => [
            'name' => 'Asia-Pacífico',
            'countries' => ['AU', 'NZ', 'JP', 'CN', 'IN', 'TH', 'ID'],
            'gbif_country_codes' => ['AU', 'NZ', 'JP', 'CN', 'IN', 'TH', 'ID'],
            'priority_taxa' => ['Aves', 'Reptilia', 'Plantae', 'Actinopterygii'],
            'ecosystems' => ['tropical_rainforest', 'coral_reef', 'temperate_rainforest', 'savanna']
        ],
        'africa' => [
            'name' => 'África',
            'countries' => ['ZA', 'KE', 'TZ', 'BW', 'NA', 'ZW'],
            'gbif_country_codes' => ['ZA', 'KE', 'TZ', 'BW', 'NA', 'ZW'],
            'priority_taxa' => ['Mammalia', 'Aves', 'Reptilia', 'Plantae'],
            'ecosystems' => ['savanna', 'desert', 'tropical_forest', 'fynbos']
        ],
        'south_america' => [
            'name' => 'Sudamérica',
            'countries' => ['BR', 'AR', 'PE', 'CO', 'VE', 'CL', 'EC', 'BO'],
            'gbif_country_codes' => ['BR', 'AR', 'PE', 'CO', 'VE', 'CL', 'EC', 'BO'],
            'priority_taxa' => ['Aves', 'Plantae', 'Amphibia', 'Insecta'],
            'ecosystems' => ['amazon_rainforest', 'atlantic_forest', 'cerrado', 'pampas', 'patagonia']
        ]
    ];
    
    public function __construct() {
        $database = new Database();
        $this->pdo = $database->getConnection();
        $this->apiConfig = new APIConfig();
        
        $this->logFile = __DIR__ . '/logs/regional_expansion.log';
        
        if (!file_exists(dirname($this->logFile))) {
            mkdir(dirname($this->logFile), 0755, true);
        }
        
        $this->createRegionalTables();
        $this->log("=== Iniciando gestor de especies regionales ===");
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
            case 'get_regional_stats':
                return $this->jsonResponse($this->getRegionalStats());
                
            case 'start_regional_expansion':
                $region = $_POST['region'] ?? '';
                $taxa = $_POST['taxa'] ?? [];
                $limit = intval($_POST['limit'] ?? 100);
                return $this->jsonResponse($this->startRegionalExpansion($region, $taxa, $limit));
                
            case 'get_expansion_progress':
                return $this->jsonResponse($this->getExpansionProgress());
                
            case 'get_regional_species':
                $region = $_POST['region'] ?? '';
                return $this->jsonResponse($this->getRegionalSpecies($region));
                
            case 'validate_new_species':
                $species_data = $_POST['species_data'] ?? [];
                return $this->jsonResponse($this->validateNewSpecies($species_data));
                
            default:
                return $this->jsonResponse(['error' => 'Acción no válida']);
        }
    }
    
    /**
     * Obtener estadísticas regionales
     */
    public function getRegionalStats() {
        $stats = [];
        
        foreach ($this->target_regions as $region_code => $region_info) {
            $stats[$region_code] = [
                'name' => $region_info['name'],
                'species_count' => $this->getRegionalSpeciesCount($region_code),
                'images_count' => $this->getRegionalImagesCount($region_code),
                'last_update' => $this->getLastRegionalUpdate($region_code),
                'priority_taxa' => $region_info['priority_taxa'],
                'countries' => $region_info['countries']
            ];
        }
        
        return [
            'success' => true,
            'regional_stats' => $stats,
            'total_species' => $this->getTotalSpeciesCount(),
            'total_regions' => count($this->target_regions)
        ];
    }
    
    /**
     * Iniciar expansión regional
     */
    public function startRegionalExpansion($region, $taxa, $limit) {
        if (!isset($this->target_regions[$region])) {
            return ['error' => 'Región no válida'];
        }
        
        $region_info = $this->target_regions[$region];
        $this->log("Iniciando expansión para región: {$region_info['name']}");
        
        $results = [
            'region' => $region,
            'region_name' => $region_info['name'],
            'species_added' => 0,
            'images_downloaded' => 0,
            'errors' => [],
            'processing_log' => []
        ];
        
        foreach ($taxa as $taxon) {
            if (!in_array($taxon, $region_info['priority_taxa'])) {
                continue;
            }
            
            $this->log("Procesando taxón: {$taxon} para región: {$region}");
            $results['processing_log'][] = "Procesando {$taxon}...";
            
            $taxon_results = $this->processRegionalTaxon($region, $taxon, $limit);
            
            $results['species_added'] += $taxon_results['species_added'];
            $results['images_downloaded'] += $taxon_results['images_downloaded'];
            $results['errors'] = array_merge($results['errors'], $taxon_results['errors']);
            $results['processing_log'] = array_merge($results['processing_log'], $taxon_results['log']);
        }
        
        $this->updateRegionalExpansionLog($region, $results);
        
        return [
            'success' => true,
            'results' => $results
        ];
    }
    
    /**
     * Procesar taxón regional
     */
    private function processRegionalTaxon($region, $taxon, $limit) {
        $region_info = $this->target_regions[$region];
        $results = [
            'species_added' => 0,
            'images_downloaded' => 0,
            'errors' => [],
            'log' => []
        ];
        
        // Buscar especies en GBIF para la región
        foreach ($region_info['gbif_country_codes'] as $country_code) {
            $species_data = $this->searchGBIFSpeciesByRegion($taxon, $country_code, $limit);
            
            if (empty($species_data)) {
                $results['log'][] = "No se encontraron especies de {$taxon} en {$country_code}";
                continue;
            }
            
            $results['log'][] = "Encontradas " . count($species_data) . " especies de {$taxon} en {$country_code}";
            
            foreach ($species_data as $species) {
                try {
                    if ($this->addRegionalSpecies($species, $region, $country_code)) {
                        $results['species_added']++;
                        
                        // Intentar descargar imagen
                        if ($this->downloadSpeciesImage($species)) {
                            $results['images_downloaded']++;
                        }
                    }
                } catch (Exception $e) {
                    $results['errors'][] = "Error procesando {$species['scientific_name']}: " . $e->getMessage();
                }
            }
        }
        
        return $results;
    }
    
    /**
     * Buscar especies en GBIF por región
     */
    private function searchGBIFSpeciesByRegion($taxon, $country_code, $limit) {
        $gbif_api_url = "https://api.gbif.org/v1/species/search";
        
        $params = [
            'q' => $taxon,
            'rank' => 'SPECIES',
            'status' => 'ACCEPTED',
            'limit' => min($limit, 100),
            'offset' => 0
        ];
        
        $url = $gbif_api_url . '?' . http_build_query($params);
        
        $response = $this->makeAPIRequest($url);
        
        if (!$response || !isset($response['results'])) {
            return [];
        }
        
        $species_list = [];
        
        foreach ($response['results'] as $species) {
            if (!isset($species['scientificName']) || !isset($species['key'])) {
                continue;
            }
            
            // Verificar si ya existe en nuestra base de datos
            if ($this->speciesExists($species['scientificName'])) {
                continue;
            }
            
            $species_data = [
                'gbif_id' => $species['key'],
                'scientific_name' => $species['scientificName'],
                'common_name' => $species['vernacularName'] ?? '',
                'kingdom' => $species['kingdom'] ?? '',
                'phylum' => $species['phylum'] ?? '',
                'class' => $species['class'] ?? '',
                'order' => $species['order'] ?? '',
                'family' => $species['family'] ?? '',
                'genus' => $species['genus'] ?? '',
                'species' => $species['species'] ?? '',
                'taxonomic_status' => $species['taxonomicStatus'] ?? 'ACCEPTED',
                'country_code' => $country_code,
                'source' => 'GBIF'
            ];
            
            $species_list[] = $species_data;
        }
        
        return $species_list;
    }
    
    /**
     * Agregar especie regional
     */
    private function addRegionalSpecies($species_data, $region, $country_code) {
        try {
            // Insertar en tabla principal
            $sql = "INSERT INTO biodiversity_categories 
                    (nombre_cientifico, nombre_comun, reino, filo, clase, orden, familia, genero, especie, 
                     estado_conservacion, habitat, distribucion, descripcion, fecha_creacion, fuente_datos, 
                     gbif_id, pais_origen, region_geografica) 
                    VALUES 
                    (:scientific_name, :common_name, :kingdom, :phylum, :class, :order, :family, :genus, :species,
                     'No Evaluado', :habitat, :distribution, :description, NOW(), :source,
                     :gbif_id, :country_code, :region)";
            
            $stmt = $this->pdo->prepare($sql);
            
            $habitat = $this->generateHabitatDescription($species_data, $region);
            $distribution = $this->generateDistributionDescription($species_data, $country_code);
            $description = $this->generateSpeciesDescription($species_data);
            
            $stmt->execute([
                ':scientific_name' => $species_data['scientific_name'],
                ':common_name' => $species_data['common_name'],
                ':kingdom' => $species_data['kingdom'],
                ':phylum' => $species_data['phylum'],
                ':class' => $species_data['class'],
                ':order' => $species_data['order'],
                ':family' => $species_data['family'],
                ':genus' => $species_data['genus'],
                ':species' => $species_data['species'],
                ':habitat' => $habitat,
                ':distribution' => $distribution,
                ':description' => $description,
                ':source' => $species_data['source'],
                ':gbif_id' => $species_data['gbif_id'],
                ':country_code' => $country_code,
                ':region' => $region
            ]);
            
            $species_id = $this->pdo->lastInsertId();
            
            // Registrar en tabla de especies regionales
            $this->recordRegionalSpecies($species_id, $region, $country_code, $species_data);
            
            $this->log("Especie agregada: {$species_data['scientific_name']} (ID: {$species_id})");
            
            return true;
            
        } catch (Exception $e) {
            $this->log("Error agregando especie {$species_data['scientific_name']}: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Verificar si especie existe
     */
    private function speciesExists($scientific_name) {
        $sql = "SELECT id FROM biodiversity_categories WHERE nombre_cientifico = :scientific_name LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':scientific_name' => $scientific_name]);
        
        return $stmt->fetch() !== false;
    }
    
    /**
     * Descargar imagen de especie
     */
    private function downloadSpeciesImage($species_data) {
        // Buscar imagen en iNaturalist
        $image_url = $this->searchINaturalistImage($species_data['scientific_name']);
        
        if (!$image_url) {
            // Buscar en Wikimedia Commons
            $image_url = $this->searchWikimediaImage($species_data['scientific_name']);
        }
        
        if (!$image_url) {
            return false;
        }
        
        // Descargar y guardar imagen
        return $this->downloadAndSaveImage($image_url, $species_data['scientific_name']);
    }
    
    /**
     * Buscar imagen en iNaturalist
     */
    private function searchINaturalistImage($scientific_name) {
        $api_url = "https://api.inaturalist.org/v1/taxa";
        $params = [
            'q' => $scientific_name,
            'rank' => 'species',
            'per_page' => 1
        ];
        
        $url = $api_url . '?' . http_build_query($params);
        $response = $this->makeAPIRequest($url);
        
        if (!$response || !isset($response['results'][0]['default_photo']['medium_url'])) {
            return null;
        }
        
        return $response['results'][0]['default_photo']['medium_url'];
    }
    
    /**
     * Buscar imagen en Wikimedia Commons
     */
    private function searchWikimediaImage($scientific_name) {
        $api_url = "https://commons.wikimedia.org/w/api.php";
        $params = [
            'action' => 'query',
            'format' => 'json',
            'list' => 'search',
            'srsearch' => $scientific_name,
            'srnamespace' => 6, // File namespace
            'srlimit' => 1
        ];
        
        $url = $api_url . '?' . http_build_query($params);
        $response = $this->makeAPIRequest($url);
        
        if (!$response || !isset($response['query']['search'][0]['title'])) {
            return null;
        }
        
        $file_title = $response['query']['search'][0]['title'];
        
        // Obtener URL de la imagen
        $image_params = [
            'action' => 'query',
            'format' => 'json',
            'titles' => $file_title,
            'prop' => 'imageinfo',
            'iiprop' => 'url',
            'iiurlwidth' => 500
        ];
        
        $image_url = $api_url . '?' . http_build_query($image_params);
        $image_response = $this->makeAPIRequest($image_url);
        
        if (!$image_response || !isset($image_response['query']['pages'])) {
            return null;
        }
        
        $page = reset($image_response['query']['pages']);
        
        return $page['imageinfo'][0]['thumburl'] ?? null;
    }
    
    /**
     * Descargar y guardar imagen
     */
    private function downloadAndSaveImage($image_url, $scientific_name) {
        try {
            $image_data = file_get_contents($image_url);
            
            if (!$image_data) {
                return false;
            }
            
            $filename = $this->generateImageFilename($scientific_name);
            $filepath = __DIR__ . '/public/images/species/' . $filename;
            
            // Crear directorio si no existe
            if (!file_exists(dirname($filepath))) {
                mkdir(dirname($filepath), 0755, true);
            }
            
            file_put_contents($filepath, $image_data);
            
            // Actualizar base de datos con la URL de la imagen
            $this->updateSpeciesImage($scientific_name, 'images/species/' . $filename);
            
            return true;
            
        } catch (Exception $e) {
            $this->log("Error descargando imagen para {$scientific_name}: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Generar nombre de archivo para imagen
     */
    private function generateImageFilename($scientific_name) {
        $clean_name = preg_replace('/[^a-zA-Z0-9_-]/', '_', $scientific_name);
        $clean_name = strtolower($clean_name);
        $timestamp = time();
        
        return "{$clean_name}_{$timestamp}.jpg";
    }
    
    /**
     * Actualizar imagen de especie
     */
    private function updateSpeciesImage($scientific_name, $image_path) {
        $sql = "UPDATE biodiversity_categories SET imagen_url = :image_path WHERE nombre_cientifico = :scientific_name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':image_path' => $image_path,
            ':scientific_name' => $scientific_name
        ]);
    }
    
    /**
     * Generar descripción de hábitat
     */
    private function generateHabitatDescription($species_data, $region) {
        $region_info = $this->target_regions[$region];
        $ecosystems = implode(', ', $region_info['ecosystems']);
        
        return "Especie nativa de {$region_info['name']}. Habita en ecosistemas como: {$ecosystems}.";
    }
    
    /**
     * Generar descripción de distribución
     */
    private function generateDistributionDescription($species_data, $country_code) {
        $country_names = [
            'US' => 'Estados Unidos',
            'CA' => 'Canadá',
            'MX' => 'México',
            'GB' => 'Reino Unido',
            'FR' => 'Francia',
            'DE' => 'Alemania',
            'AU' => 'Australia',
            'BR' => 'Brasil',
            'AR' => 'Argentina'
        ];
        
        $country_name = $country_names[$country_code] ?? $country_code;
        
        return "Distribuida en {$country_name} y regiones adyacentes.";
    }
    
    /**
     * Generar descripción de especie
     */
    private function generateSpeciesDescription($species_data) {
        $class = $species_data['class'];
        $family = $species_data['family'];
        
        return "Especie de la clase {$class}, perteneciente a la familia {$family}. Datos obtenidos de {$species_data['source']}.";
    }
    
    /**
     * Registrar especie regional
     */
    private function recordRegionalSpecies($species_id, $region, $country_code, $species_data) {
        $sql = "INSERT INTO regional_species 
                (species_id, region_code, country_code, gbif_id, taxonomic_class, 
                 discovery_date, data_source, confidence_score) 
                VALUES 
                (:species_id, :region_code, :country_code, :gbif_id, :taxonomic_class,
                 NOW(), :data_source, :confidence_score)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':species_id' => $species_id,
            ':region_code' => $region,
            ':country_code' => $country_code,
            ':gbif_id' => $species_data['gbif_id'],
            ':taxonomic_class' => $species_data['class'],
            ':data_source' => $species_data['source'],
            ':confidence_score' => 85 // Puntuación por defecto para datos de GBIF
        ]);
    }
    
    /**
     * Obtener conteo de especies regionales
     */
    private function getRegionalSpeciesCount($region) {
        $sql = "SELECT COUNT(*) FROM regional_species WHERE region_code = :region";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':region' => $region]);
        
        return $stmt->fetchColumn();
    }
    
    /**
     * Obtener conteo de imágenes regionales
     */
    private function getRegionalImagesCount($region) {
        $sql = "SELECT COUNT(*) FROM biodiversity_categories bc 
                JOIN regional_species rs ON bc.id = rs.species_id 
                WHERE rs.region_code = :region AND bc.imagen_url IS NOT NULL";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':region' => $region]);
        
        return $stmt->fetchColumn();
    }
    
    /**
     * Obtener última actualización regional
     */
    private function getLastRegionalUpdate($region) {
        $sql = "SELECT MAX(discovery_date) FROM regional_species WHERE region_code = :region";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':region' => $region]);
        
        $date = $stmt->fetchColumn();
        return $date ? date('Y-m-d H:i:s', strtotime($date)) : null;
    }
    
    /**
     * Obtener conteo total de especies
     */
    private function getTotalSpeciesCount() {
        $sql = "SELECT COUNT(*) FROM biodiversity_categories";
        return $this->pdo->query($sql)->fetchColumn();
    }
    
    /**
     * Crear tablas regionales
     */
    private function createRegionalTables() {
        // Tabla de especies regionales
        $sql = "CREATE TABLE IF NOT EXISTS regional_species (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    species_id INT NOT NULL,
                    region_code VARCHAR(50) NOT NULL,
                    country_code VARCHAR(10) NOT NULL,
                    gbif_id BIGINT,
                    taxonomic_class VARCHAR(100),
                    discovery_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    data_source VARCHAR(50) DEFAULT 'GBIF',
                    confidence_score INT DEFAULT 80,
                    notes TEXT,
                    INDEX idx_region (region_code),
                    INDEX idx_country (country_code),
                    INDEX idx_class (taxonomic_class),
                    FOREIGN KEY (species_id) REFERENCES biodiversity_categories(id) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $this->pdo->exec($sql);
        
        // Tabla de log de expansión regional
        $sql = "CREATE TABLE IF NOT EXISTS regional_expansion_log (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    region_code VARCHAR(50) NOT NULL,
                    expansion_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    species_added INT DEFAULT 0,
                    images_downloaded INT DEFAULT 0,
                    taxa_processed JSON,
                    processing_time_seconds INT,
                    success_rate DECIMAL(5,2),
                    notes TEXT
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $this->pdo->exec($sql);
        
        // Agregar columnas a tabla principal si no existen
        $this->addRegionalColumns();
    }
    
    /**
     * Agregar columnas regionales a tabla principal
     */
    private function addRegionalColumns() {
        $columns_to_add = [
            'gbif_id' => 'BIGINT NULL',
            'pais_origen' => 'VARCHAR(10) NULL',
            'region_geografica' => 'VARCHAR(50) NULL',
            'fuente_datos' => 'VARCHAR(50) DEFAULT "Manual"'
        ];
        
        foreach ($columns_to_add as $column => $definition) {
            try {
                $sql = "ALTER TABLE biodiversity_categories ADD COLUMN {$column} {$definition}";
                $this->pdo->exec($sql);
            } catch (Exception $e) {
                // Columna ya existe, continuar
            }
        }
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
     * Actualizar log de expansión regional
     */
    private function updateRegionalExpansionLog($region, $results) {
        $sql = "INSERT INTO regional_expansion_log 
                (region_code, species_added, images_downloaded, taxa_processed, notes) 
                VALUES 
                (:region_code, :species_added, :images_downloaded, :taxa_processed, :notes)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':region_code' => $region,
            ':species_added' => $results['species_added'],
            ':images_downloaded' => $results['images_downloaded'],
            ':taxa_processed' => json_encode($results),
            ':notes' => 'Expansión automática completada'
        ]);
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
    $manager = new RegionalSpeciesManager();
    $manager->handleAjaxRequest();
    exit;
}

$manager = new RegionalSpeciesManager();
$regional_stats = $manager->getRegionalStats();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Especies Regionales - Sistema de Biodiversidad</title>
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
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
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
            font-size: 1.2em;
            margin-bottom: 10px;
            opacity: 0.9;
        }
        
        .stat-card .number {
            font-size: 2.5em;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .regions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .region-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-left: 5px solid #667eea;
            transition: all 0.3s ease;
        }
        
        .region-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        .region-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .region-name {
            font-size: 1.4em;
            font-weight: bold;
            color: #333;
        }
        
        .region-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .region-stat {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .region-stat .label {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 5px;
        }
        
        .region-stat .value {
            font-size: 1.5em;
            font-weight: bold;
            color: #667eea;
        }
        
        .taxa-list {
            margin-bottom: 20px;
        }
        
        .taxa-list h4 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .taxa-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        
        .taxa-tag {
            background: #667eea;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.9em;
        }
        
        .expansion-controls {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        
        .control-group {
            margin-bottom: 15px;
        }
        
        .control-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        
        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        input[type="number"] {
            width: 100px;
            padding: 8px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
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
        
        .log-container {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            max-height: 300px;
            overflow-y: auto;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
        }
        
        .log-entry {
            margin-bottom: 5px;
            padding: 5px;
            border-left: 3px solid #667eea;
            background: white;
            border-radius: 3px;
        }
        
        .navigation {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #eee;
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
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🌍 Gestor de Especies Regionales</h1>
            <p>Expandir la base de datos con especies de diferentes regiones geográficas del mundo</p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total de Especies</h3>
                <div class="number"><?php echo number_format($regional_stats['total_species']); ?></div>
            </div>
            <div class="stat-card">
                <h3>Regiones Activas</h3>
                <div class="number"><?php echo $regional_stats['total_regions']; ?></div>
            </div>
            <div class="stat-card">
                <h3>Especies Regionales</h3>
                <div class="number" id="total-regional-species">0</div>
            </div>
        </div>
        
        <div class="regions-grid">
            <?php foreach ($regional_stats['regional_stats'] as $region_code => $region_data): ?>
            <div class="region-card" data-region="<?php echo $region_code; ?>">
                <div class="region-header">
                    <div class="region-name"><?php echo htmlspecialchars($region_data['name']); ?></div>
                </div>
                
                <div class="region-stats">
                    <div class="region-stat">
                        <div class="label">Especies</div>
                        <div class="value"><?php echo number_format($region_data['species_count']); ?></div>
                    </div>
                    <div class="region-stat">
                        <div class="label">Imágenes</div>
                        <div class="value"><?php echo number_format($region_data['images_count']); ?></div>
                    </div>
                </div>
                
                <div class="taxa-list">
                    <h4>Taxa Prioritarios:</h4>
                    <div class="taxa-tags">
                        <?php foreach ($region_data['priority_taxa'] as $taxon): ?>
                        <span class="taxa-tag"><?php echo htmlspecialchars($taxon); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="expansion-controls">
                    <div class="control-group">
                        <label>Seleccionar Taxa:</label>
                        <div class="checkbox-group">
                            <?php foreach ($region_data['priority_taxa'] as $taxon): ?>
                            <div class="checkbox-item">
                                <input type="checkbox" id="<?php echo $region_code; ?>_<?php echo $taxon; ?>" 
                                       name="taxa[]" value="<?php echo $taxon; ?>" checked>
                                <label for="<?php echo $region_code; ?>_<?php echo $taxon; ?>"><?php echo $taxon; ?></label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <label for="limit_<?php echo $region_code; ?>">Límite de especies por taxón:</label>
                        <input type="number" id="limit_<?php echo $region_code; ?>" value="50" min="10" max="500">
                    </div>
                    
                    <button class="btn" onclick="startRegionalExpansion('<?php echo $region_code; ?>')">
                        🚀 Iniciar Expansión
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="progress-container" id="progress-container">
            <h3>Progreso de Expansión</h3>
            <div class="progress-bar">
                <div class="progress-fill" id="progress-fill"></div>
            </div>
            <div id="progress-text">Preparando...</div>
            
            <div class="log-container" id="log-container">
                <div class="log-entry">Sistema listo para expansión regional...</div>
            </div>
        </div>
        
        <div class="navigation">
            <a href="index.php" class="btn">🏠 Volver al Inicio</a>
            <a href="international_downloader_interface.php" class="btn">📥 Descargador Internacional</a>
            <a href="image_quality_validator.php" class="btn">✅ Validador de Calidad</a>
        </div>
    </div>
    
    <script>
        let currentExpansion = null;
        
        // Actualizar estadísticas al cargar
        updateRegionalStats();
        
        function updateRegionalStats() {
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=get_regional_stats'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let totalRegionalSpecies = 0;
                    Object.values(data.regional_stats).forEach(region => {
                        totalRegionalSpecies += region.species_count;
                    });
                    
                    document.getElementById('total-regional-species').textContent = 
                        totalRegionalSpecies.toLocaleString();
                }
            })
            .catch(error => {
                console.error('Error updating stats:', error);
            });
        }
        
        function startRegionalExpansion(region) {
            const regionCard = document.querySelector(`[data-region="${region}"]`);
            const checkboxes = regionCard.querySelectorAll('input[name="taxa[]"]');
            const limitInput = regionCard.querySelector(`#limit_${region}`);
            
            const selectedTaxa = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);
            
            if (selectedTaxa.length === 0) {
                alert('Por favor selecciona al menos un taxón.');
                return;
            }
            
            const limit = parseInt(limitInput.value) || 50;
            
            // Mostrar contenedor de progreso
            const progressContainer = document.getElementById('progress-container');
            progressContainer.style.display = 'block';
            progressContainer.scrollIntoView({ behavior: 'smooth' });
            
            // Deshabilitar botón
            const button = regionCard.querySelector('.btn');
            button.disabled = true;
            button.innerHTML = '<div class="loading"></div>Procesando...';
            
            // Limpiar log
            const logContainer = document.getElementById('log-container');
            logContainer.innerHTML = '<div class="log-entry">Iniciando expansión regional...</div>';
            
            // Iniciar expansión
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=start_regional_expansion&region=${region}&taxa=${selectedTaxa.join(',')}&limit=${limit}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayExpansionResults(data.results);
                } else {
                    showAlert('Error: ' + (data.error || 'Error desconocido'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error de conexión: ' + error.message, 'error');
            })
            .finally(() => {
                // Rehabilitar botón
                button.disabled = false;
                button.innerHTML = '🚀 Iniciar Expansión';
                
                // Actualizar estadísticas
                setTimeout(updateRegionalStats, 2000);
            });
        }
        
        function displayExpansionResults(results) {
            const progressFill = document.getElementById('progress-fill');
            const progressText = document.getElementById('progress-text');
            const logContainer = document.getElementById('log-container');
            
            // Actualizar progreso
            progressFill.style.width = '100%';
            progressText.innerHTML = `
                Expansión completada para ${results.region_name}:<br>
                ✅ ${results.species_added} especies agregadas<br>
                📸 ${results.images_downloaded} imágenes descargadas
            `;
            
            // Mostrar log detallado
            logContainer.innerHTML = '';
            results.processing_log.forEach(entry => {
                const logEntry = document.createElement('div');
                logEntry.className = 'log-entry';
                logEntry.textContent = entry;
                logContainer.appendChild(logEntry);
            });
            
            // Mostrar errores si los hay
            if (results.errors.length > 0) {
                const errorEntry = document.createElement('div');
                errorEntry.className = 'log-entry';
                errorEntry.style.borderLeftColor = '#dc3545';
                errorEntry.innerHTML = `<strong>Errores encontrados:</strong><br>${results.errors.join('<br>')}`;
                logContainer.appendChild(errorEntry);
            }
            
            // Scroll al final del log
            logContainer.scrollTop = logContainer.scrollHeight;
            
            // Mostrar alerta de éxito
            showAlert(`Expansión completada: ${results.species_added} especies agregadas, ${results.images_downloaded} imágenes descargadas.`, 'success');
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
        
        // Actualizar estadísticas cada 30 segundos
        setInterval(updateRegionalStats, 30000);
    </script>
</body>
</html>