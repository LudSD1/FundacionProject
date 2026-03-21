@extends('layout')

@section('titulo', 'Foros Eliminados')

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
                    <i class="bi bi-chat-left-dots-fill me-2"></i>Foros Eliminados
                </h1>
                <p class="tbl-card-hero-subtitle text-white">
                    Curso: <span class="fw-bold">{{ $cursos->nombreCurso }}</span>
                </p>
            </div>

            <div class="tbl-card-hero-actions">
                <div class="d-flex gap-2">
                    <div class="ec-role-badge text-white">
                        <i class="bi bi-trash-fill me-1"></i> {{ $foro->where('cursos_id', $cursos->id)->count() }} Foros
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4">
            {{-- Barra de Herramientas --}}
            <div class="d-flex justify-content-between align-items-center mb-4 p-2 bg-light rounded-4 border border-light-subtle">
                <div class="tbl-hero-search" style="max-width: 350px; width: 100%;">
                    <i class="bi bi-search tbl-hero-search-icon text-muted"></i>
                    <input type="text" class="tbl-hero-search-input text-dark border-dark-subtle text-black-50" id="searchForos"  autocomplete="off">
                </div>
                <div class="d-flex gap-2">
                    <span class="status-badge status-secondary">
                        <i class="bi bi-info-circle me-1"></i>
                        Los foros restaurados volverán a ser visibles
                    </span>
                </div>
            </div>

            {{-- Tabla de Foros Eliminados --}}
            <div class="table-container-modern shadow-none border-0 p-0">
                <table class="table-modern" id="tablaForosEliminados">
                    <thead>
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 45%"><div class="th-content"><i class="bi bi-chat-left-text-fill"></i><span>Información del Foro</span></div></th>
                            <th style="width: 20%"><div class="th-content"><i class="bi bi-calendar-x"></i><span>Eliminación</span></div></th>
                            <th style="width: 15%"><div class="th-content justify-content-center"><i class="bi bi-info-circle-fill"></i><span>Estado</span></div></th>
                            <th style="width: 15%" class="text-center"><div class="th-content justify-content-center"><i class="bi bi-gear-fill"></i><span>Acciones</span></div></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($foro as $foroItem)
                            @if ($foroItem->cursos_id == $cursos->id)
                                <tr class="foro-row" data-foro-name="{{ strtolower($foroItem->nombreForo) }}">
                                    <td><span class="text-muted fw-bold">{{ $loop->iteration }}</span></td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <div class="fw-bold text-dark mb-1">{{ $foroItem->nombreForo }}</div>
                                            @if($foroItem->SubtituloForo)
                                                <div class="text-muted small mb-1">{{ $foroItem->SubtituloForo }}</div>
                                            @endif
                                            <div class="text-muted smallest" style="font-size: 0.75rem;">
                                                <i class="bi bi-clock-history me-1"></i>
                                                Creado: {{ $foroItem->created_at->format('d/m/Y') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge status-secondary">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            {{ $foroItem->deleted_at ? $foroItem->deleted_at->format('d/m/Y H:i') : 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="status-badge status-danger">
                                            <i class="bi bi-trash-fill me-1"></i> Eliminado
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons-cell">
                                            <form action="{{ route('restaurar', encrypt($foroItem->id)) }}" method="GET" class="form-restaurar">
                                                <button type="submit" class="btn-action-modern btn-view" title="Restaurar foro">
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="btn-action-modern btn-info" data-bs-toggle="modal" data-bs-target="#modalDetallesForo-{{ $foroItem->id }}" title="Ver detalles">
                                                <i class="bi bi-eye-fill"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state-table py-5">
                                        <i class="bi bi-chat-left-x display-4 text-muted mb-3"></i>
                                        <h5 class="text-muted">No hay foros eliminados</h5>
                                        <p class="text-muted small">Los foros que elimines aparecerán aquí.</p>
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

@push('modals')
    @foreach ($foro as $foroItem)
        <div class="modal fade" id="modalDetallesForo-{{ $foroItem->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
                    <div class="modal-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                        <h5 class="modal-title">
                            <i class="bi bi-info-circle-fill me-2"></i>Detalles del Foro
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-4">
                            <label class="form-label-modern text-primary fw-bold mb-1">Nombre del Foro</label>
                            <div class="p-3 bg-light rounded-4 border border-light-subtle fw-semibold text-dark">
                                {{ $foroItem->nombreForo }}
                            </div>
                        </div>

                        @if($foroItem->SubtituloForo)
                            <div class="mb-4">
                                <label class="form-label-modern text-muted fw-bold mb-1">Subtítulo</label>
                                <div class="p-3 bg-light rounded-4 border border-light-subtle text-muted">
                                    {{ $foroItem->SubtituloForo }}
                                </div>
                            </div>
                        @endif

                        <div class="mb-4">
                            <label class="form-label-modern text-muted fw-bold mb-1">Descripción</label>
                            <div class="p-3 bg-light rounded-4 border border-light-subtle text-muted" style="max-height: 150px; overflow-y: auto;">
                                {{ $foroItem->descripcionForo ?: 'Sin descripción' }}
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-6">
                                <div class="p-2 bg-info-subtle rounded-3 border border-info-subtle text-center">
                                    <small class="text-info-emphasis d-block fw-bold">Creado</small>
                                    <span class="text-dark small">{{ $foroItem->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 bg-danger-subtle rounded-3 border border-danger-subtle text-center">
                                    <small class="text-danger-emphasis d-block fw-bold">Eliminado</small>
                                    <span class="text-dark small">{{ $foroItem->deleted_at ? $foroItem->deleted_at->format('d/m/Y') : 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light p-3" style="border-radius: 0 0 15px 15px;">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cerrar</button>
                        <form action="{{ route('restaurar', encrypt($foroItem->id)) }}" method="GET" class="form-restaurar d-inline">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Restaurar Foro
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endpush

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Búsqueda en tiempo real
        const searchInput = document.getElementById('searchForos');
        const foroRows = document.querySelectorAll('.foro-row');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                foroRows.forEach(row => {
                    const foroName = row.getAttribute('data-foro-name');
                    row.style.display = foroName.includes(searchTerm) ? '' : 'none';
                });
            });
        }

        // Confirmación de restauración con SweetAlert2
        document.querySelectorAll('.form-restaurar').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Restaurar este foro?',
                    text: "El foro volverá a estar disponible para el curso.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Sí, restaurar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) this.submit();
                });
            });
        });
    });
</script>

<style>
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
        color: #1a4789 !important;
    }
</style>
@endsection

