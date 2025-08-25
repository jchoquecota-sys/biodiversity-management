<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Biodiversidad - Sistema de Gestión')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-green: #2d5016;
            --secondary-green: #4a7c59;
            --light-green: #8fbc8f;
            --accent-orange: #ff6b35;
            --text-dark: #2c3e50;
            --text-light: #7f8c8d;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
            color: white;
            padding: 60px 0;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section.hero-with-slider {
            background: none;
            padding: 0;
        }
        
        .hero-section.hero-with-slider::before {
            display: none;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="leaves" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><path d="M10,2 Q15,10 10,18 Q5,10 10,2" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23leaves)"/></svg>') repeat;
            opacity: 0.1;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
        }
        
        .stats-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .stats-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
            color: white;
        }
        
        .stats-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary-green);
            margin-bottom: 10px;
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-green);
            margin-bottom: 50px;
            text-align: center;
            position: relative;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--accent-orange);
            border-radius: 2px;
        }
        
        .species-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        
        .species-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .species-image {
            height: 250px;
            background: linear-gradient(45deg, var(--light-green), var(--secondary-green));
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        
        .conservation-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
        }
        
        .btn-primary {
            background: var(--primary-green);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: var(--secondary-green);
            transform: translateY(-2px);
        }
        
        .btn-outline-primary {
            color: var(--primary-green);
            border: 2px solid var(--primary-green);
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary:hover {
            background: var(--primary-green);
            border-color: var(--primary-green);
            transform: translateY(-2px);
        }
        
        .footer {
            background: var(--primary-green);
            color: white;
            padding: 50px 0 30px;
        }
        
        .navbar {
            background: white !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-nav .nav-link {
            color: var(--text-dark) !important;
            font-weight: 500;
            margin: 0 10px;
            transition: color 0.3s ease;
        }
        
        .navbar-nav .nav-link:hover {
            color: var(--primary-green) !important;
        }
        
        .search-section {
            background: #f8f9fa;
            padding: 80px 0;
        }
        
        .search-box {
            background: white;
            border-radius: 50px;
            padding: 20px 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
        }
        
        .search-btn {
            background: var(--accent-orange);
            border: none;
            border-radius: 50px;
            padding: 15px 30px;
            color: white;
            font-weight: 600;
        }
        
        .search-btn:hover {
            background: #e55a2b;
            color: white;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                @php
                    $logoPath = \App\Models\Setting::get('site_logo', null);
                    $logoAlt = \App\Models\Setting::get('site_logo_alt', 'Biodiversidad');
                @endphp
                @if($logoPath && file_exists(public_path('storage/' . $logoPath)))
                    <img src="{{ asset('storage/' . $logoPath) }}" alt="{{ $logoAlt }}" style="height: 30px; margin-right: 8px;">
                @else
                    <i class="fas fa-leaf text-success me-2"></i>
                @endif
                {{ $logoAlt }}
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @php
                        $mainMenuJson = \App\Models\Setting::get('main_menu', '[]');
                        $menuItems = json_decode($mainMenuJson, true) ?: [];
                        
                        // Ordenar por orden y filtrar solo los activos
                        $menuItems = collect($menuItems)
                            ->where('is_active', true)
                            ->sortBy('order');
                    @endphp
                    
                    @if($menuItems->isEmpty())
                        <!-- Default menu items if no custom menu is configured -->
                        <li class="nav-item">
                            <a class="nav-link" href="/">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/biodiversity">Biodiversidad</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/publications">Publicaciones</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin">Panel Admin</a>
                        </li>
                    @else
                        @foreach($menuItems as $menuItem)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ $menuItem['url'] }}">{{ $menuItem['text'] }}</a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-leaf me-2"></i>Biodiversidad</h5>
                    <p>Sistema integral de gestión y consulta de información sobre la biodiversidad.</p>
                </div>
                <div class="col-md-4">
                    <h5>Enlaces Rápidos</h5>
                    <ul class="list-unstyled">
                        <li><a href="/biodiversity" class="text-light">Explorar Especies</a></li>
                        <li><a href="/publications" class="text-light">Publicaciones</a></li>
                        <li><a href="#" class="text-light">Acerca de</a></li>
                        <li><a href="#" class="text-light">Contacto</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Síguenos</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-light"><i class="fab fa-facebook fa-2x"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-twitter fa-2x"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-instagram fa-2x"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-youtube fa-2x"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p>&copy; {{ date('Y') }} Sistema de Gestión de Biodiversidad. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>