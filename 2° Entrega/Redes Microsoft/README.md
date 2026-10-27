# Redes Microsoft — Segunda entrega

## Proyecto Translogística

Documentación académica de la infraestructura Microsoft del proyecto Translogística.

### Tecnologías utilizadas
- Windows Server 2022
- Active Directory Domain Services (AD DS)
- DNS
- Directivas de grupo (GPO)
- Grupos de seguridad y permisos NTFS
- Acceso administrativo mediante RDP
- Enrutamiento entre segmentos mediante OPNsense en el laboratorio

### Configuración verificada en el laboratorio
- Cuentas de administración del proyecto y empleados de prueba
- Grupos de empleados por funciones: vendedor, administrador interno y operador
- Estructura de unidades organizativas
- Política GPO de protector de pantalla, contraseña y espera de 10 minutos
- Permisos NTFS diferenciados para carpetas de demostración
- Servicios AD DS y DNS operativos
- Conexión RDP verificada desde la red de administración

### Evidencias previstas
- `Equipos_AD.csv`
- `Grupos_AD.csv`
- `OU_ActiveDirectory.csv`
- `GPO_Empleados.html`
- `Permisos_NTFS_Translogistica.txt`

> Las evidencias se incorporarán tras revisar que no expongan datos sensibles. La creación de este README no certifica que esos archivos ya hayan sido subidos.

### Validaciones pendientes
- Comprobación de aplicación efectiva de GPO y permisos usando una estación de trabajo del dominio
- Respaldo recuperable del estado del sistema
- Revisión controlada de configuración IP antigua
- Migración y validación de servicios en Hyper-V de la UTU

### Seguridad
No publicar contraseñas, claves temporales, respaldos completos de Active Directory ni datos secretos. Los archivos de configuración deben revisarse antes de incorporarse a un repositorio público.
