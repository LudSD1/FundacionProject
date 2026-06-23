@section('titulo')
    {{ $cursos->nombreCurso }}
@endsection

@section('contentup')
    @include('partials.cursos.course_hero')
@endsection

@section('nav')
{{-- ── Contenido del Curso en Sidebar Global ── --}}
@if($esDocenteOAdmin ?? false || (auth()->user()->hasRole('Estudiante') && ($inscritos ?? false)))

@php
    $progreso = (auth()->user()->hasRole('Estudiante') && isset($inscritos2))
        ? $cursos->calcularProgreso($inscritos2->id)
        : 0;
    $certificadosActivos    = $cursos->estado === 'Certificado Disponible';
    $cursoCompletado        = $progreso >= 100;
    $puedeObtenerCertificado = $cursoCompletado && $certificadosActivos;
@endphp

<hr class="bg-white opacity-25 my-2">

{{-- Header de sección --}}
<div class="csb-header">
    <i class="bi bi-journal-text"></i>
    <span>Contenido del Curso</span>
</div>

{{-- Temario general --}}
<a href="{{ route('Curso', $cursos->codigoCurso) }}#tab-actividades" class="nav-link csb-link">
    <i class="bi bi-list-ul"></i>
    <span>Temario General</span>
</a>

{{-- Temas y subtemas --}}
@forelse($temas as $tema)
@php
    $estaDesbloqueado = auth()->user()->hasRole('Docente') ||
        (auth()->user()->hasRole('Estudiante') && isset($inscritos2) && $tema->estaDesbloqueado($inscritos2->id));
@endphp

<div class="csb-tema-group">
    <button class="csb-tema-toggle"
            data-bs-toggle="collapse"
            data-bs-target="#sb-tema-{{ $tema->id }}"
            aria-expanded="false">
        <div class="csb-tema-left">
            <i class="bi bi-folder2"></i>
            <span>{{ $tema->titulo_tema }}</span>
        </div>
        <i class="bi bi-chevron-down csb-chevron"></i>
    </button>

    <div class="collapse" id="sb-tema-{{ $tema->id }}">
        @forelse($tema->subtemas as $subtema)
        @php
            $desbloqueado = auth()->user()->hasRole('Docente') ||
                (auth()->user()->hasRole('Estudiante') && isset($inscritos2) && $subtema->estaDesbloqueado($inscritos2->id));
        @endphp
        @if($desbloqueado)
            <a href="{{ route('Curso', $cursos->codigoCurso) }}#subtema-{{ $subtema->id }}" class="nav-link csb-sub">
                <i class="bi bi-file-text"></i>
                <span>{{ $subtema->titulo_subtema }}</span>
            </a>
        @else
            <span class="nav-link csb-sub csb-locked">
                <i class="bi bi-lock-fill"></i>
                <span>{{ $subtema->titulo_subtema }}</span>
            </span>
        @endif
        @empty
            <span class="nav-link csb-sub csb-empty">
                <i class="bi bi-dash-circle"></i>
                <span>Sin subtemas</span>
            </span>
        @endforelse
    </div>
</div>
@empty
    <span class="nav-link csb-empty">
        <i class="bi bi-exclamation-triangle"></i>
        <span>No hay temas disponibles</span>
    </span>
@endforelse

{{-- Próximas actividades (solo cursos) --}}
@if($cursos->tipo == 'curso')
@php
    $actividades = collect();
    foreach ($temas as $tema) {
        foreach ($tema->subtemas as $subtema) {
            foreach ($subtema->actividades as $actividad) {
                if ($actividad->cuestionario && $actividad->cuestionario->fecha_limite) {
                    $actividades->push([
                        'tipo'       => 'cuestionario',
                        'titulo'     => $actividad->cuestionario->titulo,
                        'fecha'      => $actividad->cuestionario->fecha_limite,
                        'subtema_id' => $subtema->id,
                    ]);
                }
            }
        }
    }
    $proximasActividades = $actividades
        ->sortBy('fecha')
        ->filter(fn($a) => \Carbon\Carbon::parse($a['fecha'])->isFuture())
        ->take(5);
