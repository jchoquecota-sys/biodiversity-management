<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Diagnóstico de Sesión</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .status {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .test-button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px;
        }
        .test-button:hover {
            background-color: #0056b3;
        }
        #results {
            margin-top: 20px;
        }
        .timestamp {
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Diagnóstico de Sesión - Dashboard Biodiversidad</h1>
        
        <div class="status info">
            <strong>Estado Actual:</strong><br>
            Usuario: {{ Auth::check() ? Auth::user()->email : 'No autenticado' }}<br>
            Session ID: {{ Session::getId() }}<br>
            CSRF Token: {{ csrf_token() }}<br>
            Timestamp: {{ now()->format('Y-m-d H:i:s') }}
        </div>
        
        @if(Auth::check())
            <div class="status success">
                ✅ Usuario autenticado correctamente
            </div>
        @else
            <div class="status error">
                ❌ Usuario no autenticado
            </div>
        @endif
        
        <h2>Tests de Sesión</h2>
        
        <button class="test-button" onclick="testDashboard()">Test Dashboard</button>
        <button class="test-button" onclick="testAjaxRequest()">Test AJAX</button>
        <button class="test-button" onclick="testSessionPersistence()">Test Persistencia</button>
        <button class="test-button" onclick="refreshPage()">Refrescar Página</button>
        <button class="test-button" onclick="clearResults()">Limpiar Resultados</button>
        
        <div id="results"></div>
        
        <h2>Información del Navegador</h2>
        <div class="status info">
            <strong>User Agent:</strong> <span id="userAgent"></span><br>
            <strong>Cookies Habilitadas:</strong> <span id="cookiesEnabled"></span><br>
            <strong>Local Storage:</strong> <span id="localStorage"></span><br>
            <strong>Session Storage:</strong> <span id="sessionStorage"></span><br>
            <strong>URL Actual:</strong> <span id="currentUrl"></span>
        </div>
        
        <h2>Cookies del Navegador</h2>
        <div class="status info">
            <pre id="cookies"></pre>
        </div>
    </div>
    
    <script>
        // Información del navegador
        document.getElementById('userAgent').textContent = navigator.userAgent;
        document.getElementById('cookiesEnabled').textContent = navigator.cookieEnabled ? 'Sí' : 'No';
        document.getElementById('currentUrl').textContent = window.location.href;
        
        // Test de almacenamiento
        try {
            localStorage.setItem('test', 'test');
            localStorage.removeItem('test');
            document.getElementById('localStorage').textContent = 'Disponible';
        } catch(e) {
            document.getElementById('localStorage').textContent = 'No disponible';
        }
        
        try {
            sessionStorage.setItem('test', 'test');
            sessionStorage.removeItem('test');
            document.getElementById('sessionStorage').textContent = 'Disponible';
        } catch(e) {
            document.getElementById('sessionStorage').textContent = 'No disponible';
        }
        
        // Mostrar cookies
        document.getElementById('cookies').textContent = document.cookie || 'No hay cookies visibles';
        
        function addResult(message, type = 'info') {
            const results = document.getElementById('results');
            const div = document.createElement('div');
            div.className = `status ${type}`;
            div.innerHTML = `<span class="timestamp">[${new Date().toLocaleTimeString()}]</span> ${message}`;
            results.appendChild(div);
            results.scrollTop = results.scrollHeight;
        }
        
        function testDashboard() {
            addResult('🔄 Probando acceso al dashboard...');
            
            fetch('/admin', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (response.ok) {
                    addResult('✅ Dashboard accesible (Status: ' + response.status + ')', 'success');
                } else if (response.status === 302 || response.status === 401) {
                    addResult('❌ Sesión perdida - Redirigido a login (Status: ' + response.status + ')', 'error');
                } else {
                    addResult('⚠️ Respuesta inesperada (Status: ' + response.status + ')', 'error');
                }
            })
            .catch(error => {
                addResult('❌ Error de red: ' + error.message, 'error');
            });
        }
        
        function testAjaxRequest() {
            addResult('🔄 Probando request AJAX...');
            
            fetch('/admin/biodiversity', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (response.ok) {
                    addResult('✅ Request AJAX exitoso (Status: ' + response.status + ')', 'success');
                } else if (response.status === 401) {
                    addResult('❌ Request AJAX no autorizado - Sesión perdida (Status: ' + response.status + ')', 'error');
                } else {
                    addResult('⚠️ Request AJAX falló (Status: ' + response.status + ')', 'error');
                }
            })
            .catch(error => {
                addResult('❌ Error en AJAX: ' + error.message, 'error');
            });
        }
        
        function testSessionPersistence() {
            addResult('🔄 Probando persistencia de sesión...');
            
            // Hacer múltiples requests rápidos
            const requests = [];
            for (let i = 0; i < 5; i++) {
                requests.push(
                    fetch('/admin', {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        credentials: 'same-origin'
                    })
                );
            }
            
            Promise.all(requests)
                .then(responses => {
                    const statuses = responses.map(r => r.status);
                    const allOk = statuses.every(s => s === 200);
                    
                    if (allOk) {
                        addResult('✅ Sesión persistente en múltiples requests', 'success');
                    } else {
                        addResult('❌ Sesión inconsistente: ' + statuses.join(', '), 'error');
                    }
                })
                .catch(error => {
                    addResult('❌ Error en test de persistencia: ' + error.message, 'error');
                });
        }
        
        function refreshPage() {
            addResult('🔄 Refrescando página...');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }
        
        function clearResults() {
            document.getElementById('results').innerHTML = '';
        }
        
        // Auto-test al cargar la página
        window.addEventListener('load', function() {
            addResult('📋 Página de diagnóstico cargada');
            testDashboard();
        });
    </script>
</body>
</html>