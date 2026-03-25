{{-- ============================================================
     ESTILOS – RESPUESTAS POR PREGUNTA
     ============================================================ --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,400&display=swap');

    :root {
        --color-primary:   #145da0;
        --color-primary-l: #2a81c2;
        --color-secondary: #39a6cb;
        --color-accent1:   #63becf;
        --color-accent3:   #2197bd;
        --orange-accent:   #ffa500;
        --orange-accent-d: #e59400;

        --rp-surface:      #ffffff;
        --rp-bg:           #eef4fb;
        --rp-border:       rgba(20,93,160,.10);
        --rp-border-h:     rgba(20,93,160,.28);
        --rp-primary:      var(--color-primary);
        --rp-primary-l:    var(--color-primary-l);
        --rp-primary-dim:  rgba(20,93,160,.08);
        --rp-secondary:    var(--color-secondary);
        --rp-green:        #1da462;
        --rp-green-dim:    rgba(29,164,98,.10);
        --rp-red:          #e5404f;
        --rp-red-dim:      rgba(229,64,79,.10);
        --rp-orange:       var(--orange-accent);
        --rp-orange-dim:   rgba(255,165,0,.10);
        --rp-text:         #0f2a45;
        --rp-muted:        #5a7a99;
        --rp-radius:       14px;
        --rp-font-head:    'Syne', sans-serif;
        --rp-font-body:    'DM Sans', sans-serif;
    }

    .rp-wrap { font-family: var(--rp-font-body); color: var(--rp-text); }

    /* ── Encabezado de sección ───────────────────────────── */
    .rp-section-head {
        display: flex;
        align-items: center;
        gap: .6rem;
        font-family: var(--rp-font-head);
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--rp-text);
        padding-bottom: .85rem;
        border-bottom: 2px solid var(--rp-border);
        margin-bottom: 1.5rem;
    }
    .rp-section-head i { color: var(--rp-primary); }

    /* ── Barra de tabs ───────────────────────────────────── */
    .rp-tabs-bar {
        display: flex;
        align-items: center;
        gap: .5rem;
        background: var(--rp-bg);
        border: 1px solid var(--rp-border);
        border-radius: 12px;
        padding: .5rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }

    .rp-tab-btn {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        background: transparent;
        border: 1px solid transparent;
        border-radius: 8px;
        color: var(--rp-muted);
        font-family: var(--rp-font-head);
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .04em;
        padding: .45rem .9rem;
        cursor: pointer;
        transition: background .2s, color .2s, border-color .2s, box-shadow .2s;
        white-space: nowrap;
    }
    .rp-tab-btn:hover {
        background: rgba(20,93,160,.06);
        color: var(--rp-primary);
        border-color: var(--rp-border);
    }
    .rp-tab-btn.active {
        background: var(--rp-surface);
        color: var(--rp-primary);
        border-color: var(--rp-border-h);
        box-shadow: 0 2px 8px rgba(20,93,160,.10);
    }
    .rp-tab-btn i { font-size: .8rem; }

    /* ── Panel de pregunta ───────────────────────────────── */
    .rp-panel {
        background: var(--rp-surface);
        border: 1px solid var(--rp-border);
        border-radius: var(--rp-radius);
        box-shadow: 0 4px 18px rgba(20,93,160,.05);
        overflow: hidden;
    }

    .rp-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 1.4rem 1.5rem;
        border-bottom: 1px solid var(--rp-border);
        background: linear-gradient(135deg, var(--rp-primary-dim) 0%, transparent 70%);
    }

    .rp-pregunta-text {
        font-family: var(--rp-font-head);
        font-size: .97rem;
        font-weight: 700;
        color: var(--rp-text);
        margin: 0 0 .5rem;
        line-height: 1.35;
    }

    /* Badge de tipo */
    .rp-type-badge {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        background: var(--rp-primary-dim);
        border: 1px solid var(--rp-border-h);
        border-radius: 99px;
        color: var(--rp-primary-l);
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .05em;
        text-transform: uppercase;
        padding: .2rem .75rem;
    }

    /* Botón de acción principal */
    .rp-action-btn {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        background: var(--rp-primary);
        color: #fff;
        border: none;
        border-radius: 9px;
        font-family: var(--rp-font-body);
        font-size: .82rem;
        font-weight: 600;
        padding: .55rem 1.1rem;
        cursor: pointer;
        transition: background .2s, transform .15s, box-shadow .2s;
        white-space: nowrap;
        text-decoration: none;
    }
    .rp-action-btn:hover {
        background: var(--rp-primary-l);
        transform: translateY(-1px);
        box-shadow: 0 5px 14px rgba(20,93,160,.22);
        color: #fff;
    }
    .rp-action-btn:active { transform: translateY(0); }

    /* ── Tabla ───────────────────────────────────────────── */
    .rp-table-wrap { padding: 0 1.5rem 1.5rem; }

    .rp-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1.1rem;
    }

    .rp-table thead tr {
        background: var(--rp-bg);
    }
    .rp-table thead th {
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .07em;
        text-transform: uppercase;
        color: var(--rp-muted);
        padding: .7rem 1rem;
        border-bottom: 1px solid var(--rp-border);
    }
    .rp-table thead th:first-child { border-radius: 8px 0 0 8px; }
    .rp-table thead th:last-child  { border-radius: 0 8px 8px 0; }

    .rp-table tbody tr {
        border-bottom: 1px solid var(--rp-border);
        transition: background .18s;
    }
    .rp-table tbody tr:last-child { border-bottom: none; }
    .rp-table tbody tr:hover { background: var(--rp-primary-dim); }

    .rp-table td { padding: .85rem 1rem; vertical-align: middle; font-size: .875rem; }

    .rp-row-num {
        font-family: var(--rp-font-head);
        font-size: .8rem;
        font-weight: 700;
        color: var(--rp-muted);
    }

    /* Ícono de respuesta */
    .rp-resp-icon {
        width: 36px; height: 36px;
        background: var(--rp-primary-dim);
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--rp-primary);
        font-size: .95rem;
        flex-shrink: 0;
        margin-right: .75rem;
    }
    .rp-resp-content {
        font-weight: 600;
        color: var(--rp-text);
        font-size: .88rem;
    }

    /* Badges correcta / incorrecta */
    .rp-badge {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        border-radius: 99px;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .04em;
        padding: .28rem .8rem;
        border: 1px solid transparent;
    }
    .rp-badge-correct   { background: var(--rp-green-dim); color: var(--rp-green);  border-color: rgba(29,164,98,.20); }
    .rp-badge-incorrect { background: var(--rp-bg);        color: var(--rp-muted);  border-color: var(--rp-border);    }

    /* Botones de acción de fila */
    .rp-row-actions { display: flex; gap: .35rem; justify-content: flex-end; }
    .rp-icon-btn {
        width: 32px; height: 32px;
        border: 1px solid var(--rp-border);
        border-radius: 8px;
        background: transparent;
        color: var(--rp-muted);
        font-size: .82rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: color .18s, border-color .18s, background .18s;
    }
    .rp-icon-btn:hover         { color: var(--rp-primary);  border-color: var(--rp-primary-l); background: var(--rp-primary-dim); }
    .rp-icon-btn.rp-del:hover  { color: var(--rp-red);       border-color: var(--rp-red);       background: var(--rp-red-dim);     }

    /* ── Empty state ─────────────────────────────────────── */
    .rp-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 3rem 1rem;
        gap: .6rem;
        color: var(--rp-muted);
        text-align: center;
    }
    .rp-empty-icon {
        width: 56px; height: 56px;
        background: var(--rp-bg);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
        color: var(--rp-muted);
        opacity: .5;
        margin-bottom: .25rem;
    }
    .rp-empty-title { font-weight: 700; font-size: .9rem; margin: 0; color: var(--rp-text); }
    .rp-empty-desc  { font-size: .8rem; margin: 0; }

    /* ── Modales ─────────────────────────────────────────── */
    .rp-modal .modal-content {
        border: 1px solid var(--rp-border);
        border-radius: 16px;
        font-family: var(--rp-font-body);
        box-shadow: 0 20px 60px rgba(20,93,160,.15);
        overflow: hidden;
    }
    .rp-modal .modal-header {
        background: linear-gradient(135deg, var(--rp-primary) 0%, var(--rp-primary-l) 100%);
        padding: 1.2rem 1.5rem;
        border: none;
    }
    .rp-modal .modal-title {
        font-family: var(--rp-font-head);
        font-size: .97rem;
        font-weight: 700;
        color: #fff;
        display: flex;
        align-items: center;
        gap: .5rem;
    }
    .rp-modal .modal-body   { padding: 1.5rem; background: var(--rp-surface); }
    .rp-modal .modal-footer { padding: 1rem 1.5rem; background: var(--rp-bg); border-top: 1px solid var(--rp-border); gap: .5rem; }

    /* Campos del modal */
    .rp-field { display: flex; flex-direction: column; gap: .4rem; margin-bottom: 1rem; }
    .rp-field:last-child { margin-bottom: 0; }
    .rp-label {
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--rp-muted);
    }
    .rp-input,
    .rp-select {
        background: var(--rp-bg);
        border: 1px solid var(--rp-border);
        border-radius: 10px;
        color: var(--rp-text);
        font-family: var(--rp-font-body);
        font-size: .875rem;
        padding: .65rem .9rem;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
        width: 100%;
    }
    .rp-input:focus,
    .rp-select:focus {
        border-color: var(--rp-primary-l);
        box-shadow: 0 0 0 3px rgba(20,93,160,.08);
    }
    .rp-input[readonly] { opacity: .6; cursor: not-allowed; }
    .rp-select option   { background: #fff; color: var(--rp-text); }
    .rp-helper          { font-size: .74rem; color: var(--rp-muted); display: flex; align-items: center; gap: .3rem; }

    /* Item de respuesta en modal de opción múltiple */
    .rp-answer-item {
        background: var(--rp-bg);
        border: 1px solid var(--rp-border);
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: .75rem;
        position: relative;
        transition: border-color .2s;
    }
    .rp-answer-item:hover { border-color: var(--rp-border-h); }

    /* Botón "agregar más" */
    .rp-add-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .5rem;
        width: 100%;
        border: 2px dashed var(--rp-border-h);
        border-radius: 10px;
        background: transparent;
        color: var(--rp-primary);
        font-family: var(--rp-font-body);
        font-size: .85rem;
        font-weight: 600;
        padding: .7rem;
        cursor: pointer;
        transition: background .2s, border-color .2s;
        margin-top: .25rem;
    }
    .rp-add-btn:hover { background: var(--rp-primary-dim); border-color: var(--rp-primary); }

    /* Info alert modal */
    .rp-info-alert {
        display: flex;
        align-items: flex-start;
        gap: .6rem;
        background: rgba(20,93,160,.07);
        border: 1px solid var(--rp-border-h);
        border-radius: 10px;
        padding: .85rem 1rem;
        font-size: .82rem;
        color: var(--rp-text);
        line-height: 1.5;
    }
    .rp-info-alert i { color: var(--rp-primary-l); flex-shrink: 0; margin-top: .1rem; }

    /* Botones del footer modal */
    .rp-btn-cancel {
        background: transparent;
        border: 1px solid var(--rp-border-h);
        border-radius: 9px;
        color: var(--rp-muted);
        font-family: var(--rp-font-body);
        font-size: .85rem;
        padding: .55rem 1.2rem;
        cursor: pointer;
        transition: color .18s, border-color .18s;
    }
    .rp-btn-cancel:hover { color: var(--rp-text); border-color: var(--rp-text); }

    .rp-btn-save {
        background: var(--rp-primary);
        border: none;
        border-radius: 9px;
        color: #fff;
        font-family: var(--rp-font-head);
        font-size: .85rem;
        font-weight: 700;
        padding: .55rem 1.4rem;
        cursor: pointer;
        transition: background .2s, transform .15s, box-shadow .2s;
    }
    .rp-btn-save:hover { background: var(--rp-primary-l); transform: translateY(-1px); box-shadow: 0 5px 14px rgba(20,93,160,.22); }
