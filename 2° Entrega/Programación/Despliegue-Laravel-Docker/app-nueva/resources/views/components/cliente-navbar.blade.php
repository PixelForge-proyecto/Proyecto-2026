<nav class="navbar navbar-expand-lg navbar-dark tl-navbar shadow-sm">
    <div class="container">
        <a
            class="navbar-brand bg-white rounded-2 px-2 py-1"
            href="{{ route('cliente.inicio') }}"
            aria-label="Ir al inicio de Translogística">
            <img
                src="{{ asset('images/logo-translogistica.png') }}"
                alt="Translogística">
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#clienteNavegacion"
            aria-controls="clienteNavegacion"
            aria-expanded="false"
            aria-label="Mostrar navegación">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div
            class="collapse navbar-collapse"
            id="clienteNavegacion">
            <ul
                class="navbar-nav ms-auto
                    align-items-lg-center gap-lg-1">
                <li class="nav-item">
                    <a
                        class="nav-link
                            {{ request()->routeIs('cliente.inicio') ? 'active' : '' }}"
                        href="{{ route('cliente.inicio') }}"
                        @if (request()->routeIs('cliente.inicio'))
                        aria-current="page"
                        @endif
                        >
                        <i
                            class="bi bi-house-door me-1"
                            aria-hidden="true"></i>

                        Inicio
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link
                            {{ request()->routeIs('cliente.cargas.*') ? 'active' : '' }}"
                        href="{{ route('cliente.cargas.index') }}"
                        @if (request()->routeIs('cliente.cargas.*'))
                        aria-current="page"
                        @endif
                        >
                        <i
                            class="bi bi-box-seam me-1"
                            aria-hidden="true"></i>

                        Mis cargas
                    </a>
                </li>

                <li
                    class="nav-item ms-lg-3
                        ps-lg-3 border-start border-secondary">
                    <form
                        method="POST"
                        action="{{ route('logout') }}">
                        @csrf

                        <button
                            type="submit"
                            class="btn btn-outline-warning
                                fw-semibold w-100">
                            <i
                                class="bi bi-box-arrow-right me-1"
                                aria-hidden="true"></i>

                            Salir
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>