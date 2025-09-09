<?php
/**
 * Expansor de Base de Datos de Especies
 * Agrega especies de diferentes regiones geográficas usando APIs internacionales
 */

require_once 'config/database.php';
require_once 'config/api_config.php';

class SpeciesDatabaseExpander {
    private $pdo;
    private $logFile;
    private $expansionStats;
    
    // Regiones geográficas objetivo
    private $target_regions = [
        'north_america' => [
            'name' => 'América del Norte',
            'countries' => ['US', 'CA', 'MX'],
            'bbox' => [-168.0, 15.0, -52.0, 72.0] // [west, south, east, north]
        ],
        'europe' => [
            'name' => 'Europa',
            'countries' => ['GB', 'FR', 'DE', 'ES', 'IT', 'NL', 'SE', 'NO'],
            'bbox' => [-25.0, 35.0, 45.0, 72.0]
        ],
        'asia' => [
            'name' => 'Asia',
            'countries' => ['CN', 'JP', 'IN', 'TH', 'MY', 'ID'],
            'bbox' => [60.0, -10.0, 180.0, 55.0]
        ],
        'oceania' => [
            'name' => 'Oceanía',
            'countries' => ['AU', 'NZ', 'PG'],
            'bbox' => [110.0, -50.0, 180.0, -10.0]
        ],
        'africa' => [
            'name' => 'África',
            'countries' => ['ZA', 'KE', 'TZ', 'GH', 'NG', 'EG'],
            'bbox' => [-20.0, -35.0, 55.0, 38.0]
        ],
        'south_america' => [
            'name' => 'América del Sur',
            'countries' => ['BR', 'AR', 'CL', 'PE', 'CO', 'VE', 'EC'],
            'bbox' => [-82.0, -56.0, -34.0, 13.0]
        ]
    ];
    
    // Grupos taxonómicos prioritarios
    private $priority_taxa = [
        'birds' => ['Aves', 'class'],
        'mammals' => ['Mammalia', 'class'],
        'reptiles' => ['Reptilia', 'class'],
        'amphibians' => ['Amphibia', 'class'],
        'fish' => ['Actinopterygii', 'class'],
        'plants' => ['Plantae', 'kingdom'],
        'insects' => ['Insecta', 'class'],
        'fungi' => ['Fungi', 'kingdom']
    ];
    
    public function __construct() {
        $database = new Database();
        $this->pdo = $database->getConnection();
        
        $this->logFile = __DIR__ . '/logs/species_expansion.log';
        
        if (!file_exists(dirname($this->logFile))) {
            mkdir(dirname($this->logFile), 0755, true);
        }
        
        $this->expansionStats = [
            'regions_processed' => 0,
            'species_added' => 0,
            'duplicates_skipped' => 0,
            'errors' => []
        ];
        
        $this->log("=== Iniciando expansión de base de datos de especies ===");
    }
    
    /**
     * Expandir base de datos por región
     */
    public function expandByRegion($region_key, $species_per_taxon = 50) {
        if (!isset($this->target_regions[$region_key])) {
            throw new Exception("Región '{$region_key}' no está definida");
        }
        
        $region = $this->target_regions[$region_key];
        $this->log("Procesando región: {$region['name']}");
        
        foreach ($this->priority_taxa as $taxon_key => $taxon_info) {
            $this->log("Buscando {$taxon_info[0]} en {$region['name']}");
            
            try {
                $species = $this->searchSpeciesByTaxonAndRegion(
                    $taxon_info[0], 
                    $taxon_info[1], 
                    $region, 
                    $species_per_taxon
                );
                
                $added = $this->addSpeciesToDatabase($species, $region_key, $taxon_key);
                $this->log("Agregadas {$added} especies de {$taxon_info[0]}");
                
            } catch (Exception $e) {
                $this->log("Error procesando {$taxon_info[0]}: " . $e->getMessage());
                $this->expansionStats['errors'][] = [
                    'region' => $region_key,
                    'taxon' => $taxon_key,
                    'error' => $e->getMessage()
                ];
            }
            
            // Pausa para respetar rate limits
            sleep(2);
        }
        
        $this->expansionStats['regions_processed']++;
    }
    
