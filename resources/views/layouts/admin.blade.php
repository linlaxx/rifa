<!DOCTYPE html>
<html>
<head>
    <title>Panel Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">Inicio</a>
            <a class="navbar-brand" href="{{ route('admin.crearSorteo') }}">Crear rifa</a>
            <a class="navbar-brand" href="{{ route('admin.listado') }}">Listado</a>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>
</body>
</html>
