@extends('layout')

@section('titulo')
    Lista de Recursos Eliminados
@endsection

@section('content')
<div class="container my-4">
    <div class="tbl-card shadow-lg">

        <div class="tbl-card-hero">
            <div class="tbl-hero-left">
                <a href="javascript:history.back()"
                   class="tbl-hero-btn tbl-hero-btn-glass prt-back-btn mb-2">
                    <i class="bi bi-arrow-left-circle-fill"></i> Volver al Curso
                </a>
                <div class="tbl-hero-eyebrow">
                    <i class="bi bi-trash-fill"></i> Papelera de Reciclaje
                </div>
                <h2 class="tbl-hero-title">Recursos Eliminados</h2>
                <p class="tbl-hero-sub text-white-50">
                    Gestiona y restaura recursos que han sido eliminados de <strong>{{ $cursos->nombreCurso }}</strong>.
                </p>
            </div>
            <div class="tbl-hero-controls text-end">
                <div class="ec-role-badge mb-2 d-inline-block">
                    <i class="bi bi-shield-fill me-1"></i> {{ auth()->user()->getRoleNames()->first() }}
                </div>
                <div class="text-white small">
                    <i class="bi bi-folder-x me-1"></i> {{ $recursos->where('cursos_id', $cursos->id)->count() }} elementos eliminados
                </div>
            </div>
        </div>

        {{-- Barra de Herramientas (Buscador y Filtros) --}}
        <div class="tbl-filter-bar bg-light border-bottom p-3">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <div class="tbl-hero-search shadow-sm border rounded-pill overflow-hidden bg-white w-100">
                        <i class="bi bi-search ms-3 text-muted"></i>
                        <input type="text" class="form-control border-0 bg-transparent py-2" id="searchResources" placeholder="Buscar por nombre del recurso...">
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="btn-group btn-group-sm p-1 bg-white border rounded-pill shadow-sm">
                        <button class="btn filter-btn active rounded-pill px-3" data-filter="all">Todos</button>
                        <button class="btn filter-btn rounded-pill px-3" data-filter="recientes">Recientes</button>
                        <button class="btn filter-btn rounded-pill px-3" data-filter="antiguos">Antiguos</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-0">
            <div class="table-responsive">
                <table class="table table-modern align-middle mb-0" id="tablaRecursosEliminados">
                    <thead>
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Información del Recurso</th>
                            <th class="text-center">Tipo</th>
                            <th class="text-center">Estado</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recursos as $recurso)
                            @if ($recurso->cursos_id == $cursos->id)
                                <tr class="resource-row" data-resource-name="{{ strtolower($recurso->nombreRecurso) }}" data-timestamp="{{ $recurso->deleted_at ? $recurso->deleted_at->timestamp : 0 }}">
                                    <td class="ps-4 text-muted fw-bold">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @php
                                                $iconos = [
                                                    'word' => 'bi-file-earmark-word-fill text-primary',
                                                    'excel' => 'bi-file-earmark-excel-fill text-success',
                                                    'powerpoint' => 'bi-file-earmark-ppt-fill text-danger',
                                                    'pdf' => 'bi-file-earmark-pdf-fill text-danger',
                                                    'docs' => 'bi-file-earmark-text-fill text-primary',
                                                    'imagen' => 'bi-file-earmark-image-fill text-info',
                                                    'video' => 'bi-file-earmark-play-fill text-danger',
                                                    'audio' => 'bi-file-earmark-music-fill text-primary',
                                                    'youtube' => 'bi-youtube text-danger',
                                                    'forms' => 'bi-card-checklist text-primary',
                                                    'drive' => 'bi-google text-primary',
                                                    'kahoot' => 'bi-gamepad text-primary',
                                                    'canva' => 'bi-palette-fill text-info',
                                                    'enlace' => 'bi-link-45deg text-primary',
                                                    'archivos-adjuntos' => 'bi-paperclip text-secondary',
                                                ];
                                                $icono = $iconos[$recurso->tipoRecurso] ?? 'bi-file-earmark-fill text-secondary';
                                            @endphp
                                            <div class="bg-light rounded-3 p-2 me-3">
                                                <i class="bi {{ $icono }} fs-4"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold text-primary">{{ $recurso->nombreRecurso }}</h6>
                                                <small class="text-muted d-block text-truncate" style="max-width: 300px;">
                                                    {{ $recurso->descripcionRecursos ?: 'Sin descripción' }}
                                                </small>
                                                <small class="text-muted" style="font-size: 0.75rem;">
                                                    <i class="bi bi-calendar-x me-1"></i>
                                                    Eliminado: {{ $recurso->deleted_at ? $recurso->deleted_at->format('d/m/Y H:i') : 'N/A' }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-primary border rounded-pill px-3 py-2 small fw-bold">
                                            {{ ucfirst($recurso->tipoRecurso) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-2 small fw-bold">
                                            <i class="bi bi-trash3-fill me-1"></i> Eliminado
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button class="tbl-hero-btn tbl-hero-btn-primary btn-sm restore-btn"
                                                    data-url="{{ route('RestaurarRecurso', encrypt($recurso->id)) }}"
                                                    title="Restaurar recurso">
                                                <i class="bi bi-arrow-counterclockwise me-1"></i> Restaurar
                                            </button>
                                            <button class="btn btn-light btn-sm border rounded-circle"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalDetalles-{{ $recurso->id }}"
                                                    title="Ver detalles">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Modal de Detalles Modernizado --}}
                                <div class="modal fade" id="modalDetalles-{{ $recurso->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-lg rounded-4">
                                            <div class="modal-header bg-light border-bottom-0 p-4">
                                                <h5 class="modal-title fw-bold text-primary">
                                                    <i class="bi bi-info-circle-fill me-2"></i>Detalles del Recurso
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <div class="text-center mb-4">
                                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                                        <i class="bi {{ $icono }} fs-1"></i>
                                                    </div>
                                                    <h4 class="fw-bold mb-1">{{ $recurso->nombreRecurso }}</h4>
                                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3">{{ ucfirst($recurso->tipoRecurso) }}</span>
                                                </div>

                                                <div class="bg-light rounded-4 p-3 mb-4">
                                                    <h6 class="small fw-bold text-muted text-uppercase mb-2">Descripción</h6>
                                                    <p class="mb-0 small">{{ $recurso->descripcionRecursos ?: 'No hay descripción disponible para este recurso.' }}</p>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-6">
                                                        <div class="border rounded-4 p-3 text-center">
                                                            <i class="bi bi-calendar-plus text-success mb-1 d-block fs-5"></i>
                                                            <small class="text-muted d-block">Creado</small>
                                                            <span class="fw-bold small">{{ $recurso->created_at->format('d/m/Y') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="border rounded-4 p-3 text-center">
                                                            <i class="bi bi-calendar-x text-danger mb-1 d-block fs-5"></i>
                                                            <small class="text-muted d-block">Eliminado</small>
                                                            <span class="fw-bold small">{{ $recurso->deleted_at ? $recurso->deleted_at->format('d/m/Y') : 'N/A' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top-0 p-4 pt-0">
                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cerrar</button>
                                                <button class="tbl-hero-btn tbl-hero-btn-primary px-4 restore-btn"
                                                        data-url="{{ route('RestaurarRecurso', encrypt($recurso->id)) }}">
                                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Restaurar Ahora
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="text-center py-5">
                                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                            <i class="bi bi-trash3 text-muted fs-1"></i>
                                        </div>
                                        <h5 class="text-muted fw-bold">No hay recursos eliminados</h5>
                                        <p class="text-muted small">Los elementos que elimines aparecerán aquí para ser restaurados.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .ec-role-badge {
        background: rgba(255,165,0,0.15); color: #ffa500;
        padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.7rem; font-weight: 800;
        border: 1px solid rgba(255,165,0,0.3);
    }

    .filter-btn.active {
        background: #145da0 !important;
        color: #fff !important;
        border-color: #145da0 !important;
    }

    .table-modern thead th {
        background-color: #f8fafc !important;
        color: #64748b !important;
        font-size: 0.75rem !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        padding: 1rem 0.75rem !important;
        border-bottom: 2px solid #e2eaf4 !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchResources');
    const rows = document.querySelectorAll('.resource-row');
    const filterBtns = document.querySelectorAll('.filter-btn');

    // Búsqueda en tiempo real
    searchInput?.addEventListener('input', function() {
        const term = this.value.toLowerCase().trim();
        rows.forEach(row => {
            const name = row.getAttribute('data-resource-name');
            row.style.display = name.includes(term) ? '' : 'none';
        });
    });

    // Filtros (Todos, Recientes, Antiguos)
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const rowsArray = Array.from(rows);
            const tbody = document.querySelector('#tablaRecursosEliminados tbody');

            if (filter === 'recientes') {
                rowsArray.sort((a, b) => b.getAttribute('data-timestamp') - a.getAttribute('data-timestamp'));
            } else if (filter === 'antiguos') {
                rowsArray.sort((a, b) => a.getAttribute('data-timestamp') - b.getAttribute('data-timestamp'));
            }

            rowsArray.forEach(row => tbody.appendChild(row));
        });
    });

    // Confirmación de Restauración con SweetAlert2
    document.querySelectorAll('.restore-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('data-url');

            // Intentar cerrar el modal si el botón está dentro de uno
            const modalEl = this.closest('.modal');
            if (modalEl) {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            }

            Swal.fire({
                title: '¿Restaurar Recurso?',
                text: "El recurso volverá a estar visible para los estudiantes.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#145da0',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Sí, restaurar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });

    // Alertas del servidor
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#145da0',
            timer: 3000
        });
    @endif
});
</script>
@endsection
