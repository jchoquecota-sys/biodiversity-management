@extends('adminlte::page')

@section('title', 'Manual de Usuario')

@section('content_header')
    <h1>Manual de Usuario</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Contenido</h3>
            </div>
            <div class="card-body p-0">
                <nav class="nav nav-pills flex-column">
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

    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <!-- Inicio -->
                <section id="inicio" class="mb-5">
                    <h2>Inicio</h2>
                    <p>Bienvenido al sistema de gestión de biodiversidad. Este manual le guiará a través de las principales funcionalidades del sistema.</p>
                    
                    <h4>Panel de Control</h4>
                    <p>El panel de control muestra un resumen de:</p>
                    <ul>
                        <li>Total de especies registradas</li>
                        <li>Total de publicaciones científicas</li>
                        <li>Distribución por reino</li>
                        <li>Estado de conservación</li>
                    </ul>
                </section>

                <!-- Gestión de Biodiversidad -->
                <section id="biodiversidad" class="mb-5">
                    <h2>Gestión de Biodiversidad</h2>
                    
                    <h4>Registro de Especies</h4>
                    <p>Para registrar una nueva especie:</p>
                    <ol>
                        <li>Vaya a "Gestión de Biodiversidad" > "Agregar Nueva"</li>
                        <li>Complete los campos requeridos (nombre científico, reino, etc.)</li>
                        <li>Suba imágenes representativas</li>
                        <li>Guarde los cambios</li>
                    </ol>

                    <h4>Edición y Eliminación</h4>
                    <p>Puede editar o eliminar especies desde la lista principal. Las especies eliminadas se mueven a la papelera y pueden ser restauradas.</p>
                </section>

                <!-- Gestión de Publicaciones -->
                <section id="publicaciones" class="mb-5">
                    <h2>Gestión de Publicaciones</h2>
                    
                    <h4>Nueva Publicación</h4>
                    <p>Para agregar una nueva publicación científica:</p>
                    <ol>
                        <li>Acceda a "Gestión de Publicaciones" > "Agregar Nueva"</li>
                        <li>Ingrese título, autores y contenido</li>
                        <li>Vincule especies relacionadas</li>
                        <li>Adjunte documentos PDF si es necesario</li>
                    </ol>

                    <h4>Exportación</h4>
                    <p>Las publicaciones pueden exportarse en formato PDF o Excel desde la sección de reportes.</p>
                </section>

                <!-- Reportes y Estadísticas -->
                <section id="reportes" class="mb-5">
                    <h2>Reportes y Estadísticas</h2>
                    
                    <h4>Generación de Reportes</h4>
                    <p>El sistema permite generar:</p>
                    <ul>
                        <li>Reportes de biodiversidad filtrados por reino y estado de conservación</li>
                        <li>Reportes de publicaciones por fecha y autor</li>
                        <li>Estadísticas generales del sistema</li>
                    </ul>

                    <h4>Visualización de Datos</h4>
                    <p>Los datos se presentan en gráficos interactivos que muestran tendencias y distribuciones.</p>
                </section>

                <!-- Mantenimiento de BD -->
                <section id="mantenimiento" class="mb-5">
                    <h2>Mantenimiento de BD</h2>
                    
                    <h4>Respaldo de Datos</h4>
                    <p>Para realizar un respaldo:</p>
                    <ol>
                        <li>Vaya a "Mantenimiento de BD" > "Backup"</li>
                        <li>Seleccione las tablas a respaldar</li>
                        <li>Inicie el proceso</li>
                    </ol>

                    <h4>Restauración y Optimización</h4>
                    <p>El sistema permite restaurar backups previos y optimizar las tablas para mejor rendimiento.</p>
                </section>

                <!-- Configuración -->
                <section id="configuracion" class="mb-5">
                    <h2>Configuración</h2>
                    
                    <h4>Configuración General</h4>
                    <p>Permite ajustar:</p>
                    <ul>
                        <li>Nombre del sitio</li>
                        <li>Correo de contacto</li>
                        <li>Elementos por página</li>
                        <li>Modo de mantenimiento</li>
                    </ul>

                    <h4>Gestión de Usuarios</h4>
                    <p>Administre usuarios y sus roles desde el panel de configuración.</p>
                </section>

                <!-- Mi Perfil -->
                <section id="perfil" class="mb-5">
                    <h2>Mi Perfil</h2>
                    
                    <h4>Actualización de Datos</h4>
                    <p>En su perfil puede:</p>
                    <ul>
                        <li>Cambiar su foto de perfil</li>
                        <li>Actualizar información personal</li>
                        <li>Modificar contraseña</li>
                    </ul>

                    <h4>Soporte Técnico</h4>
                    <p>Use el formulario de contacto para reportar problemas o solicitar ayuda.</p>
                </section>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .nav-pills .nav-link {
        border-radius: 0;
        padding: 0.5rem 1rem;
    }
    .nav-pills .nav-link.active {
        background-color: #007bff;
    }
    section {
        scroll-margin-top: 20px;
    }
    h2 {
        color: #2c3e50;
        border-bottom: 2px solid #eee;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    h4 {
        color: #3498db;
        margin-top: 20px;
    }
    ul, ol {
        padding-left: 20px;
    }
</style>
@stop

@section('js')
<script>
    // Smooth scroll para los enlaces del menú
    document.querySelectorAll('nav a').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const section = document.querySelector(this.getAttribute('href'));
            section.scrollIntoView({ behavior: 'smooth' });

            // Actualizar clase active
            document.querySelectorAll('nav a').forEach(a => a.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Actualizar menú según la sección visible
    const sections = document.querySelectorAll('section');
    const navLinks = document.querySelectorAll('nav a');

    window.addEventListener('scroll', () => {
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (scrollY >= sectionTop - 200) {
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