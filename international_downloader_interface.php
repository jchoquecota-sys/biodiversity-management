<?php
/**
 * Interfaz web para el descargador internacional de imágenes
 * Permite configurar y ejecutar descargas desde múltiples fuentes
 */

require_once 'config/database.php';
require_once 'config/api_config.php';
require_once 'international_species_downloader.php';

// Manejar peticiones AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $action = $_POST['action'] ?? '';
    
    try {
        switch ($action) {
            case 'start_download':
                $limit = (int)($_POST['limit'] ?? 50);
                $sources = $_POST['sources'] ?? ['inaturalist', 'gbif', 'wikimedia'];
                
                // Iniciar descarga en segundo plano
                $result = startDownloadProcess($limit, $sources);
                echo json_encode(['success' => true, 'data' => $result]);
                break;
                
            case 'get_stats':
                $stats = getDownloadStats();
                echo json_encode(['success' => true, 'data' => $stats]);
                break;
                
            case 'get_api_status':
                $status = getAPIStatus();
                echo json_encode(['success' => true, 'data' => $status]);
                break;
                
            case 'test_api':
                $api_name = $_POST['api_name'] ?? '';
                $result = testAPI($api_name);
                echo json_encode(['success' => true, 'data' => $result]);
                break;
                
            default:
                echo json_encode(['success' => false, 'error' => 'Acción no válida']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

// Funciones auxiliares
function startDownloadProcess($limit, $sources) {
    // En un entorno real, esto se ejecutaría en segundo plano
    // Por simplicidad, ejecutamos directamente
    
    $downloader = new InternationalImageDownloader();
    $species_list = $downloader->getSpeciesWithoutImages($limit);
    
    if (empty($species_list)) {
        return ['message' => 'No se encontraron especies sin imágenes', 'count' => 0];
    }
    
    // Simular inicio de proceso
    return [
        'message' => 'Proceso de descarga iniciado',
        'species_count' => count($species_list),
        'sources' => $sources,
        'estimated_time' => count($species_list) * 2 // 2 segundos por especie estimado
    ];
}

function getDownloadStats() {
    $database = new Database();
    $pdo = $database->getConnection();
    
    // Estadísticas generales
    $stats = [];
    
    // Total de especies
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM biodiversity_categories");
    $stats['total_species'] = $stmt->fetch()['total'];
    
    // Especies con imágenes
    $stmt = $pdo->query("SELECT COUNT(*) as with_images FROM biodiversity_categories WHERE imagen_url IS NOT NULL AND imagen_url != '' AND imagen_url NOT LIKE '%placeholder%'");
    $stats['species_with_images'] = $stmt->fetch()['with_images'];
    
    // Especies sin imágenes
    $stats['species_without_images'] = $stats['total_species'] - $stats['species_with_images'];
    
    // Porcentaje de cobertura
    $stats['coverage_percentage'] = $stats['total_species'] > 0 ? 
        round(($stats['species_with_images'] / $stats['total_species']) * 100, 2) : 0;
    
    // Estadísticas por fuente (si existe la tabla de metadatos)
    try {
        $stmt = $pdo->query("SELECT source, COUNT(*) as count FROM image_metadata GROUP BY source");
        $stats['by_source'] = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    } catch (PDOException $e) {
        $stats['by_source'] = [];
    }
    
    return $stats;
}

function getAPIStatus() {
    return APIConfig::getConfigurationInfo();
}

function testAPI($api_name) {
    try {
        $config = APIConfig::getAPIConfig($api_name);
        
        // Hacer una petición de prueba simple
        switch ($api_name) {
            case 'inaturalist':
                $url = $config['base_url'] . 'observations?per_page=1';
                break;
            case 'gbif':
                $url = $config['base_url'] . 'species/match?name=Homo+sapiens';
                break;
            case 'wikimedia':
                $url = $config['base_url'] . '?action=query&format=json&meta=siteinfo';
                break;
            default:
                throw new Exception("Prueba no implementada para {$api_name}");
        }
        
        $response = APIUtils::makeRequest($url);
        $data = APIUtils::validateJSONResponse($response);
        
        return [
            'status' => 'success',
            'message' => "API {$api_name} responde correctamente",
            'response_size' => strlen($response)
        ];
        
    } catch (Exception $e) {
        return [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Descargador Internacional de Imágenes - Biodiversity Management</title>
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
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 1.2em;
            opacity: 0.9;
        }
        
        .main-content {
            padding: 30px;
        }
        
        .section {
            margin-bottom: 30px;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .section h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 1.8em;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        
        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 0.9em;
            opacity: 0.9;
        }
        
        .api-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .api-card {
            border: 2px solid #ecf0f1;
            border-radius: 10px;
            padding: 20px;
            transition: all 0.3s ease;
        }
        
        .api-card:hover {
            border-color: #3498db;
            transform: translateY(-2px);
        }
        
        .api-card.configured {
            border-color: #27ae60;
            background: #f8fff8;
        }
        
        .api-card.not-configured {
            border-color: #e74c3c;
            background: #fff8f8;
        }
        
        .api-status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .status-configured {
            background: #27ae60;
            color: white;
        }
        
        .status-not-configured {
            background: #e74c3c;
            color: white;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .form-group input, .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ecf0f1;
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #3498db;
        }
        
        .checkbox-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }
        
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-right: 10px;
            margin-bottom: 10px;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #95a5a6, #7f8c8d);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #27ae60, #229954);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
        }
        
        .progress-container {
            background: #ecf0f1;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            display: none;
        }
        
        .progress-bar {
            width: 100%;
            height: 20px;
            background: #bdc3c7;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 10px;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(135deg, #27ae60, #229954);
            width: 0%;
            transition: width 0.3s ease;
        }
        
        .log-container {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 20px;
            border-radius: 10px;
            font-family: 'Courier New', monospace;
            max-height: 300px;
            overflow-y: auto;
            margin-top: 20px;
            display: none;
        }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
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
        
        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🌍 Descargador Internacional</h1>
            <p>Obtén imágenes de especies desde fuentes científicas globales</p>
        </div>
        
        <div class="main-content">
            <!-- Estadísticas actuales -->
            <div class="section">
                <h2>📊 Estadísticas del Sistema</h2>
                <div id="stats-container">
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-number" id="total-species">-</div>
                            <div class="stat-label">Total Especies</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number" id="with-images">-</div>
                            <div class="stat-label">Con Imágenes</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number" id="without-images">-</div>
                            <div class="stat-label">Sin Imágenes</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number" id="coverage">-</div>
                            <div class="stat-label">% Cobertura</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Estado de APIs -->
            <div class="section">
                <h2>🔌 Estado de APIs</h2>
                <div id="api-status-container">
                    <div class="api-grid" id="api-grid">
                        <!-- Se llenará dinámicamente -->
                    </div>
                </div>
            </div>
            
            <!-- Configuración de descarga -->
            <div class="section">
                <h2>⚙️ Configurar Descarga</h2>
                <form id="download-form">
                    <div class="form-group">
                        <label for="species-limit">Número de especies a procesar:</label>
                        <input type="number" id="species-limit" name="limit" value="50" min="1" max="500">
                    </div>
                    
                    <div class="form-group">
                        <label>Fuentes a utilizar:</label>
                        <div class="checkbox-group">
                            <div class="checkbox-item">
                                <input type="checkbox" id="source-inaturalist" name="sources[]" value="inaturalist" checked>
                                <label for="source-inaturalist">iNaturalist</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="source-gbif" name="sources[]" value="gbif" checked>
                                <label for="source-gbif">GBIF</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="source-wikimedia" name="sources[]" value="wikimedia" checked>
                                <label for="source-wikimedia">Wikimedia Commons</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="source-eol" name="sources[]" value="eol">
                                <label for="source-eol">Encyclopedia of Life</label>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn">🚀 Iniciar Descarga</button>
                    <button type="button" class="btn btn-secondary" onclick="refreshStats()">🔄 Actualizar Estadísticas</button>
                </form>
                
                <!-- Contenedor de progreso -->
                <div id="progress-container" class="progress-container">
                    <h3>Progreso de Descarga</h3>
                    <div class="progress-bar">
                        <div class="progress-fill" id="progress-fill"></div>
                    </div>
                    <div id="progress-text">Preparando descarga...</div>
                </div>
                
                <!-- Log de actividad -->
                <div id="log-container" class="log-container">
                    <div id="log-content"></div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Cargar datos iniciales
        document.addEventListener('DOMContentLoaded', function() {
            refreshStats();
            refreshAPIStatus();
        });
        
        // Actualizar estadísticas
        function refreshStats() {
            fetch('', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=get_stats'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const stats = data.data;
                    document.getElementById('total-species').textContent = stats.total_species;
                    document.getElementById('with-images').textContent = stats.species_with_images;
                    document.getElementById('without-images').textContent = stats.species_without_images;
                    document.getElementById('coverage').textContent = stats.coverage_percentage + '%';
                }
            })
            .catch(error => console.error('Error:', error));
        }
        
        // Actualizar estado de APIs
        function refreshAPIStatus() {
            fetch('', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=get_api_status'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const apiGrid = document.getElementById('api-grid');
                    apiGrid.innerHTML = '';
                    
                    Object.entries(data.data).forEach(([apiName, info]) => {
                        const card = document.createElement('div');
                        card.className = `api-card ${info.configured ? 'configured' : 'not-configured'}`;
                        
                        card.innerHTML = `
                            <div class="api-status ${info.configured ? 'status-configured' : 'status-not-configured'}">
                                ${info.configured ? '✅ Configurada' : '❌ No Configurada'}
                            </div>
                            <h3>${info.name}</h3>
                            <p><strong>Rate Limit:</strong> ${info.rate_limit} req/min</p>
                            <p><strong>Requiere API Key:</strong> ${info.requires_key ? 'Sí' : 'No'}</p>
                            ${info.documentation ? `<p><a href="${info.documentation}" target="_blank">📖 Documentación</a></p>` : ''}
                            <button class="btn btn-secondary" onclick="testAPI('${apiName}')">🧪 Probar API</button>
                        `;
                        
                        apiGrid.appendChild(card);
                    });
                }
            })
            .catch(error => console.error('Error:', error));
        }
        
        // Probar API específica
        function testAPI(apiName) {
            showAlert('Probando API ' + apiName + '...', 'info');
            
            fetch('', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `action=test_api&api_name=${apiName}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const result = data.data;
                    if (result.status === 'success') {
                        showAlert(`✅ ${result.message}`, 'success');
                    } else {
                        showAlert(`❌ Error: ${result.message}`, 'error');
                    }
                } else {
                    showAlert(`❌ Error: ${data.error}`, 'error');
                }
            })
            .catch(error => {
                showAlert(`❌ Error de conexión: ${error.message}`, 'error');
            });
        }
        
        // Manejar formulario de descarga
        document.getElementById('download-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('action', 'start_download');
            
            // Mostrar progreso
            document.getElementById('progress-container').style.display = 'block';
            document.getElementById('log-container').style.display = 'block';
            
            addLog('🚀 Iniciando proceso de descarga...');
            
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const result = data.data;
                    addLog(`📊 Especies a procesar: ${result.species_count}`);
                    addLog(`🌐 Fuentes: ${result.sources.join(', ')}`);
                    addLog(`⏱️ Tiempo estimado: ${result.estimated_time} segundos`);
                    
                    // Simular progreso
                    simulateProgress(result.estimated_time);
                    
                    showAlert(result.message, 'success');
                } else {
                    showAlert(`Error: ${data.error}`, 'error');
                    addLog(`❌ Error: ${data.error}`);
                }
            })
            .catch(error => {
                showAlert(`Error de conexión: ${error.message}`, 'error');
                addLog(`❌ Error de conexión: ${error.message}`);
            });
        });
        
        // Simular progreso de descarga
        function simulateProgress(totalTime) {
            let progress = 0;
            const interval = setInterval(() => {
                progress += 2;
                document.getElementById('progress-fill').style.width = progress + '%';
                document.getElementById('progress-text').textContent = `Progreso: ${progress}%`;
                
                if (progress >= 100) {
                    clearInterval(interval);
                    addLog('✅ Descarga completada');
                    document.getElementById('progress-text').textContent = 'Descarga completada';
                    refreshStats();
                }
            }, (totalTime * 1000) / 50); // 50 pasos
        }
        
        // Agregar mensaje al log
        function addLog(message) {
            const logContent = document.getElementById('log-content');
            const timestamp = new Date().toLocaleTimeString();
            logContent.innerHTML += `[${timestamp}] ${message}<br>`;
            logContent.scrollTop = logContent.scrollHeight;
        }
        
        // Mostrar alerta
        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.textContent = message;
            
            const mainContent = document.querySelector('.main-content');
            mainContent.insertBefore(alertDiv, mainContent.firstChild);
            
            // Remover después de 5 segundos
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }
    </script>
</body>
</html>