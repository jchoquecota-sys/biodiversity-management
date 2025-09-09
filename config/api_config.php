<?php
/**
 * Configuración de APIs para fuentes internacionales
 * Gestiona las claves de API y configuraciones para diferentes servicios
 */

class APIConfig {
    private static $config = [
        'inaturalist' => [
            'base_url' => 'https://api.inaturalist.org/v1/',
            'rate_limit' => 100, // requests per minute
            'requires_key' => false,
            'documentation' => 'https://www.inaturalist.org/pages/api+reference',
            'terms_of_use' => 'https://www.inaturalist.org/pages/terms'
        ],
        
        'gbif' => [
            'base_url' => 'https://api.gbif.org/v1/',
            'rate_limit' => 100,
            'requires_key' => false,
            'documentation' => 'https://www.gbif.org/developer/summary',
            'terms_of_use' => 'https://www.gbif.org/terms'
        ],
        
        'wikimedia' => [
            'base_url' => 'https://commons.wikimedia.org/w/api.php',
            'rate_limit' => 200,
            'requires_key' => false,
            'documentation' => 'https://commons.wikimedia.org/wiki/Commons:API',
            'license' => 'CC-BY-SA',
            'terms_of_use' => 'https://wikimediafoundation.org/wiki/Terms_of_Use'
        ],
        
        'flickr_bhl' => [
            'base_url' => 'https://api.flickr.com/services/rest/',
            'api_key' => '', // Configurar en .env o aquí
            'secret' => '', // Configurar en .env o aquí
            'rate_limit' => 3600, // per hour
            'requires_key' => true,
            'documentation' => 'https://www.flickr.com/services/api/',
            'bhl_user_id' => '61021753@N02', // Biodiversity Heritage Library
            'terms_of_use' => 'https://www.flickr.com/help/terms'
        ],
        
        'eol' => [
            'base_url' => 'https://eol.org/api/',
            'rate_limit' => 120,
            'requires_key' => false,
            'documentation' => 'https://eol.org/docs/what-is-eol/data-services/classic-apis',
            'terms_of_use' => 'https://eol.org/terms_of_use'
        ],
        
        'fishbase' => [
            'base_url' => 'https://fishbase.ropensci.org/',
            'rate_limit' => 60,
            'requires_key' => false,
            'documentation' => 'https://docs.ropensci.org/rfishbase/',
            'focus' => 'fish_species'
        ]
    ];
    
    /**
     * Obtener configuración de una API específica
     */
    public static function getAPIConfig($api_name) {
        if (!isset(self::$config[$api_name])) {
            throw new Exception("API '{$api_name}' no está configurada");
        }
        
        $config = self::$config[$api_name];
        
        // Cargar claves desde variables de entorno si están disponibles
        if ($api_name === 'flickr_bhl') {
            $config['api_key'] = $_ENV['FLICKR_API_KEY'] ?? $config['api_key'];
            $config['secret'] = $_ENV['FLICKR_SECRET'] ?? $config['secret'];
        }
        
        return $config;
    }
    
    /**
     * Obtener todas las APIs disponibles
     */
    public static function getAvailableAPIs() {
        return array_keys(self::$config);
    }
    
    /**
     * Verificar si una API está configurada correctamente
     */
    public static function isAPIConfigured($api_name) {
        if (!isset(self::$config[$api_name])) {
            return false;
        }
        
        $config = self::$config[$api_name];
        
        // Verificar si requiere clave y si está configurada
        if ($config['requires_key']) {
            if ($api_name === 'flickr_bhl') {
                $api_key = $_ENV['FLICKR_API_KEY'] ?? $config['api_key'];
                return !empty($api_key);
            }
        }
        
        return true;
    }
    
    /**
     * Obtener información de configuración para mostrar al usuario
     */
    public static function getConfigurationInfo() {
        $info = [];
        
        foreach (self::$config as $api_name => $config) {
            $info[$api_name] = [
                'name' => ucfirst($api_name),
                'configured' => self::isAPIConfigured($api_name),
                'requires_key' => $config['requires_key'],
                'rate_limit' => $config['rate_limit'],
                'documentation' => $config['documentation'] ?? null,
                'terms_of_use' => $config['terms_of_use'] ?? null
            ];
        }
        
        return $info;
    }
    
