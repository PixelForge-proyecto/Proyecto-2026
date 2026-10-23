@extends('layouts.app')

@section('title', 'Iniciar sesión | Translogística')

@section('content')
    <div class="login-page">
        <main class="login-card">
            <img src="{{ asset('images/logo-translogistica.png') }}" alt="Translogística" class="login-logo">

            <h1 class="h2 fw-bold mb-4">
                Iniciar sesión
            </h1>

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.authenticate') }}">
                @csrf

                <div class="mb-3">
                    <label for="usuario" class="form-label fw-semibold">
                        Usuario
                    </label>

                    <input type="text" id="usuario" name="usuario" value="{{ old('usuario') }}"
                        class="form-control @error('usuario') is-invalid @enderror" autocomplete="username" required>

                    @error('usuario')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label fw-semibold">
                        Contraseña
                    </label>

                    <input type="password" id="password" name="password"
                        class="form-control @error('password') is-invalid @enderror" autocomplete="current-password"
                        required>

                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-warning fw-bold w-100">
                    Ingresar
                </button>
            </form>

            <a href="{{ url('/') }}" class="d-inline-block mt-4">
                Volver al inicio
            </a>
        </main>
    </div>
@endsection