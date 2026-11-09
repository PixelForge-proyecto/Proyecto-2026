# Consulta para decidir ajustes DNS / GPO, no modifica nada.
$ErrorActionPreference='Continue'
Import-Module GroupPolicy -ErrorAction SilentlyContinue
Import-Module DnsServer -ErrorAction SilentlyContinue
Write-Host '=== GPO DESTINO ===' -ForegroundColor Cyan
Get-GPO -All -ErrorAction SilentlyContinue | Select-Object DisplayName,GpoStatus | Format-Table -AutoSize
Write-Host '=== DNS / ZONAS ===' -ForegroundColor Cyan
Get-DnsServerZone -ErrorAction SilentlyContinue | Select-Object ZoneName,ZoneType,IsDsIntegrated | Format-Table -AutoSize
Write-Host '=== DNS / REENVIADORES ===' -ForegroundColor Cyan
Get-DnsServerForwarder -ErrorAction SilentlyContinue | Select-Object -ExpandProperty IPAddress
Write-Host '=== SERVIDOR / IP ===' -ForegroundColor Cyan
Get-NetIPConfiguration | Select-Object InterfaceAlias,IPv4Address,IPv4DefaultGateway,DNSServer | Format-List
Write-Host 'No se importan registros DNS dinamicos ni GPO.'
