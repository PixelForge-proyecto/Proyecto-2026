# Integración controlada de la entrega nueva — 06/11/2026

## Objetivo
Integrar el código Laravel recibido del equipo de Programación con el despliegue **ya funcional** de Translogística en VMware, sin reemplazar todavía contenedores ni volúmenes de base de datos. Aplicarlo después en Proxmox UTU solo tras aprobar pruebas y conectividad.

## Hallazgos preliminares del ZIP recibido
- `compose.despliegue.yaml` incluye `mysql`, `app` y `nginx`; establece `DB_HOST=mysql`, por lo que la conexión no atraviesa ProxySQL.
- `docker-compose.replicacion.yml` define MySQL secundario y ProxySQL en un proyecto separado; requiere la red externa `translogistica_default`. La existencia y compatibilidad de esa red debe comprobarse en el destino.
- El Compose principal no declara el WAF.
- El contenedor Nginx usa una asignación de puerto `${WEB_PORT:-8088}:80`; antes de publicar, se debe decidir si su exposición será solo local o protegida por WAF.
- El README menciona `database/sql/translogistica-estructura.sql`, pero ese archivo no figura en el ZIP recibido. No ejecutar migraciones ni importaciones destructivas.
- El paquete contiene archivos `.env`, variables con contraseñas y `proxysql.cnf`. **No subir el ZIP completo al repositorio público**.
- El archivo `.dockerignore` excluye `.env*` (salvo el ejemplo), `proxysql.cnf` y otros materiales sensibles del contexto de construcción, pero eso no sustituye la revisión de secretos.

## Próximo paso: inventariar VMware sin cambios
Ejecutar desde WSL cuando la VM de VMware sea accesible por SSH:

```bash
cd ~/translogistica-ansible
ansible-playbook -i inventarios/vmware.ini playbooks/inspeccionar_despliegue_vmware.yml --syntax-check
ansible -i inventarios/vmware.ini servidores_linux -m ping
ansible-playbook -i inventarios/vmware.ini playbooks/inspeccionar_despliegue_vmware.yml
```

El informe se guarda en `evidencias/pixelforge-linux_inventario_despliegue.txt` del equipo de control. No se lee ningún secreto ni se realizan cambios remotos.

## Condiciones para desplegar
1. Confirmar qué archivo Compose exacto y qué contenedores están funcionando en VMware.
2. Obtener la versión aprobada del código de Federico y los cambios de esquema de Cristian.
3. Comparar aplicaciones, configuraciones y puertos, sin publicar secretos.
4. Hacer respaldo verificable de MySQL y datos persistentes antes de cualquier cambio.
5. Probar la actualización en VMware, con validación funcional y posibilidad de reversión.
6. Solo después ejecutar en UTU, cuando el acceso SSH y las IP definitivas estén autorizados.

**Estado:** informe preparatorio, sin despliegue ejecutado en UTU.
