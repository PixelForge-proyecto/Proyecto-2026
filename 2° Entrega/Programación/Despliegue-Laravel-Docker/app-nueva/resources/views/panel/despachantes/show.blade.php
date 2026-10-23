@extends('layouts.app')

@section('title', 'Detalle del despachante local | Translogística')

@section('content')
<div class="min-vh-100 bg-light">
    <x-panel-navbar />

    <main class="container py-5">
        @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
        @endif

        <div class="mb-4">
            <a href="{{ route('panel.despachantes.index') }}" class="text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i>
                Volver a despachantes locales
            </a>
        </div>

        <div
            class="d-flex flex-column flex-md-row
                    justify-content-between align-items-md-center
                    gap-3 mb-4">
            <div>
                <h1 class="fw-bold mb-1">
                    Detalle del despachante local
                </h1>

                <p class="text-secondary mb-0">
                    Información registrada para el despachante
                    de aduana seleccionado.
                </p>
            </div>

            <a href="{{ route('panel.despachantes.edit', $despachante) }}"
                class="btn btn-warning fw-bold">
                <i class="bi bi-pencil-square me-2"></i>
                Editar
            </a>
        </div>

        <section class="card border-0 shadow-sm">
            <div class="card-header bg-dark text-white py-3">
                <h2 class="h5 fw-bold mb-0">
                    {{ $despachante->nombre }}
                </h2>
            </div>

            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-12 col-md-6">
                        <p class="text-secondary mb-1">
                            Nombre del despachante
                        </p>

                        <p class="fw-semibold mb-0">
                            {{ $despachante->nombre }}
                        </p>
                    </div>

                    <div class="col-12 col-md-6">
                        <p class="text-secondary mb-1">
                            Correo electrónico
                        </p>

                        <p class="fw-semibold mb-0">
                            {{ $despachante->email ?: 'Sin especificar' }}
                        </p>
                    </div>

                    <div class="col-12 col-md-6">
                        <p class="text-secondary mb-1">
                            Teléfono del despachante
                        </p>

                        <p class="fw-semibold mb-0">
                            {{ $despachante->telefono ?: 'Sin especificar' }}
                        </p>
                    </div>

                    <div class="col-12 col-md-6">
                        <p class="text-secondary mb-1">
                            Dirección
                        </p>

                        <p class="fw-semibold mb-0">
                            {{ $despachante->direccion ?: 'Sin especificar' }}
                        </p>
                    </div>

                    <div class="col-12 col-md-6">
                        <p class="text-secondary mb-1">
                            Gestor
                        </p>

                        <p class="fw-semibold mb-0">
                            {{ $despachante->gestor ?: 'Sin especificar' }}
                        </p>
                    </div>

                    <div class="col-12 col-md-6">
                        <p class="text-secondary mb-1">
                            Teléfono del gestor
                        </p>

                        <p class="fw-semibold mb-0">
                            {{ $despachante->gestor_telefono ?: 'Sin especificar' }}
                        </p>
                    </div>

                    <div class="col-12 col-md-6">
                        <p class="text-secondary mb-1">
                            Contacto
                        </p>

                        <p class="fw-semibold mb-0">
                            {{ $despachante->contacto ?: 'Sin especificar' }}
                        </p>
                    </div>

                    <div class="col-12 col-md-6">
                        <p class="text-secondary mb-1">
                            Estado
                        </p>

                        <span class="badge text-bg-success">
                            Activo
                        </span>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>
@endsection