{{-- ============================================================
     CARD INDIVIDUAL POR PREGUNTA
     Recibe: $pregunta, $loop
     ============================================================ --}}
@php
    $tipoIcon = match ($pregunta->tipo) {
        'opcion_multiple' => 'bi-ui-checks',
        'abierta'         => 'bi-textarea-t',
        'boolean'         => 'bi-toggle-on',
        default           => 'bi-question-circle',
    };
    $tipoColor = match ($pregunta->tipo) {
        'opcion_multiple' => 'primary',
        'abierta'         => 'info',
        'boolean'         => 'success',
        default           => 'secondary',
    };
    $tipoLabel = match ($pregunta->tipo) {
        'opcion_multiple' => 'Opción Múltiple',
        'abierta'         => 'Abierta',
        'boolean'         => 'Verdadero / Falso',
        default           => ucfirst($pregunta->tipo),
    };

    $totalResp   = $pregunta->respuestas->count();
    $tieneCorrecta = $pregunta->respuestas->where('es_correcta', true)->count() > 0;

    // Determinar estado
    if ($totalResp === 0) {
        $estadoColor = 'danger';
        $estadoIcon  = 'bi-exclamation-triangle-fill';
        $estadoTexto = 'Sin respuestas';
    } elseif (!$tieneCorrecta) {
        $estadoColor = 'warning';
        $estadoIcon  = 'bi-exclamation-circle-fill';
        $estadoTexto = 'Sin respuesta correcta';
    } else {
        $estadoColor = 'success';
        $estadoIcon  = 'bi-check-circle-fill';
        $estadoTexto = 'Configurada';
    }
@endphp

<div class="rp-question-card {{ $loop->first ? '' : 'mt-3' }}" id="rp-card-{{ $pregunta->id }}">
    {{-- ── Header clickable ─────────────────────────────── --}}
    <div class="rp-question-header"
         data-bs-toggle="collapse"
         data-bs-target="#rp-collapse-{{ $pregunta->id }}"
         aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
         aria-controls="rp-collapse-{{ $pregunta->id }}"
         role="button">

        <div class="rp-header-left">
            {{-- Número de pregunta --}}
            <div class="rp-question-number">
                <span>P{{ $loop->iteration }}</span>
            </div>

            {{-- Contenido --}}
            <div class="rp-header-info">
                <h6 class="rp-question-text mb-1">{{ $pregunta->enunciado }}</h6>
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    {{-- Badge tipo --}}
                    <span class="badge bg-{{ $tipoColor }} bg-opacity-10 text-{{ $tipoColor }} border border-{{ $tipoColor }} border-opacity-25 px-2 py-1 rounded-pill fw-bold" style="font-size: 0.68rem;">
                        <i class="bi {{ $tipoIcon }} me-1"></i>{{ $tipoLabel }}
                    </span>
                    {{-- Badge puntos --}}
                    <span class="badge bg-light text-primary border border-primary-subtle px-2 py-1 rounded-pill fw-bold" style="font-size: 0.68rem;">
                        <i class="bi bi-star-fill me-1"></i>{{ $pregunta->puntaje }} pts
                    </span>
                    {{-- Badge conteo de respuestas --}}
                    <span class="badge bg-light text-muted border px-2 py-1 rounded-pill fw-bold" style="font-size: 0.68rem;">
                        <i class="bi bi-chat-left-dots-fill me-1"></i>{{ $totalResp }} respuesta{{ $totalResp !== 1 ? 's' : '' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="rp-header-right d-flex align-items-center gap-3">
            {{-- Indicador de estado --}}
            <span class="rp-status-pill rp-status-{{ $estadoColor }}">
                <i class="bi {{ $estadoIcon }} me-1"></i>{{ $estadoTexto }}
            </span>
            {{-- Chevron --}}
            <i class="bi bi-chevron-down rp-chevron"></i>
        </div>
    </div>

    {{-- ── Cuerpo colapsable ────────────────────────────── --}}
    <div class="collapse {{ $loop->first ? 'show' : '' }}" id="rp-collapse-{{ $pregunta->id }}">
        <div class="rp-question-body">
            {{-- Acciones contextuales --}}
            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                <p class="text-muted small mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    @if($pregunta->tipo === 'opcion_multiple')
                        Agrega opciones de respuesta y marca cuál(es) son correctas.
                    @elseif($pregunta->tipo === 'abierta')
                        Configura las palabras clave aceptadas como respuesta correcta.
                    @else
                        Genera automáticamente las opciones Verdadero y Falso.
                    @endif
                </p>
                <div>
                    @if ($pregunta->tipo === 'opcion_multiple')
                        <button class="tbl-hero-btn tbl-hero-btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#rp-modal-crear-{{ $pregunta->id }}">
                            <i class="bi bi-plus-circle-fill me-1"></i> Crear Respuesta
                        </button>
                    @elseif ($pregunta->tipo === 'abierta')
                        <button class="tbl-hero-btn tbl-hero-btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#rp-modal-clave-{{ $pregunta->id }}">
                            <i class="bi bi-key-fill me-1"></i> Respuesta Clave
                        </button>
                    @elseif ($pregunta->tipo === 'boolean')
                        @if($totalResp === 0)
                            <form method="POST"
                                  action="{{ route('respuestas.storeVerdaderoFalso', encrypt($pregunta->id)) }}"
                                  class="d-inline">
                                @csrf
                                <button type="submit" class="tbl-hero-btn tbl-hero-btn-primary">
                                    <i class="bi bi-toggle-on me-1"></i> Generar V/F
                                </button>
                            </form>
                        @else
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill fw-bold">
                                <i class="bi bi-check-circle-fill me-1"></i>Opciones generadas
                            </span>
                        @endif
                    @endif
                </div>
            </div>

            {{-- Tabla de respuestas --}}
            @include('partials.respuestas._tabla_respuestas', ['pregunta' => $pregunta])
        </div>
    </div>
</div>
