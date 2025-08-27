@extends('layouts.admin')

@section('content')
    <h1>Crear Nueva Rifa</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de la rifa</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label for="fotos" class="form-label">Foto (nombre del archivo)</label>
            <input type="text" name="fotos" id="fotos" class="form-control">
        </div>

        <div class="mb-3">
            <label for="precio_boleto" class="form-label">Precio por boleto</label>
            <input type="number" name="precio_boleto" id="precio_boleto" class="form-control" step="0.01" required>
        </div>

        <div class="mb-3">
            <label for="total_boletos" class="form-label">Total de boletos</label>
            <input type="number" name="total_boletos" id="total_boletos" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Crear Rifa</button>
    </form>
@endsection
