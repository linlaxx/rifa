<div id="listado-boletos">
    {{-- Cuadrícula de boletos --}}
    <div class="d-flex flex-wrap gap-2">
        @foreach($boletos as $boleto)
            <button 
                class="btn btn-sm toggle-boleto {{ $boleto->vendido ? 'btn-danger' : 'btn-success' }}"
                data-id="{{ $boleto->id }}"
                data-numero="{{ $boleto->numero }}">
                {{ $boleto->numero }}
            </button>
        @endforeach
    </div>

    {{-- Paginación centrada --}}
    <div class="mt-3 d-flex justify-content-center">
        <nav>
            <ul class="pagination pagination-sm mb-0">
                {{-- Previous --}}
                @if ($boletos->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $boletos->previousPageUrl() }}">&laquo;</a></li>
                @endif

                {{-- Números de página --}}
                @foreach ($boletos->getUrlRange(max($boletos->currentPage()-2,1), min($boletos->currentPage()+2,$boletos->lastPage())) as $page => $url)
                    @if ($page == $boletos->currentPage())
                        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($boletos->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $boletos->nextPageUrl() }}">&raquo;</a></li>
                @else
                    <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                @endif
            </ul>
        </nav>
    </div>
</div>
