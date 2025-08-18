<div class="mb-3">
    <h4 class="border-bottom pb-2">
        <i class="fas fa-reply me-2"></i> Respuestas por Pregunta
    </h4>
</div>

<!-- Navegación de Pestañas -->
<ul class="nav nav-tabs" id="preguntasTabs" role="tablist">
    @foreach ($preguntas as $index => $pregunta)
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="tab-{{ $pregunta->id }}" data-bs-toggle="tab"
                data-bs-target="#contenido-{{ $pregunta->id }}" type="button" role="tab"
                aria-controls="contenido-{{ $pregunta->id }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                Pregunta {{ $loop->iteration }}
            </button>
        </li>
    @endforeach
</ul>

<!-- Contenido de las Pestañas -->
<div class="tab-content" id="preguntasTabsContent">
    @foreach ($preguntas as $index => $pregunta)
        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="contenido-{{ $pregunta->id }}"
            role="tabpanel" aria-labelledby="tab-{{ $pregunta->id }}">
            <h5 class="mt-4">
                <i class="fas fa-question-circle me-2"></i> {{ $pregunta->enunciado }}
                ({{ ucfirst(str_replace('_', ' ', $pregunta->tipo)) }})
            </h5>

            <!-- Botón para Crear Respuesta -->
            @if ($pregunta->tipo === 'opcion_multiple')
                <button class="btn btn-sm btn-primary mb-3" data-bs-toggle="modal"
                    data-bs-target="#crearRespuestaModal-{{ $pregunta->id }}">
                    <i class="fas fa-plus"></i> Crear Respuesta
                </button>
            @elseif ($pregunta->tipo === 'abierta')
                <button class="btn btn-sm btn-primary mb-3" data-bs-toggle="modal"
                    data-bs-target="#crearRespuestaClaveModal-{{ $pregunta->id }}">
                    <i class="fas fa-plus"></i> Crear Respuesta Clave
                </button>
            @elseif ($pregunta->tipo === 'boolean')
                <form method="POST" action="{{ route('respuestas.storeVerdaderoFalso', encrypt($pregunta->id)) }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-primary mb-3">
                        <i class="fas fa-plus"></i> Generar Respuestas Verdadero/Falso
                    </button>
                </form>
            @endif

            <!-- Tabla de Respuestas -->
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Respuesta</th>
                        <th>¿Es Correcta?</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pregunta->respuestas as $respuesta)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $respuesta->contenido }}</td>
                            <td>
                                @if ($respuesta->es_correcta)
                                    <span class="badge bg-success">Sí</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </td>
                            <td>
                                <!-- Botón para Editar Respuesta -->
                                <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal"
                                    data-bs-target="#editarRespuestaModal-{{ $respuesta->id }}">
                                    <i class="fas fa-edit"></i> Editar
                                </button>

                                <!-- Botón para Eliminar Respuesta -->
                                <form method="POST" action="{{ route('respuestas.delete', encrypt($respuesta->id)) }}"
                                    id="delete-form-{{ $respuesta->id }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="confirmDelete({{ $respuesta->id }})">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                <i class="fas fa-inbox"></i> No hay respuestas para esta pregunta.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endforeach
</div>

<!-- Modales para Editar Respuestas -->
@foreach ($preguntas as $pregunta)
    @foreach ($pregunta->respuestas as $respuesta)
        <div class="modal fade" id="editarRespuestaModal-{{ $respuesta->id }}" tabindex="-1"
            aria-labelledby="editarRespuestaModalLabel-{{ $respuesta->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editarRespuestaModalLabel-{{ $respuesta->id }}">
                            <i class="fas fa-edit me-2"></i>Editar Respuesta
                            @if ($pregunta->tipo === 'boolean')
                                <small class="text-muted">(Verdadero/Falso)</small>
                            @endif
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('respuestas.update', encrypt($respuesta->id)) }}">
                            @csrf
                            @method('PUT')

                            <!-- Contenido de la Respuesta -->
                            <div class="mb-3">
                                <label for="contenido-{{ $respuesta->id }}" class="form-label">
                                    <i class="fas fa-comment me-1"></i>Contenido de la Respuesta
                                </label>
                                <input type="text" name="contenido" id="contenido-{{ $respuesta->id }}"
                                    class="form-control" value="{{ old('contenido', $respuesta->contenido) }}"
                                    @if ($pregunta->tipo === 'boolean') readonly @endif required>
                                @if ($pregunta->tipo === 'boolean')
                                    <small class="form-text text-muted">
                                        Las respuestas de Verdadero/Falso no se pueden modificar en contenido.
                                    </small>
                                @endif
                            </div>

                            <!-- ¿Es Correcta? -->
                            @if ($pregunta->tipo === 'opcion_multiple' || $pregunta->tipo === 'boolean')
                                <div class="mb-3">
                                    <label for="es_correcta-{{ $respuesta->id }}" class="form-label">
                                        <i class="fas fa-check-circle me-1"></i>¿Es Correcta?
                                    </label>
                                    <select name="es_correcta" id="es_correcta-{{ $respuesta->id }}"
                                        class="form-select" required>
                                        <option value="1" {{ old('es_correcta', $respuesta->es_correcta) ? 'selected' : '' }}>
                                            <i class="fas fa-check"></i> Sí
                                        </option>
                                        <option value="0" {{ !old('es_correcta', $respuesta->es_correcta) ? 'selected' : '' }}>
                                            <i class="fas fa-times"></i> No
                                        </option>
                                    </select>
                                </div>
                            @elseif ($pregunta->tipo === 'abierta')
                                <!-- Para preguntas abiertas, siempre es correcta -->
                                <input type="hidden" name="es_correcta" value="1">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Las respuestas clave de preguntas abiertas siempre se consideran correctas.
                                </div>
                            @endif

                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i>Cancelar
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i>Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endforeach

