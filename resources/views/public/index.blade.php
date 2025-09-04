@extends('layouts.public')

@section('title', 'Inicio')

@section('content')
<div id="rifasCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        @forelse($rifas as $index => $rifa)
            <div class="carousel-item @if($index == 0) active @endif">
                <a href="{{ route('public.rifa', $rifa->id) }}">
                    <img src="{{ $rifa->fotos ?? asset('images/default_rifa.png') }}" 
                         class="d-block mx-auto" 
                         style="max-width: 400px; max-height: 600px; width: 100%; height: auto; object-fit: cover; border-radius: 10px;" 
                         alt="{{ $rifa->nombre }}">
                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2 mt-2">
                        <h5>{{ $rifa->nombre }}</h5>
                        <p>Precio del boleto: ${{ number_format($rifa->precio_boleto, 2) }}</p>
                    </div>
                </a>
            </div>
        @empty
            <div class="carousel-item active">
                <img src="{{ asset('images/default_rifa.png') }}" 
                     class="d-block mx-auto" 
                     style="max-width: 400px; max-height: 600px; width: 100%; height: auto; object-fit: cover; border-radius: 10px;" 
                     alt="Sin rifas disponibles">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2 mt-2">
                    <h5>No hay rifas activas</h5>
                    <p>Vuelve pronto para ver nuevas rifas</p>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Controles --}}
    <button class="carousel-control-prev" type="button" data-bs-target="#rifasCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true" 
              style="background-color: black; border-radius: 50%;"></span>
        <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#rifasCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true" 
              style="background-color: black; border-radius: 50%;"></span>
        <span class="visually-hidden">Siguiente</span>
    </button>
</div>

<section id="preguntas-frecuentes" class="bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-4">Preguntas Frecuentes</h2>
        <p>Contenido de preguntas frecuentes...</p>
    </div>
</section>

<section id="contacto" class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Contacto</h2>
        <p>Contenido de contacto...</p>
    </div>
</section>
@endsection
