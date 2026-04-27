{{-- ╔══════════════════════════════════════════════════════════════╗
     ║  TENDENCIAS DE INSCRIPCIÓN                                 ║
     ║  Mejores cursos inscritos por mes / día                    ║
     ╚══════════════════════════════════════════════════════════════╝ --}}

<style>
    /* ── Enrollment Trends ─────────────────────────────────── */
    .et-wrap { font-family: 'Inter', 'Segoe UI', sans-serif; }

    .et-kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .et-kpi {
        background: #fff;
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all .3s cubic-bezier(.4,0,.2,1);
    }
    .et-kpi:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -6px rgba(0,0,0,.08);
        border-color: #145da0;
    }
    .et-kpi-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; flex-shrink: 0;
    }
    .et-kpi-value { font-size: 1.5rem; font-weight: 700; color: #1e293b; line-height: 1.1; }
    .et-kpi-label { font-size: .78rem; color: #64748b; font-weight: 500; }
    .et-kpi-badge {
        display: inline-flex; align-items: center; gap: .25rem;
        font-size: .7rem; font-weight: 600; padding: .15rem .5rem;
        border-radius: 20px; margin-top: .15rem;
    }
    .et-kpi-badge--up   { background: #dcfce7; color: #16a34a; }
    .et-kpi-badge--down { background: #fee2e2; color: #dc2626; }
    .et-kpi-badge--flat { background: #f1f5f9; color: #64748b; }

    /* Toolbar */
    .et-toolbar {
        display: flex; align-items: center; gap: 1rem;
        margin-bottom: 1.25rem; flex-wrap: wrap;
    }
    .et-toolbar-title {
        font-size: 1.1rem; font-weight: 700; color: #1e293b;
        display: flex; align-items: center; gap: .5rem;
        flex-grow: 1;
    }
    .et-select {
        padding: .45rem .8rem; border-radius: .5rem;
        border: 1px solid #e2e8f0; font-size: .82rem;
        color: #475569; background: #fff;
        cursor: pointer; transition: border-color .2s;
    }
    .et-select:focus { outline: none; border-color: #145da0; }
    .et-btn-back {
        padding: .4rem .75rem; border-radius: .5rem;
        border: 1px solid #e2e8f0; background: #fff;
        font-size: .8rem; color: #475569; cursor: pointer;
        display: none; align-items: center; gap: .35rem;
        transition: all .2s;
    }
    .et-btn-back:hover { border-color: #145da0; color: #145da0; }
    .et-btn-back.visible { display: inline-flex; }

    /* Charts grid */
    .et-charts-grid {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }
    @media (max-width: 992px) {
        .et-charts-grid { grid-template-columns: 1fr; }
    }
    .et-chart-card {
        background: #fff; border-radius: 1rem;
        border: 1px solid #e2e8f0; padding: 1.25rem;
        position: relative;
    }
    .et-chart-title {
        font-size: .85rem; font-weight: 600; color: #1e293b;
        margin-bottom: .75rem; display: flex; align-items: center; gap: .4rem;
    }
    .et-chart-canvas-wrap { position: relative; height: 300px; }
    .et-chart-canvas-wrap canvas { width: 100% !important; }

    /* Ranking table */
    .et-ranking { margin-bottom: 1.5rem; }
    .et-ranking-table {
        width: 100%; border-collapse: separate; border-spacing: 0;
        font-size: .82rem;
    }
    .et-ranking-table thead th {
        background: #f8fafc; color: #64748b; font-weight: 600;
        padding: .65rem .75rem; text-align: left;
        border-bottom: 2px solid #e2e8f0; font-size: .75rem;
        text-transform: uppercase; letter-spacing: .03em;
    }
    .et-ranking-table tbody tr {
        transition: background .15s;
    }
    .et-ranking-table tbody tr:hover { background: #f1f5f9; }
    .et-ranking-table td {
        padding: .65rem .75rem; border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .et-rank-num {
        width: 28px; height: 28px; border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: .75rem;
    }
    .et-rank-1 { background: linear-gradient(135deg, #fbbf24, #f59e0b); color: #fff; }
    .et-rank-2 { background: linear-gradient(135deg, #94a3b8, #64748b); color: #fff; }
    .et-rank-3 { background: linear-gradient(135deg, #d97706, #b45309); color: #fff; }
    .et-rank-default { background: #f1f5f9; color: #64748b; }

    .et-course-cell { display: flex; align-items: center; gap: .65rem; }
    .et-course-thumb {
        width: 40px; height: 40px; border-radius: 8px;
        object-fit: cover; border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,.06);
    }
    .et-course-name {
        font-weight: 600; color: #1e293b; display: block;
        max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }
    .et-course-cats {
        font-size: .68rem; color: #94a3b8;
    }
    .et-bar-wrap {
        width: 100%; height: 6px; background: #f1f5f9;
        border-radius: 6px; overflow: hidden;
    }
    .et-bar-fill {
        height: 100%; border-radius: 6px;
        background: linear-gradient(90deg, #145da0, #2c7be5);
        transition: width .6s ease;
    }
    .et-total-badge {
        font-weight: 700; font-size: .9rem; color: #145da0;
    }

    /* Weekday mini-chart */
    .et-weekday-bars {
        display: flex; align-items: flex-end; gap: 6px; height: 120px;
        padding: .5rem 0;
    }
    .et-weekday-bar {
        flex: 1; border-radius: 6px 6px 0 0;
        background: linear-gradient(180deg, #145da0, #2c7be5);
        min-height: 4px; transition: height .4s ease;
        position: relative; cursor: default;
    }
    .et-weekday-bar:hover { opacity: .85; }
    .et-weekday-label {
        text-align: center; font-size: .65rem; color: #94a3b8;
        font-weight: 600; margin-top: .35rem;
    }
    .et-weekday-val {
        text-align: center; font-size: .65rem; color: #475569;
        font-weight: 600; margin-bottom: .2rem;
    }

    /* Loading */
    .et-loading {
        display: flex; align-items: center; justify-content: center;
        padding: 3rem; color: #94a3b8;
    }
    .et-spinner {
        width: 32px; height: 32px;
        border: 3px solid #e2e8f0; border-top-color: #145da0;
        border-radius: 50%; animation: et-spin .7s linear infinite;
        margin-right: .75rem;
    }
    @keyframes et-spin { to { transform: rotate(360deg); } }

    /* Empty */
    .et-empty {
        text-align: center; padding: 3rem; color: #94a3b8;
    }
    .et-empty i { font-size: 2.5rem; margin-bottom: .5rem; display: block; }
</style>

<div class="et-wrap" id="enrollmentTrendsWrap">

    {{-- Loading --}}
    <div class="et-loading" id="etLoading">
        <div class="et-spinner"></div>
        <span>Cargando tendencias de inscripción…</span>
    </div>

    {{-- Contenido (hidden hasta que cargue) --}}
    <div id="etContent" style="display:none">

        {{-- ── KPIs ─────────────────────────────────────────── --}}
        <div class="et-kpi-grid" id="etKpis">
            <div class="et-kpi">
                <div class="et-kpi-icon bg-primary-subtle text-primary">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div>
                    <div class="et-kpi-value" id="kpiInscripciones">0</div>
                    <div class="et-kpi-label">Inscripciones este mes</div>
                    <span class="et-kpi-badge et-kpi-badge--flat" id="kpiVariacion">0%</span>
                </div>
            </div>
            <div class="et-kpi">
                <div class="et-kpi-icon bg-success-subtle text-success">
                    <i class="bi bi-trophy-fill"></i>
                </div>
                <div>
                    <div class="et-kpi-value et-kpi-value--sm" id="kpiCursoTop" style="font-size:1rem;">—</div>
                    <div class="et-kpi-label">Curso más popular</div>
                    <span class="et-kpi-badge et-kpi-badge--flat" id="kpiCursoTopTotal">0 inscritos</span>
                </div>
            </div>
            <div class="et-kpi">
                <div class="et-kpi-icon bg-warning-subtle text-warning">
                    <i class="bi bi-tags-fill"></i>
                </div>
                <div>
                    <div class="et-kpi-value" id="kpiCategoriaTop" style="font-size:1rem;">—</div>
                    <div class="et-kpi-label">Categoría más popular</div>
                    <span class="et-kpi-badge et-kpi-badge--flat" id="kpiCategoriaTopTotal">0 inscritos</span>
                </div>
            </div>
            <div class="et-kpi">
                <div class="et-kpi-icon bg-info-subtle text-info">
                    <i class="bi bi-calendar-week-fill"></i>
                </div>
                <div>
                    <div class="et-kpi-value" id="kpiMejorDia">—</div>
                    <div class="et-kpi-label">Mejor día de la semana</div>
                    <span class="et-kpi-badge et-kpi-badge--flat" id="kpiMejorDiaTotal">0 inscritos</span>
                </div>
            </div>
        </div>

        {{-- ── Toolbar ──────────────────────────────────────── --}}
        <div class="et-toolbar">
            <div class="et-toolbar-title">
                <i class="bi bi-bar-chart-line-fill text-primary"></i>
                <span id="etChartTitle">Inscripciones por Mes</span>
            </div>
            <button class="et-btn-back" id="etBtnBack" onclick="etBackToMonthly()">
                <i class="bi bi-arrow-left"></i> Volver a meses
            </button>
            <select class="et-select" id="etPeriodoSelect" onchange="etLoadData()">
                <option value="12meses">Últimos 12 meses</option>
                <option value="30dias">Últimos 30 días</option>
                <option value="7dias">Últimos 7 días</option>
            </select>
        </div>

        {{-- ── Charts ───────────────────────────────────────── --}}
        <div class="et-charts-grid">
            {{-- Main chart --}}
            <div class="et-chart-card">
                <div class="et-chart-title">
                    <i class="bi bi-bar-chart-fill text-primary"></i>
                    <span id="etMainChartLabel">Inscripciones mensuales</span>
                </div>
                <div class="et-chart-canvas-wrap">
                    <canvas id="etMainChart"></canvas>
                </div>
            </div>

            {{-- Dona de categorías --}}
            <div class="et-chart-card">
                <div class="et-chart-title">
                    <i class="bi bi-pie-chart-fill text-warning"></i>
                    Distribución por Categoría
                </div>
                <div class="et-chart-canvas-wrap" style="height:250px;">
                    <canvas id="etCategoryChart"></canvas>
                </div>
                <div id="etCategoryLegend" style="margin-top:.5rem;"></div>
            </div>
        </div>

        {{-- ── Ranking tabla ────────────────────────────────── --}}
        <div class="et-chart-card et-ranking">
            <div class="et-chart-title">
                <i class="bi bi-award-fill text-warning"></i>
                Top 10 Cursos Más Inscritos
            </div>
            <div class="table-responsive">
                <table class="et-ranking-table">
                    <thead>
                        <tr>
                            <th style="width:50px">#</th>
                            <th>Curso</th>
                            <th style="width:140px">Categoría</th>
                            <th style="width:130px">Inscripciones</th>
                            <th style="width:150px">Proporción</th>
                        </tr>
                    </thead>
                    <tbody id="etRankingBody">
                        <tr><td colspan="5" class="et-empty"><i class="bi bi-inbox"></i>Sin datos</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── Día de la semana ─────────────────────────────── --}}
        <div class="et-chart-card">
            <div class="et-chart-title">
                <i class="bi bi-calendar3 text-info"></i>
                Inscripciones por Día de la Semana
            </div>
            <div id="etWeekdayChart"></div>
        </div>

    </div>{{-- /etContent --}}
</div>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

<script>
(function() {
    'use strict';

    const API_URL = @json(route('admin.enrollment-trends'));
    let mainChart = null;
    let catChart  = null;
    let currentView = 'monthly'; // 'monthly' | 'daily'
    let currentMes  = null;

    // ── Paleta de colores premium ──
    const PALETTE = [
        '#145da0','#2c7be5','#0dcaf0','#20c997','#198754',
        '#ffc107','#fd7e14','#dc3545','#6f42c1','#d63384',
        '#0ea5e9','#14b8a6','#84cc16','#f97316','#8b5cf6'
    ];

    // ── Init ───────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function() {
        // Solo cargar cuando la pestaña sea visible
        const tabBtn = document.getElementById('btn-tab-trends');
        if (tabBtn) {
            tabBtn.addEventListener('shown.bs.tab', function() {
                if (!mainChart) etLoadData();
            });
            // Si la pestaña ya está activa (por hash en URL)
            if (tabBtn.classList.contains('active')) {
                etLoadData();
            }
        } else {
            // Fallback: cargar directamente
            etLoadData();
        }
    });

    // ── Cargar datos ───────────────────────────────────────
    window.etLoadData = function() {
        const periodo = document.getElementById('etPeriodoSelect').value;
        let url = API_URL + '?periodo=' + periodo;
        if (currentView === 'daily' && currentMes) {
            url += '&mes=' + currentMes;
        }

        document.getElementById('etLoading').style.display = 'flex';
        document.getElementById('etContent').style.display = 'none';

        fetch(url, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            renderKpis(data.kpis);
            renderMainChart(data);
            renderCategoryChart(data.byCategory);
            renderRanking(data.topCourses);
            renderWeekday(data.byWeekday, data.kpis);

            document.getElementById('etLoading').style.display = 'none';
            document.getElementById('etContent').style.display = 'block';
        })
        .catch(err => {
            console.error('Error cargando tendencias:', err);
            document.getElementById('etLoading').innerHTML =
                '<div class="et-empty"><i class="bi bi-exclamation-triangle"></i>Error al cargar datos</div>';
        });
    };

    // ── KPIs ───────────────────────────────────────────────
    function renderKpis(kpis) {
        document.getElementById('kpiInscripciones').textContent = kpis.inscripcionesMes;
        document.getElementById('kpiCursoTop').textContent = truncate(kpis.cursoTop, 30);
        document.getElementById('kpiCursoTop').title = kpis.cursoTop;
        document.getElementById('kpiCursoTopTotal').textContent = kpis.cursoTopTotal + ' inscritos';
        document.getElementById('kpiCategoriaTop').textContent = kpis.categoriaTop;
        document.getElementById('kpiCategoriaTopTotal').textContent = kpis.categoriaTopTotal + ' inscritos';

        // Variación
        const varEl = document.getElementById('kpiVariacion');
        const v = kpis.variacion;
        varEl.textContent = (v > 0 ? '+' : '') + v + '% vs mes anterior';
        varEl.className = 'et-kpi-badge ' + (v > 0 ? 'et-kpi-badge--up' : v < 0 ? 'et-kpi-badge--down' : 'et-kpi-badge--flat');
        if (v > 0) varEl.innerHTML = '<i class="bi bi-arrow-up-short"></i>' + varEl.textContent;
        else if (v < 0) varEl.innerHTML = '<i class="bi bi-arrow-down-short"></i>' + varEl.textContent;
    }

    // ── Main chart ─────────────────────────────────────────
    function renderMainChart(data) {
        const ctx = document.getElementById('etMainChart').getContext('2d');
        if (mainChart) mainChart.destroy();

        const isDaily = currentView === 'daily' && data.daily;
        const dataset = isDaily ? data.daily : data.monthly;
        const labels = dataset.map(d => d.label);
        const values = dataset.map(d => d.total);

        document.getElementById('etChartTitle').textContent =
            isDaily ? 'Inscripciones diarias — ' + currentMes : 'Inscripciones por Mes';
        document.getElementById('etMainChartLabel').textContent =
            isDaily ? 'Detalle diario' : 'Inscripciones mensuales';
        document.getElementById('etBtnBack').classList.toggle('visible', isDaily);

        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(20, 93, 160, 0.35)');
        gradient.addColorStop(1, 'rgba(20, 93, 160, 0.02)');

        mainChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Inscripciones',
                    data: values,
                    backgroundColor: gradient,
                    borderColor: '#145da0',
                    borderWidth: 2,
                    borderRadius: 6,
                    borderSkipped: false,
                    hoverBackgroundColor: 'rgba(20, 93, 160, 0.55)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                onClick: function(e, elements) {
                    if (!isDaily && elements.length > 0) {
                        const idx = elements[0].index;
                        const item = data.monthly[idx];
                        if (item) etDrillDown(item.periodo);
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleFont: { size: 13, weight: '600' },
                        bodyFont: { size: 12 },
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: ctx => ctx.parsed.y + ' inscripciones',
                            afterLabel: function() {
                                return isDaily ? '' : '🔍 Clic para ver detalle diario';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { font: { size: 11 }, color: '#94a3b8', stepSize: 1 },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        ticks: { font: { size: 11 }, color: '#64748b', maxRotation: 45 },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // ── Drill-down a día ───────────────────────────────────
    window.etDrillDown = function(mes) {
        currentView = 'daily';
        currentMes = mes;
        etLoadData();
    };

    window.etBackToMonthly = function() {
        currentView = 'monthly';
        currentMes = null;
        etLoadData();
    };

    // ── Category chart ─────────────────────────────────────
    function renderCategoryChart(cats) {
        const ctx = document.getElementById('etCategoryChart').getContext('2d');
        if (catChart) catChart.destroy();

        if (!cats || cats.length === 0) {
            document.getElementById('etCategoryLegend').innerHTML =
                '<div class="et-empty" style="padding:1rem;"><i class="bi bi-inbox"></i>Sin datos de categorías</div>';
            return;
        }

        const labels = cats.map(c => c.name);
        const values = cats.map(c => c.total);

        catChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels,
                datasets: [{
                    data: values,
                    backgroundColor: PALETTE.slice(0, labels.length),
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: ctx => ctx.label + ': ' + ctx.parsed + ' inscripciones'
                        }
                    }
                }
            }
        });

        // Custom legend
        const total = values.reduce((a, b) => a + b, 0);
        let html = '<div style="display:flex;flex-direction:column;gap:.35rem;">';
        cats.forEach((c, i) => {
            const pct = total > 0 ? ((c.total / total) * 100).toFixed(1) : 0;
            html += `<div style="display:flex;align-items:center;gap:.5rem;font-size:.75rem;">
                <span style="width:10px;height:10px;border-radius:3px;background:${PALETTE[i % PALETTE.length]};flex-shrink:0;"></span>
                <span style="color:#475569;flex-grow:1;">${c.name}</span>
                <span style="color:#1e293b;font-weight:600;">${c.total}</span>
                <span style="color:#94a3b8;">(${pct}%)</span>
            </div>`;
        });
        html += '</div>';
        document.getElementById('etCategoryLegend').innerHTML = html;
    }

    // ── Ranking ────────────────────────────────────────────
    function renderRanking(courses) {
        const tbody = document.getElementById('etRankingBody');

        if (!courses || courses.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="et-empty"><i class="bi bi-inbox"></i>No hay inscripciones en este período</td></tr>';
            return;
        }

        const maxTotal = courses[0].total;
        let html = '';
        courses.forEach((c, i) => {
            const rankClass = i === 0 ? 'et-rank-1' : i === 1 ? 'et-rank-2' : i === 2 ? 'et-rank-3' : 'et-rank-default';
            const pct = maxTotal > 0 ? ((c.total / maxTotal) * 100).toFixed(0) : 0;
            const cats = c.categorias.length > 0 ? c.categorias.join(', ') : '—';
            const tipo = c.tipo === 'congreso'
                ? '<span style="font-size:.6rem;background:#e0f2fe;color:#0284c7;padding:.1rem .4rem;border-radius:10px;"><i class="bi bi-megaphone-fill"></i> Congreso</span>'
                : '';

            html += `<tr>
                <td><span class="et-rank-num ${rankClass}">${i + 1}</span></td>
                <td>
                    <div class="et-course-cell">
                        <img src="${c.imagen}" alt="${c.nombre}" class="et-course-thumb"
                             onerror="this.src='{{ asset('assets/img/bg2.png') }}'">
                        <div>
                            <span class="et-course-name" title="${c.nombre}">${truncateServer(c.nombre, 40)}</span>
                            <div class="et-course-cats">${c.codigo} ${tipo}</div>
                        </div>
                    </div>
                </td>
                <td><span class="et-course-cats">${cats}</span></td>
                <td class="et-total-badge text-center">${c.total}</td>
                <td>
                    <div class="et-bar-wrap">
                        <div class="et-bar-fill" style="width:${pct}%"></div>
                    </div>
                </td>
            </tr>`;
        });

        tbody.innerHTML = html;
    }

    // ── Weekday ────────────────────────────────────────────
    function renderWeekday(weekdays, kpis) {
        const container = document.getElementById('etWeekdayChart');
        if (!weekdays || weekdays.length === 0) {
            container.innerHTML = '<div class="et-empty"><i class="bi bi-inbox"></i>Sin datos</div>';
            return;
        }

        const max = Math.max(...weekdays.map(d => d.total), 1);
        let bestDay = weekdays[0];
        weekdays.forEach(d => { if (d.total > bestDay.total) bestDay = d; });

        // Set KPI best day
        document.getElementById('kpiMejorDia').textContent = bestDay.dia;
        document.getElementById('kpiMejorDiaTotal').textContent = bestDay.total + ' inscritos';

        let html = '<div class="et-weekday-bars">';
        weekdays.forEach(d => {
            const h = Math.max((d.total / max) * 100, 3);
            const isBest = d.dia === bestDay.dia;
            const bg = isBest
                ? 'background: linear-gradient(180deg, #fbbf24, #f59e0b);'
                : 'background: linear-gradient(180deg, #145da0, #2c7be5);';
            html += `<div style="flex:1;display:flex;flex-direction:column;align-items:center;">
                <div class="et-weekday-val">${d.total}</div>
                <div class="et-weekday-bar" style="height:${h}%;${bg}" title="${d.dia}: ${d.total} inscripciones"></div>
                <div class="et-weekday-label">${d.dia}</div>
            </div>`;
        });
        html += '</div>';
        container.innerHTML = html;
    }

    // ── Helpers ────────────────────────────────────────────
    function truncate(str, max) {
        return str && str.length > max ? str.substring(0, max) + '…' : str;
    }
    // Alias para uso en template literals
    window.truncateServer = truncate;
})();
</script>
