@extends('layouts.app')

@section('title', 'Mis cargas | Translogística')

@section('content')
    <div class="min-vh-100 bg-light">
        <x-cliente-navbar />

        <main class="container py-5">
            <header class="mb-4">
                <h1 class="fw-bold mb-2">
                    Mis cargas
                </h1>

                <p class="text-secondary mb-0">
                    Consultá las operaciones asociadas a tu cuenta.
                </p>
            </header>

            <section class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('cliente.cargas.buscar') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-12 col-md-8">
                                <label for="buscar-dua" class="form-label fw-semibold">
                                    Buscar por número de DUA
                                </label>

                                <input type="text" id="buscar-dua" name="dua" value="{{ old('dua') }}"
                                    class="form-control
                                        @error('dua') is-invalid @enderror"
                                    placeholder="Ejemplo: 123456" inputmode="numeric" maxlength="6" pattern="[0-9]{1,6}">

                                @error('dua')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-4">
                                <button type="submit" class="btn btn-dark w-100">
                                    <i class="bi bi-search me-2"></i>
                                    Buscar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>

            <section class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h2 class="h5 fw-bold mb-0">
                        Cargas registradas
                    </h2>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">CRT</th>
                                <th scope="col">DUA</th>
                                <th scope="col">Operación</th>
                                <th scope="col">Estado</th>

                                <th scope="col" class="text-end">
                                    Acción
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($cargas as $carga)
                                @php
                                    $nombreOperacion = match ($carga->tipo_operacion) {
                                        'importacion' => 'Importación',
                                        'exportacion' => 'Exportación',
                                        default => 'Sin especificar',
                                    };

                                    $nombreEstado = match ($carga->estado) {
                                        'iniciada' => 'Iniciada',
                                        'en_curso' => 'En curso',
                                        'en_frontera' => 'En frontera',
                                        'entregada' => 'Entregada',
                                        default => 'Sin especificar',
                                    };

                                    $colorEstado = match ($carga->estado) {
                                        'iniciada' => 'secondary',
                                        'en_curso' => 'primary',
                                        'en_frontera' => 'warning',
                                        'entregada' => 'success',
                                        default => 'secondary',
                                    };
                                @endphp

                                <tr>
                                    <td>
                                        {{ $carga->num_crt ?? 'Sin especificar' }}
                                    </td>

                                    <td>
                                        {{ $carga->dua ?? 'Sin especificar' }}
                                    </td>

                                    <td>
                                        {{ $nombreOperacion }}
                                    </td>

                                    <td>
                                        <span class="badge text-bg-{{ $colorEstado }}">
                                            {{ $nombreEstado }}
                                        </span>
                                    </td>

                                    <td class="text-end">
                                        <a href="{{ route('cliente.cargas.show', $carga->id) }}"
                                            class="btn btn-sm btn-outline-dark">
                                            Ver estado
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-secondary py-5">
                                        No hay cargas registradas para
                                        tu cuenta.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-white text-secondary">
                    Solo se muestran las cargas asociadas a tu cuenta.
                </div>
            </section>
        </main>
    </div>
@endsection
