# Simulacion por defecto. Solo grupos GG_ del proyecto.
[CmdletBinding()]
param([switch]$Aplicar,[string]$Confirmacion='')
$ErrorActionPreference='Stop'
Import-Module ActiveDirectory

function Buscar-ObjetoAD {
    param([string]$Tipo,[string]$Identidad)
    try {
        if($Tipo -eq 'Grupo') { return Get-ADGroup -Identity $Identidad -ErrorAction Stop }
        return Get-ADUser -Identity $Identidad -ErrorAction Stop
    } catch [Microsoft.ActiveDirectory.Management.ADIdentityNotFoundException] {
        return $null
    }
}

$dom=Get-ADDomain
if($dom.DNSRoot -ine 'pixelforge.test'){throw 'Dominio incorrecto.'}
if($Aplicar -and $Confirmacion -cne 'HYPERV-UTU-REVISADO'){throw 'Confirmacion explicita requerida.'}
$filas=Import-Csv (Join-Path $PSScriptRoot '..\datos\Membresias_objetivo.csv')

Write-Host ('Modo: ' + $(if($Aplicar){'APLICAR'}else{'SIMULACION'})) -ForegroundColor Yellow
foreach($fila in $filas){
    if($fila.Grupo -notlike 'GG_*') { throw "Grupo fuera de lista: $($fila.Grupo)" }
    $g=Buscar-ObjetoAD -Tipo 'Grupo' -Identidad $fila.Grupo
    $u=Buscar-ObjetoAD -Tipo 'Usuario' -Identidad $fila.Miembro

    if(-not $g){
        Write-Warning "No existe el grupo $($fila.Grupo). Revisar antes de aplicar."
        if($Aplicar){throw 'No se modificaron las membresias pendientes.'}
        continue
    }
    if(-not $u){
        if($fila.Miembro -ieq 'Cristian.aguilar'){
            $anterior=Buscar-ObjetoAD -Tipo 'Usuario' -Identidad 'Cristian.aguiar'
            if($anterior){
                Write-Warning 'Cristian.aguiar existe; Cristian.aguilar pendiente. No modificar membresias hasta corregir la cuenta.'
                if($Aplicar){throw 'No aplicar membresias con cuenta Cristian pendiente de correccion.'}
                continue
            }
        }
        Write-Warning "No existe el usuario $($fila.Miembro). Revisar antes de aplicar."
        if($Aplicar){throw 'No aplicar con cuentas inexistentes.'}
        continue
    }

    $existe=Get-ADGroupMember -Identity $g -ErrorAction Stop |
        Where-Object { $_.SamAccountName -ieq $fila.Miembro }

    if($existe){
        Write-Host "[EXISTE] $($fila.Grupo) <- $($fila.Miembro)"
        continue
    }

    Write-Host "[AGREGAR] $($fila.Grupo) <- $($fila.Miembro)"
    if($Aplicar){Add-ADGroupMember -Identity $g -Members $u -ErrorAction Stop}
}
Write-Host ('Fin. Modo: '+$(if($Aplicar){'APLICADO'}else{'SOLO SIMULACION'})) -ForegroundColor Green
