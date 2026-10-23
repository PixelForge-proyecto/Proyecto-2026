@extends('layouts.app')

@section('title', 'Nuevo despachante local | Translogística')

@section('content')
<div class="min-vh-100 bg-light">
    <x-panel-navbar />

    <main class="container py-5">
        <div class="mb-4">
            <a href="{{ route('panel.despachantes.index') }}" class="text-decoration-none text-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Volver a despachantes locales
            </a>
        </div>

        <div class="mb-4">
            <h1 class="fw-bold mb-1">
                Nuevo despachante local
            </h1>

            <p class="text-secondary mb-0">
                Ingresá los datos del despachante de aduana de Uruguay
                que podrá ser seleccionado en las fichas.
            </p>
        </div>

        @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
        @endif

        <section class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('panel.despachantes.store') }}">
                    @csrf

                    <div class="row g-4">
                        <div class="col-12">
                            <label for="nombre" class="form-label fw-semibold">
                                Nombre del despachante
                            </label>

                            <input type="text" id="nombre" name="nombre"
                                value="{{ old('nombre') }}"
                                class="form-control @error('nombre') is-invalid @enderror"
                                maxlength="255" required>

                            @error('nombre')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="email" class="form-label fw-semibold">
                                Correo electrónico
                            </label>

                            <input type="email" id="email" name="email"
                                value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror"
                                maxlength="255"
                                placeholder="Ejemplo: contacto@despachante.com">

                            @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                            <div class="form-text">
                                Campo opcional.
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="telefono" class="form-label fw-semibold">
                                Teléfono del despachante
                            </label>

                            <input type="text" id="telefono" name="telefono"
                                value="{{ old('telefono') }}"
                                class="form-control @error('telefono') is-invalid @enderror"
                                maxlength="50"
                                placeholder="Ejemplo: 2900 0000">

                            @error('telefono')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                            <div class="form-text">
                                Campo opcional.
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="direccion" class="form-label fw-semibold">
                                Dirección
                            </label>

                            <input type="text" id="direccion" name="direccion"
                                value="{{ old('direccion') }}"
                                class="form-control @error('direccion') is-invalid @enderror"
                                maxlength="255"
                                placeholder="Ejemplo: Av. 18 de Julio 1234, Montevideo">

                            @error('direccion')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                            <div class="form-text">
                                Campo opcional.
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="gestor" class="form-label fw-semibold">
                                Gestor
                            </label>

                            <input type="text" id="gestor" name="gestor"
                                value="{{ old('gestor') }}"
                                class="form-control @error('gestor') is-invalid @enderror"
                                maxlength="255"
                                placeholder="Nombre del gestor">

                            @error('gestor')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                            <div class="form-text">
                                Campo opcional.
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="gestor_telefono" class="form-label fw-semibold">
                                Teléfono del gestor
                            </label>

                            <input type="text" id="gestor_telefono" name="gestor_telefono"
                                value="{{ old('gestor_telefono') }}"
                                class="form-control @error('gestor_telefono') is-invalid @enderror"
                                maxlength="50"
                                placeholder="Ejemplo: 099 123 456">

                            @error('gestor_telefono')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                            <div class="form-text">
                                Campo opcional.
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="contacto" class="form-label fw-semibold">
                                Contacto
                            </label>

                            <input type="text" id="contacto" name="contacto"
                                value="{{ old('contacto') }}"
                                class="form-control @error('contacto') is-invalid @enderror"
                                maxlength="255"
                                placeholder="Nombre o información de contacto adicional">

                            @error('contacto')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                            <div class="form-text">
                                Campo opcional.
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 mt-4">
                        <a href="{{ route('panel.despachantes.index') }}"
                            class="btn btn-outline-secondary">
                            Cancelar
                        </a>

                        <button type="submit" class="btn btn-warning fw-bold">
                            <i class="bi bi-check-circle me-2"></i>
                            Registrar despachante
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </main>
</div>
@endsection