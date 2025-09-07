{{-- resources/views/layouts/public.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Sitio')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Iconos Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        html {
            scroll-behavior: smooth;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fff;
            color: #000;
        }
        .navbar {
            background-color: #000; /* fondo negro */
        }
        .navbar-brand img {
            height: 55px; /* logo más grande */
            margin-right: 10px;
        }
        .navbar-brand span {
            font-size: 1.5rem;
            font-weight: bold;
            color: #fff;
            letter-spacing: 1px;
        }
        .nav-link {
            color: #fff !important;
            font-weight: 500;
            transition: color 0.3s ease, border-bottom 0.3s ease;
        }
        .nav-link:hover {
            color: #f8f9fa !important;
            border-bottom: 2px solid #fff;
        }
        .navbar-toggler {
            border: none;
        }
        .navbar-toggler i {
            color: #fff;
            font-size: 1.5rem;
        }
        section {
            padding: 80px 0;
        }
    </style>
</head>
<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('public.index') }}">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo">
                <span>MiSitio</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('public.index') }}"><i class="bi bi-house-door me-1"></i>Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('public.index') }}#preguntas-frecuentes"><i class="bi bi-question-circle me-1"></i>Preguntas Frecuentes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('public.metodosPago') }}"><i class="bi bi-credit-card me-1"></i>Métodos de Pago</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Contenido --}}
    <main class="pt-5">
        @yield('content')
    </main>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
