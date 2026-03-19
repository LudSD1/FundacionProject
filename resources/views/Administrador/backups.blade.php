@extends('layout')

@section('titulo', 'Gestión de Backups')

@section('content')
<div class="container-fluid py-5">
    {{-- Estructura tbl-card moderna --}}
    <div class="tbl-card">
        {{-- Cabecera con lenguaje visual moderno --}}
        <div class="tbl-card-hero">
            <div class="tbl-hero-left">
                <div class="tbl-hero-eyebrow">
                    <i class="fas fa-shield-alt"></i> Seguridad del Sistema
                </div>
                <h2 class="tbl-hero-title">Gestión de Backups</h2>
                <p class="tbl-hero-sub">Administre las copias de seguridad de la base de datos y archivos</p>
            </div>
            <div class="tbl-hero-controls">
                <a href="{{ route('Inicio') }}" class="tbl-hero-btn tbl-hero-btn-glass">
                    <i class="fas fa-home"></i> Inicio
                </a>
                <button onclick="location.reload()" class="tbl-hero-btn tbl-hero-btn-glass">
                    <i class="fas fa-sync-alt"></i> Actualizar
                </button>
            </div>
        </div>

        <div class="card-body p-4">
            <!-- Sección de creación rápida -->
            <div class="row g-4 mb-5">
                <div class="col-lg-8">
                    <div class="alert alert-info border-0 shadow-sm rounded-4 p-4 d-flex align-items-center mb-0 h-100">
                        <div class="bg-info bg-opacity-10 rounded-circle p-3 me-4">
                            <i class="fas fa-info-circle text-info fs-3"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-info mb-1">Información Importante</h6>
                            <p class="mb-0 small opacity-75">Los backups se almacenan en <code>storage/backups/</code>. Se recomienda descargar copias externas periódicamente para mayor seguridad.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 bg-light h-100">
                        <div class="card-body p-4 text-center d-flex flex-column justify-content-center">
                            <h6 class="fw-bold text-primary mb-3">Nuevo Punto de Restauración</h6>
                            <form action="{{ route('admin.backup.create') }}" method="POST">
                                @csrf
                                <div class="form-check form-switch d-inline-block mb-3">
                                    <input class="form-check-input" type="checkbox" name="compress" id="compress" checked>
                                    <label class="form-check-label small fw-bold text-muted" for="compress">Comprimir archivos</label>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm" style="background: var(--gradient-primary) !important; border: none;">
                                    <i class="fas fa-download me-2"></i> Generar Backup
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de backups -->
            <div class="mb-4">
                <h5 class="fw-bold text-dark mb-3"><i class="fas fa-history me-2 text-primary"></i>Backups Disponibles</h5>
                @if(empty($backups))
                    <div class="text-center py-5 bg-light rounded-4">
                        <i class="fas fa-database fa-3x mb-3 opacity-25 text-primary"></i>
                        <p class="mb-0 fw-bold text-muted">No hay backups disponibles</p>
                        <small class="text-muted">Genera uno nuevo usando el panel superior</small>
                    </div>
                @else
                    <div class="table-container-modern">
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th><div class="th-content">Archivo</div></th>
                                    <th><div class="th-content">Fecha de Creación</div></th>
                                    <th><div class="th-content">Tamaño</div></th>
                                    <th><div class="th-content">Estado</div></th>
                                    <th class="text-center"><div class="th-content text-center w-100">Acciones</div></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($backups as $backup)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded p-2 me-3">
                                                    <i class="fas fa-file-archive text-primary"></i>
                                                </div>
                                                <code class="text-primary fw-bold">{{ $backup['name'] }}</code>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="date-badge date-start">
                                                <i class="fas fa-calendar-alt me-1"></i> {{ $backup['date'] }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-bold">
                                                <i class="fas fa-weight-hanging me-1 text-muted"></i> {{ $backup['size_mb'] }} MB
                                            </span>
                                        </td>
                                        <td>
                                            @if($backup['is_compressed'])
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill">
                                                    <i class="fas fa-compress-arrows-alt me-1"></i> Comprimido
                                                </span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 rounded-pill">
                                                    <i class="fas fa-file me-1"></i> Normal
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('admin.backup.download', $backup['name']) }}"
                                                   class="btn btn-sm btn-outline-primary rounded-pill px-3" title="Descargar">
                                                    <i class="fas fa-download me-1"></i> Descargar
                                                </a>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                        onclick="confirmDelete('{{ $backup['name'] }}')" title="Eliminar">
                                                    <i class="fas fa-trash me-1"></i> Eliminar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Estadísticas Rápidas -->
                    <div class="row g-4 mt-4">
                        <div class="col-md-4">
                            <div class="bg-primary bg-opacity-10 border-0 rounded-4 p-4 text-center h-100">
                                <div class="text-primary mb-2"><i class="fas fa-copy fa-2x"></i></div>
                                <div class="fs-3 fw-bold text-primary">{{ count($backups) }}</div>
                                <div class="text-muted small fw-bold text-uppercase">Total Backups</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-success bg-opacity-10 border-0 rounded-4 p-4 text-center h-100">
                                <div class="text-success mb-2"><i class="fas fa-hdd fa-2x"></i></div>
                                <div class="fs-3 fw-bold text-success">{{ array_sum(array_column($backups, 'size_mb')) }} MB</div>
                                <div class="text-muted small fw-bold text-uppercase">Espacio Usado</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-info bg-opacity-10 border-0 rounded-4 p-4 text-center h-100">
                                <div class="text-info mb-2"><i class="fas fa-file-zipper fa-2x"></i></div>
                                <div class="fs-3 fw-bold text-info">{{ count(array_filter($backups, fn($b) => $b['is_compressed'])) }}</div>
                                <div class="text-muted small fw-bold text-uppercase">Comprimidos</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow overflow-hidden">
            <div class="modal-header border-0 bg-danger text-white py-3">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-exclamation-triangle me-2"></i> Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                    <i class="fas fa-trash fa-2x"></i>
                </div>
                <h5>¿Estás seguro?</h5>
                <p class="text-muted mb-4">Vas a eliminar el archivo:<br><strong class="text-dark" id="backupName"></strong></p>

                <div class="alert alert-warning border-0 rounded-3 small mb-0">
                    <i class="fas fa-exclamation-circle me-2"></i> Esta acción es irreversible.
                </div>
            </div>
            <div class="modal-footer border-0 bg-light p-3">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm">
                        <i class="fas fa-trash me-2"></i> Eliminar Permanentemente
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(filename) {
    document.getElementById('backupName').textContent = filename;
    document.getElementById('deleteForm').action = '{{ route("admin.backup.delete", "") }}/' + filename;

    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

@if(!empty($backups))
    setTimeout(() => {
        location.reload();
    }, 60000); // Aumentado a 60 segundos para evitar recargas constantes
@endif
</script>

@endsection
