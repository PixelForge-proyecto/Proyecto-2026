@extends('layouts.app')

@section('title', 'Seguimientos | Translogística')

@section('content')
<div class="min-vh-100 bg-light">
    <x-panel-navbar />

    <main class="container py-5">
        <div
            class="d-flex flex-column flex-md-row
                    justify-content-between align-items-md-center
                    gap-3 mb-4">
            <div>
                <h1 class="fw-bold mb-1">
                    Seguimientos
                </h1>

                <p class="text-secondary mb-0">
                    Consulta y actualización del estado
                    de las cargas.
                </p>
            </div>

            <a
                href="{{ route('panel.seguimientos.create') }}"
                class="btn btn-warning fw-bold">
                <i class="bi bi-plus-circle me-2"></i>
                Nuevo seguimiento
            </a>
        </div>

        @if (session('status'))
        <div
            class="alert alert-success"
            role="alert">
            {{ session('status') }}
        </div>
        @endif

        <section class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form
                    method="GET"
                    action="{{ route('panel.seguimientos.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-lg-5">
                            <label
                                for="buscar-seguimiento"
                                class="form-label fw-semibold">
                                Buscar operación
                            </label>

                            <input
                                type="search"
                                id="buscar-seguimiento"
                                name="buscar"
                                value="{{ $buscar }}"
                                class="form-control"
                                placeholder="Ficha, CRT, DUA o cliente">
                        </div>

                        <div class="col-12 col-md-7 col-lg-4">
                            <label
                                for="filtro-etapa"
                                class="form-label fw-semibold">
                                Etapa
                            </label>

                            <select
                                id="filtro-etapa"
                                name="etapa"
                                class="form-select">
                                <option value="">
                                    Todas
                                </option>

                                <option
                                    value="carga"
                                    @selected($etapa==='carga' )>
                                    Carga
                                </option>

                                <option
                                    value="deposito"
                                    @selected($etapa==='deposito' )>
                                    Depósito
                                </option>

                                <option
                                    value="frontera"
                                    @selected($etapa==='frontera' )>
                                    Frontera
                                </option>

                                <option
                                    value="aduana_internacional"
                                    @selected($etapa==='aduana_internacional' )>
                                    Aduana internacional
                                </option>

                                <option
                                    value="aduana_nacional"
                                    @selected($etapa==='aduana_nacional' )>
                                    Aduana nacional
                                </option>

                                <option
                                    value="liberacion"
                                    @selected($etapa==='liberacion' )>
                                    Liberación
                                </option>

                                <option
                                    value="entrega"
                                    @selected($etapa==='entrega' )>
                                    Entrega
                                </option>
                            </select>
                        </div>

                        <div class="col-12 col-md-5 col-lg-3">
                            <button
                                type="submit"
                                class="btn btn-dark w-100">
                                <i class="bi bi-search me-2"></i>
                                Buscar
                            </button>
                        </div>
                    </div>

                    @if ($buscar !== '' || $etapa !== '')
                    <div class="mt-3">
                        <a
                            href="{{ route('panel.seguimientos.index') }}"
                            class="text-decoration-none">
                            Limpiar filtros
                        </a>
                    </div>
                    @endif
                </form>
            </div>
        </section>

        <section class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h2 class="h5 fw-bold mb-0">
                    Operaciones en seguimiento
                </h2>
            </div>

            <div class="table-responsive">
                <table
                    class="table table-hover
                            align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">
                                Ficha
                            </th>

                            <th scope="col">
                                CRT
                            </th>

                            <th scope="col">
                                Cliente
                            </th>

                            <th scope="col">
                                Etapa actual
                            </th>

                            <th scope="col">
                                Última fecha
                            </th>

                            <th
                                scope="col"
                                class="text-end">
                                Acciones
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($seguimientos as $seguimiento)
                        @php
                        $nombreEtapa = match (
                        $seguimiento->etapa
                        ) {
                        'carga' =>
                        'Carga',

                        'deposito' =>
                        'Depósito',

                        'frontera' =>
                        'Frontera',

                        'aduana_internacional' =>
                        'Aduana internacional',

                        'aduana_nacional' =>
                        'Aduana nacional',

                        'liberacion' =>
                        'Liberación',

                        'entrega' =>
                        'Entrega',

                        default =>
                        'Sin especificar',
                        };

                        $colorEtapa = match (
                        $seguimiento->etapa
                        ) {
                        'carga' =>
                        'secondary',

                        'deposito' =>
                        'info',

                        'frontera' =>
                        'warning',

                        'aduana_internacional' =>
                        'dark',

                        'aduana_nacional' =>
                        'primary',

                        'liberacion' =>
                        'primary',

                        'entrega' =>
                        'success',

                        default =>
                        'secondary',
                        };
                        @endphp

                        <tr>
                            <td>
                                {{ $seguimiento->ficha->num_ficha ?? $seguimiento->ficha->id }}
                            </td>

                            <td>
                                {{ $seguimiento->ficha->num_crt ?? 'Sin especificar' }}
                            </td>

                            <td>
                                {{ $seguimiento->ficha->cliente?->nombre ?? 'Sin especificar' }}
                            </td>

                            <td>
                                <span
                                    class="badge
                                                text-bg-{{ $colorEtapa }}">
                                    {{ $nombreEtapa }}
                                </span>
                            </td>

                            <td>
                                {{ $seguimiento->fecha?->format('d/m/Y') ?? 'Sin especificar' }}
                            </td>

                            <td class="text-end">
                                <a
                                    href="{{ route('panel.seguimientos.show', $seguimiento) }}"
                                    class="btn btn-sm
                                                btn-outline-dark">
                                    Ver detalle
                                </a>

                                <a
                                    href="{{ route('panel.seguimientos.edit', $seguimiento) }}"
                                    class="btn btn-sm
                                                btn-warning">
                                    Actualizar
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td
                                colspan="6"
                                class="text-center
                                            text-secondary py-5">
                                No hay seguimientos que
                                coincidan con la búsqueda.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white text-secondary">
                Se muestra el seguimiento más reciente
                de cada ficha.
            </div>
        </section>
    </main>
</div>
@endsection