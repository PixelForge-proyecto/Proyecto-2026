<nav class="navbar navbar-expand-lg navbar-dark tl-navbar shadow-sm">
    <div class="container">
        <a
            class="navbar-brand bg-white rounded-2 px-2 py-1"
            href="{{ route('panel.dashboard') }}"
            aria-label="Ir al panel principal de Translogística">
            <img
                src="{{ asset('images/logo-translogistica.png') }}"
                alt="Translogística">
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#panelNavegacion"
            aria-controls="panelNavegacion"
            aria-expanded="false"
            aria-label="Mostrar navegación">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div
            class="collapse navbar-collapse"
            id="panelNavegacion">
            <ul
                class="navbar-nav ms-auto
                    align-items-lg-center gap-lg-1">
                <li class="nav-item">
                    <a
                        class="nav-link
                            {{ request()->routeIs('panel.dashboard') ? 'active' : '' }}"
                        href="{{ route('panel.dashboard') }}"
                        @if (request()->routeIs('panel.dashboard'))
                        aria-current="page"
                        @endif
                        >
                        <i
                            class="bi bi-speedometer2 me-1"
                            aria-hidden="true"></i>

                        Panel
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link
                            {{ request()->routeIs('panel.clientes.*') ? 'active' : '' }}"
                        href="{{ route('panel.clientes.index') }}"
                        @if (request()->routeIs('panel.clientes.*'))
                        aria-current="page"
                        @endif
                        >
                        <i
                            class="bi bi-people me-1"
                            aria-hidden="true"></i>

                        Clientes
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link
                            {{ request()->routeIs('panel.fichas.*') ? 'active' : '' }}"
                        href="{{ route('panel.fichas.index') }}"
                        @if (request()->routeIs('panel.fichas.*'))
                        aria-current="page"
                        @endif
                        >
                        <i
                            class="bi bi-file-earmark-text me-1"
                            aria-hidden="true"></i>

                        Fichas
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link
                            {{ request()->routeIs('panel.seguimientos.*') ? 'active' : '' }}"
                        href="{{ route('panel.seguimientos.index') }}"
                        @if (request()->routeIs('panel.seguimientos.*'))
                        aria-current="page"
                        @endif
                        >
                        <i
                            class="bi bi-geo-alt me-1"
                            aria-hidden="true"></i>

                        Seguimientos
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link
                            {{ request()->routeIs('panel.transportistas.*') ? 'active' : '' }}"
                        href="{{ route('panel.transportistas.index') }}"
                        @if (request()->routeIs('panel.transportistas.*'))
                        aria-current="page"
                        @endif
                        >
                        <i
                            class="bi bi-truck me-1"
                            aria-hidden="true"></i>

                        Transportistas
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link
                            {{ request()->routeIs('panel.despachantes.*') ? 'active' : '' }}"
                        href="{{ route('panel.despachantes.index') }}"
                        @if (request()->routeIs('panel.despachantes.*'))
                        aria-current="page"
                        @endif
                        >
                        <i
                            class="bi bi-person-vcard me-1"
                            aria-hidden="true"></i>

                        Despachantes
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