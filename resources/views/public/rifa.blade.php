@extends('layouts.public')

@section('title', $rifa->nombre)

@section('content')
<section class="container mt-5 pt-4">
    <div class="row">
        {{-- Carrusel de imágenes --}}
        <div class="col-lg-6 mb-4">
            @php
                $imagenes = is_array($rifa->fotos) ? $rifa->fotos : json_decode($rifa->fotos, true);
            @endphp

            @if(!empty($imagenes))
                <div id="carouselRifa" class="carousel slide shadow-sm rounded" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($imagenes as $index => $img)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/'.$img) }}" 
                                     class="d-block w-100 rounded" 
                                     style="max-height: 400px; object-fit: cover;" 
                                     alt="Imagen {{ $index + 1 }} de {{ $rifa->nombre }}">
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselRifa" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselRifa" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
            @else
                <img src="{{ asset('images/default_rifa.png') }}" class="img-fluid rounded shadow-sm" alt="Imagen por defecto">
            @endif
        </div>

        {{-- Información de la rifa --}}
        <div class="col-lg-6 d-flex flex-column justify-content-center">
            <h1 class="fw-bold mb-3">{{ $rifa->nombre }}</h1>
            <p class="text-muted">{{ $rifa->descripcion }}</p>

            <ul class="list-group mb-3">
                <li class="list-group-item d-flex justify-content-between">
                    <span><i class="bi bi-cash-coin"></i> Precio del boleto</span>
                    <strong>${{ number_format($rifa->precio_boleto, 2) }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span><i class="bi bi-calendar-event"></i> Fecha del sorteo</span>
                    <strong>{{ \Carbon\Carbon::parse($rifa->fecha_sorteo)->format('d/m/Y') }}</strong>
                </li>
            </ul>
        </div>
    </div>

    {{-- 📌 Botón Máquina de la suerte --}}
<div class="text-center my-4">
    <button class="btn btn-warning btn-lg fw-bold" data-bs-toggle="modal" data-bs-target="#modalSuerte">
        🍀 Máquina de la suerte
    </button>
</div>

{{-- 📌 Modal Máquina de la suerte --}}
<div class="modal fade" id="modalSuerte" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-3 shadow">
      <div class="modal-header">
        <h5 class="modal-title">🍀 Máquina de la suerte</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <label for="cantidadSuerte" class="form-label">¿Cuántos boletos quieres?</label>
        <input type="number" id="cantidadSuerte" class="form-control mb-3" min="1" value="1">

        <div id="resultadoSuerte" class="alert alert-light border d-none">
            <p class="mb-1"><strong>Boletos seleccionados al azar:</strong></p>
            <p id="listaSuerte" class="fw-bold"></p>
            <p class="mb-0"><strong>Total a pagar:</strong> $<span id="totalSuerte">0.00</span></p>
        </div>
      </div>
      <div class="modal-footer">
        <button id="btnGenerarSuerte" class="btn btn-dark fw-bold">🎲 Generar</button>
        <button id="btnPagarSuerte" class="btn btn-success fw-bold d-none">
            <i class="bi bi-whatsapp"></i> Pagar boletos
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Datos de Compra -->
<div class="modal fade" id="modalDatosCompra" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-3 shadow">
      <div class="modal-header">
        <h5 class="modal-title">📝 Ingresa tus datos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="text" id="nombreUsuario" class="form-control mb-2" placeholder="Nombre" required>
        <input type="text" id="apellidoUsuario" class="form-control mb-2" placeholder="Apellido" required>
        <input type="text" id="telefonoUsuario" class="form-control mb-2" placeholder="Teléfono" required>
        <input type="text" id="estadoUsuario" class="form-control mb-2" placeholder="Estado" required>
      </div>
      <div class="modal-footer">
        <button id="btnConfirmarCompra" class="btn btn-success fw-bold">Confirmar compra</button>
      </div>
    </div>
  </div>
</div>



    {{-- 📌 Selección de boletos --}}
    <div class="mt-5">
        <h3 class="fw-bold mb-3">🎟️ Selecciona tus números</h3>
        <div class="row g-2">
            @foreach($rifa->boletos as $boleto)
    <div class="col-2 col-sm-1">
        @if($boleto->vendido || !$boleto->disponible) {{-- vendido o reservado --}}
            <div class="p-2 text-center rounded bg-dark text-white fw-bold">
                {{ $boleto->numero }}
            </div>
        @else
            <button class="btn btn-outline-dark w-100 fw-bold select-boleto" data-numero="{{ $boleto->numero }}">
                {{ $boleto->numero }}
            </button>
        @endif
    </div>
@endforeach

        </div>

        {{-- Resumen de selección --}}
        <div id="resumen" class="alert alert-light border mt-4 d-none">
            <p class="mb-1"><strong>Boletos seleccionados:</strong> <span id="listaSeleccionados"></span></p>
            <p class="mb-0"><strong>Total a pagar:</strong> $<span id="totalPagar">0.00</span></p>
        </div>

        {{-- Botón pagar con WhatsApp --}}
        <div class="text-center mt-3">
            <button id="btnPagar" class="btn btn-success btn-lg fw-bold">
                <i class="bi bi-whatsapp"></i> Pagar por WhatsApp
            </button>
        </div>
    </div>
</section>
 

<script>
document.addEventListener("DOMContentLoaded", () => {
    const precioBoleto = {{ $rifa->precio_boleto }};
    const disponibles = @json($rifa->boletos->where('vendido', false)->where('disponible', true)->pluck('numero'));

    let seleccionados = []; // aquí guardaremos los boletos seleccionados (manual o suerte)

    // === Selección manual ===
    document.querySelectorAll(".select-boleto").forEach(btn => {
        btn.addEventListener("click", () => {
            const numero = btn.dataset.numero;
            const index = seleccionados.indexOf(numero);
            if(index > -1){
                seleccionados.splice(index,1);
                btn.classList.remove("btn-dark");
                btn.classList.add("btn-outline-dark");
            } else {
                seleccionados.push(numero);
                btn.classList.remove("btn-outline-dark");
                btn.classList.add("btn-dark");
            }
            actualizarResumen();
        });
    });

    function actualizarResumen(){
        const resumen = document.getElementById("resumen");
        const lista = document.getElementById("listaSeleccionados");
        const total = document.getElementById("totalPagar");

        if(seleccionados.length > 0){
            resumen.classList.remove("d-none");
            lista.textContent = seleccionados.join(", ");
            total.textContent = (seleccionados.length * precioBoleto).toFixed(2);
        } else {
            resumen.classList.add("d-none");
        }
    }

    // === Máquina de la suerte ===
    const cantidadInput = document.getElementById("cantidadSuerte");
    const resultado = document.getElementById("resultadoSuerte");
    const listaSuerte = document.getElementById("listaSuerte");
    const totalSuerte = document.getElementById("totalSuerte");
    const btnGenerar = document.getElementById("btnGenerarSuerte");
    const btnPagarSuerte = document.getElementById("btnPagarSuerte");

    function elegirAleatorios(array, cantidad) {
        let copia = [...array];
        let resultado = [];
        for (let i = 0; i < cantidad && copia.length > 0; i++) {
            let index = Math.floor(Math.random() * copia.length);
            resultado.push(copia.splice(index, 1)[0]);
        }
        return resultado;
    }

    btnGenerar.addEventListener("click", () => {
        const cantidad = parseInt(cantidadInput.value);
        if(isNaN(cantidad) || cantidad < 1){
            alert("Ingresa un número válido");
            return;
        }
        if(cantidad > disponibles.length){
            alert("Solo hay " + disponibles.length + " boletos disponibles.");
            return;
        }
        seleccionados = elegirAleatorios(disponibles, cantidad);
        listaSuerte.textContent = seleccionados.join(", ");
        totalSuerte.textContent = (seleccionados.length * precioBoleto).toFixed(2);
        resultado.classList.remove("d-none");
        btnPagarSuerte.classList.remove("d-none");
    });

    // === Abrir modal de datos al pagar ===
    const btnPagar = document.getElementById("btnPagar");
    btnPagar.addEventListener("click", () => {
        if(seleccionados.length === 0){
            alert("Selecciona al menos un boleto");
            return;
        }
        const modal = new bootstrap.Modal(document.getElementById("modalDatosCompra"));
        modal.show();
    });

    btnPagarSuerte.addEventListener("click", () => {
        if(seleccionados.length === 0){
            alert("Selecciona al menos un boleto");
            return;
        }
        const modal = new bootstrap.Modal(document.getElementById("modalDatosCompra"));
        modal.show();
    });

    // === Confirmar compra ===
    document.getElementById("btnConfirmarCompra").addEventListener("click", () => {
        const nombre = document.getElementById("nombreUsuario").value.trim();
        const apellido = document.getElementById("apellidoUsuario").value.trim();
        const telefono = document.getElementById("telefonoUsuario").value.trim();
        const estado = document.getElementById("estadoUsuario").value.trim();

        if(!nombre || !apellido || !telefono || !estado){
            alert("Completa todos los campos");
            return;
        }

        fetch("{{ route('rifa.reservar') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                rifa_id: {{ $rifa->id }},
                boletos: seleccionados,
                nombre: nombre,
                apellido: apellido,
                telefono: telefono,
                estado: estado
            })
        })
        .then(res => res.json())
     .then(data => {
    if(data.success){
        alert("Boletos reservados correctamente!");
        const mensaje = `Hola, quiero comprar los siguientes boletos de la rifa "{{ $rifa->nombre }}":\n\n` +
                        `🎟️ Números: ${seleccionados.join(", ")}\n` +
                        `💰 Precio por boleto: $${precioBoleto.toFixed(2)}\n` +
                        `📊 Total: $${(precioBoleto * seleccionados.length).toFixed(2)}\n\n` +
                        `📝 Datos:\nNombre: ${nombre}\nApellido: ${apellido}\nTeléfono: ${telefono}\nEstado: ${estado}`;

        const url = `https://wa.me/${data.numero}?text=${encodeURIComponent(mensaje)}`;
        window.open(url, "_blank");
        location.reload();
    } else {
        alert(data.message || "Error al reservar.");
    }
})

        .catch(err => {
            console.error(err);
            alert("Error al comunicarse con el servidor.");
        });
    });

});


</script>


@endsection
