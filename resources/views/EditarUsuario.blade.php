@section('titulo')

Editar Perfil
@endsection




@section('content')
<div class="container">
    <div class="border p-3">
        <!-- Botón de Volver -->
        <a href="javascript:history.back()" class="btn btn-primary mb-4">
            &#9668; Volver
        </a>

        <!-- Formulario -->
        <form action="{{ route('EditarperfilUser', encrypt($usuario->id)) }}" method="POST">
            @csrf
            <h4 class="mb-4">Datos Personales de {{ $usuario->name }} {{ $usuario->lastname1 }} {{ $usuario->lastname1 }}</h4>

            <!-- Nombre -->
            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" value="{{ $usuario->name }}" name="name">
            </div>

            <!-- Apellidos -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="lastname1" class="form-label">Apellido Paterno <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" value="{{ $usuario->lastname1 }}" name="lastname1">
                </div>
                <div class="col-md-6">
                    <label for="lastname2" class="form-label">Apellido Materno <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" value="{{ $usuario->lastname2 }}" name="lastname2">
                </div>
            </div>

            <!-- Celular y Correo (si no es tutor) -->
            @if (!$usuario->tutor)
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="cel" class="form-label">Celular</label>
                        <input type="text" class="form-control" value="{{ $usuario->Celular }}" name="Celular">
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="text" class="form-control" value="{{ $usuario->email }}" name="email">
                    </div>
                </div>
            @endif

            <!-- Fecha de Nacimiento y CI -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="fechadenac" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" value="{{ $usuario->fechadenac }}" name="fechadenac">
                </div>
                <div class="col-md-6">
                    <label for="CI" class="form-label">Cédula de Identidad</label>
                    <input type="text" class="form-control" value="{{ $usuario->CI }}" name="CI">
                </div>
            </div>

            <!-- País y Ciudad -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="PaisReside" class="form-label">País</label>
                    <input type="text" class="form-control" value="{{ $usuario->PaisReside }}" name="PaisReside">
                </div>
                <div class="col-md-6">
                    <label for="CiudadReside" class="form-label">Ciudad</label>
                    <input type="text" class="form-control" value="{{ $usuario->CiudadReside }}" name="CiudadReside">
                </div>
            </div>

            <!-- Datos del Tutor (si es tutor) -->
            @if ($usuario->tutor)
                <hr>
                <h4 class="mb-4">Datos Personales del Tutor</h4>

                <!-- Nombre Completo del Tutor -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="nombreT" class="form-label">Nombre</label>
                        <input type="text" class="form-control" placeholder="Nombre" name="nombreT" value="{{ $usuario->tutor->nombreTutor ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label for="appT" class="form-label">Apellido Paterno</label>
                        <input type="text" class="form-control" placeholder="Apellido Paterno" name="appT" value="{{ $usuario->tutor->appaternoTutor ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label for="apmT" class="form-label">Apellido Materno</label>
                        <input type="text" class="form-control" placeholder="Apellido Materno" name="apmT" value="{{ $usuario->tutor->apmaternoTutor ?? '' }}">
                    </div>
                </div>

                <!-- CI del Tutor -->
                <div class="mb-3">
                    <label for="CIT" class="form-label">Cédula de Identidad del Tutor</label>
                    <input type="text" class="form-control" placeholder="Cédula de Identidad" name="CIT" value="{{ $usuario->tutor->CI ?? '' }}">
                </div>

                <!-- Dirección del Tutor -->
                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" class="form-control" placeholder="Dirección" name="direccion" value="{{ $usuario->tutor->Direccion ?? '' }}">
                </div>

                <!-- Celular y Correo del Tutor -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="Celular" class="form-label">Celular</label>
                        <input type="text" class="form-control" value="{{ $usuario->Celular }}" name="Celular">
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="text" class="form-control" value="{{ $usuario->email }}" name="email">
                    </div>
                </div>
            @endif

            <!-- Datos del Docente (si es docente) -->
            @if (auth()->user()->hasRole('Docente'))
                <hr>
                <h4 class="mb-4">Datos Profesionales</h4>

                @foreach ($atributosD as $atributosD)
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="formacion" class="form-label">Formación Académica</label>
                            <input type="text" class="form-control" placeholder="Formación Académica" name="formacion" value="{{ $atributosD->formacion ?? '' }}">
                        </div>
                        <div class="col-md-4">
                            <label for="Especializacion" class="form-label">Experiencia Laboral</label>
                            <input type="text" class="form-control" placeholder="Experiencia Laboral" name="Especializacion" value="{{ $atributosD->Especializacion ?? '' }}">
                        </div>
                        <div class="col-md-4">
                            <label for="ExperienciaL" class="form-label">Especialización</label>
                            <input type="text" class="form-control" placeholder="Especialización" name="ExperienciaL" value="{{ $atributosD->ExperienciaL ?? '' }}">
                        </div>
                    </div>
                @endforeach
            @endif

            <!-- Botón de Guardar Cambios -->
            <div class="mt-4">
                <button type="submit" class="btn btn-warning">Guardar Cambios</button>
            </div>
        </form>

        <!-- Mensajes de Error -->
        @if ($errors->any())
            <div class="alert alert-danger mt-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Mensaje de Éxito -->
        @if(session('success'))
            <div class="alert alert-success mt-4">
                {{ session('success') }}
            </div>
        @endif
    </div>
</div>

<!-- Script para alternar la visibilidad de la contraseña -->
<script>
    document.getElementById("togglePassword").addEventListener("click", function() {
        var passwordInput = document.getElementById("password");
        var eyeIcon = document.getElementById("eyeIcon");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password";
            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye");
        }
    });
</script>
@endsection


@include('layout')
