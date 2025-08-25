@extends('adminlte::page')

@section('title', 'Manual de Usuario')

@section('content_header')
    <h1>Manual de Usuario</h1>
@stop

@section('content')
<div class="row">
    <!-- Sidebar de Navegación -->
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Contenido</h3>
            </div>
            <div class="card-body p-0">
                <nav id="manual-nav" class="nav flex-column nav-pills">
                    <a class="nav-link active" href="#inicio">Inicio</a>
                    <a class="nav-link" href="#biodiversidad">Gestión de Biodiversidad</a>
                    <a class="nav-link" href="#publicaciones">Gestión de Publicaciones</a>
                    <a class="nav-link" href="#reportes">Reportes y Estadísticas</a>
                    <a class="nav-link" href="#mantenimiento">Mantenimiento de BD</a>
                    <a class="nav-link" href="#configuracion">Configuración</a>
                    <a class="nav-link" href="#perfil">Mi Perfil</a>
                </nav>
            </div>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <!-- Inicio -->
                <section id="inicio" class="manual-section">
                    <h2>Inicio</h2>
                    <p>Bienvenido al Sistema de Gestión de Biodiversidad y Publicaciones Científicas. Este manual le guiará a través de las principales funcionalidades del sistema.</p>
                    
                    <h4>Panel de Control</h4>
                    <p>El panel de control muestra un resumen de:</p>
                    <ul>
                        <li>Total de especies registradas</li>
                        <li>Total de publicaciones</li>
                        <li>Gráficos de distribución por reino</li>
                        <li>Estado de conservación de especies</li>
                        <li>Últimas publicaciones añadidas</li>
                    </ul>
                </section>

                <!-- Gestión de Biodiversidad -->
                <section id="biodiversidad" class="manual-section">
                    <h2>Gestión de Biodiversidad</h2>
                    
                    <h4>Registro de Especies</h4>
                    <p>Para registrar una nueva especie:</p>
                    <ol>
                        <li>Vaya a "Gestión de Biodiversidad" > "Añadir Nueva Especie"</li>
                        <li>Complete el formulario con la información requerida:
                            <ul>
                                <li>Nombre científico</li>
                                <li>Nombre común</li>
                                <li>Reino</li>
                                <li>Estado de conservación</li>
                                <li>Descripción</li>
                                <li>Imágenes</li>
                            </ul>
                        </li>
                        <li>Haga clic en "Guardar"</li>
                    </ol>

                    <h4>Gestión de Categorías</h4>
                    <p>Las categorías ayudan a organizar las especies. Puede:</p>
                    <ul>
                        <li>Crear nuevas categorías</li>
                        <li>Editar categorías existentes</li>
                        <li>Eliminar categorías (si no tienen especies asociadas)</li>
                    </ul>
                </section>

                <!-- Gestión de Publicaciones -->
                <section id="publicaciones" class="manual-section">
                    <h2>Gestión de Publicaciones</h2>
                    
                    <h4>Añadir Publicación</h4>
                    <p>Para añadir una nueva publicación científica:</p>
                    <ol>
                        <li>Acceda a "Gestión de Publicaciones" > "Nueva Publicación"</li>
                        <li>Complete los campos:
                            <ul>
                                <li>Título</li>
                                <li>Autores</li>
                                <li>Fecha de publicación</li>
                                <li>DOI</li>
                                <li>Resumen</li>
                                <li>PDF del documento</li>
                            </ul>
                        </li>
                        <li>Haga clic en "Publicar"</li>
                    </ol>
                </section>

                <!-- Reportes y Estadísticas -->
                <section id="reportes" class="manual-section">
                    <h2>Reportes y Estadísticas</h2>
                    
                    <h4>Generación de Reportes</h4>
                    <p>El sistema permite generar diversos tipos de reportes:</p>
                    <ul>
                        <li>Estadísticas generales</li>
                        <li>Reportes de biodiversidad (PDF/Excel)</li>
                        <li>Reportes de publicaciones (PDF/Excel)</li>
                    </ul>

                    <p>Para generar un reporte:</p>
                    <ol>
                        <li>Seleccione el tipo de reporte</li>
                        <li>Configure los filtros deseados</li>
                        <li>Elija el formato de salida</li>
                        <li>Haga clic en "Generar Reporte"</li>
                    </ol>
                </section>

                <!-- Mantenimiento de BD -->
                <section id="mantenimiento" class="manual-section">
                    <h2>Mantenimiento de Base de Datos</h2>
                    
                    <h4>Copias de Seguridad</h4>
                    <p>Para realizar un backup:</p>
                    <ol>
                        <li>Vaya a "Mantenimiento de BD" > "Backup"</li>
                        <li>Seleccione las tablas a respaldar</li>
                        <li>Haga clic en "Crear Backup"</li>
                    </ol>

                    <h4>Restauración</h4>
                    <p>Para restaurar un backup:</p>
                    <ol>
                        <li>Acceda a "Mantenimiento de BD" > "Restaurar"</li>
                        <li>Seleccione el archivo de backup</li>
                        <li>Confirme la restauración</li>
                    </ol>
                </section>

                <!-- Configuración -->
                <section id="configuracion" class="manual-section">
                    <h2>Configuración</h2>
                    
                    <h4>Configuración General</h4>
                    <p>Permite ajustar:</p>
                    <ul>
                        <li>Nombre del sitio</li>
                        <li>Descripción</li>
                        <li>Email de contacto</li>
                        <li>Elementos por página</li>
                        <li>Modo de mantenimiento</li>
                    </ul>

                    <h4>Gestión de Usuarios</h4>
                    <p>Administre usuarios del sistema:</p>
                    <ul>
                        <li>Crear nuevos usuarios</li>
                        <li>Editar usuarios existentes</li>
                        <li>Asignar roles y permisos</li>
                    </ul>
                </section>

                <!-- Mi Perfil -->
                <section id="perfil" class="manual-section">
                    <h2>Mi Perfil</h2>
                    
                    <h4>Gestión de Perfil</h4>
                    <p>En esta sección puede:</p>
                    <ul>
                        <li>Actualizar información personal</li>
                        <li>Cambiar contraseña</li>
                        <li>Actualizar foto de perfil</li>
                    </ul>

                    <h4>Soporte</h4>
                    <p>Para obtener ayuda:</p>
                    <ul>
                        <li>Consulte este manual</li>
                        <li>Use el formulario de contacto</li>
                        <li>Revise la sección "Acerca del Sistema"</li>
                    </ul>
                </section>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    /* Estilos para la navegación */
    #manual-nav {
        position: sticky;
        top: 20px;
    }
    .nav-link {
        color: #495057;
        border-radius: 0;
        padding: 0.5rem 1rem;
    }
    .nav-link:hover {
        background-color: #f8f9fa;
    }
    .nav-link.active {
        background-color: #007bff;
        color: white;
    }

    /* Estilos para las secciones */
    .manual-section {
        padding: 20px 0;
        border-bottom: 1px solid #dee2e6;
    }
    .manual-section:last-child {
        border-bottom: none;
    }
    .manual-section h2 {
        color: #007bff;
        margin-bottom: 1rem;
    }
    .manual-section h4 {
        color: #495057;
        margin-top: 1.5rem;
        margin-bottom: 1rem;
    }
    .manual-section ul, 
    .manual-section ol {
        padding-left: 20px;
    }
    .manual-section li {
        margin-bottom: 0.5rem;
    }
</style>
@stop

@section('js')
<script>
    // Smooth scroll para los enlaces de navegación
    document.querySelectorAll('#manual-nav a').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const section = document.querySelector(this.getAttribute('href'));
            section.scrollIntoView({ behavior: 'smooth' });
        });
    });

    // Actualizar enlace activo al hacer scroll
    window.addEventListener('scroll', function() {
        const sections = document.querySelectorAll('.manual-section');
        const navLinks = document.querySelectorAll('#manual-nav a');

        let current = '';

        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            if (window.pageYOffset >= sectionTop - 100) {
                current = '#' + section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === current) {
                link.classList.add('active');
            }
        });
    });
</script>
@stop