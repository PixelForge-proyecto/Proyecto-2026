@extends('layouts.app')

@section('title', 'Área del cliente | Translogística')

@section('content')
    <div class="min-vh-100 bg-light">
        <x-cliente-navbar />
        <main class="container py-5">
            <header class="mb-5">
                <h1 class="fw-bold mb-2">
                    Área del cliente
                </h1>

                <p class="text-secondary mb-0">
                    Consultá tus cargas y revisá el estado de cada operación.
                </p>
            </header>

            <div class="row g-4">
                <div class="col-12 col-lg-8">
                    <section class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <i class="bi bi-search fs-1 text-warning"></i>

                            <h2 class="h4 fw-bold mt-3 mb-3">
                                Buscar una carga
                            </h2>

                            <p class="text-secondary">
                                Ingresá el número de DUA para consultar el estado
                                de la carga.
                            </p>

                            <form method="GET" action="{{ route('cliente.cargas.buscar') }}">
                                <div class="row g-3 align-items-end">
                                    <div class="col-12 col-md-8">
                                        <label for="numero-dua" class="form-label fw-semibold">
                                            Número de DUA
                                        </label>

                                        <input type="text" id="numero-dua" name="dua" value="{{ old('dua') }}"
                                            class="form-control
                                                @error('dua') is-invalid @enderror"
                                            placeholder="Ejemplo: 123456" inputmode="numeric" maxlength="6"
                                            pattern="[0-9]{1,6}">

                                        @error('dua')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <button type="submit" class="btn btn-warning fw-bold w-100">
                                            <i class="bi bi-search me-2"></i>
                                            Buscar carga
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>

                <div class="col-12 col-lg-4">
                    <section class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4 d-flex flex-column">
                            <i class="bi bi-truck fs-1 text-warning"></i>

                            <h2 class="h4 fw-bold mt-3 mb-3">
                                Mis cargas
                            </h2>

                            <p class="text-secondary">
                                Revisá todas las operaciones asociadas a tu cuenta
                                y consultá su seguimiento.
                            </p>

                            <a href="{{ route('cliente.cargas.index') }}" class="btn btn-dark fw-semibold mt-auto">
                                Ver mis cargas
                            </a>
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>
@endsection
