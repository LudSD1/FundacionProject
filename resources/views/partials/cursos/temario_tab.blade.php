<div class="tab-pane fade show active"
     id="tab-actividades"
     role="tabpanel"
     aria-labelledby="temario-tab">

    {{-- ── Header ── --}}
    <div class="tt-header">
        <div>
            <h3 class="tt-title">Contenido del Curso</h3>
            <p class="tt-sub">Explora los temas y subtemas del curso</p>
        </div>
        @if(auth()->user()->hasRole('Docente') && $cursos->docente_id == auth()->user()->id)
            <button class="cc-btn cc-btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#modalTema">
                <i class="bi bi-plus-circle-fill me-2"></i>Añadir Contenido
            </button>
        @endif
    </div>

    {{-- ── Acordeón de temas ── --}}
    <div class="tt-accordion" id="ttAccordion">

        @forelse($temas as $tema)
        @php
            $estaDesbloqueado = auth()->user()->hasRole('Docente') ||
                (auth()->user()->hasRole('Estudiante') &&
                 isset($inscritos2) &&
                 $tema->estaDesbloqueado($inscritos2->id));

            // FIX: renombrado para no colisionar con $progreso del padre
            $temaProgreso = (auth()->user()->hasRole('Estudiante') && isset($inscritos2))
                ? $tema->calcularProgreso($inscritos2->id)
                : null;
        @endphp

        <div class="tt-tema {{ !$estaDesbloqueado ? 'tt-tema--locked' : '' }}"
             id="tt-tema-{{ $tema->id }}"
             data-tema-id="{{ $tema->id }}"
             data-locked="{{ $estaDesbloqueado ? 'false' : 'true' }}">

            {{-- Header del tema --}}
            <div class="tt-tema-header" id="tt-head-{{ $tema->id }}">
                <div class="tt-tema-left">
                    {{-- Número --}}
                    <div class="tt-num">{{ $loop->iteration }}</div>

                    {{-- Info --}}
                    <div class="tt-tema-info">
                        <h4 class="tt-tema-title">
                            {{ $tema->titulo_tema }}
                            @if(!$estaDesbloqueado)
                                <i class="bi bi-lock-fill tt-lock-icon"></i>
                            @endif
                        </h4>
                        <div class="tt-meta">
                            <span class="tt-meta-item">
                                <i class="bi bi-stack"></i>
                                {{ $tema->subtemas->count() }} subtema{{ $tema->subtemas->count() != 1 ? 's' : '' }}
                            </span>
                            @if($temaProgreso !== null)
                                <span class="tt-meta-item {{ $temaProgreso >= 100 ? 'tt-meta-item--done' : '' }}">
                                    <i class="bi bi-graph-up-arrow"></i>
                                    {{ $temaProgreso }}% completado
                                </span>
                                {{-- Mini barra de progreso --}}
                                <div class="tt-mini-progress">
                                    <div class="tt-mini-fill"
                                         style="width:{{ $temaProgreso }}%"
                                         data-width="{{ $temaProgreso }}"></div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Acciones docente --}}
                <div class="tt-tema-actions">
                    @if(auth()->user()->hasRole('Docente') && $cursos->docente_id == auth()->user()->id)
                        <button class="tt-action-btn tt-action-btn--edit"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEditarTema-{{ $tema->id }}"
                                title="Editar tema"
                                onclick="event.stopPropagation()">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                    @endif
                    <i class="bi bi-chevron-down tt-chevron"></i>
                </div>
            </div>

            {{-- Cuerpo del tema (colapsable via JS) --}}
            <div class="tt-tema-body" id="tt-body-{{ $tema->id }}">
                @if($estaDesbloqueado)
                    @include('partials.cursos.tema_item', ['tema' => $tema, 'index' => $loop->index])
                @else
                    <div class="tt-locked-msg">
                        <div class="tt-locked-icon"><i class="bi bi-lock-fill"></i></div>
                        <div>
                            <strong>Tema bloqueado</strong>
                            <p class="mb-0">Completa el tema anterior para desbloquear este contenido.</p>
                        </div>
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

        /* ── Animación mini barras de progreso ── */
        function animateMiniProgress() {
            document.querySelectorAll('.tt-mini-fill').forEach(bar => {
                const w = bar.getAttribute('data-width') || '0';
                requestAnimationFrame(() =>
                    setTimeout(() => { bar.style.width = w + '%'; }, 60)
                );
            });
        }

        /* ── Toggle accordion ── */
        function toggleTema(card) {
            const wasOpen = card.classList.contains('open');

            // Cerrar todos
            document.querySelectorAll('.tt-tema.open').forEach(c => c.classList.remove('open'));

            // Abrir el clickeado si estaba cerrado
            if (!wasOpen) {
                card.classList.add('open');
                // Scroll suave al tema abierto
                setTimeout(() => {
                    card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 120);
            }
        }

        /* ── Mensaje tema bloqueado ── */
        function showLockMessage() {
            if (typeof Swal === 'undefined') return;
            Swal.fire({
                icon: 'info',
                title: 'Tema Bloqueado',
                text: 'Debes completar el tema anterior para desbloquear este contenido.',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#145da0'
            });
        }

        /* ── Bindear eventos a los headers ── */
        function bindTemaHeaders() {
            document.querySelectorAll('.tt-tema-header').forEach(header => {
                // Quitar listener previo si se llama múltiples veces (ej: pjax)
                header.removeEventListener('click', header._ttHandler);

                header._ttHandler = function (e) {
                    // No togglear si el click fue en el botón editar
                    if (e.target.closest('.tt-action-btn')) return;

                    const card   = this.closest('.tt-tema');
                    const locked = card.getAttribute('data-locked') === 'true';

                    if (locked) {
                        showLockMessage();
                    } else {
                        toggleTema(card);
                    }
                };

                header.addEventListener('click', header._ttHandler);
            });
        }

        /* ── Abrir automáticamente el primer tema desbloqueado ── */
        function openFirstUnlocked() {
            const first = document.querySelector('.tt-tema:not(.tt-tema--locked)');
            if (first && !first.classList.contains('open')) {
                first.classList.add('open');
            }
        }

        /* ── Init ── */
        document.addEventListener('DOMContentLoaded', function () {
            bindTemaHeaders();
            openFirstUnlocked();
            animateMiniProgress();
        });

        // Re-init si se usa turbo/livewire/pjax
        document.addEventListener('livewire:load',  () => { bindTemaHeaders(); animateMiniProgress(); });
        document.addEventListener('turbo:load',     () => { bindTemaHeaders(); animateMiniProgress(); });

    })();
    </script>