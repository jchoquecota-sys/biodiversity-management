# Migración de Datos: bioserver_grt.biodiversidads → biodiversity_categories

Este documento describe el proceso de migración de datos desde la tabla `biodiversidads` de la base de datos externa `bioserver_grt` hacia la tabla local `biodiversity_categories`.

## Comando de Migración

Se ha creado el comando Artisan `migrate:biodiversidads-data` que permite migrar datos de forma segura y controlada.

### Sintaxis del Comando

```bash
php artisan migrate:biodiversidads-data [opciones]
```

### Opciones Disponibles

- `--host=HOST` : Host de la base de datos externa (requerido)
- `--port=PUERTO` : Puerto de la base de datos externa (por defecto: 3306)
- `--database=BD` : Nombre de la base de datos externa (por defecto: bioserver_grt)
- `--username=USUARIO` : Usuario de la base de datos externa (requerido)
- `--password=CONTRASEÑA` : Contraseña de la base de datos externa
- `--preview` : Solo mostrar vista previa sin ejecutar la migración
- `--force` : Forzar migración sin confirmación

## Mapeo de Campos

La migración mapea los siguientes campos:

| Campo Origen (biodiversidads) | Campo Destino (biodiversity_categories) | Tipo |
|-------------------------------|------------------------------------------|------|
| `id` | `id` | Clave primaria |
| `nombre` | `name` | Nombre común |
| `nombre_cientifico` | `scientific_name` | Nombre científico |
| `nombre_comun` | `common_name` | Nombre común alternativo |
| `descripcion` | `description` | Descripción |
| `estado_conservacion` | `conservation_status` | Estado de conservación |
| `reino` | `kingdom` | Reino taxonómico |
| `habitat` | `habitat` | Hábitat |
| `imagen` | `image_path` | Ruta de imagen principal |
| `imagen_2` | `image_path_2` | Ruta de imagen secundaria |
| `imagen_3` | `image_path_3` | Ruta de imagen terciaria |
| `imagen_4` | `image_path_4` | Ruta de imagen cuaternaria |

## Proceso de Migración

### 1. Vista Previa (Recomendado)

Antes de ejecutar la migración completa, es recomendable usar la opción `--preview`:

```bash
php artisan migrate:biodiversidads-data --host=tu_host --username=tu_usuario --preview
```

Esto mostrará:
- Número total de registros a migrar
- Vista previa de los primeros 10 registros
- Mapeo de campos
- Sin ejecutar cambios en la base de datos

### 2. Migración Completa

```bash
php artisan migrate:biodiversidads-data --host=tu_host --username=tu_usuario
```

O con todos los parámetros:

```bash
php artisan migrate:biodiversidads-data \
  --host=192.168.1.100 \
  --port=3306 \
  --database=bioserver_grt \
  --username=miusuario \
  --password=micontraseña
```

### 3. Migración Forzada (Sin Confirmación)

```bash
php artisan migrate:biodiversidads-data --host=tu_host --username=tu_usuario --force
```

## Características de Seguridad

### Validación de Duplicados
- El comando verifica si ya existe un registro con el mismo `name` y `scientific_name`
- Los registros duplicados se omiten automáticamente
- Se muestra un reporte de registros migrados vs omitidos

### Transacciones
- Toda la migración se ejecuta dentro de una transacción de base de datos
- Si ocurre un error, todos los cambios se revierten automáticamente
- Garantiza la integridad de los datos

### Manejo de Errores
- Errores individuales no detienen la migración completa
- Se registran y reportan todos los errores encontrados
- Barra de progreso para seguimiento visual

## Valores por Defecto

Cuando los campos origen están vacíos o son nulos, se asignan valores por defecto:

- `name`: "Sin nombre"
- `scientific_name`: "Sin nombre científico"
- `conservation_status`: "No evaluado"
- `kingdom`: "Desconocido"
- Otros campos opcionales: `null`

## Ejemplos de Uso

### Ejemplo 1: Vista Previa
```bash
php artisan migrate:biodiversidads-data --host=localhost --username=root --preview
```

### Ejemplo 2: Migración Interactiva
```bash
php artisan migrate:biodiversidads-data --host=192.168.1.50 --username=biodiversity_user
# El comando pedirá la contraseña de forma segura
```

### Ejemplo 3: Migración Automatizada
```bash
php artisan migrate:biodiversidads-data \
  --host=db.ejemplo.com \
  --username=admin \
  --password=secreto123 \
  --force
```

## Verificación Post-Migración

Después de la migración, puedes verificar los resultados:

```bash
# Contar registros migrados
php artisan tinker
>>> App\Models\BiodiversityCategory::count()

# Ver últimos registros migrados
>>> App\Models\BiodiversityCategory::latest()->take(5)->get(['name', 'scientific_name', 'kingdom'])
```

## Solución de Problemas

### Error de Conexión
- Verificar que el host y puerto sean correctos
- Confirmar que el usuario tenga permisos de lectura en la base de datos externa
- Verificar que la base de datos `bioserver_grt` exista

### Error de Tabla No Encontrada
- Confirmar que la tabla `biodiversidads` existe en la base de datos externa
- Verificar permisos de lectura en la tabla específica

### Registros No Migrados
- Revisar los mensajes de error específicos
- Verificar que los campos requeridos no estén vacíos
- Comprobar restricciones de integridad en la tabla destino

## Notas Importantes

1. **Backup**: Siempre realiza un backup de la base de datos local antes de ejecutar migraciones masivas
2. **Conexión de Red**: Asegúrate de tener conectividad estable con la base de datos externa
3. **Permisos**: El usuario de base de datos debe tener permisos de lectura en `bioserver_grt.biodiversidads`
4. **Memoria**: Para tablas muy grandes, considera ejecutar la migración en horarios de baja actividad
5. **Logs**: Revisa los logs de Laravel para información adicional sobre errores

---

**Fecha de creación**: $(date)
**Comando**: `migrate:biodiversidads-data`
**Archivo**: `app/Console/Commands/MigrateBiodiversidadsData.php`