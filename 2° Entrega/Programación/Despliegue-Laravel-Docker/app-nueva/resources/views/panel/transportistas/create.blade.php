@extends('layouts.app')

@section('title', 'Nuevo transportista | Translogística')

@section('content')
    <div class="min-vh-100 bg-light">
        <x-panel-navbar />

        <main class="container py-5">
            <div class="mb-4">
                <a href="{{ route('panel.transportistas.index') }}" class="text-decoration-none text-secondary">
                    <i class="bi bi-arrow-left me-1"></i>
                    Volver a transportistas
                </a>
            </div>

            <div class="mb-4">
                <h1 class="fw-bold mb-1">
                    Nuevo transportista
                </h1>

                <p class="text-secondary mb-0">
                    Ingresá los datos del transportista que podrá ser asignado a las fichas.
                </p>
            </div>

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <section class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('panel.transportistas.store') }}">
                        @csrf

                        <div class="row g-4">
                            <div class="col-12">
                                <label for="nombre" class="form-label fw-semibold">
                                    Nombre del transportista
                                </label>

                                <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}"
                                    class="form-control @error('nombre') is-invalid @enderror" maxlength="255" required>

                                @error('nombre')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="email" class="form-label fw-semibold">
                                    Correo electrónico
                                </label>

                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror" maxlength="255"
                                    placeholder="Ejemplo: contacto@transportista.com">

                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="form-text">
                                    Campo opcional.
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="contacto" class="form-label fw-semibold">
                                    Contacto
                                </label>

                                <input type="text" id="contacto" name="contacto" value="{{ old('contacto') }}"
                                    class="form-control @error('contacto') is-invalid @enderror" maxlength="255"
                                    placeholder="Teléfono o persona de contacto">

                                @error('contacto')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="form-text">
                                    Campo opcional.
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 mt-4">
                            <a href="{{ route('panel.transportistas.index') }}" class="btn btn-outline-secondary">
                                Cancelar
                            </a>

                            <button type="submit" class="btn btn-warning fw-bold">
                                <i class="bi bi-check-circle me-2"></i>
                                Registrar transportista
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </main>
    </div>
@endsection
