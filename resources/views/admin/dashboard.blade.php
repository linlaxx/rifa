@extends('layouts.admin')

@section('content')
<div class="text-center mb-5">
    <h1 class="fw-bold">Bienvenido Natael 🎉</h1>
    <p class="lead">Este es tu administrador de rifas. Gestiona tus sorteos fácilmente:</p>

    <div class="mt-4">
        <a href="{{ route('admin.crearSorteo') }}" class="btn btn-primary btn-lg me-2">
            <i class="bi bi-plus-circle"></i> Crear Rifa
        </a>
        <a href="{{ route('admin.listado') }}" class="btn btn-success btn-lg">
            <i class="bi bi-card-list"></i> Listado de Rifas
        </a>
    </div>
</div>

<hr class="my-5">

{{-- Resumen general --}}
<div class="row text-center mb-5">
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Total Rifas</h6>
                <h3 class="fw-bold">{{ $rifas->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Total Boletos</h6>
                <h3 class="fw-bold">{{ $rifas->sum('total_boletos') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Vendidos</h6>
                <h3 class="fw-bold text-success">{{ $rifas->sum('vendidos') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Disponibles</h6>
                <h3 class="fw-bold text-primary">{{ $rifas->sum('disponibles') }}</h3>
            </div>
        </div>
    </div>
</div>

{{-- Tarjetas con barra de progreso --}}
<div class="row">
    @foreach($rifas as $rifa)
        @php
            $porcentaje = $rifa->total_boletos > 0 
                ? round(($rifa->vendidos / $rifa->total_boletos) * 100, 2)
                : 0;
        @endphp
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">{{ $rifa->nombre }}</h5>
                    <p class="card-text">{{ $rifa->descripcion }}</p>

                    <div class="progress mb-3" style="height: 22px;">
                        <div class="progress-bar 
                            {{ $porcentaje < 50 ? 'bg-success' : ($porcentaje < 80 ? 'bg-warning' : 'bg-danger') }}" 
                            role="progressbar" 
                            style="width: {{ $porcentaje }}%;" 
                            aria-valuenow="{{ $porcentaje }}" 
                            aria-valuemin="0" 
                            aria-valuemax="100">
                            {{ $porcentaje }}%
                        </div>
                    </div>

                    <p class="mb-0">
                        <strong>{{ $rifa->vendidos }}</strong> vendidos /
                        {{ $rifa->total_boletos }} boletos
                    </p>
                </div>
            </div>
        </div>
    @endforeach
</div>

<hr class="my-5">

{{-- Gráfico de barras --}}
<div class="card shadow-sm">
    <div class="card-body">
        <h4 class="card-title text-center mb-4">Estadísticas Generales</h4>
        <canvas id="rifasChart" height="120"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('rifasChart').getContext('2d');
    const rifasChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($rifas->pluck('nombre')),
            datasets: [
                {
                    label: 'Vendidos',
                    data: @json($rifas->pluck('vendidos')),
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                },
                {
                    label: 'Disponibles',
                    data: @json($rifas->pluck('disponibles')),
                    backgroundColor: 'rgba(75, 192, 192, 0.7)',
                },
                {
                    label: '% Vendidos',
                    data: @json($rifas->map(function($r) {
                        return $r->total_boletos > 0 ? round(($r->vendidos / $r->total_boletos) * 100, 2) : 0;
                    })),
                    backgroundColor: 'rgba(255, 206, 86, 0.7)',
                    type: 'line',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 2,
                    fill: false,
                    yAxisID: 'percentage'
                }
            ]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            stacked: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Cantidad de Boletos' }
                },
                percentage: {
                    position: 'right',
                    beginAtZero: true,
                    min: 0,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    },
                    title: { display: true, text: 'Porcentaje Vendido' }
                }
            }
        }
    });
</script>
@endsection
