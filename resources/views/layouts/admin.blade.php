<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        /* Fondo con overlay oscuro */
        body {
            position: relative;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('{{ asset("assets/fondo.png") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* overlay negro semi-transparente */
            z-index: 0;
        }

        /* Navbar */
        .navbar {
            z-index: 1; /* que quede encima del overlay */
        }

        .navbar-brand {
            font-weight: 600;
            transition: color 0.3s;
        }

        .navbar-brand:hover {
            color: #ffc107;
        }

        .nav-link {
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: #ffc107;
        }

        /* Contenido principal */
        .content-wrapper {
            position: relative;
            z-index: 1; /* que quede encima del overlay */
            background: rgba(255, 255, 255, 0.95); /* fondo blanco semitransparente */
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            margin-bottom: 50px;
        }

        /* Logo en navbar */
        .navbar-logo {
            width: 30px;
            height: 30px;
            object-fit: contain;
            margin-right: 8px;
        }

        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 0.9rem;
                margin-right: 10px;
            }

            .content-wrapper {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
        <div class="container-fluid">
            <!-- Botón de Inicio con logo -->
            <a class="navbar-brand d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="navbar-logo">
                Inicio
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Links de navegación -->
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.crearSorteo') }}">
                            <i class="bi bi-plus-circle me-1"></i> Crear Rifa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.listado') }}">
                            <i class="bi bi-card-list me-1"></i> Listado
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container">
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
