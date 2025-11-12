@if ($paginator->hasPages())
    <nav>
        <ul class="pagination pagination-modern mb-0">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link page-link-modern">
                        <i class="bi bi-chevron-double-left"></i>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link page-link-modern" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        <i class="bi bi-chevron-double-left"></i>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled">
                        <span class="page-link page-link-modern page-dots">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <span class="page-link page-link-modern page-active">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link page-link-modern" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link page-link-modern" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        <i class="bi bi-chevron-double-right"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link page-link-modern">
                        <i class="bi bi-chevron-double-right"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>

    <style>
    :root {
        --color-primary: #1a4789;
        --color-secondary: #39a6cb;
        --color-accent1: #63becf;
        --color-accent2: #055c9d;
        --gradient-primary: linear-gradient(135deg, #1a4789 0%, #055c9d 100%);
        --gradient-secondary: linear-gradient(135deg, #39a6cb 0%, #63becf 100%);
    }

    .pagination-container {
        margin-top: 1.5rem;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .pagination-modern {
        display: flex;
        padding-left: 0;
        list-style: none;
        border-radius: 12px;
        gap: 0.5rem;
        background: linear-gradient(135deg, rgba(26, 71, 137, 0.03) 0%, rgba(99, 190, 207, 0.03) 100%);
        padding: 0.75rem;
    }

    .page-item {
        margin: 0;
    }

    .page-link-modern {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.625rem 0.875rem;
        min-width: 2.75rem;
        height: 2.75rem;
        font-size: 0.9rem;
        font-weight: 500;
        line-height: 1;
        color: var(--color-primary);
        background-color: #fff;
        border: 2px solid rgba(26, 71, 137, 0.15);
        border-radius: 10px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        overflow: hidden;
    }

    /* Efecto de onda al hacer hover */
    .page-link-modern::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(26, 71, 137, 0.1);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .page-link-modern:hover::before {
        width: 300%;
        height: 300%;
    }

    .page-link-modern:hover {
        color: #fff;
        text-decoration: none;
        background: var(--gradient-primary);
        border-color: var(--color-primary);
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(26, 71, 137, 0.4);
        z-index: 2;
    }

    .page-link-modern i {
        position: relative;
        z-index: 1;
        font-size: 1rem;
    }

    /* Página activa con gradiente y animación */
    .page-item.active .page-link-modern,
    .page-active {
        color: #fff;
        background: var(--gradient-primary);
        border-color: var(--color-primary);
        box-shadow: 0 4px 15px rgba(26, 71, 137, 0.4);
        transform: scale(1.1);
        z-index: 3;
        animation: pulse-page 2s infinite;
    }

    @keyframes pulse-page {
        0%, 100% {
            box-shadow: 0 4px 15px rgba(26, 71, 137, 0.4);
        }
        50% {
            box-shadow: 0 6px 25px rgba(26, 71, 137, 0.6);
        }
    }

    .page-item.active .page-link-modern:hover {
        transform: scale(1.1) translateY(-2px);
        box-shadow: 0 8px 30px rgba(26, 71, 137, 0.5);
    }

    /* Estado deshabilitado con estilo suave */
    .page-item.disabled .page-link-modern {
        color: #adb5bd;
        pointer-events: none;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-color: #e9ecef;
        cursor: not-allowed;
        opacity: 0.6;
    }

    /* Puntos suspensivos con estilo especial */
    .page-dots {
        background: transparent;
        border: none;
        color: var(--color-secondary);
        font-weight: 600;
        letter-spacing: 2px;
    }

    /* Botones de navegación (anterior/siguiente) con gradiente secundario */
    .page-item:first-child .page-link-modern,
    .page-item:last-child .page-link-modern {
        background: linear-gradient(135deg, rgba(57, 166, 203, 0.1) 0%, rgba(99, 190, 207, 0.1) 100%);
        border-color: var(--color-secondary);
        color: var(--color-secondary);
    }

    .page-item:first-child .page-link-modern:hover,
    .page-item:last-child .page-link-modern:hover {
        background: var(--gradient-secondary);
        border-color: var(--color-secondary);
        color: #fff;
        box-shadow: 0 6px 20px rgba(57, 166, 203, 0.4);
    }

    /* Animación de entrada */
    .pagination-modern {
        animation: fadeInUp 0.5s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Efecto de ripple en click */
    .page-link-modern:active {
        transform: scale(0.95);
        transition: transform 0.1s;
    }

    /* Números con animación sutil */
    .page-link-modern:not(.page-dots):not(:disabled) {
        position: relative;
        z-index: 1;
    }

    /* Responsive mejorado */
    @media (max-width: 768px) {
        .pagination-modern {
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.375rem;
            padding: 0.5rem;
        }

        .page-link-modern {
            padding: 0.5rem 0.625rem;
            min-width: 2.25rem;
            height: 2.25rem;
            font-size: 0.8rem;
        }

        .page-link-modern i {
            font-size: 0.9rem;
        }

        /* Ocultar algunos números en móvil para mejor UX */
        .page-item:not(.active):not(:first-child):not(:last-child) {
            display: none;
        }

        .page-item.active,
        .page-item:first-child,
        .page-item:last-child,
        .page-item.disabled {
            display: block;
        }

        /* Mostrar solo página actual y navegación en móvil */
        .page-item.active ~ .page-item:not(:last-child):not(.disabled),
        .page-item.active ~ .page-item.disabled:not(:last-child) {
            display: none;
        }
    }

    @media (max-width: 576px) {
        .pagination-modern {
            gap: 0.25rem;
            padding: 0.375rem;
        }

        .page-link-modern {
            padding: 0.375rem 0.5rem;
            min-width: 2rem;
            height: 2rem;
            font-size: 0.75rem;
        }
    }

    /* Accesibilidad - Focus visible */
    .page-link-modern:focus {
        outline: 3px solid rgba(26, 71, 137, 0.3);
        outline-offset: 2px;
    }

    /* Efecto de brillo en hover para botones de navegación */
    .page-item:first-child .page-link-modern::after,
    .page-item:last-child .page-link-modern::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        transition: left 0.5s;
    }

    .page-item:first-child .page-link-modern:hover::after,
    .page-item:last-child .page-link-modern:hover::after {
        left: 100%;
    }

    /* Modo oscuro (opcional) */
    @media (prefers-color-scheme: dark) {
        .page-link-modern {
            background-color: #2d3748;
            border-color: rgba(99, 190, 207, 0.2);
            color: var(--color-accent1);
        }

        .page-link-modern:hover {
            background: var(--gradient-primary);
            color: #fff;
        }

        .page-item.disabled .page-link-modern {
            background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
        }

        .pagination-modern {
            background: linear-gradient(135deg, rgba(26, 71, 137, 0.08) 0%, rgba(99, 190, 207, 0.08) 100%);
        }
    }
    </style>
@endif
