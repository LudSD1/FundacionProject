

@extends('layout')

@section('titulo')
    Lista de Participantes: {{ $cursos->nombreCurso }}
@endsection

@section('content')

<div class="container my-4">
<div class="tbl-card">

    <div class="tbl-card-hero">

        <div class="tbl-hero-left">
            <a href="{{ route('Curso', $cursos->codigoCurso) }}"
               class="tbl-hero-btn tbl-hero-btn-glass prt-back-btn mb-2">
                <i class="bi bi-arrow-left-circle-fill"></i>
                <span>Volver</span>
            </a>
            <div class="tbl-hero-eyebrow">
                <i class="bi bi-people-fill"></i> Participantes
            </div>
            <h2 class="tbl-hero-title">Lista de Participantes</h2>
            <p class="tbl-hero-sub">
                Curso: <strong>{{ $cursos->nombreCurso }}</strong>
            </p>
        </div>

        <div class="tbl-hero-controls">
            @if(auth()->user()->id == $cursos->docente_id || auth()->user()->hasRole('Administrador'))

                {{-- Botones de acción --}}
                <div class="d-flex gap-2 mb-2 flex-wrap justify-content-end">
                    <a class="tbl-hero-btn tbl-hero-btn-glass"
                       href="{{ route('listaretirados', encrypt($cursos->id)) }}">
                        <i class="bi bi-person-x-fill"></i>
                        <span>Retirados</span>
                    </a>

                    @if($cursos->tipo == 'congreso')
                    <a class="tbl-hero-btn tbl-hero-btn-primary"
                       href="{{ route('certificadosCongreso.generar', $cursos->id) }}">
                        <i class="bi bi-award-fill"></i>
                        <span>Certificados</span>
                    </a>
                    @endif

                    <a class="tbl-hero-btn tbl-hero-btn-primary"
                       href="{{ route('lista', encrypt($cursos->id)) }}">
                        <i class="bi bi-download"></i>
                        <span>Descargar Lista</span>
                    </a>
                </div>

                {{-- Buscador y Filtros --}}
                <div class="d-flex gap-2 flex-wrap justify-content-end align-items-center">

                    {{-- Filtro estado (solo Admin) --}}
                    @role('Administrador')
                    <div class="tbl-hero-select-wrap">
                        <i class="bi bi-funnel-fill tbl-hero-select-icon"></i>
                        <select class="tbl-hero-select" id="prtStatusFilter">
                            <option value="">Todos los estados</option>
                            @if($cursos->tipo == 'curso')
                                <option value="pago-completado">Pago Completado</option>
                                <option value="pago-revision">Pago en Revisión</option>
                            @elseif($cursos->tipo == 'congreso')
                                <option value="certificado">Certificado</option>
                                <option value="sin-certificado">Sin certificado</option>
                            @endif
                        </select>
                    </div>
                    @endrole

                    {{-- Buscador --}}
                    <div class="tbl-hero-search">
                        <i class="bi bi-search tbl-hero-search-icon"></i>
                        <input type="text"
                               class="tbl-hero-search-input"
                               id="prtSearchInput"
                               placeholder="Buscar por nombre, email…"
                               autocomplete="off">
                    </div>
                </div>

            @endif
        </div>

    </div>{{-- /tbl-card-hero --}}

    {{-- Barra de contadores --}}
    <div class="tbl-filter-bar">
        <div class="tbl-filter-bar-left">
            <i class="bi bi-people-fill"></i>
            Mostrando: <strong id="prtVisible">{{ $inscritos->where('cursos_id', $cursos->id)->count() }}</strong>
            de <strong>{{ $inscritos->where('cursos_id', $cursos->id)->count() }}</strong>
            @role('Administrador')
                @if($cursos->tipo == 'curso')
                · <span class="ms-2">Pendientes: <strong>{{ $inscritos->where('cursos_id', $cursos->id)->where('pago_completado', false)->count() }}</strong></span>
                @elseif($cursos->tipo == 'congreso')
                · <span class="ms-2">Pendientes: <strong>{{ $inscritos->where('cursos_id', $cursos->id)->where('certificado', false)->count() }}</strong></span>
                @endif
            @endrole
        </div>
    </div>

    <div class="p-0"> {{-- Cambiado de p-4 a p-0 para que la tabla use todo el ancho --}}

        <div id="prtMassActions" class="prt-mass-actions" style="display:none; margin: 1rem;">
            <div class="prt-mass-actions-inner">
                <div class="prt-mass-info">
                    <div class="prt-mass-icon"><i class="bi bi-check-all"></i></div>
                    <div>
                        <strong><span id="prtSelectedCount">0</span> participantes seleccionados</strong>
                        <div class="prt-mass-sub">Elige una acción para aplicar a los seleccionados</div>
                    </div>
                </div>
                <div class="prt-mass-btns">
                    <button class="tbl-hero-btn tbl-hero-btn-danger" id="prtBtnRetirar">
                        <i class="bi bi-person-x-fill"></i> Retirar
                    </button>
                    @if($cursos->tipo == 'congreso')
                    <button class="tbl-hero-btn tbl-hero-btn-primary" id="prtBtnCertificados">
                        <i class="bi bi-award-fill"></i> Certificados
                    </button>
                    @endif
                    <button class="tbl-hero-btn tbl-hero-btn-glass prt-btn-cancel" id="prtBtnDeselect">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </button>
                </div>
            </div>
        </div>

        {{-- ╔══════════════════════════════════════╗
             ║  TABLA                              ║
             ╚══════════════════════════════════════╝ --}}
        <div class="table-container-modern">
            <table class="table-modern" id="prtTable">
                <thead>
                    <tr>
                        @if(auth()->user()->hasRole('Docente') || auth()->user()->hasRole('Administrador'))
                        <th style="width:5%" class="text-center">
                            <input type="checkbox" class="prt-checkbox" id="prtMasterCheck">
                        </th>
                        @endif
                        <th style="width:4%">
                            <div class="th-content"><i class="bi bi-hash"></i></div>
                        </th>
                        <th style="width:38%">
                            <div class="th-content">
                                <i class="bi bi-person-fill"></i><span>Participante</span>
                            </div>
                        </th>
                        <th style="width:15%">
                            <div class="th-content">
                                <i class="bi bi-telephone-fill"></i><span>Contacto</span>
                            </div>
                        </th>
                        @role('Administrador')
                        <th style="width:13%" class="text-center">
                            <div class="th-content justify-content-center">
                                <i class="bi bi-info-circle-fill"></i><span>Estado</span>
                            </div>
                        </th>
                        @endrole
                        <th class="text-center">
                            <div class="th-content justify-content-center">
                                <i class="bi bi-gear-fill"></i><span>Acciones</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inscritos as $inscrito)
                    @if($inscrito->cursos_id == $cursos->id)
                    <tr class="prt-row"
                        data-pago="{{ $inscrito->pago_completado ? 'completado' : 'pendiente' }}"
                        data-cert="{{ $inscrito->certificado ? 'certificado' : 'sin-certificado' }}">

                        {{-- Checkbox --}}
                        @if(auth()->user()->hasRole('Docente') || auth()->user()->hasRole('Administrador'))
                        <td class="text-center">
                            <input type="checkbox"
                                   class="prt-checkbox prt-row-check"
                                   value="{{ $inscrito->id }}">
                        </td>
                        @endif

                        {{-- Nro --}}
                        <td><span class="row-number">{{ $loop->iteration }}</span></td>

                        {{-- Participante --}}
                        <td>
                            <div class="prt-student">
                                <div class="tbl-avatar">
                                    {{ strtoupper(substr($inscrito->estudiantes->name ?? 'E', 0, 1)) }}
                                </div>
                                <div class="prt-student-info">
                                    <div class="prt-student-name">
                                        {{ $inscrito->estudiantes->name      ?? 'Estudiante Eliminado' }}
                                        {{ $inscrito->estudiantes->lastname1 ?? '' }}
                                        {{ $inscrito->estudiantes->lastname2 ?? '' }}
                                    </div>
                                    @if(isset($inscrito->estudiantes->email))
                                    <div class="prt-student-email">
                                        <i class="bi bi-envelope me-1"></i>
                                        {{ $inscrito->estudiantes->email }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Contacto --}}
                        <td>
                            @if($inscrito->estudiantes->Celular ?? false)
                            <div class="prt-phone">
                                <i class="bi bi-telephone-fill"></i>
                                +{{ $inscrito->estudiantes->Celular }}
                            </div>
                            @else
                            <span class="prt-no-data">Sin celular</span>
                            @endif
                        </td>

                        {{-- Estado (Admin) --}}
                        @role('Administrador')
                        <td class="text-center">
                            @if($cursos->tipo == 'curso')
                                @if($inscrito->pago_completado)
                                    <span class="status-badge status-active">
                                        <i class="bi bi-check-circle-fill"></i> Pagado
                                    </span>
                                @else
                                    <span class="status-badge status-pending">
                                        <i class="bi bi-clock-history"></i> En Revisión
                                    </span>
                                @endif
                            @elseif($cursos->tipo == 'congreso')
                                @if($inscrito->certificado)
                                    <span class="status-badge status-active">
                                        <i class="bi bi-award-fill"></i> Certificado
                                    </span>
                                @else
                                    <span class="status-badge status-pending">
                                        <i class="bi bi-award"></i> Pendiente
                                    </span>
                                @endif
                            @endif
                        </td>
                        @endrole

                        {{-- Acciones --}}
                        <td>
                            <div class="action-buttons-cell">

                                <a class="btn-action-modern btn-info"
                                   href="{{ route('perfil', [encrypt($inscrito->estudiantes->id)]) }}"
                                   title="Ver Perfil">
                                    <i class="bi bi-person-badge-fill"></i>
                                </a>

                                @if(auth()->user()->hasRole('Docente') || auth()->user()->hasRole('Administrador'))

                                <form action="{{ route('quitarInscripcion', $inscrito->id) }}"
                                      method="POST"
                                      class="prt-form prt-form-retirar">
                                    @csrf
                                    <button type="submit"
                                            class="btn-action-modern btn-delete"
                                            title="Retirar Estudiante">
                                        <i class="bi bi-person-x-fill"></i>
                                    </button>
                                </form>

                                @if($cursos->tipo == 'congreso')
                                <a class="btn-action-modern btn-view"
                                   href="{{ !isset($inscrito->certificado)
                                        ? route('certificadosCongreso.generar.admin', encrypt($inscrito->id))
                                        : route('certificados.reenviar.email', encrypt($inscrito->id)) }}"
                                   title="{{ !isset($inscrito->certificado) ? 'Generar Certificado' : 'Reenviar Certificado' }}">
                                    <i class="bi bi-award-fill"></i>
                                </a>
                                @endif

                                @if($cursos->tipo == 'curso')
                                <a class="btn-action-modern btn-info"
                                   href="{{ route('boletin', [encrypt($inscrito->id)]) }}"
                                   title="Ver Boletín">
                                    <i class="bi bi-journal-text"></i>
                                </a>
                                @endif

                                @endif

                            </div>
                        </td>

                    </tr>
                    @endif
                    @empty
                    <tr>
                        <td colspan="{{ auth()->user()->hasRole('Administrador') ? '6' : '5' }}">
                            <div class="empty-state-table">
                                <div class="empty-icon-table"><i class="bi bi-people"></i></div>
                                <h5 class="empty-title-table">No hay participantes inscritos</h5>
                                <p class="empty-text-table">Los estudiantes aparecerán aquí conforme se inscriban.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>{{-- /p-0 --}}


