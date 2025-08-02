@extends('layout')

@section('titulo')
    Crear Estudiante
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('ListaEstudiantes') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i> Volver
                </a>
                <h5 class="mb-0">Crear Nuevo Estudiante</h5>
            </div>
        </div>

        <div class="card-body">
            <div class="text-end mb-4">
                <a href="{{ route('CrearEstudianteMenor') }}" class="btn btn-success">
                    <i class="fas fa-user-plus me-2"></i> Crear Estudiante con Representante Legal
                </a>
            </div>

            <form method="post" action="{{ route('CrearEstudiantePost') }}" class="needs-validation" novalidate>
                @csrf

                <div class="row g-3">
                    <!-- Nombre -->
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        <div class="invalid-feedback">
                            Por favor ingrese el nombre del estudiante.
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
                        <label for="CI" class="form-label">CI Estudiante <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="CI" name="CI" value="{{ old('CI') }}" required>
                            <button class="btn btn-outline-secondary" type="button" id="generarCI">
                                <i class="fas fa-key"></i> Generar
                            </button>
                        </div>
                        <div class="form-text">Si no tiene CI boliviano, haga clic en Generar</div>
                        <div class="invalid-feedback">
                            Por favor ingrese el CI o genere uno automático.
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="Celular" class="form-label">Número de Celular <span class="text-danger">*</span></label>
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
                            <i class="fas fa-save me-2"></i> Guardar Estudiante
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
        </div>
    </div>
</div>

<!-- Script para generar CI automático -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Generar CI automático
    document.getElementById('generarCI').addEventListener('click', function() {
        const prefix = 'EST-' + new Date().getFullYear().toString().slice(-2);
        const randomNum = Math.floor(1000 + Math.random() * 9000);
        document.getElementById('CI').value = `${prefix}-${randomNum}`;
    });

    // Validación de formulario con Bootstrap
    (function () {
        'use strict'

        var forms = document.querySelectorAll('.needs-validation')

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
});
</script>
@endsection