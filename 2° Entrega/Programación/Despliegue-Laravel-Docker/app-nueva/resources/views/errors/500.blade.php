@extends('layouts.app')

@section('title', 'Error interno | Translogística')

@section('content')
    <x-error-page codigo="500" titulo="Ocurrió un error inesperado"
        mensaje="No pudimos completar la solicitud. Intentá nuevamente dentro de unos momentos."
        icono="bi-exclamation-triangle-fill" />
@endsection
