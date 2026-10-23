@extends('layouts.app')

@section('title', 'Detalle del seguimiento | Translogística')

@section('content')
@php
$etapas = [
'carga' => 'Carga',
'deposito' => 'Depósito',
'frontera' => 'Frontera',
'aduana_internacional' => 'Aduana internacional',
'aduana_nacional' => 'Aduana nacional',
'liberacion' => 'Liberación',
'entrega' => 'Entrega',
];

$etapasAduaneras = [
'aduana_internacional',
'aduana_nacional',
];

$etapasOpcionales = [
'deposito',
'aduana_internacional',
'aduana_nacional',
];

$coloresCanal = [
'verde' => 'success',
'naranja' => 'warning',
'rojo' => 'danger',
];

$explicacionesCanal = [
'verde' =>
'La mercadería fue liberada sin inspección documental ni física.',

'naranja' =>
'La documentación de la mercadería debe ser inspeccionada.',

'rojo' =>
'La mercadería debe pasar por una inspección física.',
];

$fechaActual = $seguimiento
->fecha
?->format('d/m/Y');

$esEtapaAduanera = in_array(
$datos['etapa_valor'],
$etapasAduaneras,
true
);

$fechasEtapas = [];
$registrosEtapas = [];

foreach ($etapas as $valorEtapa => $nombreEtapa) {
$registroEtapa = $seguimiento
->ficha
->seguimientos
->filter(
fn ($registro) =>
$registro->etapa === $valorEtapa
&& $registro->fecha !== null
)
->sortByDesc(
fn ($registro) =>
$registro->fecha->format('Y-m-d')
. '-'
. str_pad(
(string) $registro->id,
20,
'0',
STR_PAD_LEFT
)
)
->first();

$registrosEtapas[$valorEtapa] =
$registroEtapa;

$fechasEtapas[$valorEtapa] =
$registroEtapa
?->fecha
?->format('d/m/Y');
}

$aduanaInternacional =
$registrosEtapas['aduana_internacional']
?? null;

$aduanaNacional =
$registrosEtapas['aduana_nacional']
?? null;
@endphp

