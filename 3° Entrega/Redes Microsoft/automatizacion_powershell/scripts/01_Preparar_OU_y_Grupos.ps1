# Simulacion por defecto; modifica SOLO con -Aplicar y confirmacion literal.
[CmdletBinding()]
param([switch]$Aplicar,[string]$Confirmacion='')
$ErrorActionPreference = 'Stop'
Import-Module ActiveDirectory
$dom = Get-ADDomain
if ($dom.DNSRoot -ine 'pixelforge.test') { throw 'Dominio incorrecto. NO ejecutar cambios.' }
if ($Aplicar -and $Confirmacion -cne 'HYPERV-UTU-REVISADO') { throw 'Falta confirmacion explicita para modificar Active Directory.' }
$datos = Join-Path $PSScriptRoot '..\datos'
$ous = Import-Csv (Join-Path $datos 'OU_objetivo.csv')
$grupos = Import-Csv (Join-Path $datos 'Grupos_objetivo.csv')
Write-Host ('Modo: ' + $(if($Aplicar){'APLICAR'}else{'SIMULACION'})) -ForegroundColor Yellow
foreach($ou in $ous) {
    $dn=$ou.DistinguishedName
    $actual=Get-ADOrganizationalUnit -Identity $dn -ErrorAction SilentlyContinue
    if($actual) { Write-Host "[EXISTE] OU $dn"; continue }
    $parent=$dn.Substring($dn.IndexOf(',')+1)
    Write-Host "[CREAR OU] $dn"
    if($Aplicar) {
        if($parent -ne $dom.DistinguishedName -and -not (Get-ADOrganizationalUnit -Identity $parent -ErrorAction SilentlyContinue)) { throw "Falta OU padre: $parent" }
        New-ADOrganizationalUnit -Name $ou.Name -Path $parent -ProtectedFromAccidentalDeletion $true -ErrorAction Stop | Out-Null
    }
}
foreach($grupo in $grupos) {
    $actual=Get-ADGroup -Identity $grupo.SamAccountName -ErrorAction SilentlyContinue
    if($actual) { Write-Host "[EXISTE] GRUPO $($grupo.SamAccountName)"; continue }
    $path=$grupo.DistinguishedName.Substring($grupo.DistinguishedName.IndexOf(',')+1)
    Write-Host "[CREAR GRUPO] $($grupo.SamAccountName) en $path"
    if($Aplicar) {
        New-ADGroup -Name $grupo.Name -SamAccountName $grupo.SamAccountName -GroupScope Global -GroupCategory Security -Path $path -ErrorAction Stop | Out-Null
    }
}
Write-Host 'Fin. No se crean usuarios, no se cambian GPO ni DNS.' -ForegroundColor Green
