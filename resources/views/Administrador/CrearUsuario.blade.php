@section('titulo')
    Crear Nuevo Usuario
@endsection

@section('content')


<div class="create-user-wrapper">
    <div class="container" style="max-width: 890px;">
        <div class="create-user-card">

            {{-- Header --}}
            <div class="create-user-header">
                <a href="{{ route('ListaUsuarios') }}" class="btn-back-create">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
                <h5 class="header-title">
                    <i class="bi bi-person-plus-fill"></i>
                    Crear Nuevo Usuario
                </h5>
            </div>

            {{-- Body --}}
            <div class="create-user-body">
                @if(auth()->user()->hasRole('Administrador'))
                    <form method="POST" action="{{ route('CrearDocentePost') }}" novalidate>
                        @csrf

                        {{-- Sección: Datos personales --}}
                        <div class="form-section-title"><i class="bi bi-person"></i> Datos Personales</div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6 field-group">
                                <label class="field-label">Nombre <span class="req">*</span></label>
                                <input type="text" class="field-input" name="name" value="{{ old('name') }}" placeholder="Ej. Juan" required>
                            </div>
                            <div class="col-md-3 field-group">
                                <label class="field-label">Apellido Paterno <span class="req">*</span></label>
                                <input type="text" class="field-input" name="lastname1" value="{{ old('lastname1') }}" placeholder="Ej. Pérez" required>
                            </div>
                            <div class="col-md-3 field-group">
                                <label class="field-label">Apellido Materno <span class="req">*</span></label>
                                <input type="text" class="field-input" name="lastname2" value="{{ old('lastname2') }}" placeholder="Ej. López" required>
                            </div>
                        </div>

                        {{-- Sección: Identificación --}}
                        <div class="form-section-title"><i class="bi bi-card-text"></i> Identificación</div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4 field-group">
                                <label class="field-label">Cédula de Identidad <span class="req">*</span></label>
                                <input type="text" class="field-input" name="CI" value="{{ old('CI') }}" placeholder="Ej. 12345678" required>
                            </div>
                            <div class="col-md-4 field-group">
                                <label class="field-label">Celular <span class="req">*</span></label>
                                <input type="text" class="field-input" name="Celular" value="{{ old('Celular') }}" placeholder="Ej. 78901234" required>
                            </div>
                            <div class="col-md-4 field-group">
                                <label class="field-label">Fecha de Nacimiento <span class="req">*</span></label>
                                <input type="date" class="field-input" name="fechadenac" value="{{ old('fechadenac') }}" required>
                            </div>
                        </div>

                        {{-- Sección: Ubicación --}}
                        <div class="form-section-title"><i class="bi bi-geo-alt"></i> Ubicación</div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6 field-group">
                                <label class="field-label">País de Residencia</label>
                                <input type="text" class="field-input" name="PaisReside" value="{{ old('PaisReside') }}" placeholder="Ej. Bolivia">
                            </div>
                            <div class="col-md-6 field-group">
                                <label class="field-label">Ciudad de Residencia</label>
                                <input type="text" class="field-input" name="CiudadReside" value="{{ old('CiudadReside') }}" placeholder="Ej. La Paz">
                            </div>
                        </div>

                        {{-- Sección: Contacto --}}
                        <div class="form-section-title"><i class="bi bi-envelope"></i> Contacto</div>

                        <div class="row g-3 mb-3">
                            <div class="col-12 field-group">
                                <label class="field-label">Correo Electrónico <span class="req">*</span></label>
                                <input type="email" class="field-input" name="email" value="{{ old('email') }}" placeholder="usuario@correo.com" required>
                            </div>
                        </div>

                        {{-- Sección: Rol --}}
                        <div class="form-section-title"><i class="bi bi-shield"></i> Rol del Usuario</div>

                        <div class="role-grid mb-4">
                            <div class="role-option estudiante">
                                <input type="radio" name="role" id="rol_estudiante" value="Estudiante"
                                    {{ old('role', 'Estudiante') == 'Estudiante' ? 'checked' : '' }}>
                                <label for="rol_estudiante">
                                    <i class="bi bi-book-fill" style="color:#3b82f6"></i>
                                    Estudiante
                                </label>
                            </div>
                            <div class="role-option docente">
                                <input type="radio" name="role" id="rol_docente" value="Docente"
                                    {{ old('role') == 'Docente' ? 'checked' : '' }}>
                                <label for="rol_docente">
                                    <i class="bi bi-mortarboard-fill" style="color:#10b981"></i>
                                    Docente
                                </label>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn-submit-create">
                                <i class="bi bi-save2-fill"></i>
                                Guardar Usuario
                            </button>
                        </div>
                    </form>

                    {{-- Errores --}}
                    @if($errors->any())
                        <div class="errors-box">
                            <div class="errors-title">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                Corrige los siguientes errores:
                            </div>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                @else
                    <div class="no-access-box">
                        <i class="bi bi-lock-fill me-2"></i>
                        No tienes permisos de administrador para acceder a esta función.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@include('layout')