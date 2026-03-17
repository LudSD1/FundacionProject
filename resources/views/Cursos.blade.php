@section('titulo')
    {{ $cursos->nombreCurso }}
@endsection

@section('contentup')
    @include('partials.cursos.course_hero')
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

<div class="cc-wrap">
    <div class="container-fluid cc-layout">

        <aside class="cc-sidebar" id="ccSidebar">

            {{-- Header sidebar --}}
            <div class="cc-sb-header">
                <div class="cc-sb-icon"><i class="bi bi-journal-text"></i></div>
                <span class="cc-sb-title">Contenido del Curso</span>
                {{-- Botón cerrar en mobile --}}
                <button class="cc-sb-close d-lg-none" id="ccSidebarClose">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            {{-- FIX 7: scroll en sidebar --}}
            <div class="cc-sb-scroll">

                {{-- Temario general --}}
                <a href="#tab-actividades" class="cc-sb-link cc-sb-link--active"
                   data-bs-toggle="tab" role="tab">
                    <i class="bi bi-list-ul"></i>
                    <span>Temario General</span>
                </a>

                {{-- Temas y subtemas --}}
                @forelse($temas as $tema)
                @php
                    $estaDesbloqueado = auth()->user()->hasRole('Docente') ||
                        (auth()->user()->hasRole('Estudiante') && isset($inscritos2) && $tema->estaDesbloqueado($inscritos2->id));
                @endphp

                <div class="cc-tema-group">
                    <button class="cc-tema-toggle"
                            data-bs-toggle="collapse"
                            data-bs-target="#sb-tema-{{ $tema->id }}"
                            aria-expanded="false">
                        <div class="cc-tema-toggle-left">
                            <i class="bi bi-folder2 cc-tema-icon"></i>
                            <span>{{ $tema->titulo_tema }}</span>
                        </div>
                        <i class="bi bi-chevron-down cc-tema-chevron"></i>
                    </button>

                    <div class="collapse" id="sb-tema-{{ $tema->id }}">
                        @forelse($tema->subtemas as $subtema)
                        @php
                            $desbloqueado = auth()->user()->hasRole('Docente') ||
                                (auth()->user()->hasRole('Estudiante') && isset($inscritos2) && $subtema->estaDesbloqueado($inscritos2->id));
                        @endphp
                        @if($desbloqueado)
                            <a href="#subtema-{{ $subtema->id }}" class="cc-sb-link cc-sb-link--sub">
                                <i class="bi bi-file-text"></i>
                                <span>{{ $subtema->titulo_subtema }}</span>
                            </a>
                        @else
                            <span class="cc-sb-link cc-sb-link--sub cc-sb-link--locked">
                                <i class="bi bi-lock-fill"></i>
                                <span>{{ $subtema->titulo_subtema }}</span>
                            </span>
                        @endif
                        @empty
                            <span class="cc-sb-link cc-sb-link--sub cc-sb-link--empty">
                                <i class="bi bi-dash-circle"></i>
                                <span>Sin subtemas</span>
                            </span>
                        @endforelse
                    </div>
                </div>
                @empty
                    <span class="cc-sb-link cc-sb-link--empty">
                        <i class="bi bi-exclamation-triangle"></i>
                        <span>No hay temas disponibles</span>
                    </span>
                @endforelse

                {{-- ── Próximas actividades (solo cursos) ── --}}
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

                <div class="cc-sb-section">
                    <div class="cc-sb-section-header">
                        <i class="bi bi-calendar-check-fill"></i>
                        Próximas Actividades
                    </div>

                    @if($proximasActividades->count() > 0)
                        @foreach($proximasActividades as $actividad)
                        @php
                            $fecha         = \Carbon\Carbon::parse($actividad['fecha']);
                            $diasRestantes = now()->diffInDays($fecha, false);
                            $esUrgente     = $diasRestantes <= 2;
                        @endphp
                        <a href="#subtema-{{ $actividad['subtema_id'] }}"
                           class="cc-activity {{ $esUrgente ? 'cc-activity--urgent' : '' }}">
                            <div class="cc-activity-icon">
                                <i class="bi bi-clipboard-check"></i>
                            </div>
                            <div class="cc-activity-info">
                                <div class="cc-activity-title">{{ Str::limit($actividad['titulo'], 28) }}</div>
                                <div class="cc-activity-date">
                                    <i class="bi bi-clock me-1"></i>{{ $fecha->format('d M') }}
                                    @if($esUrgente)
                                        <span class="cc-urgent-badge">Urgente</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                        @endforeach
                    @else
                        <div class="cc-sb-empty">
                            <i class="bi bi-calendar-x"></i>
                            <span>Sin actividades próximas</span>
                        </div>
                    @endif
                </div>
                @endif

                {{-- ── Certificado (solo estudiantes) ── --}}
                @if(auth()->user()->hasRole('Estudiante') && $cursos->tipo == 'curso')
                    @if($puedeObtenerCertificado)
                    <div class="cc-cert-card cc-cert-card--ready">
                        <div class="cc-cert-icon"><i class="bi bi-patch-check-fill"></i></div>
                        <div class="cc-cert-title">¡Felicitaciones!</div>
                        <div class="cc-cert-sub">Has completado el curso</div>
                        <button type="button" class="cc-btn cc-btn-cert"
                                data-bs-toggle="modal" data-bs-target="#certificadoModal">
                            <i class="bi bi-download me-2"></i>Obtener Certificado
                        </button>
                    </div>
                    @elseif($cursoCompletado && !$certificadosActivos)
                    <div class="cc-cert-card cc-cert-card--pending">
                        <div class="cc-cert-icon"><i class="bi bi-hourglass-split"></i></div>
                        <div class="cc-cert-title">Curso Completado</div>
                        <div class="cc-cert-sub">Los certificados estarán disponibles pronto</div>
                    </div>
                    @endif
                @endif

            </div>{{-- /cc-sb-scroll --}}
        </aside>
        <main class="cc-main">

            <button class="cc-sb-open d-lg-none" id="ccSidebarOpen">
                <i class="bi bi-layout-sidebar"></i>
                <span>Contenido</span>
            </button>

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
                <div class="cc-progress-complete">
                    <i class="bi bi-check-circle-fill me-1"></i> ¡Curso completado!
                </div>
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
                        <div class="tab-pane fade" id="tab-expositores" role="tabpanel">
                            <div class="cc-tab-header">
                                <div>
                                    <h4 class="cc-tab-title">
                                        <i class="bi bi-mic-fill me-2"></i>Expositores Asignados
                                    </h4>
                                    <p class="cc-tab-sub">Profesionales que impartirán el congreso</p>
                                </div>
                                @if($esDocenteOAdmin)
                                <button class="cc-btn cc-btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalExpositores">
                                    <i class="bi bi-person-plus-fill me-2"></i>Asignar Expositores
                                </button>
                                @endif
                            </div>

                            <div class="row g-3">
                                @forelse($cursos->expositores as $expositor)
                                @php
                                    $imgExp = ($expositor->imagen && file_exists(public_path('storage/'.$expositor->imagen)))
                                        ? asset('storage/'.$expositor->imagen)
                                        : asset('assets2/img/talker.png');
                                @endphp
                                <div class="col-md-6">
                                    <div class="cc-expositor-card">
                                        <img src="{{ $imgExp }}"
                                             class="cc-expositor-img"
                                             alt="{{ $expositor->nombre }}">
                                        <div class="cc-expositor-body">
                                            <h5 class="cc-expositor-name">{{ $expositor->nombre }}</h5>
                                            <div class="cc-expositor-meta">
                                                <span class="cc-expositor-label">Cargo</span>
                                                <span>{{ $expositor->pivot->cargo ?? 'No especificado' }}</span>
                                            </div>
                                            <div class="cc-expositor-meta">
                                                <span class="cc-expositor-label">Tema</span>
                                                <span>{{ $expositor->pivot->tema ?? 'No especificado' }}</span>
                                            </div>
                                            <div class="cc-expositor-footer">
                                                <span class="cc-expositor-orden">
                                                    <i class="bi bi-hash"></i>{{ $expositor->pivot->orden ?? '-' }}
                                                </span>
                                                @if($esDocenteOAdmin)
                                                <form action="{{ route('cursos.quitarExpositor', [$cursos->id, $expositor->id]) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('¿Deseas quitar este expositor?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="cc-btn cc-btn-danger-sm">
                                                        <i class="bi bi-person-x-fill me-1"></i>Quitar
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12">
                                    <div class="cc-empty-state">
                                        <i class="bi bi-mic-mute"></i>
                                        <h5>No hay expositores asignados</h5>
                                        <p>Asigna expositores para comenzar con el congreso</p>
                                    </div>
                                </div>
                                @endforelse
                            </div>
                        </div>
                        @endif

                        @include('partials.cursos.foros_tab')
                        @include('partials.cursos.recursos_tab')

                    </div>
                </div>
            </div>
        </main>

    </div>
