@section('titulo')
    Lista de Paticipantes {{ $cursos->nombreCurso }}
@endsection




@section('content')
    <div class="col-xl-12 row">
        <div class="col-1 mb-3">
            <a href="{{route('listacurso', ['id'=> encrypt($cursos->id)])}}" class="btn btn-primary">
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


                            <a class="btn btn-sm btn-success" href="{{ route('restaurarIncripcion', encrypt($inscritos->id ?? '')) }}">Restaurar Inscripción</a>

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
