@if ($paginator->hasPages())
    <nav>
        <ul class="pagination pagination-sm mb-0">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">
                        <i class="bi bi-chevron-left"></i>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled">
                        <span class="page-link">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">
                        <i class="bi bi-chevron-right"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>

    <style>
    .pagination-container {
        margin-top: 1rem;
    }

    .pagination {
        display: flex;
        padding-left: 0;
        list-style: none;
        border-radius: 0.5rem;
        gap: 0.25rem;
    }

    .page-item {
        margin: 0;
    }

    .page-item:first-child .page-link {
        border-top-left-radius: 0.5rem;
        border-bottom-left-radius: 0.5rem;
    }

    .page-item:last-child .page-link {
        border-top-right-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
    }

    .page-link {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 0.75rem;
        min-width: 2.5rem;
        font-size: 0.875rem;
        line-height: 1.25;
        color: var(--primary-color);
        background-color: #fff;
        border: 1px solid #e9ecef;
        transition: all 0.2s ease-in-out;
    }

    .page-link:hover {
        z-index: 2;
        color: #fff;
        text-decoration: none;
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .page-item.active .page-link {
        z-index: 3;
        color: #fff;
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        box-shadow: 0 2px 5px rgba(26, 71, 137, 0.2);
    }

    .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }

    @media (max-width: 576px) {
        .pagination {
            flex-wrap: wrap;
            justify-content: center;
        }

        .page-link {
            padding: 0.4rem 0.6rem;
            min-width: 2rem;
        }
    }
    </style>
@endif
