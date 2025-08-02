@section('titulo')
    Crear Curso
@endsection




@section('content')

    <div class="container">
        <a href="{{ route('ListadeCursos') }}" class="btn btn-sm btn-primary my-3">
            &#9668; Volver
        </a>

        <form class="row g-3" action="{{ route('CrearCursoPost') }}" method="POST">
            @csrf
            <div class="col-12 border p-4 rounded">

                <h5 class="mb-3">Datos básicos del curso o evento</h5>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="descripcion" class="form-label">Descripción (Opcional)</label>
                        <input type="text" name="descripcion" value="{{ old('descripcion') }}" class="form-control">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="fecha_ini" class="form-label">Fecha Inicio <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_ini" value="{{ old('fecha_ini') }}" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label for="hora_ini" class="form-label">Hora Inicio</label>
                        <input type="time" name="hora_ini" value="{{ old('hora_ini') }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="fecha_fin" class="form-label">Fecha Fin <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_fin" value="{{ old('fecha_fin') }}" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label for="hora_fin" class="form-label">Hora Fin</label>
                        <input type="time" name="hora_fin" value="{{ old('hora_fin') }}" class="form-control">
                    </div>
                </div>
                <h5 class="mt-4 mb-3">Formato, tipo y docente</h5>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="formato" class="form-label">Formato</label>
                        <select name="formato" class="form-select">
                            <option value="Presencial" {{ old('formato') == 'Presencial' ? 'selected' : '' }}>Presencial
                            </option>
                            <option value="Virtual" {{ old('formato') == 'Virtual' ? 'selected' : '' }}>Virtual</option>
                            <option value="Híbrido" {{ old('formato') == 'Híbrido' ? 'selected' : '' }}>Híbrido</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="tipo" class="form-label">Tipo</label>
                        <select name="tipo" class="form-select">
                            <option value="curso" {{ old('tipo') == 'curso' ? 'selected' : '' }}>Curso</option>
                            <option value="congreso" {{ old('tipo') == 'congreso' ? 'selected' : '' }}>Evento</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="docente_id" class="form-label">Docente <span class="text-danger">*</span></label>
                        <div class="form-text mb-1">* Si no está registrado el docente, <a
                                href="{{ route('CrearDocente') }}">haz clic aquí</a></div>
                        <select name="docente_id" class="form-select">
                            @forelse ($docente as $docente)
                                <option value="{{ $docente->id }}"
                                    {{ old('docente_id') == $docente->id ? 'selected' : '' }}>
                                    {{ $docente->name }} {{ $docente->lastname1 }} {{ $docente->lastname2 }}
                                </option>
                            @empty
                                <option value="" disabled>NO HAY DOCENTES REGISTRADOS</option>
                            @endforelse
                        </select>
                    </div>
                </div>

                <h5 class="mt-4 mb-3">Edad y niveles</h5>

 <div class="row mb-3">
    <div class="col-md-6">
        <label for="edad_id" class="form-label">Edad estudiantes (rango aproximado)</label>
        <select id="edad_id" name="edad_id" class="form-select" onchange="actualizarNiveles()">
            <option value="">Seleccione un rango</option>
            <option value="3-5">3 a 5 años</option>
            <option value="6-8">6 a 8 años</option>
            <option value="9-12">9 a 12 años</option>
            <option value="13-15">13 a 15 años</option>
            <option value="16-18">16 a 18 años</option>
            <option value="18+">18 años o más</option>
        </select>
    </div>
    <div class="col-md-6">
        <label for="nivel_id" class="form-label">Nivel educativo</label>
        <select id="nivel_id" name="nivel_id" class="form-select">
            <option value="">Seleccione un nivel</option>
        </select>
    </div>
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
</script>


                <h5 class="mt-4 mb-3">Duración, Visibilidad, Cupos y Precio</h5>

                <div class="row mb-3">
                    <!-- Duración -->
                    <div class="col-md-3">
                        <label for="duracion" class="form-label">Duración (en horas) <span
                                class="text-danger">*</span></label>
                        <input type="number" name="duracion" id="duracion" class="form-control"
                            value="{{ old('duracion') }}" min="1" required>
                    </div>

                    <!-- Visibilidad -->
                    <div class="col-md-3">
                        <label for="visibilidad" class="form-label">Visibilidad</label>
                        <select name="visibilidad" id="visibilidad" class="form-select">
                            <option value="publico" {{ old('visibilidad') == 'publico' ? 'selected' : '' }}>Público
                            </option>
                            <option value="privado" {{ old('visibilidad') == 'privado' ? 'selected' : '' }}>Privado
                            </option>
                        </select>
                    </div>

                    <!-- Cupos -->
                    <div class="col-md-3">
                        <label for="cupos" class="form-label">Cupos Disponibles <span
                                class="text-danger">*</span></label>
                        <input type="number" name="cupos" id="cupos" class="form-control"
                            value="{{ old('cupos') }}" min="1" required>
                    </div>

                    <!-- Precio -->
                    <div class="col-md-3">
                        <label for="precio" class="form-label">Precio (en Bs) <span
                                class="text-danger">*</span></label>
                        <input type="number" name="precio" id="precio" class="form-control"
                            value="{{ old('precio') }}" step="0.01" min="0" required>
                    </div>
                </div>




                <!-- Horarios dinámicos (contenedor oculto por ahora) -->
                <div id="horarios-container" class="mb-3"></div>

                <div class="text-end">
                    <input class="btn btn-success" type="submit" value="Guardar">
                </div>
            </div>
        </form>
    </div>

    <!-- Validación de errores -->
    @if ($errors->any())
        <div class="container mt-3">
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger">{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <!-- Scripts -->
    <script>
        document.getElementById('add-horario')?.addEventListener('click', function() {
            const container = document.getElementById('horarios-container');
            const newHorario = container.firstElementChild.cloneNode(true);
            newHorario.querySelectorAll('input, select').forEach(input => input.value = '');
            container.appendChild(newHorario);
        });

        document.getElementById('horarios-container')?.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-horario')) {
                e.target.closest('.horario').remove();
            }
        });
    </script>

@endsection



@include('layout')