    /**
     * Expandir todas las regiones
     */
    public function expandAllRegions($species_per_taxon = 30) {
        foreach ($this->target_regions as $region_key => $region_data) {
            $this->expandByRegion($region_key, $species_per_taxon);
        }
        
        $this->generateExpansionReport();
    }
    
    /**
     * Buscar especies por taxón y región usando GBIF
     */
    private function searchSpeciesByTaxonAndRegion($taxon_name, $taxon_rank, $region, $limit) {
        $species_list = [];
        
        // Buscar usando GBIF API
        $gbif_species = $this->searchGBIFByTaxonAndRegion($taxon_name, $taxon_rank, $region, $limit);
        $species_list = array_merge($species_list, $gbif_species);
        
        // Buscar usando iNaturalist API
        $inaturalist_species = $this->searchINaturalistByTaxonAndRegion($taxon_name, $region, $limit / 2);
        $species_list = array_merge($species_list, $inaturalist_species);
        
        // Eliminar duplicados por nombre científico
        $unique_species = [];
        $seen_names = [];
        
        foreach ($species_list as $species) {
            $scientific_name = $species['scientific_name'];
            if (!in_array($scientific_name, $seen_names)) {
                $unique_species[] = $species;
                $seen_names[] = $scientific_name;
            }
        }
        
        return array_slice($unique_species, 0, $limit);
    }
    
    /**
     * Buscar en GBIF por taxón y región
     */
    private function searchGBIFByTaxonAndRegion($taxon_name, $taxon_rank, $region, $limit) {
        $species_list = [];
        
        try {
            // Primero obtener el taxon key
            $taxon_url = "https://api.gbif.org/v1/species/match?name=" . urlencode($taxon_name);
            $taxon_response = APIUtils::makeRequest($taxon_url);
            $taxon_data = APIUtils::validateJSONResponse($taxon_response);
            
            if (!isset($taxon_data['usageKey'])) {
                return $species_list;
            }
            
            $taxon_key = $taxon_data['usageKey'];
            
            // Buscar especies en la región
            $bbox = implode(',', $region['bbox']);
            $search_url = "https://api.gbif.org/v1/species/search?" . http_build_query([
                'highertaxon_key' => $taxon_key,
                'status' => 'ACCEPTED',
                'rank' => 'SPECIES',
                'limit' => $limit
            ]);
            
            $response = APIUtils::makeRequest($search_url);
            $data = APIUtils::validateJSONResponse($response);
            
            if (isset($data['results'])) {
                foreach ($data['results'] as $species) {
                    if (isset($species['scientificName']) && isset($species['canonicalName'])) {
                        $species_list[] = [
                            'scientific_name' => $species['canonicalName'],
                            'common_name' => $species['vernacularName'] ?? '',
                            'taxonomic_category' => $this->mapGBIFRank($species['rank'] ?? 'SPECIES'),
                            'kingdom' => $species['kingdom'] ?? '',
                            'phylum' => $species['phylum'] ?? '',
                            'class' => $species['class'] ?? '',
                            'order' => $species['order'] ?? '',
                            'family' => $species['family'] ?? '',
                            'genus' => $species['genus'] ?? '',
                            'source' => 'GBIF',
                            'source_id' => $species['key'] ?? '',
                            'habitat' => $this->extractHabitat($species),
                            'conservation_status' => $this->extractConservationStatus($species)
                        ];
                    }
                }
            }
            
        } catch (Exception $e) {
            $this->log("Error en búsqueda GBIF: " . $e->getMessage());
        }
        
        return $species_list;
    }
    
