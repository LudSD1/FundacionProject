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
                            <td>{{ $respuesta->es_correcta ? 'Sí' : 'No' }}</td>
                            <td>
                                <!-- Botón para Editar Respuesta -->
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editarRespuestaModal-{{ $respuesta->id }}">
                                    <i class="fas fa-edit"></i> Editar
                                </button>

                                <!-- Botón para Eliminar Respuesta -->
                                <form method="POST" action="{{ route('respuestas.delete', encrypt($respuesta->id)) }}" id="delete-form-{{ $respuesta->id }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $respuesta->id }})">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No hay respuestas para esta pregunta.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endforeach
</div>

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
                // Enviar el formulario correspondiente
                document.getElementById(`delete-form-${respuestaId}`).submit();
            }
        });
    }
</script>

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
                // Enviar el formulario correspondiente
                document.getElementById(`delete-form-${respuestaId}`).submit();
            }
        });
    }
</script>



<!-- Modales para Editar Respuestas -->
@foreach ($preguntas as $pregunta)
    @foreach ($pregunta->respuestas as $respuesta)
        <div class="modal fade" id="editarRespuestaModal-{{ $respuesta->id }}" tabindex="-1" aria-labelledby="editarRespuestaModalLabel-{{ $respuesta->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editarRespuestaModalLabel-{{ $respuesta->id }}">
                            Editar Respuesta
                            @if ($pregunta->tipo === 'boolean') (Verdadero/Falso) @endif
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('respuestas.update', encrypt($respuesta->id)) }}">
                            @csrf
                            @method('PUT')

                            <!-- Contenido de la Respuesta -->
                            <div class="mb-3">
                                <label for="contenido" class="form-label">Contenido</label>
                                <input type="text" name="contenido" class="form-control" value="{{ $respuesta->contenido }}"
                                    @if ($pregunta->tipo === 'boolean') readonly @endif required>
                            </div>

                            <!-- ¿Es Correcta? -->
                            @if ($pregunta->tipo === 'opcion_multiple' || $pregunta->tipo === 'boolean')
                                <div class="mb-3">
                                    <label for="es_correcta" class="form-label">¿Es Correcta?</label>
                                    <select name="es_correcta" class="form-select" required>
                                        <option value="1" {{ $respuesta->es_correcta ? 'selected' : '' }}>Sí</option>
                                        <option value="0" {{ !$respuesta->es_correcta ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                            @endif

                            <button type="submit" class="btn btn-success">Guardar Cambios</button>
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
    <div class="modal fade" id="crearRespuestaModal-{{ $pregunta->id }}" tabindex="-1" aria-labelledby="crearRespuestaModalLabel-{{ $pregunta->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="crearRespuestaModalLabel-{{ $pregunta->id }}">Agregar Respuestas (Opción Múltiple)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('respuestas.storeMultiple', encrypt($pregunta->id)) }}">
                        @csrf
                        <div id="respuestas-container-{{ $pregunta->id }}">
                            <div class="mb-3">
                                <label for="respuesta_1" class="form-label">Respuesta 1</label>
                                <input type="text" name="respuestas[0][contenido]" class="form-control" required>
                                <select name="respuestas[0][es_correcta]" class="form-select mt-2" required>
                                    <option value="1">Correcta</option>
                                    <option value="0">Incorrecta</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="respuesta_2" class="form-label">Respuesta 2</label>
                                <input type="text" name="respuestas[1][contenido]" class="form-control" required>
                                <select name="respuestas[1][es_correcta]" class="form-select mt-2" required>
                                    <option value="1">Correcta</option>
                                    <option value="0">Incorrecta</option>
                                </select>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addRespuestaButton-{{ $pregunta->id }}">
                            <i class="fas fa-plus"></i> Agregar Respuesta
                        </button>
                        <button type="submit" class="btn btn-success mt-3">Guardar Respuestas</button>
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
        <div class="modal fade" id="crearRespuestaClaveModal-{{ $pregunta->id }}" tabindex="-1" aria-labelledby="crearRespuestaClaveModalLabel-{{ $pregunta->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="crearRespuestaClaveModalLabel-{{ $pregunta->id }}">Agregar Respuesta Clave (Pregunta Abierta)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('respuestas.storeRespuestasClave', encrypt($pregunta->id)) }}">
                            @csrf
                            <div id="respuestas-clave-container-{{ $pregunta->id }}">
                                <div class="mb-3">
                                    <label for="respuesta_1" class="form-label">Respuesta Clave</label>
                                    <input type="text" name="respuestas[0][contenido]" class="form-control" required>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="addRespuestaClaveButton-{{ $pregunta->id }}">
                                <i class="fas fa-plus"></i> Agregar Respuesta Clave
                            </button>
                            <button type="submit" class="btn btn-success mt-3">Guardar Respuesta Clave</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach



<script>
    document.addEventListener('DOMContentLoaded', function () {
        @foreach ($preguntas as $pregunta)
            @if ($pregunta->tipo === 'opcion_multiple')
                // Seleccionar el contenedor y el botón para agregar respuestas
                const container{{ $pregunta->id }} = document.getElementById('respuestas-container-{{ $pregunta->id }}');
                const addButton{{ $pregunta->id }} = document.getElementById('addRespuestaButton-{{ $pregunta->id }}');
                let respuestaIndex{{ $pregunta->id }} = 2; // Comienza en 2 porque ya hay dos respuestas iniciales

                // Agregar evento al botón para añadir más respuestas
                addButton{{ $pregunta->id }}.addEventListener('click', function () {
                    const nuevaRespuesta = `
                        <div class="respuesta-item mb-3">
                            <label for="respuesta_${respuestaIndex{{ $pregunta->id }}}" class="form-label">Respuesta ${respuestaIndex{{ $pregunta->id }} + 1}</label>
                            <input type="text" name="respuestas[${respuestaIndex{{ $pregunta->id }} }][contenido]" class="form-control" required>
                            <select name="respuestas[${respuestaIndex{{ $pregunta->id }} }][es_correcta]" class="form-select mt-2" required>
                                <option value="1">Correcta</option>
                                <option value="0">Incorrecta</option>
                            </select>
                        </div>
                    `;
                    container{{ $pregunta->id }}.insertAdjacentHTML('beforeend', nuevaRespuesta);
                    respuestaIndex{{ $pregunta->id }}++;
                });
            @endif
        @endforeach
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        @foreach ($preguntas as $pregunta)
            @if ($pregunta->tipo === 'abierta')
                const containerClave{{ $pregunta->id }} = document.getElementById(
                    'respuestas-clave-container-{{ $pregunta->id }}');
                const addButtonClave{{ $pregunta->id }} = document.getElementById(
                    'addRespuestaClaveButton-{{ $pregunta->id }}');
                let respuestaClaveIndex{{ $pregunta->id }} = 1;

                addButtonClave{{ $pregunta->id }}.addEventListener('click', function() {
                    const nuevaRespuestaClave = `
            <div class="respuesta-item mb-3">
                <div class="mb-3">
                    <label for="contenido" class="form-label">Contenido</label>
                    <input type="text" class="form-control" name="respuestas[${respuestaClaveIndex{{ $pregunta->id }} }][contenido]" required>
                </div>
                <hr>
            </div>
        `;
                    containerClave{{ $pregunta->id }}.insertAdjacentHTML('beforeend',
                        nuevaRespuestaClave);
                    respuestaClaveIndex{{ $pregunta->id }}++;
                });
            @endif
        @endforeach
    });
</script>