</style>

{{-- ============================================================
     COMPONENTE
     ============================================================ --}}
<div class="rp-wrap">

    {{-- Encabezado --}}
    <div class="rp-section-head">
        <i class="bi bi-reply-all-fill"></i>
        Respuestas por Pregunta
    </div>

    {{-- ── BARRA DE TABS ──────────────────────────────────── --}}
    <div class="rp-tabs-bar" id="rpTabsBar" role="tablist">
        @foreach ($preguntas as $pregunta)
            <button class="rp-tab-btn {{ $loop->first ? 'active' : '' }}"
                    id="rp-tab-{{ $pregunta->id }}"
                    data-bs-toggle="pill"
                    data-bs-target="#rp-pane-{{ $pregunta->id }}"
                    type="button"
                    role="tab"
                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                <i class="bi bi-patch-question"></i>
                P{{ $loop->iteration }}
            </button>
        @endforeach
    </div>

    {{-- ── PANELES DE CONTENIDO ───────────────────────────── --}}
    <div class="tab-content">
        @foreach ($preguntas as $pregunta)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                 id="rp-pane-{{ $pregunta->id }}"
                 role="tabpanel">

                <div class="rp-panel">

                    {{-- Cabecera de la pregunta --}}
                    <div class="rp-panel-header">
                        <div>
                            <p class="rp-pregunta-text">{{ $pregunta->enunciado }}</p>
                            <span class="rp-type-badge">
                                <i class="bi bi-info-circle"></i>
                                {{ ucfirst(str_replace('_', ' ', $pregunta->tipo)) }}
                            </span>
                        </div>

                        {{-- Botón de acción según tipo --}}
                        <div>
                            @if ($pregunta->tipo === 'opcion_multiple')
                                <button class="rp-action-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#rp-modal-crear-{{ $pregunta->id }}">
                                    <i class="bi bi-plus-circle-fill"></i> Crear Respuesta
                                </button>

                            @elseif ($pregunta->tipo === 'abierta')
                                <button class="rp-action-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#rp-modal-clave-{{ $pregunta->id }}">
                                    <i class="bi bi-key-fill"></i> Respuesta Clave
                                </button>

                            @elseif ($pregunta->tipo === 'boolean')
                                <form method="POST"
                                      action="{{ route('respuestas.storeVerdaderoFalso', encrypt($pregunta->id)) }}">
                                    @csrf
                                    <button type="submit" class="rp-action-btn">
                                        <i class="bi bi-toggle-on"></i> Generar V/F
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    {{-- Tabla de respuestas --}}
                    <div class="rp-table-wrap">
                        <table class="rp-table">
                            <thead>
                                <tr>
                                    <th style="width:52px;">#</th>
                                    <th>Respuesta</th>
                                    <th style="width:140px;text-align:center;">Estado</th>
                                    <th style="width:100px;text-align:right;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pregunta->respuestas as $respuesta)
                                    <tr>
                                        <td><span class="rp-row-num">{{ $loop->iteration }}</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="rp-resp-icon">
                                                    <i class="bi bi-chat-left-text"></i>
                                                </span>
                                                <span class="rp-resp-content">{{ $respuesta->contenido }}</span>
                                            </div>
                                        </td>
                                        <td style="text-align:center;">
                                            @if ($respuesta->es_correcta)
                                                <span class="rp-badge rp-badge-correct">
                                                    <i class="bi bi-check-circle-fill"></i> Correcta
                                                </span>
                                            @else
                                                <span class="rp-badge rp-badge-incorrect">
                                                    <i class="bi bi-x-circle-fill"></i> Incorrecta
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="rp-row-actions">
                                                <button class="rp-icon-btn"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#rp-modal-edit-{{ $respuesta->id }}"
                                                        title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <form method="POST"
                                                      action="{{ route('respuestas.delete', encrypt($respuesta->id)) }}"
                                                      class="rp-form-del d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="rp-icon-btn rp-del"
                                                            title="Eliminar">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">
                                            <div class="rp-empty">
                                                <div class="rp-empty-icon">
                                                    <i class="bi bi-inbox"></i>
                                                </div>
                                                <p class="rp-empty-title">Sin respuestas registradas</p>
                                                <p class="rp-empty-desc">Comienza agregando opciones para esta pregunta.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>{{-- /rp-panel --}}
            </div>
        @endforeach
    </div>

