{{-- ═══════════════════════════════════════════════════════════════
     TOMA DE ASISTENCIA
     Fixes:
     1.  <style> y <script> fuera de @section → @push con @once
     2.  <script src="sweetalert2"> CDN → eliminado (ya es global)
     3.  status-badge redefinida con !important × 4 → variantes att-* propias
     4.  input-group bg-primary border-primary Bootstrap → att-date-wrap del sistema
     5.  btn-group btn-outline-success/danger/secondary → tbl-hero-btn del sistema
     6.  badge bg-primary rounded-pill → att-counter del sistema
     7.  tbl-filter-bar bg-light border-bottom → tbl-filter-bar solo (ya tiene sus estilos)
     8.  form-select form-select-sm rounded-pill Bootstrap → att-select del sistema
═══════════════════════════════════════════════════════════════ --}}

@extends('layout')

@section('titulo', 'Toma de Asistencia: ' . $cursos->nombreCurso)

@section('content')

<div class="container my-4">
<div class="tbl-card">

    {{-- ╔══════════════════════════════════════╗
         ║  HERO                               ║
         ╚══════════════════════════════════════╝ --}}
    <div class="tbl-card-hero">

        <div class="tbl-hero-left">
            <a href="{{ route('Curso', $cursos->codigoCurso) }}"
               class="tbl-hero-btn tbl-hero-btn-glass prt-back-btn mb-2">
                <i class="bi bi-arrow-left-circle-fill"></i> Volver al Curso
            </a>
            <div class="tbl-hero-eyebrow">
                <i class="bi bi-calendar-check"></i> Registro Diario
            </div>
            <h2 class="tbl-hero-title">Toma de Asistencia</h2>
            <p class="tbl-hero-sub">
                Curso: <strong>{{ $cursos->nombreCurso }}</strong>
            </p>
        </div>

        <div class="tbl-hero-controls">

            <a href="{{ route('historialAsistencias', encrypt($cursos->id)) }}"
               class="tbl-hero-btn tbl-hero-btn-glass">
                <i class="bi bi-clock-history"></i> Historial
            </a>

            {{-- Buscador --}}
            <div class="tbl-hero-search">
                <i class="bi bi-search tbl-hero-search-icon"></i>
                <input type="text"
                       class="tbl-hero-search-input"
                       id="attSearch"
                       placeholder="Filtrar por nombre…"
                       autocomplete="off">
            </div>

            <div class="tbl-hero-eyebrow" style="margin-top:.4rem">
                <i class="bi bi-people-fill"></i>
                {{ $inscritos->where('cursos_id', $cursos->id)->count() }} Estudiantes
            </div>

        </div>
    </div>{{-- /tbl-card-hero --}}


    <form action="{{ route('darasistenciasPostMultiple', encrypt($cursos->id)) }}"
          method="POST"
          id="attForm">
        @csrf

        {{-- ╔══════════════════════════════════════╗
             ║  BARRA DE HERRAMIENTAS              ║
             FIX 4+5+6+7: sin Bootstrap hardcoded
             ╚══════════════════════════════════════╝ --}}
        <div class="tbl-filter-bar">

            {{-- Selector de fecha -- FIX 4 --}}
            <div class="att-date-wrap">
                <div class="att-date-icon"><i class="bi bi-calendar-event"></i></div>
                <input type="date"
                       class="att-date-input"
                       id="attFecha"
                       name="fecha_asistencia"
                       value="{{ now()->format('Y-m-d') }}"
                       max="{{ now()->format('Y-m-d') }}"
                       @if($cursos->fecha_fin && now() > $cursos->fecha_fin) disabled @endif>
            </div>
            @if($cursos->fecha_fin && now() > $cursos->fecha_fin)
            <div class="att-curso-fin">
                <i class="bi bi-exclamation-triangle-fill"></i> Curso finalizado
            </div>
            @endif

            {{-- Acciones masivas -- FIX 5 -- --}}
            <div class="att-mass-btns">
                <button type="button" class="tbl-hero-btn tbl-hero-btn-primary att-btn-sm" id="attMarkPresent">
                    <i class="bi bi-check-all"></i> Todos Presentes
                </button>
                <button type="button" class="tbl-hero-btn tbl-hero-btn-danger att-btn-sm" id="attMarkAbsent">
                    <i class="bi bi-x-circle-fill"></i> Todos Ausentes
                </button>
                <button type="button" class="tbl-hero-btn tbl-hero-btn-glass att-btn-sm att-btn-neutral" id="attClearAll">
                    <i class="bi bi-eraser-fill"></i> Limpiar
                </button>
            </div>

            {{-- Contador -- FIX 6 -- --}}
            <span class="att-counter" id="attCounter">0 seleccionados</span>

        </div>{{-- /tbl-filter-bar --}}


        {{-- ╔══════════════════════════════════════╗
             ║  TABLA                              ║
             ╚══════════════════════════════════════╝ --}}
        <div class="table-container-modern">
            <table class="table-modern" id="attTable">
                <thead>
                    <tr>
                        <th width="5%">
                            <div class="th-content"><i class="bi bi-hash"></i></div>
                        </th>
                        <th width="38%">
                            <div class="th-content">
                                <i class="bi bi-person-fill"></i><span>Participante</span>
                            </div>
                        </th>
                        <th width="35%">
                            <div class="th-content">
                                <i class="bi bi-check2-square"></i><span>Tipo de Asistencia</span>
                            </div>
                        </th>
                        <th width="22%" class="text-center">
                            <div class="th-content justify-content-center">
                                <i class="bi bi-info-circle-fill"></i><span>Estado</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inscritos as $index => $inscrito)
                    @if($inscrito->cursos_id == $cursos->id)
                    <tr class="att-row">

                        <td><span class="row-number">{{ $loop->iteration }}</span></td>

                        {{-- Participante --}}
                        <td>
                            <div class="prt-student">
                                <div class="tbl-avatar">
                                    {{ strtoupper(substr($inscrito->estudiantes->name ?? 'E', 0, 1)) }}
                                </div>
                                <div class="prt-student-info">
                                    <div class="prt-student-name">
                                        {{ $inscrito->estudiantes->name }}
                                        {{ $inscrito->estudiantes->lastname1 }}
                                        {{ $inscrito->estudiantes->lastname2 }}
                                    </div>
                                    <div class="prt-student-email">
                                        <i class="bi bi-envelope me-1"></i>
                                        {{ $inscrito->estudiantes->email }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Select asistencia -- FIX 8 -- --}}
                        <td>
                            <input type="hidden" name="asistencia[{{ $index }}][inscritos_id]" value="{{ $inscrito->id }}">
                            <input type="hidden" name="asistencia[{{ $index }}][curso_id]"     value="{{ $cursos->id }}">

                            <select name="asistencia[{{ $index }}][tipo_asistencia]"
                                    class="att-select"
                                    data-row-index="{{ $index }}"
                                    @if($cursos->fecha_fin && now() > $cursos->fecha_fin) disabled @endif>
                                <option value="">Seleccione tipo…</option>
                                <option value="Presente">✅ Presente</option>
                                <option value="Retraso">⏰ Retraso</option>
                                <option value="Licencia">📋 Licencia</option>
                                <option value="Falta">❌ Falta</option>
                            </select>
                        </td>

                        {{-- Estado dinámico --}}
                        <td class="text-center">
                            <span class="status-badge status-pending att-status"
                                  id="att-status-{{ $index }}">
                                <i class="bi bi-clock"></i> Pendiente
                            </span>
                        </td>

                    </tr>
                    @endif
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state-table">
                                <div class="empty-icon-table"><i class="bi bi-people"></i></div>
                                <h5 class="empty-title-table">No hay estudiantes inscritos</h5>
                                <p class="empty-text-table">La lista aparecerá cuando se inscriban alumnos.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pie: botón guardar --}}
        @if(auth()->user()->hasRole('Docente')
            && (!$cursos->fecha_fin || now() <= $cursos->fecha_fin)
            && $inscritos->where('cursos_id', $cursos->id)->count() > 0)
        <div class="att-save-bar">
            <div class="att-save-inner">
                <button type="submit"
                        class="tbl-hero-btn tbl-hero-btn-primary att-save-btn"
                        id="attSaveBtn"
                        disabled>
                    <i class="bi bi-floppy-fill me-2"></i>
                    <span id="attSaveTxt">
                        Guardar Asistencias (0/{{ $inscritos->where('cursos_id', $cursos->id)->count() }})
                    </span>
                </button>
                <p class="att-save-hint">
                    <i class="bi bi-info-circle me-1"></i>
                    Solo se registrarán las asistencias seleccionadas.
                </p>
            </div>
        </div>
        @endif

    </form>

