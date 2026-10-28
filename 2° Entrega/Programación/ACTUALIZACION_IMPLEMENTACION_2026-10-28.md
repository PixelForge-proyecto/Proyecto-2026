# Translogística — Actualización de Programación
**Segunda entrega · 28/10/2026 · Equipo PixelForge**

## Alcance
Este registro documenta **lo comprobado en el laboratorio local AlmaLinux/VMware** durante la integración del 27–28/10/2026. No equivale a la publicación en GitHub del paquete final que se encuentra en la VM.

## Arquitectura utilizada en la prueba
Cliente → OPNsense/DMZ → `192.168.30.2:80` → `pixelforge-waf` (Nginx + OWASP ModSecurity CRS) → red Docker externa `tl_waf_bridge` → alias `tl-nginx-backend:80` → Nginx de Translogística → Laravel/PHP → ProxySQL `proxysql:6033` → MySQL.

- Contenedores de aplicación comprobados: `translogistica-servidor-nginx-1`, `translogistica-servidor-app-1`, `translogistica-servidor-proxysql-1` y los servidores MySQL, además de `pixelforge-waf`.
- Nginx se expuso en el host solamente como `127.0.0.1:8088:80`; el acceso de red se realizó a través del WAF.
- Versiones observadas: Laravel **13.32.0** y PHP **8.3.33**.
- Conexión de Laravel ajustada para usar `DB_HOST=proxysql` y `DB_PORT=6033`. La contraseña real se mantiene fuera de GitHub.

## Correcciones y pruebas
| Elemento | Intervención / comprobación | Alcance |
| --- | --- | --- |
| `public/.htaccess` | Se sustituyó en el despliegue el archivo corrupto/binario por reglas válidas de Laravel. El archivo ya presente en este repositorio contiene el formato válido. | Incidente HTTP 500 solucionado en laboratorio. |
| Permisos Laravel | Se corrigieron permisos del usuario web sobre configuración, `storage` y `bootstrap/cache`. | Respuesta HTTP 200 comprobada; no supone validación de todas las funciones. |
| ProxySQL | Se corrigieron host/puerto de acceso a datos y se sincronizó la credencial de aplicación **solo en el entorno desplegado**. | La integración de infraestructura se comprobó. |
| Recursos frontend | Se retiró `public/hot` en el entorno de prueba para usar los archivos compilados de `public/build`. | CSS/JS comprobados; persistir el ajuste en un despliegue reproducible. |
| Acceso web | Se cargaron la página principal y la ruta `/login`. | El registro público `/register` no está disponible. |
| Migraciones | `php artisan migrate:status --no-ansi`. | Siete migraciones figuraban como ejecutadas en la prueba. |

La autenticación incluye guardias separados `interno` y `cliente`, con comprobación de estado activo en el código inspeccionado. **La prueba completa de acceso, roles, autorizaciones y operaciones contra la BD definitiva queda por realizar/documentar**.

## Archivos utilizados en la VM que aún deben sincronizarse
Directorio de despliegue local: `/home/cynthia/translogistica_nueva/Translogistica-Entrega/`.

- `compose.despliegue.yaml`
- `compose.replicacion.servidor.yaml`
- `compose.proxysql.servidor.yaml`
- `compose.laravel.proxysql.override.yaml`
- `compose.nginx.waf-bridge.yaml`

En `/home/cynthia/pixelforge/` se usó también `docker-compose.waf-translogistica.yml` para enlazar el WAF existente con el nuevo Nginx. **El contenido literal de esos archivos no se conserva en este repositorio en la fecha de esta actualización**; no debe reconstruirse de memoria y presentarse como idéntico a producción.

## Nota sobre el código versionado
La carpeta `Despliegue-Laravel-Docker/` contiene el proyecto Laravel y un Compose de **laboratorio anterior** (Apache + MySQL 8.4). No representa por sí sola el esquema Nginx/ProxySQL/MySQL 8.0.30 que se probó el 28/10. Antes de reemplazarla, comparar el código con la copia real de la VM, sin eliminar cambios de Federico.

## Seguridad y próximos controles
- No publicar `.env`, credenciales, `APP_KEY`, volúmenes de datos ni respaldos con información real.
- No ejecutar `docker compose down -v` en el entorno activo.
- Validar autenticación por cada rol, consultas/escrituras, persistencia del `public/hot`, bloqueo real del WAF y reproducción del despliegue a partir de archivos versionados.
- Transferir el paquete final desde el equipo o la VM para incorporar el **código y los Compose exactos** en un commit posterior.

**Estado de publicación:** documentación de integración actualizada; código y configuración finales de la VM **no sincronizados aún**.
