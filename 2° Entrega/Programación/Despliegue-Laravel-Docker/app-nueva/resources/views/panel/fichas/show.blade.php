@extends('layouts.app')

@section('title', 'Detalle de la ficha | Translogística')

@section('content')
@php
$sinEspecificar = 'Sin especificar';

$estados = [
'iniciada' => [
'texto' => 'Iniciada',
'color' => 'secondary',
],
'en_curso' => [
'texto' => 'En curso',
'color' => 'primary',
],
'en_frontera' => [
'texto' => 'En frontera',
'color' => 'warning',
],
'entregada' => [
'texto' => 'Entregada',
'color' => 'success',
],
'anulada' => [
'texto' => 'Anulada',
'color' => 'danger',
],
];

$estado = $estados[$ficha->estado] ?? [
'texto' => ucfirst(
str_replace(
'_',
' ',
$ficha->estado
)
),
'color' => 'secondary',
];

$tipoOperacion = match ($ficha->tipo_operacion) {
'importacion' => 'Importación',
'exportacion' => 'Exportación',
default => $sinEspecificar,
};

$rolClienteInternacional = match ($ficha->tipo_operacion) {
'importacion' => 'Exportador',
'exportacion' => 'Importador',
default => $sinEspecificar,
};

$formatearImporte = function ($valor) {
return $valor !== null
? 'US$ ' . number_format(
(float) $valor,
2,
',',
'.'
)
: null;
};

$formatearFecha = function ($fecha) {
return $fecha
? \Illuminate\Support\Carbon::parse(
$fecha
)->format('d/m/Y')
: null;
};

