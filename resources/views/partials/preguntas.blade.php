<button class="m-3 btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#crearMultiplesPreguntasModal">
    <i class="fas fa-plus"></i> Crear Múltiples Preguntas
</button>
<div class="modal fade" id="crearMultiplesPreguntasModal" tabindex="-1" aria-labelledby="crearMultiplesPreguntasLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('pregunta.store', encrypt($cuestionario->id)) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="crearMultiplesPreguntasLabel">Crear Múltiples Preguntas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="preguntas-container">
                        <div class="pregunta-item mb-3">
                            <div class="mb-3">
                                <label for="preguntaTexto" class="form-label">Texto de la Pregunta</label>
                                <input type="text" class="form-control" name="preguntas[0][enunciado]"
                                    placeholder="Escribe la pregunta aquí" required>
                            </div>
                            <div class="mb-3">
                                <label for="preguntaTipo" class="form-label">Tipo de Pregunta</label>
                                <select class="form-select" name="preguntas[0][tipo]" required>
                                    <option value="opcion_multiple">Opción Múltiple</option>
                                    <option value="abierta">Respuesta Abierta</option>
                                    <option value="boolean">Verdadero/Falso</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="puntosPregunta" class="form-label">Puntos</label>
                                <input type="number" class="form-control" name="preguntas[0][puntaje]" min="1"
                                    placeholder="Ejemplo: 5" required>
                            </div>
                            <hr>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary" id="addPreguntaButton">
                        <i class="fas fa-plus"></i> Agregar Otra Pregunta
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Preguntas</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Modales para Editar Preguntas -->
@foreach ($preguntas as $pregunta)
    <div class="modal fade" id="editarPreguntaModal-{{ $pregunta->id }}" tabindex="-1"
        aria-labelledby="editarPreguntaModalLabel-{{ $pregunta->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarPreguntaModalLabel-{{ $pregunta->id }}">Editar Pregunta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('pregunta.update', encrypt($pregunta->id)) }}">
                        @csrf

                        <!-- Texto de la Pregunta -->
                        <div class="mb-3">
                            <label for="enunciado" class="form-label">Texto de la Pregunta</label>
                            <input type="text" name="enunciado" class="form-control"
                                value="{{ $pregunta->enunciado }}" required>
                        </div>

                        <!-- Tipo de Pregunta -->
                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo de Pregunta</label>
                            <select name="tipo" class="form-select" required>
                                <option value="opcion_multiple"
                                    {{ $pregunta->tipo === 'opcion_multiple' ? 'selected' : '' }}>Opción Múltiple
                                </option>
                                <option value="abierta" {{ $pregunta->tipo === 'abierta' ? 'selected' : '' }}>Respuesta
                                    Abierta</option>
                                <option value="boolean" {{ $pregunta->tipo === 'boolean' ? 'selected' : '' }}>
                                    Verdadero/Falso</option>
                            </select>
                        </div>

                        <!-- Puntos -->
                        <div class="mb-3">
                            <label for="puntaje" class="form-label">Puntos</label>
                            <input type="number" name="puntaje" class="form-control" value="{{ $pregunta->puntaje }}"
                                min="1" required>
                        </div>

                        <button type="submit" class="btn btn-success">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

<table class="table table-striped table-hover align-middle">
    <thead>
        <tr>
            <th>#</th>
            <th>Pregunta</th>
            <th>Tipo</th>
            <th>Puntos</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($preguntas as $pregunta)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $pregunta->enunciado }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $pregunta->tipo)) }}</td>
                <td>{{ $pregunta->puntaje }}</td>
                <td>

                    @if ($pregunta->deleted_at)
                        <!-- Botón para Restaurar -->
                        <form method="POST" action="{{ route('pregunta.restore', encrypt($pregunta->id)) }}"
                            id="restore-form-{{ $pregunta->id }}" class="d-inline">
                            @csrf
                            <button type="button" class="btn btn-sm btn-success"
                                onclick="confirmRestore({{ $pregunta->id }})">
                                <i class="fas fa-undo"></i> Restaurar
                            </button>
                        </form>
                    @else
                        <!-- Botón para Editar -->
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                            data-bs-target="#editarPreguntaModal-{{ $pregunta->id }}">
                            <i class="fas fa-edit"></i> Editar
                        </button>

                        <!-- Botón para Eliminar -->
                        <form method="POST" action="{{ route('pregunta.delete', encrypt($pregunta->id)) }}"
                            id="delete-form-{{ $pregunta->id }}" class="d-inline">
                            @csrf
                            <button type="button" class="btn btn-sm btn-danger"
                                onclick="confirmDelete({{ $pregunta->id }})">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    function confirmDelete(preguntaId) {
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
                // Enviar el formulario correspondiente
                document.getElementById(`delete-form-${preguntaId}`).submit();
            }
        });
    }

    function confirmRestore(preguntaId) {
        Swal.fire({
            title: '¿Restaurar esta pregunta?',
            text: "La pregunta será restaurada y estará disponible nuevamente.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, restaurar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Enviar el formulario correspondiente
                document.getElementById(`restore-form-${preguntaId}`).submit();
            }
        });
    }
</script>