{{-- ╔══════════════════════════════════════╗
     ║  ESTADÍSTICAS RÁPIDAS              ║
     ╚══════════════════════════════════════╝ --}}
<div class="row g-3 mt-2">

    <div class="col-md-3">
        <div class="st-card st-card--blue">
            <div class="st-card-body">
                <div>
                    <div class="st-label">Inscritos Totales</div>
                    {{-- FIX 9: sin text-success/warning/danger --}}
                    <div class="st-num">{{ $inscritos->where('cursos_id', $cursos->id)->count() }}</div>
                </div>
                <div class="st-icon st-icon--blue">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
            <div class="st-bar st-bar--blue"></div>
        </div>
    </div>

    @role('Administrador')
    @if($cursos->tipo == 'curso')
    <div class="col-md-3">
        <div class="st-card st-card--green">
            <div class="st-card-body">
                <div>
                    <div class="st-label">Pagos Verificados</div>
                    <div class="st-num">{{ $inscritos->where('cursos_id', $cursos->id)->where('pago_completado', true)->count() }}</div>
                </div>
                <div class="st-icon st-icon--green">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
            <div class="st-bar st-bar--green"></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="st-card st-card--orange">
            <div class="st-card-body">
                <div>
                    <div class="st-label">Pagos Pendientes</div>
                    <div class="st-num">{{ $inscritos->where('cursos_id', $cursos->id)->where('pago_completado', false)->count() }}</div>
                </div>
                <div class="st-icon st-icon--orange">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
            <div class="st-bar st-bar--orange"></div>
        </div>
    </div>
    @elseif($cursos->tipo == 'congreso')
    <div class="col-md-3">
        <div class="st-card st-card--green">
            <div class="st-card-body">
                <div>
                    <div class="st-label">Certificados OK</div>
                    <div class="st-num">{{ $inscritos->where('cursos_id', $cursos->id)->where('certificado', true)->count() }}</div>
                </div>
                <div class="st-icon st-icon--green">
                    <i class="bi bi-award-fill"></i>
                </div>
            </div>
            <div class="st-bar st-bar--green"></div>
        </div>
    </div>
    @endif
    @endrole

    <div class="col-md-3">
        <div class="st-card st-card--red">
            <div class="st-card-body">
                <div>
                    <div class="st-label">En Pantalla</div>
                    <div class="st-num" id="prtStatsVisible">
                        {{ $inscritos->where('cursos_id', $cursos->id)->count() }}
                    </div>
                </div>
                <div class="st-icon st-icon--red">
                    <i class="bi bi-eye-fill"></i>
                </div>
            </div>
            <div class="st-bar st-bar--red"></div>
        </div>
    </div>

