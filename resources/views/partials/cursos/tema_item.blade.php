<div class="ti-wrap" id="ti-tema-{{ $tema->id }}">

    <div class="ti-hero">
        <div class="ti-hero-overlay"></div>

        <div class="ti-hero-body">

            {{-- Stats + título --}}
            <div class="ti-hero-main">
                <div class="ti-hero-text">
                    <div class="ti-hero-eyebrow">
                        <i class="bi bi-folder2-open"></i> Tema {{ $index + 1 }}
                    </div>
                    <h2 class="ti-hero-title">{{ $tema->titulo_tema }}</h2>
                    <p class="ti-hero-sub">Explora el contenido y avanza en tu aprendizaje</p>
                </div>

                <div class="ti-stats">
                    <div class="ti-stat">
                        <span class="ti-stat-num">{{ $tema->subtemas->count() }}</span>
                        <span class="ti-stat-lbl">Subtemas</span>
                    </div>
                    @if (auth()->user()->hasRole('Estudiante') && isset($inscritos2))
                        {{-- FIX 5: usar $temaProgreso pasado desde el padre en lugar de recalcular --}}
                        <div class="ti-stat">
                            <span class="ti-stat-num">{{ $temaProgreso ?? 0 }}%</span>
                            <span class="ti-stat-lbl">Completado</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Descripción colapsable --}}
            @if ($tema->descripcion)
                <div class="ti-desc-wrap">
                    <button class="ti-desc-toggle" type="button" data-bs-toggle="collapse"
                        data-bs-target="#tiDesc-{{ $tema->id }}" aria-expanded="false"
                        id="tiDescBtn-{{ $tema->id }}">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-info-circle-fill"></i>
                            <span>Descripción del Tema</span>
                        </div>
                        <i class="bi bi-chevron-down ti-desc-chevron"></i>
                    </button>
                    <div class="collapse" id="tiDesc-{{ $tema->id }}">
                        <div class="ti-desc-body">
                            {!! nl2br(e($tema->descripcion)) !!}
                        </div>
                    </div>
                </div>
            @endif

            {{-- Acciones docente --}}
            @if (auth()->user()->hasRole('Docente') && $cursos->docente_id == auth()->user()->id)
                <div class="ti-hero-actions">
                    <button class="cc-btn cc-btn-glass" data-bs-toggle="modal"
                        data-bs-target="#modalSubtema-{{ $tema->id }}">
                        <i class="bi bi-plus-circle-fill me-2"></i>Nuevo Subtema
                    </button>
                    <button class="cc-btn cc-btn-glass" data-bs-toggle="modal"
                        data-bs-target="#modalEditarTema-{{ $tema->id }}">
                        <i class="bi bi-pencil-fill me-2"></i>Editar Tema
                    </button>
                    {{-- FIX 4: sin style inline --}}
                    <form action="{{ route('temas.delete', encrypt($tema->id)) }}" method="POST"
                        onsubmit="return confirm('¿Eliminar este tema y todos sus subtemas?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="cc-btn cc-btn-glass-danger">
                            <i class="bi bi-trash3-fill me-2"></i>Eliminar
                        </button>
                    </form>
                </div>
            @endif
