{{-- resources/views/layouts/public.blade.php --}}
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nata Mecanico')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Iconos Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('assets/logo.png') }}" type="image/png">
<link rel="shortcut icon" href="{{ asset('assets/logo.png') }}" type="image/png">


    <style>
        /* ===== Colores principales ===== */
        :root {
            --primary-color: #050505ff; /* Azul oscuro profesional */
            --secondary-color: #ffffff;
            --accent-color: #ffffffff; /* Color llamativo para botones o acentos */
            --hover-color: #ffba08;
            --transition-speed: 0.3s;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--secondary-color);
            color: var(--primary-color);
        }

        /* ===== Navbar ===== */
        .navbar {
            background-color: var(--primary-color);
            transition: background-color var(--transition-speed) ease;
        }

        .navbar-brand img {
            height: 55px;
            transition: transform var(--transition-speed);
        }

        .navbar-brand img:hover {
            transform: scale(1.1);
        }

        .navbar-brand span {
            font-weight: 700;
            font-size: 1.35rem;
            margin-left: 12px;
            color: var(--secondary-color);
        }

        .nav-link {
            color: var(--secondary-color) !important;
            font-weight: 500;
            position: relative;
            transition: color var(--transition-speed), transform var(--transition-speed);
        }

        .nav-link::after {
            content: '';
            display: block;
            width: 0%;
            height: 2px;
            background: var(--accent-color);
            transition: width var(--transition-speed);
            position: absolute;
            bottom: -5px;
            left: 0;
        }

        .nav-link:hover {
            color: var(--hover-color) !important;
            transform: translateY(-2px);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .navbar-toggler {
            border: none;
            outline: none;
        }

        .navbar-toggler i {
            color: var(--secondary-color);
            font-size: 1.5rem;
            transition: transform var(--transition-speed);
        }

        .navbar-toggler.collapsed i {
            transform: rotate(0deg);
        }

        .navbar-toggler:not(.collapsed) i {
            transform: rotate(90deg);
        }

        /* ===== Secciones ===== */
        section {
            padding: 120px 0;
        }

        h2, h3 {
            font-weight: 700;
            color: var(--primary-color);
        }

        /* ===== Botones ===== */
        .btn-primary-custom {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: var(--primary-color);
            font-weight: 600;
            transition: all var(--transition-speed);
        }

        .btn-primary-custom:hover {
            background-color: var(--hover-color);
            border-color: var(--hover-color);
            color: var(--primary-color);
        }

        /* ===== Footer ===== */
        footer {
            background-color: var(--primary-color);
            color: var(--secondary-color);
            padding: 50px 0;
            text-align: center;
        }

        footer p {
            margin: 0;
        }

        /* ===== Animaciones y UX ===== */
        a.nav-link:focus {
            outline: 2px solid var(--accent-color);
            outline-offset: 4px;
        }

        .shadow-custom {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

    </style>
</head>

<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('public.index') }}">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo">
                <span>NataMecanico</span>
            </a>
            <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('public.index') }}"><i
                                class="bi bi-house-door me-1"></i>Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('public.index') }}#preguntas-frecuentes"><i
                                class="bi bi-question-circle me-1"></i>Preguntas Frecuentes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('public.metodosPago') }}"><i
                                class="bi bi-credit-card me-1"></i>Métodos de Pago</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Contenido principal --}}
    <main class="pt-5">
        @yield('content')
    </main>

    {{-- Footer profesional --}}
    <footer class="py-5">
        <div class="container text-center">
            <p class="mb-2">&copy; {{ date('Y') }} NataMecanico. Todos los derechos reservados.</p>
            <p class="mb-0">Diseñado por <strong>DevSon</strong></p>
        </div>
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
