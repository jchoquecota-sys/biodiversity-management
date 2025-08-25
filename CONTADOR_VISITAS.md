# Sistema de Contador de Visitas

## Descripción

Se ha implementado un sistema completo de contador de visitas para el proyecto de gestión de biodiversidad. Este sistema registra automáticamente las visitas a todas las páginas del sitio web y proporciona estadísticas detalladas.

## Características Implementadas

### 1. Registro Automático de Visitas
- **Middleware**: `TrackPageVisits` registra automáticamente cada visita
- **Filtros inteligentes**: Excluye APIs, assets, y rutas administrativas
- **Prevención de duplicados**: Evita registrar múltiples visitas de la misma sesión en 5 minutos
- **Información capturada**:
  - URL completa
  - Dirección IP del visitante
  - User Agent del navegador
  - ID de sesión
  - ID de usuario (si está autenticado)
  - Timestamp de la visita

### 2. Modelo de Datos
- **Tabla**: `page_visits`
- **Campos**: id, url, ip_address, user_agent, session_id, user_id, timestamps
- **Índices**: Optimizado para consultas rápidas por URL y fecha
- **Relaciones**: Conectado con la tabla de usuarios

### 3. Componentes Blade Reutilizables

#### Contador Simple
```blade
<x-visit-counter />
```

#### Contador con Visitantes Únicos
```blade
<x-visit-counter show-unique="true" />
```

#### Estadísticas Completas del Sitio
```blade
<x-site-stats />
```

### 4. Helper de Estadísticas
- **Clase**: `VisitCounterHelper`
- **Funciones disponibles**:
  - `getCurrentPageVisits()`: Visitas de la página actual
  - `getPageVisits($url)`: Visitas de una URL específica
  - `getCurrentPageUniqueVisits()`: Visitantes únicos de la página actual
  - `getSiteStats()`: Estadísticas generales del sitio
  - `getTopPages($limit)`: Páginas más visitadas
  - `clearCache()`: Limpiar caché de contadores

### 5. Dashboard de Administración
- **Ubicación**: Panel de administración existente
- **Estadísticas mostradas**:
  - Total de visitas y visitantes únicos
  - Visitas diarias, semanales y mensuales
  - Gráfico de visitas diarias (últimos 30 días)
  - Lista de páginas más visitadas
  - Promedio diario de visitas

### 6. Sistema de Caché
- **Duración**: 5-15 minutos según el tipo de estadística
- **Optimización**: Reduce carga en la base de datos
- **Limpieza automática**: Comando para limpiar caché

### 7. Comando de Mantenimiento
```bash
# Limpiar visitas antiguas (por defecto 90 días)
php artisan visits:clean

# Limpiar visitas de más de 30 días
php artisan visits:clean --days=30

# Forzar limpieza sin confirmación
php artisan visits:clean --force
```

## Archivos Creados/Modificados

### Nuevos Archivos
1. `database/migrations/2025_08_25_213306_create_page_visits_table.php`
2. `app/Models/PageVisit.php`
3. `app/Http/Middleware/TrackPageVisits.php`
4. `app/Helpers/VisitCounterHelper.php`
5. `resources/views/components/visit-counter.blade.php`
6. `resources/views/components/site-stats.blade.php`
7. `app/Console/Commands/CleanOldVisits.php`

### Archivos Modificados
1. `app/Http/Kernel.php` - Registro del middleware
2. `app/Http/Controllers/Admin/DashboardController.php` - Estadísticas de visitas
3. `resources/views/admin/dashboard.blade.php` - Visualización de estadísticas
4. `resources/views/home.blade.php` - Demostración del contador

## Uso en las Vistas

### Mostrar contador simple
```blade
<x-visit-counter />
```

### Mostrar contador con visitantes únicos
```blade
<x-visit-counter show-unique="true" class="text-primary" />
```

### Mostrar estadísticas completas
```blade
<x-site-stats />
```

### Usar el helper en controladores
```php
use App\Helpers\VisitCounterHelper;

// Obtener visitas de la página actual
$visits = VisitCounterHelper::getCurrentPageVisits();

// Obtener estadísticas del sitio
$stats = VisitCounterHelper::getSiteStats();

// Obtener páginas más visitadas
$topPages = VisitCounterHelper::getTopPages(10);
```

## Configuración Automática

El sistema está configurado para:
- ✅ Registrar visitas automáticamente en todas las rutas web
- ✅ Excluir rutas de API y assets
- ✅ Usar caché para optimizar rendimiento
- ✅ Mostrar estadísticas en el dashboard de administración
- ✅ Proporcionar comandos de mantenimiento

## Rendimiento

- **Caché**: Las consultas frecuentes están en caché
- **Índices**: Base de datos optimizada con índices apropiados
- **Filtros**: Evita registrar visitas innecesarias
- **Lotes**: Limpieza de datos en lotes para evitar problemas de memoria

## Mantenimiento

Se recomienda ejecutar el comando de limpieza periódicamente:

```bash
# Agregar a cron para ejecutar mensualmente
0 0 1 * * cd /path/to/project && php artisan visits:clean --force
```

## Visualización

El sistema está activo y puede verse en:
- **Página principal**: http://localhost:8000/home (con estadísticas)
- **Dashboard admin**: Panel de administración con gráficos y estadísticas detalladas
- **Cualquier página**: Los contadores se pueden agregar usando los componentes Blade

¡El sistema de contador de visitas está completamente funcional y listo para usar!