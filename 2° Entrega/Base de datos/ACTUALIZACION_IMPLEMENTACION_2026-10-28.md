# Translogística — Actualización de Base de Datos
**Segunda entrega · 28/10/2026 · Equipo PixelForge**

## Alcance
Registro de las comprobaciones realizadas durante la integración en AlmaLinux/VMware el 27–28/10/2026. Los resultados corresponden al **laboratorio**, no acreditan que el último respaldo SQL ni todos los archivos de despliegue estén publicados en este repositorio.

## Arquitectura comprobada
- MySQL **8.0.30**: servidor primario y secundario de réplica.
- ProxySQL **2.7.3**, punto de acceso de la aplicación por el puerto **6033**.
- Hostgroup **10** para el primario y hostgroup **20** para la réplica (según configuración observada).
- Laravel utiliza `DB_HOST=proxysql` y `DB_PORT=6033`; ninguna contraseña de aplicación o replicación se documenta aquí.
- Contenedores comprobados: `translogistica-servidor-mysql-1`, `translogistica-servidor-mysql_secundario-1` y `translogistica-servidor-proxysql-1`.

## Incidencias resueltas en el entorno
1. **Contraseña de replicación:** una credencial superaba el límite de 32 caracteres admitido por la configuración utilizada. Se sustituyó por otra de 24 caracteres, sin publicarla.
2. **Usuario faltante:** se creó el usuario de replicación que faltaba en el servidor secundario.
3. **Integración de la aplicación:** se alineó la contraseña del usuario de aplicación con la configuración de Laravel/ProxySQL, sin exponer secretos en archivos públicos.

## Pruebas observadas
| Comprobación | Resultado |
| --- | --- |
| `Replica_IO_Running` | `Yes` |
| `Replica_SQL_Running` | `Yes` |
| `Seconds_Behind_Source` | `0` al momento de la prueba |
| Conexiones hacia hostgroups de ProxySQL | Observadas hacia ambos grupos |
| Exportación SQL local `translogistica_despliegue.sql` | Se observaron **18** instrucciones `CREATE TABLE` |

**Límite de la prueba:** que ambos hilos de réplica estén en `Yes` y que el retraso observado sea cero no sustituye ensayos de escritura/lectura extremo a extremo, integridad referencial, recuperación desde respaldo o supervisión continua.

## Diferencia con lo ya subido
- `create_database_v12.sql` es un script previo de creación desde cero. **Contiene operaciones `DROP TABLE`; no ejecutar sobre una BD con datos a conservar.** No se ha reemplazado por el respaldo final porque no se dispone de su contenido exacto en este entorno.
- `docker-compose.yml` es un ejemplo previo y no representa por completo el despliegue validado. Además, tiene una contraseña `root` fija de laboratorio; **no reutilizar esa configuración en un sistema expuesto ni con información real**. El archivo no se modificó aquí para no alterar el trabajo de Cristian sin contrastarlo con su versión final.
- `Diccionario_de_Datos_V4_0.docx` se conserva sin cambios hasta contrastarlo con las 18 tablas del esquema finalmente exportado.

## Archivos finales pendientes de incorporar desde la VM
Ruta local: `/home/cynthia/translogistica_nueva/Translogistica-Entrega/` (paquete original `Translogistica-Entrega.tar.gz`).

- `translogistica_despliegue.sql` (revisar y sanear datos personales y cuentas antes de subir).
- `compose.despliegue.yaml`, `compose.replicacion.servidor.yaml` y `compose.proxysql.servidor.yaml`.
- `INSTALAR.sh` y `CONFIGURAR_PROXYSQL.sh` (inspeccionar secretos antes de publicar).
- Plantillas `.env.example` seguras, si son necesarias; nunca subir el `.env` real.

No subir archivos `*.tar` con imágenes, volúmenes, contraseñas, claves, ni respaldos de datos reales. No ejecutar scripts de importación que borren tablas sin respaldo probado.

**Estado de publicación:** evidencia y diferencias técnicas documentadas; **respaldo SQL final y Compose definitivos todavía no sincronizados**.
