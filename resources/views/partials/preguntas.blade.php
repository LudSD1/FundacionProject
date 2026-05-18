<div class="mb-4">
    {{-- Mini hero toolbar --}}
    <div class="rp-toolbar">
        <div class="rp-toolbar-left">
            <div class="rp-toolbar-title">
                <i class="bi bi-list-task me-2"></i>Banco de Preguntas
            </div>
            <div class="rp-toolbar-controls">
                {{-- Búsqueda --}}
                <div class="tbl-hero-search">
                    <i class="bi bi-search tbl-hero-search-icon"></i>
                    <input type="text"
                           class="tbl-hero-search-input"
                           id="buscadorPreguntas"
                           placeholder="Buscar pregunta..."
                           autocomplete="off">
                </div>
                {{-- Filtro por tipo --}}
                <div class="tbl-hero-select-wrap">
                    <i class="bi bi-funnel-fill tbl-hero-select-icon"></i>
                    <select class="tbl-hero-select" id="filtroTipoPreguntas">
                        <option value="">Todos los tipos</option>
                        <option value="opcion_multiple">Opción Múltiple</option>
                        <option value="abierta">Abierta</option>
                        <option value="boolean">Verdadero/Falso</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="rp-toolbar-right">
            <button class="tbl-hero-btn tbl-hero-btn-primary" data-bs-toggle="modal"
                data-bs-target="#crearMultiplesPreguntasModal">
                <i class="bi bi-plus-circle-fill me-1"></i>
                <span>Crear Preguntas</span>
            </button>
        </div>
    </div>

    {{-- Info de filtro --}}
    <div class="tbl-filter-bar" id="pregFiltroInfo" style="display: none;">
        <div class="tbl-filter-bar-left">
            <i class="bi bi-funnel-fill"></i>
            Mostrando <strong><span id="pregFiltroConteo">0</span></strong> resultado(s)
        </div>
        <button class="tbl-filter-clear" id="pregLimpiarFiltro">
            <i class="bi bi-x-circle"></i> Limpiar
        </button>
    </div>

    {{-- Tabla de preguntas — mismo estilo que ListaUsuarios --}}
    <div class="table-container-modern">
        <table class="table-modern" id="tablaPreguntas">
            <thead>
                <tr>
                    <th width="5%">
                        <div class="th-content">
                            <i class="bi bi-hash"></i><span>#</span>
                        </div>
                    </th>
                    <th width="35%">
                        <div class="th-content">
                            <i class="bi bi-patch-question-fill"></i><span>Pregunta</span>
                        </div>
                    </th>
                    <th width="15%">
                        <div class="th-content">
                            <i class="bi bi-list-task"></i><span>Tipo</span>
                        </div>
                    </th>
                    <th width="10%">
                        <div class="th-content">
                            <i class="bi bi-star-fill"></i><span>Puntos</span>
                        </div>
                    </th>
                    <th width="20%">
                        <div class="th-content">
                            <i class="bi bi-chat-left-dots-fill"></i><span>Respuestas</span>
                        </div>
                    </th>
                    <th width="15%" class="text-center">
                        <div class="th-content justify-content-center">
                            <i class="bi bi-gear-fill"></i><span>Acciones</span>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($preguntas as $pregunta)
                    @php
                        $tipoConfig = match ($pregunta->tipo) {
                            'opcion_multiple' => ['icon' => 'bi-ui-checks',       'color' => 'primary', 'label' => 'Opción Múltiple'],
                            'abierta'         => ['icon' => 'bi-textarea-t',      'color' => 'info',    'label' => 'Abierta'],
                            'boolean'         => ['icon' => 'bi-toggle-on',       'color' => 'success', 'label' => 'V / F'],
                            default           => ['icon' => 'bi-question-circle', 'color' => 'secondary', 'label' => ucfirst($pregunta->tipo)],
                        };
                        $totalResp     = $pregunta->respuestas->count();
                        $tieneCorrecta = $pregunta->respuestas->where('es_correcta', true)->count() > 0;
                        $isDeleted     = (bool) $pregunta->deleted_at;
                    @endphp
                    <tr data-tipo="{{ $pregunta->tipo }}" class="{{ $isDeleted ? 'opacity-50' : '' }}">
                        <td><span class="row-number">{{ $loop->iteration }}</span></td>
                        <td>
                            <div class="{{ $isDeleted ? 'text-decoration-line-through text-muted' : 'fw-bold text-dark' }}" style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $pregunta->enunciado }}">
                                {{ $pregunta->enunciado }}
                            </div>
                            @if ($isDeleted)
                                <span class="status-badge status-danger mt-1" style="font-size: .62rem;">
                                    <i class="bi bi-trash"></i> Eliminada
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="type-badge type-{{ $pregunta->tipo === 'opcion_multiple' ? 'curso' : ($pregunta->tipo === 'boolean' ? 'evento' : 'congreso') }}">
                                <i class="bi {{ $tipoConfig['icon'] }} me-1"></i>{{ $tipoConfig['label'] }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-light text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-bold">
                                {{ $pregunta->puntaje }} pts
                            </span>
                        </td>
                        <td>
                            @if ($totalResp === 0)
                                <span class="status-badge status-danger">
                                    <i class="bi bi-exclamation-triangle-fill"></i> Sin respuestas
                                </span>
                            @elseif (!$tieneCorrecta)
                                <span class="status-badge status-pending">
                                    <i class="bi bi-exclamation-circle-fill"></i> {{ $totalResp }} — sin correcta
                                </span>
                            @else
                                <span class="status-badge status-active">
                                    <i class="bi bi-check-circle-fill"></i> {{ $totalResp }} resp. ✓
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons-cell">
                                @if ($isDeleted)
                                    <form method="POST" action="{{ route('pregunta.restore', encrypt($pregunta->id)) }}"
                                        class="form-restaurar d-inline">
                                        @csrf
                                        <button type="submit" class="btn-action-modern btn-view" title="Restaurar pregunta">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="btn-action-modern btn-edit" data-bs-toggle="modal"
                                        data-bs-target="#editarPreguntaModal-{{ $pregunta->id }}" title="Editar pregunta">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <form method="POST" action="{{ route('pregunta.delete', encrypt($pregunta->id)) }}"
                                        class="form-eliminar d-inline">
                                        @csrf
                                        <button type="submit" class="btn-action-modern btn-delete" title="Eliminar pregunta">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state-table">
                                <div class="empty-icon-table">
                                    <i class="bi bi-patch-question"></i>
                                </div>
                                <h5 class="empty-title-table">No hay preguntas registradas</h5>
                                <p class="empty-text-table">Comienza agregando preguntas al cuestionario.</p>
                                <button class="tbl-hero-btn tbl-hero-btn-primary" style="width: auto; margin: 0 auto;" data-bs-toggle="modal"
                                    data-bs-target="#crearMultiplesPreguntasModal">
                                    <i class="bi bi-plus-circle-fill me-1"></i> Crear Preguntas
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Conteo --}}
    @if ($preguntas->count() > 0)
        <div class="d-flex justify-content-between align-items-center mt-3 px-1">
            <small class="text-muted">
                <i class="bi bi-info-circle me-1"></i>
                {{ $preguntas->count() }} pregunta{{ $preguntas->count() !== 1 ? 's' : '' }} ·
                {{ $preguntas->sum('puntaje') }} puntos totales
            </small>
        </div>
    @endif
