@extends('layouts.app')
@section('title', 'Nueva ficha | Translogística')
@section('content')
<div class="min-vh-100 bg-light">
    <x-panel-navbar />
    <main class="container py-5">
        <div class="mb-4">
            <a href="{{ route('panel.fichas.index') }}" class="text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i>
                Volver a fichas
            </a>
        </div>
        <div class="mb-4">
            <h1 class="fw-bold mb-1">
                Nueva ficha
            </h1>
            <p class="text-secondary mb-0">
                Registro de una nueva operación de transporte.
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
        <form id="form-nueva-ficha" method="POST" action="{{ route('panel.fichas.store') }}"
            data-has-old-input="{{ session()->hasOldInput() ? '1' : '0' }}">
            @csrf
            <section class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white py-3">
                    <h2 class="h5 mb-0">
                        Referencia
                    </h2>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6 col-lg-3">
                            <label for="tipo_operacion" class="form-label fw-semibold">
                                Tipo de operación
                            </label>
                            <select id="tipo_operacion" name="tipo_operacion"
                                class="form-select @error('tipo_operacion') is-invalid @enderror" required>
                                <option value="">
                                    Seleccionar
                                </option>
                                <option value="importacion" @selected(old('tipo_operacion')==='importacion' )>
                                    Importación
                                </option>
                                <option value="exportacion" @selected(old('tipo_operacion')==='exportacion' )>
                                    Exportación
                                </option>
                            </select>
                            @error('tipo_operacion')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <label for="numero_ficha" class="form-label fw-semibold">
                                Número de ficha
                            </label>
                            <input type="text" id="numero_ficha" name="numero_ficha"
                                value="{{ old('numero_ficha') }}"
                                class="form-control @error('numero_ficha') is-invalid @enderror" maxlength="50"
                                placeholder="Ej.: 7381">
                            @error('numero_ficha')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <label for="transportista" class="form-label fw-semibold">
                                Transportista
                            </label>
                            <select id="transportista" name="transportista"
                                class="form-select @error('transportista') is-invalid @enderror">
                                <option value="">
                                    Sin transportista asignado
                                </option>
                                @foreach ($transportistas as $transportista)
                                <option value="{{ $transportista->id }}" @selected((string) old('transportista')===(string) $transportista->id)>
                                    {{ $transportista->nombre }}
                                </option>
                                @endforeach
                            </select>
                            @error('transportista')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <label for="num_crt" class="form-label fw-semibold">
                                Número de CRT
                            </label>
                            <input type="text" id="num_crt" name="num_crt" value="{{ old('num_crt') }}"
                                class="form-control @error('num_crt') is-invalid @enderror" maxlength="255"
                                placeholder="Ej.: BR-000123456">
                            @error('num_crt')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </section>
            <section class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white py-3">
                    <h2 class="h5 mb-0">Cliente nacional</h2>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="cliente_id" class="form-label fw-semibold">Cliente nacional (Uruguay)</label>
                            <select id="cliente_id" name="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror" required>
                                <option value="">Seleccionar cliente nacional</option>
                                @foreach ($clientes as $cliente)
                                @if ($cliente->origen === 'local')
                                <option value="{{ $cliente->id }}"
                                    data-nombre="{{ $cliente->nombre }}"
                                    data-contacto="{{ $cliente->contacto_nombre }}"
                                    data-telefono="{{ $cliente->telefono }}"
                                    data-ruc-rut="{{ $cliente->ruc_rut }}"
                                    @selected((string) old('cliente_id')===(string) $cliente->id)>
                                    {{ $cliente->nombre }}
                                </option>
                                @endif
                                @endforeach
                            </select>
                            @error('cliente_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-lg-6">
                            <label for="cliente_nombre" class="form-label fw-semibold">Nombre del cliente en la operación</label>
                            <input type="text" id="cliente_nombre" name="cliente_nombre" value="{{ old('cliente_nombre') }}" class="form-control" maxlength="255">
                            <div class="form-text">Si queda vacío, se utilizará el nombre del cliente asociado.</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="cliente_contacto" class="form-label fw-semibold">Contacto</label>
                            <input type="text" id="cliente_contacto" name="cliente_contacto" value="{{ old('cliente_contacto') }}" class="form-control" maxlength="255">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="cliente_ruc" class="form-label fw-semibold">RUC/RUT</label>
                            <input type="text" id="cliente_ruc" name="cliente_ruc" value="{{ old('cliente_ruc') }}" class="form-control" maxlength="50">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="cliente_telefono" class="form-label fw-semibold">Teléfono</label>
                            <input type="tel" id="cliente_telefono" name="cliente_telefono" value="{{ old('cliente_telefono') }}" class="form-control" maxlength="50">
                        </div>
                    </div>
                </div>
            </section>
            <section class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white py-3">
                    <h2 class="h5 mb-0">Cliente internacional</h2>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="cliente_internacional_id" class="form-label fw-semibold" id="etiqueta-cliente-internacional">Cliente internacional (Brasil o Argentina)</label>
                            <select id="cliente_internacional_id" name="cliente_internacional_id" class="form-select @error('cliente_internacional_id') is-invalid @enderror">
                                <option value="">Sin cliente internacional asignado</option>
                                @foreach ($clientesInternacionales as $clienteInternacional)
                                <option value="{{ $clienteInternacional->id }}"
                                    data-nombre="{{ $clienteInternacional->nombre }}"
                                    data-contacto="{{ $clienteInternacional->contacto_nombre }}"
                                    data-email="{{ $clienteInternacional->contacto_email }}"
                                    data-telefono="{{ $clienteInternacional->telefono }}"
                                    data-despachante="{{ $clienteInternacional->despachante }}"
                                    @selected((string) old('cliente_internacional_id')===(string) $clienteInternacional->id)>
                                    {{ $clienteInternacional->nombre }} ({{ $clienteInternacional->origen === 'brasil' ? 'Brasil' : 'Argentina' }})
                                </option>
                                @endforeach
                            </select>
                            @error('cliente_internacional_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="rol_cliente_internacional" class="form-label fw-semibold">Rol en la operación</label>
                            <input type="text" id="rol_cliente_internacional" class="form-control bg-light" value="Sin definir" readonly aria-readonly="true">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="exportador_nombre" class="form-label fw-semibold" id="etiqueta-nombre-internacional">Nombre del <span data-rol-internacional>Exportador</span></label>
                            <input type="text" id="exportador_nombre" name="exportador_nombre" value="{{ old('exportador_nombre') }}" class="form-control" maxlength="255">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="exportador_contacto" class="form-label fw-semibold">Contacto</label>
                            <input type="text" id="exportador_contacto" name="exportador_contacto" value="{{ old('exportador_contacto') }}" class="form-control" maxlength="255">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="exportador_email" class="form-label fw-semibold">Email del contacto</label>
                            <input type="email" id="exportador_email" name="exportador_email" value="{{ old('exportador_email') }}" class="form-control @error('exportador_email') is-invalid @enderror" maxlength="255">
                            @error('exportador_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="exportador_telefono" class="form-label fw-semibold">Teléfono</label>
                            <input type="tel" id="exportador_telefono" name="exportador_telefono" value="{{ old('exportador_telefono') }}" class="form-control" maxlength="50">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="despachante_exportador" class="form-label fw-semibold">Despachante</label>
                            <input type="text" id="despachante_exportador" name="despachante_exportador" value="{{ old('despachante_exportador') }}" class="form-control" maxlength="255">
                        </div>
                        <div class="col-12">
                            <label for="mercaderia" class="form-label fw-semibold">
                                Mercadería
                            </label>
                            <textarea id="mercaderia" name="mercaderia" class="form-control @error('mercaderia') is-invalid @enderror"
                                rows="3" maxlength="255">{{ old('mercaderia') }}</textarea>
                            @error('mercaderia')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="num_factura" class="form-label fw-semibold">Número de factura</label>
                            <input type="text" id="num_factura" name="num_factura" value="{{ old('num_factura') }}" class="form-control" maxlength="255">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="peso" class="form-label fw-semibold">Peso</label>
                            <div class="input-group">
                                <input type="number" id="peso" name="peso" value="{{ old('peso') }}" class="form-control @error('peso') is-invalid @enderror" min="0" step="0.01">
                                <span class="input-group-text">kg</span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="volumen" class="form-label fw-semibold">
                                Metros cúbicos
                            </label>
                            <div class="input-group">
                                <input type="number" id="volumen" name="volumen" value="{{ old('volumen') }}"
                                    class="form-control @error('volumen') is-invalid @enderror" min="0"
                                    step="0.01">
                                <span class="input-group-text">
                                    m³
                                </span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="cantidad_bultos" class="form-label fw-semibold">
                                Cantidad de bultos
                            </label>
                            <input type="number" id="cantidad_bultos" name="cantidad_bultos"
                                value="{{ old('cantidad_bultos') }}" class="form-control" min="0">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="tipo_bulto" class="form-label fw-semibold">
                                Tipo de bulto
                            </label>
                            <input type="text" id="tipo_bulto" name="tipo_bulto" value="{{ old('tipo_bulto') }}"
                                class="form-control" maxlength="255" placeholder="Ej.: pallets, cajas o bolsas">
                        </div>
                    </div>
                </div>
            </section>
            <section class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white py-3">
                    <h2 class="h5 mb-0">
                        Carga y recorrido
                    </h2>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <label for="ciudad_carga" class="form-label fw-semibold">
                                Ciudad de carga
                            </label>
                            <input type="text" id="ciudad_carga" name="ciudad_carga"
                                value="{{ old('ciudad_carga') }}" class="form-control" maxlength="255">
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="frontera" class="form-label fw-semibold">
                                Frontera
                            </label>
                            <input type="text" id="frontera" name="frontera" value="{{ old('frontera') }}"
                                class="form-control" maxlength="255">
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="coleta" class="form-label fw-semibold">
                                Coleta
                            </label>
                            <input type="text" id="coleta" name="coleta" value="{{ old('coleta') }}"
                                class="form-control" maxlength="255">
                        </div>
                    </div>
                </div>
            </section>
            <section class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white py-3">
                    <h2 class="h5 mb-0">
                        Descarga
                    </h2>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="ciudad_descarga" class="form-label fw-semibold">
                                Ciudad de descarga
                            </label>
                            <input type="text" id="ciudad_descarga" name="ciudad_descarga"
                                value="{{ old('ciudad_descarga') }}" class="form-control" maxlength="255">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="direccion_descarga" class="form-label fw-semibold">
                                Dirección de descarga
                            </label>
                            <input type="text" id="direccion_descarga" name="direccion_descarga"
                                value="{{ old('direccion_descarga') }}" class="form-control" maxlength="255">
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="telefono_descarga" class="form-label fw-semibold">
                                Teléfono
                            </label>
                            <input type="tel" id="telefono_descarga" name="telefono_descarga"
                                value="{{ old('telefono_descarga') }}" class="form-control" maxlength="50">
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="contacto_descarga" class="form-label fw-semibold">
                                Contacto
                            </label>
                            <input type="text" id="contacto_descarga" name="contacto_descarga"
                                value="{{ old('contacto_descarga') }}" class="form-control" maxlength="255">
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="email_descarga" class="form-label fw-semibold">
                                Email
                            </label>
                            <input type="email" id="email_descarga" name="email_descarga"
                                value="{{ old('email_descarga') }}"
                                class="form-control @error('email_descarga') is-invalid @enderror" maxlength="255">
                        </div>
                    </div>
                </div>
            </section>
            <section class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white py-3">
                    <h2 class="h5 mb-0">
                        Despachante nacional
                    </h2>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="selector_despachante_local" class="form-label fw-semibold">Seleccionar despachante local</label>
                            <select id="selector_despachante_local" class="form-select">
                                <option value="">Seleccionar despachante registrado</option>
                                @foreach ($despachantes as $despachante)
                                <option value="{{ $despachante->id }}"
                                    data-nombre="{{ $despachante->nombre }}"
                                    data-email="{{ $despachante->email }}"
                                    data-telefono="{{ $despachante->telefono }}"
                                    data-direccion="{{ $despachante->direccion }}"
                                    data-gestor="{{ $despachante->gestor }}"
                                    data-gestor-telefono="{{ $despachante->gestor_telefono }}"
                                    data-contacto="{{ $despachante->contacto }}">
                                    {{ $despachante->nombre }}
                                </option>
                                @endforeach
                            </select>
                            <div class="form-text">Al seleccionar un despachante, se completan sus datos. Podés editarlos antes de guardar la ficha.</div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="despachante_uy" class="form-label fw-semibold">
                                Despachante
                            </label>
                            <input type="text" id="despachante_uy" name="despachante_uy"
                                value="{{ old('despachante_uy') }}" class="form-control" maxlength="255">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="email_despachante" class="form-label fw-semibold">
                                Email
                            </label>
                            <input type="email" id="email_despachante" name="email_despachante"
                                value="{{ old('email_despachante') }}"
                                class="form-control @error('email_despachante') is-invalid @enderror"
                                maxlength="255">
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="telefono_despachante" class="form-label fw-semibold">
                                Teléfono
                            </label>
                            <input type="tel" id="telefono_despachante" name="telefono_despachante"
                                value="{{ old('telefono_despachante') }}" class="form-control" maxlength="50">
                        </div>
                        <div class="col-12 col-md-8">
                            <label for="direccion_despachante" class="form-label fw-semibold">
                                Dirección
                            </label>
                            <input type="text" id="direccion_despachante" name="direccion_despachante"
                                value="{{ old('direccion_despachante') }}" class="form-control" maxlength="255">
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="gestor" class="form-label fw-semibold">
                                Gestor
                            </label>
                            <input type="text" id="gestor" name="gestor" value="{{ old('gestor') }}"
                                class="form-control" maxlength="255">
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="telefono_gestor" class="form-label fw-semibold">
                                Teléfono del gestor
                            </label>
                            <input type="tel" id="telefono_gestor" name="telefono_gestor"
                                value="{{ old('telefono_gestor') }}" class="form-control" maxlength="50">
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="dua" class="form-label fw-semibold">
                                DUA
                            </label>
                            <input type="text" id="dua" name="dua" value="{{ old('dua') }}"
                                class="form-control @error('dua') is-invalid @enderror" inputmode="numeric"
                                maxlength="6" pattern="[0-9]{1,6}" placeholder="Hasta 6 números">
                            @error('dua')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="contacto_despachante" class="form-label fw-semibold">
                                Contacto
                            </label>
                            <input type="text" id="contacto_despachante" name="contacto_despachante"
                                value="{{ old('contacto_despachante') }}" class="form-control" maxlength="255">
                        </div>
                    </div>
                </div>
            </section>
            <section class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white py-3">
                    <h2 class="h5 mb-0">
                        Información comercial
                    </h2>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="hecha_por" class="form-label fw-semibold">
                                Hecha por
                            </label>
                            <input type="text" id="hecha_por" name="hecha_por" value="{{ old('hecha_por') }}"
                                class="form-control
                                        @error('hecha_por') is-invalid @enderror"
                                maxlength="255">
                            @error('hecha_por')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="fecha_comercial" class="form-label fw-semibold">
                                Fecha
                            </label>
                            <input type="date" id="fecha_comercial" name="fecha_comercial"
                                value="{{ old('fecha_comercial') }}"
                                class="form-control
                                        @error('fecha_comercial') is-invalid @enderror">
                            @error('fecha_comercial')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <hr>
                            <h3 class="h6 fw-bold mb-0">
                                Fletes e importes
                            </h3>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <label for="puesta_fob" class="form-label fw-semibold">
                                Puesta a FOB
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    US$
                                </span>
                                <input type="number" id="puesta_fob" name="puesta_fob"
                                    value="{{ old('puesta_fob') }}"
                                    class="form-control
                                            @error('puesta_fob') is-invalid @enderror"
                                    min="0" step="0.01">
                                @error('puesta_fob')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <label for="flete_internacional" class="form-label fw-semibold">
                                Flete internacional
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    US$
                                </span>
                                <input type="number" id="flete_internacional" name="flete_internacional"
                                    value="{{ old('flete_internacional') }}"
                                    class="form-control
                                            @error('flete_internacional') is-invalid @enderror"
                                    min="0" step="0.01">
                                @error('flete_internacional')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <label for="flete_nacional" class="form-label fw-semibold">
                                Flete nacional
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    US$
                                </span>
                                <input type="number" id="flete_nacional" name="flete_nacional"
                                    value="{{ old('flete_nacional') }}"
                                    class="form-control
                                            @error('flete_nacional') is-invalid @enderror"
                                    min="0" step="0.01">
                                @error('flete_nacional')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <label for="ad_valorem_porcentaje" class="form-label fw-semibold">
                                Porcentaje de ad valorem
                            </label>
                            <div class="input-group">
                                <input type="number" id="ad_valorem_porcentaje" name="ad_valorem_porcentaje"
                                    value="{{ old('ad_valorem_porcentaje') }}"
                                    class="form-control
                                            @error('ad_valorem_porcentaje') is-invalid @enderror"
                                    min="0" max="100" step="0.01">
                                <span class="input-group-text">
                                    %
                                </span>
                                @error('ad_valorem_porcentaje')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <label for="ad_valorem" class="form-label fw-semibold">
                                Valor del ad valorem
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    US$
                                </span>
                                <input type="number" id="ad_valorem" name="ad_valorem"
                                    value="{{ old('ad_valorem') }}"
                                    class="form-control
                                            @error('ad_valorem') is-invalid @enderror"
                                    min="0" step="0.01">
                                @error('ad_valorem')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <label for="despacho_brasil" class="form-label fw-semibold">
                                Despacho de Brasil
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    US$
                                </span>
                                <input type="number" id="despacho_brasil" name="despacho_brasil"
                                    value="{{ old('despacho_brasil') }}"
                                    class="form-control
                                            @error('despacho_brasil') is-invalid @enderror"
                                    min="0" step="0.01">
                                @error('despacho_brasil')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <label for="total_comercial" class="form-label fw-semibold">
                                Total
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    US$
                                </span>
                                <input type="number" id="total_comercial" name="total_comercial"
                                    value="{{ old('total_comercial') }}" class="form-control" step="0.01"
                                    readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="fob_usd" class="form-label fw-semibold">
                                FOB US$
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    US$
                                </span>
                                <input type="number" id="fob_usd" name="fob_usd" value="{{ old('fob_usd') }}"
                                    class="form-control
                                            @error('fob_usd') is-invalid @enderror"
                                    min="0" step="0.01">
                                @error('fob_usd')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="flete_por_camion" class="form-label fw-semibold">
                                Flete por camión
                            </label>
                            <input type="text" id="flete_por_camion" name="flete_por_camion"
                                value="{{ old('flete_por_camion') }}"
                                class="form-control @error('flete_por_camion') is-invalid @enderror" maxlength="255"
                                placeholder="Información del flete por camión">
                            @error('flete_por_camion')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <hr>
                            <h3 class="h6 fw-bold mb-0">
                                Facturación y transporte
                            </h3>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="proforma" class="form-label fw-semibold">
                                Proforma
                            </label>
                            <input type="text" id="proforma" name="proforma" value="{{ old('proforma') }}"
                                class="form-control
                                        @error('proforma') is-invalid @enderror"
                                maxlength="255" placeholder="Letras y números">
                            @error('proforma')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="factura_definitiva" class="form-label fw-semibold">
                                Factura definitiva
                            </label>
                            <input type="text" id="factura_definitiva" name="factura_definitiva"
                                value="{{ old('factura_definitiva') }}"
                                class="form-control
                                        @error('factura_definitiva') is-invalid @enderror"
                                maxlength="255" placeholder="Letras y números">
                            @error('factura_definitiva')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="cantidad_camiones" class="form-label fw-semibold">
                                Cantidad de camiones
                            </label>
                            <input type="number" id="cantidad_camiones" name="cantidad_camiones"
                                value="{{ old('cantidad_camiones') }}"
                                class="form-control
                                        @error('cantidad_camiones') is-invalid @enderror"
                                min="0" step="1">
                            @error('cantidad_camiones')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="observaciones_comerciales" class="form-label fw-semibold">
                                Observaciones
                            </label>
                            <textarea id="observaciones_comerciales" name="observaciones_comerciales"
                                class="form-control
                                        @error('observaciones_comerciales') is-invalid @enderror"
                                rows="4" maxlength="5000" placeholder="Información comercial adicional">{{ old('observaciones_comerciales') }}</textarea>
                            @error('observaciones_comerciales')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </section>
            <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                <a href="{{ route('panel.fichas.index') }}" class="btn btn-outline-secondary">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-warning fw-bold">
                    <i class="bi bi-file-earmark-check-fill me-2"></i>
                    Registrar ficha
                </button>
            </div>
        </form>
    </main>
</div>
@endsection