@extends('layouts.app')

@section('title', 'Detalle del cliente | Translogística')

@section('content')
<div class="min-vh-100 bg-light">
    <x-panel-navbar />

    <main class="container py-5">
        @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert"
                aria-label="Cerrar"></button>
        </div>
        @endif

        <div class="mb-4">
            <a href="{{ route('panel.clientes.index') }}"
                class="text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i>
                Volver a clientes
            </a>
        </div>

        <div
            class="d-flex flex-column flex-md-row
                justify-content-between align-items-md-center
                gap-3 mb-4">
            <div>
                <h1 class="fw-bold mb-1">
                    Detalle del cliente
                </h1>

                <p class="text-secondary mb-0">
                    Información registrada del cliente.
                </p>
            </div>

            <a href="{{ route('panel.clientes.edit', $cliente) }}"
                class="btn btn-warning fw-bold">
                <i class="bi bi-pencil-square me-2"></i>
                Editar cliente
            </a>
        </div>

        <section class="card border-0 shadow-sm">
            <div class="card-header bg-dark text-white py-3">
                <h2 class="h5 mb-0">
                    {{ $cliente->nombre }}
                </h2>
            </div>

            <div class="card-body p-4">
                <div class="row g-4">

                    {{-- Nombre --}}
                    <div class="col-12 col-md-6">
                        <span class="text-secondary d-block">
                            Nombre
                        </span>

                        <strong>
                            {{ $cliente->nombre }}
                        </strong>
                    </div>

                    {{-- País de origen --}}
                    <div class="col-12 col-md-6">
                        <span class="text-secondary d-block">
                            País de origen
                        </span>

                        <strong>
                            @switch($cliente->origen)
                            @case('local')
                            Uruguay
                            @break

                            @case('brasil')
                            Brasil
                            @break

                            @case('argentina')
                            Argentina
                            @break

                            @default
                            {{ ucfirst($cliente->origen ?? '') }}
                            @endswitch
                        </strong>
                    </div>

                    {{-- Estado --}}
                    <div class="col-12 col-md-6">
                        <span class="text-secondary d-block">
                            Estado
                        </span>

                        @if ($cliente->estado === 'activo')
                        <span class="badge text-bg-success">
                            Activo
                        </span>
                        @else
                        <span class="badge text-bg-secondary">
                            Inactivo
                        </span>
                        @endif
                    </div>

                    {{-- RUC/RUT del cliente uruguayo --}}
                    @if ($cliente->esNacional())
                    <div class="col-12 col-md-6">
                        <span class="text-secondary d-block">
                            RUC/RUT
                        </span>

                        <strong>
                            {{ $cliente->ruc_rut ?: 'Sin especificar' }}
                        </strong>
                    </div>
                    @endif

                    {{-- Contacto --}}
                    <div class="col-12 col-md-6">
                        <span class="text-secondary d-block">
                            Contacto
                        </span>

                        <strong>
                            {{ $cliente->contacto_nombre ?: 'Sin especificar' }}
                        </strong>
                    </div>

                    {{-- Email del contacto --}}
                    <div class="col-12 col-md-6">
                        <span class="text-secondary d-block">
                            Email del contacto
                        </span>

                        <strong>
                            {{ $cliente->contacto_email ?: 'Sin especificar' }}
                        </strong>
                    </div>

                    {{-- Teléfono --}}
                    <div class="col-12 col-md-6">
                        <span class="text-secondary d-block">
                            Teléfono
                        </span>

                        <strong>
                            {{ $cliente->telefono ?: 'Sin especificar' }}
                        </strong>
                    </div>

                    {{-- Dirección --}}
                    <div class="col-12 col-md-6">
                        <span class="text-secondary d-block">
                            Dirección
                        </span>

                        <strong>
                            {{ $cliente->direccion ?: 'Sin especificar' }}
                        </strong>
                    </div>

                    {{-- Despachante del cliente internacional --}}
                    @if ($cliente->esInternacional())
                    <div class="col-12 col-md-6">
                        <span class="text-secondary d-block">
                            Despachante
                        </span>

                        <strong>
                            {{ $cliente->despachante ?: 'Sin especificar' }}
                        </strong>
                    </div>
                    @endif

                    {{-- Acceso al sistema: solamente clientes nacionales --}}
                    @if ($cliente->esNacional())
                    <div class="col-12">
                        <hr>
                    </div>

                    <div class="col-12 col-md-6">
                        <span class="text-secondary d-block">
                            Usuario
                        </span>

                        <strong>
                            {{ $cliente->usuario ?: 'Sin especificar' }}
                        </strong>
                    </div>

                    <div class="col-12">
                        <h3 class="h5 fw-bold">
                            Acceso del cliente
                        </h3>

                        <p class="text-secondary mb-0">
                            La contraseña se almacena de forma protegida
                            y no se muestra en esta pantalla.
                        </p>
                    </div>
                    @endif

                </div>
            </div>
        </section>
    </main>
</div>
@endsection