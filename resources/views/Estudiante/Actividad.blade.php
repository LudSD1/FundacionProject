@section('content')

{{-- ============================================================
     ESTILOS – DETALLE DE ACTIVIDAD
     ============================================================ --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,400&display=swap');

    /* ── Paleta del proyecto ─────────────────────────────── */
    :root {
        --color-primary:   #145da0;
        --color-primary-l: #2a81c2;
        --color-secondary: #39a6cb;
        --color-accent1:   #63becf;
        --color-accent3:   #2197bd;
        --orange-accent:   #ffa500;
        --orange-accent-d: #e59400;

        /* Tokens de módulo */
        --da-bg:           #eef4fb;
        --da-surface:      #ffffff;
        --da-border:       rgba(20,93,160,.10);
        --da-border-h:     rgba(20,93,160,.25);
        --da-primary:      var(--color-primary);
        --da-primary-l:    var(--color-primary-l);
        --da-primary-dim:  rgba(20,93,160,.08);
        --da-secondary:    var(--color-secondary);
        --da-accent:       var(--color-accent1);
        --da-orange:       var(--orange-accent);
        --da-orange-d:     var(--orange-accent-d);
        --da-orange-dim:   rgba(255,165,0,.10);
        --da-green:        #1da462;
        --da-green-dim:    rgba(29,164,98,.10);
        --da-red:          #e5404f;
        --da-red-dim:      rgba(229,64,79,.10);
        --da-text:         #0f2a45;
        --da-muted:        #5a7a99;
        --da-radius:       14px;
        --da-font-head:    'Syne', sans-serif;
        --da-font-body:    'DM Sans', sans-serif;
    }

    /* ── Reset de módulo ─────────────────────────────────── */
    .da-wrap * { box-sizing: border-box; }
    .da-wrap { font-family: var(--da-font-body); color: var(--da-text); background: var(--da-bg); min-height: 100vh; }

    /* ── HERO / HEADER ───────────────────────────────────── */
    .da-hero {
        background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-l) 55%, var(--color-secondary) 100%);
        padding: 2.5rem 0 4rem;
        position: relative;
        overflow: hidden;
    }
    .da-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(circle at 80% 20%, rgba(99,190,207,.25) 0%, transparent 55%),
            radial-gradient(circle at 10% 90%, rgba(255,165,0,.12) 0%, transparent 45%);
        pointer-events: none;
    }
    /* Patrón de puntos sutil */
    .da-hero::after {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255,255,255,.08) 1px, transparent 1px);
        background-size: 22px 22px;
        pointer-events: none;
    }

    .da-hero-inner { position: relative; z-index: 1; padding: 0 1.5rem; }

    .da-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.25);
        border-radius: 99px;
        color: rgba(255,255,255,.9);
        font-size: .75rem;
        font-weight: 600;
        letter-spacing: .06em;
        text-transform: uppercase;
        padding: .3rem .9rem;
        margin-bottom: 1rem;
        backdrop-filter: blur(6px);
    }

    .da-title {
        font-family: var(--da-font-head);
        font-size: clamp(1.5rem, 3vw, 2.2rem);
        font-weight: 800;
        color: #fff;
        margin: 0 0 .75rem;
        line-height: 1.2;
    }

    .da-subtitle {
        color: rgba(255,255,255,.75);
        font-size: .88rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: .35rem;
    }

    .da-back-btn {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.30);
        border-radius: 10px;
        color: #fff;
        font-family: var(--da-font-body);
        font-size: .85rem;
        font-weight: 500;
        padding: .6rem 1.15rem;
        text-decoration: none;
        transition: background .2s, transform .15s;
        backdrop-filter: blur(6px);
        white-space: nowrap;
    }
    .da-back-btn:hover { background: rgba(255,255,255,.25); transform: translateY(-1px); color: #fff; }

    /* ── Overlap card ────────────────────────────────────── */
    .da-overlap {
        margin-top: -2.5rem;
        position: relative;
        z-index: 10;
        padding: 0 1.5rem 3rem;
    }

    /* ── Tarjetas ────────────────────────────────────────── */
    .da-card {
        background: var(--da-surface);
        border: 1px solid var(--da-border);
        border-radius: var(--da-radius);
        box-shadow: 0 4px 20px rgba(20,93,160,.06);
        margin-bottom: 1.25rem;
        overflow: hidden;
    }
    .da-card-body { padding: 1.5rem; }

    /* Variantes de tarjeta */
    .da-card-success { border-left: 4px solid var(--da-green); }
    .da-card-locked  { background: linear-gradient(135deg, #f5f7fa, #eef4fb); }

    /* ── Sección title ───────────────────────────────────── */
    .da-section-title {
        font-family: var(--da-font-head);
        font-size: 1rem;
        font-weight: 700;
        color: var(--da-text);
        display: flex;
        align-items: center;
        gap: .5rem;
        margin: 0 0 1.1rem;
    }
    .da-section-title i { color: var(--da-primary); font-size: 1.05rem; }

    /* ── Info boxes (habilitación / vencimiento) ─────────── */
    .da-info-box {
        background: var(--da-primary-dim);
        border: 1px solid var(--da-border);
        border-radius: 10px;
        padding: .9rem 1rem;
    }
    .da-info-box-label {
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--da-muted);
        margin-bottom: .25rem;
        display: flex;
        align-items: center;
        gap: .3rem;
    }
    .da-info-box-value {
        font-size: .92rem;
        font-weight: 600;
        color: var(--da-text);
    }

    /* ── Alerta de ponderación ───────────────────────────── */
    .da-alert-points {
        display: flex;
        align-items: center;
        gap: .75rem;
        background: var(--da-orange-dim);
        border: 1px solid rgba(255,165,0,.20);
        border-radius: 10px;
        padding: .9rem 1.1rem;
        margin-top: 1.25rem;
        font-size: .875rem;
        color: var(--da-text);
    }
    .da-alert-points i { color: var(--da-orange-d); font-size: 1.1rem; flex-shrink: 0; }
    .da-alert-points strong { color: var(--da-orange-d); }

    /* ── Badge de calificación ───────────────────────────── */
    .da-score-badge {
        display: inline-flex;
        align-items: center;
        background: var(--da-green-dim);
        border: 1px solid rgba(29,164,98,.20);
        color: var(--da-green);
        font-family: var(--da-font-head);
        font-size: 1.1rem;
        font-weight: 800;
        border-radius: 10px;
        padding: .35rem .9rem;
    }

    .da-feedback-box {
        background: var(--da-primary-dim);
        border-left: 3px solid var(--da-primary-l);
        border-radius: 0 8px 8px 0;
        padding: .85rem 1rem;
        font-style: italic;
        color: var(--da-text);
        font-size: .9rem;
        line-height: 1.55;
    }

    /* ── Entrega individual ──────────────────────────────── */
    .da-entrega-card {
        background: var(--da-bg);
        border: 1px solid var(--da-border);
        border-radius: 10px;
        padding: 1rem 1.1rem;
        margin-bottom: .75rem;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: .75rem;
        transition: border-color .2s, box-shadow .2s;
    }
    .da-entrega-card:hover { border-color: var(--da-border-h); box-shadow: 0 4px 14px rgba(20,93,160,.08); }

    .da-entrega-time {
        font-size: .75rem;
        color: var(--da-muted);
        display: flex;
        align-items: center;
        gap: .3rem;
        margin-bottom: .2rem;
    }
    .da-entrega-comment { font-size: .88rem; font-weight: 500; color: var(--da-text); margin: 0; }

    /* ── Botones ─────────────────────────────────────────── */
    .da-btn {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        border-radius: 8px;
        font-family: var(--da-font-body);
        font-size: .82rem;
        font-weight: 500;
        padding: .5rem 1rem;
        text-decoration: none;
        cursor: pointer;
        transition: background .2s, transform .15s, box-shadow .2s, color .2s, border-color .2s;
        border: 1px solid transparent;
        white-space: nowrap;
    }
    .da-btn-primary {
        background: var(--da-primary);
        color: #fff;
        border-color: var(--da-primary);
    }
    .da-btn-primary:hover { background: var(--da-primary-l); color: #fff; transform: translateY(-1px); box-shadow: 0 6px 16px rgba(20,93,160,.22); }

    .da-btn-outline {
        background: transparent;
        color: var(--da-primary);
        border-color: var(--da-border-h);
    }
    .da-btn-outline:hover { background: var(--da-primary-dim); border-color: var(--da-primary); color: var(--da-primary); }

    .da-btn-danger {
        background: transparent;
        color: var(--da-red);
        border-color: rgba(229,64,79,.25);
    }
    .da-btn-danger:hover { background: var(--da-red-dim); border-color: var(--da-red); }

    .da-btn-submit {
        background: linear-gradient(135deg, var(--da-primary) 0%, var(--da-primary-l) 100%);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-family: var(--da-font-head);
        font-size: .9rem;
        font-weight: 700;
        padding: .85rem 1.5rem;
        width: 100%;
        cursor: pointer;
        transition: opacity .2s, transform .15s, box-shadow .2s;
        letter-spacing: .03em;
    }
    .da-btn-submit:hover { opacity: .9; transform: translateY(-2px); box-shadow: 0 10px 28px rgba(20,93,160,.30); }
    .da-btn-submit:active { transform: translateY(0); }

    /* ── Empty state ─────────────────────────────────────── */
    .da-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 2.5rem 1rem;
        gap: .6rem;
        color: var(--da-muted);
        text-align: center;
    }
    .da-empty i { font-size: 2.2rem; opacity: .3; }
    .da-empty p { margin: 0; font-size: .88rem; }

    /* ── Locked state ────────────────────────────────────── */
    .da-locked {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 2.5rem 1rem;
        gap: .6rem;
        text-align: center;
        color: var(--da-muted);
    }
    .da-locked-icon {
        width: 64px; height: 64px;
        background: var(--da-primary-dim);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem;
        color: var(--da-primary);
        margin-bottom: .5rem;
    }
    .da-locked-title { font-family: var(--da-font-head); font-size: 1rem; font-weight: 700; color: var(--da-text); margin: 0; }
    .da-locked-desc  { font-size: .82rem; color: var(--da-muted); margin: 0; }

    /* ── Upload dropzone ─────────────────────────────────── */
    .da-dropzone {
        position: relative;
        border: 2px dashed var(--da-border-h);
        border-radius: 12px;
        background: var(--da-primary-dim);
        padding: 1.75rem 1rem;
        text-align: center;
        transition: border-color .2s, background .2s;
        cursor: pointer;
    }
    .da-dropzone:hover { border-color: var(--da-primary); background: rgba(20,93,160,.12); }
    .da-dropzone input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
    }
    .da-dropzone-icon { font-size: 2rem; color: var(--da-primary); margin-bottom: .5rem; display: block; }
    .da-dropzone-text { font-size: .82rem; color: var(--da-muted); margin: 0; }
    .da-dropzone-success { color: var(--da-green); }
    .da-dropzone-success .da-dropzone-icon { color: var(--da-green); }

    /* ── Form fields (sidebar) ───────────────────────────── */
    .da-form-label {
        font-size: .75rem;
        font-weight: 700;
        letter-spacing: .05em;
        text-transform: uppercase;
        color: var(--da-muted);
        margin-bottom: .4rem;
        display: block;
    }
    .da-textarea {
        width: 100%;
        background: var(--da-bg);
        border: 1px solid var(--da-border);
        border-radius: 10px;
        color: var(--da-text);
        font-family: var(--da-font-body);
        font-size: .875rem;
        padding: .7rem .9rem;
        resize: vertical;
        min-height: 90px;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }
    .da-textarea:focus {
        border-color: var(--da-primary-l);
        box-shadow: 0 0 0 3px rgba(20,93,160,.08);
    }
    .da-textarea::placeholder { color: var(--da-muted); }

    /* ── Sticky sidebar card ─────────────────────────────── */
    .da-sidebar-card {
        position: sticky;
        top: 20px;
        z-index: 100;
    }

    /* ── Animaciones de entrada ──────────────────────────── */
    @keyframes da-fade-up {
        from { opacity: 0; transform: translateY(18px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .da-anim { animation: da-fade-up .45s ease both; }
    .da-anim-1 { animation-delay: .05s; }
    .da-anim-2 { animation-delay: .12s; }
    .da-anim-3 { animation-delay: .20s; }
    .da-anim-4 { animation-delay: .28s; }
</style>

{{-- ============================================================
     ESTRUCTURA PRINCIPAL
     ============================================================ --}}
<div class="da-wrap">

    {{-- ── HERO ──────────────────────────────────────────── --}}
    <div class="da-hero">
        <div class="da-hero-inner">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="da-eyebrow">
                            <i class="bi bi-journal-bookmark"></i>
                            {{ $actividades->tipoActividad->nombre }}
                        </div>
                        <h1 class="da-title">{{ $actividades->titulo_tarea }}</h1>
                        <p class="da-subtitle">
                            <i class="bi bi-calendar3"></i>
                            Límite:
                            {{ $actividades->fecha_limite
                                ? \Carbon\Carbon::parse($actividades->fecha_limite)->format('d/m/Y H:i')
                                : 'Sin fecha límite' }}
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <a href="{{ route('Curso', $actividades->subtema->tema->curso->codigoCurso) }}"
                           class="da-back-btn">
                            <i class="bi bi-arrow-left-circle"></i> Volver al Curso
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── CONTENIDO (overlap) ────────────────────────────── --}}
    <div class="da-overlap">
        <div class="container-fluid">
            <div class="row g-4">

                {{-- ══════════════════════════════════════
                     COLUMNA PRINCIPAL
                     ══════════════════════════════════════ --}}
                <div class="col-lg-8">

                    {{-- Descripción + fechas + ponderación --}}
                    <div class="da-card da-anim da-anim-1">
                        <div class="da-card-body">
                            <h4 class="da-section-title">
                                <i class="bi bi-info-circle-fill"></i> Descripción
                            </h4>
                            <p style="color:var(--da-muted);line-height:1.65;font-size:.9rem;margin:0;">
                                {{ $actividades->descripcion }}
                            </p>

                            <div class="row mt-4 g-3">
                                <div class="col-sm-6">
                                    <div class="da-info-box">
                                        <div class="da-info-box-label">
                                            <i class="bi bi-calendar-check"></i> Habilitación
                                        </div>
                                        <div class="da-info-box-value">
                                            {{ \Carbon\Carbon::parse($actividades->fecha_inicio)->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="da-info-box">
                                        <div class="da-info-box-label">
                                            <i class="bi bi-calendar-x"></i> Vencimiento
                                        </div>
                                        <div class="da-info-box-value">
                                            {{ $actividades->fecha_limite
                                                ? \Carbon\Carbon::parse($actividades->fecha_limite)->format('d/m/Y H:i')
                                                : 'Sin límite' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="da-alert-points">
                                <i class="bi bi-award-fill"></i>
                                <span>
                                    <strong>Ponderación:</strong> Esta actividad vale hasta
                                    <strong>{{ $actividades->getPuntajeMaximoAttribute() }} puntos</strong>.
                                </span>
                            </div>
                        </div>
                    </div>

                    @if (auth()->user()->hasRole('Estudiante'))

                        {{-- ── Calificación ────────────────────────────── --}}
                        @forelse ($notas as $nota)
                            @if ($nota->inscripcion->estudiante_id == auth()->user()->id && $nota->actividad_id == $actividades->id)
                                <div class="da-card da-card-success da-anim da-anim-2">
                                    <div class="da-card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                            <h4 class="da-section-title mb-0" style="color:var(--da-green);">
                                                <i class="bi bi-check-circle-fill" style="color:var(--da-green);"></i>
                                                Actividad Calificada
                                            </h4>
                                            <div class="da-score-badge">
                                                {{ $nota->nota }} / {{ $actividades->getPuntajeMaximoAttribute() }}
                                            </div>
                                        </div>
                                        <div class="da-form-label" style="margin-bottom:.5rem;">Retroalimentación del docente</div>
                                        <div class="da-feedback-box">
                                            "{{ $nota->retroalimentacion }}"
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                        @endforelse

                        {{-- ── Historial de entregas ────────────────────── --}}
                        <div class="da-anim da-anim-3">
                            <h4 class="da-section-title" style="margin-bottom:1rem;">
                                <i class="bi bi-cloud-upload-fill"></i> Tus Entregas
                            </h4>

                            @php $hasEntregas = false; @endphp
                            @foreach ($actividades->entregas as $entrega)
                                @if ($entrega->user_id == auth()->user()->id)
                                    @php $hasEntregas = true; @endphp
                                    <div class="da-entrega-card">
                                        <div>
                                            <div class="da-entrega-time">
                                                <i class="bi bi-clock"></i>
                                                {{ \Carbon\Carbon::parse($entrega->fecha_entrega)->format('d/m/Y H:i') }}
                                            </div>
                                            <p class="da-entrega-comment">
                                                {{ $entrega->comentario ?? 'Sin comentario' }}
                                            </p>
                                        </div>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <a href="{{ asset('storage/' . $entrega->archivo) }}"
                                               target="_blank"
                                               class="da-btn da-btn-outline">
                                                <i class="bi bi-file-earmark-text"></i> Ver Archivo
                                            </a>
                                            @if (!$actividades->fecha_limite || now() <= \Carbon\Carbon::parse($actividades->fecha_limite))
                                                <a href="{{ route('quitarEntrega', encrypt($entrega->id)) }}"
                                                   class="da-btn da-btn-danger">
                                                    <i class="bi bi-trash"></i> Quitar
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            @if (!$hasEntregas)
                                <div class="da-card">
                                    <div class="da-empty">
                                        <i class="bi bi-inbox"></i>
                                        <p>Aún no has realizado ninguna entrega para esta actividad.</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                    @endif
                </div>

                {{-- ══════════════════════════════════════
                     COLUMNA LATERAL – FORMULARIO
                     ══════════════════════════════════════ --}}
                <div class="col-lg-4">
                    @if (auth()->user()->hasRole('Estudiante'))

                        @php
                            $cursoCerrado = $actividades->subtema->tema->curso->fecha_fin
                                && \Carbon\Carbon::now() > \Carbon\Carbon::parse($actividades->subtema->tema->curso->fecha_fin);
                            $actividadCerrada = $actividades->fecha_limite
                                && \Carbon\Carbon::now() > \Carbon\Carbon::parse($actividades->fecha_limite);
                        @endphp

                        @if ($cursoCerrado || $actividadCerrada)

                            {{-- Estado bloqueado --}}
                            <div class="da-card da-card-locked da-anim da-anim-2">
                                <div class="da-card-body">
                                    <div class="da-locked">
                                        <div class="da-locked-icon">
                                            <i class="bi bi-lock-fill"></i>
                                        </div>
                                        <p class="da-locked-title">Actividad Finalizada</p>
                                        <p class="da-locked-desc">Ya no se reciben más entregas para este recurso.</p>
                                    </div>
                                </div>
                            </div>

                        @else

                            {{-- Formulario de entrega --}}
                            <div class="da-sidebar-card da-anim da-anim-2">
                                <div class="da-card">
                                    <div class="da-card-body">
                                        <h5 class="da-section-title">
                                            <i class="bi bi-plus-circle-fill"></i> Nueva Entrega
                                        </h5>

                                        <form action="{{ route('subirArchivo', encrypt($actividades->id)) }}"
                                              method="POST"
                                              enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="actividad_id" value="{{ $actividades->id }}">
                                            <input type="hidden" name="user_id"      value="{{ auth()->user()->id }}">

                                            {{-- Dropzone --}}
                                            <div class="mb-3">
                                                <label class="da-form-label">Seleccionar Archivo</label>
                                                <div class="da-dropzone" id="daDropzone">
                                                    <input type="file"
                                                           name="archivo"
                                                           id="archivo"
                                                           required
                                                           onchange="daUpdateFile(this)">
                                                    <div id="daDropDefault">
                                                        <span class="da-dropzone-icon"><i class="bi bi-cloud-arrow-up"></i></span>
                                                        <p class="da-dropzone-text">Haz clic o arrastra un archivo aquí</p>
                                                    </div>
                                                    <div id="daDropSelected" class="d-none da-dropzone-success">
                                                        <span class="da-dropzone-icon"><i class="bi bi-file-check"></i></span>
                                                        <p class="da-dropzone-text fw-bold" id="daFileName"></p>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Comentario --}}
                                            <div class="mb-4">
                                                <label class="da-form-label" for="comentario">Comentario (opcional)</label>
                                                <textarea name="comentario"
                                                          id="comentario"
                                                          class="da-textarea"
                                                          rows="3"
                                                          placeholder="Añade una nota a tu entrega…"></textarea>
                                            </div>

                                            <button type="submit" class="da-btn-submit">
                                                <i class="bi bi-send-fill me-2"></i> Enviar Tarea
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        @endif
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     JAVASCRIPT
     ============================================================ --}}
<script>
function daUpdateFile(input) {
    const def      = document.getElementById('daDropDefault');
    const selected = document.getElementById('daDropSelected');
    const name     = document.getElementById('daFileName');
    const zone     = document.getElementById('daDropzone');

    if (input.files && input.files[0]) {
        def.classList.add('d-none');
        selected.classList.remove('d-none');
        name.textContent = input.files[0].name;
        zone.style.borderColor = 'var(--da-green)';
        zone.style.background  = 'rgba(29,164,98,.06)';
    } else {
        def.classList.remove('d-none');
        selected.classList.add('d-none');
        zone.style.borderColor = '';
        zone.style.background  = '';
    }
}
</script>

@endsection

@include('layout')
