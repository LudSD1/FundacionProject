@extends('layout')

@section('titulo')
Elementos eliminados del curso: {{ $curso->nombreCurso }}
@endsection

@section('content')
<div class="container-fluid py-4">
    {{-- Botón Volver --}}
    <div class="back-button-wrapper mb-4">
        <a href="{{ route('Curso', $curso->codigoCurso) }}" class="btn-back-modern">
            <i class="bi bi-arrow-left-circle-fill"></i>
            <span>Volver al curso</span>
        </a>
    </div>

    <div class="tbl-card">
        {{-- Hero Section --}}
        <div class="tbl-card-hero">
            <div class="tbl-card-hero-content">
                <h1 class="tbl-card-hero-title text-white">
                    <i class="bi bi-trash-fill me-2"></i>Elementos eliminados
                </h1>
                <p class="tbl-card-hero-subtitle text-white">
                    Curso: <span class="fw-bold">{{ $curso->nombreCurso }}</span>
                </p>
            </div>

            <div class="tbl-card-hero-actions">
                <div class="d-flex gap-2">
                    <div class="ec-role-badge text-white">
                        <i class="bi bi-people-fill me-1"></i> {{ $cantidadInscritos }} Estudiantes
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4">
            <div class="alert alert-info border-0 rounded-4 shadow-sm mb-4">
                <div class="d-flex align-items-center">
                    <i class="bi bi-info-circle-fill display-6 me-3"></i>
                    <div>
                        <p class="mb-0 fw-bold">Restauración de elementos</p>
                        <p class="mb-0 small">La restauración de elementos permitirá que los estudiantes vuelvan a acceder a estos recursos inmediatamente.</p>
                    </div>
                </div>
            </div>

            {{-- Navegación de las pestañas Modernizada --}}
            <ul class="nav nav-tabs border-0 mb-4 bg-light p-1 rounded-pill w-fit-content" id="elementosTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill px-4 border-0" id="temas-tab" data-bs-toggle="tab" data-bs-target="#temas" type="button" role="tab">
                        <i class="bi bi-journal-bookmark me-2"></i>Temas ({{ $temasEliminados->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-4 border-0" id="subtemas-tab" data-bs-toggle="tab" data-bs-target="#subtemas" type="button" role="tab">
                        <i class="bi bi-journal-text me-2"></i>Subtemas ({{ $subtemasEliminados->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-4 border-0" id="actividades-tab" data-bs-toggle="tab" data-bs-target="#actividades" type="button" role="tab">
                        <i class="bi bi-card-checklist me-2"></i>Actividades ({{ $actividadesEliminadas->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-4 border-0" id="foros-tab" data-bs-toggle="tab" data-bs-target="#foros" type="button" role="tab">
                        <i class="bi bi-chat-dots me-2"></i>Foros ({{ $forosEliminados->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-4 border-0" id="recursos-tab" data-bs-toggle="tab" data-bs-target="#recursos" type="button" role="tab">
                        <i class="bi bi-folder me-2"></i>Recursos ({{ $recursosEliminados->count() }})
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="elementosTabsContent">
                <!-- Temas eliminados -->
                <div class="tab-pane fade show active" id="temas" role="tabpanel" aria-labelledby="temas-tab">
                    @if($temasEliminados->count() > 0)
                        <div class="table-container-modern shadow-none border-0 p-0">
                            <table class="table-modern">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th style="width: 30%"><div class="th-content"><i class="bi bi-bookmark-fill"></i><span>Título</span></div></th>
                                        <th style="width: 40%"><div class="th-content"><i class="bi bi-text-left"></i><span>Descripción</span></div></th>
                                        <th style="width: 15%"><div class="th-content"><i class="bi bi-calendar-event"></i><span>Eliminación</span></div></th>
                                        <th style="width: 10%" class="text-center"><div class="th-content justify-content-center"><i class="bi bi-gear-fill"></i><span>Acciones</span></div></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($temasEliminados as $tema)
                                        <tr>
                                            <td><span class="text-muted fw-bold">{{ $loop->iteration }}</span></td>
                                            <td><div class="fw-bold text-dark">{{ $tema->titulo_tema }}</div></td>
                                            <td><div class="text-muted small text-wrap" style="max-width: 300px;">{{ Str::limit($tema->descripcion, 100) }}</div></td>
                                            <td><span class="status-badge status-secondary"><i class="bi bi-clock"></i> {{ $tema->deleted_at->format('d/m/Y H:i') }}</span></td>
                                            <td>
                                                <div class="action-buttons-cell">
                                                    <form action="{{ route('cursos.restaurar-elemento') }}" method="POST" class="form-restaurar">
                                                        @csrf
                                                        <input type="hidden" name="tipo" value="tema">
                                                        <input type="hidden" name="id" value="{{ $tema->id }}">
                                                        <input type="hidden" name="curso_id" value="{{ $curso->id }}">
                                                        <button type="submit" class="btn-action-modern btn-view" title="Restaurar">
                                                            <i class="bi bi-arrow-counterclockwise"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state-table py-5">
                            <i class="bi bi-journal-x display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">No hay temas eliminados</h5>
                        </div>
                    @endif
                </div>

                <!-- Subtemas eliminados -->
                <div class="tab-pane fade" id="subtemas" role="tabpanel" aria-labelledby="subtemas-tab">
                    @if($subtemasEliminados->count() > 0)
                        <div class="table-container-modern shadow-none border-0 p-0">
                            <table class="table-modern">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th style="width: 25%"><div class="th-content"><i class="bi bi-bookmark"></i><span>Título</span></div></th>
                                        <th style="width: 20%"><div class="th-content"><i class="bi bi-folder-fill"></i><span>Tema</span></div></th>
                                        <th style="width: 30%"><div class="th-content"><i class="bi bi-text-left"></i><span>Descripción</span></div></th>
                                        <th style="width: 10%"><div class="th-content"><i class="bi bi-calendar-event"></i><span>Eliminación</span></div></th>
                                        <th style="width: 10%" class="text-center"><div class="th-content justify-content-center"><i class="bi bi-gear-fill"></i><span>Acciones</span></div></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subtemasEliminados as $subtema)
                                        <tr>
                                            <td><span class="text-muted fw-bold">{{ $loop->iteration }}</span></td>
                                            <td><div class="fw-bold text-dark">{{ $subtema->titulo_subtema }}</div></td>
                                            <td><span class="status-badge status-primary">{{ $subtema->tema->titulo_tema ?? 'Tema eliminado' }}</span></td>
                                            <td><div class="text-muted small text-wrap" style="max-width: 250px;">{{ Str::limit($subtema->descripcion, 80) }}</div></td>
                                            <td><span class="status-badge status-secondary">{{ $subtema->deleted_at->format('d/m/Y H:i') }}</span></td>
                                            <td>
                                                <div class="action-buttons-cell">
                                                    <form action="{{ route('cursos.restaurar-elemento') }}" method="POST" class="form-restaurar">
                                                        @csrf
                                                        <input type="hidden" name="tipo" value="subtema">
                                                        <input type="hidden" name="id" value="{{ $subtema->id }}">
                                                        <input type="hidden" name="curso_id" value="{{ $curso->id }}">
                                                        <button type="submit" class="btn-action-modern btn-view" title="Restaurar">
                                                            <i class="bi bi-arrow-counterclockwise"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state-table py-5">
                            <i class="bi bi-journal-x display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">No hay subtemas eliminados</h5>
                        </div>
                    @endif
                </div>

                <!-- Actividades eliminadas -->
                <div class="tab-pane fade" id="actividades" role="tabpanel" aria-labelledby="actividades-tab">
                    @if($actividadesEliminadas->count() > 0)
                        <div class="table-container-modern shadow-none border-0 p-0">
                            <table class="table-modern">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th style="width: 30%"><div class="th-content"><i class="bi bi-card-checklist"></i><span>Título</span></div></th>
                                        <th style="width: 20%"><div class="th-content"><i class="bi bi-journal-text"></i><span>Subtema</span></div></th>
                                        <th style="width: 15%"><div class="th-content"><i class="bi bi-tag-fill"></i><span>Tipo</span></div></th>
                                        <th style="width: 15%"><div class="th-content"><i class="bi bi-calendar-event"></i><span>Eliminación</span></div></th>
                                        <th style="width: 15%" class="text-center"><div class="th-content justify-content-center"><i class="bi bi-gear-fill"></i><span>Acciones</span></div></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($actividadesEliminadas as $actividad)
                                        <tr>
                                            <td><span class="text-muted fw-bold">{{ $loop->iteration }}</span></td>
                                            <td><div class="fw-bold text-dark">{{ $actividad->titulo }}</div></td>
                                            <td><span class="status-badge status-info">{{ $actividad->subtema->titulo_subtema ?? 'Subtema eliminado' }}</span></td>
                                            <td>
                                                @php
                                                    $tipoColor = match($actividad->tipoActividad->nombre ?? '') {
                                                        'Cuestionario' => 'primary',
                                                        'Tarea' => 'warning',
                                                        default => 'secondary'
                                                    };
                                                @endphp
                                                <span class="status-badge status-{{ $tipoColor }}">
                                                    {{ $actividad->tipoActividad->nombre ?? 'Desconocido' }}
                                                </span>
                                            </td>
                                            <td><span class="status-badge status-secondary">{{ $actividad->deleted_at->format('d/m/Y H:i') }}</span></td>
                                            <td>
                                                <div class="action-buttons-cell">
                                                    <form action="{{ route('cursos.restaurar-elemento') }}" method="POST" class="form-restaurar">
                                                        @csrf
                                                        <input type="hidden" name="tipo" value="actividad">
                                                        <input type="hidden" name="id" value="{{ $actividad->id }}">
                                                        <input type="hidden" name="curso_id" value="{{ $curso->id }}">
                                                        <button type="submit" class="btn-action-modern btn-view" title="Restaurar">
                                                            <i class="bi bi-arrow-counterclockwise"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state-table py-5">
                            <i class="bi bi-card-x display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">No hay actividades eliminadas</h5>
                        </div>
                    @endif
                </div>

                <!-- Foros eliminados -->
                <div class="tab-pane fade" id="foros" role="tabpanel" aria-labelledby="foros-tab">
                    @if($forosEliminados->count() > 0)
                        <div class="table-container-modern shadow-none border-0 p-0">
                            <table class="table-modern">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th style="width: 30%"><div class="th-content"><i class="bi bi-chat-dots-fill"></i><span>Título</span></div></th>
                                        <th style="width: 40%"><div class="th-content"><i class="bi bi-text-left"></i><span>Descripción</span></div></th>
                                        <th style="width: 15%"><div class="th-content"><i class="bi bi-calendar-event"></i><span>Eliminación</span></div></th>
                                        <th style="width: 10%" class="text-center"><div class="th-content justify-content-center"><i class="bi bi-gear-fill"></i><span>Acciones</span></div></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($forosEliminados as $foro)
                                        <tr>
                                            <td><span class="text-muted fw-bold">{{ $loop->iteration }}</span></td>
                                            <td><div class="fw-bold text-dark">{{ $foro->titulo ?? 'Sin título' }}</div></td>
                                            <td><div class="text-muted small text-wrap" style="max-width: 300px;">{{ Str::limit($foro->descripcion ?? 'Sin descripción', 100) }}</div></td>
                                            <td><span class="status-badge status-secondary">{{ $foro->deleted_at->format('d/m/Y H:i') }}</span></td>
                                            <td>
                                                <div class="action-buttons-cell">
                                                    <form action="{{ route('cursos.restaurar-elemento') }}" method="POST" class="form-restaurar">
                                                        @csrf
                                                        <input type="hidden" name="tipo" value="foro">
                                                        <input type="hidden" name="id" value="{{ $foro->id }}">
                                                        <input type="hidden" name="curso_id" value="{{ $curso->id }}">
                                                        <button type="submit" class="btn-action-modern btn-view" title="Restaurar">
                                                            <i class="bi bi-arrow-counterclockwise"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state-table py-5">
                            <i class="bi bi-chat-left-x display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">No hay foros eliminados</h5>
                        </div>
                    @endif
                </div>

                <!-- Recursos eliminados -->
                <div class="tab-pane fade" id="recursos" role="tabpanel" aria-labelledby="recursos-tab">
                    @if($recursosEliminados->count() > 0)
                        <div class="table-container-modern shadow-none border-0 p-0">
                            <table class="table-modern">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th style="width: 30%"><div class="th-content"><i class="bi bi-file-earmark-fill"></i><span>Título</span></div></th>
                                        <th style="width: 40%"><div class="th-content"><i class="bi bi-text-left"></i><span>Descripción</span></div></th>
                                        <th style="width: 15%"><div class="th-content"><i class="bi bi-calendar-event"></i><span>Eliminación</span></div></th>
                                        <th style="width: 10%" class="text-center"><div class="th-content justify-content-center"><i class="bi bi-gear-fill"></i><span>Acciones</span></div></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recursosEliminados as $recurso)
                                        <tr>
                                            <td><span class="text-muted fw-bold">{{ $loop->iteration }}</span></td>
                                            <td><div class="fw-bold text-dark">{{ $recurso->titulo ?? 'Sin título' }}</div></td>
                                            <td><div class="text-muted small text-wrap" style="max-width: 300px;">{{ Str::limit($recurso->descripcion ?? 'Sin descripción', 100) }}</div></td>
                                            <td><span class="status-badge status-secondary">{{ $recurso->deleted_at->format('d/m/Y H:i') }}</span></td>
                                            <td>
                                                <div class="action-buttons-cell">
                                                    <form action="{{ route('cursos.restaurar-elemento') }}" method="POST" class="form-restaurar">
                                                        @csrf
                                                        <input type="hidden" name="tipo" value="recurso">
                                                        <input type="hidden" name="id" value="{{ $recurso->id }}">
                                                        <input type="hidden" name="curso_id" value="{{ $curso->id }}">
                                                        <button type="submit" class="btn-action-modern btn-view" title="Restaurar">
                                                            <i class="bi bi-arrow-counterclockwise"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state-table py-5">
                            <i class="bi bi-folder-x display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">No hay recursos eliminados</h5>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Confirmación de restauración con SweetAlert2
        document.querySelectorAll('.form-restaurar').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Restaurar este elemento?',
                    text: "Este recurso volverá a estar visible para los estudiantes.",
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

        // Animación de pestañas
        const tabButtons = document.querySelectorAll('button[data-bs-toggle="tab"]');
        tabButtons.forEach(button => {
            button.addEventListener('show.bs.tab', () => {
                const target = document.querySelector(button.dataset.bsTarget);
                target.style.opacity = '0';
                target.style.transform = 'translateY(10px)';
                setTimeout(() => {
                    target.style.transition = 'all 0.3s ease';
                    target.style.opacity = '1';
                    target.style.transform = 'translateY(0)';
                }, 50);
            });
        });
    });
</script>

<style>
    .w-fit-content { width: fit-content; }
    .nav-tabs .nav-link {
        color: #64748b;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .nav-tabs .nav-link.active {
        background-color: #1a4789 !important;
        color: white !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .empty-state-table {
        text-align: center;
        background: #f8fafc;
        border-radius: 20px;
        border: 2px dashed #e2e8f0;
    }
</style>
@endsection

@section('scripts')
@endsection