    /**
     * Generar archivo .env de ejemplo
     */
    public static function generateEnvExample() {
        $env_content = "# Configuración de APIs para Biodiversity Management\n";
        $env_content .= "# Copiar este archivo como .env y configurar las claves necesarias\n\n";
        
        $env_content .= "# Flickr API (para acceso a Biodiversity Heritage Library)\n";
        $env_content .= "# Obtener en: https://www.flickr.com/services/apps/create/apply\n";
        $env_content .= "FLICKR_API_KEY=your_flickr_api_key_here\n";
        $env_content .= "FLICKR_SECRET=your_flickr_secret_here\n\n";
        
        $env_content .= "# Configuración de base de datos\n";
        $env_content .= "DB_HOST=localhost\n";
        $env_content .= "DB_NAME=biodiversity_db\n";
        $env_content .= "DB_USER=root\n";
        $env_content .= "DB_PASS=\n\n";
        
        $env_content .= "# Configuración de descarga\n";
        $env_content .= "MAX_IMAGES_PER_SPECIES=3\n";
        $env_content .= "IMAGE_QUALITY_THRESHOLD=70\n";
        $env_content .= "DOWNLOAD_TIMEOUT=60\n";
        
        return $env_content;
    }
}

/**
 * Clase para manejar rate limiting
 */
class RateLimiter {
    private static $requests = [];
    
    /**
     * Verificar si se puede hacer una petición
     */
    public static function canMakeRequest($api_name) {
        $config = APIConfig::getAPIConfig($api_name);
        $rate_limit = $config['rate_limit'];
        
        $now = time();
        $window = 60; // 1 minuto por defecto
        
        // Ajustar ventana de tiempo según la API
        if ($api_name === 'flickr_bhl') {
            $window = 3600; // 1 hora para Flickr
        }
        
        // Limpiar peticiones antiguas
        if (!isset(self::$requests[$api_name])) {
            self::$requests[$api_name] = [];
        }
        
        self::$requests[$api_name] = array_filter(
            self::$requests[$api_name],
            function($timestamp) use ($now, $window) {
                return ($now - $timestamp) < $window;
            }
        );
        
        // Verificar si se puede hacer la petición
        return count(self::$requests[$api_name]) < $rate_limit;
    }
    
    /**
     * Registrar una petición
     */
    public static function recordRequest($api_name) {
        if (!isset(self::$requests[$api_name])) {
            self::$requests[$api_name] = [];
        }
        
        self::$requests[$api_name][] = time();
    }
    
    /**
     * Calcular tiempo de espera necesario
     */
    public static function getWaitTime($api_name) {
        if (self::canMakeRequest($api_name)) {
            return 0;
        }
        
        $config = APIConfig::getAPIConfig($api_name);
        $rate_limit = $config['rate_limit'];
        
        $window = ($api_name === 'flickr_bhl') ? 3600 : 60;
        
        if (!isset(self::$requests[$api_name]) || empty(self::$requests[$api_name])) {
            return 0;
        }
        
        $oldest_request = min(self::$requests[$api_name]);
        $wait_time = $window - (time() - $oldest_request);
        
        return max(0, $wait_time);
    }
}

/**
 * Utilidades para manejo de APIs
 */
class APIUtils {
    /**
     * Hacer petición HTTP con manejo de errores
     */
    public static function makeRequest($url, $options = []) {
        $default_options = [
            'timeout' => 30,
            'user_agent' => 'BiodiversityManagement/1.0 (Educational Purpose)',
            'headers' => ['Accept: application/json']
        ];
        
        $options = array_merge($default_options, $options);
        
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => implode("\r\n", array_merge(
                    $options['headers'],
                    ["User-Agent: {$options['user_agent']}"]
                )),
                'timeout' => $options['timeout']
            ]
        ]);
        
        $response = @file_get_contents($url, false, $context);
        
        if ($response === false) {
            $error = error_get_last();
            throw new Exception("Error en petición HTTP: " . ($error['message'] ?? 'Unknown error'));
        }
        
        return $response;
    }
    
    /**
     * Validar respuesta JSON
     */
    public static function validateJSONResponse($response) {
        $data = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Respuesta JSON inválida: ' . json_last_error_msg());
        }
        
        return $data;
    }
    
    /**
     * Generar URL segura
     */
    public static function buildURL($base_url, $params = []) {
        if (empty($params)) {
            return $base_url;
        }
        
        $query_string = http_build_query($params, '', '&', PHP_QUERY_RFC3986);
        $separator = (strpos($base_url, '?') !== false) ? '&' : '?';
        
        return $base_url . $separator . $query_string;
    }
}

// Cargar variables de entorno si existe el archivo .env
if (file_exists(__DIR__ . '/../.env')) {
    $env_lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($env_lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}
?>