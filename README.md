# Translogística — PixelForge

Proyecto final UTU. Código y documentación organizados por entrega y área.

## Aplicación Laravel

El despliegue PHP 8.3 + Apache + Laravel 13 de la segunda entrega está organizado en [**Programación → Despliegue Laravel Docker**](2%C2%B0%20Entrega/Programaci%C3%B3n/Despliegue-Laravel-Docker).

**Estado validado en el laboratorio:** el contenedor `pixelforge-web` responde por `http://192.168.30.2`, detrás de OPNsense, con el frontend cargando correctamente.

**Falta subir a esta carpeta el código completo `app-nueva/`** desde el paquete depurado; todavía no está publicado en el repositorio.

El `docker-compose.yml` de la raíz está actualizado para referenciar la ubicación del proyecto **cuando se suba `app-nueva/`**. No debe ejecutarse sobre el servidor de producción/laboratorio existente sin revisar rutas, volúmenes y variables.

## Base de datos

Los archivos de base de datos actualmente presentes en el repositorio **NO corresponden a la versión definitiva**. No utilizarlos para despliegues ni migraciones hasta que el equipo de BD entregue la versión correcta.

El servicio `mysql:8.4` del Compose raíz representa únicamente el MySQL **temporal y actualmente operativo** en VMware, cuyo volumen debe conservarse. La integración con la base definitiva, ProxySQL y replicación está pendiente.

No subir `.env`, credenciales, respaldos SQL, `vendor/` ni `node_modules/`.

## Materias

- [Programación — Segunda entrega](2%C2%B0%20Entrega/Programaci%C3%B3n)
- [Redes Linux — Segunda entrega](2%C2%B0%20Entrega/Redes%20Linux)
- [Base de datos — Segunda entrega](2%C2%B0%20Entrega/Base%20de%20datos)
