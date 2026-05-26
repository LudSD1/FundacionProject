
@extends('layoutlanding')

@section('main')




<section class="search-hero-section">

    <div class="hero-blob-left"></div>
    <div class="hero-blob-right"></div>
    <div class="hero-grid"></div>

    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8">

                {{-- Chip superior --}}
                <div class="text-center mb-3">
                    <span class="hero-chip">
                        <i class="bi bi-lightning-charge-fill me-1"></i>
                        +{{ $cursos->total() }} cursos disponibles
                    </span>
                </div>

                {{-- Título --}}
                <h1 class="search-hero-title text-center">
                    Encuentra tu próximo<br>
                    <span class="hero-title-highlight">curso o evento</span>
                </h1>

                <p class="search-hero-subtitle text-center">
                    Explora nuestra biblioteca de cursos y eventos educativos
                    diseñados para impulsar tu carrera
                </p>

                <form method="GET"
                      action="{{ route('lista.cursos.congresos') }}"
                      class="search-hero-form"
                      id="heroSearchForm">

                    {{-- Campos ocultos para preservar filtros al buscar --}}
                    @if(request('type'))
                        <input type="hidden" name="type" value="{{ request('type') }}">
                    @endif
                    @if(request('categoria'))
                        <input type="hidden" name="categoria" value="{{ request('categoria') }}">
                    @endif
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                    @if(request('formato'))
                        <input type="hidden" name="formato" value="{{ request('formato') }}">
                    @endif
                    @if(request('nivel'))
                        <input type="hidden" name="nivel" value="{{ request('nivel') }}">
                    @endif
                    @if(request('precio'))
                        <input type="hidden" name="precio" value="{{ request('precio') }}">
                    @endif
                    @if(request('mes'))
                        <input type="hidden" name="mes" value="{{ request('mes') }}">
                    @endif

                    <div class="search-input-wrapper">
                        <div class="search-icon-side">
                            <i class="bi bi-search"></i>
                        </div>
                        <input type="text"
                               name="search"
                               id="heroSearchInput"
                               class="search-input-main"
                               placeholder="Buscar cursos, eventos o temas…"
                               value="{{ request('search') }}"
                               autocomplete="off">
                        @if(request('search'))
                            <button type="button"
                                    class="search-clear-btn"
                                    id="heroClearBtn"
                                    aria-label="Limpiar">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        @endif
                        <button type="submit" class="search-btn-main">
                            Buscar
                        </button>
                    </div>

                    {{-- ── Pills de filtro rápido ─────────────── --}}
                    <div class="hero-filter-pills">

                        {{-- Tipo --}}
                        <div class="hero-pill-group">
                            <span class="hero-pill-label">
                                <i class="bi bi-tag"></i> Tipo:
                            </span>
                            <a href="{{ request()->fullUrlWithQuery(['type' => null]) }}"
                               class="hero-pill {{ !request('type') ? 'active' : '' }}">
                                Todos
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['type' => 'curso']) }}"
                               class="hero-pill {{ request('type') === 'curso' ? 'active' : '' }}">
                                <i class="bi bi-book me-1"></i>Cursos
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['type' => 'congreso']) }}"
                               class="hero-pill {{ request('type') === 'congreso' ? 'active' : '' }}">
                                <i class="bi bi-calendar-event me-1"></i>Eventos
                            </a>
                        </div>

                        {{-- Separador --}}
                        <div class="hero-pill-divider"></div>

                        {{-- Categoría (dropdown custom) --}}
                        <div class="hero-pill-group">
                            <span class="hero-pill-label">
                                <i class="bi bi-collection"></i> Categoría:
                            </span>
                            <div class="hero-pill-dropdown" id="heroCatDrop">
                                <button class="hero-pill hero-pill-drop-btn {{ request('categoria') ? 'active' : '' }}"
                                        type="button">
                                    @php $selCat = $categorias->firstWhere('id', request('categoria')); @endphp
                                    {{ $selCat ? $selCat->name : 'Todas' }}
                                    <i class="bi bi-chevron-down ms-1 drop-chevron"></i>
                                </button>
                                <ul class="hero-pill-drop-menu">
                                    <li>
                                        <a href="{{ request()->fullUrlWithQuery(['categoria' => null]) }}"
                                           class="hero-pill-drop-item {{ !request('categoria') ? 'active' : '' }}">
                                            Todas las categorías
                                        </a>
                                    </li>
                                    @foreach ($categorias as $cat)
                                        <li>
                                            <a href="{{ request()->fullUrlWithQuery(['categoria' => $cat->id]) }}"
                                               class="hero-pill-drop-item {{ request('categoria') == $cat->id ? 'active' : '' }}">
                                                {{ $cat->name }}
                                                <span class="pill-drop-count">{{ $cat->cursos_count }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                    </div>

                </form>

                {{-- ── Stats ─────────────────────────────────── --}}
                {{-- <div class="hero-stats">
                    <div class="hero-stat">
                        <i class="bi bi-mortarboard-fill"></i>
                        <span><strong>+200</strong> Docentes</span>
                    </div>
                    <div class="hero-stat-divider"></div>
                    <div class="hero-stat">
                        <i class="bi bi-people-fill"></i>
                        <span><strong>+5.000</strong> Estudiantes</span>
                    </div>
                    <div class="hero-stat-divider"></div>
                    <div class="hero-stat">
                        <i class="bi bi-patch-check-fill"></i>
                        <span><strong>100%</strong> Certificados</span>
                    </div>
                </div> --}}

            </div>
        </div>
    </div>
</section>

<div id="courses-results-anchor"></div>

<div class="courses-listing-container">
    <div class="container">
        <div class="row g-4">

            <div class="col-lg-3">
                <aside class="filters-sidebar">

                    {{-- Controles de vista + conteo --}}
                    <div class="view-controls-wrapper">
                        <div class="view-toggle-buttons">
                            <div class="view-btn-group" role="group" aria-label="Cambiar vista">
                                <button type="button"
                                        class="view-btn active"
                                        data-view="grid"
                                        aria-pressed="true"
                                        title="Vista en cuadrícula">
                                    <i class="bi bi-grid-3x3-gap-fill"></i>
                                </button>
                                <button type="button"
                                        class="view-btn"
                                        data-view="list"
                                        aria-pressed="false"
                                        title="Vista en lista">
                                    <i class="bi bi-list-ul"></i>
                                </button>
                            </div>
                        </div>
                        <span class="results-count">
                            {{ $cursos->count() }} de {{ $cursos->total() }} resultados
                        </span>
                    </div>

                    {{-- Filtros --}}
                    <div class="card filters-card">
                        <div class="card-header filters-header">
                            <h5 class="filters-title text-white mb-0">
                                <i class="bi bi-funnel me-2"></i>Filtros
                            </h5>
                        </div>
                        <div class="card-body filters-body">

                            {{-- Filtro por Tipo --}}
                            <div class="filter-group mb-3">
                                <h6 class="filter-group-title">
                                    <i class="bi bi-tag me-1"></i> Tipo
                                </h6>
                                <div class="filter-options">
                                    <a href="{{ request()->fullUrlWithQuery(['type' => null]) }}"
                                       class="filter-option {{ !request('type') ? 'active' : '' }}">
                                        <i class="bi bi-grid me-1"></i> Todos
                                    </a>
                                    <a href="{{ request()->fullUrlWithQuery(['type' => 'curso']) }}"
                                       class="filter-option {{ request('type') === 'curso' ? 'active' : '' }}">
                                        <i class="bi bi-book me-1"></i> Cursos
                                    </a>
                                    <a href="{{ request()->fullUrlWithQuery(['type' => 'congreso']) }}"
                                       class="filter-option {{ request('type') === 'congreso' ? 'active' : '' }}">
                                        <i class="bi bi-calendar-event me-1"></i> Eventos
                                    </a>
                                </div>
                            </div>

                            <hr class="filter-divider">

                            {{-- Filtro por Categoría --}}
                            <div class="filter-group mb-3">
                                <h6 class="filter-group-title">
                                    <i class="bi bi-collection me-1"></i> Categorías
                                </h6>
                                <div class="filter-options">
                                    <a href="{{ request()->fullUrlWithQuery(['categoria' => null]) }}"
                                       class="filter-option {{ !request('categoria') ? 'active' : '' }}">
                                        Todas las categorías
                                    </a>
                                    @foreach ($categorias as $cat)
                                        <!-- DEBUG: Category ID {{ $cat->id }} Name {{ $cat->name }} Count {{ $cat->cursos_count }} -->
                                        <a href="{{ request()->fullUrlWithQuery(['categoria' => $cat->id]) }}"
                                           class="filter-option {{ request('categoria') == $cat->id ? 'active' : '' }}">
                                            {{ $cat->name }}
                                            <span class="filter-count">{{ $cat->cursos_count }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            <hr class="filter-divider">

                            {{-- Filtro por Nivel --}}
                            <div class="filter-group mb-3">
                                <h6 class="filter-group-title">
                                    <i class="bi bi-bar-chart me-1"></i> Nivel
                                </h6>
                                <div class="filter-options">
                                    <a href="{{ request()->fullUrlWithQuery(['nivel' => null]) }}"
                                       class="filter-option {{ !request('nivel') ? 'active' : '' }}">
                                        Todos los niveles
                                    </a>
                                    @foreach(['Principiante', 'Intermedio', 'Avanzado'] as $nivel)
                                        <a href="{{ request()->fullUrlWithQuery(['nivel' => $nivel]) }}"
                                           class="filter-option {{ request('nivel') === $nivel ? 'active' : '' }}">
                                            {{ $nivel }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            <hr class="filter-divider">

                            {{-- Filtro por Formato --}}
                            <div class="filter-group mb-3">
                                <h6 class="filter-group-title">
                                    <i class="bi bi-laptop me-1"></i> Formato
                                </h6>
                                <div class="filter-options">
                                    <a href="{{ request()->fullUrlWithQuery(['formato' => null]) }}"
                                       class="filter-option {{ !request('formato') ? 'active' : '' }}">
                                        Todos los formatos
                                    </a>
                                    @foreach(['Presencial', 'Virtual', 'Online'] as $formato)
                                        <a href="{{ request()->fullUrlWithQuery(['formato' => $formato]) }}"
                                           class="filter-option {{ request('formato') === $formato ? 'active' : '' }}">
                                            {{ $formato }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            <hr class="filter-divider">

                            {{-- Filtro por Precio --}}
                            <div class="filter-group mb-3">
                                <h6 class="filter-group-title">
                                    <i class="bi bi-cash-stack me-1"></i> Inversión
                                </h6>
                                <div class="filter-options">
                                    <a href="{{ request()->fullUrlWithQuery(['precio' => null]) }}"
                                       class="filter-option {{ !request('precio') ? 'active' : '' }}">
                                        Todos
                                    </a>
                                    <a href="{{ request()->fullUrlWithQuery(['precio' => 'gratis']) }}"
                                       class="filter-option {{ request('precio') === 'gratis' ? 'active' : '' }}">
                                        Gratuitos
                                    </a>
                                    <a href="{{ request()->fullUrlWithQuery(['precio' => 'pago']) }}"
                                       class="filter-option {{ request('precio') === 'pago' ? 'active' : '' }}">
                                        De pago
                                    </a>
                                </div>
                            </div>

                            <hr class="filter-divider">

                            {{-- Filtro por Mes --}}
                            <div class="filter-group mb-3">
                                <h6 class="filter-group-title">
                                    <i class="bi bi-calendar3 me-1"></i> Mes de inicio
                                </h6>
                                <div class="filter-options">
                                    <a href="{{ request()->fullUrlWithQuery(['mes' => null]) }}"
                                       class="filter-option {{ !request('mes') ? 'active' : '' }}">
                                        Cualquier mes
                                    </a>
                                    @php
                                        $meses = [
                                            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                                            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                                            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                                        ];
                                        // Solo mostrar meses que tengan sentido (actual y futuros del año) o todos?
                                        // Vamos a mostrar todos por ahora.
                                    @endphp
                                    <div class="row g-1">
                                        @foreach($meses as $num => $nombre)
                                            <div class="col-6">
                                                <a href="{{ request()->fullUrlWithQuery(['mes' => $num]) }}"
                                                   class="filter-option py-1 {{ request('mes') == $num ? 'active' : '' }}"
                                                   style="font-size: 0.85rem;">
                                                    {{ $nombre }}
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <hr class="filter-divider">

                            {{-- Ordenar por --}}
                            <div class="filter-group mb-3">
                                <h6 class="filter-group-title">
                                    <i class="bi bi-sort-down me-1"></i> Ordenar por
                                </h6>
                                <div class="filter-options">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => null]) }}"
                                       class="filter-option {{ !request('sort') ? 'active' : '' }}">
                                        Mejor valorados
                                    </a>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'date_desc']) }}"
                                       class="filter-option {{ request('sort') === 'date_desc' ? 'active' : '' }}">
                                        Más recientes
                                    </a>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}"
                                       class="filter-option {{ request('sort') === 'price_asc' ? 'active' : '' }}">
                                        Precio: menor a mayor
                                    </a>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}"
                                       class="filter-option {{ request('sort') === 'price_desc' ? 'active' : '' }}">
                                        Precio: mayor a menor
                                    </a>
                                </div>
                            </div>

                            <a href="{{ route('lista.cursos.congresos') }}" class="clear-filters-btn">
                                <i class="bi bi-x-circle me-2"></i>Limpiar filtros
                            </a>

                        </div>
                    </div>

                    {{-- Recomendaciones para el usuario --}}
                    @include('partials._recomendaciones_widget')

                </aside>
            </div>

            <div class="col-lg-9" id="courses-results">

                <div class="row g-4" id="gridView">
                    @forelse ($cursos as $curso)
                        @php
                            $imagen  = $curso->imagen
                                ? asset('storage/' . $curso->imagen)
                                : asset('assets/img/bg2.png');
                            $docente = $curso->docente;
                            $avatar  = $docente?->profile_photo_path
                                ? asset('storage/' . $docente->profile_photo_path)
                                : asset('assets/img/user.png');
                            $esCurso = $curso->tipo === 'Curso';
                        @endphp
                        <div class="col-md-6 col-xl-4">
                            @include('partials._course-card-grid', compact('curso','imagen','docente','avatar','esCurso'))
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="courses-empty-state">
                                <i class="bi bi-search empty-state-icon"></i>
                                <h4 class="empty-state-title">No se encontraron cursos</h4>
                                <p class="empty-state-text">Intenta ajustar tus filtros de búsqueda</p>
                                <a href="{{ route('lista.cursos.congresos') }}" class="btn empty-state-btn">
                                    Ver todos los cursos
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>

                {{-- Vista Lista --}}
                <div class="d-none" id="listView">
                    @foreach ($cursos as $curso)
                        @php
                            $imagen  = $curso->imagen
                                ? asset('storage/' . $curso->imagen)
                                : asset('assets/img/bg2.png');
                            $docente = $curso->docente;
                            $avatar  = $docente?->profile_photo_path
                                ? asset('storage/' . $docente->profile_photo_path)
                                : asset('assets/img/user.png');
                        @endphp
                        @include('partials._course-card-list', compact('curso','imagen','docente','avatar'))
                    @endforeach
                </div>

            </div>

            {{-- Paginación --}}
            <div class="courses-pagination w-100">
                {{ $cursos->appends(request()->query())->links('custom-pagination') }}
            </div>

        </div>
    </div>
