{{-- ============================================================
     TABLA DE RESPUESTAS REUTILIZABLE
     Recibe: $pregunta
     Usa el mismo patrón de tabla que ListaUsuarios
     ============================================================ --}}
@if ($pregunta->respuestas->count())
    <div class="table-container-modern">
        <table class="table-modern">
            <thead>
                <tr>
                    <th width="5%">
                        <div class="th-content">
                            <i class="bi bi-hash"></i><span>#</span>
                        </div>
                    </th>
                    <th width="45%">
                        <div class="th-content">
                            <i class="bi bi-chat-left-text-fill"></i><span>Respuesta</span>
                        </div>
                    </th>
                    <th width="25%" class="text-center">
                        <div class="th-content justify-content-center">
                            <i class="bi bi-patch-check-fill"></i><span>Estado</span>
                        </div>
                    </th>
                    <th width="25%" class="text-center">
                        <div class="th-content justify-content-center">
                            <i class="bi bi-gear-fill"></i><span>Acciones</span>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pregunta->respuestas as $respuesta)
                    <tr>
                        <td><span class="row-number">{{ $loop->iteration }}</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rp-answer-icon">
                                    @if ($respuesta->es_correcta)
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    @else
                                        <i class="bi bi-chat-left-text text-primary"></i>
                                    @endif
                                </div>
                                <span class="fw-semibold">{{ $respuesta->contenido }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            @if ($respuesta->es_correcta)
                                <span class="status-badge status-active">
                                    <i class="bi bi-check-circle-fill me-1"></i> Correcta
                                </span>
                            @else
                                <span class="status-badge status-inactive">
                                    <i class="bi bi-x-circle-fill me-1"></i> Incorrecta
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons-cell">
                                <button class="btn-action-modern btn-edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#rp-modal-edit-{{ $respuesta->id }}"
                                        title="Editar respuesta">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <form method="POST"
                                      action="{{ route('respuestas.delete', encrypt($respuesta->id)) }}"
                                      class="form-eliminar d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn-action-modern btn-delete"
                                            title="Eliminar respuesta">
                                        <i class="bi bi-trash-fill"></i>
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
    {{-- Estado vacío --}}
    <div class="rp-empty-state">
        <div class="rp-empty-icon">
            <i class="bi bi-inbox"></i>
        </div>
        <h6 class="fw-bold text-muted mb-1">Sin respuestas registradas</h6>
        <p class="text-muted small mb-0">
            @if($pregunta->tipo === 'opcion_multiple')
                Haz clic en <strong>"Crear Respuesta"</strong> para agregar opciones.
            @elseif($pregunta->tipo === 'abierta')
                Haz clic en <strong>"Respuesta Clave"</strong> para definir las claves aceptadas.
            @else
                Haz clic en <strong>"Generar V/F"</strong> para crear las opciones automáticamente.
            @endif
        </p>
    </div>
@endif
