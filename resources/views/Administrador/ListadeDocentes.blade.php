@section('titulo')
    Lista de Docentes
@endsection


@section('content')
    <div class="container my-4">
        <div class="border p-3 rounded shadow-sm bg-light">
            <div class="row align-items-center">
                <div class="col-md-6 mb-2">
                    <a href="{{ route('CrearDocente') }}" class="btn btn-sm btn-success">
                        <i class="bi bi-person-plus"></i> Crear Docente
                    </a>
                    <a href="{{ route('DocentesEliminados') }}" class="btn btn-sm btn-info">
                        <i class="bi bi-trash"></i> Docentes Eliminados
                    </a>
                </div>
                <div class="col-md-6 text-md-end">
                    <form action="{{ route('ListaDocentes') }}" method="GET" class="d-inline-block w-100 w-md-auto">
                        <div class="input-group">
                            <button type="submit" class="input-group-text"><i class="fa fa-search"></i></button>
                            <input class="form-control" placeholder="Buscar estudiante..." name="search" type="text"
                                id="searchInput" value="{{ request('search') }}">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @if (request('search'))
            <div class="alert alert-info mt-3">
                Mostrando resultados para: <strong>{{ request('search') }}</strong>
                <a href="{{ route('ListaDocentes') }}" class="float-right">Limpiar búsqueda</a>
            </div>
        @endif

        <div class="table-responsive mt-3">
            <table class="table table-hover table-striped align-middle">
                <thead class="">
                    <tr>
                        <th scope="col">Nro</th>
                        <th scope="col">Nombre y Apellidos</th>
                        <th scope="col">Celular</th>
                        <th scope="col"w>Correo</th>
                        <th scope="col" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($docentes as $docente)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $docente->name }} {{ $docente->lastname1 }} {{ $docente->lastname2 }}</td>
                            <td>+{{ $docente->Celular }}</td>
                            <td>{{ $docente->email }}</td>
                            <td class="text-center">
                                <a class="btn btn-sm btn-info" href="{{ route('perfil', [encrypt($docente->id)]) }}"
                                    title="Ver perfil">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <form action="{{ route('deleteUser', encrypt($docente->id)) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger btn-delete" title="Eliminar"
                                        onclick="return mostrarAdvertencia(event)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
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
            {{ $docentes->appends(['search' => request('search')])->links('custom-pagination') }}
        </div>
    </div>







    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
@endsection

@include('layout')
