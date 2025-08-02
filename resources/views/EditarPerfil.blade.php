@section('titulo')
    Editar Perfil
@endsection




@section('content')
<h1>Editar Perfíl</h1>

<form action="{{ route('EditarperfilPost', encrypt(auth()->user()->id)) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <br>

    <!-- Pestañas -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="contacto-tab" data-toggle="tab" href="#contacto" role="tab" aria-controls="contacto" aria-selected="true">Datos de Contacto</a>
        </li>
        @if (auth()->user()->hasRole('Docente'))
            <li class="nav-item">
                <a class="nav-link" id="profesional-tab" data-toggle="tab" href="#profesional" role="tab" aria-controls="profesional" aria-selected="false">Datos Profesionales</a>
            </li>
        @endif
        <li class="nav-item">
            <a class="nav-link" id="confirmacion-tab" data-toggle="tab" href="#confirmacion" role="tab" aria-controls="confirmacion" aria-selected="false">Confirmar Cambios</a>
        </li>
    </ul>

    <!-- Contenido de las Pestañas -->
    <div class="tab-content" id="myTabContent">
        <!-- Pestaña de Datos de Contacto -->
        <div class="tab-pane fade show active" id="contacto" role="tabpanel" aria-labelledby="contacto-tab">
            <br>
            <h2>Datos de Contacto</h2>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="name">Nombre:</label>
                    <input type="text" name="name" value="{{ auth()->user()->name }}" class="form-control">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="lastname1">Apellido Paterno:</label>
                    <input type="text" name="lastname1" value="{{ auth()->user()->lastname1 }}" class="form-control">
                </div>
                <div class="form-group col-md-6">
                    <label for="lastname2">Apellido Materno:</label>
                    <input type="text" name="lastname2" value="{{ auth()->user()->lastname2 }}" class="form-control">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="Celular">Número de Celular:</label>
                    <input type="text" name="Celular" value="{{ auth()->user()->Celular }}" class="form-control">
                </div>
                <div class="form-group col-md-6">
                    <label for="fecha_nac">Fecha de Nacimiento:</label>
                    <input type="date" name="fecha_nac" value="{{ auth()->user()->fechadenac }}" class="form-control">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="PaisReside">País:</label>
                    <input type="text" name="PaisReside" value="{{ auth()->user()->PaisReside }}" class="form-control">
                </div>
                <div class="form-group col-md-6">
                    <label for="CiudadReside">Ciudad:</label>
                    <input type="text" name="CiudadReside" value="{{ auth()->user()->CiudadReside }}" class="form-control">
                </div>
            </div>
        </div>

        <!-- Pestaña de Datos Profesionales (solo para Docentes) -->
        @if (auth()->user()->hasRole('Docente'))
            <div class="tab-pane fade" id="profesional" role="tabpanel" aria-labelledby="profesional-tab">
                <br>
                <h2>Datos Profesionales</h2>
                @foreach ($atributosD as $atributo)
                    <div class="form-row mb-4">
                        <div class="form-group col-md-4">
                            <label for="formacion">Formación Académica:</label>
                            <input type="text" name="formacion" placeholder="Formación Académica" value="{{ $atributo->formacion }}" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="Especializacion">Experiencia Laboral:</label>
                            <input type="text" name="Especializacion" placeholder="Experiencia Laboral" value="{{ $atributo->Especializacion }}" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="ExperienciaL">Especialización:</label>
                            <input type="text" name="ExperienciaL" placeholder="Especialización" value="{{ $atributo->ExperienciaL }}" class="form-control">
                        </div>
                    </div>
                @endforeach

                <br>
                <h2>Últimos Trabajos Realizados</h2>
                <table class="table table-responsive-sm table-hover">
                    <thead>
                        <tr>
                            <th>Empresa</th>
                            <th>Cargo</th>
                            <th>Año Ingreso</th>
                            <th>Año Salida</th>
                            <th>Contacto Referencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ultimosTrabajos as $trabajo)
                            <tr>
                                <td><input type="text" name="trabajos[{{ $loop->index }}][empresa]" value="{{ $trabajo->empresa }}" class="form-control"></td>
                                <td><input type="text" name="trabajos[{{ $loop->index }}][cargo]" value="{{ $trabajo->cargo }}" class="form-control"></td>
                                <td><input type="date" name="trabajos[{{ $loop->index }}][fechain]" value="{{ $trabajo->fecha_inicio }}" class="form-control"></td>
                                <td><input type="date" name="trabajos[{{ $loop->index }}][fechasal]" value="{{ $trabajo->fecha_fin }}" class="form-control"></td>
                                <td><input type="text" name="trabajos[{{ $loop->index }}][contacto]" value="{{ $trabajo->contacto_ref }}" class="form-control"></td>
                            </tr>
                        @empty
                            @for ($i = 0; $i < 4; $i++)
                                <tr>
                                    <td><input type="text" name="trabajos[{{ $i }}][empresa]" value="" class="form-control"></td>
                                    <td><input type="text" name="trabajos[{{ $i }}][cargo]" value="" class="form-control"></td>
                                    <td><input type="date" name="trabajos[{{ $i }}][fechain]" value="" class="form-control"></td>
                                    <td><input type="date" name="trabajos[{{ $i }}][fechasal]" value="" class="form-control"></td>
                                    <td><input type="text" name="trabajos[{{ $i }}][contacto]" value="" class="form-control"></td>
                                </tr>
                            @endfor
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Pestaña de Confirmación de Cambios -->
        <div class="tab-pane fade" id="confirmacion" role="tabpanel" aria-labelledby="confirmacion-tab">
            <br>
            <h2>Confirmar Cambios</h2>
            <div class="form-group mt-4 mb-4">
                <label for="confirmpassword">INGRESA LA CONTRASEÑA PARA HACER LOS CAMBIOS</label>
                <div class="input-group">
                    <input type="password" id="confirmpassword" name="confirmpassword" placeholder="Contraseña" class="form-control ml-4">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i id="togglePassword" class="fa fa-eye-slash" aria-hidden="true"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group m-4">
                <input class="btn btn-success" type="submit" value="Guardar Cambios">
            </div>
        </div>
    </div>
</form>

<!-- Script para mostrar/ocultar contraseña -->
<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('confirmpassword');
        const icon = this;
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    });
</script>
@endsection

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>


@include('layout')
