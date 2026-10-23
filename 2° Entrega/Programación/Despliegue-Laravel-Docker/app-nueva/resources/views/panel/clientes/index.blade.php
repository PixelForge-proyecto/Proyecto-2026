@extends('layouts.app')

@section('title', 'Clientes | Translogística')

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

        <div
            class="d-flex flex-column flex-md-row
                    justify-content-between align-items-md-center
                    gap-3 mb-4">
            <div>
                <h1 class="fw-bold mb-1">
                    Clientes
                </h1>

                <p class="text-secondary mb-0">
                    Consulta y administración de clientes nacionales
                    e internacionales.
                </p>
            </div>

            <a href="{{ route('panel.clientes.create') }}"
                class="btn btn-warning fw-bold">
                <i class="bi bi-person-plus-fill me-2"></i>
                Nuevo cliente
            </a>
        </div>

        <section class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('panel.clientes.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-lg-6">
                            <label for="buscar-cliente"
                                class="form-label fw-semibold">
                                Buscar cliente
                            </label>

                            <input type="search" id="buscar-cliente"
                                name="buscar" value="{{ request('buscar') }}"
                                class="form-control"
                                placeholder="Nombre, usuario o dirección">
                        </div>

                        <div class="col-12 col-md-6 col-lg-3">
                            <label for="origen" class="form-label fw-semibold">
                                País de origen
                            </label>

                            <select id="origen" name="origen"
                                class="form-select">
                                <option value="">
                                    Todos
                                </option>

                                <option value="local"
                                    @selected(request('origen')==='local' )>
                                    Uruguay
                                </option>

                                <option value="brasil"
                                    @selected(request('origen')==='brasil' )>
                                    Brasil
                                </option>

                                <option value="argentina"
                                    @selected(request('origen')==='argentina' )>
                                    Argentina
                                </option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="d-flex gap-2">
                                <button type="submit"
                                    class="btn btn-dark flex-grow-1">
                                    <i class="bi bi-search me-2"></i>
                                    Buscar
                                </button>

                                @if (request()->filled('buscar') || request()->filled('origen'))
                                <a href="{{ route('panel.clientes.index') }}"
                                    class="btn btn-outline-secondary"
                                    title="Limpiar búsqueda"
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
                    Clientes registrados
                </h2>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Usuario / Acceso</th>
                            <th scope="col">Origen</th>
                            <th scope="col">Contacto</th>
                            <th scope="col">Teléfono</th>

                            <th scope="col" class="text-end">
                                Acciones
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($clientes as $cliente)
                        <tr>
                            <td>
                                {{ $cliente->nombre }}
                            </td>

                            <td>
                                @if (filled($cliente->usuario))
                                {{ $cliente->usuario }}
                                @else
                                <span class="text-secondary">
                                    Sin acceso
                                </span>
                                @endif
                            </td>

                            <td>
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
                            </td>

                            <td>
                                {{ $cliente->contacto_nombre ?: 'Sin especificar' }}
                            </td>

                            <td>
                                {{ $cliente->telefono ?: 'Sin especificar' }}
                            </td>

                            <td class="text-end">
                                <div
                                    class="d-flex flex-wrap gap-2
                                                justify-content-end">
                                    <a href="{{ route('panel.clientes.show', $cliente) }}"
                                        class="btn btn-sm btn-outline-dark">
                                        Ver
                                    </a>

                                    <a href="{{ route('panel.clientes.edit', $cliente) }}"
                                        class="btn btn-sm btn-outline-warning">
                                        Editar
                                    </a>

                                    <form method="POST"
                                        action="{{ route('panel.clientes.destroy', $cliente) }}"
                                        class="d-inline formulario-baja-cliente"
                                        data-nombre-cliente="{{ $cliente->nombre }}">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="btn btn-sm btn-outline-danger">
                                            Dar de baja
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6"
                                class="text-center text-secondary py-5">
                                <i class="bi bi-people fs-1 d-block mb-2"></i>

                                No hay clientes registrados que coincidan
                                con la búsqueda.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white text-secondary">
                Clientes activos encontrados: {{ $clientes->count() }}
            </div>
        </section>
    </main>
</div>
@endsection