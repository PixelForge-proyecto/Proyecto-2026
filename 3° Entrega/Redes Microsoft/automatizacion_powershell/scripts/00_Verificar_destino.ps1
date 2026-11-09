# Translogistica / UTU. CONSULTA solamente. No modifica el sistema.
$ErrorActionPreference = 'Stop'
Write-Host '=== TRANSLOGISTICA - PRECHEQUEO WINDOWS ===' -ForegroundColor Cyan
$sys = Get-CimInstance Win32_ComputerSystem
[pscustomobject]@{Equipo=$env:COMPUTERNAME; DominioWindows=$sys.Domain; PerteneceAD=$sys.PartOfDomain; DomainRole=$sys.DomainRole} | Format-List
Get-NetIPConfiguration | Select-Object InterfaceAlias,IPv4Address,IPv4DefaultGateway,DNSServer | Format-List
Get-WindowsFeature AD-Domain-Services,DNS,GPMC | Select-Object Name,InstallState | Format-Table -AutoSize
Get-Service NTDS,DNS,Netlogon -ErrorAction SilentlyContinue | Select-Object Name,Status | Format-Table -AutoSize
try {
    Import-Module ActiveDirectory -ErrorAction Stop
    $dom = Get-ADDomain -ErrorAction Stop
    Write-Host ('Dominio AD accesible: ' + $dom.DNSRoot) -ForegroundColor Green
    Write-Host ('DN del dominio: ' + $dom.DistinguishedName)
} catch {
    Write-Warning ('Active Directory NO confirmado: ' + $_.Exception.Message)
}
Write-Host 'SOLO CONSULTA. No se modifico ninguna configuracion.'
