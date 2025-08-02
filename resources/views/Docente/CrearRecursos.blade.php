

<style>
    .icon-gallery {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: center;
        margin: 20px 0;
    }

    .icon-option {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100px;
        padding: 10px;
        border: 2px solid transparent;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .icon-option:hover {
        background-color: #f0f0f0;
    }

    .icon-option.selected {
        border-color: #007bff;
        background-color: #e7f1ff;
    }

    .icon-option img {
        width: 50px;
        height: 50px;
        object-fit: contain;
        margin-bottom: 8px;
    }

    .icon-option p {
        margin: 0;
        font-size: 0.8rem;
        text-align: center;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-input,
    .form-button {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .form-button {
        background-color: #007bff;
        color: white;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .form-button:hover {
        background-color: #0056b3;
    }
</style>
@section('content')
    <div class="border p-3">
        <a href="{{ route('Curso', ['id' => encrypt($curso->id)]) }}" class="btn btn-primary">
            &#9668; Volver
        </a>
        <br>

        <div class="container">
            <form id="resourceForm" action="{{route('CrearRecursosPost', ['id' => encrypt($curso->id)])}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="fileTitle">Título del Recurso:</label>
                    <input type="text" id="fileTitle" name="tituloRecurso" class="form-input" required minlength="3"
                        maxlength="100">
                </div>

                <div class="form-group">
                    <label for="fileDescription">Descripción del Recurso:</label>
                    <textarea id="fileDescription" name="descripcionRecurso" class="form-input" rows="4" required minlength="10"
                        maxlength="500"></textarea>
                </div>

                <div class="form-group">
                    <label for="fileUpload">Seleccionar Archivo:</label>
                    <input type="file" id="fileUpload" name="archivo" class="form-input" >
                </div>

                <h3>Elige el tipo de Recurso</h3>

                <!-- Replace the icon gallery with a select dropdown -->
                <select id="resourceSelect" name="tipoRecurso">
                    <option value="" disabled selected>Selecciona un recurso</option>
                    <option value="word">Word</option>
                    <option value="excel">Excel</option>
                    <option value="powerpoint">PowerPoint</option>
                    <option value="pdf">PDF</option>
                    <option value="archivos-adjuntos">Archivos Adjuntos</option>
                    <option value="docs">Docs</option>
                    <option value="forms">Forms</option>
                    <option value="drive">Drive</option>
                    <option value="youtube">YouTube</option>
                    <option value="kahoot">Kahoot</option>
                    <option value="canva">Canva</option>
                    <option value="zoom">Zoom</option>
                    <option value="meet">Meet</option>
                    <option value="teams">Teams</option>
                    <option value="enlace">Enlace</option>
                    <option value="imagen">Imagen</option>
                    <option value="video">Video</option>
                    <option value="audio">Audio</option>
                </select>

                <p id="selected-icon">Seleccionado: Ninguno</p>

                <button type="submit" class="form-button">Guardar Recurso</button>
            </form>
        </div>


        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const resourceSelect = document.getElementById('resourceSelect');
                const selectedIcon = document.getElementById('selected-icon');

                // Update the text when a new option is selected
                resourceSelect.addEventListener('change', function() {
                    const selectedValue = this.value;
                    const selectedOption = this.options[this.selectedIndex];

                    // Update the selection text
                    selectedIcon.textContent = `Seleccionado: ${selectedOption.textContent}`;

                    // Debugging
                    console.log('Selected Resource Type:', selectedValue);
                });

                // Validation on form submission
                const form = document.querySelector('form');
                if (form) {
                    form.addEventListener('submit', function(event) {
                        // Check if the user has selected a resource
                        if (!resourceSelect.value) {
                            event.preventDefault();
                            alert('Por favor, seleccione un tipo de recurso.');
                        } else {
                            console.log('Formulario enviado con tipo de recurso:', resourceSelect.value);
                        }
                    });
                }
            });
        </script>



        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


    </div>
@endsection

@include('layout')
