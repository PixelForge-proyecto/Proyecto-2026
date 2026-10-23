@extends('layouts.app')

@section('title', 'Panel principal | Translogística')

@section('content')
<div class="min-vh-100 bg-light">
    <x-panel-navbar />

    <main class="container py-5">
        <div class="mb-5">
            <h1 class="fw-bold">
                Panel principal
            </h1>

            <p class="text-secondary mb-0">
                Gestión interna de las operaciones de Translogística.
            </p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-12 col-md-6 col-xl-3">
                <a href="{{ route('panel.clientes.index') }}" class="text-decoration-none text-dark d-block h-100">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-people-fill fs-1 text-warning"></i>

                            <h2 class="h5 mt-3">
                                Clientes
                            </h2>

                            <p class="display-6 fw-bold mb-0">
                                {{ $cantidadClientes }}
                            </p>

                            <small class="text-secondary">
                                Clientes activos
                            </small>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <a href="{{ route('panel.fichas.index') }}" class="text-decoration-none text-dark d-block h-100">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-file-earmark-text-fill fs-1 text-warning"></i>

                            <h2 class="h5 mt-3">
                                Fichas
                            </h2>

                            <p class="display-6 fw-bold mb-0">
                                {{ $cantidadFichas }}
                            </p>

                            <small class="text-secondary">
                                Total de fichas registradas
                            </small>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <a href="{{ route('panel.transportistas.index') }}"
                    class="text-decoration-none text-dark d-block h-100">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-truck fs-1 text-warning"></i>

                            <h2 class="h5 mt-3">
                                Transportistas
                            </h2>

                            <p class="display-6 fw-bold mb-0">
                                {{ $cantidadTransportistas }}
                            </p>

                            <small class="text-secondary">
                                Transportistas activos
                            </small>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <a href="{{ route('panel.seguimientos.index') }}"
                    class="text-decoration-none text-dark d-block h-100">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-check-circle-fill fs-1 text-success"></i>

                            <h2 class="h5 mt-3">
                                Entregas
                            </h2>

                            <p class="display-6 fw-bold mb-0">
                                {{ $cantidadEntregasMes }}
                            </p>

                            <small class="text-secondary">
                                Completadas este mes
                            </small>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <section>
            <h2 class="h4 fw-bold mb-4">
                Acciones principales
            </h2>

            <div class="row g-4">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-person-plus-fill fs-2 text-warning"></i>

                            <h3 class="h5 mt-3">
                                Registrar cliente
                            </h3>

                            <p class="text-secondary">
                                Crear una cuenta para un nuevo cliente.
                            </p>

                            <a href="{{ route('panel.clientes.create') }}"
                                class="btn btn-warning fw-semibold">
                                Nuevo cliente
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-file-earmark-plus-fill fs-2 text-warning"></i>

                            <h3 class="h5 mt-3">
                                Crear ficha
                            </h3>

                            <p class="text-secondary">
                                Registrar una nueva operación de transporte.
                            </p>

                            <a href="{{ route('panel.fichas.create') }}"
                                class="btn btn-warning fw-semibold">
                                Nueva ficha
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-search fs-2 text-warning"></i>

                            <h3 class="h5 mt-3">
                                Consultar operaciones
                            </h3>

                            <p class="text-secondary">
                                Buscar fichas y consultar su seguimiento.
                            </p>

                            <a href="{{ route('panel.fichas.index') }}"
                                class="btn btn-warning fw-semibold">
                                Ver operaciones
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>
@endsection