</div>{{-- /rp-wrap --}}


{{-- ============================================================
     MODALES
     ============================================================ --}}
@push('modals')

    {{-- ── MODALES: EDITAR RESPUESTA ──────────────────────── --}}
    @foreach ($preguntas as $pregunta)
        @foreach ($pregunta->respuestas as $respuesta)
            <div class="modal fade rp-modal"
                 id="rp-modal-edit-{{ $respuesta->id }}"
                 tabindex="-1"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form method="POST"
                              action="{{ route('respuestas.update', encrypt($respuesta->id)) }}">
                            @csrf
                            @method('PUT')

                            <div class="modal-header">
                                <span class="modal-title">
                                    <i class="bi bi-pencil-square"></i> Editar Respuesta
                                </span>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <div class="rp-field">
                                    <label class="rp-label">Contenido de la respuesta</label>
                                    <input type="text"
                                           name="contenido"
                                           class="rp-input"
                                           value="{{ old('contenido', $respuesta->contenido) }}"
                                           @if ($pregunta->tipo === 'boolean') readonly @endif
                                           required>
                                    @if ($pregunta->tipo === 'boolean')
                                        <span class="rp-helper">
                                            <i class="bi bi-info-circle"></i>
                                            Los enunciados Verdadero/Falso son fijos.
                                        </span>
                                    @endif
                                </div>

                                @if ($pregunta->tipo === 'opcion_multiple' || $pregunta->tipo === 'boolean')
                                    <div class="rp-field">
                                        <label class="rp-label">¿Es correcta?</label>
                                        <select name="es_correcta" class="rp-select" required>
                                            <option value="1" {{ old('es_correcta', $respuesta->es_correcta) ? 'selected' : '' }}>
                                                ✓ Sí, es correcta
                                            </option>
                                            <option value="0" {{ !old('es_correcta', $respuesta->es_correcta) ? 'selected' : '' }}>
                                                ✗ No, es incorrecta
                                            </option>
                                        </select>
                                    </div>

                                @elseif ($pregunta->tipo === 'abierta')
                                    <input type="hidden" name="es_correcta" value="1">
                                    <div class="rp-info-alert">
                                        <i class="bi bi-info-circle-fill"></i>
                                        Las respuestas clave de preguntas abiertas siempre se consideran correctas.
                                    </div>
                                @endif
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="rp-btn-cancel" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="rp-btn-save">
                                    <i class="bi bi-check-lg me-1"></i> Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach

    {{-- ── MODALES: CREAR OPCIÓN MÚLTIPLE ────────────────── --}}
    @foreach ($preguntas as $pregunta)
        @if ($pregunta->tipo === 'opcion_multiple')
            <div class="modal fade rp-modal"
                 id="rp-modal-crear-{{ $pregunta->id }}"
                 tabindex="-1"
                 aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <form method="POST"
                              action="{{ route('respuestas.storeMultiple', encrypt($pregunta->id)) }}">
                            @csrf

                            <div class="modal-header">
                                <span class="modal-title">
                                    <i class="bi bi-plus-circle-fill"></i> Agregar Respuestas — Opción Múltiple
                                </span>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <div id="rp-container-{{ $pregunta->id }}">
                                    @for ($i = 0; $i < 2; $i++)
                                        <div class="rp-answer-item">
                                            <div class="row g-3 align-items-end">
                                                <div class="col-md-8">
                                                    <div class="rp-field mb-0">
                                                        <label class="rp-label">Respuesta {{ $i + 1 }}</label>
                                                        <input type="text"
                                                               name="respuestas[{{ $i }}][contenido]"
                                                               class="rp-input"
                                                               placeholder="Escriba la respuesta…"
                                                               required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="rp-field mb-0">
                                                        <label class="rp-label">¿Correcta?</label>
                                                        <select name="respuestas[{{ $i }}][es_correcta]"
                                                                class="rp-select"
                                                                required>
                                                            <option value="">Seleccione…</option>
                                                            <option value="1">✓ Sí</option>
                                                            <option value="0">✗ No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>

                                <button type="button"
                                        class="rp-add-btn"
                                        id="rp-add-{{ $pregunta->id }}">
                                    <i class="bi bi-plus-circle"></i> Agregar Más Respuestas
                                </button>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="rp-btn-cancel" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="rp-btn-save">
                                    <i class="bi bi-check-lg me-1"></i> Guardar Respuestas
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    {{-- ── MODALES: RESPUESTA CLAVE (ABIERTA) ────────────── --}}
    @foreach ($preguntas as $pregunta)
        @if ($pregunta->tipo === 'abierta')
            <div class="modal fade rp-modal"
                 id="rp-modal-clave-{{ $pregunta->id }}"
                 tabindex="-1"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form method="POST"
                              action="{{ route('respuestas.storeRespuestasClave', encrypt($pregunta->id)) }}">
                            @csrf

                            <div class="modal-header">
                                <span class="modal-title">
                                    <i class="bi bi-key-fill"></i> Respuesta Clave — Pregunta Abierta
                                </span>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <div id="rp-container-clave-{{ $pregunta->id }}">
                                    <div class="rp-field">
                                        <label class="rp-label">Respuesta Clave 1</label>
                                        <input type="text"
                                               name="respuestas[0][contenido]"
                                               class="rp-input"
                                               placeholder="Escriba una respuesta clave…"
                                               required>
                                        <input type="hidden" name="respuestas[0][es_correcta]" value="1">
                                        <span class="rp-helper">
                                            <i class="bi bi-info-circle"></i> Posible respuesta correcta aceptada.
                                        </span>
                                    </div>
                                </div>

                                <button type="button"
                                        class="rp-add-btn"
                                        id="rp-add-clave-{{ $pregunta->id }}">
                                    <i class="bi bi-plus-circle"></i> Agregar Otra Clave
                                </button>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="rp-btn-cancel" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="rp-btn-save">
                                    <i class="bi bi-check-lg me-1"></i> Guardar Respuestas
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