    /**
     * Buscar en iNaturalist por taxón y región
     */
    private function searchINaturalistByTaxonAndRegion($taxon_name, $region, $limit) {
        $species_list = [];
        
        try {
            // Buscar taxón en iNaturalist
            $taxon_url = "https://api.inaturalist.org/v1/taxa?q=" . urlencode($taxon_name) . "&rank=class,kingdom";
            $taxon_response = APIUtils::makeRequest($taxon_url);
            $taxon_data = APIUtils::validateJSONResponse($taxon_response);
            
            if (!isset($taxon_data['results'][0]['id'])) {
                return $species_list;
            }
            
            $taxon_id = $taxon_data['results'][0]['id'];
            
            // Buscar especies del taxón en países de la región
            $countries = implode(',', $region['countries']);
            $species_url = "https://api.inaturalist.org/v1/taxa?" . http_build_query([
                'taxon_id' => $taxon_id,
                'rank' => 'species',
                'place_id' => $countries,
                'per_page' => $limit,
                'order' => 'desc',
                'order_by' => 'observations_count'
            ]);
            
            $response = APIUtils::makeRequest($species_url);
            $data = APIUtils::validateJSONResponse($response);
            
            if (isset($data['results'])) {
                foreach ($data['results'] as $species) {
                    if (isset($species['name'])) {
                        $species_list[] = [
                            'scientific_name' => $species['name'],
                            'common_name' => $this->extractCommonName($species),
                            'taxonomic_category' => 'Especie',
                            'kingdom' => $species['ancestor_ids'][1] ?? '',
                            'phylum' => $species['ancestor_ids'][2] ?? '',
                            'class' => $species['ancestor_ids'][3] ?? '',
                            'order' => $species['ancestor_ids'][4] ?? '',
                            'family' => $species['ancestor_ids'][5] ?? '',
                            'genus' => $species['parent']['name'] ?? '',
                            'source' => 'iNaturalist',
                            'source_id' => $species['id'],
                            'habitat' => '',
                            'conservation_status' => $species['conservation_status']['status'] ?? ''
                        ];
                    }
                }
            }
            
        } catch (Exception $e) {
            $this->log("Error en búsqueda iNaturalist: " . $e->getMessage());
        }
        
        return $species_list;
    }
    
    /**
     * Agregar especies a la base de datos
     */
    private function addSpeciesToDatabase($species_list, $region_key, $taxon_key) {
        $added_count = 0;
        
        $sql = "INSERT INTO biodiversity_categories 
                (nombre_cientifico, nombre_comun, categoria_taxonomica, reino, filo, clase, orden, familia, genero, habitat, estado_conservacion, region_geografica, fuente_datos, id_fuente_externa, created_at) 
                VALUES 
                (:scientific_name, :common_name, :taxonomic_category, :kingdom, :phylum, :class, :order, :family, :genus, :habitat, :conservation_status, :region, :source, :source_id, NOW())
                ON DUPLICATE KEY UPDATE 
                nombre_comun = COALESCE(NULLIF(VALUES(nombre_comun), ''), nombre_comun),
                habitat = COALESCE(NULLIF(VALUES(habitat), ''), habitat),
                estado_conservacion = COALESCE(NULLIF(VALUES(estado_conservacion), ''), estado_conservacion),
                updated_at = NOW()";
        
        $stmt = $this->pdo->prepare($sql);
        
        foreach ($species_list as $species) {
            try {
                // Verificar si ya existe
                if ($this->speciesExists($species['scientific_name'])) {
                    $this->expansionStats['duplicates_skipped']++;
                    continue;
                }
                
                $stmt->execute([
                    ':scientific_name' => $species['scientific_name'],
                    ':common_name' => $species['common_name'],
                    ':taxonomic_category' => $species['taxonomic_category'],
                    ':kingdom' => $species['kingdom'],
                    ':phylum' => $species['phylum'],
                    ':class' => $species['class'],
                    ':order' => $species['order'],
                    ':family' => $species['family'],
                    ':genus' => $species['genus'],
                    ':habitat' => $species['habitat'],
                    ':conservation_status' => $species['conservation_status'],
                    ':region' => $this->target_regions[$region_key]['name'],
                    ':source' => $species['source'],
                    ':source_id' => $species['source_id']
                ]);
                
                $added_count++;
                $this->expansionStats['species_added']++;
                
            } catch (PDOException $e) {
                // Si la tabla no tiene todas las columnas, crearlas
                if (strpos($e->getMessage(), 'Unknown column') !== false) {
                    $this->updateDatabaseSchema();
                    // Reintentar
                    $stmt->execute([
                        ':scientific_name' => $species['scientific_name'],
                        ':common_name' => $species['common_name'],
                        ':taxonomic_category' => $species['taxonomic_category'],
                        ':kingdom' => $species['kingdom'],
                        ':phylum' => $species['phylum'],
                        ':class' => $species['class'],
                        ':order' => $species['order'],
                        ':family' => $species['family'],
                        ':genus' => $species['genus'],
                        ':habitat' => $species['habitat'],
                        ':conservation_status' => $species['conservation_status'],
                        ':region' => $this->target_regions[$region_key]['name'],
                        ':source' => $species['source'],
                        ':source_id' => $species['source_id']
                    ]);
                    $added_count++;
                    $this->expansionStats['species_added']++;
                } else {
                    $this->log("Error agregando {$species['scientific_name']}: " . $e->getMessage());
                }
            }
        }
        
        return $added_count;
    }
    