@endphp

<div class="csb-section">
    <div class="csb-section-header">
        <i class="bi bi-calendar-check-fill"></i>
        <span>Próximas Actividades</span>
    </div>

    @if($proximasActividades->count() > 0)
        @foreach($proximasActividades as $actividad)
        @php
            $fecha         = \Carbon\Carbon::parse($actividad['fecha']);
            $diasRestantes = now()->diffInDays($fecha, false);
            $esUrgente     = $diasRestantes <= 2;
        @endphp
        <a href="{{ route('Curso', $cursos->codigoCurso) }}#subtema-{{ $actividad['subtema_id'] }}"
           class="csb-activity {{ $esUrgente ? 'csb-activity--urgent' : '' }}">
            <i class="bi bi-clipboard-check"></i>
            <div class="csb-activity-info">
                <div class="csb-activity-title">{{ Str::limit($actividad['titulo'], 22) }}</div>
                <div class="csb-activity-date">
                    <i class="bi bi-clock me-1"></i>{{ $fecha->format('d M') }}
                    @if($esUrgente)
                        <span class="csb-urgent-badge">!</span>
                    @endif
                </div>
            </div>
        </a>
        @endforeach
    @else
        <div class="csb-empty-msg">
            <i class="bi bi-calendar-x"></i>
            <span>Sin actividades próximas</span>
        </div>
    @endif
</div>
@endif

{{-- Certificado (solo estudiantes) --}}
@if(auth()->user()->hasRole('Estudiante') && $cursos->tipo == 'curso')
    @if($puedeObtenerCertificado)
    <div class="csb-cert csb-cert--ready">
        <i class="bi bi-patch-check-fill csb-cert-icon"></i>
        <span>¡Certificado listo!</span>
        <button type="button" class="csb-cert-btn"
                data-bs-toggle="modal" data-bs-target="#certificadoModal">
            <i class="bi bi-download me-1"></i>Obtener
        </button>
    </div>
    @elseif($cursoCompletado && !$certificadosActivos)
    <div class="csb-cert csb-cert--pending">
        <i class="bi bi-hourglass-split csb-cert-icon"></i>
        <span>Certificados pronto</span>
    </div>
    @endif

    {{-- Progreso mini --}}
    <div class="csb-progress">
        <div class="csb-progress-header">
            <span>Progreso</span>
            <span class="csb-progress-pct">{{ $progreso }}%</span>
        </div>
        <div class="csb-progress-track">
            <div class="csb-progress-fill" style="width: {{ min($progreso, 100) }}%"></div>
        </div>
    </div>
@endif

@endif
@endsection

@section('content')

@if($esDocenteOAdmin || (auth()->user()->hasRole('Estudiante') && $inscritos))

{{-- FIX 4: calcularProgreso una sola vez --}}
@php
    $progreso = (auth()->user()->hasRole('Estudiante') && isset($inscritos2))
        ? $cursos->calcularProgreso($inscritos2->id)
        : 0;
    $certificadosActivos    = $cursos->estado === 'Certificado Disponible';
    $cursoCompletado        = $progreso >= 100;
    $puedeObtenerCertificado = $cursoCompletado && $certificadosActivos;
@endphp

@if(auth()->user()->hasRole('Estudiante') && $cursos->tipo == 'curso')
<div class="cc-progress-card">
    <div class="cc-progress-header">
        <div>
            <div class="cc-progress-label">
                <i class="bi bi-graph-up-arrow me-2"></i>Progreso del Curso
            </div>
            <div class="cc-progress-sub">Tu avance en el contenido</div>
        </div>
        <div class="cc-progress-pct
            {{ $progreso >= 100 ? 'cc-progress-pct--done' : ($progreso >= 50 ? 'cc-progress-pct--mid' : '') }}">
            {{ $progreso }}%
        </div>
    </div>
    <div class="cc-progress-track">
        <div class="cc-progress-fill" data-width="{{ $progreso }}"></div>
    </div>
    @if($progreso >= 100)
    <div class="cc-progress-complete mb-2">
        <i class="bi bi-check-circle-fill me-1"></i> ¡Curso completado!
    </div>
        @if($puedeObtenerCertificado)
            <div class="mt-2 text-center">
                <button type="button" class="btn w-100 fw-bold rounded-3 shadow-sm" style="background-color: #f59e0b; color: white;"
                        data-bs-toggle="modal" data-bs-target="#certificadoModal">
                    <i class="bi bi-patch-check-fill me-2"></i>Obtener mi Certificado
                </button>
            </div>
        @else
            <div class="mt-2 text-center">
                <small class="text-muted"><i class="bi bi-hourglass-split me-1"></i>A la espera de activación del certificado</small>
            </div>
        @endif
    @endif
