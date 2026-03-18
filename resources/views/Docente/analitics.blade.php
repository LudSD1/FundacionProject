
@extends('estudiante.index')

@section('content')



<div class="sm-wrap">
    <div class="container sm-container">
        <div class="sm-hero">
            <div class="sm-hero-overlay"></div>
            <div class="sm-hero-body">
                <div>
                    <div class="sm-hero-eyebrow">
                        <i class="bi bi-person-video3"></i> Panel Docente
                    </div>
                    <h2 class="sm-hero-title">Mis Cursos Asignados</h2>
                    <p class="sm-hero-sub">Gestiona y visualiza los cursos donde eres docente</p>
                </div>
                {{-- Totales rápidos --}}
                @php
                    $totalCursos = $cursos2->where('docente_id', auth()->user()->id)->count();
                @endphp
                <div class="sm-hero-count">
                    <span class="sm-hero-count-num">{{ $totalCursos }}</span>
                    <span class="sm-hero-count-lbl">
                        curso{{ $totalCursos !== 1 ? 's' : '' }} asignado{{ $totalCursos !== 1 ? 's' : '' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- ╔══════════════════════════════════════╗
             ║  GRID DE CURSOS                     ║
             ╚══════════════════════════════════════╝ --}}
        <div class="sm-grid">

            @forelse($cursos2 as $curso)
            @if(auth()->user()->id == $curso->docente_id)
            @php
                $est      = $curso->obtenerEstadisticasProgreso();
                $pct      = $est['porcentaje_total'] ?? 0;
                $activo   = strtolower($curso->estado ?? '') === 'activo';
                $fechaFin = $curso->fecha_fin
                    ? \Carbon\Carbon::parse($curso->fecha_fin)->format('d/m/Y')
                    : 'Sin fecha';

                // Color de progreso según porcentaje
                $pctColor = $pct >= 75 ? 'green' : ($pct >= 40 ? 'blue' : 'orange');
            @endphp

            <div class="sm-card" data-aos="fade-up">

                {{-- Badge estado --}}
                <div class="sm-card-status">
                    <span class="sm-badge {{ $activo ? 'sm-badge--green' : 'sm-badge--orange' }}">
                        <i class="bi bi-circle-fill sm-badge-dot"></i>
                        {{ ucfirst($curso->estado ?? 'En proceso') }}
                    </span>
                </div>

                {{-- Header de la card --}}
                <div class="sm-card-head">
                    <div class="sm-card-icon">
                        <i class="bi bi-journal-text"></i>
                    </div>
                    <div class="sm-card-head-info">
                        <h5 class="sm-card-title">{{ $curso->nombreCurso }}</h5>
                        <p class="sm-card-docente">
                            <i class="bi bi-person-circle me-1"></i>
                            {{ auth()->user()->name }} {{ auth()->user()->lastname1 }}
                        </p>
                    </div>
                </div>

                {{-- Stats 2 columnas --}}
                <div class="sm-card-stats">
                    <div class="sm-stat-box">
                        <div class="sm-stat-box-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div>
                            <div class="sm-stat-box-num">{{ $est['estudiantes_total'] }}</div>
                            <div class="sm-stat-box-lbl">Estudiantes</div>
                            <div class="sm-stat-box-sub">{{ $est['estudiantes_completados'] }} completados</div>
                        </div>
                    </div>
                    <div class="sm-stat-box">
                        <div class="sm-stat-box-icon sm-stat-box-icon--orange">
                            <i class="bi bi-calendar-event-fill"></i>
                        </div>
                        <div>
                            <div class="sm-stat-box-num">{{ $fechaFin }}</div>
                            <div class="sm-stat-box-lbl">Fecha límite</div>
                            <div class="sm-stat-box-sub">&nbsp;</div>
                        </div>
                    </div>
                </div>

                {{-- Estado de estudiantes: pills --}}
                <div class="sm-card-pills-wrap">
                    <span class="sm-card-pills-label">Estado de estudiantes</span>
                    <div class="sm-card-pills">
                        <span class="sm-pill sm-pill--green"
                              title="Completados"
                              data-bs-toggle="tooltip">
                            <i class="bi bi-check-circle-fill"></i>
                            {{ $est['estudiantes_completados'] }}
                        </span>
                        <span class="sm-pill sm-pill--blue"
                              title="En progreso"
                              data-bs-toggle="tooltip">
                            <i class="bi bi-arrow-repeat"></i>
                            {{ $est['estudiantes_en_progreso'] }}
                        </span>
                        <span class="sm-pill sm-pill--gray"
                              title="Sin iniciar"
                              data-bs-toggle="tooltip">
                            <i class="bi bi-hourglass"></i>
                            {{ $est['estudiantes_sin_iniciar'] }}
                        </span>
                    </div>
                </div>

                {{-- Barra de progreso --}}
                <div class="sm-progress-wrap">
                    <div class="sm-progress-top">
                        <span class="sm-progress-label">Progreso general</span>
                        <span class="sm-progress-pct sm-progress-pct--{{ $pctColor }}">{{ $pct }}%</span>
                    </div>
                    <div class="sm-progress-track">
                        <div class="sm-progress-fill sm-progress-fill--{{ $pctColor }}"
                             data-width="{{ $pct }}"></div>
                    </div>
                </div>

                {{-- CTA --}}
                <a href="{{ route('rfc', encrypt($curso->id)) }}"
                   class="cc-btn cc-btn-primary sm-card-btn">
                    <i class="bi bi-eye-fill me-2"></i>Ver Detalles
                </a>

            </div>
            @endif
            @empty

            {{-- Estado vacío --}}
            <div class="sm-empty">
                <div class="sm-empty-icon"><i class="bi bi-journal-x"></i></div>
                <h4 class="sm-empty-title">No hay cursos asignados</h4>
                <p class="sm-empty-sub">
                    Actualmente no tienes ningún curso asignado como docente.
                </p>
            </div>

            @endforelse

        </div>{{-- /sm-grid --}}
    </div>
</div>

@endsection


<script>
(function () {
    document.addEventListener('DOMContentLoaded', function () {

        /* ── 1. Animar barras de progreso ── */
        document.querySelectorAll('.sm-progress-fill').forEach(bar => {
            const w = bar.getAttribute('data-width') || '0';
            requestAnimationFrame(() =>
                setTimeout(() => { bar.style.width = w + '%'; }, 80)
            );
        });

        /* ── 2. Tooltips Bootstrap en pills ── */
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
            new bootstrap.Tooltip(el, { placement: 'top', trigger: 'hover' });
        });

    });
})();
</script>
