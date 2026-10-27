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

### Evidencias incorporadas
- `Equipos_AD.csv`
- `Grupos_AD.csv`
- `OU_ActiveDirectory.csv`
- `GPO_Empleados.html` (versión HTML de consulta pública: conserva los datos del reporte y omite el código incrustado de Windows)
- `Permisos_NTFS_Translogistica.txt`

> Los cinco archivos de evidencias fueron incorporados al repositorio después de una revisión de contenido. Los CSV y el TXT preservan los datos de los originales; el HTML de GPO es una versión depurada para consulta pública, no una exportación restaurable.

### Validaciones pendientes
- Comprobación de aplicación efectiva de GPO y permisos usando una estación de trabajo del dominio
- Respaldo recuperable del estado del sistema
- Revisión controlada de configuración IP antigua
- Migración y validación de servicios en Hyper-V de la UTU

### Seguridad
No publicar contraseñas, claves temporales, respaldos completos de Active Directory ni datos secretos. Los archivos de configuración deben revisarse antes de incorporarse a un repositorio público.
