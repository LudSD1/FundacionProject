

@section('titulo')

Crear Estudiante Con tutor
@endsection




@section('content')
<div class="col-xl-12">
<a href="{{route('ListaEstudiantes')}}" class="btn btn-primary">
    &#9668; Volver
</a>
<br>
<br>
<br>
<div class="col-xl-12">


<form method="post">
    @csrf
@if (auth()->user()->hasRole('Administrador'))
<form class="form" method="post" action="{{route('CrearEstudianteMenorPost')}}">
    @csrf

    <h2>Informacion de Estudiante</h2>

    <div class="form-group">
        <label for="name">Nombre</label>
        <span class="text-danger">*</span>
        <input type="text" name="name" value="{{ old('name')}}" class="form-control custom-input">
    </div>

    <div class="mr-10 mt-3 mb-3" style="display: flex; align-items: center;">

    <div class="mr-8">
        <label for="lastname1">Apellido Paterno</label>
        <span class="text-danger">*</span>
        <input type="text" name="lastname1"  value="{{ old('lastname1')}}" class="form-control w-auto">
    </div>

    <div class="ml-3">
        <label for="lastname2">Apellido Materno</label>
        <span class="text-danger">*</span>
        <input type="text" name="lastname2" value="{{ old('lastname2')}}" class="form-control w-auto">
    </div>
    </div>

    <div class="mr-10 mt-3 mb-3" style="display: flex; align-items: center;">

    <div class="mr-8">
        <label for="CI">Cédula de Identidad Estudiante</label>
        <span class="text-danger">*</span>
        <input type="text" name="CI" value="{{ old('CI')}}" class="form-control w-auto">
    </div>

    <div class="ml-3">
        <label for="fechadenac">Fecha de Nacimiento</label>
        <span class="text-danger">*</span>
        <input type="date" name="fechadenac" value="{{ old('fechadenac')}}" class="form-control w-auto">
    </div>
        </div>

        <div class="mr-10 mt-3 mb-3" style="display: flex; align-items: center;">

    <div class="mr-8">
        <label for="PaisReside">Pais Residencia (Opcional)</label>
        <input type="text" name="PaisReside" value="{{ old('PaisReside')}}" class="form-control w-auto">
    </div>

    <div class="ml-3">
        <label for="CiudadReside">Ciudad Residencia (Opcional)</label>
        <input type="text" name="CiudadReside" value="{{ old('CiudadReside')}}" class="form-control w-auto">
    </div>
    </div>

    <br>
    <h2>Informacion de Representante Legal</h2>
    <br>

    <div class="form-group">
        <label for="nombreT">Nombre Tutor</label>
        <span class="text-danger">*</span>
        <input type="text" name="nombreT" value="{{ old('nombreT')}}" class="form-control custom-input">
    </div>

    <div class="mr-10 mt-3 mb-3" style="display: flex; align-items: center;">

    <div class="mr-8">
        <label for="appT">Apellido Paterno Tutor</label>
        <span class="text-danger">*</span>
        <input type="text" name="appT" value="{{ old('appT')}}" class="form-control w-auto">
    </div>

    <div class="ml-3">
        <label for="apmT">Apellido Materno Tutor</label>
        <span class="text-danger">*</span>
        <input type="text" name="apmT" value="{{ old('apmT')}}" class="form-control w-auto">
    </div>
    </div>

    <div class="mr-10 mt-3 mb-3" style="display: flex; align-items: center;">

    <div class="mr-8">
        <label for="CelularT">Celular Tutor</label>
        <span class="text-danger">*</span>
        <input type="text" name="CelularT" value="{{ old('CelularT')}}" class="form-control w-auto">
    </div>

    <div class="ml-3">
        <label for="CIT">Cédula de Identidad Tutor</label>
        <span class="text-danger">*</span>

        <input type="text" name="CIT" value="{{ old('CIT')}}" class="form-control w-auto">
    </div>
    </div>

    <div class="form-group">
        <label for="emailTutor">Correo Electrónico Tutor</label>
        <span class="text-danger">*</span>
        <input type="text" name="email" value="{{ old('email')}}" class="form-control custom-input">
    </div>

    <br>
    <input type="submit" value="Guardar" class="btn btn-primary">
</form>

<style>
    .custom-input {
        width: 50%;
        height: 50px;
    }
</style>


@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif



@else

<h1>NO ERES ADMINISTRADOR</h1>

@endif
</div>
@endsection

@include('layout')
