@extends('layout')

@section('titulo')
Elementos eliminados del curso: {{ $curso->nombreCurso }}
@endsection

@section('content')
<div class="container-fluid py-4 px-md-5">
    <div class="tbl-card shadow-sm">
        {{-- Hero Section --}}
        <div class="tbl-card-hero">
            <div class="tbl-hero-left">
                <a href="{{ route('Curso', $curso->codigoCurso) }}" class="btn-modern btn-accent-custom mb-3" style="background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2);">
                    <i class="bi bi-arrow-left"></i> Volver al Curso
                </a>
                <div class="tbl-hero-eyebrow">
                    <i class="bi bi-trash-fill"></i> Papelera de Reciclaje
                </div>
                <h2 class="tbl-hero-title">Elementos Eliminados</h2>
                <p class="tbl-hero-sub">
                    Curso: <span class="fw-bold">{{ $curso->nombreCurso }}</span>
                </p>
            </div>

            <div class="tbl-hero-controls">
                <div class="d-flex gap-2">
                    <div class="ec-role-badge" style="background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2);">
                        <i class="bi bi-people-fill me-1"></i> {{ $cantidadInscritos }} Estudiantes
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4 p-lg-5">
            <div class="alert alert-info border-0 rounded-4 shadow-sm mb-5 d-flex align-items-center p-4">
                <div class="bg-info-subtle text-info p-3 rounded-circle me-4">
                    <i class="bi bi-info-circle-fill fs-3"></i>
                </div>
                <div>
                    <h6 class="mb-1 fw-bold">Restauración de elementos</h6>
                    <p class="mb-0 text-muted small">La restauración permitirá que los estudiantes vuelvan a acceder a estos recursos inmediatamente en el aula virtual.</p>
                </div>
            </div>

            {{-- Navegación de las pestañas Modernizada --}}
            <ul class="nav nav-pills mb-5 bg-light p-2 rounded-4 gap-2 justify-content-center flex-wrap" id="elementosTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-3 px-4 py-2 fw-bold" id="temas-tab" data-bs-toggle="tab" data-bs-target="#temas" type="button" role="tab">
                        <i class="bi bi-journal-bookmark me-2"></i>Temas ({{ $temasEliminados->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-3 px-4 py-2 fw-bold" id="subtemas-tab" data-bs-toggle="tab" data-bs-target="#subtemas" type="button" role="tab">
                        <i class="bi bi-journal-text me-2"></i>Subtemas ({{ $subtemasEliminados->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-3 px-4 py-2 fw-bold" id="actividades-tab" data-bs-toggle="tab" data-bs-target="#actividades" type="button" role="tab">
                        <i class="bi bi-card-checklist me-2"></i>Actividades ({{ $actividadesEliminadas->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-3 px-4 py-2 fw-bold" id="foros-tab" data-bs-toggle="tab" data-bs-target="#foros" type="button" role="tab">
                        <i class="bi bi-chat-dots me-2"></i>Foros ({{ $forosEliminados->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-3 px-4 py-2 fw-bold" id="recursos-tab" data-bs-toggle="tab" data-bs-target="#recursos" type="button" role="tab">
                        <i class="bi bi-folder me-2"></i>Recursos ({{ $recursosEliminados->count() }})
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="elementosTabsContent">
                <!-- Temas eliminados -->
                <div class="tab-pane fade show active" id="temas" role="tabpanel">
                    @if($temasEliminados->count() > 0)
                        <div class="table-responsive rounded-4 border overflow-hidden">
                            <table class="table table-modern table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th style="width: 30%">Título</th>
                                        <th style="width: 40%">Descripción</th>
                                        <th style="width: 15%">Eliminación</th>
                                        <th style="width: 10%" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($temasEliminados as $tema)
                                        <tr>
                                            <td><span class="text-muted fw-bold">{{ $loop->iteration }}</span></td>
                                            <td><div class="fw-bold text-dark">{{ $tema->titulo_tema }}</div></td>
                                            <td><div class="text-muted small text-truncate" style="max-width: 300px;">{{ $tema->descripcion ?: 'Sin descripción' }}</div></td>
                                            <td><span class="badge bg-light text-dark border fw-medium"><i class="bi bi-clock me-1"></i>{{ $tema->deleted_at->format('d/m/Y') }}</span></td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <form action="{{ route('cursos.restaurar-elemento') }}" method="POST" class="form-restaurar">
                                                        @csrf
                                                        <input type="hidden" name="tipo" value="tema">
                                                        <input type="hidden" name="id" value="{{ $tema->id }}">
                                                        <input type="hidden" name="curso_id" value="{{ $curso->id }}">
                                                        <button type="submit" class="btn-action-modern btn-restore" title="Restaurar">
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
                        <div class="text-center py-5 bg-light rounded-4">
                            <i class="bi bi-journal-x display-4 text-muted mb-3 d-block"></i>
                            <h5 class="text-muted fw-bold">No hay temas eliminados</h5>
                        </div>
                    @endif
                </div>

                <!-- Subtemas eliminados -->
                <div class="tab-pane fade" id="subtemas" role="tabpanel">
                    @if($subtemasEliminados->count() > 0)
                        <div class="table-responsive rounded-4 border overflow-hidden">
                            <table class="table table-modern table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th style="width: 25%">Título</th>
                                        <th style="width: 20%">Tema</th>
                                        <th style="width: 30%">Descripción</th>
                                        <th style="width: 10%">Eliminación</th>
                                        <th style="width: 10%" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subtemasEliminados as $subtema)
                                        <tr>
                                            <td><span class="text-muted fw-bold">{{ $loop->iteration }}</span></td>
                                            <td><div class="fw-bold text-dark">{{ $subtema->titulo_subtema }}</div></td>
                                            <td><span class="badge bg-primary-subtle text-primary">{{ $subtema->tema->titulo_tema ?? 'Tema eliminado' }}</span></td>
                                            <td><div class="text-muted small text-truncate" style="max-width: 250px;">{{ $subtema->descripcion ?: 'Sin descripción' }}</div></td>
                                            <td><span class="badge bg-light text-dark border fw-medium">{{ $subtema->deleted_at->format('d/m/Y') }}</span></td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <form action="{{ route('cursos.restaurar-elemento') }}" method="POST" class="form-restaurar">
                                                        @csrf
                                                        <input type="hidden" name="tipo" value="subtema">
                                                        <input type="hidden" name="id" value="{{ $subtema->id }}">
                                                        <input type="hidden" name="curso_id" value="{{ $curso->id }}">
                                                        <button type="submit" class="btn-action-modern btn-restore" title="Restaurar">
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
                        <div class="text-center py-5 bg-light rounded-4">
                            <i class="bi bi-journal-x display-4 text-muted mb-3 d-block"></i>
                            <h5 class="text-muted fw-bold">No hay subtemas eliminados</h5>
                        </div>
                    @endif
                </div>

                <!-- Actividades eliminadas -->
                <div class="tab-pane fade" id="actividades" role="tabpanel">
                    @if($actividadesEliminadas->count() > 0)
                        <div class="table-responsive rounded-4 border overflow-hidden">
                            <table class="table table-modern table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th style="width: 30%">Título</th>
                                        <th style="width: 20%">Subtema</th>
                                        <th style="width: 15%">Tipo</th>
                                        <th style="width: 15%">Eliminación</th>
                                        <th style="width: 15%" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($actividadesEliminadas as $actividad)
                                        <tr>
                                            <td><span class="text-muted fw-bold">{{ $loop->iteration }}</span></td>
                                            <td><div class="fw-bold text-dark">{{ $actividad->titulo }}</div></td>
                                            <td><span class="badge bg-info-subtle text-info">{{ $actividad->subtema->titulo_subtema ?? 'Subtema eliminado' }}</span></td>
                                            <td>
                                                @php
                                                    $tipoColor = match($actividad->tipoActividad->nombre ?? '') {
                                                        'Cuestionario' => 'primary',
                                                        'Tarea' => 'warning',
                                                        default => 'secondary'
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $tipoColor }}-subtle text-{{ $tipoColor }}">
                                                    {{ $actividad->tipoActividad->nombre ?? 'Desconocido' }}
                                                </span>
                                            </td>
                                            <td><span class="badge bg-light text-dark border fw-medium">{{ $actividad->deleted_at->format('d/m/Y') }}</span></td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <form action="{{ route('cursos.restaurar-elemento') }}" method="POST" class="form-restaurar">
                                                        @csrf
                                                        <input type="hidden" name="tipo" value="actividad">
                                                        <input type="hidden" name="id" value="{{ $actividad->id }}">
                                                        <input type="hidden" name="curso_id" value="{{ $curso->id }}">
                                                        <button type="submit" class="btn-action-modern btn-restore" title="Restaurar">
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
                        <div class="text-center py-5 bg-light rounded-4">
                            <i class="bi bi-card-x display-4 text-muted mb-3 d-block"></i>
                            <h5 class="text-muted fw-bold">No hay actividades eliminadas</h5>
                        </div>
                    @endif
                </div>

                <!-- Foros eliminados -->
                <div class="tab-pane fade" id="foros" role="tabpanel">
                    @if($forosEliminados->count() > 0)
                        <div class="table-responsive rounded-4 border overflow-hidden">
                            <table class="table table-modern table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th style="width: 30%">Título</th>
                                        <th style="width: 40%">Descripción</th>
                                        <th style="width: 15%">Eliminación</th>
                                        <th style="width: 10%" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($forosEliminados as $foro)
                                        <tr>
                                            <td><span class="text-muted fw-bold">{{ $loop->iteration }}</span></td>
                                            <td><div class="fw-bold text-dark">{{ $foro->titulo ?? 'Sin título' }}</div></td>
                                            <td><div class="text-muted small text-truncate" style="max-width: 300px;">{{ $foro->descripcion ?? 'Sin descripción' }}</div></td>
                                            <td><span class="badge bg-light text-dark border fw-medium">{{ $foro->deleted_at->format('d/m/Y') }}</span></td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <form action="{{ route('cursos.restaurar-elemento') }}" method="POST" class="form-restaurar">
                                                        @csrf
                                                        <input type="hidden" name="tipo" value="foro">
                                                        <input type="hidden" name="id" value="{{ $foro->id }}">
                                                        <input type="hidden" name="curso_id" value="{{ $curso->id }}">
                                                        <button type="submit" class="btn-action-modern btn-restore" title="Restaurar">
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
                        <div class="text-center py-5 bg-light rounded-4">
                            <i class="bi bi-chat-left-x display-4 text-muted mb-3 d-block"></i>
                            <h5 class="text-muted fw-bold">No hay foros eliminados</h5>
                        </div>
                    @endif
                </div>

                <!-- Recursos eliminados -->
                <div class="tab-pane fade" id="recursos" role="tabpanel">
                    @if($recursosEliminados->count() > 0)
                        <div class="table-responsive rounded-4 border overflow-hidden">
                            <table class="table table-modern table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th style="width: 30%">Título</th>
                                        <th style="width: 40%">Descripción</th>
                                        <th style="width: 15%">Eliminación</th>
                                        <th style="width: 10%" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recursosEliminados as $recurso)
                                        <tr>
                                            <td><span class="text-muted fw-bold">{{ $loop->iteration }}</span></td>
                                            <td><div class="fw-bold text-dark">{{ $recurso->titulo ?? 'Sin título' }}</div></td>
                                            <td><div class="text-muted small text-truncate" style="max-width: 300px;">{{ $recurso->descripcion ?? 'Sin descripción' }}</div></td>
                                            <td><span class="badge bg-light text-dark border fw-medium">{{ $recurso->deleted_at->format('d/m/Y') }}</span></td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <form action="{{ route('cursos.restaurar-elemento') }}" method="POST" class="form-restaurar">
                                                        @csrf
                                                        <input type="hidden" name="tipo" value="recurso">
                                                        <input type="hidden" name="id" value="{{ $recurso->id }}">
                                                        <input type="hidden" name="curso_id" value="{{ $curso->id }}">
                                                        <button type="submit" class="btn-action-modern btn-restore" title="Restaurar">
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
                        <div class="text-center py-5 bg-light rounded-4">
                            <i class="bi bi-folder-x display-4 text-muted mb-3 d-block"></i>
                            <h5 class="text-muted fw-bold">No hay recursos eliminados</h5>
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
