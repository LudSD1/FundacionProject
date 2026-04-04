{{-- Modal: Lista de Horarios --}}
<div class="modal fade" id="modalHorario" tabindex="-1" aria-labelledby="modalHorarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title d-flex align-items-center" id="modalHorarioLabel">
                    <i class="bi bi-calendar3 me-2"></i>Lista de Horarios
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-0">
                @if($horarios->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x display-4 text-muted"></i>
                        <p class="mt-3 text-muted">No hay horarios registrados para este curso</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="bi bi-calendar-day me-1"></i>Día</th>
                                    <th><i class="bi bi-clock me-1"></i>Hora Inicio</th>
                                    <th><i class="bi bi-clock-fill me-1"></i>Hora Fin</th>
                                    <th><i class="bi bi-hourglass-split me-1"></i>Duración</th>
                                    @if($cursos->docente_id == auth()->user()->id || auth()->user()->hasRole('Administrador'))
                                        <th class="text-center"><i class="bi bi-gear me-1"></i>Acciones</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($horarios as $horario)
                                    @php
                                        $inicio   = Carbon\Carbon::parse($horario->horario->hora_inicio);
                                        $fin      = Carbon\Carbon::parse($horario->horario->hora_fin);
                                        $duracion = $inicio->diff($fin);
                                    @endphp
                                    <tr class="{{ $horario->trashed() ? 'table-warning' : '' }}">
                                        <td class="fw-medium">
                                            <span class="badge bg-light text-dark border">
                                                {{ $horario->horario->dia }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-success fw-medium">{{ $inicio->format('h:i A') }}</span>
                                        </td>
                                        <td>
                                            <span class="text-danger fw-medium">{{ $fin->format('h:i A') }}</span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $duracion->h }}h {{ $duracion->i }}m</small>
                                        </td>
                                        @if($cursos->docente_id == auth()->user()->id || auth()->user()->hasRole('Administrador'))
                                            <td>
                                                <div class="d-flex gap-2 justify-content-center">
                                                    @if($horario->trashed())
                                                        <span class="badge bg-warning text-dark">
                                                            <i class="bi bi-archive"></i> Eliminado
                                                        </span>
                                                        <form action="{{ route('horarios.restore', ['id' => $horario->id]) }}"
                                                              method="POST"
                                                              onsubmit="return confirm('¿Restaurar este horario?')">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                                <i class="bi bi-arrow-clockwise"></i> Restaurar
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button class="btn btn-sm btn-outline-primary btn-editar-horario"
                                                                data-id="{{ $horario->id }}"
                                                                data-dia="{{ $horario->horario->dia }}"
                                                                data-hora-inicio="{{ $horario->horario->hora_inicio }}"
                                                                data-hora-fin="{{ $horario->horario->hora_fin }}"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalEditarHorario">
                                                            <i class="bi bi-pencil"></i> Editar
                                                        </button>
                                                        <form action="{{ route('horarios.delete', ['id' => $horario->id]) }}"
                                                              method="POST"
                                                              onsubmit="return confirm('¿Eliminar este horario? Esta acción se puede revertir.')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                <i class="bi bi-trash"></i> Eliminar
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            <div class="modal-footer bg-light">
                @if($cursos->docente_id == auth()->user()->id || auth()->user()->hasRole('Administrador'))
                    <button type="button" class="btn btn-success"
                            data-bs-toggle="modal" data-bs-target="#modalCrearHorario">
                        <i class="bi bi-plus-circle me-1"></i>Agregar Horario
                    </button>
                @endif
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal: Crear Horario --}}
<div class="modal fade" id="modalCrearHorario" tabindex="-1" aria-labelledby="modalCrearHorarioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('horarios.store') }}" id="formCrearHorario" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCrearHorarioLabel">Agregar Horario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="curso_id" value="{{ $cursos->id }}">
                    <div class="mb-3">
                        <label for="dia" class="form-label">Día</label>
                        <select name="dia" id="dia" class="form-select">
                            @foreach(['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'] as $dia)
                                <option value="{{ $dia }}">{{ $dia }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="hora_inicio" class="form-label">Hora de Inicio</label>
                        <input type="time" name="hora_inicio" id="hora_inicio" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="hora_fin" class="form-label">Hora de Fin</label>
                        <input type="time" name="hora_fin" id="hora_fin" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: Editar Horario --}}
<div class="modal fade" id="modalEditarHorario" tabindex="-1" aria-labelledby="modalEditarHorarioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarHorarioLabel">Editar Horario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarHorario" action="" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="edit_dia" class="form-label">Día</label>
                        <select name="dia" id="edit_dia" class="form-select" required>
                            @foreach(['lunes','martes','miércoles','jueves','viernes','sábado','domingo'] as $dia)
                                <option value="{{ $dia }}">{{ ucfirst($dia) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_hora_inicio" class="form-label">Hora de Inicio</label>
                        <input type="time" name="hora_inicio" id="edit_hora_inicio" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_hora_fin" class="form-label">Hora de Fin</label>
                        <input type="time" name="hora_fin" id="edit_hora_fin" class="form-control" required>
                    </div>
                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
