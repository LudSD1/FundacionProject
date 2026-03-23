<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Estudiantes</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- FIX 7: rawgit.com está muerto → cdnjs funciona --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap');

        :root {
            --ls-blue:   #145da0;
            --ls-blue-l: #2a81c2;
            --ls-orange: #ffa500;
            --ls-cyan:   #63becf;
        }

        * { box-sizing: border-box; }

        body {
            background:  #a1a3a7;
            color:       #000;
            font-family: 'Montserrat', sans-serif;
            font-size:   .9rem;
        }

        /* ── Barra acciones ── */
        .ls-actions {
            background:    #fff;
            border-radius: 10px;
            padding:       .85rem 1.2rem;
            margin-bottom: 1rem;
            display:       flex;
            align-items:   center;
            justify-content: space-between;
            gap:           .75rem;
            flex-wrap:     wrap;
            box-shadow:    0 2px 8px rgba(0,0,0,.10);
        }
        .ls-actions-left  { display: flex; align-items: center; gap: .6rem; flex-wrap: wrap; }
        .ls-actions-right { display: flex; align-items: center; gap: .5rem; }

        /* FIX 5: colores del sistema */
        .ls-btn {
            display:       inline-flex;
            align-items:   center;
            gap:           .4rem;
            padding:       .48rem 1.1rem;
            border-radius: 50px;
            font-size:     .84rem;
            font-weight:   600;
            cursor:        pointer;
            border:        none;
            text-decoration: none;
            font-family:   inherit;
            transition:    all .22s ease;
            white-space:   nowrap;
        }
        .ls-btn:hover { transform: translateY(-2px); }

        .ls-btn-back {
            background: #fff;
            color:      #64748b;
            border:     1.5px solid #d1dce8;
        }
        .ls-btn-back:hover { border-color: var(--ls-blue); color: var(--ls-blue); transform: none; }

        .ls-btn-pdf {
            background: linear-gradient(135deg, var(--ls-blue), var(--ls-blue-l));
            color:      #fff;
            box-shadow: 0 4px 12px rgba(20,93,160,.25);
        }
        .ls-btn-pdf:hover { box-shadow: 0 6px 18px rgba(20,93,160,.35); color: #fff; }

        .ls-btn-preview {
            background: linear-gradient(135deg, var(--ls-orange), #e59400);
            color:      #fff;
            box-shadow: 0 4px 12px rgba(255,165,0,.22);
        }
        .ls-btn-preview:hover { box-shadow: 0 6px 18px rgba(255,165,0,.32); color: #fff; }

        .ls-format-label {
            font-size:   .78rem;
            color:       #64748b;
            font-weight: 600;
        }
        .ls-format-select {
            padding:          .38rem 1.8rem .38rem .7rem;
            border:           1.5px solid #d1dce8;
            border-radius:    50px;
            font-size:        .8rem;
            font-family:      inherit;
            color:            #374151;
            background:       #fff;
            outline:          none;
            cursor:           pointer;
            appearance:       none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat:   no-repeat;
            background-position: right .6rem center;
        }

        /* Indicador de carga */
        .ls-loading {
            position:  fixed;
            inset:     0;
            background: rgba(0,0,0,.45);
            z-index:   9999;
            display:   flex;
            align-items: center;
            justify-content: center;
        }
        .ls-loading-card {
            background:    #fff;
            border-radius: 14px;
            padding:       1.4rem 2rem;
            display:       flex;
            align-items:   center;
            gap:           1rem;
            font-weight:   600;
        }
        .ls-spinner {
            width:  26px; height: 26px;
            border: 3px solid #e2eaf4;
            border-top-color: var(--ls-blue);
            border-radius: 50%;
            animation: lsSpin .7s linear infinite;
        }
        @keyframes lsSpin { to { transform: rotate(360deg); } }

        /* ══════════════════════════════════════
           DOCUMENTO (se convierte en PDF)
        ══════════════════════════════════════ */
        .ls-doc {
            background:    #fff;
            border-radius: 12px;
            overflow:      hidden;
            margin-bottom: 20px;
            box-shadow:    0 4px 20px rgba(0,0,0,.12);
        }

        /* ── Header azul sólido mejorado ── */
        .ls-header {
            background: linear-gradient(135deg, #0d2244 0%, #145da0 55%, #2a81c2 100%);
            padding:    0;
            position:   relative;
            overflow:   hidden;
        }

        /* Patrón de puntos decorativo */
        .ls-header::before {
            content:          '';
            position:         absolute;
            inset:            0;
            background-image: radial-gradient(rgba(255,255,255,.07) 1px, transparent 1px);
            background-size:  20px 20px;
            pointer-events:   none;
        }

        /* Acento naranja esquina inferior derecha */
        .ls-header::after {
            content:       '';
            position:      absolute;
            bottom:        -40px; right: -40px;
            width:         180px; height: 180px;
            border-radius: 50%;
            background:    radial-gradient(circle, rgba(255,165,0,.18), transparent 70%);
            pointer-events: none;
        }

        .ls-header-inner {
            position:        relative;
            z-index:         1;
            display:         flex;
            align-items:     center;
            justify-content: space-between;
            padding:         16px 24px;
            gap:             12px;
        }

        /* Logo izquierdo: sobre fondo azul, se ve bien */
        .ls-logo-left img {
            height: 72px;
            width:  auto;
            filter: brightness(0) invert(1); /* blanco si el logo es oscuro */
            /* Quitar filter si el logo ya es claro */
        }

        /* Logo derecho: fondo blanco pill para legibilidad */
        .ls-logo-right {
            background:    rgba(255,255,255,.92);
            border-radius: 10px;
            padding:       8px 14px;
            display:       flex;
            align-items:   center;
        }
        .ls-logo-right img { height: 38px; width: auto; }

        /* Título dentro del header */
        .ls-header-center {
            flex:       1;
            text-align: center;
        }
        .ls-header-title {
            font-size:     1.15rem;
            font-weight:   800;
            color:         #fff;
            margin:        0 0 .2rem;
            letter-spacing: -.01em;
            text-shadow:   0 1px 4px rgba(0,0,0,.2);
        }
        .ls-header-sub {
            font-size:  .72rem;
            color:      rgba(255,255,255,.72);
            font-weight: 500;
            margin:     0;
        }

        /* ── Cuerpo del documento ── */
        .ls-body { padding: 22px 24px 20px; }

        /* Título principal */
        .ls-title {
            font-size:     1.5rem;
            font-weight:   800;
            text-align:    center;
            color:         #0f172a;
            margin-bottom: .25rem;
            letter-spacing: -.02em;
        }
        .ls-title-line {
            width:         60px;
            height:        3px;
            background:    linear-gradient(90deg, var(--ls-blue), var(--ls-orange));
            border-radius: 99px;
            margin:        0 auto 1.4rem;
        }

        /* Info del curso — tarjetas en lugar de párrafos sueltos */
        .ls-info-grid {
            display:               grid;
            grid-template-columns: 1fr 1fr;
            gap:                   .6rem;
            margin-bottom:         1.4rem;
        }
        .ls-info-item {
            display:       flex;
            align-items:   flex-start;
            gap:           .6rem;
            background:    #f4f8fc;
            border-left:   3px solid var(--ls-blue);
            border-radius: 0 8px 8px 0;
            padding:       .55rem .75rem;
        }
        .ls-info-icon {
            width:           26px; height: 26px;
            border-radius:   50%;
            background:      rgba(20,93,160,.10);
            color:           var(--ls-blue);
            display:         flex;
            align-items:     center;
            justify-content: center;
            font-size:       .72rem;
            flex-shrink:     0;
            margin-top:      .1rem;
        }
        .ls-info-label {
            font-size:   .65rem;
            font-weight: 700;
            color:       #94a3b8;
            text-transform: uppercase;
            letter-spacing: .05em;
            display:     block;
            margin-bottom: .12rem;
        }
        .ls-info-val {
            font-size:   .82rem;
            font-weight: 600;
            color:       #0f172a;
            line-height: 1.4;
        }

        /* Encabezado de sección tabla */
        .ls-section-label {
            display:       flex;
            align-items:   center;
            gap:           .5rem;
            font-size:     .72rem;
            font-weight:   800;
            letter-spacing: .07em;
            text-transform: uppercase;
            color:         var(--ls-blue);
            margin-bottom: .6rem;
        }
        .ls-section-label::after {
            content:    '';
            flex:       1;
            height:     1.5px;
            background: linear-gradient(90deg, rgba(20,93,160,.2), transparent);
            border-radius: 99px;
        }

        /* Tabla mejorada */
        .ls-table-wrap { border-radius: 10px; overflow: hidden; border: 1.5px solid #e2eaf4; }

        .ls-table        { width: 100%; border-collapse: collapse; font-size: .84rem; }
        .ls-table thead tr { background: linear-gradient(135deg, #0d2244, var(--ls-blue)); }
        .ls-table th {
            color:       #fff;
            font-weight: 700;
            font-size:   .72rem;
            letter-spacing: .05em;
            text-transform: uppercase;
            padding:     .65rem .9rem;
            text-align:  left;
            border:      none;
        }
        .ls-table td {
            padding:        .52rem .9rem;
            border-bottom:  1px solid #f0f4f8;
            vertical-align: middle;
            color:          #374151;
        }
        .ls-table tbody tr:last-child td { border-bottom: none; }
        .ls-table tbody tr:nth-child(even) { background: rgba(20,93,160,.025); }
        .ls-table tbody tr:hover           { background: rgba(20,93,160,.05); }

        /* Columna Nro */
        .ls-table td:first-child,
        .ls-table th:first-child {
            text-align: center;
            width:      52px;
        }
        .ls-nro {
            display:         inline-flex;
            align-items:     center;
            justify-content: center;
            width:           24px; height: 24px;
            background:      rgba(20,93,160,.08);
            color:           var(--ls-blue);
            border-radius:   6px;
            font-size:       .75rem;
            font-weight:     700;
        }

        /* Estado vacío */
        .ls-empty {
            text-align:  center;
            padding:     2.5rem 1rem;
            color:       #94a3b8;
            font-weight: 600;
            font-size:   .88rem;
        }

        /* Footer del documento */
        .ls-footer {
            display:     flex;
            align-items: center;
            justify-content: center;
            gap:         .75rem;
            margin-top:  1.5rem;
            padding-top: 1rem;
            border-top:  1.5px solid #e8f0f8;
        }
        .ls-footer-line {
            flex:       1;
            max-width:  60px;
            height:     1.5px;
            background: linear-gradient(90deg, transparent, rgba(20,93,160,.25));
        }
        .ls-footer-line:last-child {
            background: linear-gradient(270deg, transparent, rgba(20,93,160,.25));
        }
        .ls-footer-text {
            font-weight: 700;
            font-size:   .78rem;
            color:       #64748b;
            letter-spacing: .05em;
            text-transform: uppercase;
        }

        @media print {
            .ls-actions { display: none !important; }
            body        { background: #fff; }
            .ls-doc     { box-shadow: none; border-radius: 0; }
        }
    </style>
</head>
<body>
<div class="container py-3">

    {{-- FIX 4: todo en HTML, nada creado por JS --}}
    <div class="ls-actions" id="ls-actions">
        <div class="ls-actions-left">
            <a href="javascript:history.back()" class="ls-btn ls-btn-back">
                &#9668; Volver
            </a>
            <button type="button" class="ls-btn ls-btn-pdf" id="lsBtnPdf">
                &#128196; Generar PDF
            </button>
            <button type="button" class="ls-btn ls-btn-preview" id="lsBtnPreview">
                &#128065; Previsualizar
            </button>
        </div>
        <div class="ls-actions-right">
            <span class="ls-format-label">Formato:</span>
            <select class="ls-format-select" id="lsFormat">
                <option value="letter">Carta (8.5×11")</option>
                <option value="a4">A4 (210×297mm)</option>
                <option value="legal">Legal (8.5×14")</option>
            </select>
        </div>
    </div>

    <div class="ls-doc" id="ls-container">

        {{-- ── HEADER AZUL SÓLIDO ── --}}
        <header class="ls-header">
            <div class="ls-header-inner">

                {{-- Logo izquierdo --}}
                <div class="ls-logo-left">
                    <img src="{{ asset('assets/img/logof.png') }}" alt="Logo Fundación">
                </div>

                {{-- Centro: nombre de la institución --}}
                <div class="ls-header-center">
                    <p class="ls-header-title">Fundación Educar para la Vida</p>
                    <p class="ls-header-sub">Sistema de Gestión Académica</p>
                </div>

                {{-- Logo derecho sobre pastilla blanca --}}
                <div class="ls-logo-right">
                    <img src="{{ asset('assets/img/Acceder.png') }}" alt="Acceder">
                </div>

            </div>
        </header>

        {{-- ── CUERPO DEL DOCUMENTO ── --}}
        <div class="ls-body">

            {{-- Título --}}
            <h1 class="ls-title">Lista de Estudiantes</h1>
            <div class="ls-title-line"></div>

            {{-- Info del curso en tarjetas ── --}}
            {{-- NOTA controlador: mover filtro al query:
                 $inscritos = Inscrito::where('cursos_id', $curso->id)->with('estudiantes')->get(); --}}
            <div class="ls-info-grid">

                <div class="ls-info-item">
                    <div class="ls-info-icon">&#128100;</div>
                    <div>
                        <span class="ls-info-label">Estudiante</span>
                        <span class="ls-info-val">
                            {{ auth()->user()->name }}
                            {{ auth()->user()->lastname1 }}
                            {{ auth()->user()->lastname2 }}
                        </span>
                    </div>
                </div>

                <div class="ls-info-item">
                    <div class="ls-info-icon">&#127891;</div>
                    <div>
                        <span class="ls-info-label">Curso</span>
                        <span class="ls-info-val">
                            {{ ucfirst(strtolower($curso->nombreCurso)) }}
                        </span>
                    </div>
                </div>

                <div class="ls-info-item">
                    <div class="ls-info-icon">&#128101;</div>
                    <div>
                        <span class="ls-info-label">Docente</span>
                        <span class="ls-info-val">
                            {{ $curso->docente->name }}
                            {{ $curso->docente->lastname1 }}
                            {{ $curso->docente->lastname2 }}
                        </span>
                    </div>
                </div>

                <div class="ls-info-item">
                    <div class="ls-info-icon">&#128202;</div>
                    <div>
                        <span class="ls-info-label">Nivel</span>
                        <span class="ls-info-val">
                            {{ ucfirst(strtolower($curso->nivel)) }}
                        </span>
                    </div>
                </div>

                <div class="ls-info-item">
                    <div class="ls-info-icon">&#128197;</div>
                    <div>
                        <span class="ls-info-label">Periodo</span>
                        <span class="ls-info-val">
                            {{ $curso->fecha_ini }} — {{ $curso->fecha_fin }}
                        </span>
                    </div>
                </div>

                <div class="ls-info-item">
                    <div class="ls-info-icon">&#128336;</div>
                    <div>
                        <span class="ls-info-label">Horario</span>
                        <span class="ls-info-val">
                            @foreach($horarios as $horario)
                                {{ $horario->horario->dia }}
                                {{ \Carbon\Carbon::parse($horario->horario->hora_inicio)->format('h:i A') }}
                                a
                                {{ \Carbon\Carbon::parse($horario->horario->hora_fin)->format('h:i A') }}
                                @if(!$loop->last) · @endif
                            @endforeach
                        </span>
                    </div>
                </div>

            </div>{{-- /ls-info-grid --}}

            {{-- Tabla --}}
            <div class="ls-section-label">
                &#128203; Nómina de estudiantes inscritos
            </div>

            <div class="ls-table-wrap">
                <table class="ls-table">
                    <thead>
                        <tr>
                            <th>Nro</th>
                            <th>Nombre</th>
                            <th>Apellido Paterno</th>
                            <th>Apellido Materno</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $n = 0; @endphp
                        @forelse($inscritos as $inscrito)
                        @if($inscrito->cursos_id == $curso->id)
                        @php $n++ @endphp
                        <tr>
                            <td><span class="ls-nro">{{ $n }}</span></td>
                            <td>{{ $inscrito->estudiantes->name      ?? 'Estudiante eliminado' }}</td>
                            <td>{{ $inscrito->estudiantes->lastname1 ?? '—' }}</td>
                            <td>{{ $inscrito->estudiantes->lastname2 ?? '—' }}</td>
                        </tr>
                        @endif
                        @empty
                        <tr>
                            <td colspan="4" class="ls-empty">
                                No hay alumnos inscritos en este curso.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            <div class="ls-footer">
                <div class="ls-footer-line"></div>
                <span class="ls-footer-text">Fundación Educar para la Vida</span>
                <div class="ls-footer-line"></div>
            </div>

        </div>{{-- /ls-body --}}
    </div>{{-- /ls-doc --}}
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
    'use strict';

    function getOpts(format) {
        return {
            margin      : [10, 10, 10, 10],
            filename    : 'lista_estudiantes_{{ now()->format("Y-m-d") }}.pdf',
            image       : { type: 'jpeg', quality: 1.0 },
            html2canvas : { scale: 3, useCORS: true, letterRendering: true, logging: false },
            jsPDF       : { unit: 'mm', format: format, orientation: 'portrait', compress: true },
            pagebreak   : { mode: ['avoid-all', 'css', 'legacy'] },
        };
    }

    function showLoading() {
        const el = document.createElement('div');
        el.id    = 'lsLoadingEl';
        el.className = 'ls-loading';
        el.innerHTML = `<div class="ls-loading-card">
            <div class="ls-spinner"></div><span>Generando PDF…</span>
        </div>`;
        document.body.appendChild(el);
        return el;
    }
    function hideLoading(el) { el?.remove(); }

    function showError(msg) {
        const t = document.createElement('div');
        t.style.cssText = `position:fixed;bottom:1.5rem;left:50%;transform:translateX(-50%);
            background:#dc2626;color:#fff;padding:.55rem 1.3rem;border-radius:50px;
            font-size:.84rem;font-weight:600;z-index:9999;box-shadow:0 6px 20px rgba(0,0,0,.2)`;
        t.textContent = msg;
        document.body.appendChild(t);
        setTimeout(() => t.remove(), 4000);
    }

    /* FIX 3: un único listener por botón */
    document.addEventListener('DOMContentLoaded', function () {
        const btnPdf     = document.getElementById('lsBtnPdf');
        const btnPreview = document.getElementById('lsBtnPreview');
        const formatSel  = document.getElementById('lsFormat');
        const container  = document.getElementById('ls-container');

        btnPdf?.addEventListener('click', function () {
            const loading = showLoading();
            html2pdf()
                .set(getOpts(formatSel.value))
                .from(container)
                .save()
                .then(() => hideLoading(loading))
                /* FIX 2: sin alert() */
                .catch(err => { hideLoading(loading); showError('Error: ' + (err.message || 'desconocido')); });
        });

        btnPreview?.addEventListener('click', function () {
            const loading = showLoading();
            html2pdf()
                .set({
                    margin:      [10,10,10,10],
                    image:       { type:'jpeg', quality:.9 },
                    html2canvas: { scale:2, useCORS:true, logging:false },
                    jsPDF:       { unit:'mm', format:formatSel.value, orientation:'portrait' },
                })
                .from(container)
                .outputPdf('dataurlnewwindow')
                .then(() => hideLoading(loading))
                .catch(() => hideLoading(loading));
        });
    });

})();
</script>
</body>
</html>
