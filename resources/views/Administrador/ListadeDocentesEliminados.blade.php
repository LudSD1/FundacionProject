@section('titulo')
    Lista de Docentes
@endsection


@section('nav')
    @if (auth()->user()->hasRole('Administrador'))
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('Miperfil') }}">
                    <i class="ni ni-circle-08 text-green"></i> Mi perfil
                </a>
            </li>
            <li class="nav-item ">
                <a class="nav-link " href="{{ route('Inicio') }}">
                    <i class="ni ni-tv-2 text-primary"></i> Inicio
                </a>
            </li>
            <li class="nav-item   ">
                <a class="nav-link  " href="{{ route('ListadeCursos') }}">
                    <i class="ni ni-book-bookmark text-blue"></i> Lista de Cursos
                </a>
            </li>
            <li class="nav-item active">
                <a class="nav-link active " href="{{ route('ListaDocentes') }}">
                    <i class="ni ni-single-02 text-blue"></i> Lista de Docentes
                </a>
            </li>
            <li class="nav-item ">
                <a class="nav-link " href="{{ route('ListaEstudiantes') }}">
                    <i class="ni ni-single-02 text-orange"></i> Lista de Estudiantes
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link " href="{{ route('aportesLista') }}">
                    <i class="ni ni-bullet-list-67 text-red"></i> Aportes
                </a>
            </li>
            <li class="nav-item ">
                <a class="nav-link " href="{{ route('AsignarCurso') }}">
                    <i class="ni ni-key-25 text-info"></i> Asignación Cursos
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
    <div class="col-lg-12 row">
        <form class="navbar-search navbar-search form-inline mr-3 d-none d-md-flex ml-lg-auto">
            <div class="input-group input-group-alternative">
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
                <th scope="col">Correo</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($docente as $docente)
            <tr>

                <td scope="row">
                    {{ $loop->iteration }}
                </td>
                <td scope="row">
                    {{ $docente->name }}
                    {{ $docente->lastname1 }}
                    {{ $docente->lastname2 }}
                </td>
                <td>
                    {{ $docente->Celular }}
                </td>
                <td>
                    {{ $docente->email }}
                </td>
                <td>
                    <a href="{{ route('perfil' , [$docente->id]) }}">
                        Ver Mas
                        <img src="{{ asset('assets/icons/ojo.png') }}" alt="Ver Icon" style="width: 16px; height: 16px;">
                    </a>
                    /
                    <a href="{{route('restaurarUsuario', [$docente->id])}}" onclick="mostrarAdvertencia(event)">
                        Restaurar Docente
                        <img src="{{ asset('assets/icons/borrar.png') }}" alt="Borrar Icon" style="width: 16px; height: 16px;">
                    </a>
                </td>
            </tr>
            @empty
            <td>
                <h4>NO HAY DOCENTES ELIMINADOS</h4>
            </td>
            @endforelse

        </tbody>
    </table>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

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


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        function mostrarAdvertencia(event) {
            event.preventDefault();

            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción retornara a este Docente eliminado. ¿Estás seguro de que deseas continuar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirige al usuario al enlace original
                    window.location.href = event.target.getAttribute('href');
                }
            });
        }
    </script>


@endsection

@include('layout')
