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
                                    <img src="{{ asset('storage/' . $img) }}" class="d-block w-100 rounded"
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
                    <img src="{{ asset('images/default_rifa.png') }}" class="img-fluid rounded shadow-sm"
                        alt="Imagen por defecto">
                @endif
            </div>

            {{-- Información de la rifa --}}
            <div class="col-lg-6 d-flex flex-column justify-content-center">
                <h1 class="fw-bold mb-3">{{ $rifa->nombre }}</h1>
                <p class="text-muted">{{ $rifa->descripcion }}</p>
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between">
                        <span><i class="bi bi-cash-coin"></i> Precio del boleto</span>
                        <strong>
                            ${{ number_format($rifa->precio_boleto, 2) }}
                            @if($rifa->descuento_por_cantidad)
                                (o ${{ number_format($rifa->precio_descuento, 2) }} si compras más de
                                {{ $rifa->cantidad_descuento }} boletos)
                            @endif
                        </strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span><i class="bi bi-calendar-event"></i> Fecha del sorteo</span>
                        <strong>
                            {{ \Carbon\Carbon::parse($rifa->fecha_sorteo)->format('d/m/Y H:i') }}
                        </strong>
                    </li>
                </ul>
            </div>

            {{-- Botón Máquina de la suerte --}}
            <div class="text-center my-4">
                <button class="btn btn-warning btn-lg fw-bold" data-bs-toggle="modal" data-bs-target="#modalSuerte">
                    🍀 Máquina de la suerte
                </button>
            </div>

            {{-- Selección de boletos --}}
            <div class="mt-5">
                <h3 class="fw-bold mb-3">🎟️ Selecciona tus números</h3>
                <div id="boletos-container" style="max-height:500px; overflow-y:auto;" class="row g-2 border p-2 rounded">
                </div>

                {{-- Paginación --}}
                <div class="d-flex justify-content-center mt-3 gap-3 flex-wrap">
                    <button id="prevPage" class="btn btn-outline-dark">« Anterior</button>
                    <button id="nextPage" class="btn btn-outline-dark">Siguiente »</button>
                </div>

                {{-- Resumen de selección --}}
                <div id="resumen"
                    class="alert alert-light border mt-4 d-none d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <p class="mb-1"><strong>Boletos seleccionados:</strong> <span id="listaSeleccionados"></span></p>
                        <p class="mb-0"><strong>Total a pagar:</strong> $<span id="totalPagar">0.00</span></p>
                    </div>
                    <button id="btnBorrarSeleccion" class="btn btn-sm btn-danger fw-bold ms-2 mt-2 mt-md-0">Borrar
                        selección</button>
                </div>

                {{-- Botón pagar con WhatsApp --}}
                <div class="text-center mt-3">
                    <button id="btnPagar" class="btn btn-success btn-lg fw-bold">
                        <i class="bi bi-whatsapp"></i> Pagar por WhatsApp
                    </button>
                </div>
            </div>
    </section>

    {{-- Modal Máquina de la suerte --}}
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
                        <p class="mb-1"><strong>Boletos generados:</strong></p>
                        <p id="listaSuerte" class="fw-bold" style="font-size:1.2em;"></p>
                        <p class="mb-0"><strong>Total a pagar:</strong> $<span id="totalSuerte">0.00</span></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btnGenerar" class="btn btn-dark fw-bold">🎲 Generar</button>
                    <button id="btnAgregarLista" class="btn btn-success fw-bold d-none">➕ Agregar a la lista</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Datos de Compra --}}
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

    <style>
        /* ===== Botones de boletos responsivos y cuadrados ===== */
        #boletos-container button.select-boleto {
            width: 100%;
            aspect-ratio: 1 / 1;
            /* cuadrado perfecto */
            padding: 0.25rem;
            font-size: clamp(0.8rem, 2vw, 1rem);
            display: flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-weight: 700;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            font-size: clamp(1rem, 2vw, 1.2rem);
            text-overflow: ellipsis;
            /* negrita */
        }

        /* Ajuste de columnas responsive */
        #boletos-container .col-2,
        #boletos-container .col-sm-1 {
            flex: 0 0 8.33%;
            /* 12 columnas aprox */
            max-width: 8.33%;
        }

        @media (max-width: 768px) {

            #boletos-container .col-2,
            #boletos-container .col-sm-1 {
                flex: 0 0 12.5%;
                /* 8 columnas */
                max-width: 12.5%;
            }
        }

        @media (max-width: 576px) {

            #boletos-container .col-2,
            #boletos-container .col-sm-1 {
                flex: 0 0 16.66%;
                /* 6 columnas */
                max-width: 16.66%;
            }
        }

        @media (max-width: 400px) {

            #boletos-container .col-2,
            #boletos-container .col-sm-1 {
                flex: 0 0 20%;
                /* 5 columnas */
                max-width: 20%;
            }
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const PRECIO_BOLETO = {{ $rifa->precio_boleto }};
            const PRECIO_DESCUENTO = 15;
            const CANTIDAD_DESCUENTO = 10;

            const boletosPorPagina = 10000;
            let paginaActual = 1;
            let totalPaginas = 1;
            let seleccionados = [];
            let generadosSuerte = [];

            const container = document.getElementById("boletos-container");
            const listaSeleccionadosEl = document.getElementById("listaSeleccionados");
            const totalPagarEl = document.getElementById("totalPagar");

            function precioUnitario(cantidad) {
                if (PRECIO_BOLETO === 19 && cantidad > CANTIDAD_DESCUENTO) {
                    return PRECIO_DESCUENTO;
                }
                return PRECIO_BOLETO;
            }

            async function renderBoletos() {
                container.innerHTML = `<div class="col-12 text-center py-5">Cargando boletos...</div>`;
                const res = await fetch(`/rifa/{{ $rifa->id }}/boletos?perPage=${boletosPorPagina}&page=${paginaActual}`);
                const data = await res.json();
                totalPaginas = data.totalPaginas;
                container.innerHTML = "";

                data.boletos.forEach(b => {
                    const col = document.createElement("div");
                    col.className = "col-2 col-sm-1 mb-1";

                    if (b.vendido || !b.disponible) {
                        col.innerHTML = `<div class="p-2 text-center rounded bg-dark text-white fw-bold">${b.numero}</div>`;
                    } else {
                        const btn = document.createElement("button");
                        btn.className = "btn btn-outline-dark select-boleto";
                        btn.textContent = b.numero;
                        btn.dataset.numero = b.numero;

                        if (seleccionados.includes(b.numero)) btn.classList.replace("btn-outline-dark", "btn-dark");

                        btn.addEventListener("click", () => {
                            const index = seleccionados.indexOf(b.numero);
                            if (index > -1) {
                                seleccionados.splice(index, 1);
                                btn.classList.replace("btn-dark", "btn-outline-dark");
                            } else {
                                seleccionados.push(b.numero);
                                btn.classList.replace("btn-outline-dark", "btn-dark");
                            }
                            actualizarResumen();
                        });

                        col.appendChild(btn);
                    }

                    container.appendChild(col);
                });

                document.getElementById("prevPage").disabled = paginaActual <= 1;
                document.getElementById("nextPage").disabled = paginaActual >= totalPaginas;
            }

            function actualizarResumen() {
                if (seleccionados.length > 0) {
                    document.getElementById("resumen").classList.remove("d-none");
                    listaSeleccionadosEl.textContent = seleccionados.join(", ");
                    const total = precioUnitario(seleccionados.length) * seleccionados.length;
                    totalPagarEl.textContent = total.toFixed(2);
                } else {
                    document.getElementById("resumen").classList.add("d-none");
                }
            }

            document.getElementById("prevPage").addEventListener("click", () => {
                if (paginaActual > 1) paginaActual--;
                renderBoletos();
            });

            document.getElementById("nextPage").addEventListener("click", () => {
                if (paginaActual < totalPaginas) paginaActual++;
                renderBoletos();
            });

            document.getElementById("btnBorrarSeleccion").addEventListener("click", () => {
                seleccionados = [];
                actualizarResumen();
                document.querySelectorAll('#boletos-container button.select-boleto').forEach(btn => {
                    btn.classList.replace("btn-dark", "btn-outline-dark");
                });
            });

            renderBoletos();
            actualizarResumen();

            // --- Máquina de la suerte ---
            const cantidadInput = document.getElementById("cantidadSuerte");
            const resultado = document.getElementById("resultadoSuerte");
            const listaSuerte = document.getElementById("listaSuerte");
            const totalSuerte = document.getElementById("totalSuerte");
            const btnGenerar = document.getElementById("btnGenerar");
            const btnAgregarLista = document.getElementById("btnAgregarLista");

            function elegirAleatorios(array, cantidad) {
                let copia = [...array];
                let resultado = [];
                for (let i = 0; i < cantidad && copia.length > 0; i++) {
                    const index = Math.floor(Math.random() * copia.length);
                    resultado.push(copia.splice(index, 1)[0]);
                }
                return resultado;
            }

            btnGenerar.addEventListener("click", async () => {
                const cantidad = parseInt(cantidadInput.value);
                if (isNaN(cantidad) || cantidad < 1) {
                    alert("Ingresa un número válido");
                    return;
                }

                btnGenerar.disabled = true;
                btnAgregarLista.classList.add("d-none");
                listaSuerte.textContent = "Generando...";
                resultado.classList.remove("d-none");

                const res = await fetch(`/rifa/{{ $rifa->id }}/boletos-disponibles`);
                const data = await res.json();
                const disponibles = data.boletos
                    .filter(b => !b.vendido && b.disponible && !seleccionados.includes(b.numero))
                    .map(b => b.numero);

                if (cantidad > disponibles.length) {
                    alert("Solo hay " + disponibles.length + " boletos disponibles.");
                    btnGenerar.disabled = false;
                    listaSuerte.textContent = "";
                    return;
                }

                generadosSuerte = elegirAleatorios(disponibles, cantidad);

                listaSuerte.textContent = "";
                generadosSuerte.forEach((n, i) => {
                    const span = document.createElement("span");
                    span.textContent = n;
                    span.style.opacity = 0;
                    span.style.transition = "opacity 0.3s, transform 0.3s";
                    span.style.display = "inline-block";
                    listaSuerte.appendChild(span);
                    if (i < generadosSuerte.length - 1) listaSuerte.appendChild(document.createTextNode(", "));
                });

                const spans = listaSuerte.querySelectorAll("span");
                for (let i = 0; i < spans.length; i++) {
                    spans[i].style.transform = "translateY(-20px)";
                    await new Promise(r => setTimeout(r, 100));
                    spans[i].style.opacity = 1;
                    spans[i].style.transform = "translateY(0)";
                }

                totalSuerte.textContent = (precioUnitario(generadosSuerte.length) * generadosSuerte.length).toFixed(2);
                btnGenerar.disabled = false;
                btnAgregarLista.classList.remove("d-none");
            });

            btnAgregarLista.addEventListener("click", () => {
                seleccionados.push(...generadosSuerte);
                generadosSuerte = [];
                actualizarResumen();
                renderBoletos();
                btnAgregarLista.classList.add("d-none");
                listaSuerte.textContent = "";
            });

            function abrirModalDatos() {
                if (seleccionados.length === 0) {
                    alert("Selecciona al menos un boleto");
                    return;
                }
                document.querySelectorAll('.modal.show').forEach(m => bootstrap.Modal.getInstance(m)?.hide());
                document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
                const modal = new bootstrap.Modal(document.getElementById("modalDatosCompra"));
                modal.show();
            }

            document.getElementById("btnPagar").addEventListener("click", abrirModalDatos);

            document.getElementById("btnConfirmarCompra").addEventListener("click", () => {
                const nombre = document.getElementById("nombreUsuario").value.trim();
                const apellido = document.getElementById("apellidoUsuario").value.trim();
                const telefono = document.getElementById("telefonoUsuario").value.trim();
                const estado = document.getElementById("estadoUsuario").value.trim();

                if (!nombre || !apellido || !telefono || !estado) {
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
                        rifa_id:{{ $rifa->id }},
                        boletos: seleccionados,
                        nombre, apellido, telefono, estado
                    })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert("Boletos reservados correctamente!");
                            const numero = String(data.numero).replace(/\D/g, '');
                            const total = precioUnitario(seleccionados.length) * seleccionados.length;
                            const mensaje = `Hola, quiero comprar los siguientes boletos de la rifa "{{ $rifa->nombre }}":\n\n` +
                                `🎫 Números: ${seleccionados.join(", ")}\n` +
                                `💰 Precio por boleto: $${precioUnitario(seleccionados.length).toFixed(2)}\n` +
                                `📊 Total: $${total.toFixed(2)}\n\n` +
                                `📝 Datos:\nNombre: ${nombre}\nApellido: ${apellido}\nTeléfono: ${telefono}\nEstado: ${estado}`;

                            window.open(`https://wa.me/${data.numero}?text=${encodeURIComponent(mensaje)}`, "_blank");
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