</div>



@once
<template id="tpl-course-card-grid">
</template>
@endonce


<script>
(() => {
    'use strict';

    /* ── Animar cards con stagger ───────────────────── */
    function animateCardsIn(container) {
        if (!container) return;
        const STEP      = 60;
        const MAX_DELAY = 400;
        container.querySelectorAll('.course-card, .course-card-list').forEach((card, i) => {
            card.classList.remove('animate-in');
            card.offsetHeight; // reflow
            card.style.transitionDelay = Math.min(i * STEP, MAX_DELAY) + 'ms';
            requestAnimationFrame(() => card.classList.add('animate-in'));
        });
    }

    /* ── Toggle grid / lista ────────────────────────── */
    function initViewToggle() {
        const gridBtn  = document.querySelector('[data-view="grid"]');
        const listBtn  = document.querySelector('[data-view="list"]');
        const gridView = document.getElementById('gridView');
        const listView = document.getElementById('listView');
        if (!gridBtn || !gridView) return;

        function switchTo(show, hide, activeBtn, inactiveBtn) {
            activeBtn.classList.add('active');
            activeBtn.setAttribute('aria-pressed', 'true');
            inactiveBtn.classList.remove('active');
            inactiveBtn.setAttribute('aria-pressed', 'false');
            show.classList.remove('d-none');
            hide.classList.add('d-none');
            animateCardsIn(show);
        }

        gridBtn.addEventListener('click', () => switchTo(gridView, listView, gridBtn, listBtn));
        listBtn.addEventListener('click', () => switchTo(listView, gridView, listBtn, gridBtn));

        // Animación inicial
        animateCardsIn(gridView);
    }

    /* ── Sort dinámico ──────────────────────────────── */
    function initSortSelect() {
        document.querySelectorAll('.sort-select').forEach(select => {
            select.addEventListener('change', () => {
                const url = new URL(window.location.href);
                select.value
                    ? url.searchParams.set('sort', select.value)
                    : url.searchParams.delete('sort');
                window.location.href = url.toString();
            });
        });
    }

    /* ── Filtros hero: submit al cambiar select ─────── */
    function initHeroFilters() {
        ['heroType', 'heroCategoria'].forEach(id => {
            document.getElementById(id)?.addEventListener('change', () => {
                document.getElementById('heroSearchForm').submit();
            });
        });
    }

    /* ── Limpiar búsqueda ───────────────────────────── */
    function initClearSearch() {
        document.getElementById('heroClearBtn')?.addEventListener('click', () => {
            const input = document.getElementById('heroSearchInput');
            if (input) {
                input.value = '';
                document.getElementById('heroSearchForm').submit();
            }
        });
    }

    /* ── Dropdown de categoría (toggle open) ──────── */
    function initCategoryDropdown() {
        const drop = document.getElementById('heroCatDrop');
        if (!drop) return;

        const btn = drop.querySelector('.hero-pill-drop-btn');
        if (!btn) return;

        // Toggle al hacer clic en el botón
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            drop.classList.toggle('open');
        });

        // Cerrar al hacer clic fuera
        document.addEventListener('click', (e) => {
            if (!drop.contains(e.target)) {
                drop.classList.remove('open');
            }
        });

        // Cerrar con Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                drop.classList.remove('open');
            }
        });
    }

    /* ── Init ───────────────────────────────────────── */
    document.addEventListener('DOMContentLoaded', () => {
        initViewToggle();
        initSortSelect();
        initHeroFilters();
        initClearSearch();
        initCategoryDropdown();

        // ── Auto-scroll a resultados si hay búsqueda o filtros ──
        const urlParams = new URLSearchParams(window.location.search);
        const hasFilters = urlParams.has('search') || urlParams.has('type') || urlParams.has('categoria') || urlParams.has('sort');

        if (hasFilters) {
            const resultsSection = document.getElementById('courses-results');
            if (resultsSection) {
                // Si hay filtros, hacemos scroll al ancla que está justo antes de la sección de cursos
                const anchor = document.getElementById('courses-results-anchor');
                if (anchor) {
                    anchor.scrollIntoView({ behavior: 'smooth' });
                }
            }
        }
    });
})();
</script>

@endsection
