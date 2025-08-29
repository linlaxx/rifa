@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Boletos de {{ $rifa->nombre }}</h2>

    <div class="mb-3">
        <span class="badge bg-primary">Total: {{ $total }}</span>
        <span class="badge bg-success">Disponibles: {{ $disponibles }}</span>
        <span class="badge bg-danger">Vendidos: {{ $vendidos }}</span>
    </div>

    <div class="d-flex flex-wrap gap-2">
        @foreach($rifa->boletos as $boleto)
            <button 
                class="btn btn-sm toggle-boleto {{ $boleto->vendido ? 'btn-danger' : 'btn-success' }}"
                data-id="{{ $boleto->id }}">
                {{ $boleto->numero }}
            </button>
        @endforeach
    </div>
</div>

<script>
document.querySelectorAll('.toggle-boleto').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        fetch(`/admin/boletos/${id}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                // Cambiar color del botón
                this.classList.toggle('btn-success', !data.vendido);
                this.classList.toggle('btn-danger', data.vendido);
            }
        });
    });
});
</script>
@endsection
