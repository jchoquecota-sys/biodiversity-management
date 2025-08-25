# GitHub Actions Workflows

Este directorio contiene los workflows de GitHub Actions para automatizar el proceso de desarrollo, testing y deployment del proyecto de Gestión de Biodiversidad.

## Workflows Disponibles

### 1. CI (Integración Continua) - `ci.yml`

**Trigger:** Push y Pull Requests a las ramas `main` y `develop`

**Funcionalidades:**
- ✅ Testing automático con PHPUnit
- ✅ Análisis de calidad de código con PHP CS Fixer
- ✅ Análisis estático con PHPStan
- ✅ Escaneo de seguridad básico
- ✅ Configuración de base de datos MySQL para tests
- ✅ Configuración de entorno Laravel

### 2. Deployment - `deploy.yml`

**Trigger:** Push a la rama `main` y ejecución manual

**Funcionalidades:**
- 🚀 Deployment automático a producción
- 📦 Creación de artefactos de deployment
- 🔄 Ejecución de migraciones
- ⚡ Optimización de cache (config, routes, views)
- 📢 Notificaciones de Slack
- 🔄 Reinicio de servicios

### 3. Code Quality - `code-quality.yml`

**Trigger:** Pull Requests y Push a `develop`

**Funcionalidades:**
- 🔍 PHP CS Fixer para estilo de código
- 📊 PHPStan para análisis estático
- 🔎 Psalm para análisis adicional de tipos
- 📝 Reportes detallados de calidad

### 4. Security - `security.yml`

**Trigger:** Push, Pull Requests y programado (lunes 2 AM)

**Funcionalidades:**
- 🛡️ Escaneo de vulnerabilidades con Composer Audit
- 🔒 Security Checker para dependencias
- 🕵️ CodeQL para análisis de código
- 🔍 Semgrep para patrones de seguridad
- 🔐 TruffleHog para detección de secretos
- 📋 Dependency Review en PRs

## Dependabot - `dependabot.yml`

**Funcionalidades:**
- 📦 Actualizaciones automáticas de dependencias PHP (Composer)
- 🟨 Actualizaciones de dependencias JavaScript (npm)
- ⚙️ Actualizaciones de GitHub Actions
- 📅 Programado semanalmente los lunes
- 🏷️ Etiquetado automático de PRs

## Configuración Requerida

### Secrets de GitHub

Para que los workflows funcionen correctamente, necesitas configurar los siguientes secrets en tu repositorio:

#### Para Deployment (`deploy.yml`):
```
HOST=tu-servidor.com
USERNAME=usuario-ssh
PRIVATE_KEY=clave-privada-ssh
PORT=22
SLACK_WEBHOOK=webhook-url-de-slack
```

#### Para Security (`security.yml`):
```
SEMGREP_APP_TOKEN=token-de-semgrep
```

### Configuración del Servidor

Para el deployment automático, tu servidor debe tener:
- Git configurado
- Composer instalado
- PHP 8.2+
- Nginx/Apache configurado
- Permisos adecuados para el usuario SSH

## Archivos de Configuración

### `.php-cs-fixer.php`
Configura las reglas de estilo de código para PHP CS Fixer:
- PSR-12 compliance
- Symfony coding standards
- Ordenamiento de imports
- Eliminación de imports no utilizados

### `phpstan.neon`
Configura PHPStan para análisis estático:
- Nivel 5 de strictness
- Integración con Larastan
- Exclusiones específicas para Laravel
- Ignorar patrones comunes de Laravel

## Uso

### Desarrollo Local

1. **Ejecutar PHP CS Fixer:**
   ```bash
   vendor/bin/php-cs-fixer fix
   ```

2. **Ejecutar PHPStan:**
   ```bash
   vendor/bin/phpstan analyse
   ```

3. **Ejecutar Tests:**
   ```bash
   php artisan test
   ```

### Flujo de Trabajo

1. **Feature Branch:** Crea una rama desde `develop`
2. **Development:** Desarrolla tu feature
3. **Pull Request:** Crea PR hacia `develop`
   - Se ejecutan automáticamente: CI, Code Quality, Security
4. **Review:** Revisa y aprueba el PR
5. **Merge to Develop:** Merge a `develop`
6. **Release:** Merge de `develop` a `main`
   - Se ejecuta automáticamente el deployment

## Badges de Estado

Puedes agregar estos badges a tu README principal:

```markdown
![CI](https://github.com/tu-usuario/biodiversity-management/workflows/CI/badge.svg)
![Security](https://github.com/tu-usuario/biodiversity-management/workflows/Security%20Scan/badge.svg)
![Code Quality](https://github.com/tu-usuario/biodiversity-management/workflows/Code%20Quality/badge.svg)
```

## Troubleshooting

### Errores Comunes

1. **Tests fallan:** Verifica la configuración de la base de datos
2. **Deployment falla:** Revisa los secrets y permisos SSH
3. **Code Quality falla:** Ejecuta PHP CS Fixer localmente
4. **Security scan falla:** Actualiza dependencias vulnerables

### Logs

Puedes ver los logs detallados en la pestaña "Actions" de tu repositorio de GitHub.