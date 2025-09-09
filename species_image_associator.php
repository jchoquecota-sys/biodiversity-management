<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asociador de Imágenes y Especies</title>
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
        
        .content {
            padding: 30px;
        }
        
        .search-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .search-box {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .search-box input {
            flex: 1;
            padding: 12px;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            font-size: 16px;
        }
        
        .search-box input:focus {
            outline: none;
            border-color: #2E8B57;
        }
        
        .btn {
            background: #2E8B57;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s ease;
        }
        
        .btn:hover {
            background: #228B22;
        }
        
        .btn-secondary {
            background: #6c757d;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .results-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 30px;
        }
        
        .species-list, .images-list {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
        }
        
        .species-item, .image-item {
            background: white;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .species-item:hover, .image-item:hover {
            border-color: #2E8B57;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .species-item.selected, .image-item.selected {
            border-color: #2E8B57;
            background: #e8f5e8;
        }
        
        .species-name {
            font-weight: bold;
            color: #2E8B57;
            margin-bottom: 5px;
        }
        
        .species-details {
            font-size: 14px;
            color: #666;
        }
        
        .image-preview {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        
        .image-name {
            font-size: 14px;
            color: #333;
            text-align: center;
        }
        
        .association-panel {
            background: #e7f3ff;
            border: 2px solid #b3d9ff;
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
            text-align: center;
        }
        
        .selected-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 20px 0;
        }
        
        .selected-species, .selected-image {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border: 2px solid #2E8B57;
        }
        
        .status-message {
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        
        .success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }
        
        .page-btn {
            padding: 8px 12px;
            border: 1px solid #dee2e6;
            background: white;
            cursor: pointer;
            border-radius: 5px;
        }
        
        .page-btn.active {
            background: #2E8B57;
            color: white;
            border-color: #2E8B57;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔗 Asociador de Imágenes y Especies</h1>
            <p>Conecta imágenes existentes con especies en la base de datos</p>
        </div>
        
        <div class="content">
            <div class="search-section">
                <h3>🔍 Buscar Especies</h3>
                <div class="search-box">
                    <input type="text" id="speciesSearch" placeholder="Buscar por nombre científico o común...">
                    <button class="btn" onclick="searchSpecies()">Buscar</button>
                    <button class="btn btn-secondary" onclick="loadAllSpecies()">Ver Todas</button>
                </div>
            </div>
            
            <div class="results-grid">
                <div class="species-list">
                    <h3>📋 Especies (sin imágenes)</h3>
                    <div id="speciesResults"></div>
                    <div id="speciesPagination" class="pagination"></div>
                </div>
                
                <div class="images-list">
                    <h3>🖼️ Imágenes Disponibles</h3>
                    <div id="imageResults"></div>
                    <div id="imagesPagination" class="pagination"></div>
                </div>
            </div>
            
            <div class="association-panel">
                <h3>🔗 Asociar Imagen con Especie</h3>
                <p>Selecciona una especie y una imagen para asociarlas</p>
                
                <div class="selected-info">
                    <div class="selected-species">
                        <h4>Especie Seleccionada:</h4>
                        <div id="selectedSpeciesInfo">Ninguna seleccionada</div>
                    </div>
                    <div class="selected-image">
                        <h4>Imagen Seleccionada:</h4>
                        <div id="selectedImageInfo">Ninguna seleccionada</div>
                    </div>
                </div>
                
                <button class="btn" onclick="associateImageWithSpecies()" id="associateBtn" disabled>
                    🔗 Crear Asociación
                </button>
                
                <div id="statusMessage"></div>
            </div>
        </div>
    </div>
    
    <script>
        let selectedSpecies = null;
        let selectedImage = null;
        let currentSpeciesPage = 1;
        let currentImagesPage = 1;
        const itemsPerPage = 10;
        
        // Cargar imágenes disponibles al inicio
        document.addEventListener('DOMContentLoaded', function() {
            loadAvailableImages();
            loadSpeciesWithoutImages();
        });
        
        async function searchSpecies() {
            const query = document.getElementById('speciesSearch').value;
            if (query.trim() === '') {
                loadSpeciesWithoutImages();
                return;
            }
            
            try {
                const response = await fetch(`species_search.php?q=${encodeURIComponent(query)}&page=${currentSpeciesPage}`);
                const data = await response.json();
                displaySpecies(data.species);
                displaySpeciesPagination(data.totalPages, data.currentPage);
            } catch (error) {
                console.error('Error:', error);
            }
        }
        
        async function loadSpeciesWithoutImages(page = 1) {
            currentSpeciesPage = page;
            try {
                const response = await fetch(`species_search.php?without_images=1&page=${page}`);
                const data = await response.json();
                displaySpecies(data.species);
                displaySpeciesPagination(data.totalPages, data.currentPage);
            } catch (error) {
                console.error('Error:', error);
            }
        }
        
        async function loadAllSpecies(page = 1) {
            currentSpeciesPage = page;
            try {
                const response = await fetch(`species_search.php?all=1&page=${page}`);
                const data = await response.json();
                displaySpecies(data.species);
                displaySpeciesPagination(data.totalPages, data.currentPage);
            } catch (error) {
                console.error('Error:', error);
            }
        }
        
        async function loadAvailableImages(page = 1) {
            currentImagesPage = page;
            try {
                const response = await fetch(`available_images.php?page=${page}`);
                const data = await response.json();
                displayImages(data.images);
                displayImagesPagination(data.totalPages, data.currentPage);
            } catch (error) {
                console.error('Error:', error);
            }
        }
        
        function displaySpecies(species) {
            const container = document.getElementById('speciesResults');
            container.innerHTML = '';
            
            species.forEach(sp => {
                const div = document.createElement('div');
                div.className = 'species-item';
                div.onclick = () => selectSpecies(sp, div);
                
                div.innerHTML = `
                    <div class="species-name">${sp.scientific_name}</div>
                    <div class="species-details">
                        <strong>Común:</strong> ${sp.common_name || 'N/A'}<br>
                        <strong>ID:</strong> ${sp.id}<br>
                        <strong>Imagen actual:</strong> ${sp.image_path || 'Sin imagen'}
                    </div>
                `;
                
                container.appendChild(div);
            });
        }
        
        function displayImages(images) {
            const container = document.getElementById('imageResults');
            container.innerHTML = '';
            
            images.forEach(img => {
                const div = document.createElement('div');
                div.className = 'image-item';
                div.onclick = () => selectImage(img, div);
                
                div.innerHTML = `
                    <img src="${img.path}" alt="${img.filename}" class="image-preview" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iI2Y4ZjlmYSIvPjx0ZXh0IHg9IjUwIiB5PSI1MCIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjEyIiBmaWxsPSIjNjY2IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkeT0iLjNlbSI+SW1hZ2VuPC90ZXh0Pjwvc3ZnPg=='">
                    <div class="image-name">${img.filename}</div>
                `;
                
                container.appendChild(div);
            });
        }
        
        function selectSpecies(species, element) {
            // Remover selección anterior
            document.querySelectorAll('.species-item.selected').forEach(el => {
                el.classList.remove('selected');
            });
            
            // Seleccionar nuevo
            element.classList.add('selected');
            selectedSpecies = species;
            
            document.getElementById('selectedSpeciesInfo').innerHTML = `
                <strong>${species.scientific_name}</strong><br>
                <small>${species.common_name || 'Sin nombre común'}</small><br>
                <small>ID: ${species.id}</small>
            `;
            
            updateAssociateButton();
        }
        
        function selectImage(image, element) {
            // Remover selección anterior
            document.querySelectorAll('.image-item.selected').forEach(el => {
                el.classList.remove('selected');
            });
            
            // Seleccionar nuevo
            element.classList.add('selected');
            selectedImage = image;
            
            document.getElementById('selectedImageInfo').innerHTML = `
                <img src="${image.path}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;"><br>
                <strong>${image.filename}</strong><br>
                <small>${image.category}</small>
            `;
            
            updateAssociateButton();
        }
        
        function updateAssociateButton() {
            const btn = document.getElementById('associateBtn');
            btn.disabled = !(selectedSpecies && selectedImage);
        }
        
        async function associateImageWithSpecies() {
            if (!selectedSpecies || !selectedImage) return;
            
            const statusDiv = document.getElementById('statusMessage');
            statusDiv.innerHTML = '<div style="color: #2E8B57;">🔄 Procesando asociación...</div>';
            
            try {
                const response = await fetch('associate_image.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        species_id: selectedSpecies.id,
                        image_path: selectedImage.relative_path
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    statusDiv.innerHTML = `
                        <div class="status-message success">
                            ✅ Asociación exitosa: ${selectedSpecies.scientific_name} ↔ ${selectedImage.filename}
                        </div>
                    `;
                    
                    // Limpiar selecciones
                    selectedSpecies = null;
                    selectedImage = null;
                    document.getElementById('selectedSpeciesInfo').innerHTML = 'Ninguna seleccionada';
                    document.getElementById('selectedImageInfo').innerHTML = 'Ninguna seleccionada';
                    document.querySelectorAll('.selected').forEach(el => el.classList.remove('selected'));
                    updateAssociateButton();
                    
                    // Recargar listas
                    setTimeout(() => {
                        loadSpeciesWithoutImages(currentSpeciesPage);
                    }, 1000);
                } else {
                    statusDiv.innerHTML = `
                        <div class="status-message error">
                            ❌ Error: ${result.error}
                        </div>
                    `;
                }
            } catch (error) {
                statusDiv.innerHTML = `
                    <div class="status-message error">
                        ❌ Error de conexión: ${error.message}
                    </div>
                `;
            }
        }
        
        function displaySpeciesPagination(totalPages, currentPage) {
            const container = document.getElementById('speciesPagination');
            container.innerHTML = '';
            
            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.className = `page-btn ${i === currentPage ? 'active' : ''}`;
                btn.textContent = i;
                btn.onclick = () => loadSpeciesWithoutImages(i);
                container.appendChild(btn);
            }
        }
        
        function displayImagesPagination(totalPages, currentPage) {
            const container = document.getElementById('imagesPagination');
            container.innerHTML = '';
            
            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.className = `page-btn ${i === currentPage ? 'active' : ''}`;
                btn.textContent = i;
                btn.onclick = () => loadAvailableImages(i);
                container.appendChild(btn);
            }
        }
    </script>
</body>
</html>