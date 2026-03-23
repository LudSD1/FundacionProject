@extends('layout')

@section('titulo')
    Lista de Participantes Retirados: {{ $cursos->nombreCurso }}
@endsection

@section('content')

<div class="container my-4">
    <div class="tbl-card">

        <div class="tbl-card-hero">
            <div class="tbl-hero-left">
                <a href="{{ route('listacurso', ['id' => encrypt($cursos->id)]) }}"
                   class="tbl-hero-btn tbl-hero-btn-glass prt-back-btn mb-2">
                    <i class="bi bi-arrow-left-circle-fill"></i>
                    <span>Volver</span>
                </a>
                <div class="tbl-hero-eyebrow">
                    <i class="bi bi-person-x-fill"></i> Retirados
                </div>
                <h2 class="tbl-hero-title">Estudiantes Retirados</h2>
                <p class="tbl-hero-sub">
                    Curso: <strong>{{ $cursos->nombreCurso }}</strong>
                </p>
            </div>

            <div class="tbl-hero-controls">
                {{-- Botones de acción --}}
                <div class="d-flex gap-2 mb-2 flex-wrap justify-content-end">
                    <form id="restaurar-todos-form" action="{{ route('cursos.restaurarTodos', ['cursoId' => $cursos->id]) }}"
                        method="POST" style="display: none;">
                        @csrf
                    </form>
                    <button type="button" class="tbl-hero-btn tbl-hero-btn-glass" onclick="confirmarRestaurarTodos()"
                        {{ $inscritos->where('cursos_id', $cursos->id)->count() == 0 ? 'disabled' : '' }}>
                        <i class="bi bi-arrow-counterclockwise"></i>
                        <span>Restaurar Todos</span>
                    </button>
                </div>

                {{-- Buscador --}}
                <div class="d-flex gap-2 flex-wrap justify-content-end align-items-center">
                    <div class="tbl-hero-search">
                        <i class="bi bi-search tbl-hero-search-icon"></i>
                        <input type="text"
                               class="tbl-hero-search-input"
                               id="searchInput"
                               placeholder="Buscar por nombre, email o celular..."
                               autocomplete="off">
                    </div>
                </div>
            </div>
        </div>{{-- /tbl-card-hero --}}

        {{-- Barra de contadores --}}
        <div class="tbl-filter-bar">
            <div class="tbl-filter-bar-left">
                <i class="bi bi-people-fill"></i>
                Total Retirados: <strong id="totalCount">{{ $inscritos->where('cursos_id', $cursos->id)->count() }}</strong>
                <span class="mx-2">·</span>
                Mostrando: <strong id="visibleCount">{{ $inscritos->where('cursos_id', $cursos->id)->count() }}</strong>
            </div>
        </div>

        <div class="p-0">
            <!-- Tabla de Estudiantes Retirados -->
            <div class="table-container-modern">
                <table class="table-modern" id="estudiantesTable">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%">
                                <input type="checkbox" id="masterCheckbox" class="prt-checkbox">
                            </th>
                            <th width="5%">
                                <div class="th-content"><i class="bi bi-hash"></i></div>
                            </th>
                            <th width="35%">
                                <div class="th-content"><i class="bi bi-person-fill"></i><span>Estudiante</span></div>
                            </th>
                            <th width="20%">
                                <div class="th-content"><i class="bi bi-telephone-fill"></i><span>Contacto</span></div>
                            </th>
                            <th width="15%">
                                <div class="th-content"><i class="bi bi-calendar-x-fill"></i><span>Fecha Retiro</span></div>
                            </th>
                            <th class="text-center" width="20%">
                                <div class="th-content justify-content-center"><i class="bi bi-gear-fill"></i><span>Acciones</span></div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($inscritos as $inscrito)
                            @if ($inscrito->cursos_id == $cursos->id)
                                <tr class="estudiante-row prt-row" data-estudiante-id="{{ $inscrito->id }}">
                                    <td class="text-center">
                                        <input type="checkbox" class="prt-checkbox student-checkbox"
                                            value="{{ $inscrito->id }}">
                                    </td>
                                    <td><span class="row-number">{{ $loop->iteration }}</span></td>
                                    <td>
                                        <div class="prt-student">
                                            <div class="tbl-avatar bg-warning text-white">
                                                {{ strtoupper(substr($inscrito->estudiantes->name ?? 'E', 0, 1)) }}
                                            </div>
                                            <div class="prt-student-info">
                                                <div class="prt-student-name">
                                                    {{ $inscrito->estudiantes->name ?? 'Estudiante Eliminado' }}
                                                    {{ $inscrito->estudiantes->lastname1 ?? '' }}
                                                    {{ $inscrito->estudiantes->lastname2 ?? '' }}
                                                </div>
                                                @if (isset($inscrito->estudiantes->email))
                                                    <div class="prt-student-email">
                                                        <i class="bi bi-envelope me-1"></i>{{ $inscrito->estudiantes->email }}
                                                    </div>
                                                @endif
                                                <span class="badge bg-danger-subtle text-danger border-danger-subtle px-2 py-0 small mt-1">Retirado</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($inscrito->estudiantes->Celular ?? false)
                                            <div class="prt-phone">
                                                <i class="bi bi-telephone-fill"></i>
                                                +{{ $inscrito->estudiantes->Celular }}
                                            </div>
                                        @else
                                            <span class="prt-no-data">Sin celular</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="prt-phone text-muted">
                                            <i class="bi bi-calendar-event"></i>
                                            {{ $inscrito->updated_at ? $inscrito->updated_at->format('d/m/Y') : 'N/A' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-buttons-cell justify-content-center">
                                            <button class="btn-action-modern btn-edit"
                                                onclick="confirmarRestauracion('{{ encrypt($inscrito->id ?? '') }}', '{{ $inscrito->estudiantes->name ?? 'Estudiante' }}')"
                                                title="Restaurar inscripción">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                            <a class="btn-action-modern btn-info"
                                                href="{{ route('perfil', [encrypt($inscrito->estudiantes->id)]) }}"
                                                title="Ver Perfil">
                                                <i class="bi bi-person-badge-fill"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr id="noResultsRow">
                                <td colspan="6">
                                    <div class="empty-state-table">
                                        <div class="empty-icon-table text-primary"><i class="bi bi-check-circle"></i></div>
                                        <h5 class="empty-title-table">¡Excelente!</h5>
                                        <p class="empty-text-table">No hay estudiantes retirados en este curso</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Acciones masivas Flotantes -->
        <div id="massActionsCard" class="prt-mass-actions" style="display:none; margin: 1rem; position: fixed; bottom: 1rem; left: 50%; transform: translateX(-50%); z-index: 1000; min-width: 400px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
            <div class="prt-mass-actions-inner">
                <div class="prt-mass-info">
                    <div class="prt-mass-icon"><i class="bi bi-check-all"></i></div>
                    <div>
                        <strong><span id="selectedCount">0</span> seleccionados</strong>
                        <div class="prt-mass-sub">Acción masiva para retirados</div>
                    </div>
                </div>
                <div class="prt-mass-btns">
                    <button class="tbl-hero-btn tbl-hero-btn-primary" id="restaurarSeleccionados">
                        <i class="bi bi-arrow-counterclockwise"></i> Restaurar
                    </button>
                    <button class="tbl-hero-btn tbl-hero-btn-glass prt-btn-cancel" id="deselectAll">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </button>
                </div>
            </div>
        </div>

        <!-- Mensaje cuando no hay resultados de búsqueda -->
        <div class="text-center py-5" id="noSearchResults" style="display: none;">
            <div class="empty-state-table">
                <div class="empty-icon-table"><i class="bi bi-search"></i></div>
                <h5 class="empty-title-table">Sin resultados</h5>
                <p class="empty-text-table mb-4">No se encontraron estudiantes que coincidan con la búsqueda</p>
                <button class="tbl-hero-btn tbl-hero-btn-primary mx-auto" id="clearSearchBtn" style="width: auto;">
                    <i class="bi bi-arrow-clockwise me-1"></i> Limpiar búsqueda
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
/* ════════════════════
   TOOLBAR (REUSADO DE PARTICIPANTES)
════════════════════ */
.tbl-hero-search {
    position: relative;
    width: 100%;
}
.tbl-hero-search-icon {
    position: absolute;
    left: 1.25rem;
    top: 50%;
    transform: translateY(-50%);
    z-index: 5;
    color: #94a3b8;
}
.tbl-hero-search-input {
    padding-left: 3rem !important;
    background: #fff !important;
    border: 1px solid #d1dce8 !important;
    border-radius: 50px !important;
    height: 42px;
    width: 100%;
}
.tbl-hero-search-input:focus {
    border-color: #2a81c2 !important;
    box-shadow: 0 0 0 3px rgba(42,129,194,.13) !important;
    outline: none;
}

/* ════════════════════
   ACCIONES MASIVAS
════════════════════ */
.prt-mass-actions {
    background: rgba(20,93,160,.06);
    border: 1.5px solid rgba(20,93,160,.18);
    border-radius: 12px;
    animation: prtFadeIn .25s ease both;
}
@keyframes prtFadeIn {
    from { opacity: 0; transform: translate(-50%, 20px); }
    to   { opacity: 1; transform: translate(-50%, 0); }
}
.prt-mass-actions-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: .9rem 1.1rem;
    gap: .8rem;
}
.prt-mass-info {
    display: flex;
    align-items: center;
    gap: .75rem;
}
.prt-mass-icon {
    width: 40px; height: 40px;
    border-radius: 50%;
    background: rgba(20,93,160,.12);
    color: #145da0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}
.prt-mass-sub { font-size: .76rem; color: #64748b; }
.prt-mass-btns { display: flex; gap: .5rem; }

/* ════════════════════
   TABLA Y CELDAS
════════════════════ */
.prt-checkbox {
    width: 1.1rem; height: 1.1rem;
    accent-color: #145da0;
    cursor: pointer;
}
.prt-student { display: flex; align-items: center; gap: .7rem; }
.prt-student-info { display: flex; flex-direction: column; gap: .1rem; }
.prt-student-name { font-size: .88rem; font-weight: 700; color: #0f172a; }
.prt-student-email { font-size: .72rem; color: #94a3b8; display: flex; align-items: center; }
.prt-phone { display: flex; align-items: center; gap: .35rem; font-size: .83rem; color: #374151; }
.prt-phone i { color: #145da0; font-size: .78rem; }
.prt-no-data { font-size: .76rem; color: #c5d0dc; font-style: italic; }
.prt-back-btn { padding: .32rem .75rem !important; font-size: .76rem !important; }

@media (max-width: 768px) {
    #massActionsCard { min-width: 90% !important; }
    .prt-mass-actions-inner { flex-direction: column; align-items: flex-start; }
    .prt-mass-btns { width: 100%; justify-content: flex-end; }
}
</style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const clearSearch = document.getElementById('clearSearch');
            const clearSearchBtn = document.getElementById('clearSearchBtn');
            const rows = document.querySelectorAll('.estudiante-row');
            const visibleCountSpan = document.getElementById('visibleCount');
            const totalCountSpan = document.getElementById('totalCount');
            const noSearchResults = document.getElementById('noSearchResults');
            const tableResponsive = document.querySelector('.table-responsive');
            const masterCheckbox = document.getElementById('masterCheckbox');
            const studentCheckboxes = document.querySelectorAll('.student-checkbox');
            const massActionsCard = document.getElementById('massActionsCard');
            const selectedCountSpan = document.getElementById('selectedCount');

            let totalRows = rows.length;

            // Inicializar tooltips de Bootstrap
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Función de búsqueda mejorada
            function applySearch() {
                const searchText = searchInput.value.toLowerCase().trim();
                let visibleCount = 0;

                rows.forEach(row => {
                    const rowText = row.textContent.toLowerCase();
                    const match = searchText === '' || rowText.includes(searchText);
                    row.style.display = match ? '' : 'none';

                    if (match && searchText.length > 0) {
                        row.classList.add('table-active');
                    } else {
                        row.classList.remove('table-active');
                    }

                    if (match) visibleCount++;
                });

                // Actualizar contadores
                if (visibleCountSpan) visibleCountSpan.textContent = visibleCount;

                // Mostrar/ocultar mensaje de sin resultados
                if (visibleCount === 0 && searchText !== '' && totalRows > 0) {
                    if (noSearchResults) noSearchResults.style.display = 'block';
                    if (tableResponsive) tableResponsive.style.display = 'none';
                } else {
                    if (noSearchResults) noSearchResults.style.display = 'none';
                    if (tableResponsive) tableResponsive.style.display = 'block';
                }
            }

            if (searchInput) {
                searchInput.addEventListener('input', applySearch);
            }

            // Limpiar búsqueda
            function resetSearch() {
                if (searchInput) searchInput.value = '';
                rows.forEach(row => {
                    row.style.display = '';
                    row.classList.remove('table-active');
                });
                if (visibleCountSpan) visibleCountSpan.textContent = totalRows;
                if (noSearchResults) noSearchResults.style.display = 'none';
                if (tableResponsive) tableResponsive.style.display = 'block';
            }

            if (clearSearch) clearSearch.addEventListener('click', resetSearch);
            if (clearSearchBtn) clearSearchBtn.addEventListener('click', resetSearch);

            // Manejo de checkboxes
            if (masterCheckbox) {
                masterCheckbox.addEventListener('change', function() {
                    const isChecked = this.checked;
                    rows.forEach(row => {
                        if (row.style.display !== 'none') {
                            const cb = row.querySelector('.student-checkbox');
                            if (cb) cb.checked = isChecked;
                        }
                    });
                    updateMassActions();
                });
            }

            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('student-checkbox')) {
                    updateMassActions();
                }
            });

            function updateMassActions() {
                const selected = document.querySelectorAll('.student-checkbox:checked').length;
                if (selectedCountSpan) selectedCountSpan.textContent = selected;
                if (massActionsCard) massActionsCard.style.display = selected > 0 ? 'block' : 'none';

                if (masterCheckbox) {
                    const visibleCBs = Array.from(document.querySelectorAll('.student-checkbox')).filter(cb => cb.closest('tr').style.display !== 'none');
                    const checkedVisible = visibleCBs.filter(cb => cb.checked);
                    masterCheckbox.checked = visibleCBs.length > 0 && visibleCBs.length === checkedVisible.length;
                }
            }

            const deselectAllBtn = document.getElementById('deselectAll');
            if (deselectAllBtn) {
                deselectAllBtn.addEventListener('click', function() {
                    document.querySelectorAll('.student-checkbox').forEach(cb => cb.checked = false);
                    if (masterCheckbox) masterCheckbox.checked = false;
                    updateMassActions();
                });
            }

            // Restaurar seleccionados
            const restaurarSeleccionadosBtn = document.getElementById('restaurarSeleccionados');
            if (restaurarSeleccionadosBtn) {
                restaurarSeleccionadosBtn.addEventListener('click', function() {
                    const selected = Array.from(document.querySelectorAll('.student-checkbox:checked')).map(cb => cb.value);
                    if (selected.length > 0) {
                        confirmarRestauracionMasiva(selected);
                    }
                });
            }
        });

        // Funciones de confirmación con SweetAlert2
        function confirmarRestauracion(id, nombre) {
            Swal.fire({
                title: '¿Restaurar inscripción?',
                text: `¿Estás seguro de restaurar la inscripción de ${nombre}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, restaurar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('restaurarIncripcion', '') }}/" + id;
                }
            });
        }

        function confirmarRestaurarTodos() {
            const total = {{ $inscritos->where('cursos_id', $cursos->id)->count() }};

            if (total === 0) {
                Swal.fire({
                    title: 'Sin inscripciones',
                    text: 'No hay inscripciones retiradas para restaurar en este curso.',
                    icon: 'info',
                    confirmButtonText: 'Entendido'
                });
                return;
            }

            Swal.fire({
                title: '¿Restaurar todas las inscripciones?',
                text: `Se restaurarán todas las inscripciones retiradas del curso. Esta acción no se puede deshacer.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, restaurar todas',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('restaurar-todos-form').submit();
                }
            });
        }

        function confirmarRestauracionMasiva(ids) {
            Swal.fire({
                title: '¿Restaurar inscripciones seleccionadas?',
                text: `Se restaurarán ${ids.length} inscripciones seleccionadas.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, restaurar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Función pendiente',
                        text: 'Esta funcionalidad requiere implementar la ruta correspondiente.',
                        icon: 'info'
                    });
                }
            });
        }
    </script>
