@extends('layouts.app')

@section('title', 'Registrar cliente | Translogística')

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
                    <div class="card-body p-4 p-md-5">
                        <div class="mb-4">
                            <h1 class="fw-bold mb-2">Registrar cliente</h1>
                            <p class="text-secondary mb-0" id="descripcion-cliente">
                                Seleccioná el país para registrar un cliente nacional o internacional.
                            </p>
                        </div>

                        <div class="alert alert-info" role="alert" id="aviso-cliente">
                            Los clientes de Uruguay, Brasil y Argentina pueden acceder al portal con sus credenciales.
                        </div>

                        @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            Revisá los campos señalados antes de continuar.
                        </div>
                        @endif

                        <form method="POST" action="{{ route('panel.clientes.store') }}">
                            @csrf

                            <div class="row g-4">
                                <div class="col-12 col-md-6">
                                    <label for="origen" class="form-label fw-semibold">País de origen</label>
                                    <select id="origen" name="origen"
                                        class="form-select @error('origen') is-invalid @enderror" required>
                                        <option value="" disabled @selected(old('origen')===null)>Seleccionar</option>
                                        <option value="local" @selected(old('origen')==='local' )>Uruguay</option>
                                        <option value="brasil" @selected(old('origen')==='brasil' )>Brasil</option>
                                        <option value="argentina" @selected(old('origen')==='argentina' )>Argentina</option>
                                    </select>
                                    @error('origen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="nombre" class="form-label fw-semibold">Nombre del cliente</label>
                                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}"
                                        class="form-control @error('nombre') is-invalid @enderror"
                                        placeholder="Nombre o razón social" maxlength="255" required>
                                    @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6" data-solo-nacional hidden>
                                    <label for="ruc_rut" class="form-label fw-semibold">
                                        RUC / RUT <span class="text-secondary fw-normal">(opcional)</span>
                                    </label>
                                    <input type="text" id="ruc_rut" name="ruc_rut" value="{{ old('ruc_rut') }}"
                                        class="form-control @error('ruc_rut') is-invalid @enderror"
                                        maxlength="50" placeholder="RUC o RUT del cliente">
                                    @error('ruc_rut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="telefono" class="form-label fw-semibold">
                                        Teléfono <span class="text-secondary fw-normal">(opcional)</span>
                                    </label>
                                    <input type="tel" id="telefono" name="telefono" value="{{ old('telefono') }}"
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
                                        value="{{ old('contacto_nombre') }}"
                                        class="form-control @error('contacto_nombre') is-invalid @enderror"
                                        maxlength="255" placeholder="Persona de contacto">
                                    @error('contacto_nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="contacto_email" class="form-label fw-semibold">
                                        Correo de contacto <span class="text-secondary fw-normal">(opcional)</span>
                                    </label>
                                    <input type="email" id="contacto_email" name="contacto_email"
                                        value="{{ old('contacto_email') }}"
                                        class="form-control @error('contacto_email') is-invalid @enderror"
                                        maxlength="255" placeholder="contacto@empresa.com">
                                    @error('contacto_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12" data-solo-internacional hidden>
                                    <label for="despachante" class="form-label fw-semibold">
                                        Despachante <span class="text-secondary fw-normal">(opcional)</span>
                                    </label>
                                    <input type="text" id="despachante" name="despachante"
                                        value="{{ old('despachante') }}"
                                        class="form-control @error('despachante') is-invalid @enderror"
                                        maxlength="255" placeholder="Despachante habitual del cliente">
                                    @error('despachante')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="direccion" class="form-label fw-semibold">
                                        Dirección <span class="text-secondary fw-normal">(opcional)</span>
                                    </label>
                                    <input type="text" id="direccion" name="direccion"
                                        value="{{ old('direccion') }}"
                                        class="form-control @error('direccion') is-invalid @enderror"
                                        placeholder="Calle, número y ciudad" maxlength="255">
                                    @error('direccion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <hr>
                                    <h2 class="h5 fw-bold mb-0">Datos de acceso</h2>
                                    <p class="text-secondary mb-0 mt-2">
                                        Todos los clientes necesitan usuario y contraseña para consultar sus cargas.
                                        Las cuentas son creadas por personal autorizado.
                                    </p>
                                </div>

                                <div class="col-12">
                                    <label for="usuario" class="form-label fw-semibold">Usuario</label>
                                    <input type="text" id="usuario" name="usuario" value="{{ old('usuario') }}"
                                        class="form-control @error('usuario') is-invalid @enderror"
                                        placeholder="Usuario para iniciar sesión"
                                        autocomplete="username"
                                        minlength="3" maxlength="255" required>
                                    @error('usuario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="password" class="form-label fw-semibold">Contraseña</label>
                                    <input type="password" id="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        autocomplete="new-password"
                                        minlength="8" maxlength="255" required>
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Debe contener al menos 8 caracteres.</div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="password_confirmation" class="form-label fw-semibold">
                                        Confirmar contraseña
                                    </label>
                                    <input type="password" id="password_confirmation"
                                        name="password_confirmation"
                                        class="form-control"
                                        autocomplete="new-password"
                                        minlength="8" maxlength="255" required>
                                </div>

                                <div class="col-12 d-flex flex-column flex-sm-row gap-2 justify-content-end">
                                    <a href="{{ route('panel.clientes.index') }}"
                                        class="btn btn-outline-secondary">Cancelar</a>
                                    <button type="submit" class="btn btn-warning fw-bold">
                                        <i class="bi bi-person-check-fill me-2"></i>
                                        Registrar cliente
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
        const descripcion = document.getElementById('descripcion-cliente');
        const aviso = document.getElementById('aviso-cliente');

        function actualizarCampos() {
            const nacional = origen.value === 'local';
            const internacional = origen.value === 'brasil' || origen.value === 'argentina';

            document.querySelectorAll('[data-solo-nacional]').forEach(function(elemento) {
                elemento.hidden = !nacional;

                elemento.querySelectorAll('input').forEach(function(campo) {
                    campo.disabled = !nacional;
                });
            });

            document.querySelectorAll('[data-solo-internacional]').forEach(function(elemento) {
                elemento.hidden = !internacional;

                elemento.querySelectorAll('input').forEach(function(campo) {
                    campo.disabled = !internacional;
                });
            });

            if (nacional || internacional) {
                descripcion.textContent = 'Registrá la empresa y creá sus credenciales para consultar las cargas.';
                aviso.textContent = 'Los clientes de Uruguay, Brasil y Argentina pueden acceder al portal. Las cuentas son creadas por personal autorizado de Translogística.';
            } else {
                descripcion.textContent = 'Seleccioná el país para registrar un cliente nacional o internacional.';
                aviso.textContent = 'Seleccioná Uruguay, Brasil o Argentina para completar los datos del cliente y sus credenciales.';
            }
        }

        origen.addEventListener('change', actualizarCampos);
        actualizarCampos();
    });
</script>
@endsection