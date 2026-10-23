@extends('layouts.app')

@section('title', 'Editar cliente | Translogística')

@section('content')
<div class="min-vh-100 bg-light">
    <x-panel-navbar />

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="mb-4">
                    <a href="{{ route('panel.clientes.index') }}" class="text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i>
                        Volver a clientes
                    </a>
                </div>

                <section class="card border-0 shadow-sm">
                    <div class="card-header bg-dark text-white py-3">
                        <h1 class="h4 mb-0">Editar cliente</h1>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            Revisá los campos señalados antes de continuar.
                        </div>
                        @endif

                        <form method="POST" action="{{ route('panel.clientes.update', $cliente) }}">
                            @csrf
                            @method('PUT')

                            <div class="row g-4">
                                <div class="col-12">
                                    <label for="nombre" class="form-label fw-semibold">Nombre del cliente</label>
                                    <input type="text" id="nombre" name="nombre"
                                        value="{{ old('nombre', $cliente->nombre) }}"
                                        class="form-control @error('nombre') is-invalid @enderror"
                                        maxlength="255" required>
                                    @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="origen" class="form-label fw-semibold">País de origen</label>
                                    <select id="origen" name="origen"
                                        class="form-select @error('origen') is-invalid @enderror" required>
                                        <option value="" disabled @selected(!old('origen', $cliente->origen))>
                                            Seleccionar
                                        </option>
                                        <option value="local" @selected(old('origen', $cliente->origen) === 'local')>
                                            Uruguay
                                        </option>
                                        <option value="brasil" @selected(old('origen', $cliente->origen) === 'brasil')>
                                            Brasil
                                        </option>
                                        <option value="argentina" @selected(old('origen', $cliente->origen) === 'argentina')>
                                            Argentina
                                        </option>
                                    </select>
                                    @error('origen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="telefono" class="form-label fw-semibold">
                                        Teléfono <span class="text-secondary fw-normal">(opcional)</span>
                                    </label>
                                    <input type="tel" id="telefono" name="telefono"
                                        value="{{ old('telefono', $cliente->telefono) }}"
                                        class="form-control @error('telefono') is-invalid @enderror"
                                        placeholder="Ej.: +55 11 99999-9999"
                                        autocomplete="tel" maxlength="50">
                                    @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Puede ser un teléfono fijo o celular.</div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="contacto_nombre" class="form-label fw-semibold">
                                        Nombre de contacto <span class="text-secondary fw-normal">(opcional)</span>
                                    </label>
                                    <input type="text" id="contacto_nombre" name="contacto_nombre"
                                        value="{{ old('contacto_nombre', $cliente->contacto_nombre) }}"
                                        class="form-control @error('contacto_nombre') is-invalid @enderror"
                                        maxlength="255">
                                    @error('contacto_nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="contacto_email" class="form-label fw-semibold">
                                        Email de contacto <span class="text-secondary fw-normal">(opcional)</span>
                                    </label>
                                    <input type="email" id="contacto_email" name="contacto_email"
                                        value="{{ old('contacto_email', $cliente->contacto_email) }}"
                                        class="form-control @error('contacto_email') is-invalid @enderror"
                                        maxlength="255">
                                    @error('contacto_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12" id="datos-nacionales">
                                    <label for="ruc_rut" class="form-label fw-semibold">
                                        RUC / RUT <span class="text-secondary fw-normal">(opcional)</span>
                                    </label>
                                    <input type="text" id="ruc_rut" name="ruc_rut"
                                        value="{{ old('ruc_rut', $cliente->ruc_rut) }}"
                                        class="form-control @error('ruc_rut') is-invalid @enderror"
                                        maxlength="50">
                                    @error('ruc_rut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12" id="datos-internacionales">
                                    <label for="despachante" class="form-label fw-semibold">
                                        Despachante <span class="text-secondary fw-normal">(opcional)</span>
                                    </label>
                                    <input type="text" id="despachante" name="despachante"
                                        value="{{ old('despachante', $cliente->despachante) }}"
                                        class="form-control @error('despachante') is-invalid @enderror"
                                        maxlength="255">
                                    @error('despachante')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="direccion" class="form-label fw-semibold">
                                        Dirección <span class="text-secondary fw-normal">(opcional)</span>
                                    </label>
                                    <input type="text" id="direccion" name="direccion"
                                        value="{{ old('direccion', $cliente->direccion) }}"
                                        class="form-control @error('direccion') is-invalid @enderror"
                                        placeholder="Calle, número y ciudad" maxlength="255">
                                    @error('direccion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12" id="datos-acceso">
                                    <hr>
                                    <h2 class="h5 fw-bold mb-3">Datos de acceso</h2>
                                    <p class="text-secondary mb-3">
                                        Los clientes de Uruguay, Brasil y Argentina pueden ingresar
                                        al portal con su usuario y contraseña.
                                    </p>

                                    <div class="row g-4">
                                        <div class="col-12">
                                            <label for="usuario" class="form-label fw-semibold">
                                                Usuario
                                            </label>
                                            <input type="text" id="usuario" name="usuario"
                                                value="{{ old('usuario', $cliente->usuario) }}"
                                                class="form-control @error('usuario') is-invalid @enderror"
                                                autocomplete="username"
                                                minlength="3" maxlength="255" required>
                                            @error('usuario')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            @if (blank($cliente->password))
                                            <h3 class="h5 fw-bold">Establecer contraseña</h3>
                                            <p class="text-secondary mb-0">
                                                Este cliente todavía no tiene una contraseña.
                                                Debés establecer una para habilitar su acceso al portal.
                                            </p>
                                            @else
                                            <h3 class="h5 fw-bold">Cambiar contraseña</h3>
                                            <p class="text-secondary mb-0">
                                                Dejá estos campos vacíos si no querés modificar
                                                la contraseña actual.
                                            </p>
                                            @endif
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="password" class="form-label fw-semibold">
                                                {{ blank($cliente->password) ? 'Contraseña' : 'Nueva contraseña' }}
                                            </label>
                                            <input type="password" id="password" name="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                autocomplete="new-password"
                                                minlength="8" maxlength="255"
                                                @if (blank($cliente->password)) required @endif>
                                            @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">
                                                Debe contener al menos 8 caracteres.
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="password_confirmation" class="form-label fw-semibold">
                                                Confirmar contraseña
                                            </label>
                                            <input type="password"
                                                id="password_confirmation"
                                                name="password_confirmation"
                                                class="form-control"
                                                autocomplete="new-password"
                                                minlength="8" maxlength="255"
                                                @if (blank($cliente->password)) required @endif>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 d-flex flex-column flex-sm-row justify-content-end gap-2">
                                    <a href="{{ route('panel.clientes.show', $cliente) }}"
                                        class="btn btn-outline-secondary">
                                        Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-warning fw-bold">
                                        <i class="bi bi-check-circle-fill me-2"></i>
                                        Guardar cambios
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const origen = document.getElementById('origen');
        const nacionales = document.getElementById('datos-nacionales');
        const internacionales = document.getElementById('datos-internacionales');

        function actualizarCampos() {
            const esNacional = origen.value === 'local';
            const esInternacional = origen.value === 'brasil' ||
                origen.value === 'argentina';

            nacionales.hidden = !esNacional;
            internacionales.hidden = !esInternacional;

            nacionales.querySelectorAll('input').forEach(function(campo) {
                campo.disabled = !esNacional;
            });

            internacionales.querySelectorAll('input').forEach(function(campo) {
                campo.disabled = !esInternacional;
            });
        }

        origen.addEventListener('change', actualizarCampos);
        actualizarCampos();
    });
</script>
@endsection