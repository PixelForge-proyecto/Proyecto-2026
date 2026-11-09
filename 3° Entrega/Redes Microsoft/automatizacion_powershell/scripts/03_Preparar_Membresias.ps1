# Simulacion por defecto. Solo grupos GG_ del proyecto.
[CmdletBinding()]
param([switch]$Aplicar,[string]$Confirmacion='')
$ErrorActionPreference='Stop'
Import-Module ActiveDirectory
$dom=Get-ADDomain
if($dom.DNSRoot -ine 'pixelforge.test'){throw 'Dominio incorrecto.'}
if($Aplicar -and $Confirmacion -cne 'HYPERV-UTU-REVISADO'){throw 'Confirmacion explicita requerida.'}
$filas=Import-Csv (Join-Path $PSScriptRoot '..\datos\Membresias_objetivo.csv')
foreach($fila in $filas){
    if($fila.Grupo -notlike 'GG_*') { throw "Grupo fuera de lista: $($fila.Grupo)" }
    $g=Get-ADGroup -Identity $fila.Grupo -ErrorAction SilentlyContinue
    $u=Get-ADUser -Identity $fila.Miembro -ErrorAction SilentlyContinue
    if(-not $g -or -not $u){Write-Warning "Falta grupo o usuario: $($fila.Grupo) / $($fila.Miembro)";continue}
    $existe=Get-ADGroupMember -Identity $g -ErrorAction Stop | Where-Object {$_.SamAccountName -ieq $fila.Miembro}
    if($existe){Write-Host "[EXISTE] $($fila.Grupo) <- $($fila.Miembro)";continue}
    Write-Host "[AGREGAR] $($fila.Grupo) <- $($fila.Miembro)"
    if($Aplicar){Add-ADGroupMember -Identity $g -Members $u -ErrorAction Stop}
}
Write-Host ('Fin. Modo: '+$(if($Aplicar){'APLICADO'}else{'SOLO SIMULACION'}))