</div>


@if($cursos->tipo == 'congreso' && $esDocenteOAdmin)
<div class="modal fade" id="modalExpositores" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <form method="POST" action="{{ route('cursos.asignarExpositores', $cursos->id) }}">
            @csrf
            <div class="modal-content cc-modal">
                <div class="cc-modal-header">
                    <div class="cc-modal-icon"><i class="bi bi-person-plus-fill"></i></div>
                    <div>
                        <h5 class="cc-modal-title">Asignar Expositores</h5>
                        <small>Al congreso: {{ $cursos->nombreCurso }}</small>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="cc-modal-search">
                        <i class="bi bi-search cc-modal-search-icon"></i>
                        <input type="text" id="buscadorExpositores"
                               class="cc-modal-search-input"
                               placeholder="Buscar expositor por nombre...">
                    </div>
                    <div class="cc-exp-list">
                        {{-- FIX 5: usar $expositor->id como key en lugar de $loop->index --}}
                        @foreach($expositores as $expositor)
                        @php
                            $imgExp = ($expositor->imagen && file_exists(public_path('storage/'.$expositor->imagen)))
                                ? asset('storage/'.$expositor->imagen)
                                : asset('assets2/img/talker.png');
                        @endphp
                        <div class="cc-exp-item" data-nombre="{{ strtolower($expositor->nombre) }}">
                            <div class="cc-exp-item-head">
                                <img src="{{ $imgExp }}"
                                     class="cc-exp-avatar"
                                     alt="{{ $expositor->nombre }}">
                                <div>
                                    <strong class="d-block">{{ $expositor->nombre }}</strong>
                                    <small class="text-muted">Expositor disponible</small>
                                </div>
                                <div class="form-check ms-auto">
                                    <input type="checkbox"
                                           class="form-check-input cc-exp-check"
                                           name="expositoresSeleccionados[]"
                                           value="{{ $expositor->id }}"
                                           id="exp-check-{{ $expositor->id }}">
                                    <label class="form-check-label small" for="exp-check-{{ $expositor->id }}">
                                        Seleccionar
                                    </label>
                                </div>
                            </div>
                            {{-- FIX 5: key = expositor->id, no loop->index --}}
                            <input type="hidden"
                                   name="expositores[{{ $expositor->id }}][id]"
                                   value="{{ $expositor->id }}">
                            <div class="cc-exp-fields">
                                <input type="text"
                                       class="cc-input"
                                       name="expositores[{{ $expositor->id }}][cargo]"
                                       placeholder="Cargo del expositor">
                                <input type="text"
                                       class="cc-input"
                                       name="expositores[{{ $expositor->id }}][tema]"
                                       placeholder="Tema a exponer">
                                <input type="number"
                                       class="cc-input cc-input--sm"
                                       name="expositores[{{ $expositor->id }}][orden]"
                                       placeholder="Orden" min="1">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="cc-modal-footer">
                    <button type="button" class="cc-btn cc-btn-outline" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="cc-btn cc-btn-success">
                        <i class="bi bi-floppy-fill me-2"></i>Guardar Asignaciones
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

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

