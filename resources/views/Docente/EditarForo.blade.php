
@section('titulo')
Editar Foro
@endsection



@section('nav')
    @if (auth()->user()->hasRole('Administrador'))
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('Miperfil') }}">
                    <i class="ni ni-circle-08 text-green"></i> Mi perfil
                </a>
            </li>
            <li class="nav-item  active ">
                <a class="nav-link  active " href="{{ route('Inicio') }}">
                    <i class="ni ni-tv-2 text-primary"></i> Mis Cursos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="{{ route('ListaDocentes') }}">
                    <i class="ni ni-single-02 text-blue"></i> Lista de Docentes
                </a>
            </li>
            <li class="nav-item">F
                <a class="nav-link " href="{{ route('ListaEstudiantes') }}">
                    <i class="ni ni-single-02 text-orange"></i> Lista de Estudiantes
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link " href="{{ route('pagos') }}">
                    <i class="ni ni-bullet-list-67 text-red"></i> Aportes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('AsignarCurso') }}">
                    <i class="ni ni-key-25 text-info"></i> Asignación de Cursos
                </a>
            </li>

        </ul>
    @endif

    @if (auth()->user()->hasRole('Docente'))
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('Miperfil') }}">
                    <i class="ni ni-circle-08 text-green"></i> Mi perfil
                </a>
            </li>
            <li class="nav-item  active ">
                <a class="nav-link  active " href="{{ route('Inicio') }}">
                    <i class="ni ni-tv-2 text-primary"></i> Mis Cursos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="{{ route('pagos') }}">
                    <i class="ni ni-bullet-list-67 text-red"></i> Aportes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('AsignarCurso') }}">
                    <i class="ni ni-key-25 text-info"></i> Asignación de Cursos
                </a>
            </li>

        </ul>
    @endif


@endsection

@section('content')
<div class="border p-3">
<a href="javascript:history.back()" class="btn btn-primary">
    &#9668; Volver
</a>
<br>
<div class="form col-10  mb-10 ">
    <form method="post" action="{{route('EditarForoPost', $foro->id)}}">
        @csrf

        <input type="text" name="idForo" value="{{$foro->id}}" hidden>

        <input type="text" name="curso_id" value="{{$foro->cursos_id}}" hidden>
        <!-- Campos del formulario -->
        <label for="name">Nombre Foro</label>
        <input type="text" name="nombreForo" value="{{$foro->nombreForo}}">
        <br>
        <label for="name">Subtitulo Foro</label>
        <input type="text" name="SubtituloForo" value="{{$foro->SubtituloForo}}">
        <br>
        <label for="lastname1">Descripcion del foro</label>
        <br>
        <textarea id="" cols="100" rows="10" name="descripcionForo" >
            {{$foro->descripcionForo}}
        </textarea>
        <br>
        <br>
        <label for="fechadenac">Fecha de Finalizacion</label>
        <input type="date" name="fechaFin" value="{{$foro->fechaFin}}">
        <br>
        <input type="submit" value="Guardar Cambios" class="btn-crear">
        <br><br>
    </form>
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
@endsection


@include('layout')
