@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">📱 Gestión de Números</h2>

    {{-- Mensajes --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Formulario para agregar --}}
    <form action="{{ route('admin.numeros.store') }}" method="POST" class="mb-4">
        @csrf
        <div class="row g-2">
            <div class="col-md-4">
                <input type="text" name="nombre" class="form-control" placeholder="Nombre (opcional)">
            </div>
            <div class="col-md-4">
                <input type="text" name="numero" class="form-control" placeholder="Número" required>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">➕ Agregar</button>
            </div>
        </div>
    </form>

    {{-- Listado --}}
    <table class="table table-bordered text-center">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Número</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse($numeros as $num)
                <tr>
                    <td>{{ $num->id }}</td>
                    <td>{{ $num->nombre }}</td>
                    <td>{{ $num->numero }}</td>
                    <td>
                        <form action="{{ route('admin.numeros.destroy', $num->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este número?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">🗑 Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">Sin números registrados</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