{{-- Overlay sidebar mobile --}}
<div class="cc-sb-overlay d-lg-none" id="ccSidebarOverlay"></div>

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

        const sidebar  = document.getElementById('ccSidebar');
        const overlay  = document.getElementById('ccSidebarOverlay');
        const btnOpen  = document.getElementById('ccSidebarOpen');
        const btnClose = document.getElementById('ccSidebarClose');

        function openSidebar()  {
            sidebar?.classList.add('open');
            overlay?.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            sidebar?.classList.remove('open');
            overlay?.classList.remove('active');
            document.body.style.overflow = '';
        }

        btnOpen?.addEventListener('click',  openSidebar);
        btnClose?.addEventListener('click', closeSidebar);
        overlay?.addEventListener('click',  closeSidebar);

        /* ── 3. FIX 8: sincronizar top del sidebar con la altura real del nav ── */
        function syncSidebarTop() {
            if (window.innerWidth <= 991) return; // mobile usa position:fixed
            const header  = document.getElementById('header');
            const authNav = document.getElementById('authNavbar');
            if (!sidebar || !header) return;
            const hH  = header.getBoundingClientRect().height;
            const anH = authNav ? authNav.getBoundingClientRect().height : 0;
            sidebar.style.top = (hH + anH + 16) + 'px'; // 16px de margen
            sidebar.style.maxHeight = `calc(100vh - ${hH + anH + 32}px)`;
        }
        syncSidebarTop();
        window.addEventListener('resize', syncSidebarTop);
        window.addEventListener('scroll', syncSidebarTop, { passive: true });

        /* ── 4. Chevron de temas colapso ── */
        document.querySelectorAll('.cc-tema-toggle').forEach(btn => {
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
        // Al hacer click en un link de subtema, expande su tema padre
        document.querySelectorAll('.cc-sb-link--sub').forEach(link => {
            link.addEventListener('click', function () {
                const collapse = this.closest('.collapse');
                if (collapse) {
                    const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapse);
                    bsCollapse.show();
                }
                if (window.innerWidth <= 991) closeSidebar();
            });
        });

    })();
    </script>

@include('layout')