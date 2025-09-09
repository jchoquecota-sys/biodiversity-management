<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Imágenes de Especies</title>
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
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 1.1em;
            opacity: 0.9;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 30px;
            background: #f8f9fa;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #2E8B57;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9em;
        }
        
        .categories {
            padding: 30px;
        }
        
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .category-card {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .category-card:hover {
            border-color: #2E8B57;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .category-header {
            background: #2E8B57;
            color: white;
            padding: 15px;
            font-weight: bold;
            text-align: center;
        }
        
        .category-content {
            padding: 20px;
        }
        
        .image-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: 10px;
            margin-top: 15px;
        }
        
        .image-item {
            aspect-ratio: 1;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .image-item:hover {
            border-color: #2E8B57;
            transform: scale(1.05);
        }
        
        .image-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 15px;
        }
        
        .upload-area:hover {
            border-color: #2E8B57;
            background: #f8f9fa;
        }
        
        .upload-area input[type="file"] {
            display: none;
        }
        
        .btn {
            background: #2E8B57;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s ease;
        }
        
        .btn:hover {
            background: #228B22;
        }
        
        .instructions {
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            border-radius: 10px;
            padding: 20px;
            margin: 30px;
        }
        
        .instructions h3 {
            color: #0066cc;
            margin-bottom: 15px;
        }
        
        .instructions ul {
            margin-left: 20px;
        }
        
        .instructions li {
            margin-bottom: 8px;
            color: #333;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
        }
        
        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 10px;
            max-width: 90%;
            max-height: 90%;
        }
        
        .modal img {
            max-width: 100%;
            max-height: 70vh;
            object-fit: contain;
        }
        
        .close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            color: #aaa;
        }
        
        .close:hover {
            color: #000;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🌿 Gestor de Imágenes de Especies</h1>
            <p>Sistema de administración de imágenes para biodiversidad peruana</p>
        </div>
        
        <?php
        // Configuración de la base de datos
        $host = 'localhost';
        $dbname = 'biodiversity_management';
        $username = 'root';
        $password = '';
        
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
        
        // Obtener estadísticas
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM biodiversity_categories");
        $totalSpecies = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        $stmt = $pdo->query("SELECT COUNT(*) as with_images FROM biodiversity_categories WHERE image_path IS NOT NULL AND image_path != ''");
        $speciesWithImages = $stmt->fetch(PDO::FETCH_ASSOC)['with_images'];
        
        $speciesWithoutImages = $totalSpecies - $speciesWithImages;
        $percentage = $totalSpecies > 0 ? round(($speciesWithImages / $totalSpecies) * 100, 1) : 0;
        
        // Contar imágenes por categoría
        $categories = [
            'reptiles' => 'Reptiles 🦎',
            'anfibios' => 'Anfibios 🐸',
            'mamiferos' => 'Mamíferos 🦌',
            'aves' => 'Aves 🦅',
            'peces' => 'Peces 🐟',
            'plantas' => 'Plantas 🌱',
            'otros' => 'Otros 🔬'
        ];
        
        $categoryStats = [];
        foreach ($categories as $folder => $name) {
            $dir = "public/images/especies/$folder";
            $count = 0;
            if (is_dir($dir)) {
                $files = glob($dir . '/*');
                $count = count($files);
            }
            $categoryStats[$folder] = $count;
        }
        ?>
        
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo $totalSpecies; ?></div>
                <div class="stat-label">Total de Especies</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $speciesWithImages; ?></div>
                <div class="stat-label">Con Imágenes</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $speciesWithoutImages; ?></div>
                <div class="stat-label">Sin Imágenes</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $percentage; ?>%</div>
                <div class="stat-label">Completado</div>
            </div>
        </div>
        
        <div class="categories">
            <h2>📁 Categorías de Imágenes</h2>
            <div class="category-grid">
                <?php foreach ($categories as $folder => $name): ?>
                <div class="category-card">
                    <div class="category-header">
                        <?php echo $name; ?> (<?php echo $categoryStats[$folder]; ?> imágenes)
                    </div>
                    <div class="category-content">
                        <p><strong>Ruta:</strong> public/images/especies/<?php echo $folder; ?>/</p>
                        
                        <?php if ($categoryStats[$folder] > 0): ?>
                        <div class="image-grid">
                            <?php
                            $dir = "public/images/especies/$folder";
                            $files = glob($dir . '/*');
                            $displayFiles = array_slice($files, 0, 6); // Mostrar máximo 6
                            foreach ($displayFiles as $file):
                                $filename = basename($file);
                                $webPath = "images/especies/$folder/$filename";
                            ?>
                            <div class="image-item" onclick="openModal('<?php echo $webPath; ?>')">
                                <img src="<?php echo $webPath; ?>" alt="<?php echo $filename; ?>" onerror="this.style.display='none'">
                            </div>
                            <?php endforeach; ?>
                            
                            <?php if (count($files) > 6): ?>
                            <div class="image-item" style="background: #f8f9fa; display: flex; align-items: center; justify-content: center; color: #666; font-size: 12px;">
                                +<?php echo count($files) - 6; ?> más
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php else: ?>
                        <p style="color: #666; font-style: italic;">No hay imágenes en esta categoría</p>
                        <?php endif; ?>
                        
                        <div class="upload-area" onclick="document.getElementById('upload-<?php echo $folder; ?>').click()">
                            <input type="file" id="upload-<?php echo $folder; ?>" accept="image/*" multiple>
                            📤 Hacer clic para subir imágenes
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="instructions">
            <h3>📋 Instrucciones de Uso</h3>
            <ul>
                <li><strong>Estructura de archivos:</strong> Las imágenes se organizan automáticamente por categorías taxonómicas</li>
                <li><strong>Formatos soportados:</strong> JPG, PNG, SVG (recomendado: JPG para fotos reales)</li>
                <li><strong>Nombres de archivo:</strong> Mantén los nombres existentes para reemplazar imágenes placeholder</li>
                <li><strong>Tamaño recomendado:</strong> 800x600 píxeles o similar para mejor rendimiento</li>
                <li><strong>Reemplazo de imágenes:</strong> Sube una imagen con el mismo nombre para reemplazar la existente</li>
                <li><strong>Visualización:</strong> Haz clic en cualquier imagen para verla en tamaño completo</li>
            </ul>
        </div>
    </div>
    
    <!-- Modal para ver imágenes -->
    <div id="imageModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <img id="modalImage" src="" alt="">
        </div>
    </div>
    
    <script>
        function openModal(imageSrc) {
            document.getElementById('imageModal').style.display = 'block';
            document.getElementById('modalImage').src = imageSrc;
        }
        
        function closeModal() {
            document.getElementById('imageModal').style.display = 'none';
        }
        
        // Cerrar modal al hacer clic fuera de la imagen
        window.onclick = function(event) {
            const modal = document.getElementById('imageModal');
            if (event.target == modal) {
                closeModal();
            }
        }
        
        // Manejar subida de archivos
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function(e) {
                const files = e.target.files;
                const category = this.id.replace('upload-', '');
                
                if (files.length > 0) {
                    uploadFiles(files, category);
                }
            });
        });
        
        async function uploadFiles(files, category) {
            const formData = new FormData();
            
            // Agregar archivos al FormData
            for (let i = 0; i < files.length; i++) {
                formData.append('images[]', files[i]);
            }
            formData.append('category', category);
            
            // Mostrar indicador de carga
            const uploadArea = document.querySelector(`#upload-${category}`).parentElement;
            const originalContent = uploadArea.innerHTML;
            uploadArea.innerHTML = '<div style="padding: 20px; text-align: center; color: #2E8B57;">📤 Subiendo archivos...</div>';
            
            try {
                const response = await fetch('upload_handler.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    let message = `✅ Subida completada:\n\n`;
                    message += `📊 Resumen:\n`;
                    message += `• Total: ${result.summary.total} archivos\n`;
                    message += `• Exitosos: ${result.summary.successful}\n`;
                    message += `• Fallidos: ${result.summary.failed}\n\n`;
                    
                    if (result.summary.successful > 0) {
                        message += `📋 Detalles de archivos exitosos:\n`;
                        result.results.forEach(r => {
                            if (r.success) {
                                message += `• ${r.file} → ${r.saved_as}`;
                                if (r.species_updated) {
                                    message += ` (asociado a: ${r.species_updated})`;
                                }
                                message += `\n`;
                            }
                        });
                    }
                    
                    if (result.summary.failed > 0) {
                        message += `\n❌ Errores:\n`;
                        result.results.forEach(r => {
                            if (!r.success) {
                                message += `• ${r.file}: ${r.error}\n`;
                            }
                        });
                    }
                    
                    alert(message);
                    
                    // Recargar la página para mostrar las nuevas imágenes
                    if (result.summary.successful > 0) {
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    }
                } else {
                    alert('❌ Error en la subida: ' + (result.error || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('❌ Error de conexión: ' + error.message);
            } finally {
                // Restaurar contenido original
                uploadArea.innerHTML = originalContent;
                // Reactivar el event listener
                const newInput = uploadArea.querySelector('input[type="file"]');
                newInput.addEventListener('change', function(e) {
                    const files = e.target.files;
                    const category = this.id.replace('upload-', '');
                    if (files.length > 0) {
                        uploadFiles(files, category);
                    }
                });
            }
        }
    </script>
</body>
</html>