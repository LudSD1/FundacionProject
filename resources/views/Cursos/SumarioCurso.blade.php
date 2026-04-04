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

    <link rel="stylesheet" href="{{ asset("assets2/css/document.css") }}">

     {{-- FIX 7: estilos para PDF (solo se aplican al imprimir o generar PDF) --}}
     <style>
        @media print {
            body * { visibility: hidden; }
            #rf-container, #rf-container * { visibility: visible; }
            #rf-container { position: absolute; left: 0; top: 0; width: 100%; }
            .rf-actions { display: none !important; }
        }
    </style>
</head>

<body>
<div class="container my-4" style="max-width:960px">

    {{-- Barra de acciones (no va en PDF) --}}
    <div class="rf-actions" id="rf-actions">

        {{-- Volver: siempre visible --}}
        <a href="{{route('Inicio')}}" class="rf-btn rf-btn-back">
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