</div>

{{-- ── MODALES ─────────────────────────────────────────── --}}
@push('modals')
    @include('partials.preguntas._modal_crear', ['cuestionario' => $cuestionario])

    @foreach ($preguntas as $pregunta)
        @include('partials.preguntas._modal_editar', ['pregunta' => $pregunta])
    @endforeach
@endpush

{{-- ── JAVASCRIPT ──────────────────────────────────────── --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const buscador     = document.getElementById('buscadorPreguntas');
    const filtroTipo   = document.getElementById('filtroTipoPreguntas');
    const tabla        = document.getElementById('tablaPreguntas');
    const filtroInfo   = document.getElementById('pregFiltroInfo');
    const filtroConteo = document.getElementById('pregFiltroConteo');

    function filtrarPreguntas() {
        if (!tabla) return;
        const q     = buscador ? buscador.value.toLowerCase().trim() : '';
        const tipo  = filtroTipo ? filtroTipo.value : '';
        const filas = tabla.querySelectorAll('tbody tr[data-tipo]');
        let visibles = 0;

        filas.forEach(fila => {
            const textoMatch = !q || fila.textContent.toLowerCase().includes(q);
            const tipoMatch  = !tipo || fila.dataset.tipo === tipo;
            const mostrar    = textoMatch && tipoMatch;
            fila.style.display = mostrar ? '' : 'none';
            if (mostrar) visibles++;
        });

        if (filtroInfo && filtroConteo) {
            const hayFiltro = q || tipo;
            filtroInfo.style.display = hayFiltro ? '' : 'none';
            filtroConteo.textContent = visibles;
        }
    }

    if (buscador) buscador.addEventListener('input', filtrarPreguntas);
    if (filtroTipo) filtroTipo.addEventListener('change', filtrarPreguntas);

    /* ── Botón limpiar filtros ──────────────────────── */
    const btnLimpiar = document.getElementById('pregLimpiarFiltro');
    if (btnLimpiar) {
        btnLimpiar.addEventListener('click', function() {
            if (buscador) buscador.value = '';
            if (filtroTipo) filtroTipo.value = '';
            filtrarPreguntas();
        });
    }

    /* ── Dinámica del modal crear preguntas ────────── */
    const container = document.getElementById('preguntas-container');
    const btnAdd = document.getElementById('addPreguntaButton');
    let index = 1;

    if (btnAdd && container) {
        btnAdd.addEventListener('click', function() {
            const html = `
                <div class="mb-3 p-3 bg-light rounded-4 border position-relative">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-pregunta" style="font-size: 0.7rem;"></button>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-muted small text-uppercase">Texto de la Pregunta</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-chat-left-text text-primary"></i></span>
                                <input type="text" class="form-control bg-white" name="preguntas[${index}][enunciado]"
                                    placeholder="Escribe la pregunta aquí..." required>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-bold text-muted small text-uppercase">Tipo de Pregunta</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-list-task text-primary"></i></span>
                                <select class="form-select bg-white" name="preguntas[${index}][tipo]" required>
                                    <option value="opcion_multiple">Opción Múltiple</option>
                                    <option value="abierta">Respuesta Abierta</option>
                                    <option value="boolean">Verdadero/Falso</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small text-uppercase">Puntos</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-star text-primary"></i></span>
                                <input type="number" class="form-control bg-white" name="preguntas[${index}][puntaje]" min="1"
                                    placeholder="5" required>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            index++;
        });

        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-pregunta')) {
                e.target.closest('.mb-3').remove();
            }
        });
    }
});
</script>
