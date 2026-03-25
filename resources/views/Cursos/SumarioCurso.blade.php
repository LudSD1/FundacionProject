<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Final — {{ $cursos->nombreCurso }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    {{-- FIX 1: rawgit.com muerto → cdnjs --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    {{-- FIX 8: Chart.js en head para que esté disponible al ejecutar el script --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap');

        :root {
            --rf-blue:    #145da0;
            --rf-blue-l:  #2a81c2;
            --rf-blue-d:  #0d2244;
            --rf-orange:  #ffa500;
            --rf-orange-d:#e59400;
            --rf-green:   #16a34a;
            --rf-red:     #dc2626;
            --rf-yellow:  #d97706;
            --rf-cyan:    #0891b2;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Montserrat', sans-serif;
            background:  #e8edf4;
            color:       #0f172a;
            font-size:   .9rem;
        }

        /* ── Barra de acción (no va en PDF) ── */
        .rf-actions {
            background:    #fff;
            border-radius: 10px;
            padding:       .75rem 1.1rem;
            margin-bottom: 1rem;
            display:       flex;
            justify-content: flex-end;
            gap:           .6rem;
            box-shadow:    0 2px 8px rgba(0,0,0,.08);
        }

        /* FIX 4: botones del sistema */
        .rf-btn {
            display:       inline-flex;
            align-items:   center;
            gap:           .4rem;
            padding:       .46rem 1.1rem;
            border-radius: 50px;
            font-size:     .83rem;
            font-weight:   700;
            cursor:        pointer;
            border:        none;
            font-family:   inherit;
            transition:    all .22s ease;
            white-space:   nowrap;
        }
        .rf-btn:hover { transform: translateY(-2px); }

        .rf-btn-back {
            background: #fff;
            color:      #64748b;
            border:     1.5px solid #d1dce8;
        }
        .rf-btn-back:hover { border-color: var(--rf-blue); color: var(--rf-blue); transform: none; }

        .rf-btn-print {
            background: rgba(20,93,160,.08);
            color:      var(--rf-blue);
            border:     1.5px solid rgba(20,93,160,.18);
        }
        .rf-btn-print:hover { background: rgba(20,93,160,.15); }

        .rf-btn-pdf {
            background: linear-gradient(135deg, var(--rf-blue), var(--rf-blue-l));
            color:      #fff;
            box-shadow: 0 4px 12px rgba(20,93,160,.25);
        }
        .rf-btn-pdf:hover { box-shadow: 0 6px 18px rgba(20,93,160,.35); color: #fff; }

        /* Indicador de carga */
        .rf-loading {
            position:  fixed; inset: 0;
            background: rgba(0,0,0,.45);
            z-index:   9999;
            display:   flex;
            align-items: center;
            justify-content: center;
        }
        .rf-loading-card {
            background: #fff; border-radius: 14px;
            padding:    1.3rem 2rem;
            display:    flex; align-items: center; gap: 1rem;
            font-weight: 600;
        }
        .rf-spinner {
            width: 24px; height: 24px;
            border: 3px solid #e2eaf4;
            border-top-color: var(--rf-blue);
            border-radius: 50%;
            animation: rfSpin .7s linear infinite;
        }
        @keyframes rfSpin { to { transform: rotate(360deg); } }

        /* ════════════════════
           DOCUMENTO
        ════════════════════ */
        .rf-doc {
            background:    #fff;
            border-radius: 12px;
            overflow:      hidden;
            box-shadow:    0 4px 20px rgba(0,0,0,.10);
        }

        /* FIX 2: header azul sólido (mismo que lista-estudiantes) */
        .rf-header {
            background: linear-gradient(135deg, var(--rf-blue-d) 0%, var(--rf-blue) 55%, var(--rf-blue-l) 100%);
            position:   relative;
            overflow:   hidden;
        }
        .rf-header::before {
            content:          '';
            position:         absolute; inset: 0;
            background-image: radial-gradient(rgba(255,255,255,.07) 1px, transparent 1px);
            background-size:  20px 20px;
            pointer-events:   none;
        }
        .rf-header::after {
            content:       '';
            position:      absolute;
            bottom: -40px; right: -40px;
            width:  180px; height: 180px;
            border-radius: 50%;
            background:    radial-gradient(circle, rgba(255,165,0,.18), transparent 70%);
            pointer-events: none;
        }
        .rf-header-inner {
            position:        relative; z-index: 1;
            display:         flex;
            align-items:     center;
            justify-content: space-between;
            padding:         16px 24px;
            gap:             12px;
        }
        .rf-logo-left img {
            height: 70px; width: auto;
            filter: brightness(0) invert(1);
            /* quitar filter si el logo ya es claro */
        }
        .rf-logo-right {
            background:    rgba(255,255,255,.92);
            border-radius: 10px;
            padding:       7px 13px;
            display:       flex; align-items: center;
        }
        .rf-logo-right img { height: 36px; width: auto; }
        .rf-header-center  { flex: 1; text-align: center; }
        .rf-header-title {
            font-size:     1.1rem; font-weight: 800;
            color:         #fff; margin: 0 0 .15rem;
            text-shadow:   0 1px 4px rgba(0,0,0,.2);
        }
        .rf-header-sub { font-size: .7rem; color: rgba(255,255,255,.72); margin: 0; }

        /* Cuerpo del documento */
        .rf-body { padding: 22px 24px 24px; }

        /* Título principal */
        .rf-title {
            font-size:     1.45rem; font-weight: 800;
            text-align:    center; color: #0f172a;
            text-transform: uppercase; letter-spacing: .04em;
            margin-bottom: .2rem;
        }
        .rf-title-line {
            width: 60px; height: 3px;
            background: linear-gradient(90deg, var(--rf-blue), var(--rf-orange));
            border-radius: 99px; margin: 0 auto 1.5rem;
        }

        /* Info grid igual que lista-estudiantes */
        .rf-info-grid {
            display:               grid;
            grid-template-columns: 1fr 1fr;
            gap:                   .55rem;
            margin-bottom:         1.5rem;
        }
        .rf-info-item {
            display:       flex; align-items: flex-start; gap: .6rem;
            background:    #f4f8fc;
            border-left:   3px solid var(--rf-blue);
            border-radius: 0 8px 8px 0;
            padding:       .52rem .75rem;
        }
        .rf-info-icon {
            width: 26px; height: 26px; border-radius: 50%;
            background: rgba(20,93,160,.10); color: var(--rf-blue);
            display: flex; align-items: center; justify-content: center;
            font-size: .7rem; flex-shrink: 0; margin-top: .1rem;
        }
        .rf-info-label {
            font-size: .63rem; font-weight: 700; color: #94a3b8;
            text-transform: uppercase; letter-spacing: .05em;
            display: block; margin-bottom: .1rem;
        }
        .rf-info-val {
            font-size: .82rem; font-weight: 600; color: #0f172a; line-height: 1.4;
        }

        /* Sección label (igual que lista-estudiantes) */
        .rf-section {
            display:       flex; align-items: center; gap: .5rem;
            font-size:     .72rem; font-weight: 800;
            letter-spacing: .07em; text-transform: uppercase;
            color:         var(--rf-blue); margin-bottom: .6rem;
        }
        .rf-section::after {
            content: ''; flex: 1; height: 1.5px;
            background: linear-gradient(90deg, rgba(20,93,160,.2), transparent);
            border-radius: 99px;
        }

        /* FIX 3: tabla con estilo del sistema */
        .rf-table-wrap { border-radius: 10px; overflow: hidden; border: 1.5px solid #e2eaf4; margin-bottom: 1.5rem; }
        .rf-table { width: 100%; border-collapse: collapse; font-size: .82rem; }
        .rf-table thead tr { background: linear-gradient(135deg, var(--rf-blue-d), var(--rf-blue)); }
        .rf-table th {
            color: #fff; font-weight: 700; font-size: .7rem;
            letter-spacing: .05em; text-transform: uppercase;
            padding: .6rem .8rem; text-align: left; border: none;
        }
        .rf-table th.text-center { text-align: center; }
        .rf-table td {
            padding: .5rem .8rem; border-bottom: 1px solid #f0f4f8;
            vertical-align: middle; color: #374151;
        }
        .rf-table tbody tr:last-child td { border-bottom: none; }
        .rf-table tbody tr:nth-child(even) { background: rgba(20,93,160,.025); }
        .rf-table tbody tr:hover           { background: rgba(20,93,160,.05); }
        .rf-table td.text-center { text-align: center; }

        /* Escala de notas */
        .rf-escala {
            display: inline-flex; align-items: center;
            padding: .2rem .6rem; border-radius: 50px;
            font-size: .72rem; font-weight: 700;
        }
        .rf-escala--experto    { background: rgba(22,163,74,.10);  color: var(--rf-green); }
        .rf-escala--habilidoso { background: rgba(20,93,160,.10);  color: var(--rf-blue); }
        .rf-escala--aprendiz   { background: rgba(255,165,0,.12);  color: var(--rf-yellow); }
        .rf-escala--part       { background: rgba(100,116,139,.09);color: #64748b; }

        /* Certificado */
        .rf-cert-si  { color: var(--rf-green); font-weight: 700; }
        .rf-cert-no  { color: #94a3b8; font-weight: 600; }

        /* FIX 6+7: gráficos con colores del sistema */
        .rf-charts { display: flex; justify-content: center; gap: 1.5rem; flex-wrap: wrap; margin-top: .5rem; }
        .rf-chart-box {
            background:    #fff;
            border:        1.5px solid #e2eaf4;
            border-radius: 12px;
            padding:       1rem;
            flex:          1;
            min-width:     260px;
            max-width:     380px;
        }
        .rf-chart-title {
            font-size: .72rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: .06em;
            color: #64748b; margin-bottom: .6rem; text-align: center;
        }

        /* Footer */
        .rf-footer {
            display:     flex; align-items: center; justify-content: center;
            gap:         .75rem; margin-top: 1.5rem;
            padding-top: 1rem; border-top: 1.5px solid #e8f0f8;
        }
        .rf-footer-line {
            flex: 1; max-width: 60px; height: 1.5px;
            background: linear-gradient(90deg, transparent, rgba(20,93,160,.25));
        }
        .rf-footer-line:last-child {
            background: linear-gradient(270deg, transparent, rgba(20,93,160,.25));
        }
        .rf-footer-text {
            font-size: .75rem; font-weight: 700;
            color: #64748b; letter-spacing: .05em; text-transform: uppercase;
        }

        @media print {
            .rf-actions { display: none !important; }
            body        { background: #fff; }
            .rf-doc     { box-shadow: none; border-radius: 0; }
        }
    </style>
</head>

<body>
<div class="container my-4" style="max-width:960px">

    {{-- Barra de acciones (no va en PDF) --}}
    <div class="rf-actions" id="rf-actions">

        {{-- Volver: siempre visible --}}
        <a href="javascript:history.back()" class="rf-btn rf-btn-back">
            <i class="bi bi-arrow-left-circle-fill"></i> Volver
        </a>

        {{-- Imprimir: siempre visible --}}
        <button type="button" class="rf-btn rf-btn-print" id="rfBtnPrint">
            <i class="bi bi-printer-fill"></i> Imprimir
        </button>

        {{-- PDF: solo Admin --}}
        @if(auth()->user()->hasRole('Administrador'))
        <button type="button" class="rf-btn rf-btn-pdf" id="rfBtnPdf">
            <i class="bi bi-file-earmark-pdf-fill"></i> Generar PDF
        </button>
        @endif

    </div>

    {{-- DOCUMENTO --}}
    <div class="rf-doc" id="rf-container">

        {{-- Header --}}
        <header class="rf-header">
            <div class="rf-header-inner">
                <div class="rf-logo-left">
                    <img src="{{ asset('assets/img/logof.png') }}" alt="Logo Fundación">
                </div>
                <div class="rf-header-center">
                    <p class="rf-header-title">Fundación Educar para la Vida</p>
                    <p class="rf-header-sub">Sistema de Gestión Académica</p>
                </div>
                <div class="rf-logo-right">
                    <img src="{{ asset('assets/img/Acceder.png') }}" alt="Acceder">
                </div>
            </div>
        </header>

        <div class="rf-body">

            {{-- Título --}}
            <h1 class="rf-title">
                {{ $cursos->tipo == 'curso' ? 'Sumario del Curso' : 'Sumario del Congreso' }}
            </h1>
            <div class="rf-title-line"></div>

            {{-- Info del curso --}}
            <div class="rf-info-grid">

                <div class="rf-info-item">
                    <div class="rf-info-icon">&#127891;</div>
                    <div>
                        <span class="rf-info-label">
                            {{ $cursos->tipo == 'curso' ? 'Curso' : 'Congreso' }}
                        </span>
                        <span class="rf-info-val">{{ $cursos->nombreCurso }}</span>
                    </div>
                </div>

                @if($cursos->tipo == 'curso')
                <div class="rf-info-item">
                    <div class="rf-info-icon">&#128101;</div>
                    <div>
                        <span class="rf-info-label">Docente</span>
                        <span class="rf-info-val">
                            {{ $cursos->docente->name }}
                            {{ $cursos->docente->lastname1 }}
                            {{ $cursos->docente->lastname2 }}
                        </span>
                    </div>
                </div>
                @endif

                <div class="rf-info-item">
                    <div class="rf-info-icon">&#128197;</div>
                    <div>
                        <span class="rf-info-label">Periodo</span>
                        <span class="rf-info-val">{{ $cursos->fecha_ini }} — {{ $cursos->fecha_fin }}</span>
                    </div>
                </div>

                <div class="rf-info-item">
                    <div class="rf-info-icon">&#128202;</div>
                    <div>
                        <span class="rf-info-label">Nivel</span>
                        <span class="rf-info-val">{{ $cursos->nivel ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="rf-info-item">
                    <div class="rf-info-icon">&#128100;</div>
                    <div>
                        <span class="rf-info-label">Inscritos</span>
                        <span class="rf-info-val">{{ $inscritos->count() }} estudiantes</span>
                    </div>
                </div>

                @if($cursos->tipo == 'curso')
                <div class="rf-info-item">
                    <div class="rf-info-icon">&#128218;</div>
                    <div>
                        <span class="rf-info-label">Contenido</span>
                        <span class="rf-info-val">
                            {{ $temas->count() }} temas ·
                            {{ $foros->count() }} foros ·
                            {{ $recursos->count() }} recursos
                        </span>
                    </div>
                </div>
                @endif

            </div>{{-- /rf-info-grid --}}

            {{-- Tabla participantes --}}
            <div class="rf-section">&#128203; Participantes</div>
            <div class="rf-table-wrap">
                <table class="rf-table">
                    <thead>
                        <tr>
                            <th>Participante</th>
                            @if($cursos->tipo == 'curso')
                                <th class="text-center">Asistencias</th>
                                <th>Detalle</th>
                                <th class="text-center">Nota</th>
                                <th class="text-center">Escala</th>
                            @else
                                <th class="text-center">Certificado</th>
                                <th class="text-center">Fecha</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inscritos as $inscrito)
                        @php
                            $notasInscrito        = $notasEntregas->where('inscripcion_id', $inscrito->id);
                            $cuestionariosInscrito= $notasCuestionarios->where('inscripcion_id', $inscrito->id);
                            $sumaNotas     = $notasInscrito->sum('nota') + $cuestionariosInscrito->sum('calificacion');
                            $cantNotas     = $notasInscrito->count() + $cuestionariosInscrito->count();
                            $notaFinal     = $cantNotas > 0 ? round($sumaNotas / $cantNotas, 2) : 0;

                            $escalaCls  = 'rf-escala--part';
                            $escalaTxt  = 'Participante';
                            if ($notaFinal >= 90) { $escalaCls = 'rf-escala--experto';    $escalaTxt = 'Experto'; }
                            elseif ($notaFinal >= 75) { $escalaCls = 'rf-escala--habilidoso'; $escalaTxt = 'Habilidoso'; }
                            elseif ($notaFinal >= 60) { $escalaCls = 'rf-escala--aprendiz';   $escalaTxt = 'Aprendiz'; }
                        @endphp
                        <tr>
                            <td>
                                <strong>
                                    {{ $inscrito->estudiantes->name }}
                                    {{ $inscrito->estudiantes->lastname1 }}
                                    {{ $inscrito->estudiantes->lastname2 }}
                                </strong>
                            </td>
                            @if($cursos->tipo == 'curso')
                            <td class="text-center">
                                {{ $inscrito->asistencia->count() }}
                            </td>
                            <td style="font-size:.78rem; line-height:1.6">
                                <span style="color:var(--rf-green)">&#10003; Presente:</span>
                                {{ $inscrito->asistencia->where('tipoAsitencia', 'Presente')->count() }}<br>
                                <span style="color:var(--rf-yellow)">&#9719; Retraso:</span>
                                {{ $inscrito->asistencia->where('tipoAsitencia', 'Retraso')->count() }}<br>
                                <span style="color:var(--rf-red)">&#10007; Falta:</span>
                                {{ $inscrito->asistencia->where('tipoAsitencia', 'Falta')->count() }}<br>
                                <span style="color:var(--rf-blue)">&#9741; Licencia:</span>
                                {{ $inscrito->asistencia->where('tipoAsitencia', 'Licencia')->count() }}
                            </td>
                            <td class="text-center">
                                <strong>{{ $notaFinal }}</strong>
                            </td>
                            <td class="text-center">
                                <span class="rf-escala {{ $escalaCls }}">{{ $escalaTxt }}</span>
                            </td>
                            @else
                            @if(isset($inscrito->certificado))
                            <td class="text-center rf-cert-si">
                                <i class="bi bi-award-fill me-1"></i> Sí
                            </td>
                            <td class="text-center" style="font-size:.78rem">
                                {{ $inscrito->certificado->created_at->format('d/m/Y') }}
                            </td>
                            @else
                            <td class="text-center rf-cert-no">No</td>
                            <td class="text-center rf-cert-no" style="font-size:.78rem">No recibido</td>
                            @endif
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Gráficos (solo curso) --}}
            @if($cursos->tipo == 'curso')
            <div class="rf-section">&#128200; Estadísticas del Curso</div>
            <div class="rf-charts">
                <div class="rf-chart-box">
                    <div class="rf-chart-title">Distribución de Asistencias</div>
                    <canvas id="rfChart1"></canvas>
                </div>
                <div class="rf-chart-box">
                    <div class="rf-chart-title">Distribución por Escala</div>
                    <canvas id="rfChart2"></canvas>
                </div>
            </div>
            @endif

            {{-- Footer --}}
            <div class="rf-footer">
                <div class="rf-footer-line"></div>
                <span class="rf-footer-text">Fundación Educar para la Vida</span>
                <div class="rf-footer-line"></div>
            </div>

        </div>{{-- /rf-body --}}
    </div>{{-- /rf-doc --}}
</div>{{-- /container --}}

{{-- FIX 8+9: un solo bloque de scripts bien estructurado --}}
<script>
(function () {
    /* ── FIX 6: colores del sistema en Chart.js ── */
    const COLORS = {
        blue  : { bg: 'rgba(20,93,160,.18)',   bd: '#145da0' },
        orange: { bg: 'rgba(255,165,0,.18)',   bd: '#ffa500' },
        red   : { bg: 'rgba(220,38,38,.18)',   bd: '#dc2626' },
        green : { bg: 'rgba(22,163,74,.18)',   bd: '#16a34a' },
        gray  : { bg: 'rgba(100,116,139,.18)', bd: '#64748b' },
        yellow: { bg: 'rgba(217,119,6,.18)',   bd: '#d97706' },
    };

    /* ── Gráfico 1: Asistencias ── */
    const ctx1 = document.getElementById('rfChart1');
    if (ctx1) {
        new Chart(ctx1.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Presente', 'Retraso', 'Falta', 'Licencia'],
                datasets: [{
                    label:           'Asistencias',
                    data:            [{{ $conteoPresentes }}, {{ $conteoRetrasos }}, {{ $conteoFaltas }}, {{ $conteoLicencias }}],
                    backgroundColor: [COLORS.green.bg, COLORS.yellow.bg, COLORS.red.bg, COLORS.blue.bg],
                    borderColor:     [COLORS.green.bd, COLORS.yellow.bd, COLORS.red.bd, COLORS.blue.bd],
                    borderWidth: 2, borderRadius: 6,
                }]
            },
            options: {
                scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,.05)' } } },
                plugins: { legend: { display: false } },
                responsive: true,
            }
        });
    }

    /* ── Gráfico 2: Escala ── */
    const ctx2 = document.getElementById('rfChart2');
    if (ctx2) {
        new Chart(ctx2.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Participante', 'Aprendiz', 'Habilidoso', 'Experto'],
                datasets: [{
                    label:           'Estudiantes',
                    data:            [{{ $participanteCount }}, {{ $aprendizCount }}, {{ $habilidosoCount }}, {{ $expertoCount }}],
                    backgroundColor: [COLORS.gray.bg, COLORS.orange.bg, COLORS.blue.bg, COLORS.green.bg],
                    borderColor:     [COLORS.gray.bd, COLORS.orange.bd, COLORS.blue.bd, COLORS.green.bd],
                    borderWidth: 2, borderRadius: 6,
                }]
            },
            options: {
                scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,.05)' } } },
                plugins: { legend: { display: false } },
                responsive: true,
            }
        });
    }

    /* ── Imprimir ── */
    document.getElementById('rfBtnPrint')?.addEventListener('click', function () {
        window.print();
    });

    /* ── Generador PDF ── */
    function showLoading() {
        const el = document.createElement('div');
        el.id    = 'rfLoading';
        el.className = 'rf-loading';
        el.innerHTML = `<div class="rf-loading-card">
            <div class="rf-spinner"></div><span>Generando PDF…</span>
        </div>`;
        document.body.appendChild(el);
        return el;
    }

    document.getElementById('rfBtnPdf')?.addEventListener('click', function () {
        const loading = showLoading();
        html2pdf()
            .set({
                margin      : [10, 10, 10, 10],
                filename    : 'reporte_final_{{ now()->format("Y-m-d") }}.pdf',
                image       : { type: 'jpeg', quality: 1.0 },
                html2canvas : { scale: 3, useCORS: true, letterRendering: true, logging: false },
                jsPDF       : { unit: 'mm', format: 'a4', orientation: 'portrait', compress: true },
                pagebreak   : { mode: ['avoid-all', 'css', 'legacy'] },
            })
            .from(document.getElementById('rf-container'))
            .save()
            .then(() => loading.remove())
            .catch(err => {
                loading.remove();
                const t = document.createElement('div');
                t.style.cssText = `position:fixed;bottom:1.5rem;left:50%;transform:translateX(-50%);
                    background:#dc2626;color:#fff;padding:.5rem 1.2rem;border-radius:50px;
                    font-size:.83rem;font-weight:600;z-index:9999`;
                t.textContent = 'Error al generar PDF: ' + (err.message || 'desconocido');
                document.body.appendChild(t);
                setTimeout(() => t.remove(), 4000);
            });
    });

})();
</script>

</body>
</html>
