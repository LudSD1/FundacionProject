@extends('layout')

@section('titulo', 'Gestión de Backups')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-database me-2"></i>
                        Gestión de Backups de Base de Datos
                    </h4>
                    <div>
                        <a href="{{ route('Inicio') }}" class="btn btn-light btn-sm me-2">
                            <i class="fas fa-home me-1"></i>
                            Inicio
                        </a>
                        <button onclick="location.reload()" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-sync-alt me-1"></i>
                            Actualizar
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
                            <div class="card bg-light">
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
                                        <button type="submit" class="btn btn-success btn-sm">
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
                        <div class="table-responsive">
                            <table class="table table-hover">
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
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.backup.download', $backup['name']) }}"
                                                       class="btn btn-primary btn-sm"
                                                       title="Descargar backup">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    <button type="button"
                                                            class="btn btn-danger btn-sm"
                                                            title="Eliminar backup"
                                                            onclick="confirmDelete('{{ $backup['name'] }}')">
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
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h5>{{ count($backups) }}</h5>
                                        <small>Total de Backups</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h5>{{ array_sum(array_column($backups, 'size_mb')) }} MB</h5>
                                        <small>Espacio Total Usado</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h5>{{ count(array_filter($backups, fn($b) => $b['is_compressed'])) }}</h5>
                                        <small>Backups Comprimidos</small>
                                    </div>
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Cancelar
                </button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>
                        Eliminar
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
