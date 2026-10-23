@extends('layouts.app')

@section('title', 'Fichas | Translogística')

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

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="fw-bold mb-1">Fichas</h1>
                <p class="text-secondary mb-0">Consulta y administración de operaciones de transporte.</p>
            </div>

            <a href="{{ route('panel.fichas.create') }}" class="btn btn-warning fw-bold">
                <i class="bi bi-file-earmark-plus-fill me-2"></i>
                Nueva ficha
            </a>
        </div>

        <section class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('panel.fichas.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-lg-9">
                            <label for="buscar-ficha" class="form-label fw-semibold">Buscar operación</label>
                            <input type="search" id="buscar-ficha" name="buscar" value="{{ request('buscar') }}"
                                class="form-control" placeholder="Número de ficha, CRT, DUA o cliente">
                        </div>
                        <div class="col-12 col-lg-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-dark flex-grow-1">
                                    <i class="bi bi-search me-2"></i>
                                    Buscar
                                </button>
                                @if (request()->filled('buscar'))
                                <a href="{{ route('panel.fichas.index') }}" class="btn btn-outline-secondary"
                                    title="Limpiar búsqueda" aria-label="Limpiar búsqueda">
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
                <h2 class="h5 fw-bold mb-0">Operaciones registradas</h2>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Ficha</th>
                            <th scope="col">CRT</th>
                            <th scope="col">Cliente nacional</th>
                            <th scope="col">Cliente internacional</th>
                            <th scope="col">Operación</th>
                            <th scope="col">Transportista</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Fecha</th>
                            <th scope="col" class="text-end">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($fichas as $ficha)
                        <tr>
                            <td>{{ $ficha->num_ficha ?: $ficha->id }}</td>
                            <td>{{ $ficha->num_crt ?: 'Sin especificar' }}</td>
                            <td>{{ $ficha->cliente_nombre ?: $ficha->cliente?->nombre ?: 'Sin especificar' }}</td>
                            <td>
                                <div>{{ $ficha->clienteInternacional?->nombre ?: 'Sin especificar' }}</div>
                                @if ($ficha->clienteInternacional)
                                <small class="text-secondary">
                                    {{ $ficha->tipo_operacion === 'importacion' ? 'Exportador' : ($ficha->tipo_operacion === 'exportacion' ? 'Importador' : 'Rol sin definir') }}
                                    ·
                                    {{ $ficha->clienteInternacional->origen === 'brasil' ? 'Brasil' : ($ficha->clienteInternacional->origen === 'argentina' ? 'Argentina' : 'País sin especificar') }}
                                </small>
                                @endif
                            </td>
                            <td>
                                @if ($ficha->tipo_operacion === 'importacion')
                                <span class="badge text-bg-primary">Importación</span>
                                @elseif ($ficha->tipo_operacion === 'exportacion')
                                <span class="badge text-bg-success">Exportación</span>
                                @else
                                <span class="badge text-bg-secondary">Sin especificar</span>
                                @endif
                            </td>
                            <td>{{ $ficha->transportista?->nombre ?: 'Sin asignar' }}</td>
                            <td>
                                @switch($ficha->estado)
                                @case('iniciada')
                                <span class="badge text-bg-secondary">Iniciada</span>
                                @break
                                @case('en_curso')
                                <span class="badge text-bg-warning">En curso</span>
                                @break
                                @case('en_frontera')
                                <span class="badge text-bg-info">En frontera</span>
                                @break
                                @case('entregada')
                                <span class="badge text-bg-success">Entregada</span>
                                @break
                                @default
                                <span class="badge text-bg-secondary">
                                    {{ ucfirst(str_replace('_', ' ', $ficha->estado)) }}
                                </span>
                                @endswitch
                            </td>
                            <td>{{ $ficha->created_at?->format('d/m/Y') ?: 'Sin fecha' }}</td>
                            <td class="text-end">
                                <a href="{{ route('panel.fichas.show', $ficha) }}"
                                    class="btn btn-sm btn-outline-dark">Ver</a>
                                <a href="{{ route('panel.fichas.edit', $ficha) }}"
                                    class="btn btn-sm btn-outline-warning">Editar</a>
                                <form method="POST" action="{{ route('panel.fichas.destroy', $ficha) }}"
                                    class="d-inline formulario-anular-ficha"
                                    data-numero-ficha="{{ $ficha->num_ficha ?: $ficha->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Anular</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-secondary py-5">
                                <i class="bi bi-file-earmark-text fs-1 d-block mb-2"></i>
                                No hay fichas activas que coincidan con la búsqueda.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white text-secondary">
                Fichas activas encontradas: {{ $fichas->count() }}
            </div>
        </section>
    </main>
</div>
@endsection