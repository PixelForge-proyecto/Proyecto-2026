# Translogística — Automatización Windows Server 2022 (3.ª entrega)

Scripts PowerShell para auditar y preparar una implementación reproducible en Hyper-V UTU, a partir de VMware. Versión inicial: 09/11/2026.

## Estado de pruebas
- **00_Verificar_destino.ps1**: ejecutado en Windows Server 2022 de VMware mediante RDP. Pendiente de verificar en Hyper-V.
- **01, 02 y 03**: pendientes de probar en modo SIMULACIÓN, sin modificaciones.
- **04 y 05**: pendientes de probar.
- **Ningún script de modificación fue aprobado para ejecutarse en UTU.**

## Scripts
| Archivo | Función | ¿Modifica AD? |
|---|---|---|
| scripts/00_Verificar_destino.ps1 | Consulta SO, red, dominio, roles y servicios | No |
| scripts/01_Preparar_OU_y_Grupos.ps1 | Simula / prepara OU y grupos | Solo con aplicación autorizada |
| scripts/02_Preparar_Usuarios.ps1 | Simula / prepara usuarios con contraseñas nuevas | Solo con aplicación autorizada |
| scripts/03_Preparar_Membresias.ps1 | Simula / prepara miembros de grupos GG_ | Solo con aplicación autorizada |
| scripts/04_Respaldo_GPO_origen.ps1 | Respaldo de GPO en VMware | No cambia AD; crea archivos |
| scripts/05_Consultar_GPO_DNS_destino.ps1 | Consulta GPO, DNS e IP | No |

## Inventarios privados NO incluidos
Los CSV de usuarios, grupos, OU, membresías, informes AD, XML de GPO y registros DNS NO deben publicarse.

Los scripts 01 a 03 requieren una carpeta local llamada datos, hermana de la carpeta scripts, con estos archivos privados:
- OU_objetivo.csv: Name,DistinguishedName,Description,ProtectedFromAccidentalDeletion
- Grupos_objetivo.csv: Name,SamAccountName,GroupCategory,GroupScope,DistinguishedName,Description
- Usuarios_objetivo.csv: SamAccountName,Name,GivenName,Surname,Enabled,UserPrincipalName,DistinguishedName,Description
- Membresias_objetivo.csv: Grupo,Miembro,Tipo,DN_Miembro

Los CSV deben conservarse localmente en el paquete ZIP privado ya recibido. **Estos scripts requieren esos datos, por lo que no ejecutarán las fases 01–03 sin los CSV.** No hacer commit de esos archivos en el repositorio.

## Seguridad y despliegue
1. Probar primero 00 y 05 en VMware.
2. Probar 01, 02 y 03 SOLO en simulación, sin parámetro de aplicación.
3. Revisar estado actual de Windows Hyper-V UTU, sus redes y su dominio, antes de cualquier cambio.
4. Hacer respaldos y recibir autorización para el despliegue.
5. Dos bosques independientes llamados pixelforge.test NO son un mismo dominio. Recrear usuarios NO conserva SID, claves ni perfiles.
6. No copiar IP de VMware o reenviadores DNS privados a UTU. Los registros SRV de AD no deben importarse ciegamente.
7. Un reporte XML de GPO NO sustituye un respaldo hecho con Backup-GPO; revisar vínculos y filtrado antes de importar.
8. Nunca subir respaldos GPO, información interna ni contraseñas a GitHub.

## Ejecución del script verificado
En el Windows Server del laboratorio VMware, desde la carpeta principal del paquete:
    & ".\scripts\00_Verificar_destino.ps1"

Este script solo consulta. La integración Windows–OPNsense–AlmaLinux sigue pendiente de prueba.