<div class="min-vh-100 bg-light">
    <x-panel-navbar />

    <main class="container py-5">
        <div class="mb-4">
            <a
                href="{{ route('panel.seguimientos.index') }}"
                class="text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i>
                Volver a seguimientos
            </a>
        </div>

        @if (session('status'))
        <div
            class="alert alert-success"
            role="alert">
            {{ session('status') }}
        </div>
        @endif

        <div
            class="d-flex flex-column flex-lg-row
                    justify-content-between align-items-lg-center
                    gap-3 mb-4">
            <div>
                <h1 class="fw-bold mb-1">
                    Seguimiento de la ficha
                    {{ $datos['numero'] }}
                </h1>

                <p class="text-secondary mb-0">
                    Detalle del registro y progreso
                    de la operación.
                </p>
            </div>

            <div class="d-flex flex-column flex-sm-row gap-2">
                <a
                    href="{{ route('panel.seguimientos.edit', $seguimiento) }}"
                    class="btn btn-warning fw-bold">
                    <i class="bi bi-pencil-square me-2"></i>
                    Editar seguimiento
                </a>

                <form
                    method="POST"
                    action="{{ route('panel.seguimientos.destroy', $seguimiento) }}"
                    onsubmit="return confirm(
                            '¿Confirmás que querés eliminar este seguimiento?'
                        );">
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-outline-danger fw-bold w-100">
                        <i class="bi bi-trash me-2"></i>
                        Eliminar
                    </button>
                </form>
            </div>
        </div>

        <section class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-dark text-white py-3">
                <h2 class="h5 mb-0">
                    Datos de la operación
                </h2>
            </div>

            <div class="card-body">
                <div class="row g-4">
                    <div class="col-12 col-sm-6 col-lg-3">
                        <span class="text-secondary d-block">
                            Número de ficha
                        </span>

                        <strong>
                            {{ $datos['numero'] }}
                        </strong>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <span class="text-secondary d-block">
                            CRT
                        </span>

                        <strong>
                            {{ $datos['crt'] }}
                        </strong>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <span class="text-secondary d-block">
                            Cliente
                        </span>

                        <strong>
                            {{ $datos['cliente'] }}
                        </strong>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <span class="text-secondary d-block">
                            Operación
                        </span>

                        <span class="badge text-bg-primary">
                            {{ $datos['operacion'] }}
                        </span>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <span class="text-secondary d-block">
                            Paso fronterizo
                        </span>

                        <strong>
                            {{ $seguimiento->ficha->frontera_completa }}
                        </strong>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <span class="text-secondary d-block">
                            Estado de la ficha
                        </span>

                        <span class="badge text-bg-warning">
                            {{ $datos['estado'] }}
                        </span>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <span class="text-secondary d-block">
                            Etapa del registro
                        </span>

                        <strong>
                            {{ $etapas[$datos['etapa_valor']] ?? 'Observación sin etapa' }}
                        </strong>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <span class="text-secondary d-block">
                            Canal internacional
                        </span>

                        @if ($aduanaInternacional?->canal_aduanero)
                        <span
                            class="badge text-bg-{{ $coloresCanal[$aduanaInternacional->canal_aduanero] }}">
                            {{ ucfirst($aduanaInternacional->canal_aduanero) }}
                        </span>
                        @elseif ($aduanaInternacional)
                        <span class="text-secondary">
                            Sin especificar
                        </span>
                        @else
                        <span class="text-secondary">
                            Sin registrar
                        </span>
                        @endif
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <span class="text-secondary d-block">
                            Canal nacional
                        </span>

                        @if ($aduanaNacional?->canal_aduanero)
                        <span
                            class="badge text-bg-{{ $coloresCanal[$aduanaNacional->canal_aduanero] }}">
                            {{ ucfirst($aduanaNacional->canal_aduanero) }}
                        </span>
                        @elseif ($aduanaNacional)
                        <span class="text-secondary">
                            Sin especificar
                        </span>
                        @else
                        <span class="text-secondary">
                            Sin registrar
                        </span>
                        @endif
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <span class="text-secondary d-block">
                            Fecha del registro
                        </span>

                        <strong>
                            {{ $fechaActual ?? 'Sin fecha' }}
                        </strong>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <span class="text-secondary d-block">
                            Notificación al cliente
                        </span>

                        @if ($datos['notificar_cliente'])
                        <span class="badge text-bg-success">
                            Sí
                        </span>
                        @else
                        <span class="badge text-bg-secondary">
                            No
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <section class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h2 class="h5 fw-bold mb-0">
                    Progreso registrado
                </h2>
            </div>

            <div class="card-body p-4">
                <div class="row g-4">
                    @foreach ($etapas as $valorEtapa => $nombreEtapa)
                    @php
                    $fechaEtapa =
                    $fechasEtapas[$valorEtapa]
                    ?? null;

                    $esOpcional = in_array(
                    $valorEtapa,
                    $etapasOpcionales,
                    true
                    );
                    @endphp

                    <div class="col-12 col-md-6 col-xl-3">
                        <div
                            class="card h-100
                                        {{ $fechaEtapa ? 'border-success' : '' }}">
                            <div class="card-body">
                                @if ($fechaEtapa)
                                <i
                                    class="bi bi-check-circle-fill
                                                    fs-2 text-success"></i>
                                @elseif ($esOpcional)
                                <i
                                    class="bi bi-dash-circle
                                                    fs-2 text-secondary"></i>
                                @else
                                <i
                                    class="bi bi-clock
                                                    fs-2 text-secondary"></i>
                                @endif

                                <h3 class="h6 fw-bold mt-3">
                                    {{ $nombreEtapa }}
                                </h3>

                                @if ($fechaEtapa)
                                <p class="mb-1">
                                    {{ $fechaEtapa }}
                                </p>

                                <small class="text-success">
                                    Registrada
                                </small>
                                @elseif ($esOpcional)
                                <p class="mb-1 text-secondary">
                                    Sin registro
                                </p>

                                <small class="text-secondary">
                                    Etapa opcional
                                </small>
                                @else
                                <p class="mb-1 text-secondary">
                                    Sin fecha
                                </p>

                                <small class="text-secondary">
                                    Pendiente
                                </small>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        @if ($esEtapaAduanera && $datos['canal_aduanero'])
        <section class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h2 class="h5 fw-bold mb-0">
                    Información aduanera del registro
                </h2>
            </div>

            <div class="card-body">
                <div
                    class="alert
                                alert-{{ $coloresCanal[$datos['canal_aduanero']] }}
                                mb-0"
                    role="alert">
                    <div class="d-flex gap-3">
                        <i class="bi bi-shield-check fs-3"></i>

                        <div>
                            <h3 class="h6 fw-bold">
                                {{ $etapas[$datos['etapa_valor']] }}:
                                canal
                                {{ ucfirst($datos['canal_aduanero']) }}
                            </h3>

                            <p class="mb-0">
                                {{ $explicacionesCanal[$datos['canal_aduanero']] }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif

        <section class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h2 class="h5 fw-bold mb-0">
                    Observaciones
                </h2>
            </div>

            <div class="card-body">
                <p class="mb-0">
                    {{ $datos['observacion'] ?: 'Sin observaciones registradas.' }}
                </p>
            </div>
        </section>
    </main>
</div>
@endsection