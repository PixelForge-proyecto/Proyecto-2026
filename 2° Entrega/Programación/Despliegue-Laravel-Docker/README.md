# Translogística — Despliegue Laravel + Docker

**Segunda entrega · Actualización documental: 28/10/2026**

> **ATENCIÓN:** el `docker-compose.yml` de esta carpeta corresponde al laboratorio **anterior** (Apache + MySQL 8.4). La arquitectura verificada el 28/10/2026 usa Nginx, Laravel, ProxySQL y MySQL 8.0.30 primario/secundario. **No usar este Compose para reemplazar el despliegue activo.**

El estado de integración, las correcciones comprobadas y la lista de archivos finales aún no sincronizados se encuentran en:

[**Actualización de Programación — 28/10/2026**](../ACTUALIZACION_IMPLEMENTACION_2026-10-28.md)

## Contenido de esta carpeta
- `app-nueva/`: código Laravel versionado en GitHub; debe compararse con la copia exacta que opera en la VM antes de afirmar que están sincronizados.
- `app-nueva/public/.htaccess`: reglas de reescritura válidas para Laravel (se revisó tras un incidente con una copia corrupta).
- `apache/Dockerfile`: imagen PHP/Apache usada en el laboratorio anterior.
- `docker-compose.yml`: Compose histórico que publica `pixelforge-web` y usa `pixelforge-db` (MySQL 8.4).
- `.env.docker.example`: plantilla sin credenciales reales para ese laboratorio histórico.

## Estado de la nueva integración, observado en VMware
- AlmaLinux 9 minimal; Laravel **13.32.0**, PHP **8.3.33**.
- Cadena de red: OPNsense → WAF `pixelforge-waf` → `tl_waf_bridge` → Nginx (`tl-nginx-backend:80`) → Laravel → ProxySQL `proxysql:6033` → MySQL 8.0.30.
- Nginx del proyecto vinculado en el host a `127.0.0.1:8088:80`, sin publicación directa a la LAN.
- Se comprobaron carga visual y HTTP 200. `/login` responde, pero las pruebas integrales de autenticación/roles están pendientes.
- Los cambios de Compose y código que solo están en `/home/cynthia/translogistica_nueva/Translogistica-Entrega/` **aún requieren sincronización desde la VM**.

## Precauciones
No publicar `.env`, contraseñas, `APP_KEY`, datos reales ni respaldos sin sanear. No ejecutar `docker compose down -v` y no conectar el volumen MySQL 8.4 directamente a MySQL 8.0.30. En despliegues nuevos, revisar cuidadosamente las variables, imágenes y rutas según la versión final del paquete.
