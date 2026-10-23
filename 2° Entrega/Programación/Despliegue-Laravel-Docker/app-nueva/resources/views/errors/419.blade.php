@extends('layouts.app')

@section('title', 'Sesión vencida | Translogística')

@section('content')
    <x-error-page codigo="419" titulo="La sesión venció"
        mensaje="La sesión o el formulario vencieron. Volvé a ingresar e intentá nuevamente." icono="bi-clock-history" />
@endsection
