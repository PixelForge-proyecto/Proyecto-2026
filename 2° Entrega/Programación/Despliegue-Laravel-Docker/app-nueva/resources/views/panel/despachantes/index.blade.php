@extends('layouts.app')

@section('title', 'Despachantes locales | Translogística')

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
                    Despachantes locales
                </h1>

                <p class="text-secondary mb-0">
                    Consulta y administración de los despachantes
                    de aduana locales de Uruguay.
                </p>
            </div>

            <a href="{{ route('panel.despachantes.create') }}" class="btn btn-warning fw-bold">
                <i class="bi bi-person-vcard me-2"></i>
                Nuevo despachante
            </a>
        </div>

        <section class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('panel.despachantes.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-lg-8">
                            <label for="buscar-despachante" class="form-label fw-semibold">
                                Buscar despachante local
                            </label>

                            <input type="search" id="buscar-despachante" name="buscar"
                                value="{{ request('buscar') }}" class="form-control"
                                placeholder="Nombre, correo electrónico, contacto o gestor">
                        </div>

                        <div class="col-12 col-lg-4">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-dark flex-grow-1">
                                    <i class="bi bi-search me-2"></i>
                                    Buscar
                                </button>

                                @if (request()->filled('buscar'))
                                <a href="{{ route('panel.despachantes.index') }}"
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
                    Despachantes registrados
                </h2>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Correo electrónico</th>
                            <th scope="col">Contacto</th>
                            <th scope="col">Gestor</th>
                            <th scope="col" class="text-end">
                                Acciones
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($despachantes as $despachante)
                        <tr>
                            <td>
                                {{ $despachante->nombre }}
                            </td>

                            <td>
                                {{ $despachante->email ?: 'Sin especificar' }}
                            </td>

                            <td>
                                {{ $despachante->contacto ?: 'Sin especificar' }}
                            </td>

                            <td>
                                {{ $despachante->gestor ?: 'Sin especificar' }}
                            </td>

                            <td class="text-end">
                                <a href="{{ route('panel.despachantes.show', $despachante) }}"
                                    class="btn btn-sm btn-outline-dark">
                                    Ver
                                </a>

                                <a href="{{ route('panel.despachantes.edit', $despachante) }}"
                                    class="btn btn-sm btn-outline-warning">
                                    Editar
                                </a>

                                <form method="POST"
                                    action="{{ route('panel.despachantes.destroy', $despachante) }}"
                                    class="d-inline formulario-baja-despachante"
                                    data-nombre-despachante="{{ $despachante->nombre }}">
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
                            <td colspan="5" class="text-center text-secondary py-5">
                                <i class="bi bi-person-vcard fs-1 d-block mb-2"></i>

                                No hay despachantes locales activos que
                                coincidan con la búsqueda.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white text-secondary">
                Despachantes locales activos encontrados:
                {{ $despachantes->count() }}
            </div>
        </section>
    </main>
</div>
@endsection