    /**
     * Verificar si una especie ya existe
     */
    private function speciesExists($scientific_name) {
        $sql = "SELECT COUNT(*) FROM biodiversity_categories WHERE nombre_cientifico = :name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':name' => $scientific_name]);
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Actualizar esquema de base de datos
     */
    private function updateDatabaseSchema() {
        $alterations = [
            "ALTER TABLE biodiversity_categories ADD COLUMN IF NOT EXISTS reino VARCHAR(100)",
            "ALTER TABLE biodiversity_categories ADD COLUMN IF NOT EXISTS filo VARCHAR(100)",
            "ALTER TABLE biodiversity_categories ADD COLUMN IF NOT EXISTS clase VARCHAR(100)",
            "ALTER TABLE biodiversity_categories ADD COLUMN IF NOT EXISTS orden VARCHAR(100)",
            "ALTER TABLE biodiversity_categories ADD COLUMN IF NOT EXISTS familia VARCHAR(100)",
            "ALTER TABLE biodiversity_categories ADD COLUMN IF NOT EXISTS genero VARCHAR(100)",
            "ALTER TABLE biodiversity_categories ADD COLUMN IF NOT EXISTS habitat TEXT",
            "ALTER TABLE biodiversity_categories ADD COLUMN IF NOT EXISTS estado_conservacion VARCHAR(50)",
            "ALTER TABLE biodiversity_categories ADD COLUMN IF NOT EXISTS region_geografica VARCHAR(100)",
            "ALTER TABLE biodiversity_categories ADD COLUMN IF NOT EXISTS fuente_datos VARCHAR(50)",
            "ALTER TABLE biodiversity_categories ADD COLUMN IF NOT EXISTS id_fuente_externa VARCHAR(100)",
            "ALTER TABLE biodiversity_categories ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
        ];
        
        foreach ($alterations as $sql) {
            try {
                $this->pdo->exec($sql);
            } catch (PDOException $e) {
                // Ignorar errores de columnas que ya existen
                if (strpos($e->getMessage(), 'Duplicate column name') === false) {
                    $this->log("Error actualizando esquema: " . $e->getMessage());
                }
            }
        }
        
        // Agregar índices para mejorar rendimiento
        $indexes = [
            "CREATE INDEX IF NOT EXISTS idx_scientific_name ON biodiversity_categories(nombre_cientifico)",
            "CREATE INDEX IF NOT EXISTS idx_region ON biodiversity_categories(region_geografica)",
            "CREATE INDEX IF NOT EXISTS idx_class ON biodiversity_categories(clase)",
            "CREATE INDEX IF NOT EXISTS idx_source ON biodiversity_categories(fuente_datos)"
        ];
        
        foreach ($indexes as $sql) {
            try {
                $this->pdo->exec($sql);
            } catch (PDOException $e) {
                // Ignorar errores de índices que ya existen
            }
        }
    }
    
    /**
     * Utilidades de mapeo y extracción
     */
    private function mapGBIFRank($rank) {
        $mapping = [
            'SPECIES' => 'Especie',
            'GENUS' => 'Género',
            'FAMILY' => 'Familia',
            'ORDER' => 'Orden',
            'CLASS' => 'Clase',
            'PHYLUM' => 'Filo',
            'KINGDOM' => 'Reino'
        ];
        
        return $mapping[$rank] ?? 'Especie';
    }
    
    private function extractHabitat($species_data) {
        // Extraer información de hábitat si está disponible
        $habitat_indicators = ['habitat', 'environment', 'ecosystem'];
        
        foreach ($habitat_indicators as $indicator) {
            if (isset($species_data[$indicator])) {
                return $species_data[$indicator];
            }
        }
        
        return '';
    }
    
    private function extractConservationStatus($species_data) {
        if (isset($species_data['threatStatus'])) {
            return $species_data['threatStatus'];
        }
        
        return '';
    }
    
    private function extractCommonName($species_data) {
        if (isset($species_data['preferred_common_name'])) {
            return $species_data['preferred_common_name'];
        }
        
        if (isset($species_data['english_common_name'])) {
            return $species_data['english_common_name'];
        }
        
        return '';
    }
    
    /**
     * Generar reporte de expansión
     */
    private function generateExpansionReport() {
        $this->log("\n=== REPORTE DE EXPANSIÓN ===");
        $this->log("Regiones procesadas: {$this->expansionStats['regions_processed']}");
        $this->log("Especies agregadas: {$this->expansionStats['species_added']}");
        $this->log("Duplicados omitidos: {$this->expansionStats['duplicates_skipped']}");
        
        if (!empty($this->expansionStats['errors'])) {
            $this->log("\nErrores encontrados: " . count($this->expansionStats['errors']));
            foreach ($this->expansionStats['errors'] as $error) {
                $this->log("- {$error['region']}/{$error['taxon']}: {$error['error']}");
            }
        }
        
        // Estadísticas por región
        $this->log("\n=== ESTADÍSTICAS POR REGIÓN ===");
        foreach ($this->target_regions as $region_key => $region_data) {
            $count = $this->getSpeciesCountByRegion($region_data['name']);
            $this->log("{$region_data['name']}: {$count} especies");
        }
        
        $this->log("=== FIN DEL REPORTE ===");
    }
    
    private function getSpeciesCountByRegion($region_name) {
        $sql = "SELECT COUNT(*) FROM biodiversity_categories WHERE region_geografica = :region";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':region' => $region_name]);
        return $stmt->fetchColumn();
    }
    
    private function log($message) {
        $timestamp = date('Y-m-d H:i:s');
        $log_entry = "[{$timestamp}] {$message}" . PHP_EOL;
        file_put_contents($this->logFile, $log_entry, FILE_APPEND | LOCK_EX);
        echo $log_entry;
    }
}

// Ejecutar si se llama directamente
if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
    $expander = new SpeciesDatabaseExpander();
    
    // Obtener parámetros de línea de comandos
    $region = $argv[1] ?? 'all';
    $species_per_taxon = (int)($argv[2] ?? 30);
    
    if ($region === 'all') {
        echo "Expandiendo todas las regiones con {$species_per_taxon} especies por taxón...\n";
        $expander->expandAllRegions($species_per_taxon);
    } else {
        echo "Expandiendo región '{$region}' con {$species_per_taxon} especies por taxón...\n";
        $expander->expandByRegion($region, $species_per_taxon);
    }
}
?>