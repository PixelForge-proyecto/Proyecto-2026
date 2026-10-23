@extends('layouts.app')

@section('title', 'Página no encontrada | Translogística')

@section('content')
    <x-error-page codigo="404" titulo="Página no encontrada"
        mensaje="La página que buscás no existe o ya no se encuentra disponible." icono="bi-search" />
@endsection
