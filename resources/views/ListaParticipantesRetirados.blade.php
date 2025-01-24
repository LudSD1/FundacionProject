@section('titulo')
    Lista de Paticipantes {{ $cursos->nombreCurso }}
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
            <li class="nav-item">
                <a class="nav-link " href="{{ route('ListaEstudiantes') }}">
                    <i class="ni ni-single-02 text-orange"></i> Lista de Estudiantes
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link " href="./examples/tables.html">
                    <i class="ni ni-bullet-list-67 text-red"></i> Aportes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('AsignarCurso') }}">
                    <i class="ni ni-key-25 text-info"></i> Asignación Cursos
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
                <a class="nav-link " href="./examples/tables.html">
                    <i class="ni ni-bullet-list-67 text-red"></i> Aportes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('AsignarCurso') }}">
                    <i class="ni ni-key-25 text-info"></i> Asignación Cursos
                </a>
            </li>

        </ul>
    @endif

    @if (auth()->user()->hasRole('Estudiante'))
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
                <a class="nav-link " href="./examples/tables.html">
                    <i class="ni ni-bullet-list-67 text-red"></i> Mis Aportes
                </a>
            </li>


        </ul>
    @endif
@endsection


@section('content')
    <div class="col-xl-12 row">
        <div class="col-1 mb-3">
            <a href="{{route('listacurso', ['id'=> $cursos->id])}}" class="btn btn-primary">
                &#9668; Volver
            </a>
        </div>
        <form class="navbar-search navbar-search form-inline mr-3 d-none d-md-flex ml-lg-auto">
            <div class="ml-3 input-group input-group-alternative">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                </div>
                <input class="form-control" placeholder="Buscar" type="text" id="searchInput">
            </div>
        </form>
    </div>
    <table class="table align-items-center table-flush">
        <thead class="thead-light">
            <tr>
                <th scope="col">Nro</th>
                <th scope="col">Nombre y Apellidos</th>
                <th scope="col">Celular</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>

            @forelse ($inscritos as $inscritos)
                @if ($inscritos->cursos_id == $cursos->id)
                    <tr>

                        <td scope="row">

                            {{ $loop->iteration }}

                        </td>
                        <td scope="row">
                            {{ isset($inscritos->estudiantes) ? $inscritos->estudiantes->name : 'Estudiante Eliminado' }}
                            {{ isset($inscritos->estudiantes) ? $inscritos->estudiantes->lastname1 : '' }}
                            {{ isset($inscritos->estudiantes) ? $inscritos->estudiantes->lastname2 : '' }}
                        </td>
                        <td>
                            {{ isset($inscritos->estudiantes) ? $inscritos->estudiantes->Celular : '' }}
                        </td>

                        <td>


                            <a class="btn btn-sm btn-success" href="{{ route('restaurarIncripcion', $inscritos->id ?? '') }}">Restaurar Inscripción</a>

                        </td>
                    </tr>
                @endif


            @empty
                <tr>

                    <td>

                        <h4>NO HAY ALUMNOS RETIRADOS</h4>

                    </td>
                </tr>
            @endforelse


        </tbody>
    </table>


        <!-- Agrega esto en tu archivo Blade antes de </body> -->
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

        <script>
            $(document).ready(function() {
                // Manejo del evento de entrada en el campo de búsqueda
                $('input[type="text"]').on('input', function() {
                    var searchText = $(this).val().toLowerCase();

                    // Filtra las filas de la tabla basándote en el valor del campo de búsqueda
                    $('tbody tr').filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(searchText) > -1);
                    });
                });
            });
        </script>


        <script>
            $(document).ready(function() {
                // Manejo del evento de entrada en el campo de búsqueda
                $('.search-input').on('input', function() {
                    var searchText = $(this).val().toLowerCase();

                    // Filtra las filas de la tabla basándote en el valor del campo de búsqueda
                    $('tbody tr').filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(searchText) > -1);
                    });
                });
            });
        </script>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
@endsection

@include('layout')
