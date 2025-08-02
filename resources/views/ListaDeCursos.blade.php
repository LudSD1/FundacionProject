@section('titulo')
    Lista de cursos
@endsection


@section('content')
<div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-header bg-light border-bottom">
            <div class="row align-items-center">
                @if (auth()->user()->hasRole('Administrador'))
                <div class="col-md-6 mb-2">
                    <a href="{{ route('CrearCurso') }}" class="btn btn-sm btn-success">
                        <i class="bi bi-plus-circle"></i> Crear Curso
                    </a>
                    <a href="{{ route('ListadeCursosEliminados') }}" class="btn btn-sm btn-info">
                        <i class="bi bi-trash"></i> Cursos Eliminados
                    </a>
                </div>
                @endif
                <div class="col-md-6 text-md-end">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                        <input type="text" class="form-control search-input" placeholder="Buscar curso..." id="searchInput">
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            @if (auth()->user()->hasRole('Administrador'))
                <table class="table table-striped table-bordered table-hover">
                    <thead >
                        <tr>
                            <th>Nº</th>
                            <th>Nombre Curso</th>
                            <th>Docente</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Formato</th>
                            <th>Tipo</th>
                            <th colspan="3" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cursos as $curso)
                            <tr class="curso-row" data-bs-toggle="modal" data-bs-target="#courseModal{{ $curso->id }}" style="cursor: pointer;">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ ucfirst(strtolower($curso->nombreCurso)) }}</td>
                                <td>{{ $curso->docente ? $curso->docente->name . ' ' . $curso->docente->lastname1 . ' ' . $curso->docente->lastname2 : 'N/A' }}</td>
                                <td>{{ $curso->fecha_ini ?? 'N/A' }}</td>
                                <td>{{ $curso->fecha_fin ?? 'N/A' }}</td>
                                <td>{{ $curso->formato ?? 'N/A' }}</td>
                                <td>{{ ucfirst(strtolower($curso->tipo)) ?? 'N/A' }}</td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-warning" href="{{ route('editarCurso', encrypt($curso->id)) }}" title="Editar">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-danger btn-delete" href="{{ route('quitarCurso', [encrypt($curso->id)]) }}" title="Eliminar">
                                        <i class="bi bi-trash"></i> Borrar
                                    </a>

                                </td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-info" href="{{ route('Curso', [encrypt($curso->id)]) }}" title="Ver Curso">
                                        <i class="bi bi-eye"></i> Ver
                                    </a>

                                    <div class="modal fade" id="courseModal{{ $curso->id }}" tabindex="-1" aria-labelledby="courseModalLabel{{ $curso->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="courseModalLabel{{ $curso->id }}">Detalles del Curso</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Nombre:</strong> {{ ucfirst(strtolower($curso->nombreCurso)) }}</p>
                                                    <p><strong>Nivel:</strong> {{ $curso->nivel ? ucfirst(strtolower($curso->nivel)) : 'N/A' }}</p>
                                                    <p><strong>Instructor:</strong> {{ $curso->docente ? $curso->docente->name . ' ' . $curso->docente->lastname1 . ' ' . $curso->docente->lastname2 : 'N/A' }}</p>
                                                    <p><strong>Edad Dirigida:</strong> {{ $curso->edad_dirigida ? ucfirst(strtolower($curso->edad_dirigida)) : 'N/A' }}</p>
                                                    <p><strong>Fecha Inicio:</strong> {{ $curso->fecha_ini ?? 'N/A' }}</p>
                                                    <p><strong>Fecha Fin:</strong> {{ $curso->fecha_fin ?? 'N/A' }}</p>
                                                    <p><strong>Formato:</strong> {{ $curso->formato ?? 'N/A' }}</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">
                                    <div class="alert alert-warning m-0">
                                        <i class="bi bi-exclamation-triangle"></i> No hay cursos registrados.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // Búsqueda en tiempo real
        $('#searchInput').on('input', function() {
            let searchText = $(this).val().toLowerCase();
            $('tbody tr').each(function() {
                $(this).toggle($(this).text().toLowerCase().includes(searchText));
            });
        });

        // Confirmación de eliminación con SweetAlert2
        $('.btn-delete').on('click', function(event) {
            event.preventDefault();
            event.stopPropagation(); // Prevent row click event
            let url = $(this).attr('href');

            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción eliminará el curso permanentemente.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });

        // Prevent action buttons from triggering row click
        $('.btn-warning, .btn-info').on('click', function(event) {
            event.stopPropagation();
        });
    });
</script>


    @if (auth()->user()->hasRole('Estudiante'))
        @forelse ($inscritos as $inscrito)
            @if (auth()->user()->id == $inscrito->estudiante_id && $inscrito->cursos && $inscrito->cursos->deleted_at === null)
                <div class="w-full md:w-1/2 xl:w-1/3 p-3">

                    <a href="{{ route('Curso', encrypt($inscrito->cursos_id)) }}" class="block bg-white border rounded shadow p-2">
                        <div class="flex flex-row items-center">
                            <div class="flex-shrink pr-4">
                                <div class="rounded p-3 bg-blue-400"><i class="fa fa-bars fa-2x fa-fw fa-inverse"></i></div>
                            </div>
                            <div class="flex-1 text-right md:text-center">
                                <h3 class="atma text-3xl">{{ $inscrito->cursos->nombreCurso }} <span
                                        class="text-green-500"></span></h3>
                                <h5 class="alegreya uppercase"></h5>
                                <span class="inline-block mt-2">IR</span>
                            </div>
                        </div>
                    </a>
                </div>
            @endif
        @empty
            <h1>NO TIENES CURSOS ASIGNADOS</h1>
        @endforelse
    @endif

    @if (auth()->user()->hasRole('Docente'))
        @forelse ($cursos as $cursos)
            @if (auth()->user()->id == $cursos->docente_id)
                <div class="w-full md:w-1/2 xl:w-1/3 p-3">

                    <a href="{{ route('Curso', encrypt($cursos->id)) }}" class="block bg-white border rounded shadow p-2">
                        <div class="flex flex-row items-center">
                            <div class="flex-shrink pr-4">
                                <div class="rounded p-3 bg-blue-400"><i class="fa fa-bars fa-2x fa-fw fa-inverse"></i>
                                </div>
                            </div>
                            <div class="flex-1 text-right md:text-center">
                                <h3 class="atma text-3xl">{{ $cursos->nombreCurso }} <span class="text-green-500"></span>
                                </h3>
                                <h5 class="alegreya uppercase"></h5>
                                <span class="inline-block mt-2">IR</span>
                            </div>
                        </div>
                    </a>
                </div>
            @else
            @endif
        @empty
            <div class="card pb-3 pt-3 col-xl-12">
                <h4>NO TIENES CURSOS ASIGNADOS</h4>
            </div>
        @endforelse
    @endif



@endsection


@if (auth()->user()->hasRole('Docente') || auth()->user()->hasRole('Estudiante'))
    @include('FundacionPlantillaUsu.index')
@endif



@if (auth()->user()->hasRole('Administrador'))
    @include('layout')
@endif