</div>
</div>{{-- /container-fluid --}}

@endsection


<style>
/* ════════════════════
   TOOLBAR
════════════════════ */
.adm-tabs-header .tbl-hero-search {
    position: relative;
    width: 100%;
}
.adm-tabs-header .tbl-hero-search-icon {
    position: absolute;
    left: 1.25rem;
    top: 50%;
    transform: translateY(-50%);
    z-index: 5;
}
.adm-tabs-header .tbl-hero-search-input {
    padding-left: 3rem !important;
}
.adm-tabs-header .tbl-hero-select-wrap {
    position: relative;
}
.adm-tabs-header .tbl-hero-select-icon {
    position: absolute;
    left: 1.25rem;
    top: 50%;
    transform: translateY(-50%);
    z-index: 5;
}
.adm-tabs-header .tbl-hero-select {
    padding-left: 2.5rem !important;
}

/* Inputs en fondo claro */
.prt-input-light {
    background:   #fff !important;
    color:        #0f172a !important;
    border-color: #d1dce8 !important;
    width:        100% !important;
}
.prt-input-light::placeholder { color: #94a3b8 !important; }
.prt-input-light:focus {
    border-color: #2a81c2 !important;
    box-shadow:   0 0 0 3px rgba(42,129,194,.13) !important;
    width:        100% !important;
    background:   #fff !important;
}
.prt-select-light {
    background-color: #fff !important;
    color:            #374151 !important;
    border-color:     #d1dce8 !important;
}
.prt-icon-muted { color: #94a3b8 !important; }

/* ════════════════════
   ACCIONES MASIVAS
   FIX 5: animación del sistema
════════════════════ */
.prt-mass-actions {
    background:    rgba(20,93,160,.06);
    border:        1.5px solid rgba(20,93,160,.18);
    border-radius: 12px;
    margin-bottom: 1rem;
    animation:     prtFadeIn .25s ease both;
}
@keyframes prtFadeIn {
    from { opacity: 0; transform: translateY(-6px); }
    to   { opacity: 1; transform: translateY(0); }
}
.prt-mass-actions-inner {
    display:         flex;
    align-items:     center;
    justify-content: space-between;
    padding:         .9rem 1.1rem;
    gap:             .8rem;
    flex-wrap:       wrap;
}
.prt-mass-info {
    display:     flex;
    align-items: center;
    gap:         .75rem;
}
.prt-mass-icon {
    width:           40px; height: 40px;
    border-radius:   50%;
    background:      rgba(20,93,160,.12);
    color:           #145da0;
    display:         flex;
    align-items:     center;
    justify-content: center;
    font-size:       1.1rem;
    flex-shrink:     0;
}
.prt-mass-sub   { font-size: .76rem; color: #64748b; margin-top: .12rem; }
.prt-mass-btns  { display: flex; gap: .5rem; flex-wrap: wrap; }
.prt-btn-cancel { color: #64748b !important; }

/* ════════════════════
   CHECKBOX PERSONALIZADO
════════════════════ */
.prt-checkbox {
    width:         1.1rem;
    height:        1.1rem;
    border-radius: 5px;
    cursor:        pointer;
    accent-color:  #145da0;
}

/* ════════════════════
   CELDA PARTICIPANTE
════════════════════ */
.prt-student {
    display:     flex;
    align-items: center;
    gap:         .7rem;
}
.prt-student-info  { display: flex; flex-direction: column; gap: .1rem; }
.prt-student-name  { font-size: .88rem; font-weight: 700; color: #0f172a; }
.prt-student-email { font-size: .72rem; color: #94a3b8; display: flex; align-items: center; }

.prt-phone {
    display:     flex;
    align-items: center;
    gap:         .35rem;
    font-size:   .83rem;
    color:       #374151;
}
.prt-phone i { color: #145da0; font-size: .78rem; }
.prt-no-data { font-size: .76rem; color: #c5d0dc; font-style: italic; }

/* Botón volver compacto en el hero */
.prt-back-btn { padding: .32rem .75rem !important; font-size: .76rem !important; margin-bottom: .5rem; }

/* ── Responsive ── */
@media (max-width: 768px) {
    .prt-toolbar         { flex-direction: column; align-items: stretch; }
    .prt-toolbar-right   { flex-direction: column; align-items: flex-start; }
    .prt-mass-actions-inner { flex-direction: column; align-items: flex-start; }
    .prt-mass-btns       { width: 100%; }
    .tbl-hero-btn span   { display: none; }
}
</style>


<script>
(function () {
    document.addEventListener('DOMContentLoaded', function () {

        /* ── Referencias ── */
        const searchInput   = document.getElementById('prtSearchInput');
        const statusFilter  = document.getElementById('prtStatusFilter');
        const visibleSpan   = document.getElementById('prtVisible');
        const statsSpan     = document.getElementById('prtStatsVisible');
        const masterCheck   = document.getElementById('prtMasterCheck');
        const massActions   = document.getElementById('prtMassActions');
        const selectedSpan  = document.getElementById('prtSelectedCount');

        /* ── Filtrado ── */
        function applyFilters() {
            const rows = document.querySelectorAll('.prt-row');
            const q  = searchInput?.value.toLowerCase().trim() || '';
            const fv = statusFilter?.value || '';
            let vis  = 0;

            rows.forEach(row => {
                const matchQ = !q || row.textContent.toLowerCase().includes(q);
                let matchF   = true;

                if (fv) {
                    if (fv === 'pago-completado') matchF = row.dataset.pago === 'completado';
                    else if (fv === 'pago-revision') matchF = row.dataset.pago === 'pendiente';
                    else if (fv === 'certificado' || fv === 'sin-certificado')
                        matchF = row.dataset.cert === fv;
                }

                const show = matchQ && matchF;
                row.style.display = show ? '' : 'none';
                if (show) vis++;
            });

            if (visibleSpan)  visibleSpan.textContent  = vis;
            if (statsSpan)    statsSpan.textContent     = vis;
        }

        if (searchInput) {
            searchInput.addEventListener('input', applyFilters);
            searchInput.addEventListener('keyup', applyFilters);
        }

        if (statusFilter) {
            statusFilter.addEventListener('change', applyFilters);
        }

        /* ── Selección masiva ── */
        function rowChecks() {
            return document.querySelectorAll('.prt-row-check');
        }

        function updateMass() {
            const checkedRows = document.querySelectorAll('.prt-row-check:checked');
            const n = checkedRows.length;
            if (selectedSpan)  selectedSpan.textContent  = n;
            if (massActions)   massActions.style.display = n > 0 ? 'block' : 'none';
        }

        if (masterCheck) {
            masterCheck.addEventListener('change', function () {
                const isChecked = this.checked;
                rowChecks().forEach(cb => {
                    if (cb.closest('tr')?.style.display !== 'none') cb.checked = isChecked;
                });
                updateMass();
            });
        }

        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('prt-row-check')) updateMass();
        });

        const deselectBtn = document.getElementById('prtBtnDeselect');
        if (deselectBtn) {
            deselectBtn.addEventListener('click', () => {
                rowChecks().forEach(cb => cb.checked = false);
                if (masterCheck) masterCheck.checked = false;
                updateMass();
            });
        }

        /* ── Confirmar retiro individual ── */
        document.querySelectorAll('.prt-form-retirar').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const f = this;
                Swal.fire({
                    title            : '¿Retirar estudiante?',
                    text             : 'El estudiante será quitado de la lista. Puede revertirse desde retirados.',
                    icon             : 'warning',
                    showCancelButton : true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor : '#145da0',
                    confirmButtonText : 'Sí, retirar',
                    cancelButtonText  : 'Cancelar',
                    reverseButtons    : true,
                }).then(r => { if (r.isConfirmed) f.submit(); });
            });
        });

        /* ── Retiro masivo ── */
        const btnRetirarMasivo = document.getElementById('prtBtnRetirar');
        if (btnRetirarMasivo) {
            btnRetirarMasivo.addEventListener('click', function () {
                const ids = Array.from(document.querySelectorAll('.prt-row-check:checked'))
                                .map(cb => cb.value);
                if (!ids.length) return;

                Swal.fire({
                    title            : `¿Retirar ${ids.length} participantes?`,
                    text             : 'Se retirarán todos los estudiantes seleccionados.',
                    icon             : 'warning',
                    showCancelButton : true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor : '#145da0',
                    confirmButtonText : 'Sí, retirar todos',
                    cancelButtonText  : 'Cancelar',
                    reverseButtons    : true,
                    showLoaderOnConfirm: true,
                    preConfirm: () => fetch('{{ route('cursos.retirarMasivo') }}', {
                        method  : 'POST',
                        headers : {
                            'Content-Type' : 'application/json',
                            'X-CSRF-TOKEN' : '{{ csrf_token() }}',
                            'Accept'       : 'application/json',
                        },
                        body: JSON.stringify({ inscripciones: ids, curso_id: {{ $cursos->id }} }),
                    })
                    .then(r => r.json())
                    .then(d => { if (!d.success) throw new Error(d.message); return d; })
                    .catch(err => Swal.showValidationMessage(`Error: ${err.message}`)),
                }).then(r => {
                    if (r.isConfirmed)
                        Swal.fire({
                            icon             : 'success',
                            title            : '¡Listo!',
                            text             : 'Participantes retirados correctamente.',
                            confirmButtonColor: '#145da0',
                        }).then(() => window.location.reload());
                });
            });
        }

        /* ── Certificados masivos ── */
        const btnCertificadosMasivos = document.getElementById('prtBtnCertificados');
        if (btnCertificadosMasivos) {
            btnCertificadosMasivos.addEventListener('click', function () {
                const ids = Array.from(document.querySelectorAll('.prt-row-check:checked'))
                                .map(cb => cb.value);
                Swal.fire({
                    title            : '¿Generar certificados?',
                    text             : `Se procesarán ${ids.length} certificados.`,
                    icon             : 'question',
                    showCancelButton : true,
                    confirmButtonColor: '#145da0',
                    cancelButtonColor : '#94a3b8',
                    confirmButtonText : 'Sí, generar',
                    cancelButtonText  : 'Cancelar',
                }).then(r => {
                    if (r.isConfirmed)
                        Swal.fire({
                            icon             : 'info',
                            title            : 'En desarrollo',
                            text             : 'La generación masiva estará disponible pronto.',
                            confirmButtonColor: '#145da0',
                        });
                });
            });
        }

    });
})();
</script>