@endpush


{{-- ============================================================
     JAVASCRIPT
     ============================================================ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Confirmación de eliminación ─────────────────── */
    document.querySelectorAll('.rp-form-del').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const self = this;
            Swal.fire({
                title: '¿Eliminar respuesta?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e5404f',
                cancelButtonColor:  '#5a7a99',
                confirmButtonText:  'Sí, eliminar',
                cancelButtonText:   'Cancelar',
                reverseButtons: true,
                borderRadius: '14px'
            }).then(r => { if (r.isConfirmed) self.submit(); });
        });
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
                div.className = 'rp-answer-item';
                div.innerHTML = `
                    <button type="button"
                            class="rp-icon-btn rp-del position-absolute"
                            style="top:.6rem;right:.6rem;"
                            onclick="this.closest('.rp-answer-item').remove()">
                        <i class="bi bi-x-lg"></i>
                    </button>
                    <div class="row g-3 align-items-end">
                        <div class="col-md-8">
                            <div class="rp-field mb-0">
                                <label class="rp-label">Respuesta ${idx + 1}</label>
                                <input type="text"
                                       name="respuestas[${idx}][contenido]"
                                       class="rp-input"
                                       placeholder="Escriba la respuesta…"
                                       required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="rp-field mb-0">
                                <label class="rp-label">¿Correcta?</label>
                                <select name="respuestas[${idx}][es_correcta]" class="rp-select" required>
                                    <option value="">Seleccione…</option>
                                    <option value="1">✓ Sí</option>
                                    <option value="0">✗ No</option>
                                </select>
                            </div>
                        </div>
                    </div>`;
                div.style.position = 'relative';
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
                div.className = 'rp-field position-relative';
                div.innerHTML = `
                    <button type="button"
                            class="rp-icon-btn rp-del position-absolute"
                            style="top:0;right:0;"
                            onclick="this.closest('.rp-field').remove()">
                        <i class="bi bi-x-lg"></i>
                    </button>
                    <label class="rp-label">Respuesta Clave ${idx + 1}</label>
                    <input type="text"
                           name="respuestas[${idx}][contenido]"
                           class="rp-input"
                           placeholder="Escriba una respuesta clave…"
                           required>
                    <input type="hidden" name="respuestas[${idx}][es_correcta]" value="1">`;
                container.appendChild(div);
                idx++;
            });
        })();
        @endif
    @endforeach

});
</script>
