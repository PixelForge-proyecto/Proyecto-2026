# Ejecutar SOLO en Windows Server de origen VMware.
# Genera respaldo de GPO en disco; no altera configuracion de AD.
$ErrorActionPreference='Stop'
Import-Module GroupPolicy
$destino='C:\Translogistica\RespaldosGPO'
New-Item -ItemType Directory -Path $destino -Force | Out-Null
$gpo=Get-GPO -Name 'GPO_PixelForge_Seguridad' -ErrorAction Stop
Backup-GPO -Guid $gpo.Id -Path $destino -Comment 'Translogistica - Respaldo de GPO personalizado' -ErrorAction Stop | Out-Null
Write-Host "Respaldo de la GPO guardado en: $destino" -ForegroundColor Green
Write-Host 'NO subir este respaldo a un repositorio publico.' -ForegroundColor Yellow
