@extends('layouts.app')

@section('title', 'Estado de la carga | Translogística')

@section('content')
<div class="min-vh-100 bg-light">
    <x-cliente-navbar />

    <main class="container py-5">
        <div
            class="d-flex flex-column flex-md-row
                    justify-content-between align-items-md-center
                    gap-3 mb-4">
            <div>
                <h1 class="fw-bold mb-1">
                    Estado de la carga
                </h1>

                <p class="text-secondary mb-0">
                    Información y seguimiento de la operación.
                </p>
            </div>

            <a
                href="{{ route('cliente.cargas.index') }}"
                class="btn btn-outline-dark">
                <i class="bi bi-arrow-left me-2"></i>
                Volver a mis cargas
            </a>
        </div>

        <section class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-dark text-white py-3">
                <h2 class="h5 fw-bold mb-0">
                    Datos de la operación
                </h2>
            </div>

            <div class="card-body">
                <div class="row g-4">
                    <div class="col-12 col-md-6 col-lg-4">
                        <small class="text-secondary d-block">
                            Número de CRT
                        </small>

                        <span class="fw-semibold">
                            {{ $carga['crt'] }}
                        </span>
                    </div>

                    <div class="col-12 col-md-6 col-lg-4">
                        <small class="text-secondary d-block">
                            Número de DUA
                        </small>

                        <span class="fw-semibold">
                            {{ $carga['dua'] }}
                        </span>
                    </div>

                    <div class="col-12 col-md-6 col-lg-4">
                        <small class="text-secondary d-block">
                            Tipo de operación
                        </small>

                        <span class="fw-semibold">
                            {{ $carga['operacion'] }}
                        </span>
                    </div>

                    <div class="col-12 col-md-6 col-lg-4">
                        <small class="text-secondary d-block">
                            Estado actual
                        </small>

                        <span class="fw-semibold">
                            {{ $carga['estado'] }}
                        </span>
                    </div>

                    <div class="col-12 col-md-6 col-lg-4">
                        <small class="text-secondary d-block">
                            Ciudad de origen
                        </small>

                        <span class="fw-semibold">
                            {{ $carga['origen'] }}
                        </span>
                    </div>

                    <div class="col-12 col-md-6 col-lg-4">
                        <small class="text-secondary d-block">
                            Ciudad de destino
                        </small>

                        <span class="fw-semibold">
                            {{ $carga['destino'] }}
                        </span>
                    </div>

                    <div class="col-12 col-md-6 col-lg-4">
                        <small class="text-secondary d-block">
                            Paso fronterizo
                        </small>

                        <span class="fw-semibold">
                            {{ $carga['frontera'] }}
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <section class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h2 class="h5 fw-bold mb-0">
                    Seguimiento
                </h2>
            </div>

            <div class="card-body p-4">
                <div class="row g-4">
                    {{-- Carga --}}
                    <div class="col-12 col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div
                                class="d-flex align-items-center gap-3">
                                @if ($carga['fecha_carga'])
                                <i
                                    class="bi bi-check-circle-fill
                                                fs-3 text-success"></i>
                                @else
                                <i
                                    class="bi bi-clock-fill
                                                fs-3 text-secondary"></i>
                                @endif

                                <div>
                                    <h3 class="h6 fw-bold mb-1">
                                        Carga
                                    </h3>

                                    @if ($carga['fecha_carga'])
                                    <p class="mb-0">
                                        {{ $carga['fecha_carga'] }}
                                    </p>
                                    @else
                                    <p class="text-secondary mb-0">
                                        Pendiente
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Depósito opcional --}}
                    <div class="col-12 col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div
                                class="d-flex align-items-center gap-3">
                                @if ($carga['fecha_deposito'])
                                <i
                                    class="bi bi-check-circle-fill
                                                fs-3 text-success"></i>
                                @else
                                <i
                                    class="bi bi-dash-circle-fill
                                                fs-3 text-secondary"></i>
                                @endif

                                <div>
                                    <h3 class="h6 fw-bold mb-1">
                                        Llegada al depósito
                                    </h3>

                                    @if ($carga['fecha_deposito'])
                                    <p class="mb-0">
                                        {{ $carga['fecha_deposito'] }}
                                    </p>
                                    @else
                                    <p class="text-secondary mb-0">
                                        Sin registro
                                    </p>

                                    <small class="text-secondary">
                                        Etapa opcional
                                    </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Frontera --}}
                    <div class="col-12 col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div
                                class="d-flex align-items-center gap-3">
                                @if ($carga['fecha_frontera'])
                                <i
                                    class="bi bi-check-circle-fill
                                                fs-3 text-success"></i>
                                @else
                                <i
                                    class="bi bi-clock-fill
                                                fs-3 text-secondary"></i>
                                @endif

                                <div>
                                    <h3 class="h6 fw-bold mb-1">
                                        Frontera
                                    </h3>

                                    @if ($carga['fecha_frontera'])
                                    <p class="mb-0">
                                        {{ $carga['fecha_frontera'] }}
                                    </p>
                                    @else
                                    <p class="text-secondary mb-0">
                                        Pendiente
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Aduana internacional --}}
                    <div class="col-12 col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div
                                class="d-flex align-items-start gap-3">
                                @if (!$carga['fecha_aduana_internacional'])
                                <i
                                    class="bi bi-dash-circle-fill
                                                fs-3 text-secondary"></i>
                                @elseif ($carga['canal_aduana_internacional'] === 'verde')
                                <i
                                    class="bi bi-check-circle-fill
                                                fs-3 text-success"></i>
                                @elseif ($carga['canal_aduana_internacional'] === 'naranja')
                                <i
                                    class="bi bi-exclamation-circle-fill
                                                fs-3 text-warning"></i>
                                @elseif ($carga['canal_aduana_internacional'] === 'rojo')
                                <i
                                    class="bi bi-exclamation-triangle-fill
                                                fs-3 text-danger"></i>
                                @else
                                <i
                                    class="bi bi-check-circle-fill
                                                fs-3 text-success"></i>
                                @endif

                                <div>
                                    <h3 class="h6 fw-bold mb-1">
                                        Aduana internacional
                                    </h3>

                                    @if ($carga['fecha_aduana_internacional'])
                                    <p class="mb-2">
                                        {{ $carga['fecha_aduana_internacional'] }}
                                    </p>

                                    @if ($carga['canal_aduana_internacional'] === 'verde')
                                    <span
                                        class="badge text-bg-success">
                                        Canal verde
                                    </span>
                                    @elseif ($carga['canal_aduana_internacional'] === 'naranja')
                                    <span
                                        class="badge text-bg-warning">
                                        Canal naranja
                                    </span>
                                    @elseif ($carga['canal_aduana_internacional'] === 'rojo')
                                    <span
                                        class="badge text-bg-danger">
                                        Canal rojo
                                    </span>
                                    @else
                                    <span class="text-secondary">
                                        Canal sin especificar
                                    </span>
                                    @endif
                                    @else
                                    <p class="text-secondary mb-0">
                                        Sin registro
                                    </p>

                                    <small class="text-secondary">
                                        Puede no corresponder
                                    </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Aduana nacional --}}
                    <div class="col-12 col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div
                                class="d-flex align-items-start gap-3">
                                @if (!$carga['fecha_aduana_nacional'])
                                <i
                                    class="bi bi-dash-circle-fill
                                                fs-3 text-secondary"></i>
                                @elseif ($carga['canal_aduana_nacional'] === 'verde')
                                <i
                                    class="bi bi-check-circle-fill
                                                fs-3 text-success"></i>
                                @elseif ($carga['canal_aduana_nacional'] === 'naranja')
                                <i
                                    class="bi bi-exclamation-circle-fill
                                                fs-3 text-warning"></i>
                                @elseif ($carga['canal_aduana_nacional'] === 'rojo')
                                <i
                                    class="bi bi-exclamation-triangle-fill
                                                fs-3 text-danger"></i>
                                @else
                                <i
                                    class="bi bi-check-circle-fill
                                                fs-3 text-success"></i>
                                @endif

                                <div>
                                    <h3 class="h6 fw-bold mb-1">
                                        Aduana nacional
                                    </h3>

                                    @if ($carga['fecha_aduana_nacional'])
                                    <p class="mb-2">
                                        {{ $carga['fecha_aduana_nacional'] }}
                                    </p>

                                    @if ($carga['canal_aduana_nacional'] === 'verde')
                                    <span
                                        class="badge text-bg-success">
                                        Canal verde
                                    </span>
                                    @elseif ($carga['canal_aduana_nacional'] === 'naranja')
                                    <span
                                        class="badge text-bg-warning">
                                        Canal naranja
                                    </span>
                                    @elseif ($carga['canal_aduana_nacional'] === 'rojo')
                                    <span
                                        class="badge text-bg-danger">
                                        Canal rojo
                                    </span>
                                    @else
                                    <span class="text-secondary">
                                        Canal sin especificar
                                    </span>
                                    @endif
                                    @else
                                    <p class="text-secondary mb-0">
                                        Sin registro
                                    </p>

                                    <small class="text-secondary">
                                        Puede no corresponder
                                    </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Liberación --}}
                    <div class="col-12 col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div
                                class="d-flex align-items-center gap-3">
                                @if ($carga['fecha_liberacion'])
                                <i
                                    class="bi bi-check-circle-fill
                                                fs-3 text-success"></i>
                                @else
                                <i
                                    class="bi bi-clock-fill
                                                fs-3 text-secondary"></i>
                                @endif

                                <div>
                                    <h3 class="h6 fw-bold mb-1">
                                        Liberación
                                    </h3>

                                    @if ($carga['fecha_liberacion'])
                                    <p class="mb-0">
                                        {{ $carga['fecha_liberacion'] }}
                                    </p>
                                    @else
                                    <p class="text-secondary mb-0">
                                        Pendiente
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Entrega --}}
                    <div class="col-12 col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div
                                class="d-flex align-items-center gap-3">
                                @if ($carga['fecha_entrega'])
                                <i
                                    class="bi bi-check-circle-fill
                                                fs-3 text-success"></i>
                                @else
                                <i
                                    class="bi bi-clock-fill
                                                fs-3 text-secondary"></i>
                                @endif

                                <div>
                                    <h3 class="h6 fw-bold mb-1">
                                        Entrega
                                    </h3>

                                    @if ($carga['fecha_entrega'])
                                    <p class="mb-0">
                                        {{ $carga['fecha_entrega'] }}
                                    </p>
                                    @else
                                    <p class="text-secondary mb-0">
                                        Pendiente
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Historial de observaciones --}}
                    <div class="col-12">
                        <section class="border rounded p-4">
                            <div
                                class="d-flex align-items-center
                                        gap-2 mb-3">
                                <i
                                    class="bi bi-chat-left-text-fill
                                            text-warning fs-4"></i>

                                <h3 class="h5 fw-bold mb-0">
                                    Historial de observaciones
                                </h3>
                            </div>

                            @forelse ($carga['observaciones'] as $observacion)
                            <article
                                class="bg-light border rounded
                                            p-3 mb-3">
                                <div
                                    class="d-flex flex-column
                                                flex-sm-row
                                                justify-content-between
                                                gap-2 mb-2">
                                    <strong>
                                        {{ $observacion['etapa'] }}
                                    </strong>

                                    <span class="text-secondary">
                                        <i
                                            class="bi bi-calendar3 me-1"></i>

                                        {{ $observacion['fecha'] }}
                                    </span>
                                </div>

                                <p class="mb-0">
                                    {{ $observacion['texto'] }}
                                </p>
                            </article>
                            @empty
                            <div
                                class="alert alert-light
                                            border mb-0">
                                <p class="text-secondary mb-0">
                                    No hay observaciones comunicadas
                                    para esta carga.
                                </p>
                            </div>
                            @endforelse
                        </section>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-white text-secondary">
                Información actualizada desde el seguimiento
                de la carga.
            </div>
        </section>
    </main>
</div>
@endsection