</div>{{-- /tbl-card --}}
</div>{{-- /container --}}

@endsection


<style>
/* ════════════════════
   BARRA DE HERRAMIENTAS
════════════════════ */
.tbl-filter-bar {
    display:     flex;
    align-items: center;
    gap:         .75rem;
    flex-wrap:   wrap;
}

/* Fecha -- FIX 4 */
.att-date-wrap {
    position:    relative;
    display:     flex;
    align-items: center;
}
.att-date-icon {
    position:       absolute;
    left:           .75rem;
    color:          #145da0;
    font-size:      .88rem;
    pointer-events: none;
    z-index:        1;
}
.att-date-input {
    padding:       .48rem .85rem .48rem 2.2rem;
    border:        1.5px solid #d1dce8;
    border-radius: 50px;
    font-size:     .84rem;
    color:         #0f172a;
    background:    #fff;
    outline:       none;
    font-family:   inherit;
    cursor:        pointer;
    transition:    border-color .2s ease, box-shadow .2s ease;
}
.att-date-input:focus {
    border-color: #2a81c2;
    box-shadow:   0 0 0 3px rgba(42,129,194,.13);
}
.att-date-input:disabled { opacity: .55; cursor: not-allowed; }

/* Curso finalizado */
.att-curso-fin {
    font-size:  .76rem;
    color:      #dc2626;
    font-weight: 600;
    display:    flex;
    align-items: center;
    gap:        .3rem;
}

