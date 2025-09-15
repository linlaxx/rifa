@extends('layouts.public')

@section('title', 'Rifas Activas')

@section('content')

{{-- 🎟️ Carrusel de Rifas Activas --}}
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-5 text-dark display-6">🎟️ Rifas Activas</h2>

        @if($rifas->count() > 0)
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    @foreach($rifas as $rifa)
                        @php
                            $imagenes = is_array($rifa->fotos) ? $rifa->fotos : json_decode($rifa->fotos, true);
                            $imagenPrincipal = $imagenes[0] ?? null;

                            $total = $rifa->total_boletos;
                            $vendidos = $rifa->vendidos;
                            $porcentaje = $total > 0 ? round(($vendidos / $total) * 100, 2) : 0;

                            if ($porcentaje < 50) {
                                $color = 'linear-gradient(90deg, #28a745, #20c997)';
                            } elseif ($porcentaje < 80) {
                                $color = 'linear-gradient(90deg, #ffc107, #fd7e14)';
                            } else {
                                $color = 'linear-gradient(90deg, #dc3545, #b21f2d)';
                            }
                        @endphp

                        <div class="swiper-slide">
                            <div class="card h-100 shadow-lg border-0 rounded-4 overflow-hidden position-relative rifa-card">
                                <img src="{{ $imagenPrincipal ? asset('storage/' . $imagenPrincipal) : asset('images/default_rifa.png') }}" 
                                     class="card-img-top" 
                                     style="height: 230px; object-fit: cover;" 
                                     alt="{{ $rifa->nombre }}">

                                <div class="card-body d-flex flex-column p-4">
                                    <h5 class="card-title fw-bold text-dark">{{ $rifa->nombre }}</h5>
                                    <p class="card-text text-muted small">{{ Str::limit($rifa->descripcion, 90) }}</p>

                                    {{-- 📊 Barra de progreso --}}
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between small mb-1 fw-semibold text-secondary">
                                            <span>🎟️ Vendidos: {{ $vendidos }}</span>
                                            <span>Total: {{ $total }}</span>
                                        </div>

                                        <div class="progress" style="height: 22px; border-radius: 12px; background: #f1f3f5;">
                                            <div class="progress-bar fw-bold text-white position-relative" 
                                                 role="progressbar" 
                                                 style="
                                                    width: {{ $porcentaje }}%; 
                                                    border-radius: 12px; 
                                                    background: {{ $color }};
                                                    transition: width 1.5s ease-in-out;
                                                 " 
                                                 aria-valuenow="{{ $porcentaje }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">

                                                <span class="position-absolute top-50 start-50 translate-middle small fw-bold">
                                                    {{ $porcentaje }}%
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="fw-bold text-success mb-3 fs-5">
                                        🎫 ${{ number_format($rifa->precio_boleto, 2) }}
                                    </p>

                                    <a href="{{ route('public.rifa', $rifa->id) }}" 
                                       class="btn btn-warning w-100 mt-auto fw-bold rounded-pill shadow-sm">
                                        Participar
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Controles de Swiper --}}
                <div class="swiper-pagination mt-4"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        @else
            <p class="text-center text-muted">⚠️ No hay rifas disponibles en este momento.</p>
        @endif
    </div>
</section>

{{-- ❓ Preguntas Frecuentes --}}
<section id="preguntas-frecuentes" class="bg-white py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-4">❓ Preguntas Frecuentes</h2>
        <div class="accordion shadow-sm" id="faqAccordion">

            {{-- Pregunta 1 --}}
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                        ¿Cómo compro un boleto?
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Puedes comprar tus boletos en línea mediante nuestra plataforma segura y rápida.
                    </div>
                </div>
            </div>

            {{-- Pregunta 2 --}}
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                        ¿Cuándo se realiza el sorteo?
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Cada rifa tiene su fecha específica, revisa la descripción para más detalles.
                    </div>
                </div>
            </div>

            {{-- Pregunta 3 --}}
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                        ¿Puedo seleccionar varios boletos al mismo tiempo?
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Sí, puedes seleccionar tantos boletos como quieras, y el precio total se actualizará automáticamente.
                    </div>
                </div>
            </div>

            {{-- Pregunta 4 --}}
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">
                        ¿Cómo sé si gané?
                    </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Los ganadores se anuncian en la sección de resultados de nuestra página, y también recibirás una notificación por WhatsApp.
                    </div>r
                </div>
            </div>

            {{-- Pregunta 5 --}}
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFive">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive">
                        ¿Qué métodos de pago aceptan?
                    </button>
                </h2>
                <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Aceptamos transferencias bancarias o depositos en OXXO.
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- 🌐 Botón Flotante de WhatsApp con Logo --}}
<a href="https://wa.me/5216471174653?text=Hola!%20Quiero%20información%20sobre%20las%20rifas" 
   class="btn-whatsapp" target="_blank">
   <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" 
        alt="WhatsApp" width="40" height="40">
</a>

{{-- Swiper.js --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<script>
    var swiper = new Swiper(".mySwiper", {
        effect: "coverflow",
        grabCursor: true,
        centeredSlides: true,
        loop: true,
        autoplay: {
            delay: 3500,
            disableOnInteraction: false,
        },
        slidesPerView: "auto",
        coverflowEffect: {
            rotate: 30,
            stretch: 0,
            depth: 150,
            modifier: 1,
            slideShadows: true,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
            dynamicBullets: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        breakpoints: {
            640: { slidesPerView: 1 },
            768: { slidesPerView: 2 },
            1024: { slidesPerView: 3 },
        },
    });
    
</script>

{{-- 🎨 Extra CSS para mejorar diseño --}}
<style>
    .rifa-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .rifa-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 12px 24px rgba(0,0,0,0.15);
    }
    .swiper-button-next, .swiper-button-prev {
        color: #ffc107;
        transition: 0.3s;
    }
    .swiper-button-next:hover, .swiper-button-prev:hover {
        color: #ff9800;
    }
    .swiper-pagination-bullet {
        background: #ffc107;
        opacity: 0.7;
    }
    .swiper-pagination-bullet-active {
        background: #ff9800;
        opacity: 1;
    }

    /* 🎯 Estilo del botón flotante */
    .btn-whatsapp {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #25d366;
        border-radius: 50%;
        width: 65px;
        height: 65px;
        display: flex;
        justify-content: center;
        align-items: center;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 1000;
        transition: transform 0.3s ease, background 0.3s ease;
    }
    .btn-whatsapp:hover {
        transform: scale(1.1);
        background-color: #20b954;
    }
    .btn-whatsapp img {
        width: 35px;
        height: 35px;
    }
</style>

@endsection
