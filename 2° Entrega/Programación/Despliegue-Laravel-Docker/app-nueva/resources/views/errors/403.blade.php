@extends('layouts.app')

@section('title', 'Acceso no autorizado | Translogística')

@section('content')
    <x-error-page codigo="403" titulo="Acceso no autorizado" mensaje="No tenés permisos para acceder a esta sección."
        icono="bi-shield-lock-fill" />
@endsection
