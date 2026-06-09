@extends('layout')

@section('titulo', 'Gestión de Cuestionario')

@section('content')
@php
    $totalPreguntas   = $cuestionario->preguntas->count();
    $totalPuntos      = $cuestionario->preguntas->sum('puntaje');
    $preguntasListas  = $cuestionario->preguntas->filter(fn($p) => $p->respuestas->count() > 0 && $p->respuestas->where('es_correcta', true)->count() > 0)->count();
    $totalRespuestas  = $cuestionario->preguntas->sum(fn($p) => $p->respuestas->count());
    $porcentaje       = $totalPreguntas > 0 ? round(($preguntasListas / $totalPreguntas) * 100) : 0;

    // Determinar estado general
    if ($totalPreguntas === 0) {
        $estadoGeneral = ['color' => 'warning', 'icon' => 'bi-exclamation-triangle-fill', 'texto' => 'Sin preguntas'];
    } elseif ($preguntasListas === $totalPreguntas) {
        $estadoGeneral = ['color' => 'success', 'icon' => 'bi-check-circle-fill', 'texto' => 'Cuestionario listo'];
    } else {
        $estadoGeneral = ['color' => 'warning', 'icon' => 'bi-clock-fill', 'texto' => 'En configuración'];
    }
@endphp

<div class="container my-4">
    <div class="tbl-card shadow-lg">
        <div class="tbl-card-hero">
            <div class="tbl-hero-left">
                <a href="{{ route('Curso', $cuestionario->actividad->subtema->tema->curso->codigoCurso) }}"
                   class="tbl-hero-btn tbl-hero-btn-glass prt-back-btn mb-2">
                    <i class="bi bi-arrow-left-circle-fill"></i> Volver al Curso
                </a>
                <div class="tbl-hero-eyebrow">
                    <i class="bi bi-patch-question-fill"></i> Evaluación Académica
                </div>
                <h2 class="tbl-hero-title">{{ $cuestionario->actividad->titulo }}</h2>
                <p class="tbl-hero-sub text-white-50">
                    Gestión de Cuestionario
                </p>
            </div>
            <div class="tbl-hero-controls text-end">
                <div class="ec-role-badge mb-2 d-inline-block">
                    <i class="bi bi-shield-check-fill me-1"></i> Docente
                </div>
                {{-- KPI Badges --}}
                <div class="d-flex gap-2 justify-content-end flex-wrap">
                    <div class="badge bg-white text-primary border p-2 rounded-3 shadow-sm">
                        <i class="bi bi-question-circle-fill me-1"></i> {{ $totalPreguntas }} Preguntas
                    </div>
                    <div class="badge bg-white text-info border p-2 rounded-3 shadow-sm">
                        <i class="bi bi-star-fill me-1"></i> {{ $totalPuntos }} Puntos
                    </div>
                    <div class="badge bg-white text-{{ $estadoGeneral['color'] }} border p-2 rounded-3 shadow-sm">
                        <i class="bi {{ $estadoGeneral['icon'] }} me-1"></i> {{ $estadoGeneral['texto'] }}
                    </div>
                </div>
                {{-- Mini progress --}}
                @if ($totalPreguntas > 0)
                    <div class="mt-2 d-flex align-items-center gap-2 justify-content-end">
                        <small class="text-white-50" style="font-size: .72rem;">
                            Respuestas: {{ $preguntasListas }}/{{ $totalPreguntas }}
                        </small>
                        <div style="width: 80px; height: 5px; background: rgba(255,255,255,.2); border-radius: 50px; overflow: hidden;">
                            <div style="width: {{ $porcentaje }}%; height: 100%; background: {{ $porcentaje === 100 ? '#16a34a' : '#ffa500' }}; border-radius: 50px; transition: width 0.6s ease;"></div>
                        </div> 
                    </div>
                @endif
            </div>
        </div>

        <div class="adm-tabs-header bg-light border-bottom p-0">
            <ul class="nav adm-tabs-nav" id="preguntasRespuestasTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="adm-tab active" id="preguntas-tab" data-bs-toggle="tab" data-bs-target="#preguntas"
                        type="button" role="tab" aria-controls="preguntas" aria-selected="true">
                        <i class="bi bi-list-task me-2"></i>Preguntas
                        <span class="badge bg-primary bg-opacity-10 text-primary ms-2 rounded-pill" style="font-size: .68rem;">{{ $totalPreguntas }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="adm-tab" id="respuestas-tab" data-bs-toggle="tab" data-bs-target="#respuestas"
                        type="button" role="tab" aria-controls="respuestas" aria-selected="false">
                        <i class="bi bi-reply-all-fill me-2"></i>Respuestas
                        @if ($totalPreguntas > 0)
                            <span class="badge {{ $preguntasListas === $totalPreguntas ? 'bg-success bg-opacity-10 text-success' : 'bg-warning bg-opacity-10 text-warning' }} ms-2 rounded-pill" style="font-size: .68rem;">
                                {{ $preguntasListas }}/{{ $totalPreguntas }}
                            </span>
                        @endif
                    </button>
                </li>
            </ul>

            
        </div>

        <div class="p-0">
            <div class="tab-content" id="preguntasRespuestasContent">
                <div class="tab-pane fade show active p-4 p-md-5" id="preguntas" role="tabpanel" aria-labelledby="preguntas-tab">
                    @include('partials.preguntas', ['preguntas' => $cuestionario->preguntas])
                </div>

                <div class="tab-pane fade p-4 p-md-5" id="respuestas" role="tabpanel" aria-labelledby="respuestas-tab">
                    @include('partials.respuestas', ['preguntas' => $cuestionario->preguntas])
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        /* ── Confirmación de eliminación ─────────────────── */
        document.querySelectorAll('.form-eliminar').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const self = this;
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esta acción!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => { if (result.isConfirmed) self.submit(); });
            });
        });

        /* ── Confirmación de restaurar ───────────────────── */
        document.querySelectorAll('.form-restaurar').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const self = this;
                Swal.fire({
                    title: '¿Restaurar pregunta?',
                    text: "¿Quieres habilitar esta pregunta nuevamente?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#145da0',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Sí, restaurar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => { if (result.isConfirmed) self.submit(); });
            });
        });

        /* ── Animación suave al cambiar de tab ──────────── */
        const tabButtons = document.querySelectorAll('.adm-tab');
        tabButtons.forEach(button => {
            button.addEventListener('show.bs.tab', () => {
                const target = document.querySelector(button.dataset.bsTarget);
                target.style.opacity = '0';
                target.style.transform = 'translateY(10px)';
                setTimeout(() => {
                    target.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                    target.style.opacity = '1';
                    target.style.transform = 'translateY(0)';
                }, 50);
            });
        });
    });

    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Éxito', text: "{{ session('success') }}" });
    @endif
    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Error', text: "{{ session('error') }}" });
    @endif
</script>
@endsection
