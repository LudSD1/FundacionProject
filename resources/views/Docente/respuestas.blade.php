@section('titulo')
    Respuestas
@endsection




@section('content')
    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('Curso', encrypt($cuestionario->actividad->subtema->tema->curso_id)) }}" class="btn btn-sm btn-primary">
                &#9668; Volver
            </a>



        </div>




        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const preguntasContainer = document.getElementById('preguntas-container');
                const addPreguntaButton = document.getElementById('addPreguntaButton');

                let preguntaIndex = 1; // Índice para las preguntas dinámicas

                addPreguntaButton.addEventListener('click', function() {
                    const nuevaPregunta = `
            <div class="pregunta-item mb-3">
                <div class="mb-3">
                    <label for="preguntaTexto" class="form-label">Texto de la Pregunta</label>
                    <input type="text" class="form-control" name="preguntas[${preguntaIndex}][enunciado]" placeholder="Escribe la pregunta aquí" required>
                </div>
                <div class="mb-3">
                    <label for="preguntaTipo" class="form-label">Tipo de Pregunta</label>
                    <select class="form-select" name="preguntas[${preguntaIndex}][tipo]" required>
                        <option value="opcion_multiple">Opción Múltiple</option>
                        <option value="abierta">Respuesta Abierta</option>
                        <option value="boolean">Verdadero/Falso</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="puntosPregunta" class="form-label">Puntos</label>
                    <input type="number" class="form-control" name="preguntas[${preguntaIndex}][puntaje]" min="1" placeholder="Ejemplo: 5" required>
                </div>
                <button type="button" class="btn btn-sm btn-danger removePreguntaButton">Eliminar</button>
                <hr>
            </div>
        `;

                    preguntasContainer.insertAdjacentHTML('beforeend', nuevaPregunta);
                    preguntaIndex++;

                    // Agregar funcionalidad para eliminar preguntas dinámicas
                    const removeButtons = document.querySelectorAll('.removePreguntaButton');
                    removeButtons.forEach(button => {
                        button.addEventListener('click', function() {
                            this.parentElement.remove();
                        });
                    });
                });
            });
        </script>



        <div class="modal fade" id="crearRespuestaModal" tabindex="-1" aria-labelledby="crearRespuestaLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="">
                        @csrf
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="crearRespuestaLabel">Crear Respuesta</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="pregunta_id" id="pregunta_id" value="">

                            <div class="mb-3">
                                <label for="respuestaTexto" class="form-label">Texto de la Respuesta</label>
                                <input type="text" class="form-control" id="respuestaTexto" name="respuesta" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label d-block">¿Es correcta?</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="es_correcta" id="verdadero"
                                        value="1" required>
                                    <label class="form-check-label" for="verdadero">Verdadero</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="es_correcta" id="falso"
                                        value="0">
                                    <label class="form-check-label" for="falso">Falso</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="puntosRespuesta" class="form-label">Puntos (opcional)</label>
                                <input type="number" class="form-control" id="puntosRespuesta" name="puntos"
                                    min="0">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar Respuesta</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="mb-4">
            <h4 class="border-bottom pb-2">
                <i class="fas fa-question-circle me-2"></i> Preguntas y Respuestas
            </h4>

            <!-- Navegación de las pestañas -->
            <ul class="nav nav-tabs" id="preguntasRespuestasTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="preguntas-tab" data-bs-toggle="tab" data-bs-target="#preguntas"
                        type="button" role="tab" aria-controls="preguntas" aria-selected="true">
                        <i class="fas fa-question me-1"></i> Preguntas
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="respuestas-tab" data-bs-toggle="tab" data-bs-target="#respuestas"
                        type="button" role="tab" aria-controls="respuestas" aria-selected="false">
                        <i class="fas fa-reply me-1"></i> Respuestas
                    </button>
                </li>
            </ul>

            <!-- Contenido de las pestañas -->
            <div class="tab-content mt-3" id="preguntasRespuestasContent">
                <!-- Pestaña de Preguntas -->
                <div class="tab-pane fade show active" id="preguntas" role="tabpanel" aria-labelledby="preguntas-tab">
                    @include('partials.preguntas', ['preguntas' => $cuestionario->preguntas])
                </div>

                <!-- Pestaña de Respuestas -->
                <div class="tab-pane fade" id="respuestas" role="tabpanel" aria-labelledby="respuestas-tab">
                    @include('partials.respuestas', ['preguntas' => $cuestionario->preguntas])
                </div>
            </div>
        </div>


    </div>
@endsection



<script>
    document.addEventListener('DOMContentLoaded', function () {
    const buscador = document.getElementById('buscador');
    const tablaPreguntas = document.getElementById('tabla-preguntas');

    buscador.addEventListener('input', function () {
        const filtro = buscador.value.toLowerCase();
        const filas = tablaPreguntas.getElementsByTagName('tr');

        Array.from(filas).forEach(fila => {
            const columnas = fila.getElementsByTagName('td');
            const textoFila = Array.from(columnas).map(columna => columna.textContent.toLowerCase()).join(' ');
            fila.style.display = textoFila.includes(filtro) ? '' : 'none';
        });
    });
});
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Selecciona todos los formularios con la clase 'form-eliminar'
        const formsEliminar = document.querySelectorAll('.form-eliminar');
        // Selecciona todos los formularios con la clase 'form-restaurar'
        const formsRestaurar = document.querySelectorAll('.form-restaurar');

        // Función para manejar la confirmación de eliminación
        formsEliminar.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Evita el envío automático del formulario

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esta acción!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si el usuario confirma, envía el formulario
                        form.submit();
                    }
                });
            });
        });

        // Función para manejar la confirmación de restauración
        formsRestaurar.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Evita el envío automático del formulario

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¿Quieres restaurar esta pregunta?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745', // Verde para restaurar
                    cancelButtonColor: '#6c757d', // Gris para cancelar
                    confirmButtonText: 'Sí, restaurar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si el usuario confirma, envía el formulario
                        form.submit();
                    }
                });
            });
        });
    });
</script>



@include('layout')