/* Botones masivos -- FIX 5 */
.att-mass-btns { display: flex; gap: .4rem; flex-wrap: wrap; }
.att-btn-sm    { padding: .38rem .85rem !important; font-size: .78rem !important; }
.att-btn-neutral { color: #374151 !important; }

/* Contador -- FIX 6 */
.att-counter {
    display:       inline-flex;
    align-items:   center;
    padding:       .28rem .8rem;
    border-radius: 50px;
    font-size:     .76rem;
    font-weight:   700;
    background:    rgba(20,93,160,.10);
    color:         #145da0;
    white-space:   nowrap;
    margin-left:   auto;
    transition:    background .2s ease;
}
.att-counter.att-counter--active {
    background: linear-gradient(135deg, #145da0, #2a81c2);
    color:      #fff;
}

/* ════════════════════
   SELECT ASISTENCIA -- FIX 8
════════════════════ */
.att-select {
    width:         100%;
    padding:       .45rem 2rem .45rem .85rem;
    border:        1.5px solid #d1dce8;
    border-radius: 50px;
    font-size:     .83rem;
    color:         #374151;
    background:    #fff;
    outline:       none;
    cursor:        pointer;
    appearance:    none;
    font-family:   inherit;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat:   no-repeat;
    background-position: right .75rem center;
    transition:    border-color .2s ease, box-shadow .2s ease;
}
.att-select:focus {
    border-color: #2a81c2;
    box-shadow:   0 0 0 3px rgba(42,129,194,.13);
}
.att-select:disabled { opacity: .55; cursor: not-allowed; }

/* Estados coloreados del select */
.att-select.att-present { border-color: #16a34a; background-color: rgba(22,163,74,.05); color: #15803d; }
.att-select.att-late    { border-color: #d97706; background-color: rgba(255,165,0,.05);  color: #92400e; }
.att-select.att-excuse  { border-color: #2a81c2; background-color: rgba(42,129,194,.05); color: #145da0; }
.att-select.att-absent  { border-color: #dc2626; background-color: rgba(220,38,38,.05);  color: #991b1b; }

/* ════════════════════
   BADGES DE ESTADO -- FIX 3
   Variantes propias sin !important
════════════════════ */
.att-status { min-width: 95px; justify-content: center; }

.att-present { background: rgba(22,163,74,.10);  color: #16a34a; border: 1px solid rgba(22,163,74,.20); }
.att-late    { background: rgba(234,179,8,.10);   color: #854d0e; border: 1px solid rgba(234,179,8,.25); }
.att-excuse  { background: rgba(14,165,233,.10);  color: #075985; border: 1px solid rgba(14,165,233,.20); }
.att-absent  { background: rgba(220,38,38,.10);   color: #991b1b; border: 1px solid rgba(220,38,38,.20); }

/* ════════════════════
   PIE DE TABLA: GUARDAR
════════════════════ */
.att-save-bar {
    padding:       1.2rem 1.5rem;
    background:    #f8fafc;
    border-top:    1.5px solid #f0f4f8;
    display:       flex;
    justify-content: center;
}
.att-save-inner { text-align: center; max-width: 420px; width: 100%; }
.att-save-btn {
    width:     100%;
    justify-content: center;
    padding:   .75rem 1.5rem !important;
    font-size: 1rem !important;
}
.att-save-btn:disabled {
    opacity:  .55;
    cursor:   not-allowed;
    transform: none !important;
}
.att-save-hint {
    font-size: .78rem;
    color:     #94a3b8;
    margin:    .6rem 0 0;
}

/* ── Responsive ── */
@media (max-width: 768px) {
    .tbl-filter-bar  { flex-direction: column; align-items: flex-start; }
    .att-mass-btns   { width: 100%; }
    .att-counter     { margin-left: 0; }
    .att-btn-sm span { display: none; }
}
</style>


{{-- FIX 1+2: @push, sin CDN sweetalert2 --}}
<script>
(function () {
    document.addEventListener('DOMContentLoaded', function () {

        const selects     = document.querySelectorAll('.att-select');
        const counter     = document.getElementById('attCounter');
        const saveTxt     = document.getElementById('attSaveTxt');
        const saveBtn     = document.getElementById('attSaveBtn');
        const total       = {{ $inscritos->where('cursos_id', $cursos->id)->count() }};

        /* ── Mapa de estados ── */
        const STATES = {
            'Presente': { cls: 'att-present', selCls: 'att-present', icon: 'bi-check-circle-fill', text: 'Presente' },
            'Retraso' : { cls: 'att-late',    selCls: 'att-late',    icon: 'bi-clock-fill',        text: 'Retraso'  },
            'Licencia': { cls: 'att-excuse',  selCls: 'att-excuse',  icon: 'bi-file-medical-fill', text: 'Licencia' },
            'Falta'   : { cls: 'att-absent',  selCls: 'att-absent',  icon: 'bi-x-circle-fill',     text: 'Falta'    },
        };
        const PENDING = { icon: 'bi-clock', text: 'Pendiente' };

        /* ── Actualizar badge de estado ── */
        function updateBadge(select) {
            const idx    = select.dataset.rowIndex;
            const badge  = document.getElementById(`att-status-${idx}`);
            if (!badge) return;

            /* Limpiar clases de color anteriores */
            badge.className = 'status-badge att-status';
            ['att-present','att-late','att-excuse','att-absent'].forEach(c => badge.classList.remove(c));

            /* Limpiar clases de select */
            ['att-present','att-late','att-excuse','att-absent'].forEach(c => select.classList.remove(c));

            const s = STATES[select.value];
            if (s) {
                badge.classList.add(s.cls);
                badge.innerHTML = `<i class="bi ${s.icon}"></i> ${s.text}`;
                select.classList.add(s.selCls);
            } else {
                badge.classList.add('status-pending');
                badge.innerHTML = `<i class="bi ${PENDING.icon}"></i> ${PENDING.text}`;
            }
        }

        /* ── Actualizar contador y botón ── */
        function updateCounter() {
            const n = Array.from(selects).filter(s => s.value !== '').length;

            if (counter) {
                counter.textContent = `${n} seleccionado${n !== 1 ? 's' : ''}`;
                counter.classList.toggle('att-counter--active', n > 0);
            }
            if (saveTxt) saveTxt.textContent = `Guardar Asistencias (${n}/${total})`;
            if (saveBtn) saveBtn.disabled = n === 0;
        }

        /* ── Listeners por select ── */
        selects.forEach(s => {
            s.addEventListener('change', () => {
                updateBadge(s);
                updateCounter();
            });
        });

        /* ── Acciones masivas ── */
        function setAll(val) {
            selects.forEach(s => { s.value = val; updateBadge(s); });
            updateCounter();
        }

        document.getElementById('attMarkPresent')?.addEventListener('click', () => setAll('Presente'));
        document.getElementById('attMarkAbsent') ?.addEventListener('click', () => setAll('Falta'));
        document.getElementById('attClearAll')   ?.addEventListener('click', () => setAll(''));

        /* ── Búsqueda por nombre ── */
        document.getElementById('attSearch')?.addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();
            document.querySelectorAll('.att-row').forEach(row => {
                const name = row.querySelector('.prt-student-name')?.textContent.toLowerCase() || '';
                row.style.display = name.includes(q) ? '' : 'none';
            });
        });

        /* ── Submit con confirmación ── */
        document.getElementById('attForm')?.addEventListener('submit', function (e) {
            e.preventDefault();
            const n = Array.from(selects).filter(s => s.value !== '').length;
            const f = this;

            if (n === 0) {
                Swal.fire({
                    icon             : 'warning',
                    title            : 'Sin selección',
                    text             : 'Seleccione al menos una asistencia antes de guardar.',
                    confirmButtonColor: '#145da0',
                });
                return;
            }

            Swal.fire({
                title            : '¿Registrar asistencias?',
                text             : `Se guardarán ${n} registro${n !== 1 ? 's' : ''} para hoy.`,
                icon             : 'question',
                showCancelButton : true,
                confirmButtonColor: '#145da0',
                cancelButtonColor : '#94a3b8',
                confirmButtonText : 'Sí, guardar',
                cancelButtonText  : 'Cancelar',
                reverseButtons    : true,
            }).then(r => { if (r.isConfirmed) f.submit(); });
        });

        /* ── Init ── */
        updateCounter();

    });
})();
</script>
