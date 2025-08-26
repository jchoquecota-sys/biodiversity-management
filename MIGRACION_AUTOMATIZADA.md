# Sistema de Migración Automatizada de Datos

## Descripción General

Este sistema automatiza la migración de datos de publicación desde la tabla `biodiversity_categories` hacia las tablas `publications` y `biodiversity_category_publication`, eliminando la redundancia de datos y normalizando la estructura de la base de datos.

## Componentes del Sistema

### 1. Comando Artisan: `MigratePublicationData`

**Ubicación:** `app/Console/Commands/MigratePublicationData.php`

**Funcionalidades:**
- Migra datos de publicación de `biodiversity_categories` a `publications`
- Crea relaciones en la tabla pivot `biodiversity_category_publication`
- Evita duplicados verificando publicaciones existentes
- Incluye modo de vista previa (`--dry-run`)
- Permite ejecución forzada (`--force`)
- Muestra barra de progreso durante la migración

**Uso:**
```bash
# Vista previa (sin cambios)
php artisan migrate:publication-data --dry-run

# Ejecución completa
php artisan migrate:publication-data --force

# Ejecución interactiva
php artisan migrate:publication-data
```

### 2. Migración Automática: `migrate_publication_data_before_removal`

**Ubicación:** `database/migrations/2025_08_26_134747_migrate_publication_data_before_removal.php`

**Funcionalidades:**
- Se ejecuta automáticamente antes de eliminar campos de publicación
- Verifica la existencia de campos de publicación
- Ejecuta el comando de migración automáticamente
- Registra todo el proceso en tabla de log
- Maneja errores y rollbacks

**Ejecución:**
```bash
php artisan migrate --path=database/migrations/2025_08_26_134747_migrate_publication_data_before_removal.php
```

### 3. Tabla de Log: `temp_migration_log`

**Estructura:**
- `id`: Identificador único
- `operation`: Tipo de operación realizada
- `details`: Detalles de la operación
- `success`: Estado de éxito/error
- `created_at`, `updated_at`: Timestamps

**Consulta de logs:**
```sql
SELECT * FROM temp_migration_log ORDER BY created_at DESC;
```

## Proceso de Migración

### Paso 1: Análisis de Datos
El sistema identifica:
- Categorías con datos de publicación
- Publicaciones duplicadas existentes
- Relaciones ya establecidas

### Paso 2: Migración de Datos
Para cada categoría con datos de publicación:
1. Busca publicación existente por DOI o combinación título/autor/año
2. Si no existe, crea nueva publicación
3. Establece relación en tabla pivot
4. Registra el proceso

### Paso 3: Verificación
- Confirma que todos los datos fueron migrados
- Verifica integridad de relaciones
- Genera reporte de resultados

## Campos Migrados

**Desde `biodiversity_categories`:**
- `autor_publicacion` → `publications.author`
- `titulo_publicacion` → `publications.title`
- `revista_publicacion` → `publications.journal`
- `año_publicacion` → `publications.publication_year`
- `doi` → `publications.doi`

**Relación establecida en `biodiversity_category_publication`:**
- `biodiversity_category_id`
- `publication_id`
- `relevant_excerpt` (null por defecto)
- `page_reference` (null por defecto)

## Seguridad y Validaciones

### Prevención de Duplicados
- Verificación por DOI único
- Verificación por combinación título/autor/año
- Reutilización de publicaciones existentes

### Manejo de Errores
- Transacciones de base de datos
- Rollback automático en caso de error
- Logging detallado de errores
- Validación de datos antes de migración

### Modo Seguro
- Vista previa sin cambios (`--dry-run`)
- Confirmación requerida para ejecución
- Backup automático recomendado

## Monitoreo y Logs

### Tipos de Operaciones Registradas
- `migration_start`: Inicio de migración
- `data_migration`: Migración de datos completada
- `migration_error`: Error durante migración
- `no_migration_needed`: No hay datos para migrar
- `rollback_warning`: Advertencia de rollback

### Consulta de Estado
```php
// Ver logs recientes
DB::table('temp_migration_log')
  ->orderBy('created_at', 'desc')
  ->get();

// Verificar éxito de migración
DB::table('temp_migration_log')
  ->where('operation', 'data_migration')
  ->where('success', true)
  ->exists();
```

## Instrucciones de Uso Completo

### Migración Manual
```bash
# 1. Vista previa
php artisan migrate:publication-data --dry-run

# 2. Ejecución (si todo está correcto)
php artisan migrate:publication-data --force

# 3. Verificar logs
php check_migration_log.php
```

### Migración Automática
```bash
# Ejecutar migración que incluye automatización
php artisan migrate --path=database/migrations/2025_08_26_134747_migrate_publication_data_before_removal.php

# Continuar con migración que elimina campos
php artisan migrate --path=database/migrations/2025_08_25_190455_remove_publication_fields_from_biodiversity_categories_table.php
```

## Rollback y Recuperación

### En caso de problemas:
1. Los datos migrados permanecen en `publications` y `biodiversity_category_publication`
2. Los campos originales pueden restaurarse con rollback de migración
3. La tabla de log mantiene registro completo del proceso

### Rollback de migración:
```bash
php artisan migrate:rollback --path=database/migrations/2025_08_26_134747_migrate_publication_data_before_removal.php
```

## Mantenimiento

### Limpieza de Logs
```sql
-- Eliminar logs antiguos (opcional)
DELETE FROM temp_migration_log WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY);
```

### Verificación de Integridad
```sql
-- Verificar que todas las categorías tienen sus publicaciones migradas
SELECT bc.id, bc.name 
FROM biodiversity_categories bc
LEFT JOIN biodiversity_category_publication bcp ON bc.id = bcp.biodiversity_category_id
WHERE bcp.biodiversity_category_id IS NULL
AND (bc.autor_publicacion IS NOT NULL OR bc.titulo_publicacion IS NOT NULL);
```

## Notas Importantes

1. **Backup:** Siempre realizar backup antes de ejecutar migraciones
2. **Entorno:** Probar primero en entorno de desarrollo
3. **Monitoreo:** Revisar logs después de cada ejecución
4. **Performance:** La migración puede tomar tiempo con grandes volúmenes de datos
5. **Integridad:** Verificar relaciones después de la migración

## Soporte y Troubleshooting

### Problemas Comunes

**Error: "No publication fields found"**
- Los campos ya fueron eliminados
- Verificar estructura de tabla actual

**Error: "Duplicate entry"**
- Publicación ya existe
- El sistema debería manejar esto automáticamente

**Error: "Foreign key constraint"**
- Problema con relaciones de tabla
- Verificar integridad de datos

### Contacto
Para soporte adicional, consultar la documentación del proyecto o contactar al equipo de desarrollo.