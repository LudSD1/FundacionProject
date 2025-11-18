@extends('layout')

@section('titulo', 'Gestión de Backups')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-header-modern d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-database me-2"></i>
                        Gestión de Backups de Base de Datos
                    </h4>
                    <div>
                        <a href="{{ route('Inicio') }}" class="btn-modern btn-primary-custom me-2">
                            <i class="fas fa-home me-1"></i><span class="ms-1">Inicio</span>
                        </a>
                        <button onclick="location.reload()" class="btn-modern btn-accent-custom">
                            <i class="fas fa-sync-alt me-1"></i><span class="ms-1">Actualizar</span>
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Sección de crear backup -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Información:</strong> Los backups se almacenan en <code>storage/backups/</code> y contienen toda la estructura y datos de la base de datos.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-modern">
                                <div class="card-body text-center">
                                    <h6 class="card-title">
                                        <i class="fas fa-plus-circle text-success me-1"></i>
                                        Crear Nuevo Backup
                                    </h6>
                                    <form action="{{ route('admin.backup.create') }}" method="POST" class="d-inline">
                                        @csrf
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="compress" id="compress">
                                            <label class="form-check-label" for="compress">
                                                <small>Comprimir (recomendado)</small>
                                            </label>
                                        </div>
                                        <button type="submit" class="btn-modern btn-success-custom">
                                            <i class="fas fa-download me-1"></i>
                                            Crear Backup
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de backups -->
                    @if(empty($backups))
                        <div class="alert alert-warning text-center">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            No hay backups disponibles. Crea tu primer backup usando el botón de arriba.
                        </div>
                    @else
                        <div class="table-container-modern">
                            <table class="table-modern table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th><i class="fas fa-file me-1"></i>Archivo</th>
                                        <th><i class="fas fa-calendar me-1"></i>Fecha de Creación</th>
                                        <th><i class="fas fa-weight me-1"></i>Tamaño</th>
                                        <th><i class="fas fa-compress me-1"></i>Tipo</th>
                                        <th><i class="fas fa-cogs me-1"></i>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($backups as $backup)
                                        <tr>
                                            <td>
                                                <code class="text-dark">{{ $backup['name'] }}</code>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ $backup['date'] }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $backup['size_mb'] }} MB
                                                </span>
                                            </td>
                                            <td>
                                                @if($backup['is_compressed'])
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-compress me-1"></i>
                                                        Comprimido
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-file me-1"></i>
                                                        Sin comprimir
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="action-buttons-cell" role="group">
                                                    <a href="{{ route('admin.backup.download', $backup['name']) }}" class="btn-action-modern" title="Descargar backup">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    <button type="button" class="btn-action-modern btn-action-delete" title="Eliminar backup" onclick="confirmDelete('{{ $backup['name'] }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Estadísticas -->
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="stats-card text-center">
                                    <div class="stats-number">{{ count($backups) }}</div>
                                    <div class="stats-label">Total de Backups</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stats-card text-center">
                                    <div class="stats-number">{{ array_sum(array_column($backups, 'size_mb')) }} MB</div>
                                    <div class="stats-label">Espacio Total Usado</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stats-card text-center">
                                    <div class="stats-number">{{ count(array_filter($backups, fn($b) => $b['is_compressed'])) }}</div>
                                    <div class="stats-label">Backups Comprimidos</div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar el backup <strong id="backupName"></strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Advertencia:</strong> Esta acción no se puede deshacer.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modern btn-accent-custom" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i><span class="ms-1">Cancelar</span>
                </button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-modern btn-orange-custom">
                        <i class="fas fa-trash me-1"></i><span class="ms-1">Eliminar</span>
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

// Auto-refresh cada 30 segundos si hay backups en proceso
@if(!empty($backups))
    setTimeout(() => {
        location.reload();
    }, 30000);
@endif
</script>

<style>
.table th {
    border-top: none;
    font-weight: 600;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.card {
    transition: transform 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
}

code {
    background-color: #f8f9fa;
    padding: 2px 4px;
    border-radius: 3px;
    font-size: 0.9em;
}
</style>
@endsection