<!-- Modal para Crear Respuestas de Tipo Opción Múltiple -->
@foreach ($preguntas as $pregunta)
@if ($pregunta->tipo === 'opcion_multiple')
    <div class="modal fade" id="crearRespuestaModal-{{ $pregunta->id }}" tabindex="-1"
        aria-labelledby="crearRespuestaModalLabel-{{ $pregunta->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="crearRespuestaModalLabel-{{ $pregunta->id }}">
                        <i class="fas fa-plus me-2"></i>Agregar Respuestas (Opción Múltiple)
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('respuestas.storeMultiple', encrypt($pregunta->id)) }}">
                        @csrf
                        <div id="respuestas-container-{{ $pregunta->id }}">
                            <div class="mb-3 border p-3 rounded">
                                <label for="respuesta_1_{{ $pregunta->id }}" class="form-label fw-bold">Respuesta 1</label>
                                <input type="text" name="respuestas[0][contenido]" id="respuesta_1_{{ $pregunta->id }}"
                                    class="form-control mb-2" placeholder="Escriba la primera respuesta..." required>
                                <select name="respuestas[0][es_correcta]" class="form-select" required>
                                    <option value="">Seleccione si es correcta...</option>
                                    <option value="1">✓ Correcta</option>
                                    <option value="0">✗ Incorrecta</option>
                                </select>
                            </div>
                            <div class="mb-3 border p-3 rounded">
                                <label for="respuesta_2_{{ $pregunta->id }}" class="form-label fw-bold">Respuesta 2</label>
                                <input type="text" name="respuestas[1][contenido]" id="respuesta_2_{{ $pregunta->id }}"
                                    class="form-control mb-2" placeholder="Escriba la segunda respuesta..." required>
                                <select name="respuestas[1][es_correcta]" class="form-select" required>
                                    <option value="">Seleccione si es correcta...</option>
                                    <option value="1">✓ Correcta</option>
                                    <option value="0">✗ Incorrecta</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-primary" id="addRespuestaButton-{{ $pregunta->id }}">
                                <i class="fas fa-plus"></i> Agregar Más Respuestas
                            </button>
                            <div>
                                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">
                                    <i class="fas fa-times"></i> Cancelar
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Guardar Respuestas
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif
@endforeach

<!-- Modal para Crear Respuestas de Tipo Abierta -->
@foreach ($preguntas as $pregunta)
    @if ($pregunta->tipo === 'abierta')
        <div class="modal fade" id="crearRespuestaClaveModal-{{ $pregunta->id }}" tabindex="-1"
            aria-labelledby="crearRespuestaClaveModalLabel-{{ $pregunta->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="crearRespuestaClaveModalLabel-{{ $pregunta->id }}">
                            <i class="fas fa-key me-2"></i>Agregar Respuesta Clave (Pregunta Abierta)
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('respuestas.storeRespuestasClave', encrypt($pregunta->id)) }}">
                            @csrf
                            <div id="respuestas-clave-container-{{ $pregunta->id }}">
                                <div class="mb-3">
                                    <label for="respuesta_clave_1_{{ $pregunta->id }}" class="form-label fw-bold">
                                        <i class="fas fa-lightbulb me-1"></i>Respuesta Clave 1
                                    </label>
                                    <input type="text" name="respuestas[0][contenido]"
                                        id="respuesta_clave_1_{{ $pregunta->id }}" class="form-control"
                                        placeholder="Escriba una respuesta clave..." required>
                                    <!-- Campo oculto para marcar como correcta -->
                                    <input type="hidden" name="respuestas[0][es_correcta]" value="1">
                                    <small class="form-text text-muted">
                                        Esta será una posible respuesta correcta para la pregunta abierta.
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-primary"
                                    id="addRespuestaClaveButton-{{ $pregunta->id }}">
                                    <i class="fas fa-plus"></i> Agregar Más Respuestas Clave
                                </button>
                                <div>
                                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">
                                        <i class="fas fa-times"></i> Cancelar
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Guardar Respuestas
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

