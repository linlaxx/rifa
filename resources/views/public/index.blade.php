@extends('layouts.public')

@section('title', 'Inicio')

@section('content')
<div id="rifasCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
    <div class="carousel-inner">
        @forelse($rifas as $index => $rifa)
            <div class="carousel-item @if($index == 0) active @endif text-center">
                <a href="{{ route('public.rifa', $rifa->id) }}">
                    <img src="{{ $rifa->fotos 
                                    ? asset('storage/rifas/' . $rifa->fotos) 
                                    : asset('images/default_rifa.png') }}" 
                         class="d-block mx-auto shadow-lg"
                         style="max-width: 400px; max-height: 600px; width: 100%; height: auto; object-fit: cover; border-radius: 15px;" 
                         alt="{{ $rifa->nombre }}">
                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-75 rounded p-3 shadow mt-3">
                        <h5 class="fw-bold">{{ $rifa->nombre }}</h5>
                        <p class="mb-0 text-warning">Precio del boleto: ${{ number_format($rifa->precio_boleto, 2) }}</p>
                    </div>
                </a>
            </div>
        @empty
            <div class="carousel-item active text-center">
                <img src="{{ asset('images/default_rifa.png') }}" 
                     class="d-block mx-auto shadow-lg"
                     style="max-width: 400px; max-height: 600px; width: 100%; height: auto; object-fit: cover; border-radius: 15px;" 
                     alt="Sin rifas disponibles">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-75 rounded p-3 shadow mt-3">
                    <h5>No hay rifas activas</h5>
                    <p class="mb-0">Vuelve pronto para ver nuevas rifas</p>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Controles --}}
    <button class="carousel-control-prev" type="button" data-bs-target="#rifasCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: black; border-radius: 50%;"></span>
        <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#rifasCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: black; border-radius: 50%;"></span>
        <span class="visually-hidden">Siguiente</span>
    </button>
</div>

{{-- Preguntas frecuentes --}}
<section id="preguntas-frecuentes" class="bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-4">Preguntas Frecuentes</h2>
        <div class="accordion" id="faqAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                        ¿Cómo compro un boleto?
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Puedes comprar tus boletos en línea mediante nuestra plataforma segura.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                        ¿Cuándo se realiza el sorteo?
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Cada rifa tiene su fecha específica indicada en la descripción.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Contacto --}}
<section id="contacto" class="py-5 bg-dark text-white">
    <div class="container">
        <h2 class="text-center mb-4">Contacto</h2>
        <form class="row g-3 mx-auto" style="max-width: 700px;">
            <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Tu nombre" required>
            </div>
            <div class="col-md-6">
                <input type="email" class="form-control" placeholder="Tu correo" required>
            </div>
            <div class="col-12">
                <textarea class="form-control" rows="4" placeholder="Tu mensaje..." required></textarea>
            </div>
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-warning px-4">Enviar</button>
            </div>
        </form>
    </div>
</section>
@endsection
