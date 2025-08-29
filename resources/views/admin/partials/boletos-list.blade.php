<div class="d-flex flex-wrap gap-2">
    @foreach($boletos as $boleto)
        <button 
            class="btn btn-sm toggle-boleto {{ $boleto->vendido ? 'btn-danger' : 'btn-success' }}"
            data-id="{{ $boleto->id }}">
            {{ $boleto->numero }}
        </button>
    @endforeach
</div>

<div class="mt-3">
    {{ $boletos->links() }}
</div>
