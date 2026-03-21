@extends('layout')

@section('titulo')
    Lista de Participantes: {{ $cursos->nombreCurso }}
@endsection

@section('content')
<div class="container-fluid py-4">
    {{-- Botón Volver --}}
    <div class="back-button-wrapper mb-4">
        <a href="{{ route('Curso', $cursos->codigoCurso) }}" class="btn-back-modern">
            <i class="bi bi-arrow-left-circle-fill"></i>
            <span>Volver al Curso</span>
        </a>
    </div>

    <div class="tbl-card">
        {{-- Hero Section --}}
        <div class="tbl-card-hero">
            <div class="tbl-card-hero-content">
                <h1 class="tbl-card-hero-title text-white">
                    <i class="bi bi-people-fill me-2"></i>Lista de Participantes
                </h1>
                <p class="tbl-card-hero-subtitle text-white">
                    Curso: <span class="fw-bold">{{ $cursos->nombreCurso }}</span>
                </p>
            </div>

            <div class="tbl-card-hero-actions">
                <div class="d-flex gap-2 flex-wrap justify-content-end">
                    @if (auth()->user()->id == $cursos->docente_id || auth()->user()->hasRole('Administrador'))
                        <a class="tbl-hero-btn tbl-hero-btn-glass" href="{{ route('listaretirados', encrypt($cursos->id)) }}">
                            <i class="bi bi-person-x"></i>
                            <span>Retirados</span>
                        </a>
                        @if ($cursos->tipo == 'congreso')
                            <a class="tbl-hero-btn tbl-hero-btn-primary" href="{{ route('certificadosCongreso.generar', $cursos->id) }}">
                                <i class="bi bi-award"></i>
                                <span>Generar Certificados</span>
                            </a>
                        @endif
                        <a class="tbl-hero-btn tbl-hero-btn-primary" href="{{ route('lista', encrypt($cursos->id)) }}">
                            <i class="bi bi-download"></i>
                            <span>Descargar Lista</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="p-4">
            {{-- Barra de Herramientas y Filtros --}}
            <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded-4 border border-light-subtle flex-wrap gap-3">
                <div class="tbl-hero-search" style="max-width: 400px; width: 100%;">
                    <i class="bi bi-search tbl-hero-search-icon text-muted"></i>
                    <input type="text" class="tbl-hero-search-input text-dark border-light-subtle" id="searchInput" placeholder="Buscar participante por nombre, email..." autocomplete="off">
                </div>

                <div class="d-flex gap-3 align-items-center flex-wrap">
                    @role('Administrador')
                        <div class="tbl-hero-select-wrap">
                            <i class="bi bi-filter tbl-hero-select-icon text-muted"></i>
                            <select class="tbl-hero-select ps-5 text-dark border-light-subtle" id="statusFilter" style="width: 220px; background-color: white;">
                                <option value="">Todos los estados</option>
                                @if ($cursos->tipo == 'curso')
                                    <option value="pago-completado">Pago Completado</option>
                                    <option value="pago-revision">Pago en Revisión</option>
                                @elseif($cursos->tipo == 'congreso')
                                    <option value="certificado">Certificado</option>
                                    <option value="sin-certificado">Sin certificado</option>
                                @endif
                            </select>
                        </div>
                    @endrole

                    <div class="d-flex gap-2">
                        <span class="status-badge status-primary">
                            <i class="bi bi-people-fill me-1"></i>
                            <span id="visibleCount">{{ $inscritos->where('cursos_id', $cursos->id)->count() }}</span> / {{ $inscritos->where('cursos_id', $cursos->id)->count() }}
                        </span>
                        @role('Administrador')
                            @if ($cursos->tipo == 'curso')
                                <span class="status-badge status-warning">
                                    <i class="bi bi-clock-history me-1"></i>
                                    Pendientes: {{ $inscritos->where('cursos_id', $cursos->id)->where('pago_completado', false)->count() }}
                                </span>
                            @elseif ($cursos->tipo == 'congreso')
                                <span class="status-badge status-warning">
                                    <i class="bi bi-award me-1"></i>
                                    Pendientes: {{ $inscritos->where('cursos_id', $cursos->id)->where('certificado', false)->count() }}
                                </span>
                            @endif
                        @endrole
                    </div>
                </div>
            </div>

            {{-- Acciones Masivas (Oculto inicialmente) --}}
            <div id="massActionsCard" class="alert alert-primary border-0 rounded-4 shadow-sm mb-4 animate__animated animate__fadeIn" style="display: none;">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-all display-6 me-3"></i>
                        <div>
                            <p class="mb-0 fw-bold"><span id="selectedCount">0</span> Participantes seleccionados</p>
                            <p class="mb-0 small text-primary-emphasis">Elige una acción para aplicar a todos los seleccionados.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-danger rounded-pill px-4" id="retirarSeleccionados">
                            <i class="bi bi-person-x me-1"></i> Retirar
                        </button>
                        @if ($cursos->tipo == 'congreso')
                            <button class="btn btn-success rounded-pill px-4" id="generarCertificados">
                                <i class="bi bi-award me-1"></i> Certificados
                            </button>
                        @endif
                        <button class="btn btn-outline-primary rounded-pill px-4" id="deselectAll">
                            <i class="bi bi-x-circle me-1"></i> Cancelar
                        </button>
                    </div>
                </div>
            </div>

            {{-- Tabla de Participantes --}}
            <div class="table-container-modern shadow-none border-0 p-0">
                <table class="table-modern" id="participantesTable">
                    <thead>
                        <tr>
                            @if (auth()->user()->hasRole('Docente') || auth()->user()->hasRole('Administrador'))
                                <th style="width: 5%" class="text-center">
                                    <div class="form-check d-flex justify-content-center">
                                        <input type="checkbox" id="masterCheckbox" class="form-check-input custom-checkbox">
                                    </div>
                                </th>
                            @endif
                            <th style="width: 5%">#</th>
                            <th style="width: 40%"><div class="th-content"><i class="bi bi-person-fill"></i><span>Participante</span></div></th>
                            <th style="width: 15%"><div class="th-content"><i class="bi bi-telephone-fill"></i><span>Contacto</span></div></th>
                            @role('Administrador')
                                <th style="width: 15%"><div class="th-content justify-content-center"><i class="bi bi-info-circle-fill"></i><span>Estado</span></div></th>
                            @endrole
                            <th style="width: 20%" class="text-center"><div class="th-content justify-content-center"><i class="bi bi-gear-fill"></i><span>Acciones</span></div></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($inscritos as $inscrito)
                            @if ($inscrito->cursos_id == $cursos->id)
                                <tr class="participante-row" data-participante-id="{{ $inscrito->id }}"
                                    data-pago-status="{{ $inscrito->pago_completado ? 'completado' : 'pendiente' }}"
                                    data-certificado-status="{{ $inscrito->certificado ? 'certificado' : 'sin-certificado' }}">
                                    @if (auth()->user()->hasRole('Docente') || auth()->user()->hasRole('Administrador'))
                                        <td class="text-center">
                                            <div class="form-check d-flex justify-content-center">
                                                <input type="checkbox" class="form-check-input student-checkbox custom-checkbox" value="{{ $inscrito->id }}">
                                            </div>
                                        </td>
                                    @endif
                                    <td><span class="text-muted fw-bold">{{ $loop->iteration }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="tbl-avatar me-3">
                                                {{ substr($inscrito->estudiantes->name ?? 'E', 0, 1) }}
                                            </div>
                                            <div class="d-flex flex-column">
                                                <div class="fw-bold text-dark mb-0">
                                                    {{ $inscrito->estudiantes->name ?? 'Estudiante Eliminado' }}
                                                    {{ $inscrito->estudiantes->lastname1 ?? '' }}
                                                    {{ $inscrito->estudiantes->lastname2 ?? '' }}
                                                </div>
                                                @if (isset($inscrito->estudiantes->email))
                                                    <div class="text-muted smallest" style="font-size: 0.75rem;">
                                                        <i class="bi bi-envelope me-1"></i>{{ $inscrito->estudiantes->email }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($inscrito->estudiantes->Celular ?? false)
                                            <div class="text-dark small">
                                                <i class="bi bi-telephone-fill me-1 text-primary"></i>
                                                +{{ $inscrito->estudiantes->Celular }}
                                            </div>
                                        @else
                                            <span class="text-muted smallest italic">Sin celular</span>
                                        @endif
                                    </td>
                                    @role('Administrador')
                                        <td class="text-center">
                                            @if ($cursos->tipo == 'curso')
                                                @if ($inscrito->pago_completado)
                                                    <span class="status-badge status-success">
                                                        <i class="bi bi-check-circle-fill"></i> Pagado
                                                    </span>
                                                @else
                                                    <span class="status-badge status-warning">
                                                        <i class="bi bi-clock-history"></i> En Revisión
                                                    </span>
                                                @endif
                                            @elseif ($cursos->tipo == 'congreso')
                                                @if ($inscrito->certificado)
                                                    <span class="status-badge status-success">
                                                        <i class="bi bi-award-fill"></i> Certificado
                                                    </span>
                                                @else
                                                    <span class="status-badge status-warning">
                                                        <i class="bi bi-award"></i> Pendiente
                                                    </span>
                                                @endif
                                            @endif
                                        </td>
                                    @endrole
                                    <td>
                                        <div class="action-buttons-cell">
                                            <a class="btn-action-modern btn-info" href="{{ route('perfil', [encrypt($inscrito->estudiantes->id)]) }}" title="Ver Perfil">
                                                <i class="bi bi-person-badge-fill"></i>
                                            </a>

                                            @if (auth()->user()->hasRole('Docente') || auth()->user()->hasRole('Administrador'))
                                                <form action="{{ route('quitarInscripcion', $inscrito->id) }}" method="POST" class="form-retirar d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn-action-modern btn-delete" title="Retirar Estudiante">
                                                        <i class="bi bi-person-x-fill"></i>
                                                    </button>
                                                </form>

                                                @if ($cursos->tipo == 'congreso')
                                                    <a class="btn-action-modern btn-view"
                                                        href="{{ !isset($inscrito->certificado) ? route('certificadosCongreso.generar.admin', encrypt($inscrito->id)) : route('certificados.reenviar.email', encrypt($inscrito->id)) }}"
                                                        title="{{ !isset($inscrito->certificado) ? 'Generar Certificado' : 'Reenviar Certificado' }}">
                                                        <i class="bi bi-award-fill"></i>
                                                    </a>
                                                @endif

                                                @if ($cursos->tipo == 'curso')
                                                    <a class="btn-action-modern btn-info" href="{{ route('boletin', [encrypt($inscrito->id)]) }}" title="Ver Boletín">
                                                        <i class="bi bi-journal-text"></i>
                                                    </a>
                                                    {{-- <a class="btn-action-modern btn-view" href="{{ route('verCalificacionFinal', [encrypt($inscrito->id)]) }}" title="Calificaciones Finales">
                                                        <i class="bi bi-journal-check"></i>
                                                    </a> --}}
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->hasRole('Administrador') ? '6' : '5' }}">
                                    <div class="empty-state-table py-5">
                                        <i class="bi bi-people display-4 text-muted mb-3"></i>
                                        <h5 class="text-muted">No hay participantes inscritos</h5>
                                        <p class="text-muted small">Los estudiantes aparecerán aquí conforme se inscriban.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Estadísticas Rápidas --}}
    <div class="row g-4 mt-2">
        <div class="col-md-3">
            <div class="st-card st-card--blue">
                <div class="st-card-body">
                    <div>
                        <div class="st-label">Inscritos Totales</div>
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
            @if ($cursos->tipo == 'curso')
                <div class="col-md-3">
                    <div class="st-card st-card--green">
                        <div class="st-card-body">
                            <div>
                                <div class="st-label">Pagos Verificados</div>
                                <div class="st-num text-success">{{ $inscritos->where('cursos_id', $cursos->id)->where('pago_completado', true)->count() }}</div>
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
                                <div class="st-num text-warning">{{ $inscritos->where('cursos_id', $cursos->id)->where('pago_completado', false)->count() }}</div>
                            </div>
                            <div class="st-icon st-icon--orange">
                                <i class="bi bi-clock-history"></i>
                            </div>
                        </div>
                        <div class="st-bar st-bar--orange"></div>
                    </div>
                </div>
            @elseif ($cursos->tipo == 'congreso')
                <div class="col-md-3">
                    <div class="st-card st-card--green">
                        <div class="st-card-body">
                            <div>
                                <div class="st-label">Certificados OK</div>
                                <div class="st-num text-success">{{ $inscritos->where('cursos_id', $cursos->id)->where('certificado', true)->count() }}</div>
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
                        <div id="statsVisible" class="st-num text-danger">{{ $inscritos->where('cursos_id', $cursos->id)->count() }}</div>
                    </div>
                    <div class="st-icon st-icon--red">
                        <i class="bi bi-eye-fill"></i>
                    </div>
                </div>
                <div class="st-bar st-bar--red"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const rows = document.querySelectorAll('.participante-row');
        const visibleCountSpan = document.getElementById('visibleCount');
        const statsVisibleSpan = document.getElementById('statsVisible');
        const totalRows = rows.length;

        function applyFilters() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const filterValue = statusFilter ? statusFilter.value : '';
            let visibleCount = 0;

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const pagoStatus = row.dataset.pagoStatus;
                const certificadoStatus = row.dataset.certificadoStatus;

                let matchesSearch = text.includes(searchTerm);
                let matchesFilter = true;

                if (filterValue) {
                    if (filterValue === 'pago-completado' || filterValue === 'pago-revision') {
                        matchesFilter = (filterValue === 'pago-completado' && pagoStatus === 'completado') ||
                                        (filterValue === 'pago-revision' && pagoStatus === 'pendiente');
                    } else if (filterValue === 'certificado' || filterValue === 'sin-certificado') {
                        matchesFilter = (certificadoStatus === filterValue);
                    }
                }

                const isVisible = matchesSearch && matchesFilter;
                row.style.display = isVisible ? '' : 'none';
                if (isVisible) visibleCount++;
            });

            visibleCountSpan.textContent = visibleCount;
            statsVisibleSpan.textContent = visibleCount;
        }

        if (searchInput) searchInput.addEventListener('input', applyFilters);
        if (statusFilter) statusFilter.addEventListener('change', applyFilters);

        // Manejo de checkboxes y acciones masivas
        const masterCheckbox = document.getElementById('masterCheckbox');
        const studentCheckboxes = document.querySelectorAll('.student-checkbox');
        const massActionsCard = document.getElementById('massActionsCard');
        const selectedCountSpan = document.getElementById('selectedCount');

        function updateMassActions() {
            const selectedCount = document.querySelectorAll('.student-checkbox:checked').length;
            selectedCountSpan.textContent = selectedCount;
            massActionsCard.style.display = selectedCount > 0 ? 'block' : 'none';
        }

        if (masterCheckbox) {
            masterCheckbox.addEventListener('change', function() {
                studentCheckboxes.forEach(cb => {
                    if (cb.closest('tr').style.display !== 'none') {
                        cb.checked = this.checked;
                    }
                });
                updateMassActions();
            });
        }

        studentCheckboxes.forEach(cb => {
            cb.addEventListener('change', updateMassActions);
        });

        document.getElementById('deselectAll')?.addEventListener('click', () => {
            studentCheckboxes.forEach(cb => cb.checked = false);
            if (masterCheckbox) masterCheckbox.checked = false;
            updateMassActions();
        });

        // Confirmación de retiro individual
        document.querySelectorAll('.form-retirar').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Retirar estudiante?',
                    text: "Esta acción quitará al estudiante de la lista de inscritos. Se puede revertir desde la lista de retirados.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Sí, retirar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) this.submit();
                });
            });
        });

        // Retiro masivo
        document.getElementById('retirarSeleccionados')?.addEventListener('click', function() {
            const ids = Array.from(document.querySelectorAll('.student-checkbox:checked')).map(cb => cb.value);
            if (ids.length === 0) return;

            Swal.fire({
                title: `¿Retirar ${ids.length} participantes?`,
                text: "Se retirarán todos los estudiantes seleccionados del curso.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sí, retirar todos',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return fetch('{{ route('cursos.retirarMasivo') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            inscripciones: ids,
                            curso_id: {{ $cursos->id }}
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) throw new Error(data.message);
                        return data;
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`Error: ${error.message}`);
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('¡Éxito!', 'Los participantes han sido retirados.', 'success')
                        .then(() => window.location.reload());
                }
            });
        });

        // Generar certificados masivos
        document.getElementById('generarCertificados')?.addEventListener('click', function() {
            const ids = Array.from(document.querySelectorAll('.student-checkbox:checked')).map(cb => cb.value);
            Swal.fire({
                title: '¿Generar certificados?',
                text: `Se procesarán ${ids.length} certificados para los estudiantes seleccionados.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                confirmButtonText: 'Sí, generar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Función en desarrollo', 'La generación masiva estará disponible pronto.', 'info');
                }
            });
        });
    });
</script>

<style>
    .custom-checkbox {
        width: 1.2rem;
        height: 1.2rem;
        border-radius: 6px;
        cursor: pointer;
    }
    .italic { font-style: italic; }
    .status-badge {
        font-size: 0.75rem;
        padding: 0.4rem 0.8rem;
        border-radius: 50px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }
    .smallest { font-size: 0.75rem; }
    .empty-state-table {
        text-align: center;
        background: #f8fafc;
        border-radius: 20px;
        border: 2px dashed #e2e8f0;
    }
    .tbl-hero-search-input:focus {
        border-color: #1a4789 !important;
        box-shadow: 0 0 0 0.2rem rgba(26, 71, 137, 0.1) !important;
        background: white !important;
    }
    .tbl-hero-select:focus {
        border-color: #1a4789 !important;
        outline: none;
    }
</style>
@endsection

