




@section('content')
<div class="border p-3">
<a href="javascript:history.back()" class="btn btn-primary">
    &#9668; Volver
</a>
<br>

<div class="recursoscrear-container">
<div class="title">

                <h2>Modificar Recurso </h2>
            </div>

        <div class="form">
            <form method="POST" enctype="multipart/form-data" action="{{route('editarRecursosPost', encrypt($recurso->id))}}">
                @csrf
                <input type="text" value="{{$recurso->cursos_id}}" name="cursos_id" hidden>
                <input type="text" value="{{$recurso->id}}" name="idRecurso" hidden>
                <div class="form-group">
                    <label for="tituloRecurso" class="form-label">Título del Recurso:</label>
                    <input type="text" id="fileTitle" name="tituloRecurso" class="form-input" value="{{$recurso->nombreRecurso}}" required>
                </div>
                <div class="form-group">
                    <label for="fileDescription" class="form-label">Descripción del Recurso:</label>
                    <textarea id="fileDescription" name="descripcionRecurso" rows="4" class="form-input"  required>{{$recurso->descripcionRecursos}}</textarea>
                </div>
                <div class="form-group">
                    <label for="fileUpload" class="form-label">Seleccionar Archivo:</label>
                    <input type="file" id="fileUpload" name="archivo" class="form-input" >
                </div>


                <div class="icon-gallery">
                    <!-- Documentos -->
                    <div class="icon-category mb-4">
                        <h5 class="category-title mb-3 border-bottom pb-2">Documentos</h5>
                        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3">
                            <div class="col">
                                <div class="icon-option text-center p-2 rounded hover-shadow">
                                    <img src="{{asset('resources/icons/word.png')}}" alt="Word" data-value="word" height="50px" class="img-fluid mb-2">
                                    <p class="mb-0 small">Word</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="icon-option text-center p-2 rounded hover-shadow">
                                    <img src="{{asset('resources/icons/excel.png')}}" alt="Excel" data-value="excel" height="50px" class="img-fluid mb-2">
                                    <p class="mb-0 small">Excel</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="icon-option text-center p-2 rounded hover-shadow">
                                    <img src="{{asset('resources/icons/powerpoint.png')}}" alt="PowerPoint" data-value="powerpoint" height="50px" class="img-fluid mb-2">
                                    <p class="mb-0 small">PowerPoint</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="icon-option text-center p-2 rounded hover-shadow">
                                    <img src="{{asset('resources/icons/pdf.png')}}" alt="PDF" data-value="pdf" height="50px" class="img-fluid mb-2">
                                    <p class="mb-0 small">PDF</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="icon-option text-center p-2 rounded hover-shadow">
                                    <img src="{{asset('resources/icons/doc.png')}}" alt="Docs" data-value="docs" height="50px" class="img-fluid mb-2">
                                    <p class="mb-0 small">Docs</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Google Workspace -->
                    <div class="icon-category mb-4">
                        <h5 class="category-title mb-3 border-bottom pb-2">Google Workspace</h5>
                        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3">
                            <div class="col">
                                <div class="icon-option text-center p-2 rounded hover-shadow">
                                    <img src="{{asset('resources/icons/drive.png')}}" alt="Drive" data-value="drive" height="50px" class="img-fluid mb-2">
                                    <p class="mb-0 small">Drive</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="icon-option text-center p-2 rounded hover-shadow">
                                    <img src="{{asset('resources/icons/forms.png')}}" alt="Forms" data-value="forms" height="50px" class="img-fluid mb-2">
                                    <p class="mb-0 small">Forms</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Multimedia -->
                    <div class="icon-category mb-4">
                        <h5 class="category-title mb-3 border-bottom pb-2">Multimedia</h5>
                        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3">
                            <div class="col">
                                <div class="icon-option text-center p-2 rounded hover-shadow">
                                    <img src="{{asset('resources/icons/imagen.png')}}" alt="Imagen" data-value="imagen" height="50px" class="img-fluid mb-2">
                                    <p class="mb-0 small">Imagen</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="icon-option text-center p-2 rounded hover-shadow">
                                    <img src="{{asset('resources/icons/video.png')}}" alt="Video" data-value="video" height="50px" class="img-fluid mb-2">
                                    <p class="mb-0 small">Video</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="icon-option text-center p-2 rounded hover-shadow">
                                    <img src="{{asset('resources/icons/audio.png')}}" alt="Audio" data-value="audio" height="50px" class="img-fluid mb-2">
                                    <p class="mb-0 small">Audio</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="icon-option text-center p-2 rounded hover-shadow">
                                    <img src="{{asset('resources/icons/youtube.png')}}" alt="YouTube" data-value="youtube" height="50px" class="img-fluid mb-2">
                                    <p class="mb-0 small">YouTube</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Videoconferencia -->
                    <div class="icon-category mb-4">
                        <h5 class="category-title mb-3 border-bottom pb-2">Videoconferencia</h5>
                        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3">
                            <div class="col">
                                <div class="icon-option text-center p-2 rounded hover-shadow">
                                    <img src="{{asset('resources/icons/zoom.png')}}" alt="Zoom" data-value="zoom" height="50px" class="img-fluid mb-2">
                                    <p class="mb-0 small">Zoom</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="icon-option text-center p-2 rounded hover-shadow">
                                    <img src="{{asset('resources/icons/meet.png')}}" alt="Meet" data-value="meet" height="50px" class="img-fluid mb-2">
                                    <p class="mb-0 small">Meet</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="icon-option text-center p-2 rounded hover-shadow">
                                    <img src="{{asset('resources/icons/teams.png')}}" alt="Teams" data-value="teams" height="50px" class="img-fluid mb-2">
                                    <p class="mb-0 small">Teams</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Herramientas Educativas -->
                    <div class="icon-category mb-4">
                        <h5 class="category-title mb-3 border-bottom pb-2">Herramientas Educativas</h5>
                        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3">
                            <div class="col">
                                <div class="icon-option text-center p-2 rounded hover-shadow">
                                    <img src="{{asset('resources/icons/kahoot.png')}}" alt="Kahoot" data-value="kahoot" height="50px" class="img-fluid mb-2">
                                    <p class="mb-0 small">Kahoot</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="icon-option text-center p-2 rounded hover-shadow">
                                    <img src="{{asset('resources/icons/canva.png')}}" alt="Canva" data-value="canva" height="50px" class="img-fluid mb-2">
                                    <p class="mb-0 small">Canva</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Otros -->
                    <div class="icon-category mb-4">
                        <h5 class="category-title mb-3 border-bottom pb-2">Otros</h5>
                        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3">
                            <div class="col">
                                <div class="icon-option text-center p-2 rounded hover-shadow">
                                    <img src="{{asset('resources/icons/enlace.png')}}" alt="Enlace" data-value="enlace" height="50px" class="img-fluid mb-2">
                                    <p class="mb-0 small">Enlace</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="icon-option text-center p-2 rounded hover-shadow">
                                    <img src="{{asset('resources/icons/archivos-adjuntos.png')}}" alt="Archivos" data-value="archivos-adjuntos" height="50px" class="img-fluid mb-2">
                                    <p class="mb-0 small">Archivos</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <style>
                    .icon-gallery {
                        padding: 20px;
                    }
                    .category-title {
                        color: #3f51b5;
                        font-weight: 600;
                    }
                    .icon-option {
                        transition: all 0.3s ease;
                        cursor: pointer;
                    }
                    .icon-option:hover {
                        background-color: #f5f5f5;
                        transform: translateY(-3px);
                    }
                    .hover-shadow {
                        transition: box-shadow 0.3s ease;
                    }
                    .hover-shadow:hover {
                        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                    }
                </style>
                <input id="input-seleccionado" type="text" name="tipoRecurso" value="" hidden >
                 <p id="icono-seleccionado">Seleccionado: Ninguno</p>

            <script>
                const iconOptions = document.querySelectorAll('.icon-option');
                const iconoSeleccionado = document.getElementById('icono-seleccionado');
                const inputSeleccionado = document.getElementById('input-seleccionado');
                iconOptions.forEach((option) => {
                    option.addEventListener('click', () => {
                        const valorSeleccionado = option.querySelector('img').getAttribute('data-value');
                        iconoSeleccionado.textContent = `Seleccionado: ${valorSeleccionado}`;
                        inputSeleccionado.value = valorSeleccionado;

                    });
                });
            </script>



                <button type="submit" class="form-button">Guardar Cambios</button>
            </form>
        </div>


    </div>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<style>
        .recursoscrear-container {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            margin: 20px;
        }

        .title {
            text-align: left;
        }

        .task-form {
            width: 100%;
            max-width: 400px;
            text-align: left;
            margin-top: 20px;
        }

    .task-form h2 {
        margin-bottom: 20px;
        font-size: 24px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }

    input[type="text"],
    textarea,
    input[type="date"],
    input[type="number"],
    input[type="file"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }

    input[type="checkbox"] {
        margin-top: 5px;
    }

    button {
        background-color: #007BFF;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        font-size: 18px;
        cursor: pointer;
    }

    button:hover {
        background-color: #0056b3;
    }
</style>


</div>
@endsection

@include('layout')

