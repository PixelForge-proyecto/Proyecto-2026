# Simulacion por defecto. Requiere credenciales iniciales NUEVAS al aplicar.
# NO replica passwords, SID ni perfiles del dominio VMware.
[CmdletBinding()]
param([switch]$Aplicar,[string]$Confirmacion='')
$ErrorActionPreference='Stop'
Import-Module ActiveDirectory

function Buscar-UsuarioAD {
    param([Parameter(Mandatory=$true)][string]$Sam)
    try {
        return Get-ADUser -Identity $Sam -ErrorAction Stop
    } catch [Microsoft.ActiveDirectory.Management.ADIdentityNotFoundException] {
        return $null
    }
}

$dom=Get-ADDomain
if($dom.DNSRoot -ine 'pixelforge.test') { throw 'Dominio incorrecto.' }
if($Aplicar -and $Confirmacion -cne 'HYPERV-UTU-REVISADO') { throw 'Confirmacion explicita requerida.' }
$archivo=Join-Path $PSScriptRoot '..\datos\Usuarios_objetivo.csv'
$usuarios=Import-Csv $archivo
Write-Host ('Modo: ' + $(if($Aplicar){'APLICAR'}else{'SIMULACION'})) -ForegroundColor Yellow

foreach($u in $usuarios) {
    $actual=Buscar-UsuarioAD -Sam $u.SamAccountName
    if($actual) { Write-Host "[EXISTE] USUARIO $($u.SamAccountName)"; continue }

    # No crear un segundo Cristian si persiste el usuario anterior en este dominio.
    if($u.SamAccountName -ieq 'Cristian.aguilar') {
        $anterior=Buscar-UsuarioAD -Sam 'Cristian.aguiar'
        if($anterior) {
            Write-Warning 'Cristian.aguiar existe: correccion a Cristian.aguilar pendiente. NO crear duplicado.'
            if($Aplicar) { throw 'Detenido: resolver correccion del usuario Cristian antes de aplicar.' }
            continue
        }
    }

    $parent=$u.DistinguishedName.Substring($u.DistinguishedName.IndexOf(',')+1)
    Write-Host "[CREAR USUARIO] $($u.SamAccountName) ($($u.Name)) en $parent"
    if(-not $Aplicar){continue}

    $pass=Read-Host "Contrasena inicial para $($u.SamAccountName) (no se muestra)" -AsSecureString
    $args=@{ Name=$u.Name; SamAccountName=$u.SamAccountName; UserPrincipalName=$u.UserPrincipalName; Path=$parent; AccountPassword=$pass; Enabled=$true; ChangePasswordAtLogon=$true; ErrorAction='Stop' }
    if($u.GivenName){$args.GivenName=$u.GivenName}
    if($u.Surname){$args.Surname=$u.Surname}
    if($u.Description){$args.Description=$u.Description}
    New-ADUser @args
}
Write-Host 'Fin. Cuentas creadas NO conservan identidad (SID) ni clave de origen.' -ForegroundColor Green
