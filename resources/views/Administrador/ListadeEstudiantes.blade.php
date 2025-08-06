@section('titulo')
    Lista de Estudiantes
@endsection


@section('content')

<div class="container my-4">
    <div class="border p-3 rounded shadow-sm bg-light">
        <div class="row align-items-center">
            <div class="col-md-6 mb-2">
                <a href="{{ route('CrearEstudiante') }}" class="btn btn-sm btn-success">
                    <i class="bi bi-person-plus"></i> Crear Estudiante
                </a>
                <a href="{{ route('ListaEstudiantesEliminados') }}" class="btn btn-sm btn-info">
                    <i class="bi bi-trash"></i> Estudiantes Eliminados
                </a>
            </div>
            <div class="col-md-6 text-md-end">
                <form action="{{ route('ListaEstudiantes') }}" method="GET" class="d-inline-block w-100 w-md-auto">
                    <div class="input-group">
                        <button type="submit" class="input-group-text"><i class="fa fa-search"></i></button>
                        <input class="form-control" placeholder="Buscar estudiante..." name="search" type="text" id="searchInput" value="{{ request('search') }}">
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if(request('search'))
    <div class="alert alert-info mt-3">
        Mostrando resultados para: <strong>{{ request('search') }}</strong>
        <a href="{{ route('ListaEstudiantes') }}" class="float-right">Limpiar búsqueda</a>
    </div>
    @endif

    <div class="table-responsive mt-3">
        <table class="table table-hover table-striped align-middle">
            <thead >
                <tr>
                    <th scope="col">Nro</th>
                    <th scope="col">Nombre y Apellidos</th>
                    <th scope="col">Celular</th>
                    <th scope="col">Correo</th>
                    <th scope="col" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($estudiantes as $estudiante)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $estudiante->name }} {{ $estudiante->lastname1 }} {{ $estudiante->lastname2 }}</td>
                        <td>+{{ $estudiante->Celular }}</td>
                        <td>{{ $estudiante->email }}</td>
                        <td class="text-center">
                            <a class="btn btn-sm btn-info" href="{{ route('perfil', [encrypt($estudiante->id)]) }}" title="Ver perfil">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a class="btn btn-sm btn-danger btn-delete" href="{{ route('eliminarUser', [encrypt($estudiante->id)]) }}" title="Eliminar" onclick="mostrarAdvertencia(event)">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            <div class="alert alert-warning m-0">
                                <i class="bi bi-exclamation-triangle"></i> No hay estudiantes registrados.
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $estudiantes->appends(['search' => request('search')])->links('custom-pagination') }}
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function mostrarAdvertencia(event) {
            event.preventDefault();
            const url = event.target.getAttribute('href');

            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción borrará a este estudiante. ¿Estás seguro de que deseas continuar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
    </script>


@endsection

@include('layout')
