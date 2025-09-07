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

    {{-- 📌 Selección de boletos --}}
    <div class="mt-5">
        <h3 class="fw-bold mb-3">🎟️ Selecciona tus números</h3>
        <div class="row g-2">
            @foreach($rifa->boletos as $boleto)
                <div class="col-2 col-sm-1">
                    @if($boleto->vendido)
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

{{-- Script para selección múltiple y WhatsApp --}}
<script>
    document.addEventListener("DOMContentLoaded", () => {
        let seleccionados = [];
        const precioBoleto = {{ $rifa->precio_boleto }};
        const resumen = document.getElementById("resumen");
        const listaSeleccionados = document.getElementById("listaSeleccionados");
        const totalPagar = document.getElementById("totalPagar");

        function actualizarResumen() {
            if (seleccionados.length > 0) {
                resumen.classList.remove("d-none");
                listaSeleccionados.textContent = seleccionados.join(", ");
                totalPagar.textContent = (seleccionados.length * precioBoleto).toFixed(2);
            } else {
                resumen.classList.add("d-none");
            }
        }

        document.querySelectorAll(".select-boleto").forEach(btn => {
            btn.addEventListener("click", () => {
                let numero = btn.dataset.numero;

                if (seleccionados.includes(numero)) {
                    // Deseleccionar
                    seleccionados = seleccionados.filter(n => n !== numero);
                    btn.classList.remove("btn-success");
                    btn.classList.add("btn-outline-dark");
                } else {
                    // Seleccionar
                    seleccionados.push(numero);
                    btn.classList.remove("btn-outline-dark");
                    btn.classList.add("btn-success");
                }

                actualizarResumen();
            });
        });

        document.getElementById("btnPagar").addEventListener("click", () => {
            if (seleccionados.length === 0) {
                alert("Por favor selecciona al menos un boleto.");
                return;
            }

            let mensaje = `Hola, quiero comprar los siguientes boletos de la rifa "{{ $rifa->nombre }}":\n\n` +
                          `🎟️ Números: ${seleccionados.join(", ")}\n` +
                          `💰 Precio por boleto: $${precioBoleto.toFixed(2)}\n` +
                          `📊 Total: $${(precioBoleto * seleccionados.length).toFixed(2)}`;

            let numeroWhatsApp = "521234567890"; // <-- Cambia este número por el tuyo
            let url = `https://wa.me/${numeroWhatsApp}?text=${encodeURIComponent(mensaje)}`;
            window.open(url, "_blank");
        });
    });
</script>
@endsection
