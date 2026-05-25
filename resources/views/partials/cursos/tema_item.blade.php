{{-- Lista simple de subtemas dentro de un tema --}}
<div class="cl-lessons" id="cl-lessons-{{ $tema->id }}">

    {{-- Descripción del tema (si existe) --}}
    @if ($tema->descripcion)
        <div class="cl-tema-desc">
            <i class="bi bi-info-circle me-2 text-primary"></i>
            <span>{{ $tema->descripcion }}</span>
        </div>
    @endif

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

        <div class="cl-lesson {{ !$desbloqueado ? 'cl-lesson--locked' : '' }} {{ $completado ? 'cl-lesson--done' : '' }}"
             id="subtema-{{ $subtema->id }}">

            {{-- Fila clickable del subtema --}}
            <div class="cl-lesson-row"
                 @if($desbloqueado)
                    data-bs-toggle="collapse"
                    data-bs-target="#cl-content-{{ $subtema->id }}"
                    aria-expanded="{{ $subtemaIndex === 0 ? 'true' : 'false' }}"
                 @endif>

                {{-- Indicador de estado --}}
                <div class="cl-lesson-status">
                    @if ($completado)
                        <div class="cl-status-icon cl-status--done">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                    @elseif (!$desbloqueado)
                        <div class="cl-status-icon cl-status--lock">
                            <i class="bi bi-lock-fill"></i>
                        </div>
                    @else
                        <div class="cl-status-icon cl-status--open">
                            <i class="bi bi-play-circle"></i>
                        </div>
                    @endif
                </div>

                {{-- Info del subtema --}}
                <div class="cl-lesson-info">
                    <span class="cl-lesson-title">{{ $subtema->titulo_subtema }}</span>
                    <div class="cl-lesson-meta">
                        @if ($subtema->duracion)
                            <span><i class="bi bi-clock me-1"></i>{{ $subtema->duracion }}</span>
                        @endif
                        @if (auth()->user()->hasRole('Estudiante'))
                            <span class="cl-lesson-badge cl-badge--{{ $completado ? 'done' : ($desbloqueado ? 'open' : 'lock') }}">
                                {{ $completado ? 'Completado' : ($desbloqueado ? 'Disponible' : 'Bloqueado') }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Chevron (solo si desbloqueado) --}}
                @if($desbloqueado)
                    <i class="bi bi-chevron-down cl-lesson-chevron"></i>
                @endif
            </div>

            {{-- Contenido expandible del subtema --}}
            @if ($desbloqueado)
                <div class="collapse {{ $subtemaIndex === 0 ? 'show' : '' }}"
                     id="cl-content-{{ $subtema->id }}">
                    <div class="cl-lesson-content">
                        @include('partials.cursos.subtema_item', [
                            'subtema' => $subtema,
                            'tema' => $tema,
                        ])
                    </div>
                </div>
            @endif
        </div>

    @empty
        <div class="cl-empty-lessons">
            <i class="bi bi-inbox me-2"></i>
            <span>No hay lecciones en este tema.</span>
            @if (auth()->user()->hasRole('Docente') && $cursos->docente_id == auth()->user()->id)
                <button class="cc-btn cc-btn-primary cc-btn-sm ms-2" data-bs-toggle="modal"
                    data-bs-target="#modalSubtema-{{ $tema->id }}">
                    <i class="bi bi-plus-circle-fill me-1"></i>Crear Lección
                </button>
            @endif
        </div>
    @endforelse
</div>

<script>
(function() {
    function bindLessonChevrons() {
        document.querySelectorAll('.cl-lesson-row[data-bs-toggle="collapse"]').forEach(row => {
            const targetId = row.getAttribute('data-bs-target');
            const panel = document.querySelector(targetId);
            const chevron = row.querySelector('.cl-lesson-chevron');
            if (!panel) return;

            panel.addEventListener('show.bs.collapse', () => {
                if (chevron) chevron.classList.add('cl-chevron-open');
                row.setAttribute('aria-expanded', 'true');
            });
            panel.addEventListener('hide.bs.collapse', () => {
                if (chevron) chevron.classList.remove('cl-chevron-open');
                row.setAttribute('aria-expanded', 'false');
            });

            // Init
            if (panel.classList.contains('show') && chevron) {
                chevron.classList.add('cl-chevron-open');
            }
        });
    }

    document.addEventListener('DOMContentLoaded', bindLessonChevrons);
    document.addEventListener('livewire:load', bindLessonChevrons);
    document.addEventListener('turbo:load', bindLessonChevrons);
})();
</script>
