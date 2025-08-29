@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Boletos de {{ $rifa->nombre }}</h2>

    <div class="mb-3">
        <span class="badge bg-primary">Total: {{ $total }}</span>
        <span class="badge bg-success">Disponibles: {{ $disponibles }}</span>
        <span class="badge bg-danger">Vendidos: {{ $vendidos }}</span>
    </div>

    <input type="number" id="buscarBoleto" class="form-control mb-3" placeholder="Buscar boletos por número">

    <div id="listado-boletos">
        @include('admin.partials.boletos-list', ['boletos' => $boletos])
    </div>
</div>

<script>
function activarToggleBoleto() {
    document.querySelectorAll('.toggle-boleto').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const url = "{{ route('admin.toggleBoleto', ':id') }}".replace(':id', id);

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    this.classList.toggle('btn-success', !data.vendido);
                    this.classList.toggle('btn-danger', data.vendido);
                }
            });
        });
    });
}

// Activar toggles al cargar la página
activarToggleBoleto();

// Búsqueda en tiempo real
document.getElementById('buscarBoleto').addEventListener('keyup', function() {
    const q = this.value;
    const rifaId = '{{ $rifa->id }}';
    
    fetch(`/admin/rifas/${rifaId}/boletos/search?q=${q}`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('listado-boletos').innerHTML = html;
            // Reaplicar eventos a los botones nuevos
            activarToggleBoleto();
        });
});
</script>

@endsection
