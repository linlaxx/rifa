@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg rounded">
        <div class="card-header bg-primary text-white text-center">
            <h2>✏️ Editar Rifa</h2>
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

            <form action="{{ route('admin.editar', $rifa->id) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Columna izquierda: datos generales -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nombre" class="form-label fw-bold">Nombre de la rifa <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="nombre" class="form-control" 
                                   placeholder="Ej: Rifa de un iPhone 15" 
                                   value="{{ old('nombre', $rifa->nombre) }}" required>
                            <div class="invalid-feedback">Por favor ingresa un nombre para la rifa.</div>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label fw-bold">Descripción</label>
                            <textarea name="descripcion" id="descripcion" class="form-control" 
                                      placeholder="Escribe una breve descripción..." rows="3">{{ old('descripcion', $rifa->descripcion) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="fecha_sorteo" class="form-label fw-bold">Fecha del sorteo <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="fecha_sorteo" id="fecha_sorteo" class="form-control"
                                   value="{{ old('fecha_sorteo', $rifa->fecha_sorteo ? \Carbon\Carbon::parse($rifa->fecha_sorteo)->format('Y-m-d\TH:i') : '') }}"
                                   required>
                            <div class="invalid-feedback">Por favor ingresa la fecha del sorteo.</div>
                        </div>

                        <div class="mb-3">
                            <label for="precio_boleto" class="form-label fw-bold">Precio por boleto <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="precio_boleto" id="precio_boleto" class="form-control" 
                                       step="0.01" placeholder="Ej: 50.00" 
                                       value="{{ old('precio_boleto', $rifa->precio_boleto) }}" required>
                            </div>
                            <div class="invalid-feedback">Por favor ingresa un precio válido.</div>
                        </div>

                        <div class="mb-3">
                            <label for="total_boletos" class="form-label fw-bold">Total de boletos <span class="text-danger">*</span></label>
                            <input type="number" name="total_boletos" id="total_boletos" class="form-control" 
                                   placeholder="Ej: 100" 
                                   value="{{ old('total_boletos', $rifa->total_boletos) }}" required>
                            <div class="invalid-feedback">Por favor ingresa el total de boletos.</div>
                        </div>

                        <div class="mb-3">
                            <label for="estado" class="form-label fw-bold">Estado <span class="text-danger">*</span></label>
                            <select name="estado" id="estado" class="form-select" required>
                                <option value="activa" {{ old('estado', $rifa->estado) === 'activa' ? 'selected' : '' }}>Activa</option>
                                <option value="finalizada" {{ old('estado', $rifa->estado) === 'finalizada' ? 'selected' : '' }}>Finalizada</option>
                            </select>
                            <div class="invalid-feedback">Por favor selecciona un estado.</div>
                        </div>
                    </div>

                    <!-- Columna derecha: fotos -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="fotos" class="form-label fw-bold">Fotos del premio</label>
                            <input type="file" name="fotos[]" id="fotos" class="form-control" accept="image/*" multiple>
                            <small class="text-muted d-block">Puedes subir hasta 3 imágenes (JPG, PNG).</small>

                            @if($rifa->fotos && is_array($rifa->fotos))
                                <div class="mt-3 d-flex flex-wrap gap-3" id="existing-preview">
                                    @foreach($rifa->fotos as $foto)
                                        <div class="position-relative">
                                            <img src="{{ asset('storage/' . $foto) }}" class="img-thumbnail" style="width:120px; height:120px; object-fit:cover;">
                                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 remove-existing" data-foto="{{ $foto }}">
                                                &times;
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <input type="hidden" name="remove_fotos" id="remove_fotos" value="">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Vista previa nuevas imágenes:</label>
                            <div class="d-flex flex-wrap gap-3" id="preview"></div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('admin.listado') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-pencil-square"></i> Actualizar Rifa
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

    // Preview nuevas imágenes
    const inputFotos = document.getElementById('fotos');
    const preview = document.getElementById('preview');
    inputFotos?.addEventListener('change', () => {
        preview.innerHTML = "";
        Array.from(inputFotos.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                const div = document.createElement('div');
                div.classList.add('position-relative');
                div.innerHTML = `
                    <img src="${e.target.result}" class="img-thumbnail" style="width:120px; height:120px; object-fit:cover;">
                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0">&times;</button>
                `;
                div.querySelector('button').onclick = () => div.remove();
                preview.appendChild(div);
            }
            reader.readAsDataURL(file);
        });
    });

    // Eliminar imágenes existentes
    const removeFotosInput = document.getElementById('remove_fotos');
    document.querySelectorAll('.remove-existing').forEach(btn => {
        btn.addEventListener('click', () => {
            let toRemove = removeFotosInput.value ? removeFotosInput.value.split(',') : [];
            toRemove.push(btn.dataset.foto);
            removeFotosInput.value = toRemove.join(',');
            btn.parentElement.remove();
        });
    });
})();
</script>
@endsection
