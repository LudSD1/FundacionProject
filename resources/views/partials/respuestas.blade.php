
<div class="mb-4">
    {{-- Encabezado de sección --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <h4 class="text-primary fw-bold mb-1"><i class="bi bi-reply-all-fill me-2"></i>Respuestas por Pregunta</h4>
            <p class="text-muted small mb-0">Gestiona las opciones de respuesta para cada pregunta. Expande una pregunta para ver y editar sus respuestas.</p>
        </div>
        {{-- Resumen rápido --}}
        @php
            $totalPreguntas    = $preguntas->count();
            $preguntasListas   = $preguntas->filter(fn($p) => $p->respuestas->count() > 0 && $p->respuestas->where('es_correcta', true)->count() > 0)->count();
            $porcentajeListas  = $totalPreguntas > 0 ? round(($preguntasListas / $totalPreguntas) * 100) : 0;
        @endphp
        <div class="rp-summary-box">
            <div class="rp-summary-progress">
                <div class="rp-progress-bar" style="width: {{ $porcentajeListas }}%"></div>
            </div>
            <span class="rp-summary-text">
                <i class="bi bi-check2-all me-1"></i>
                <strong>{{ $preguntasListas }}/{{ $totalPreguntas }}</strong> preguntas configuradas
            </span>
        </div>
    </div>

    {{-- Accordion de preguntas --}}
    @forelse ($preguntas as $pregunta)
        @include('partials.respuestas._pregunta_card', ['pregunta' => $pregunta, 'loop' => $loop])
    @empty
        <div class="text-center py-5">
            <i class="bi bi-inbox display-4 text-muted mb-3"></i>
            <h5 class="text-muted mb-1">No hay preguntas registradas</h5>
            <p class="text-muted small mb-0">Primero agrega preguntas en la pestaña "Preguntas".</p>
        </div>
    @endforelse
</div>

{{-- ============================================================
     MODALES
     ============================================================ --}}
@push('modals')
    @foreach ($preguntas as $pregunta)
        {{-- Modales de edición por respuesta --}}
        @foreach ($pregunta->respuestas as $respuesta)
            @include('partials.respuestas._modal_editar', ['pregunta' => $pregunta, 'respuesta' => $respuesta])
        @endforeach

        {{-- Modal de crear respuestas (opción múltiple) --}}
        @include('partials.respuestas._modal_crear', ['pregunta' => $pregunta])

        {{-- Modal de respuesta clave (abierta) --}}
        @include('partials.respuestas._modal_clave', ['pregunta' => $pregunta])
    @endforeach
@endpush

{{-- ============================================================
     JAVASCRIPT — DINÁMICAS DE FORMULARIO
     ============================================================ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    /* ── Confirmación de eliminación ─────────────────── */
    document.querySelectorAll('.form-eliminar').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const self = this;
            Swal.fire({
                title: '¿Eliminar respuesta?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then(r => { if (r.isConfirmed) self.submit(); });
        });
    });

    /* ── Animación de chevron en accordion ────────────── */
    document.querySelectorAll('.rp-question-header').forEach(header => {
        const collapseEl = document.querySelector(header.dataset.bsTarget);
        if (!collapseEl) return;
        collapseEl.addEventListener('show.bs.collapse', () => header.classList.add('rp-open'));
        collapseEl.addEventListener('hide.bs.collapse', () => header.classList.remove('rp-open'));
        // Sync initial state
        if (collapseEl.classList.contains('show')) header.classList.add('rp-open');
    });

    /* ── Dinámica de respuestas opciones múltiples ─── */
    @foreach ($preguntas as $pregunta)
        @if ($pregunta->tipo === 'opcion_multiple')
        (function () {
            const container = document.getElementById('rp-container-{{ $pregunta->id }}');
            const addBtn    = document.getElementById('rp-add-{{ $pregunta->id }}');
            let   idx       = 2;

            if (!addBtn) return;

            addBtn.addEventListener('click', () => {
                const div = document.createElement('div');
                div.className = 'mb-3 p-3 bg-light rounded-4 border position-relative';
                div.innerHTML = `
                    <button type="button"
                            class="btn-close position-absolute top-0 end-0 m-2"
                            style="font-size: 0.7rem;"
                            onclick="this.closest('.mb-3').remove()">
                    </button>
                    <div class="row g-3 align-items-end">
                        <div class="col-md-8">
                            <label class="form-label fw-bold text-muted small text-uppercase">Respuesta ${idx + 1}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-chat-left-text text-primary"></i></span>
                                <input type="text"
                                       name="respuestas[${idx}][contenido]"
                                       class="form-control bg-white"
                                       placeholder="Escriba la respuesta…"
                                       required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small text-uppercase">¿Correcta?</label>
                            <select name="respuestas[${idx}][es_correcta]" class="form-select bg-white" required>
                                <option value="">Seleccione…</option>
                                <option value="1">✓ Sí</option>
                                <option value="0">✗ No</option>
                            </select>
                        </div>
                    </div>`;
                container.appendChild(div);
                idx++;
            });
        })();
        @endif

        @if ($pregunta->tipo === 'abierta')
        (function () {
            const container = document.getElementById('rp-container-clave-{{ $pregunta->id }}');
            const addBtn    = document.getElementById('rp-add-clave-{{ $pregunta->id }}');
            let   idx       = 1;

            if (!addBtn) return;

            addBtn.addEventListener('click', () => {
                const div = document.createElement('div');
                div.className = 'mb-3 position-relative';
                div.innerHTML = `
                    <button type="button"
                            class="btn-close position-absolute top-0 end-0"
                            style="font-size: 0.7rem;"
                            onclick="this.closest('.mb-3').remove()">
                    </button>
                    <label class="form-label fw-bold text-muted small text-uppercase">Respuesta Clave ${idx + 1}</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-chat-left-text text-primary"></i></span>
                        <input type="text"
                               name="respuestas[${idx}][contenido]"
                               class="form-control bg-light"
                               placeholder="Escriba una respuesta clave…"
                               required>
                    </div>
                    <input type="hidden" name="respuestas[${idx}][es_correcta]" value="1">`;
                container.appendChild(div);
                idx++;
            });
        })();
        @endif
    @endforeach
});
</script>
