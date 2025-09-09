# Migración de Taxonomía desde bioserver_grt

Este documento describe el proceso para migrar datos de taxonomía (clases, órdenes, familias) desde la base de datos `bioserver_grt` al proyecto actual de gestión de biodiversidad.

## Descripción General

La migración transfiere datos de las siguientes tablas:
- **clases**: Clasificación taxonómica de clases
- **ordens**: Órdenes taxonómicos
- **familias**: Familias taxonómicas

## Estructura de Datos

### Tabla `clases`
- `idclase` (PK): ID único de la clase
- `nombre`: Nombre de la clase
- `definicion`: Definición o descripción
- `idreino` (FK): Referencia al reino (opcional)
- `created_at`, `updated_at`: Timestamps

### Tabla `ordens`
- `idorden` (PK): ID único del orden
- `nombre`: Nombre del orden
- `definicion`: Definición o descripción
- `idclase` (FK): Referencia a la clase
- `created_at`, `updated_at`: Timestamps

### Tabla `familias`
- `idfamilia` (PK): ID único de la familia
- `nombre`: Nombre de la familia
- `definicion`: Definición o descripción
- `idorden` (FK): Referencia al orden
- `created_at`, `updated_at`: Timestamps

## Configuración Previa

### 1. Variables de Entorno

Agregar las siguientes variables al archivo `.env`:

```env
# Configuración de base de datos bioserver_grt para migración
BIOSERVER_DB_HOST=127.0.0.1
BIOSERVER_DB_PORT=3306
BIOSERVER_DB_DATABASE=bioserver_grt
BIOSERVER_DB_USERNAME=root
BIOSERVER_DB_PASSWORD=tu_password
```

### 2. Verificar Conexión

Antes de ejecutar la migración, verificar que la conexión a `bioserver_grt` funcione correctamente.

## Comandos Disponibles

### 1. Inspeccionar Tablas de bioserver_grt

```bash
# Inspeccionar todas las tablas relevantes
php artisan inspect:bioserver-tables

# Inspeccionar una tabla específica
php artisan inspect:bioserver-tables clases

# Inspeccionar con conteo de registros
php artisan inspect:bioserver-tables --count
```

### 2. Ejecutar Migración

```bash
# Modo de prueba (no guarda cambios)
php artisan migrate:taxonomy-bioserver --dry-run

# Ejecutar migración real
php artisan migrate:taxonomy-bioserver
```

## Proceso de Migración

### Orden de Ejecución

1. **Clases**: Se migran primero ya que son referenciadas por órdenes
2. **Órdenes**: Se migran después, referenciando las clases migradas
3. **Familias**: Se migran al final, referenciando los órdenes migrados

### Lógica de Mapeo

#### Mapeo de Reinos
- Si existe `idreino` en bioserver_grt, se busca el reino correspondiente por nombre
- Si no se encuentra, se asigna `null`

#### Mapeo de Clases
- Para órdenes: se busca la clase por nombre en el proyecto actual
- Si no se encuentra, se omite el registro

#### Mapeo de Órdenes
- Para familias: se busca el orden por nombre en el proyecto actual
- Si no se encuentra, se omite el registro

### Manejo de Duplicados

- **Verificación por nombre**: Se verifica si ya existe un registro con el mismo nombre
- **Omisión automática**: Los duplicados se omiten automáticamente
- **Reporte detallado**: Se muestra un resumen de registros migrados vs omitidos

## Validaciones y Verificaciones

### Pre-migración

1. **Conexión a bioserver_grt**: Verificar que la base de datos sea accesible
2. **Existencia de tablas**: Confirmar que las tablas `clases`, `ordens`, `familias` existen
3. **Estructura de datos**: Validar que las columnas esperadas estén presentes

### Post-migración

1. **Conteo de registros**: Comparar cantidad de registros antes y después
2. **Integridad referencial**: Verificar que las relaciones FK estén correctas
3. **Datos de ejemplo**: Revisar algunos registros migrados manualmente

## Comandos de Verificación

```bash
# Verificar conteo de registros migrados
php artisan tinker
>>> App\Models\Clase::count()
>>> App\Models\Orden::count()
>>> App\Models\Familia::count()

# Verificar relaciones
>>> App\Models\Orden::with('clase')->first()
>>> App\Models\Familia::with('orden.clase')->first()
```

## Solución de Problemas

### Error de Conexión

```
Error al conectar con bioserver_grt: SQLSTATE[HY000] [2002]
```

**Solución**:
1. Verificar que el servidor MySQL esté ejecutándose
2. Confirmar credenciales en el archivo `.env`
3. Verificar que la base de datos `bioserver_grt` exista

### Tabla No Encontrada

```
No se encontró la tabla "clases" en bioserver_grt
```

**Solución**:
1. Verificar que la base de datos sea la correcta
2. Confirmar nombres de tablas en bioserver_grt
3. Verificar permisos de usuario de base de datos

### Error de Mapeo

```
No se pudo mapear la clase para el orden 'NombreOrden'
```

**Solución**:
1. Ejecutar primero la migración de clases
2. Verificar que los nombres coincidan exactamente
3. Revisar datos manualmente si es necesario

## Rollback

En caso de necesitar revertir la migración:

```sql
-- Eliminar registros migrados (usar con precaución)
DELETE FROM familias WHERE definicion = 'Migrado desde bioserver_grt';
DELETE FROM ordens WHERE definicion = 'Migrado desde bioserver_grt';
DELETE FROM clases WHERE definicion = 'Migrado desde bioserver_grt';
```

## Notas Importantes

1. **Backup**: Siempre hacer backup de la base de datos antes de ejecutar la migración
2. **Modo de prueba**: Usar `--dry-run` para verificar el proceso antes de la ejecución real
3. **Monitoreo**: Revisar los logs durante la migración para detectar problemas
4. **Validación**: Verificar la integridad de los datos después de la migración

## Archivos Relacionados

- `app/Console/Commands/MigrateTaxonomyFromBioserver.php`: Comando principal de migración
- `app/Console/Commands/InspectBioserverTables.php`: Comando de inspección
- `app/Models/Clase.php`: Modelo de clases
- `app/Models/Orden.php`: Modelo de órdenes
- `app/Models/Familia.php`: Modelo de familias

## Contacto

Para soporte técnico o consultas sobre la migración, contactar al equipo de desarrollo.