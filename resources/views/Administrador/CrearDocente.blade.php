@extends('layout')

@section('titulo')
    Crear Docente
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('ListaDocentes') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i> Volver
                </a>
                <h5 class="mb-0">Crear Nuevo Docente</h5>
            </div>
        </div>

        <div class="card-body">
            @if (auth()->user()->hasRole('Administrador'))
                <form method="post" action="{{ route('CrearDocentePost') }}" class="needs-validation" novalidate>
                    @csrf

                    <div class="row g-3">
                        <!-- Nombre -->
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nombre Docente <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                            <div class="invalid-feedback">
                                Por favor ingrese el nombre del docente.
                            </div>
                        </div>

                        <!-- Apellidos -->
                        <div class="col-md-3">
                            <label for="lastname1" class="form-label">Apellido Paterno <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="lastname1" name="lastname1" value="{{ old('lastname1') }}" required>
                            <div class="invalid-feedback">
                                Por favor ingrese el apellido paterno.
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="lastname2" class="form-label">Apellido Materno <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="lastname2" name="lastname2" value="{{ old('lastname2') }}" required>
                            <div class="invalid-feedback">
                                Por favor ingrese el apellido materno.
                            </div>
                        </div>

                        <!-- CI y Celular -->
                        <div class="col-md-6">
                            <label for="CI" class="form-label">Número de CI <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="CI" name="CI" value="{{ old('CI') }}" required>
                            <div class="invalid-feedback">
                                Por favor ingrese el número de CI.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="Celular" class="form-label">Celular <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="Celular" name="Celular" value="{{ old('Celular') }}" required>
                            <div class="invalid-feedback">
                                Por favor ingrese el número de celular.
                            </div>
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div class="col-md-6">
                            <label for="fechadenac" class="form-label">Fecha de Nacimiento <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="fechadenac" name="fechadenac" value="{{ old('fechadenac') }}" required>
                            <div class="invalid-feedback">
                                Por favor seleccione la fecha de nacimiento.
                            </div>
                        </div>

                        <!-- País y Ciudad -->
                        <div class="col-md-6">
                            <label for="PaisReside" class="form-label">País de Residencia</label>
                            <input type="text" class="form-control" id="PaisReside" name="PaisReside" value="{{ old('PaisReside') }}">
                        </div>

                        <div class="col-md-6">
                            <label for="CiudadReside" class="form-label">Ciudad de Residencia</label>
                            <input type="text" class="form-control" id="CiudadReside" name="CiudadReside" value="{{ old('CiudadReside') }}">
                        </div>

                        <!-- Correo -->
                        <div class="col-md-12">
                            <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                            <div class="invalid-feedback">
                                Por favor ingrese un correo electrónico válido.
                            </div>
                        </div>

                        <!-- Botón de envío -->
                        <div class="col-12 mt-4">
                            <button class="btn btn-primary px-4" type="submit">
                                <i class="fas fa-save me-2"></i> Guardar Docente
                            </button>
                        </div>
                    </div>
                </form>

                @if ($errors->any())
                    <div class="alert alert-danger mt-4">
                        <h6 class="alert-heading">Corrija los siguientes errores:</h6>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            @else
                <div class="alert alert-danger mt-3">
                    <i class="fas fa-exclamation-circle me-2"></i> No tienes permisos de administrador para acceder a esta función.
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Validación de formulario con Bootstrap -->
<script>
(function () {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })
})()

document.addEventListener('DOMContentLoaded', function() {
    const ciInput = document.getElementById('CI');
    const ciHelpText = document.createElement('small');
    ciHelpText.className = 'form-text text-muted';
    ciHelpText.textContent = 'Dejar vacío para generación automática';
    ciInput.parentNode.appendChild(ciHelpText);

    ciInput.addEventListener('blur', function() {
        if(this.value.trim() === '') {
            this.value = 'AUTOGENERAR';
        }
    });
});
</script>


@endsection