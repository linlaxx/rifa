{{-- Contenedor con scroll --}}
<style>
#boletos-scroll {
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    max-height: 48vh;
}
@media(min-width: 992px){
    #boletos-scroll { max-height: 400px; }
}
</style>

<div id="boletos-scroll" class="border rounded p-3">
    <div class="row g-2">
        @foreach($boletos as $boleto)
            <div class="col-3 col-sm-2 col-md-1 mb-2">
                @if($boleto->vendido || !$boleto->disponible)
                    <div class="p-2 text-center rounded bg-dark text-white fw-bold">
                        {{ $boleto->numero }}
                    </div>
                @else
                    <button class="btn btn-outline-dark w-100 fw-bold select-boleto"
                            data-numero="{{ $boleto->numero }}">
                        {{ $boleto->numero }}
                    </button>
                @endif
            </div>
        @endforeach
    </div>
</div>

<div id="boletos-pagination" class="mt-3 d-flex justify-content-center">
    {{ $boletos->links() }}
</div>
