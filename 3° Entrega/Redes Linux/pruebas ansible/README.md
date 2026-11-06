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