$ultimoSeguimiento = function (
string $etapa
) use ($ficha) {
return $ficha->seguimientos
->where('etapa', $etapa)
->sort(
function (
$primero,
$segundo
) {
$comparacionFecha = strcmp(
(string) $segundo->fecha,
(string) $primero->fecha
);

return $comparacionFecha !== 0
? $comparacionFecha
: $segundo->id <=> $primero->id;
    }
    )
    ->first();
    };

    $seguimientoCarga = $ultimoSeguimiento(
    'carga'
    );

    $seguimientoFrontera = $ultimoSeguimiento(
    'frontera'
    );

    $seguimientoLiberacion = $ultimoSeguimiento(
    'liberacion'
    );

    $seguimientoEntrega = $ultimoSeguimiento(
    'entrega'
    );

    $secciones = [
    'Cliente nacional' => [
    'Nombre' => $ficha->cliente_nombre ?: $ficha->cliente?->nombre,
    'Contacto' => $ficha->cliente_contacto,
    'Teléfono' => $ficha->cliente_telefono,
    'RUC/RUT' => $ficha->cliente_ruc_rut,
    ],

    'Cliente internacional' => [
    'Rol en la operación' => $rolClienteInternacional,
    'Nombre' => $ficha->nombre_exportador ?: $ficha->clienteInternacional?->nombre,
    'País' => match ($ficha->clienteInternacional?->origen) {
    'brasil' => 'Brasil',
    'argentina' => 'Argentina',
    default => null,
    },
    'Contacto' => $ficha->contacto_exportador_nombre,
    'Correo electrónico' => $ficha->contacto_exportador_email,
    'Teléfono' => $ficha->telefono_exportador,
    'Despachante' => $ficha->despachante_exportador,
    ],

    'Datos de la carga' => [
    'Mercadería' =>
    $ficha->mercaderia,

    'Peso bruto' =>
    $ficha->peso_bruto !== null
    ? $ficha->peso_bruto . ' kg'
    : null,

    'Volumen' =>
    $ficha->volumen !== null
    ? $ficha->volumen . ' m³'
    : null,

    'Tipo de bulto' =>
    $ficha->tipo_bulto,

    'Cantidad de bultos' =>
    $ficha->cantidad_bultos,

    'Número de factura' =>
    $ficha->num_factura,
    ],

    'Carga' => [
    'Ciudad de carga' =>
    $ficha->ciudad_carga,

    /*
    * En la ficha interna se muestra exactamente
    * la frontera que fue registrada.
    */
    'Frontera' =>
    $ficha->frontera,

    'Coleta' =>
    $ficha->coleta,
    ],

    'Descarga' => [
    'Ciudad de descarga' =>
    $ficha->ciudad_descarga,

    'Dirección de descarga' =>
    $ficha->direccion_descarga,

    'Teléfono' =>
    $ficha->telefono_descarga,

    'Contacto' =>
    $ficha->contacto_descarga,

    'Correo electrónico' =>
    $ficha->email_descarga,
    ],

    'Despachante local' => [
    'Nombre' =>
    $ficha->despachante_local_nombre,

    'Correo electrónico' =>
    $ficha->despachante_local_email,

    'Teléfono' =>
    $ficha->despachante_local_telefono,

    'Dirección' =>
    $ficha->despachante_local_direccion,

    'Gestor' =>
    $ficha->despachante_local_gestor,

    'Teléfono del gestor' =>
    $ficha->despachante_local_gestor_telefono,

    'Contacto' =>
    $ficha->despachante_local_contacto,

    'DUA' =>
    $ficha->dua,
    ],

    'Información comercial' => [
    'Hecha por' =>
    $ficha->hecha_por,

    'Fecha' => $formatearFecha(
    $ficha->fecha_comercial
    ),

    'Puesta a FOB' => $formatearImporte(
    $ficha->puesta_fob
    ),

    'Flete internacional' => $formatearImporte(
    $ficha->flete_internacional
    ),

    'Flete nacional' => $formatearImporte(
    $ficha->flete_nacional
    ),

    'Ad valorem (%)' =>
    $ficha->ad_valorem_porcentaje !== null
    ? number_format(
    (float) $ficha->ad_valorem_porcentaje,
    2,
    ',',
    '.'
    ) . ' %'
    : null,

    'Valor del ad valorem' => $formatearImporte(
    $ficha->ad_valorem
    ),

    'Despacho de Brasil' => $formatearImporte(
    $ficha->despacho_brasil
    ),

    'Total' => $formatearImporte(
    $ficha->total_comercial
    ),

    'FOB' => $formatearImporte(
    $ficha->fob_usd
    ),

    'Proforma' =>
    $ficha->proforma,

    'Factura definitiva' =>
    $ficha->factura_definitiva,

    'Cantidad de camiones' =>
    $ficha->cantidad_camiones,

    'Flete por camión' =>
    $ficha->flete_por_camion,

    'Observaciones' =>
    $ficha->observaciones_comerciales,
    ],
    ];

    $fechasSeguimiento = [
    'Carga' => $formatearFecha(
    $seguimientoCarga?->fecha
    ),

    'Frontera' => $formatearFecha(
    $seguimientoFrontera?->fecha
    ),

    'Liberación' => $formatearFecha(
    $seguimientoLiberacion?->fecha
    ),

    'Entrega' => $formatearFecha(
    $seguimientoEntrega?->fecha
    ),
    ];
    @endphp

    <div class="min-vh-100 bg-light">
        <x-panel-navbar />

        <main class="container py-5">
            <div class="mb-4">
                <a
                    href="{{ route('panel.fichas.index') }}"
                    class="text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i>
                    Volver a fichas
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
                class="d-flex flex-column flex-md-row
                    justify-content-between align-items-md-center
                    gap-3 mb-4">
                <div>
                    <h1 class="fw-bold mb-1">
                        Ficha N.º
                        {{ $ficha->num_ficha ?: $ficha->id }}
                    </h1>

                    <p class="text-secondary mb-0">
                        Información completa de la operación
                        de transporte.
                    </p>
                </div>

                <a
                    href="{{ route('panel.fichas.edit', $ficha) }}"
                    class="btn btn-warning fw-bold">
                    <i class="bi bi-pencil-square me-2"></i>
                    Editar ficha
                </a>
            </div>

            <section class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white py-3">
                    <h2 class="h5 mb-0">
                        Referencia de la operación
                    </h2>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-12 col-md-6 col-xl">
                            <span class="text-secondary d-block">
                                Tipo de operación
                            </span>

                            <strong>
                                {{ $tipoOperacion }}
                            </strong>
                        </div>

                        <div class="col-12 col-md-6 col-xl">
                            <span class="text-secondary d-block">
                                Número de ficha
                            </span>

                            <strong>
                                {{ $ficha->num_ficha ?: $sinEspecificar }}
                            </strong>
                        </div>

                        <div class="col-12 col-md-6 col-xl">
                            <span class="text-secondary d-block">
                                Transportista
                            </span>

                            <strong>
                                {{ $ficha->transportista?->nombre ?: $sinEspecificar }}
                            </strong>
                        </div>

                        <div class="col-12 col-md-6 col-xl">
                            <span class="text-secondary d-block">
                                Número de CRT
                            </span>

                            <strong>
                                {{ $ficha->num_crt ?: $sinEspecificar }}
                            </strong>
                        </div>

                        <div class="col-12 col-md-6 col-xl">
                            <span class="text-secondary d-block">
                                Estado
                            </span>

                            <span
                                class="badge text-bg-{{ $estado['color'] }}">
                                {{ $estado['texto'] }}
                            </span>
                        </div>
                    </div>
                </div>
            </section>

            <div class="row g-4">
                @foreach ($secciones as $titulo => $campos)
                <div class="col-12 col-lg-6">
                    <section
                        class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-3">
                            <h2 class="h5 fw-bold mb-0">
                                {{ $titulo }}
                            </h2>
                        </div>

                        <div class="card-body">
                            <dl class="row mb-0">
                                @foreach ($campos as $etiqueta => $valor)
                                <dt class="col-sm-5 mb-2">
                                    {{ $etiqueta }}
                                </dt>

                                <dd class="col-sm-7 mb-2">
                                    @if ($valor !== null && $valor !== '')
                                    {{ $valor }}
                                    @else
                                    <span class="text-secondary">
                                        {{ $sinEspecificar }}
                                    </span>
                                    @endif
                                </dd>
                                @endforeach
                            </dl>
                        </div>
                    </section>
                </div>
                @endforeach
            </div>

            <section class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-dark text-white py-3">
                    <h2 class="h5 mb-0">
                        Seguimiento de la operación
                    </h2>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">
                        @foreach ($fechasSeguimiento as $etapa => $fecha)
                        <div class="col-12 col-sm-6 col-xl-3">
                            <span class="text-secondary d-block">
                                {{ $etapa }}
                            </span>

                            <strong>
                                {{ $fecha ?: 'Sin registrar' }}
                            </strong>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </main>
    </div>
    @endsection