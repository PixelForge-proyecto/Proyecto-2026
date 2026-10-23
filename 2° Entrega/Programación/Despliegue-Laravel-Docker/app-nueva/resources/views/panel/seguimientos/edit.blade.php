@extends('layouts.app')

@section('title', 'Actualizar seguimiento | Translogística')

@section('content')
<div class="min-vh-100 bg-light">
    <x-panel-navbar />

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="mb-4">
                    <a
                        href="{{ route('panel.seguimientos.index') }}"
                        class="text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i>
                        Volver a seguimientos
                    </a>
                </div>

                <section class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-dark text-white py-3">
                        <h1 class="h4 mb-0">
                            Actualizar seguimiento
                        </h1>
                    </div>

                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-12 col-md-4">
                                <span class="text-secondary d-block">
                                    Número de ficha
                                </span>

                                <strong>
                                    {{ $datos['numero'] }}
                                </strong>
                            </div>

                            <div class="col-12 col-md-4">
                                <span class="text-secondary d-block">
                                    CRT
                                </span>

                                <strong>
                                    {{ $datos['crt'] }}
                                </strong>
                            </div>

                            <div class="col-12 col-md-4">
                                <span class="text-secondary d-block">
                                    Cliente
                                </span>

                                <strong>
                                    {{ $datos['cliente'] }}
                                </strong>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                        @endif

                        @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <p class="fw-bold mb-2">
                                Revisá los datos ingresados:
                            </p>

                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form
                            method="POST"
                            action="{{ route('panel.seguimientos.update', $seguimiento) }}">
                            @csrf

                            <div class="row g-4">
                                <div class="col-12 col-md-6">
                                    <label
                                        for="etapa"
                                        class="form-label fw-semibold">
                                        Etapa
                                    </label>

                                    <select
                                        id="etapa"
                                        name="etapa"
                                        class="form-select @error('etapa') is-invalid @enderror">
                                        <option value="">
                                            Sin etapa
                                        </option>

                                        <option
                                            value="carga"
                                            @selected(
                                            old( 'etapa' ,
                                            $datos['etapa_valor']
                                            )==='carga'
                                            )>
                                            Carga
                                        </option>

                                        <option
                                            value="deposito"
                                            @selected(
                                            old( 'etapa' ,
                                            $datos['etapa_valor']
                                            )==='deposito'
                                            )>
                                            Depósito
                                        </option>

                                        <option
                                            value="frontera"
                                            @selected(
                                            old( 'etapa' ,
                                            $datos['etapa_valor']
                                            )==='frontera'
                                            )>
                                            Frontera
                                        </option>

                                        <option
                                            value="aduana_internacional"
                                            @selected(
                                            old( 'etapa' ,
                                            $datos['etapa_valor']
                                            )==='aduana_internacional'
                                            )>
                                            Aduana internacional
                                        </option>

                                        <option
                                            value="aduana_nacional"
                                            @selected(
                                            old( 'etapa' ,
                                            $datos['etapa_valor']
                                            )==='aduana_nacional'
                                            )>
                                            Aduana nacional
                                        </option>

                                        <option
                                            value="liberacion"
                                            @selected(
                                            old( 'etapa' ,
                                            $datos['etapa_valor']
                                            )==='liberacion'
                                            )>
                                            Liberación
                                        </option>

                                        <option
                                            value="entrega"
                                            @selected(
                                            old( 'etapa' ,
                                            $datos['etapa_valor']
                                            )==='entrega'
                                            )>
                                            Entrega
                                        </option>
                                    </select>

                                    @error('etapa')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror

                                    <div class="form-text">
                                        Al seleccionar una etapa registrada,
                                        se cargarán sus datos automáticamente.
                                        Si la etapa aún no existe, los campos
                                        quedarán vacíos.
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label
                                        for="fecha"
                                        class="form-label fw-semibold">
                                        Fecha
                                    </label>

                                    <input
                                        type="date"
                                        id="fecha"
                                        name="fecha"
                                        value="{{ old('fecha', $datos['fecha']) }}"
                                        class="form-control @error('fecha') is-invalid @enderror">

                                    @error('fecha')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror

                                    <div class="form-text">
                                        La fecha es obligatoria cuando se
                                        selecciona una etapa.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label
                                        for="canal_aduanero"
                                        class="form-label fw-semibold">
                                        Canal aduanero
                                    </label>

                                    <select
                                        id="canal_aduanero"
                                        name="canal_aduanero"
                                        class="form-select @error('canal_aduanero') is-invalid @enderror">
                                        <option value="">
                                            Sin asignar
                                        </option>

                                        <option
                                            value="verde"
                                            @selected(
                                            old( 'canal_aduanero' ,
                                            $datos['canal_aduanero']
                                            )==='verde'
                                            )>
                                            Verde
                                        </option>

                                        <option
                                            value="naranja"
                                            @selected(
                                            old( 'canal_aduanero' ,
                                            $datos['canal_aduanero']
                                            )==='naranja'
                                            )>
                                            Naranja
                                        </option>

                                        <option
                                            value="rojo"
                                            @selected(
                                            old( 'canal_aduanero' ,
                                            $datos['canal_aduanero']
                                            )==='rojo'
                                            )>
                                            Rojo
                                        </option>
                                    </select>

                                    @error('canal_aduanero')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror

                                    <div class="form-text">
                                        Campo opcional. Solo se habilita
                                        para Aduana internacional y
                                        Aduana nacional.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="alert alert-secondary mb-0">
                                        <strong>Verde:</strong>
                                        mercadería liberada.

                                        <br>

                                        <strong>Naranja:</strong>
                                        inspección documental.

                                        <br>

                                        <strong>Rojo:</strong>
                                        inspección física de la mercadería.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label
                                        for="observacion"
                                        class="form-label fw-semibold">
                                        Observaciones
                                    </label>

                                    <textarea
                                        id="observacion"
                                        name="observacion"
                                        class="form-control @error('observacion') is-invalid @enderror"
                                        rows="4"
                                        maxlength="2000"
                                        placeholder="Ingresá novedades u observaciones.">{{ old('observacion', $datos['observacion']) }}</textarea>

                                    @error('observacion')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror

                                    <div class="form-text">
                                        Se puede registrar una observación
                                        sin etapa ni fecha.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-check">
                                        <input
                                            type="checkbox"
                                            id="notificar_cliente"
                                            name="notificar_cliente"
                                            class="form-check-input @error('notificar_cliente') is-invalid @enderror"
                                            value="1"
                                            @checked(
                                            session()->hasOldInput()
                                        ? old('notificar_cliente')
                                        : $datos['notificar_cliente']
                                        )
                                        >

                                        <label
                                            for="notificar_cliente"
                                            class="form-check-label">
                                            Notificar al cliente sobre esta
                                            actualización
                                        </label>

                                        @error('notificar_cliente')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 d-flex flex-column flex-sm-row justify-content-end gap-2">
                                    <a
                                        href="{{ route('panel.seguimientos.show', $seguimiento) }}"
                                        class="btn btn-outline-secondary">
                                        Cancelar
                                    </a>

                                    <button
                                        type="submit"
                                        class="btn btn-warning fw-bold">
                                        <i class="bi bi-check-circle-fill me-2"></i>
                                        Guardar actualización
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </main>
</div>

<script type="application/json" id="datos-etapas-seguimiento">
    @json($datosEtapas ?? [])
</script>
@endsection