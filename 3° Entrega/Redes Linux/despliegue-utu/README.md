# Translogística — preparación del despliegue UTU (3.ª entrega)

> **Estado al 20/11/2026:** documentación de preparación, **NO paquete listo para producción**. Esta carpeta se mantiene en una rama de revisión hasta verificar los archivos exactos del equipo WSL y las condiciones de la red UTU.

## Estado verificado en el laboratorio WSL

- `docker compose up -d`: seis servicios en ejecución (`mysql`, `mysql_secundario`, `proxysql`, `app`, `nginx`, `waf`).
- ProxySQL 2.7.3 ejecutándose con UID/GID **999:999** desde el Compose principal.
- Consultas Laravel/ProxySQL a MySQL principal (`@@server_id=1`) y réplica (`@@server_id=2`) comprobadas.
- MySQL principal y secundario con volúmenes independientes.
- Ansible local: `preparar_candidato_completo.yml`, segunda ejecución **ok=33, changed=0, failed=0, skipped=9**.
- Candidato `compose.utu-v1.yaml`: `docker compose config -q` correcto con valores **temporales** de prueba para `TL_APP_URL` y `UTU_WEB_IP`.
- Cinco imágenes disponibles localmente: `mysql:8.0.30`, `proxysql/proxysql:2.7.3`, `translogistica-web:unificado-v1`, `translogistica-nginx:unificado-v1`, `owasp/modsecurity-crs:nginx`.
- Exportación local realizada a `translogistica-imagenes.tar.gz` (~634 MB). **No consta aún la salida completa** de verificación `gzip -t` y `sha256sum -c`.

## Componentes pendientes de incorporar desde WSL

Los archivos exactos **no están copiados aquí**. Se conservan en el equipo de trabajo y deben revisarse antes de subirlos:

| Origen local | Destino previsto en esta carpeta | Nota |
| --- | --- | --- |
| `~/translogistica-utu-candidato/compose.utu-v1.yaml` | `compose.utu-candidato.yaml` | Requiere revisar rutas, redes, IP y variables; no ejecutar todavía |
| `~/translogistica-ansible/playbooks/preparar_candidato_completo.yml` y playbooks importados | `../pruebas ansible/playbooks/` | Copiar versión exacta y revisar rutas fijas de HOME/WSL |
| `~/translogistica-ansible/inventarios/laboratorio.ini` | `../pruebas ansible/inventarios/` | Solo `localhost`, no IP de UTU |
| Imágenes locales Docker | Transporte fuera de Git | Verificar integridad y seguridad antes de importar |

## Nunca subir

- `.env`, `.env.infra`, `.env.despliegue`, claves de SSH/TLS, `APP_KEY`, tokens y contraseñas.
- `privado/proxysql.cnf` real: incluye parámetros de acceso. Publicar solamente ejemplos saneados.
- Respaldos SQL/volúmenes, certificados, DB de ProxySQL, imágenes `.tar.gz` y capturas con datos sensibles.

El repositorio es **público**. `.gitignore` no protege secretos ya comprometidos en el historial: si hubo una filtración, rotar credenciales y revisar historial.

## Preparación antes del despliegue real

1. Sincronizar y revisar el Compose y los playbooks **exactos** de WSL en esta rama.
2. Validar archivos y ausencia de secretos; revisar imágenes personalizadas para descartar credenciales incorporadas.
3. Definir `TL_APP_URL` y `UTU_WEB_IP` autorizadas. Ajustar redes Docker a UTU sin alterar OPNsense ni reglas institucionales.
4. Preparar Ansible para la VM AlmaLinux de Proxmox (rutas, usuario, inventario SSH e idempotencia). La VPN está deshabilitada: el acceso a UTU se hace desde red institucional autorizada.
5. Crear o conservar volúmenes de manera no destructiva. Probar inicialización y restauración de MySQL, y la replicación en destino.
6. Verificar `docker compose config -q`, comprobar imágenes, luego desplegar solamente con autorización y respaldos.
7. Comprobar web, autenticación, escritura/lectura, ProxySQL sin root, WAF, persistencia, `SHOW REPLICA STATUS\G` y pruebas de recuperación.

**No ejecutar** `docker compose down -v`, comandos de borrado de volúmenes ni importar un SQL sobre una base con datos.

## Nota sobre la raíz del repositorio

El `docker-compose.yml` de la raíz sigue documentando un laboratorio **anterior** con MySQL 8.4. **No usarlo** para desplegar el stack actual de seis servicios. Cuando el candidato sea verificado, actualizar documentación y enlaces sin afectar trabajos previos.
