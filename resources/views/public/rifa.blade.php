@extends('layouts.public')

@section('title', $rifa->titulo)

@section('content')
<section class="container mt-5">
    <h1>{{ $rifa->titulo }}</h1>
    <img src="{{ $rifa->imagen }}" class="img-fluid mb-3" alt="{{ $rifa->titulo }}">
    <p>{{ $rifa->descripcion }}</p>
    <p><strong>Precio del boleto:</strong> ${{ $rifa->precio }}</p>
    <p><strong>Fecha del sorteo:</strong> {{ $rifa->fecha_sorteo }}</p>
    <a href="#" class="btn btn-primary">Comprar Boleto</a>
</section>
@endsection
