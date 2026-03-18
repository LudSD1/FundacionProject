
@extends('layoutlanding')

@section('main')


<section class="search-hero-section">

    {{-- Decoración de fondo --}}
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
                    <span class="hero-title-highlight">curso o congreso</span>
                </h1>

                <p class="search-hero-subtitle text-center">
                    Explora nuestra biblioteca de cursos y eventos educativos
                    diseñados para impulsar tu carrera
                </p>

                {{-- ── Buscador ──────────────────────────────── --}}
                <form method="GET"
                      action="{{ route('lista.cursos.congresos') }}"
                      class="search-hero-form"
                      id="heroSearchForm">

                    <div class="search-input-wrapper">
                        <div class="search-icon-side">
                            <i class="bi bi-search"></i>
                        </div>
                        <input type="text"
                               name="search"
                               id="heroSearchInput"
                               class="search-input-main"
                               placeholder="Buscar cursos, congresos o temas…"
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
                            <a href="{{ request()->fullUrlWithQuery(['type' => '']) }}"
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
                                        <a href="{{ request()->fullUrlWithQuery(['categoria' => '']) }}"
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
                <div class="hero-stats">
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
                </div>

            </div>
        </div>
    </div>
</section>

{{-- Ancla para scroll --}}
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

                            <form id="sidebarFilters"
                                  method="GET"
                                  action="{{ route('lista.cursos.congresos') }}">
                                @foreach (['type', 'sort', 'search', 'categoria'] as $param)
                                    <input type="hidden" name="{{ $param }}" value="{{ request($param) }}">
                                @endforeach
                                {{-- Aquí van tus partials de filtros cuando estén listos --}}
                            </form>

                            <a href="{{ route('lista.cursos.congresos') }}" class="clear-filters-btn">
                                <i class="bi bi-x-circle me-2"></i>Limpiar filtros
                            </a>

                        </div>
                    </div>

                    {{-- Newsletter --}}
                    <div class="card newsletter-card">
                        <div class="card-body newsletter-body">
                            <h5 class="newsletter-title">
                                <i class="bi bi-envelope-heart me-2"></i>¿Quieres recibir nuevos cursos?
                            </h5>
                            <p class="newsletter-text">
                                Suscríbete para recibir actualizaciones sobre nuevos cursos y ofertas especiales.
                            </p>
                            <div class="input-group">
                                <input type="email"
                                       class="form-control newsletter-input"
                                       placeholder="Tu correo electrónico"
                                       aria-label="Correo para suscripción">
                                <button class="btn newsletter-btn" type="button">
                                    Suscribirse
                                </button>
                            </div>
                        </div>
                    </div>

                </aside>
            </div>

            {{-- ── CURSOS ───────────────────────────────── --}}
            <div class="col-lg-9" id="courses-results">

                {{-- Vista Grid --}}
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


{{-- ═══════════════════════════════════════════════════════
     PARTIALS INLINE  (mueve a resources/views/partials/)
═══════════════════════════════════════════════════════ --}}

{{-- partial: _course-card-grid --}}
@once
<template id="tpl-course-card-grid">
    {{-- Referencia de estructura — el partial real está en partials/_course-card-grid.blade.php --}}
</template>
@endonce


{{-- ═══════════════════════════════════════════════════════
     SCRIPTS
═══════════════════════════════════════════════════════ --}}
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

    /* ── Init ───────────────────────────────────────── */
    document.addEventListener('DOMContentLoaded', () => {
        initViewToggle();
        initSortSelect();
        initHeroFilters();
        initClearSearch();

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
