# Pruebas Ansible — Translogística

Ejercicios de Ansible preparados para la tercera entrega de Redes Linux/POSIX.

> **Origen:** estos archivos fueron reconstruidos a partir de los comandos y resultados de las pruebas de WSL/VMware. **No son una copia binaria del respaldo** `translogistica-ansible.tar.gz` almacenado en la PC. Para conservar ese archivo exacto, subirlo desde la computadora.

## Contenido

- `playbooks/diagnostico.yml`: obtiene datos básicos del entorno local.
- `playbooks/auditoria.yml`: audita SO, RAM, CPU, Docker y presencia de `sshd_config`.
- `playbooks/preparar_directorios.yml`: demuestra ejecución idempotente al crear `evidencias/`.
- `playbooks/verificacion_utu.yml`: verifica SO, memoria, rutas, presencia de Docker y servicio SSH (solo lectura).
- `playbooks/verificar_docker_utu.yml`: comprueba versión de Docker, Compose y servicio Docker (solo lectura).
- `playbooks/generar_evidencias.yml`: genera un informe TXT local con información del sistema, red, Docker y SSH. Los informes generados deben revisarse antes de compartirse.
- `playbooks/prechequeo_despliegue.yml`: consulta espacio en disco, imágenes, redes y volúmenes Docker sin cambios en el servidor.
- `playbooks/inspeccionar_despliegue_vmware.yml`: genera un informe local de contenedores, proyectos Compose y rutas de archivos del servidor VMware, **sin consultar variables de entorno ni secretos**.
- `INFORME_INTEGRACION_2026-11-06.md`: resultados preliminares de revisión del nuevo ZIP y condiciones para integrarlo de forma segura.
- `inventarios/laboratorio.ini`: equipo WSL local.
- `inventarios/vmware.ini`: plantilla para la VM de laboratorio, **sin IP ni usuario reales**.
- `inventarios/utu.ini`: plantilla para servidores Linux de UTU, **sin IP ni usuario reales**.

## Pruebas locales

Desde la carpeta `pruebas ansible`, con `ansible-core` instalado:

```bash
ansible -i inventarios/laboratorio.ini laboratorio_local -m ping
ansible-playbook -i inventarios/laboratorio.ini playbooks/diagnostico.yml
ansible-playbook -i inventarios/laboratorio.ini playbooks/auditoria.yml
ansible-playbook -i inventarios/laboratorio.ini playbooks/preparar_directorios.yml --check
```

## Prechequeo del despliegue

```bash
ansible-playbook -i inventarios/vmware.ini playbooks/prechequeo_despliegue.yml --syntax-check
```

Se validó la sintaxis en WSL el 06/11/2026. **No se ha ejecutado aún sobre Proxmox UTU**.

## Integración de la entrega nueva

Los paquetes recién recibidos se deben comparar contra el despliegue operativo de VMware antes de usarlos en Proxmox. El informe de inventario se genera **solo en WSL** y no debe publicarse sin revisar rutas y datos internos.

```bash
ansible-playbook -i inventarios/vmware.ini playbooks/inspeccionar_despliegue_vmware.yml --syntax-check
ansible -i inventarios/vmware.ini servidores_linux -m ping
ansible-playbook -i inventarios/vmware.ini playbooks/inspeccionar_despliegue_vmware.yml
```

## Pruebas sobre VMware / UTU

1. Editar el inventario correspondiente con la IP y el usuario SSH reales.
2. Configurar un par de claves SSH en el equipo de control y autorizar la **clave pública** en el servidor.
3. Probar la conexión: `ansible -i inventarios/vmware.ini servidores_linux -m ping`.
4. Ejecutar auditoría: `ansible-playbook -i inventarios/vmware.ini playbooks/auditoria.yml`.

Antes de conectarse remotamente, verificar solo la sintaxis (no ejecuta cambios):

```bash
ansible-playbook -i inventarios/vmware.ini playbooks/verificar_docker_utu.yml --syntax-check
ansible-playbook -i inventarios/laboratorio.ini playbooks/verificacion_utu.yml --syntax-check
```

La UTU requiere acceso desde una máquina autorizada dentro de su red. No se deben subir claves privadas, contraseñas, archivos `.env` ni secretos a este repositorio público.

## Informe de evidencias

```bash
ansible-playbook -i inventarios/laboratorio.ini playbooks/generar_evidencias.yml
cat evidencias/localhost_informe.txt
```

En WSL se verificó `ok=6`, `changed=2`, `failed=0`. Los dos cambios fueron locales (carpeta e informe). La sección Docker del playbook fue corregida para mostrar `Docker no disponible en este equipo` si corresponde.

## Evidencia de las nuevas pruebas (06/11/2026)

- `verificacion_utu.yml` se ejecutó en WSL: `ok=8`, `changed=0`, `failed=0`.
- `verificar_docker_utu.yml` pasó la verificación `--syntax-check` en WSL. Aún **no se ejecutó en Proxmox UTU**.

## Evidencia del laboratorio

Las pruebas previas desde WSL hacia AlmaLinux de VMware completaron la auditoría con `ok=6`, `changed=0`, `failed=0`.

## Integración Windows–Linux: verificación de rp_filter (11/11/2026)

En el laboratorio VMware se comprobó comunicación TCP 22 desde Windows hacia AlmaLinux y resolución DNS desde AlmaLinux hacia Windows. El tráfico dirigido a la interfaz Linux de la red de servidores no respondía por el filtrado estricto de ruta inversa cuando había un camino de retorno diferente. Tras cambiar temporalmente el valor de la interfaz correspondiente a 2 (modo flexible), el ping y TCP 22 desde Windows respondieron.

Se guardó en AlmaLinux `/etc/sysctl.d/90-translogistica-rpfilter.conf` y se comprobó que `sysctl -p` devuelve el valor `2`. **Todavía no se validó la persistencia mediante reinicio**. Esta configuración es específica de las interfaces de VMware y no debe aplicarse sin revisión a UTU.

El playbook `playbooks/verificar_rpfilter_vmware.yml` es **solo de lectura** y comprueba el valor activo y el archivo persistente. Para ejecutarlo desde WSL, con el inventario privado:

```bash
ansible-playbook -i inventarios/vmware.ini playbooks/verificar_rpfilter_vmware.yml
```

No se comparten IP, contraseñas ni capturas privadas de los servidores en el repositorio.