</div>
    </div>

    <div class="ti-timeline">

        @forelse($tema->subtemas as $subtemaIndex => $subtema)
            @php
                $desbloqueado =
                    auth()->user()->hasRole('Docente') ||
                    (auth()->user()->hasRole('Estudiante') &&
                        isset($inscritos2) &&
                        $subtema->estaDesbloqueado($inscritos2->id ?? null));

                $completado =
                    auth()->user()->hasRole('Estudiante') && isset($inscritos2)
                        ? $subtema->estaCompletado($inscritos2->id ?? null)
                        : false;
            @endphp

            <div class="ti-subtema" id="subtema-{{ $subtema->id }}">

                {{-- Marcador timeline --}}
                <div
                    class="ti-marker {{ $completado ? 'ti-marker--done' : ($desbloqueado ? 'ti-marker--open' : 'ti-marker--lock') }}">
                    @if ($completado)
                        <i class="bi bi-check-lg"></i>
                    @elseif(!$desbloqueado)
                        <i class="bi bi-lock-fill"></i>
                    @else
                        {{ $subtemaIndex + 1 }}
                    @endif
                </div>

                {{-- Card del subtema --}}
                <div class="ti-sub-card {{ !$desbloqueado ? 'ti-sub-card--locked' : '' }}">

                    {{-- Header --}}
                    <div class="ti-sub-header" data-bs-toggle="collapse"
                        data-bs-target="#tiSubBody-{{ $subtema->id }}"
                        aria-expanded="{{ $subtemaIndex === 0 ? 'true' : 'false' }}"
                        id="tiSubHead-{{ $subtema->id }}">

                        <div class="ti-sub-head-left">
                            <h4 class="ti-sub-title">
                                {{ $subtema->titulo_subtema }}
                                @if (!$desbloqueado)
                                    <i class="bi bi-lock-fill ti-sub-lock"></i>
                                @endif
                            </h4>
                            <div class="ti-sub-meta">
                                @if ($subtema->duracion)
                                    <span class="ti-sub-meta-item">
                                        <i class="bi bi-clock"></i> {{ $subtema->duracion }}
                                    </span>
                                @endif
                                @if (auth()->user()->hasRole('Estudiante'))
                                    <span
                                        class="ti-sub-meta-item
                                    {{ $completado ? 'ti-sub-meta-item--done' : ($desbloqueado ? 'ti-sub-meta-item--open' : 'ti-sub-meta-item--lock') }}">
                                        <i
                                            class="bi bi-{{ $completado ? 'check-circle-fill' : ($desbloqueado ? 'play-circle-fill' : 'lock-fill') }}"></i>
                                        {{ $completado ? 'Completado' : ($desbloqueado ? 'Disponible' : 'Bloqueado') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <i
                            class="bi bi-chevron-down ti-sub-chevron
                        {{ $subtemaIndex === 0 ? 'ti-sub-chevron--open' : '' }}"></i>
                    </div>

                    {{-- Cuerpo --}}
                    @if ($desbloqueado)
                        <div class="collapse {{ $subtemaIndex === 0 ? 'show' : '' }}"
                            id="tiSubBody-{{ $subtema->id }}">
                            <div class="ti-sub-body">
                                @include('partials.cursos.subtema_item', [
                                    'subtema' => $subtema,
                                    'tema' => $tema,
                                ])
                            </div>
                        </div>
                    @else
                        <div class="ti-sub-locked-body">
                            <div class="ti-sub-locked-msg">
                                <div class="ti-sub-locked-icon"><i class="bi bi-lock-fill"></i></div>
                                <div>
                                    <strong>Contenido bloqueado</strong>
                                    <p class="mb-0">Completa los requisitos anteriores para desbloquear este
                                        contenido.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>{{-- /ti-sub-card --}}
            </div>{{-- /ti-subtema --}}

        @empty

            {{-- Estado vacío --}}
            <div class="ti-empty">
                <div class="ti-empty-icon"><i class="bi bi-inbox"></i></div>
                <h5 class="ti-empty-title">No hay subtemas disponibles</h5>
                <p class="ti-empty-sub">Aún no se han agregado subtemas a este tema.</p>
                @if (auth()->user()->hasRole('Docente') && $cursos->docente_id == auth()->user()->id)
                    <button class="cc-btn cc-btn-primary" data-bs-toggle="modal"
                        data-bs-target="#modalSubtema-{{ $tema->id }}">
                        <i class="bi bi-plus-circle-fill me-2"></i>Crear Primer Subtema
                    </button>
                @endif
            </div>
        @endforelse
    </div>{{-- /ti-timeline --}}

</div>

<script>
    (function() {

        /* ── 1. Chevron de descripción del tema ── */
        function bindDescToggles() {
            document.querySelectorAll('.ti-desc-toggle').forEach(btn => {
                // Sincronizar chevron con el estado del collapse de Bootstrap
                const targetId = btn.getAttribute('data-bs-target');
                const panel = document.querySelector(targetId);
                if (!panel) return;

                panel.addEventListener('show.bs.collapse', () => {
                    btn.setAttribute('aria-expanded', 'true');
                });
                panel.addEventListener('hide.bs.collapse', () => {
                    btn.setAttribute('aria-expanded', 'false');
                });
            });
        }

        /* ── 2. Chevron de cabecera de subtema ── */
        function bindSubtemaHeaders() {
            document.querySelectorAll('.ti-sub-header').forEach(header => {
                const targetId = header.getAttribute('data-bs-target');
                const panel = document.querySelector(targetId);
                const chevron = header.querySelector('.ti-sub-chevron');
                if (!panel || !chevron) return;

                panel.addEventListener('show.bs.collapse', () => {
                    chevron.classList.add('ti-sub-chevron--open');
                    header.setAttribute('aria-expanded', 'true');
                    // Scroll suave al subtema al abrir
                    setTimeout(() => {
                        header.closest('.ti-subtema')
                            ?.scrollIntoView({
                                behavior: 'smooth',
                                block: 'nearest'
                            });
                    }, 120);
                });
                panel.addEventListener('hide.bs.collapse', () => {
                    chevron.classList.remove('ti-sub-chevron--open');
                    header.setAttribute('aria-expanded', 'false');
                });
            });
        }

        /* ── 3. IntersectionObserver para animar subtemas al entrar en pantalla ── */
        function observeSubtemas() {
            if (!('IntersectionObserver' in window)) return;

            const obs = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                        obs.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.08
            });

            document.querySelectorAll('.ti-subtema').forEach(item => {
                // Solo para items más allá del primero (el primero ya animó con CSS)
                const idx = parseInt(item.closest('.ti-timeline')
                    ?.querySelectorAll('.ti-subtema') ?
                    Array.from(item.closest('.ti-timeline').querySelectorAll('.ti-subtema')).indexOf(
                        item) :
                    0);

                if (idx > 5) {
                    // Los primeros 6 tienen delay CSS; el resto los maneja el observer
                    item.style.opacity = '0';
                    item.style.transform = 'translateY(14px)';
                    item.style.transition = 'opacity .35s ease, transform .35s ease';
                    obs.observe(item);
                }
            });
        }

        /* ── Init ── */
        document.addEventListener('DOMContentLoaded', function() {
            bindDescToggles();
            bindSubtemaHeaders();
            observeSubtemas();
        });

        // Compatibilidad con Livewire / Turbo
        document.addEventListener('livewire:load', () => {
            bindDescToggles();
            bindSubtemaHeaders();
        });
        document.addEventListener('turbo:load', () => {
            bindDescToggles();
            bindSubtemaHeaders();
            observeSubtemas();
        });

    })();
</script>
