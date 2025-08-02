@php
    $achievements = \App\Models\Achievement::with('users')->get();
@endphp

<div class="row mb-3">
    <div class="col-md-6">
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control search-input" placeholder="Buscar logros...">
        </div>
    </div>
    <div class="col-md-6 text-end">
        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newAchievementModal">
            <i class="bi bi-plus-lg"></i> Nuevo Logro
        </button>
    </div>
</div>

<div class="row g-4">
    @forelse ($achievements as $achievement)
        <div class="col-md-4 achievement-card">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="achievement-icon me-3">
                            <i class="bi {{ $achievement->icon }} display-5 text-primary"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-1">{{ $achievement->name }}</h5>
                            <p class="card-subtitle text-muted">
                                {{ $achievement->users->count() }} usuarios han obtenido este logro
                            </p>
                        </div>
                    </div>

                    <p class="card-text">{{ $achievement->description }}</p>

                    <div class="progress mb-3">
                        <div class="progress-bar bg-success"
                             role="progressbar"
                             style="width: {{ ($achievement->users->count() / \App\Models\User::count()) * 100 }}%">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-primary">{{ $achievement->points }} puntos</span>
                        <div class="btn-group">
                            <button class="btn btn-outline-primary btn-sm"
                                    data-action="view-achievement"
                                    data-id="{{ $achievement->id }}"
                                    data-bs-toggle="tooltip"
                                    title="Ver detalles">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-outline-secondary btn-sm"
                                    data-action="edit-achievement"
                                    data-id="{{ $achievement->id }}"
                                    data-bs-toggle="tooltip"
                                    title="Editar">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm"
                                    data-action="delete-achievement"
                                    data-id="{{ $achievement->id }}"
                                    data-bs-toggle="tooltip"
                                    title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="empty-state text-center py-5">
                <i class="bi bi-trophy display-4 text-muted"></i>
                <p class="mt-3 mb-0">No hay logros disponibles</p>
                <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#newAchievementModal">
                    Crear primer logro
                </button>
            </div>
        </div>
    @endforelse
</div>

<!-- Modal para nuevo logro -->
<div class="modal fade" id="newAchievementModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Logro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="newAchievementForm">
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Puntos</label>
                        <input type="number" class="form-control" name="points" required min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ícono</label>
                        <select class="form-select" name="icon" required>
                            <option value="bi-star-fill">Estrella</option>
                            <option value="bi-award">Premio</option>
                            <option value="bi-trophy-fill">Trofeo</option>
                            <option value="bi-lightning-fill">Rayo</option>
                            <option value="bi-gem">Gema</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" data-action="save-achievement">Guardar</button>
            </div>
        </div>
    </div>
</div>
