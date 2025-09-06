@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg rounded">
        <div class="card-header bg-primary text-white text-center">
            <h2>🎉 Crear Nuevo Sorteo</h2>
        </div>
        <div class="card-body">

            {{-- Errores de validación --}}
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

            <form action="{{ route('admin.guardar') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf

                <div class="row">
                    {{-- Columna izquierda: datos --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nombre" class="form-label fw-bold">Nombre del sorteo <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ej: Sorteo de un iPhone 15" value="{{ old('nombre') }}" required>
                            <div class="invalid-feedback">Por favor ingresa un nombre para el sorteo.</div>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label fw-bold">Descripción</label>
                            <textarea name="descripcion" id="descripcion" class="form-control" placeholder="Escribe una breve descripción..." rows="3">{{ old('descripcion') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="precio_boleto" class="form-label fw-bold">Precio por boleto <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">💵</span>
                                <input type="number" name="precio_boleto" id="precio_boleto" class="form-control" step="0.01" placeholder="Ej: 50.00" value="{{ old('precio_boleto') }}" required>
                            </div>
                            <div class="invalid-feedback">Por favor ingresa un precio válido.</div>
                        </div>

                        <div class="mb-3">
                            <label for="total_boletos" class="form-label fw-bold">Total de boletos <span class="text-danger">*</span></label>
                            <input type="number" name="total_boletos" id="total_boletos" class="form-control" placeholder="Ej: 100" value="{{ old('total_boletos') }}" required>
                            <div class="invalid-feedback">Por favor ingresa el total de boletos.</div>
                        </div>
                    </div>

                    {{-- Columna derecha: fotos --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="fotos" class="form-label fw-bold">Fotos del premio (máx. 3)</label>
                            <input type="file" name="fotos[]" id="fotos" class="form-control" accept="image/*" multiple>
                            <small class="text-muted">Puedes subir de 1 a 3 imágenes. Formatos: JPG, PNG. Máx: 2MB c/u.</small>
                            <div class="mt-3 text-center d-flex justify-content-center gap-2 flex-wrap" id="preview-container">
                                <!-- Aquí se mostrarán las vistas previas -->
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('admin.listado') }}" class="btn btn-outline-secondary btn-lg">
                        ⬅ Volver
                    </a>
                    <button type="submit" class="btn btn-success btn-lg">
                        ✅ Crear Sorteo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Scripts de validación y preview --}}
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

// Preview con opción de eliminar imágenes
const inputFotos = document.getElementById('fotos');
const container = document.getElementById('preview-container');
let selectedFiles = [];

inputFotos.addEventListener('change', function(event) {
    selectedFiles = Array.from(event.target.files).slice(0, 3); // máximo 3
    renderPreviews();
});

function renderPreviews() {
    container.innerHTML = "";

    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = e => {
            const wrapper = document.createElement('div');
            wrapper.classList.add('position-relative', 'd-inline-block');

            const img = document.createElement('img');
            img.src = e.target.result;
            img.classList.add('img-fluid', 'rounded', 'shadow', 'm-1');
            img.style.maxHeight = "150px";
            img.style.maxWidth = "150px";

            const btn = document.createElement('button');
            btn.type = "button";
            btn.innerHTML = "❌";
            btn.classList.add('btn', 'btn-sm', 'btn-danger', 'position-absolute');
            btn.style.top = "5px";
            btn.style.right = "5px";
            btn.style.borderRadius = "50%";

            btn.addEventListener('click', () => {
                selectedFiles.splice(index, 1);
                updateInputFiles();
                renderPreviews();
            });

            wrapper.appendChild(img);
            wrapper.appendChild(btn);
            container.appendChild(wrapper);
        }
        reader.readAsDataURL(file);
    });
}

function updateInputFiles() {
    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(file => dataTransfer.items.add(file));
    inputFotos.files = dataTransfer.files;
}
</script>
@endsection
