<ul class="pagination">
    {{-- Botón "Anterior" --}}
    @if ($paginator->onFirstPage())
        <li class="page-item disabled"><span class="page-link"><i class="fa fa-angle-double-left"></i></span></li>
    @else
        <li class="page-item"><a href="{{ $paginator->previousPageUrl() }}" class="page-link"><i class="fa fa-angle-double-left"></i></a></li>
    @endif

    {{-- Páginas --}}
    @foreach ($elements as $page => $url)
        @if ($page == $paginator->currentPage())
            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
        @else
            <li class="page-item"><a href="{{ $url }}" class="page-link">{{ $page }}</a></li>
        @endif
    @endforeach

    {{-- Botón "Siguiente" --}}
    @if ($paginator->hasMorePages())
        <li class="page-item"><a href="{{ $paginator->nextPageUrl() }}" class="page-link"><i class="fa fa-angle-double-right"></i></a></li>
    @else
        <li class="page-item disabled"><span class="page-link"><i class="fa fa-angle-double-right"></i></span></li>
    @endif
</ul>
