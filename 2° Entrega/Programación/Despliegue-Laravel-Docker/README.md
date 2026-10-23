# Translogística — Despliegue Laravel + Docker

Área Redes Linux, segunda entrega, equipo PixelForge.

**Estado validado en VMware (23/10/2026):** AlmaLinux 9, PHP 8.3.33, Laravel 13.32.0, Apache en puerto 80, contenedor `pixelforge-web` visible desde Windows mediante OPNsense (APP `192.168.30.2/27`). MySQL 8.4 `pixelforge-db` continúa activo. Aún falta validar el login y conectar la nueva base de datos, replicación y ProxySQL.

## Archivos de infraestructura
- `apache/Dockerfile`: imagen PHP/Apache, extensiones y Composer.
- `docker-compose.yml`: web y MySQL del laboratorio; persiste `mysql_data`.
- `.env.docker.example`: plantilla de variables, sin contraseñas reales.
- `app-nueva/`: **código Laravel que debe agregarse desde el ZIP de publicación**; no se considera subido hasta completar ese commit.

## Despliegue
En un entorno NUEVO, preparar `app-nueva/.env` desde `.env.example`, `.env.docker` con claves distintas, instalar dependencias con Composer, generar `APP_KEY`, dar permisos de escritura mínimos a `storage` y `bootstrap/cache` y desplegar con `docker compose up -d --build`.

**Importante:** no ejecutar esos comandos a ciegas sobre la VM ya desplegada, donde los contenedores están funcionando. No subir `.env`, claves ni respaldos. No ejecutar `docker compose down -v`. El volumen MySQL 8.4 no se puede montar directamente en un servidor MySQL 8.0.30.

La separación interna por redes Docker (frontend, appdb y datos) y la integración final de ProxySQL todavía están pendientes. El código que contiene la página Laravel se añadirá en un commit separado desde GitHub Desktop.
