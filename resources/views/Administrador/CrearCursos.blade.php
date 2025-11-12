@section('titulo')
    Crear Curso
@endsection




@section('content')

    <div class="container form-container-modern">
        <!-- Botón Volver -->
        <div class="back-button-wrapper">
            <a href="{{ route('ListadeCursos') }}" class="btn-back-modern">
                <i class="bi bi-arrow-left-circle-fill me-2"></i>
                <span>Volver al Listado</span>
            </a>
        </div>

        <!-- Formulario Principal -->
        <form class="form-modern" action="{{ route('CrearCursoPost') }}" method="POST">
            @csrf

            <div class="form-card-modern">
                <!-- Header del Formulario -->
                <div class="form-header-modern">
                    <div class="form-title-wrapper">
                        <i class="bi bi-plus-circle-fill form-icon-main"></i>
                        <h3 class="form-title-main">Crear Nuevo Curso o Evento</h3>
                    </div>
                    <p class="form-subtitle-main">Complete los siguientes datos para registrar un curso o evento</p>
                </div>

                <!-- Sección 1: Datos Básicos -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon-wrapper">
                            <i class="bi bi-info-circle-fill"></i>
                        </div>
                        <h5 class="section-title">Datos Básicos del Curso o Evento</h5>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="nombre" class="form-label-modern">
                                    <i class="bi bi-bookmark-fill label-icon"></i>
                                    Nombre del Curso
                                    <span class="required-badge">*</span>
                                </label>
                                <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}"
                                    class="form-control-modern" placeholder="Ej: Introducción a la Programación" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="descripcion" class="form-label-modern">
                                    <i class="bi bi-text-paragraph label-icon"></i>
                                    Descripción
                                    <span class="optional-badge">Opcional</span>
                                </label>
                                <input type="text" name="descripcion" id="descripcion" value="{{ old('descripcion') }}"
                                    class="form-control-modern" placeholder="Breve descripción del curso">
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mt-2">
                        <div class="col-md-3">
                            <div class="form-group-modern">
                                <label for="fecha_ini" class="form-label-modern">
                                    <i class="bi bi-calendar-check label-icon"></i>
                                    Fecha Inicio
                                    <span class="required-badge">*</span>
                                </label>
                                <input type="date" name="fecha_ini" id="fecha_ini" value="{{ old('fecha_ini') }}"
                                    class="form-control-modern" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-modern">
                                <label for="hora_ini" class="form-label-modern">
                                    <i class="bi bi-clock label-icon"></i>
                                    Hora Inicio
                                </label>
                                <input type="time" name="hora_ini" id="hora_ini" value="{{ old('hora_ini') }}"
                                    class="form-control-modern">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-modern">
                                <label for="fecha_fin" class="form-label-modern">
                                    <i class="bi bi-calendar-x label-icon"></i>
                                    Fecha Fin
                                    <span class="required-badge">*</span>
                                </label>
                                <input type="date" name="fecha_fin" id="fecha_fin" value="{{ old('fecha_fin') }}"
                                    class="form-control-modern" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-modern">
                                <label for="hora_fin" class="form-label-modern">
                                    <i class="bi bi-clock-fill label-icon"></i>
                                    Hora Fin
                                </label>
                                <input type="time" name="hora_fin" id="hora_fin" value="{{ old('hora_fin') }}"
                                    class="form-control-modern">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección 2: Formato, Tipo y Docente -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon-wrapper">
                            <i class="bi bi-gear-fill"></i>
                        </div>
                        <h5 class="section-title">Formato, Tipo y Docente</h5>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="form-group-modern">
                                <label for="formato" class="form-label-modern">
                                    <i class="bi bi-laptop label-icon"></i>
                                    Formato
                                </label>
                                <select name="formato" id="formato" class="form-select-modern">
                                    <option value="Presencial" {{ old('formato') == 'Presencial' ? 'selected' : '' }}>
                                        🏢 Presencial
                                    </option>
                                    <option value="Virtual" {{ old('formato') == 'Virtual' ? 'selected' : '' }}>
                                        💻 Virtual
                                    </option>
                                    <option value="Híbrido" {{ old('formato') == 'Híbrido' ? 'selected' : '' }}>
                                        🔄 Híbrido
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-modern">
                                <label for="tipo" class="form-label-modern">
                                    <i class="bi bi-tags-fill label-icon"></i>
                                    Tipo
                                </label>
                                <select name="tipo" id="tipo" class="form-select-modern">
                                    <option value="curso" {{ old('tipo') == 'curso' ? 'selected' : '' }}>
                                        📚 Curso
                                    </option>
                                    <option value="congreso" {{ old('tipo') == 'congreso' ? 'selected' : '' }}>
                                        🎉 Evento
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-modern">
                                <label for="docente_id" class="form-label-modern">
                                    <i class="bi bi-person-badge label-icon"></i>
                                    Docente
                                    <span class="required-badge">*</span>
                                </label>
                                <div class="helper-text-modern mb-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Si no está registrado,
                                    <a href="{{ route('CrearDocente') }}" class="helper-link">crear docente aquí</a>
                                </div>
                                <select name="docente_id" id="docente_id" class="form-select-modern" required>
                                    @forelse ($docente as $doc)
                                        <option value="{{ $doc->id }}"
                                            {{ old('docente_id') == $doc->id ? 'selected' : '' }}>
                                            {{ $doc->name }} {{ $doc->lastname1 }} {{ $doc->lastname2 }}
                                        </option>
                                    @empty
                                        <option value="" disabled selected>NO HAY DOCENTES REGISTRADOS</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección 3: Edad y Niveles -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon-wrapper">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h5 class="section-title">Edad y Niveles Educativos</h5>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="edad_id" class="form-label-modern">
                                    <i class="bi bi-person-check label-icon"></i>
                                    Edad Estudiantes (rango aproximado)
                                </label>
                                <select id="edad_id" name="edad_id" class="form-select-modern"
                                    onchange="actualizarNiveles()">
                                    <option value="">Seleccione un rango</option>
                                    <option value="3-5">👶 3 a 5 años</option>
                                    <option value="6-8">🧒 6 a 8 años</option>
                                    <option value="9-12">👦 9 a 12 años</option>
                                    <option value="13-15">👨 13 a 15 años</option>
                                    <option value="16-18">🎓 16 a 18 años</option>
                                    <option value="18+">👔 18 años o más</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="nivel_id" class="form-label-modern">
                                    <i class="bi bi-bar-chart-fill label-icon"></i>
                                    Nivel Educativo
                                </label>
                                <select id="nivel_id" name="nivel_id" class="form-select-modern">
                                    <option value="">Seleccione un nivel</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección 4: Duración, Visibilidad, Cupos y Precio -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon-wrapper">
                            <i class="bi bi-card-checklist"></i>
                        </div>
                        <h5 class="section-title">Duración, Visibilidad, Cupos y Precio</h5>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-3">
                            <div class="form-group-modern">
                                <label for="duracion" class="form-label-modern">
                                    <i class="bi bi-hourglass-split label-icon"></i>
                                    Duración (horas)
                                    <span class="required-badge">*</span>
                                </label>
                                <input type="number" name="duracion" id="duracion" class="form-control-modern"
                                    value="{{ old('duracion') }}" min="1" placeholder="Ej: 40" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-modern">
                                <label for="visibilidad" class="form-label-modern">
                                    <i class="bi bi-eye-fill label-icon"></i>
                                    Visibilidad
                                </label>
                                <select name="visibilidad" id="visibilidad" class="form-select-modern">
                                    <option value="publico" {{ old('visibilidad') == 'publico' ? 'selected' : '' }}>
                                        🌐 Público
                                    </option>
                                    <option value="privado" {{ old('visibilidad') == 'privado' ? 'selected' : '' }}>
                                        🔒 Privado
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-modern">
                                <label for="cupos" class="form-label-modern">
                                    <i class="bi bi-people label-icon"></i>
                                    Cupos Disponibles
                                    <span class="required-badge">*</span>
                                </label>
                                <input type="number" name="cupos" id="cupos" class="form-control-modern"
                                    value="{{ old('cupos') }}" min="1" placeholder="Ej: 30" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-modern">
                                <label for="precio" class="form-label-modern">
                                    <i class="bi bi-currency-dollar label-icon"></i>
                                    Precio (Bs)
                                    <span class="required-badge">*</span>
                                </label>
                                <input type="number" name="precio" id="precio" class="form-control-modern"
                                    value="{{ old('precio') }}" step="0.01" min="0" placeholder="Ej: 250.00"
                                    required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="form-actions d-flex justify-content-end gap-3 mt-4 flex-wrap">
                    <button type="reset" class="btn-action-modern btn-reset">
                        <i class="bi bi-arrow-counterclockwise me-2"></i>
                        Limpiar Formulario
                    </button>
                    <button type="submit" class="btn-action-modern btn-submit">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Guardar Curso
                    </button>
                </div>

            </div>
        </form>

        <!-- Mensajes de Error -->
        @if ($errors->any())
            <div class="error-container-modern">
                @foreach ($errors->all() as $error)
                    <div class="alert-modern alert-error">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ $error }}
                    </div>
                @endforeach
            </div>
        @endif
    </div>



    <script>
        const nivelesPorEdad = {
            "3-5": ["Preescolar"],
            "6-8": ["Primaria"],
            "9-12": ["Primaria", "Secundaria"],
            "13-15": ["Secundaria", "Media"],
            "16-18": ["Media"],
            "18+": ["Superior"]
        };

        function actualizarNiveles() {
            const edadSeleccionada = document.getElementById("edad_id").value;
            const nivelSelect = document.getElementById("nivel_id");

            // Limpiar opciones anteriores
            nivelSelect.innerHTML = '<option value="">Seleccione un nivel</option>';

            // Agregar nuevas opciones
            if (edadSeleccionada && nivelesPorEdad[edadSeleccionada]) {
                nivelesPorEdad[edadSeleccionada].forEach(nivel => {
                    const option = document.createElement("option");
                    option.value = nivel.toLowerCase();
                    option.text = nivel;
                    nivelSelect.appendChild(option);
                });
            }
        }

        // Animación de enfoque suave en inputs
        document.querySelectorAll('.form-control-modern, .form-select-modern').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });

            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });
        });
    </script>

@endsection



@include('layout')
