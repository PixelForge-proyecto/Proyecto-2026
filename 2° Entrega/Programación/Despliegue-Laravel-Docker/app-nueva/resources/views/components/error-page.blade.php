@props(['codigo', 'titulo', 'mensaje', 'icono' => 'bi-exclamation-triangle-fill'])

@php
    if (auth('interno')->check()) {
        $rutaRegreso = route('panel.dashboard');
        $textoRegreso = 'Volver al panel';
    } elseif (auth('cliente')->check()) {
        $rutaRegreso = route('cliente.inicio');
        $textoRegreso = 'Volver al área del cliente';
    } else {
        $rutaRegreso = url('/');
        $textoRegreso = 'Volver al inicio';
    }
@endphp

<div class="min-vh-100 bg-light d-flex align-items-center">
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <section class="card border-0 shadow-sm text-center">
                    <div class="card-body p-4 p-md-5">
                        <i class="bi {{ $icono }} display-1 text-warning" aria-hidden="true"></i>

                        <p class="display-3 fw-bold mb-2">
                            {{ $codigo }}
                        </p>

                        <h1 class="h3 fw-bold mb-3">
                            {{ $titulo }}
                        </h1>

                        <p class="text-secondary mb-4">
                            {{ $mensaje }}
                        </p>

                        <a href="{{ $rutaRegreso }}" class="btn btn-dark fw-semibold">
                            <i class="bi bi-arrow-left me-2"></i>
                            {{ $textoRegreso }}
                        </a>
                    </div>
                </section>
            </div>
        </div>
    </main>
</div>
