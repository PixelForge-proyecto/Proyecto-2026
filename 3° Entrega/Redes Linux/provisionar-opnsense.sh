#!/usr/bin/env bash
# Translogistica - Aprovisionamiento de una NUEVA VM OPNsense en Proxmox VE.
# Entrega 3 / Redes Linux. Preparado a partir de la VM 1401 (03-11-2026).
#
# Importante:
# - NO modifica la VM 1401 ni importa el respaldo config.xml.
# - Por defecto solo muestra el comando (simulacion).
# - Necesita acceso a la consola del HOST Proxmox y permisos para usar qm.
# - El respaldo config.xml debe guardarse cifrado y FUERA del repositorio.
#
# Uso:
#   bash provisionar-opnsense.sh 1501             # Ver el plan: no cambia nada
#   bash provisionar-opnsense.sh 1501 --execute   # Crear VM nueva (confirmacion)
#
# Reemplazar 1501 por un VMID libre y autorizado por UTU.
set -Eeuo pipefail

VMID="${1:-}"
MODO="${2:---dry-run}"

if [[ ! "$VMID" =~ ^[0-9]{3,9}$ ]]; then
  echo "Uso: bash $0 <VMID-nuevo> [--execute]"
  exit 2
fi
if [[ "$MODO" != "--dry-run" && "$MODO" != "--execute" ]]; then
  echo "Modo no valido: $MODO (usar --execute o dejar simulacion)."
  exit 2
fi
if [[ "$VMID" == "1401" ]]; then
  echo "ERROR: VMID 1401 corresponde al OPNsense actual. No se modifica."
  exit 2
fi

# Parametros observados en la VM OPNsense de UTU.
VM_NAME="Translogistica-OPNsense-${VMID}"
RAM_MB=2048
SOCKETS=2
CORES=2
DISK_STORAGE="VMSSD"
DISK_GB=32
ISO_VOL="local:iso/OPNsense-26.7-dvd-amd64.iso"
BRIDGE_LAN="vmbr5"  # net0 -> vtnet0; LAN actual: 192.168.34.1/24
BRIDGE_WAN="vmbr0"  # net1 -> vtnet1; WAN actual: 192.168.3.76/23

# Ambos enlaces se crean DESCONECTADOS para evitar conflictos en UTU.
# Luego de instalar y validar el XML, habilitarlos desde Hardware en Proxmox.
COMANDO=(
  qm create "$VMID"
  --name "$VM_NAME"
  --memory "$RAM_MB"
  --sockets "$SOCKETS"
  --cores "$CORES"
  --bios seabios
  --ostype other
  --scsihw virtio-scsi-single
  --scsi0 "${DISK_STORAGE}:${DISK_GB},iothread=1"
  --ide2 "${ISO_VOL},media=cdrom"
  --boot 'order=ide2;scsi0'
  --net0 "virtio,bridge=${BRIDGE_LAN},firewall=1,link_down=1"
  --net1 "virtio,bridge=${BRIDGE_WAN},firewall=1,link_down=1"
  --onboot 0
)

echo "=== Translogistica / Aprovisionamiento OPNsense ==="
echo "VMID nuevo: $VMID"
echo "Destino: Proxmox VE, VM nueva, sin arrancar; interfaces desconectadas."
printf 'Comando: '
printf '%q ' "${COMANDO[@]}"
printf '\n'

if [[ "$MODO" == "--dry-run" ]]; then
  echo "SIMULACION: no se ha modificado ninguna maquina virtual."
  echo "Para crear la VM: bash $0 $VMID --execute"
  exit 0
fi

if [[ "${EUID}" -ne 0 ]]; then
  echo "ERROR: ejecutar en el HOST Proxmox como administrador."
  exit 1
fi
for app in qm pvesm ip; do
  command -v "$app" >/dev/null 2>&1 || {
    echo "ERROR: comando requerido no disponible: $app"
    exit 1
  }
done
if qm status "$VMID" >/dev/null 2>&1; then
  echo "ERROR: el VMID $VMID ya existe. No se modifica ninguna VM existente."
  exit 1
fi
for bridge in "$BRIDGE_LAN" "$BRIDGE_WAN"; do
  ip link show "$bridge" >/dev/null 2>&1 || {
    echo "ERROR: falta el bridge $bridge. Revisar el nodo Proxmox."
    exit 1
  }
done
ISO_PATH="$(pvesm path "$ISO_VOL")" || {
  echo "ERROR: no se pudo encontrar el volumen ISO $ISO_VOL"
  exit 1
}
if [[ ! -f "$ISO_PATH" ]]; then
  echo "ERROR: ISO inexistente: $ISO_PATH"
  exit 1
fi

echo "Esta accion creara una VM NUEVA y reservara un disco de $DISK_GB GiB."
read -r -p "Escribi CREAR para confirmar: " RESPUESTA
if [[ "$RESPUESTA" != "CREAR" ]]; then
  echo "Cancelado. No se creo la VM."
  exit 0
fi

"${COMANDO[@]}"
echo "VM $VMID creada. NO fue encendida ni conectada a la red."
echo "Pendiente: instalar OPNsense, revisar version, restaurar config.xml"
echo "y asignar interfaces/IP segun las redes autorizadas de UTU."