</div>
@endif

<div class="cc-card">
    <div class="cc-tabs-wrap">
        <ul class="cc-tabs nav" id="courseTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="cc-tab nav-link active"
                        data-bs-toggle="tab"
                        data-bs-target="#tab-actividades"
                        type="button" role="tab">
                    <i class="bi bi-list-check me-2"></i>Temario
                </button>
            </li>
            @if($cursos->tipo == 'congreso')
            <li class="nav-item" role="presentation">
                <button class="cc-tab nav-link"
                        data-bs-toggle="tab"
                        data-bs-target="#tab-expositores"
                        type="button" role="tab">
                    <i class="bi bi-mic-fill me-2"></i>Expositores
                </button>
            </li>
            @endif
            <li class="nav-item" role="presentation">
                <button class="cc-tab nav-link"
                        data-bs-toggle="tab"
                        data-bs-target="#tab-foros"
                        type="button" role="tab">
                    <i class="bi bi-chat-dots-fill me-2"></i>Foros
                    <span class="cc-tab-badge">{{ isset($foros) ? $foros->count() : 0 }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="cc-tab nav-link"
                        data-bs-toggle="tab"
                        data-bs-target="#tab-recursos"
                        type="button" role="tab">
                    <i class="bi bi-folder2-open me-2"></i>Recursos
                    <span class="cc-tab-badge">{{ isset($recursos) ? $recursos->count() : 0 }}</span>
                </button>
            </li>
        </ul>
    </div>

    <div class="cc-card-body">
        <div class="tab-content" id="courseTabContent">

            @include('partials.cursos.temario_tab')

            {{-- Tab expositores (congresos) --}}
            @if($cursos->tipo == 'congreso')
            @include('partials.cursos.expositores_tab')
            @endif

            @include('partials.cursos.foros_tab')
            @include('partials.cursos.recursos_tab')

        </div>
    </div>
</div>




@include('partials.cursos.modals.agregar_tema')
@include('partials.cursos.modals.agregar_subtema')
@include('Docente.CrearForo')
@include('Docente.CrearRecursos')

@foreach($foros as $foro)
    @include('Docente.EditarForo')
@endforeach
@foreach($recursos as $recurso)
    @include('Docente.EditarRecursos')
@endforeach

@foreach($temas as $tema)
<div class="modal fade" id="modalEditarTema-{{ $tema->id }}"
     tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <form method="POST"
              action="{{ route('temas.update', encrypt($tema->id)) }}"
              enctype="multipart/form-data">
            @csrf
            <div class="modal-content cc-modal">
                <div class="cc-modal-header">
                    <div class="cc-modal-icon"><i class="bi bi-pencil-square"></i></div>
                    <div>
                        <h5 class="cc-modal-title">Editar Tema</h5>
                        <small>{{ $tema->titulo_tema }}</small>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="cc-field">
                                <label class="cc-label">Título del Tema <span class="cc-req">*</span></label>
                                <div class="cc-input-wrap">
                                    <i class="bi bi-type cc-input-icon"></i>
                                    <input type="text" class="cc-input cc-input-with-icon"
                                           name="titulo"
                                           value="{{ $tema->titulo_tema }}"
                                           placeholder="Título del tema" required>
                                </div>
                            </div>
                            <div class="cc-field">
                                <label class="cc-label">Descripción</label>
                                <textarea class="cc-input cc-textarea"
                                          name="descripcion"
                                          rows="4"
                                          placeholder="Describe el contenido del tema...">{{ $tema->descripcion }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="cc-field">
                                <label class="cc-label">Imagen del Tema</label>
                                @if($tema->imagen)
                                    <img src="{{ asset('storage/'.$tema->imagen) }}"
                                         class="cc-img-preview mb-2"
                                         alt="Imagen actual">
                                    <small class="text-muted d-block mb-2">Imagen actual</small>
                                @else
                                    <div class="cc-img-placeholder mb-2">
                                        <i class="bi bi-image"></i>
                                        <span>Sin imagen</span>
                                    </div>
                                @endif
                                <input type="file" class="cc-input"
                                       name="imagen"
                                       accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cc-modal-footer">
                    <button type="button" class="cc-btn cc-btn-outline" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="cc-btn cc-btn-primary">
                        <i class="bi bi-floppy-fill me-2"></i>Guardar Cambios
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach



@else
{{-- ── Acceso denegado ── --}}
<div class="cc-denied">
    <div class="cc-denied-card">
        <div class="cc-denied-icon"><i class="bi bi-lock-fill"></i></div>
        <h3 class="cc-denied-title">Acceso Denegado</h3>
        <p class="cc-denied-sub">No tienes permisos para acceder a este curso.</p>
        <a href="{{ route('Inicio') }}" class="cc-btn cc-btn-primary">
            <i class="bi bi-house-door-fill me-2"></i>Volver al Inicio
        </a>
    </div>
</div>
@endif

{{-- Errores de validación --}}
@if($errors->any())
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        icon: 'error',
        title: 'Errores de validación',
        html: `<div style="text-align:left;max-height:280px;overflow-y:auto;"><ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul></div>`,
        confirmButtonColor: '#145da0'
    });
});
</script>
@endif

