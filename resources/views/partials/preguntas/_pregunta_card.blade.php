{{-- ============================================================
     CARD INDIVIDUAL POR PREGUNTA — PESTAÑA PREGUNTAS
     Recibe: $pregunta, $loop
     ============================================================ --}}
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

    // Estado de respuestas
    if ($totalResp === 0) {
        $estadoResp = ['color' => 'danger',  'icon' => 'bi-exclamation-triangle-fill', 'texto' => 'Sin respuestas'];
    } elseif (!$tieneCorrecta) {
        $estadoResp = ['color' => 'warning', 'icon' => 'bi-exclamation-circle-fill',   'texto' => 'Sin correcta'];
    } else {
        $estadoResp = ['color' => 'success', 'icon' => 'bi-check-circle-fill',         'texto' => $totalResp . ' resp. ✓'];
    }
@endphp

<div class="rp-question-card {{ $loop->first ? '' : 'mt-3' }} {{ $isDeleted ? 'rp-card-deleted' : '' }}"
     id="preg-card-{{ $pregunta->id }}"
     data-tipo="{{ $pregunta->tipo }}"
     data-search="{{ Str::lower($pregunta->enunciado) }}">

    {{-- ── Header clickable ─────────────────────────────── --}}
    <div class="rp-question-header"
         data-bs-toggle="collapse"
         data-bs-target="#preg-collapse-{{ $pregunta->id }}"
         aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
         aria-controls="preg-collapse-{{ $pregunta->id }}"
         role="button">

        <div class="rp-header-left">
            {{-- Número --}}
            <div class="rp-question-number">
                <span>{{ $loop->iteration }}</span>
            </div>

            {{-- Info --}}
            <div class="rp-header-info">
                <h6 class="rp-question-text mb-1">
                    @if ($isDeleted)
                        <span class="text-decoration-line-through text-muted">{{ $pregunta->enunciado }}</span>
                    @else
                        {{ $pregunta->enunciado }}
                    @endif
                </h6>
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    {{-- Badge tipo --}}
                    <span class="badge bg-{{ $tipoConfig['color'] }} bg-opacity-10 text-{{ $tipoConfig['color'] }} border border-{{ $tipoConfig['color'] }} border-opacity-25 px-2 py-1 rounded-pill fw-bold" style="font-size: 0.68rem;">
                        <i class="bi {{ $tipoConfig['icon'] }} me-1"></i>{{ $tipoConfig['label'] }}
                    </span>
                    {{-- Badge puntos --}}
                    <span class="badge bg-light text-primary border border-primary-subtle px-2 py-1 rounded-pill fw-bold" style="font-size: 0.68rem;">
                        <i class="bi bi-star-fill me-1"></i>{{ $pregunta->puntaje }} pts
                    </span>
                    {{-- Badge estado respuestas --}}
                    <span class="badge bg-{{ $estadoResp['color'] }} bg-opacity-10 text-{{ $estadoResp['color'] }} border border-{{ $estadoResp['color'] }} border-opacity-25 px-2 py-1 rounded-pill fw-bold" style="font-size: 0.68rem;">
                        <i class="bi {{ $estadoResp['icon'] }} me-1"></i>{{ $estadoResp['texto'] }}
                    </span>
                    {{-- Badge eliminada --}}
                    @if ($isDeleted)
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 rounded-pill fw-bold" style="font-size: 0.68rem;">
                            <i class="bi bi-trash me-1"></i>Eliminada
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="rp-header-right d-flex align-items-center gap-3">
            {{-- Acciones rápidas (fuera del collapse para acceso inmediato) --}}
            <div class="d-flex gap-2" onclick="event.stopPropagation();">
                @if ($isDeleted)
                    <form method="POST" action="{{ route('pregunta.restore', encrypt($pregunta->id)) }}"
                          class="form-restaurar d-inline">
                        @csrf
                        <button type="submit" class="btn-action-modern btn-info" title="Restaurar pregunta">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </button>
                    </form>
                @else
                    <button class="btn-action-modern btn-info" data-bs-toggle="modal"
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
            {{-- Chevron --}}
            <i class="bi bi-chevron-down rp-chevron"></i>
        </div>
    </div>

    {{-- ── Cuerpo colapsable con detalles ───────────────── --}}
    <div class="collapse {{ $loop->first ? 'show' : '' }}" id="preg-collapse-{{ $pregunta->id }}">
        <div class="rp-question-body">
            <div class="row g-3">
                {{-- Detalle de la pregunta --}}
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded-3 h-100">
                        <label class="form-label fw-bold text-muted small text-uppercase mb-2">
                            <i class="bi bi-info-circle me-1"></i>Detalles
                        </label>
                        <ul class="list-unstyled mb-0 small">
                            <li class="mb-2 d-flex align-items-center gap-2">
                                <i class="bi {{ $tipoConfig['icon'] }} text-{{ $tipoConfig['color'] }}"></i>
                                <span class="text-muted">Tipo:</span>
                                <strong>{{ $tipoConfig['label'] }}</strong>
                            </li>
                            <li class="mb-2 d-flex align-items-center gap-2">
                                <i class="bi bi-star-fill text-primary"></i>
                                <span class="text-muted">Puntaje:</span>
                                <strong>{{ $pregunta->puntaje }} puntos</strong>
                            </li>
                            <li class="d-flex align-items-center gap-2">
                                <i class="bi bi-chat-left-dots-fill text-{{ $estadoResp['color'] }}"></i>
                                <span class="text-muted">Respuestas:</span>
                                <strong>{{ $totalResp }}</strong>
                            </li>
                        </ul>
                    </div>
                </div>
                {{-- Vista previa de respuestas --}}
                <div class="col-md-8">
                    <div class="p-3 bg-light rounded-3 h-100">
                        <label class="form-label fw-bold text-muted small text-uppercase mb-2">
                            <i class="bi bi-eye me-1"></i>Vista previa de respuestas
                        </label>
                        @if ($totalResp > 0)
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($pregunta->respuestas->take(6) as $resp)
                                    <span class="badge {{ $resp->es_correcta ? 'bg-success bg-opacity-10 text-success border border-success border-opacity-25' : 'bg-light text-muted border' }} px-3 py-2 rounded-pill fw-semibold" style="font-size: .78rem;">
                                        @if ($resp->es_correcta)
                                            <i class="bi bi-check-circle-fill me-1"></i>
                                        @else
                                            <i class="bi bi-circle me-1"></i>
                                        @endif
                                        {{ Str::limit($resp->contenido, 30) }}
                                    </span>
                                @endforeach
                                @if ($totalResp > 6)
                                    <span class="badge bg-light text-muted border px-3 py-2 rounded-pill fw-semibold" style="font-size: .78rem;">
                                        +{{ $totalResp - 6 }} más
                                    </span>
                                @endif
                            </div>
                        @else
                            <p class="text-muted small mb-0">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                Esta pregunta aún no tiene respuestas configuradas.
                                Ve a la pestaña <strong>"Respuestas"</strong> para agregarlas.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