<script>
    function confirmDelete(respuestaId) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${respuestaId}`).submit();
            }
        });
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        @foreach ($preguntas as $pregunta)
            @if ($pregunta->tipo === 'opcion_multiple')
                const container{{ $pregunta->id }} = document.getElementById('respuestas-container-{{ $pregunta->id }}');
                const addButton{{ $pregunta->id }} = document.getElementById('addRespuestaButton-{{ $pregunta->id }}');
                let respuestaIndex{{ $pregunta->id }} = 2;

                addButton{{ $pregunta->id }}.addEventListener('click', function () {
                    const nuevaRespuesta = `
                        <div class="respuesta-item mb-3 border p-3 rounded">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label fw-bold mb-0">Respuesta ${respuestaIndex{{ $pregunta->id }} + 1}</label>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-respuesta">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <input type="text" name="respuestas[${respuestaIndex{{ $pregunta->id }}}][contenido]"
                                class="form-control mb-2" placeholder="Escriba la respuesta..." required>
                            <select name="respuestas[${respuestaIndex{{ $pregunta->id }}}][es_correcta]"
                                class="form-select" required>
                                <option value="">Seleccione si es correcta...</option>
                                <option value="1">✓ Correcta</option>
                                <option value="0">✗ Incorrecta</option>
                            </select>
                        </div>
                    `;
                    container{{ $pregunta->id }}.insertAdjacentHTML('beforeend', nuevaRespuesta);
                    respuestaIndex{{ $pregunta->id }}++;

                    // Agregar evento para eliminar respuesta
                    const removeButtons = container{{ $pregunta->id }}.querySelectorAll('.remove-respuesta');
                    removeButtons.forEach(button => {
                        button.addEventListener('click', function() {
                            this.closest('.respuesta-item').remove();
                        });
                    });
                });
            @endif
        @endforeach
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @foreach ($preguntas as $pregunta)
            @if ($pregunta->tipo === 'abierta')
                const containerClave{{ $pregunta->id }} = document.getElementById('respuestas-clave-container-{{ $pregunta->id }}');
                const addButtonClave{{ $pregunta->id }} = document.getElementById('addRespuestaClaveButton-{{ $pregunta->id }}');
                let respuestaClaveIndex{{ $pregunta->id }} = 1;

                addButtonClave{{ $pregunta->id }}.addEventListener('click', function() {
                    const nuevaRespuestaClave = `
                        <div class="respuesta-item mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label fw-bold mb-0">
                                    <i class="fas fa-lightbulb me-1"></i>Respuesta Clave ${respuestaClaveIndex{{ $pregunta->id }} + 1}
                                </label>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-respuesta-clave">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <input type="text" name="respuestas[${respuestaClaveIndex{{ $pregunta->id }}}][contenido]"
                                class="form-control" placeholder="Escriba una respuesta clave..." required>
                            <input type="hidden" name="respuestas[${respuestaClaveIndex{{ $pregunta->id }}}][es_correcta]" value="1">
                            <small class="form-text text-muted">
                                Esta será una posible respuesta correcta para la pregunta abierta.
                            </small>
                            <hr class="mt-3">
                        </div>
                    `;
                    containerClave{{ $pregunta->id }}.insertAdjacentHTML('beforeend', nuevaRespuestaClave);
                    respuestaClaveIndex{{ $pregunta->id }}++;

                    // Agregar evento para eliminar respuesta clave
                    const removeButtons = containerClave{{ $pregunta->id }}.querySelectorAll('.remove-respuesta-clave');
                    removeButtons.forEach(button => {
                        button.addEventListener('click', function() {
                            this.closest('.respuesta-item').remove();
                        });
                    });
                });
            @endif
        @endforeach
    });
</script>
