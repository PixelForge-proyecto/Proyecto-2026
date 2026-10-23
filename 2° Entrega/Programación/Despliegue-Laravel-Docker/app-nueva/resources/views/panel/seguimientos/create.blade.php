@extends('layouts.app')

@section('title', 'Nuevo seguimiento | Translogística')

@section('content')
<div class="min-vh-100 bg-light">
    <x-panel-navbar />

    <main class="container py-5">
        <div class="mb-4">
            <a href="{{ route('panel.seguimientos.index') }}"
                class="text-decoration-none text-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Volver a seguimientos
            </a>
        </div>

        <div class="mb-4">
            <h1 class="fw-bold mb-1">
                Nuevo seguimiento
            </h1>

            <p class="text-secondary mb-0">
                Registrá una etapa, una observación o ambas
                para una ficha.
            </p>
        </div>

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

        @if ($fichas->isEmpty())
        <div class="alert alert-warning" role="alert">
            No hay fichas activas disponibles. Primero
            debés registrar una ficha.
        </div>
        @endif

        <section class="card border-0 shadow-sm">
            <div class="card-header bg-dark text-white py-3">
                <h2 class="h5 mb-0">
                    Datos del seguimiento
                </h2>
            </div>

            <div class="card-body p-4">
                <form method="POST"
                    action="{{ route('panel.seguimientos.store') }}">
                    @csrf

                    <div class="row g-4">
                        <div class="col-12">
                            <label for="ficha_id"
                                class="form-label fw-semibold">
                                Ficha
                            </label>

                            <select id="ficha_id"
                                name="ficha_id"
                                class="form-select
                                        @error('ficha_id')
                                            is-invalid
                                        @enderror"
                                required>
                                <option value="">
                                    Seleccionar una ficha
                                </option>

                                @foreach ($fichas as $ficha)
                                <option value="{{ $ficha->id }}"
                                    @selected(
                                    (string) old('ficha_id')===(string) $ficha->id
                                    )>
                                    Ficha
                                    {{ $ficha->num_ficha ?? $ficha->id }}
                                    —
                                    {{ $ficha->cliente?->nombre ?? 'Cliente sin especificar' }}

                                    @if ($ficha->num_crt)
                                    — CRT {{ $ficha->num_crt }}
                                    @endif
                                </option>
                                @endforeach
                            </select>

                            @error('ficha_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="etapa"
                                class="form-label fw-semibold">
                                Etapa
                            </label>

                            <select id="etapa"
                                name="etapa"
                                class="form-select
                                        @error('etapa')
                                            is-invalid
                                        @enderror">
                                <option value="">
                                    Sin etapa
                                </option>

                                <option value="carga"
                                    @selected(old('etapa')==='carga' )>
                                    Carga
                                </option>

                                <option value="deposito"
                                    @selected(old('etapa')==='deposito' )>
                                    Depósito
                                </option>

                                <option value="frontera"
                                    @selected(old('etapa')==='frontera' )>
                                    Frontera
                                </option>

                                <option value="aduana_internacional"
                                    @selected(
                                    old('etapa')==='aduana_internacional'
                                    )>
                                    Aduana internacional
                                </option>

                                <option value="aduana_nacional"
                                    @selected(
                                    old('etapa')==='aduana_nacional'
                                    )>
                                    Aduana nacional
                                </option>

                                <option value="liberacion"
                                    @selected(
                                    old('etapa')==='liberacion'
                                    )>
                                    Liberación
                                </option>

                                <option value="entrega"
                                    @selected(old('etapa')==='entrega' )>
                                    Entrega
                                </option>
                            </select>

                            @error('etapa')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                            <div class="form-text">
                                Depósito es una etapa opcional.
                                Si seleccionás cualquier etapa,
                                también debés ingresar su fecha.
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="fecha"
                                class="form-label fw-semibold">
                                Fecha
                            </label>

                            <input type="date"
                                id="fecha"
                                name="fecha"
                                value="{{ old('fecha') }}"
                                class="form-control
                                        @error('fecha')
                                            is-invalid
                                        @enderror">

                            @error('fecha')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                            <div class="form-text">
                                La fecha es obligatoria cuando
                                se selecciona una etapa.
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="canal_aduanero"
                                class="form-label fw-semibold">
                                Canal aduanero
                            </label>

                            <select id="canal_aduanero"
                                name="canal_aduanero"
                                class="form-select
                                        @error('canal_aduanero')
                                            is-invalid
                                        @enderror">
                                <option value="">
                                    Sin especificar
                                </option>

                                <option value="verde"
                                    @selected(
                                    old('canal_aduanero')==='verde'
                                    )>
                                    Verde
                                </option>

                                <option value="naranja"
                                    @selected(
                                    old('canal_aduanero')==='naranja'
                                    )>
                                    Naranja
                                </option>

                                <option value="rojo"
                                    @selected(
                                    old('canal_aduanero')==='rojo'
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
                                Campo opcional. Solo está disponible
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
                            <label for="observacion"
                                class="form-label fw-semibold">
                                Observación
                            </label>

                            <textarea id="observacion"
                                name="observacion"
                                rows="5"
                                maxlength="2000"
                                class="form-control
                                        @error('observacion')
                                            is-invalid
                                        @enderror"
                                placeholder="Ingresá información adicional sobre el seguimiento.">{{ old('observacion') }}</textarea>

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
                                <input type="checkbox"
                                    id="notificar_cliente"
                                    name="notificar_cliente"
                                    value="1"
                                    class="form-check-input
                                            @error('notificar_cliente')
                                                is-invalid
                                            @enderror"
                                    @checked(old('notificar_cliente'))>

                                <label for="notificar_cliente"
                                    class="form-check-label">
                                    Marcar este seguimiento para
                                    notificar al cliente
                                </label>

                                @error('notificar_cliente')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div
                        class="d-flex flex-column flex-sm-row
                                justify-content-end gap-2 mt-4">
                        <a href="{{ route('panel.seguimientos.index') }}"
                            class="btn btn-outline-secondary">
                            Cancelar
                        </a>

                        <button type="submit"
                            class="btn btn-warning fw-bold"
                            @disabled($fichas->isEmpty())>
                            <i class="bi bi-check-circle me-2"></i>
                            Registrar seguimiento
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </main>
</div>
@endsection