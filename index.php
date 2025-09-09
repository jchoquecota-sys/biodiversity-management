<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Biodiversidad</title>
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
            background: linear-gradient(135deg, #2E8B57, #228B22);
            color: white;
            padding: 40px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 3em;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .header p {
            font-size: 1.2em;
            opacity: 0.9;
        }
        
        .nav-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            padding: 40px;
        }
        
        .nav-card {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }
        
        .nav-card:hover {
            border-color: #2E8B57;
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        
        .nav-icon {
            font-size: 4em;
            margin-bottom: 20px;
            display: block;
        }
        
        .nav-title {
            font-size: 1.5em;
            font-weight: bold;
            color: #2E8B57;
            margin-bottom: 15px;
        }
        
        .nav-description {
            color: #666;
            line-height: 1.6;
        }
        
        .stats-section {
            background: #f8f9fa;
            padding: 40px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            color: #2E8B57;
            margin-bottom: 10px;
        }
        
        .stat-label {
            color: #666;
            font-size: 1em;
        }
        
        .features-section {
            padding: 40px;
        }
        
        .features-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #2E8B57;
        }
        
        .feature-icon {
            font-size: 1.5em;
            margin-right: 15px;
            color: #2E8B57;
        }
        
        .footer {
            background: #2E8B57;
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .footer p {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🌿 Sistema de Gestión de Biodiversidad</h1>
            <p>Plataforma integral para la administración de especies e imágenes de la biodiversidad peruana</p>
        </div>
        
        <?php
        // Configuración de la base de datos
        $host = 'localhost';
        $dbname = 'biodiversity_management';
        $username = 'root';
        $password = '';
        
        $stats = [
            'total_species' => 0,
            'species_with_images' => 0,
            'total_images' => 0,
            'categories' => 0
        ];
        
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Obtener estadísticas
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM biodiversity_categories");
            $stats['total_species'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            $stmt = $pdo->query("SELECT COUNT(*) as with_images FROM biodiversity_categories WHERE image_path IS NOT NULL AND image_path != ''");
            $stats['species_with_images'] = $stmt->fetch(PDO::FETCH_ASSOC)['with_images'];
            
            // Contar imágenes en el sistema de archivos
            $imageDir = 'public/images/especies';
            $categories = ['reptiles', 'anfibios', 'mamiferos', 'aves', 'peces', 'plantas', 'otros'];
            $totalImages = 0;
            $activeCategories = 0;
            
            foreach ($categories as $category) {
                $categoryPath = $imageDir . '/' . $category;
                if (is_dir($categoryPath)) {
                    $files = glob($categoryPath . '/*');
                    $imageCount = count($files);
                    $totalImages += $imageCount;
                    if ($imageCount > 0) $activeCategories++;
                }
            }
            
            $stats['total_images'] = $totalImages;
            $stats['categories'] = $activeCategories;
            
        } catch (PDOException $e) {
            // En caso de error, mantener estadísticas en 0
        }
        ?>
        
        <div class="stats-section">
            <h2 style="text-align: center; color: #2E8B57; margin-bottom: 10px;">📊 Estadísticas del Sistema</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $stats['total_species']; ?></div>
                    <div class="stat-label">Especies Registradas</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $stats['species_with_images']; ?></div>
                    <div class="stat-label">Con Imágenes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $stats['total_images']; ?></div>
                    <div class="stat-label">Imágenes Totales</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $stats['categories']; ?></div>
                    <div class="stat-label">Categorías Activas</div>
                </div>
            </div>
        </div>
        
        <div class="nav-grid">
            <a href="image_manager.php" class="nav-card">
                <div class="nav-icon">🖼️</div>
                <div class="nav-title">Gestor de Imágenes</div>
                <div class="nav-description">
                    Visualiza, organiza y sube nuevas imágenes para las especies. 
                    Interfaz principal para la gestión de archivos multimedia.
                </div>
            </a>
            
            <a href="species_image_associator.php" class="nav-card">
                <div class="nav-icon">🔗</div>
                <div class="nav-title">Asociador de Imágenes</div>
                <div class="nav-description">
                    Conecta imágenes existentes con especies específicas en la base de datos. 
                    Herramienta para asociaciones manuales.
                </div>
            </a>
            
            <a href="#" onclick="runImageManagement()" class="nav-card">
                <div class="nav-icon">⚙️</div>
                <div class="nav-title">Organizar Sistema</div>
                <div class="nav-description">
                    Ejecuta scripts de mantenimiento para organizar imágenes por categorías 
                    y actualizar la base de datos.
                </div>
            </a>
            
            <a href="#" onclick="showDatabaseInfo()" class="nav-card">
                <div class="nav-icon">📊</div>
                <div class="nav-title">Información del Sistema</div>
                <div class="nav-description">
                    Visualiza estadísticas detalladas, estructura de la base de datos 
                    y estado del sistema de archivos.
                </div>
            </a>
        </div>
        
        <div class="features-section">
            <h2 style="text-align: center; color: #2E8B57; margin-bottom: 10px;">✨ Características del Sistema</h2>
            <div class="features-list">
                <div class="feature-item">
                    <div class="feature-icon">📁</div>
                    <div>Organización automática por categorías taxonómicas</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">🔄</div>
                    <div>Redimensionamiento automático de imágenes</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">🔍</div>
                    <div>Búsqueda avanzada de especies e imágenes</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">📤</div>
                    <div>Subida múltiple de archivos con validación</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">🔗</div>
                    <div>Asociación manual de imágenes con especies</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">📊</div>
                    <div>Estadísticas en tiempo real del sistema</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">🎨</div>
                    <div>Generación de imágenes placeholder SVG</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">💾</div>
                    <div>Respaldo automático de asociaciones</div>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>🌿 Sistema de Gestión de Biodiversidad - Desarrollado para la conservación de especies peruanas</p>
        </div>
    </div>
    
    <script>
        function runImageManagement() {
            if (confirm('¿Deseas ejecutar el script de organización de imágenes?\n\nEsto reorganizará las imágenes por categorías y actualizará la base de datos.')) {
                window.location.href = 'image_management_system.php';
            }
        }
        
        function showDatabaseInfo() {
            alert('📊 Información del Sistema:\n\n' +
                  '• Especies totales: <?php echo $stats["total_species"]; ?>\n' +
                  '• Especies con imágenes: <?php echo $stats["species_with_images"]; ?>\n' +
                  '• Imágenes totales: <?php echo $stats["total_images"]; ?>\n' +
                  '• Categorías activas: <?php echo $stats["categories"]; ?>\n\n' +
                  'Base de datos: <?php echo $dbname; ?>\n' +
                  'Directorio de imágenes: public/images/especies/');
        }
    </script>
</body>
</html>