<div class="tab-pane fade show active"
     id="tab-actividades"
     role="tabpanel"
     aria-labelledby="temario-tab">

    {{-- ── Header ── --}}
    <div class="tt-header">
        <div>
            <h3 class="tt-title">Contenido del Curso</h3>
            <p class="tt-sub">
                {{ $temas->count() }} tema{{ $temas->count() != 1 ? 's' : '' }}
                · {{ $temas->sum(fn($t) => $t->subtemas->count()) }} lecciones
            </p>
        </div>
        @if(auth()->user()->hasRole('Docente') && $cursos->docente_id == auth()->user()->id)
            <button class="cc-btn cc-btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#modalTema">
                <i class="bi bi-plus-circle-fill me-2"></i>Añadir Contenido
            </button>
        @endif
    </div>

    {{-- ── Lista de temas ── --}}
    <div class="cl-course-list" id="clCourseList">

        @forelse($temas as $tema)
        @php
            $estaDesbloqueado = auth()->user()->hasRole('Docente') ||
                (auth()->user()->hasRole('Estudiante') &&
                 isset($inscritos2) &&
                 $tema->estaDesbloqueado($inscritos2->id));

            $temaProgreso = (auth()->user()->hasRole('Estudiante') && isset($inscritos2))
                ? $tema->calcularProgreso($inscritos2->id)
                : null;

            $totalSubtemas    = $tema->subtemas->count();
            $completados      = 0;
            if (auth()->user()->hasRole('Estudiante') && isset($inscritos2)) {
                $completados = $tema->subtemas->filter(fn($s) => $s->estaCompletado($inscritos2->id))->count();
            }
        @endphp

        <div class="cl-section {{ !$estaDesbloqueado ? 'cl-section--locked' : '' }}"
             id="cl-section-{{ $tema->id }}">

            {{-- Cabecera del tema --}}
            <div class="cl-section-header"
                 data-cl-target="#cl-body-{{ $tema->id }}"
                 aria-expanded="{{ $loop->first && $estaDesbloqueado ? 'true' : 'false' }}">

                <div class="cl-section-left">
                    <div class="cl-section-toggle">
                        <i class="bi bi-chevron-right cl-chevron"></i>
                    </div>
                    <div class="cl-section-info">
                        <h4 class="cl-section-title">
                            @if(!$estaDesbloqueado)
                                <i class="bi bi-lock-fill cl-lock-icon"></i>
                            @endif
                            {{ $tema->titulo_tema }}
                        </h4>
                        <div class="cl-section-meta">
                            <span>{{ $totalSubtemas }} lección{{ $totalSubtemas != 1 ? 'es' : '' }}</span>
                            @if($temaProgreso !== null)
                                <span class="cl-section-progress-text {{ $temaProgreso >= 100 ? 'cl-done' : '' }}">
                                    · {{ $completados }}/{{ $totalSubtemas }} completadas
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="cl-section-right" onclick="event.stopPropagation()">
                    {{-- Mini barra de progreso --}}
                    @if($temaProgreso !== null)
                        <div class="cl-mini-progress" title="{{ $temaProgreso }}%">
                            <div class="cl-mini-fill" style="width: {{ $temaProgreso }}%"></div>
                        </div>
                    @endif

                    {{-- Menú docente (kebab) --}}
                    @if(auth()->user()->hasRole('Docente') && $cursos->docente_id == auth()->user()->id)
                        <div class="dropdown">
                            <button class="cl-kebab-btn" data-bs-toggle="dropdown" aria-expanded="false"
                                    title="Opciones del tema">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end cl-dropdown">
                                <li>
                                    <button class="dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#modalSubtema-{{ $tema->id }}">
                                        <i class="bi bi-plus-circle me-2"></i>Nuevo Subtema
                                    </button>
                                </li>
                                <li>
                                    <button class="dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#modalEditarTema-{{ $tema->id }}">
                                        <i class="bi bi-pencil me-2"></i>Editar Tema
                                    </button>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('temas.delete', encrypt($tema->id)) }}" method="POST"
                                          class="cl-form-delete">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-trash me-2"></i>Eliminar Tema
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Cuerpo: lista de subtemas --}}
            <div class="collapse {{ $loop->first && $estaDesbloqueado ? 'show' : '' }}"
                 id="cl-body-{{ $tema->id }}">

                @if($estaDesbloqueado)
                    @include('partials.cursos.tema_item', ['tema' => $tema, 'index' => $loop->index])
                @else
                    <div class="cl-locked-msg">
                        <i class="bi bi-lock-fill"></i>
                        <span>Completa el tema anterior para desbloquear este contenido.</span>
                    </div>
                @endif
            </div>

        </div>
        @empty

        {{-- Estado vacío --}}
        <div class="tt-empty">
            <div class="tt-empty-icon"><i class="bi bi-journal-bookmark"></i></div>
            <h4 class="tt-empty-title">No hay temas disponibles</h4>
            <p class="tt-empty-sub">Aún no se ha agregado contenido a este curso.</p>
            @if(auth()->user()->hasRole('Docente') && $cursos->docente_id == auth()->user()->id)
                <button class="cc-btn cc-btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#modalTema">
                    <i class="bi bi-plus-circle-fill me-2"></i>Crear Primer Tema
                </button>
            @endif
        </div>

        @endforelse
    </div>
</div>

<script>
    (function () {

        /* ── Toggle sections via JS (avoids Bootstrap capturing dropdown clicks) ── */
        function bindSectionHeaders() {
            document.querySelectorAll('.cl-section-header[data-cl-target]').forEach(header => {
                const targetId = header.getAttribute('data-cl-target');
                const panel = document.querySelector(targetId);
                if (!panel) return;

                // Sync chevron with collapse events
                panel.addEventListener('show.bs.collapse', () => {
                    header.classList.add('cl-open');
                    header.setAttribute('aria-expanded', 'true');
                });
                panel.addEventListener('hide.bs.collapse', () => {
                    header.classList.remove('cl-open');
                    header.setAttribute('aria-expanded', 'false');
                });

                // Init state
                if (panel.classList.contains('show')) {
                    header.classList.add('cl-open');
                }

                // Click handler — ignore clicks on dropdown area
                if (!header._clBound) {
                    header._clBound = true;
                    header.addEventListener('click', function(e) {
                        // Don't toggle if user clicked inside the right side (dropdown zone)
                        if (e.target.closest('.cl-section-right')) return;

                        // Toggle the Bootstrap collapse programmatically
                        const bsCollapse = bootstrap.Collapse.getOrCreateInstance(panel, { toggle: false });
                        bsCollapse.toggle();
                    });
                }
            });
        }

        /* ── Delete confirmation ── */
        function bindDeleteForms() {
            document.querySelectorAll('.cl-form-delete').forEach(form => {
                if (form._clBound) return;
                form._clBound = true;
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: '¿Eliminar este tema?',
                            text: 'Se eliminarán todos los subtemas asociados.',
                            showCancelButton: true,
                            confirmButtonColor: '#dc2626',
                            cancelButtonColor: '#64748b',
                            confirmButtonText: 'Sí, eliminar',
                            cancelButtonText: 'Cancelar'
                        }).then(r => { if (r.isConfirmed) form.submit(); });
                    } else {
                        if (confirm('¿Eliminar este tema y todos sus subtemas?')) form.submit();
                    }
                });
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            bindSectionHeaders();
            bindDeleteForms();
        });
        document.addEventListener('livewire:load', () => { bindSectionHeaders(); });
        document.addEventListener('turbo:load', () => { bindSectionHeaders(); bindDeleteForms(); });
    })();
</script>