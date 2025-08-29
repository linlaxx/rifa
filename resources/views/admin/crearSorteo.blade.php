@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg rounded">
        <div class="card-header bg-primary text-white text-center">
            <h2>🎉 Crear Nueva Rifa</h2>
        </div>
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <h5>Por favor corrige los siguientes errores:</h5>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf

                
                <div class="mb-3">
                    <label for="nombre" class="form-label fw-bold">Nombre de la rifa <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ej: Rifa de un iPhone 15" value="{{ old('nombre') }}" required>
                    <div class="invalid-feedback">Por favor ingresa un nombre para la rifa.</div>
                </div>

               
                <div class="mb-3">
                    <label for="descripcion" class="form-label fw-bold">Descripción</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" placeholder="Escribe una breve descripción..." rows="3">{{ old('descripcion') }}</textarea>
                </div>

               
                <div class="mb-3">
                    <label for="foto" class="form-label fw-bold">Foto del premio</label>
                    <input type="file" name="foto" id="foto" class="form-control">
                    <small class="text-muted">Formatos permitidos: JPG, PNG.</small>
                </div>

                
                <div class="mb-3">
                    <label for="precio_boleto" class="form-label fw-bold">Precio por boleto <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="precio_boleto" id="precio_boleto" class="form-control" step="0.01" placeholder="Ej: 50.00" value="{{ old('precio_boleto') }}" required>
                    </div>
                    <div class="invalid-feedback">Por favor ingresa un precio válido.</div>
                </div>

              
                <div class="mb-3">
                    <label for="total_boletos" class="form-label fw-bold">Total de boletos <span class="text-danger">*</span></label>
                    <input type="number" name="total_boletos" id="total_boletos" class="form-control" placeholder="Ej: 100" value="{{ old('total_boletos') }}" required>
                    <div class="invalid-feedback">Por favor ingresa el total de boletos.</div>
                </div>

                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.listado') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Crear Rifa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

(() => {
    'use strict'
    const forms = document.querySelectorAll('.needs-validation')
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>
@endsection
