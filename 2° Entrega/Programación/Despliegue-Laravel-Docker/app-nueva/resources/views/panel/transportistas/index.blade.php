@extends('layouts.app')

@section('title', 'Transportistas | Translogística')

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

            <div
                class="d-flex flex-column flex-md-row
                    justify-content-between align-items-md-center
                    gap-3 mb-4">
                <div>
                    <h1 class="fw-bold mb-1">
                        Transportistas
                    </h1>

                    <p class="text-secondary mb-0">
                        Consulta y administración de los transportistas
                        registrados.
                    </p>
                </div>

                <a href="{{ route('panel.transportistas.create') }}" class="btn btn-warning fw-bold">
                    <i class="bi bi-truck me-2"></i>
                    Nuevo transportista
                </a>
            </div>

            <section class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('panel.transportistas.index') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-12 col-lg-8">
                                <label for="buscar-transportista" class="form-label fw-semibold">
                                    Buscar transportista
                                </label>

                                <input type="search" id="buscar-transportista" name="buscar"
                                    value="{{ request('buscar') }}" class="form-control"
                                    placeholder="Nombre, correo electrónico o contacto">
                            </div>

                            <div class="col-12 col-lg-4">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-dark flex-grow-1">
                                        <i class="bi bi-search me-2"></i>
                                        Buscar
                                    </button>

                                    @if (request()->filled('buscar'))
                                        <a href="{{ route('panel.transportistas.index') }}"
                                            class="btn btn-outline-secondary" title="Limpiar búsqueda"
                                            aria-label="Limpiar búsqueda">
                                            <i class="bi bi-x-lg"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>

            <section class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h2 class="h5 fw-bold mb-0">
                        Transportistas registrados
                    </h2>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">Nombre</th>
                                <th scope="col">Correo electrónico</th>
                                <th scope="col">Contacto</th>

                                <th scope="col" class="text-end">
                                    Acciones
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($transportistas as $transportista)
                                <tr>
                                    <td>
                                        {{ $transportista->nombre }}
                                    </td>

                                    <td>
                                        {{ $transportista->email ?: 'Sin especificar' }}
                                    </td>

                                    <td>
                                        {{ $transportista->contacto ?: 'Sin especificar' }}
                                    </td>

                                    <td class="text-end">
                                        <a href="{{ route('panel.transportistas.show', $transportista) }}"
                                            class="btn btn-sm btn-outline-dark">
                                            Ver
                                        </a>

                                        <a href="{{ route('panel.transportistas.edit', $transportista) }}"
                                            class="btn btn-sm btn-outline-warning">
                                            Editar
                                        </a>

                                        <form method="POST"
                                            action="{{ route('panel.transportistas.destroy', $transportista) }}"
                                            class="d-inline formulario-baja-transportista"
                                            data-nombre-transportista="{{ $transportista->nombre }}">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                Dar de baja
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-secondary py-5">
                                        <i
                                            class="bi bi-truck fs-1
                                                d-block mb-2"></i>

                                        No hay transportistas activos que
                                        coincidan con la búsqueda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-white text-secondary">
                    Transportistas activos encontrados:
                    {{ $transportistas->count() }}
                </div>
            </section>
        </main>
    </div>
@endsection
