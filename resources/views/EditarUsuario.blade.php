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
                <ul class="nav nav-tabs" id="profileTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab" aria-controls="personal" aria-selected="true">
                    <i class="fas fa-user"></i> Datos Personales
                </button>
            </li>
            @hasrole('Administrador')
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="role-tab" data-bs-toggle="tab" data-bs-target="#role" type="button" role="tab" aria-controls="role" aria-selected="false">
                    <i class="fas fa-user-cog"></i> Cambiar Rol
                </button>
            </li>
            @endhasrole
        </ul>



        <div class="container">
    <div class="border p-3">
        <div class="tab-content" id="profileTabContent">
            <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                <div class="mt-3">
                    <form action="{{ route('EditarperfilUser', encrypt($usuario->id)) }}" method="POST">
                        @csrf
                        <h4 class="mb-4">Datos Personales de {{ $usuario->name }} {{ $usuario->lastname1 }} {{ $usuario->lastname2 }}</h4>

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
                                <input type="text" class="form-control" value="{{ $usuario->CI }}" name="ci">
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
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Pestaña de Cambiar Rol -->
            @hasrole('Administrador')

            <div class="tab-pane fade" id="role" role="tabpanel" aria-labelledby="role-tab">
                <div class="mt-3">
                    {{-- {{ route('CambiarRolUser', encrypt($usuario->id)) }} --}}
                    <form action="{{ route('CambiarRolUser', encrypt($usuario->id)) }}" method="POST">
                        @csrf
                        <h4 class="mb-4">Cambiar Rol de Usuario</h4>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Usuario actual:</strong> {{ $usuario->name }} {{ $usuario->lastname1 }} {{ $usuario->lastname2 }}
                            <br>
                            <strong>Rol actual:</strong>
                            @if($usuario->getRoleNames()->isNotEmpty())
                                <span class="badge bg-primary">{{ $usuario->getRoleNames()->first() }}</span>
                            @else
                                <span class="badge bg-secondary">Sin rol asignado</span>
                            @endif
                        </div>

                        <!-- Selección de Nuevo Rol -->
                        <div class="mb-4">
                            <label for="nuevo_rol" class="form-label">Seleccionar Nuevo Rol <span class="text-danger">*</span></label>
                            <select class="form-select" name="nuevo_rol" id="nuevo_rol" required>
                                <option value="">-- Seleccione un rol --</option>
                                <option value="Estudiante">Estudiante</option>
                                <option value="Docente">Docente</option>
                                <option value="Administrador">Administrador</option>
                            </select>
                        </div>

                        <!-- Descripción de Roles -->
                        <div class="mb-4">
                            <h5>Descripción de Roles:</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card mb-2">
                                        <div class="card-body p-3">
                                            <h6 class="card-title text-primary">
                                                <i class="fas fa-user-graduate"></i> Estudiante
                                            </h6>
                                            <p class="card-text small">Acceso a cursos, materiales de estudio y evaluaciones.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card mb-2">
                                        <div class="card-body p-3">
                                            <h6 class="card-title text-success">
                                                <i class="fas fa-chalkboard-teacher"></i> Docente
                                            </h6>
                                            <p class="card-text small">Puede crear cursos, gestionar estudiantes y calificar.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card mb-2">
                                        <div class="card-body p-3">
                                            <h6 class="card-title text-danger">
                                                <i class="fas fa-user-shield"></i> Administrador
                                            </h6>
                                            <p class="card-text small">Control total del sistema, gestión de usuarios y configuraciones.</p>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <div class="card mb-2">
                                        <div class="card-body p-3">
                                            <h6 class="card-title text-warning">
                                                <i class="fas fa-user-friends"></i> Tutor
                                            </h6>
                                            <p class="card-text small">Supervisión de estudiantes menores de edad.</p>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                        </div>

                        <!-- Confirmación -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="confirmar_cambio" name="confirmar_cambio" required>
                                <label class="form-check-label" for="confirmar_cambio">
                                    Confirmo que deseo cambiar el rol de este usuario
                                </label>
                            </div>
                        </div>

                        <!-- Botón de Cambiar Rol -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-danger" id="btn-cambiar-rol" disabled>
                                <i class="fas fa-exchange-alt"></i> Cambiar Rol
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endrole
        </div>



    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Habilitar/deshabilitar botón según checkbox
    const confirmarCheckbox = document.getElementById('confirmar_cambio');
    const btnCambiarRol = document.getElementById('btn-cambiar-rol');

    if (confirmarCheckbox && btnCambiarRol) {
        confirmarCheckbox.addEventListener('change', function() {
            btnCambiarRol.disabled = !this.checked;
        });
    }

    // Prevenir envío accidental
    const formCambiarRol = document.querySelector('form[action*="CambiarRolUser"]');
    if (formCambiarRol) {
        formCambiarRol.addEventListener('submit', function(e) {
            const nuevoRol = document.getElementById('nuevo_rol').value;
            if (!nuevoRol) {
                e.preventDefault();
                alert('Por favor seleccione un rol.');
                return;
            }

            if (!confirm(`¿Está seguro de que desea cambiar el rol a "${nuevoRol}"?`)) {
                e.preventDefault();
            }
        });
    }
});
</script>

@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Error de validación',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            confirmButtonText: 'Entendido'
        });
    });
</script>
@endif

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            confirmButtonText: 'Entendido'
        });
    });
</script>
@endif

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