@endsection


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    (function () {

        document.querySelectorAll('.cc-progress-fill').forEach(bar => {
            const w = bar.getAttribute('data-width') || '0';
            requestAnimationFrame(() => setTimeout(() => { bar.style.width = w + '%'; }, 80));
        });

        document.querySelectorAll('.csb-progress-fill').forEach(bar => {
            const w = bar.parentNode.previousElementSibling.querySelector('.csb-progress-pct').textContent.replace('%','');
            requestAnimationFrame(() => setTimeout(() => { bar.style.width = w + '%'; }, 80));
        });

        /* ── 4. Chevron de temas colapso ── */
        document.querySelectorAll('.csb-tema-toggle').forEach(btn => {
            const targetId = btn.getAttribute('data-bs-target');
            const panel    = document.querySelector(targetId);
            if (!panel) return;
            panel.addEventListener('show.bs.collapse',  () => btn.setAttribute('aria-expanded', 'true'));
            panel.addEventListener('hide.bs.collapse',  () => btn.setAttribute('aria-expanded', 'false'));
        });

        /* ── 5. Buscador expositores en modal ── */
        document.getElementById('buscadorExpositores')
            ?.addEventListener('input', function () {
                const q = this.value.toLowerCase().trim();
                document.querySelectorAll('.cc-exp-item').forEach(item => {
                    const nombre = item.getAttribute('data-nombre') || '';
                    item.style.display = nombre.includes(q) ? '' : 'none';
                });
            });

        /* ── 6. Tab activo → abrir tema correspondiente en sidebar ── */
        document.querySelectorAll('.csb-sub').forEach(link => {
            link.addEventListener('click', function () {
                const collapse = this.closest('.collapse');
                if (collapse) {
                    const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapse);
                    bsCollapse.show();
                }
                if (window.innerWidth <= 991) {
                    const sb = document.getElementById('sidebar');
                    if (sb && sb.classList.contains('show')) {
                        sb.classList.remove('show');
                        document.getElementById('sidebarOverlay')?.classList.remove('show');
                        document.body.style.overflow = '';
                    }
                }
            });
        });

    })();
    </script>

@include('layout')
