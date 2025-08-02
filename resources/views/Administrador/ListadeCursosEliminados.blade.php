@section('titulo')
    Área Personal
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
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ ucfirst(strtolower($curso->nombreCurso)) }}</td>
                                <td>{{ $curso->docente ? $curso->docente->name . ' ' . $curso->docente->lastname1 . ' ' . $curso->docente->lastname2 : 'N/A' }}</td>
                                <td>{{ $curso->fecha_ini ?? 'N/A' }}</td>
                                <td>{{ $curso->fecha_fin ?? 'N/A' }}</td>
                                <td>{{ $curso->formato ?? 'N/A' }}</td>
                                <td>{{ ucfirst(strtolower($curso->tipo)) ?? 'N/A' }}</td>

                                <td class="text-center">
                                    <a class="btn btn-sm btn-info" href="{{ route('restaurarCurso', [encrypt($curso->id)]) }}" title="Eliminar">
                                        <i class="bi bi-check"></i> Restaurar
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-info" href="{{ route('Curso', [encrypt($curso->id)]) }}" title="Ver Curso">
                                        <i class="bi bi-eye"></i> Ver
                                    </a>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#courseModal{{ $curso->id }}">
                                        <i class="bi bi-info-circle"></i> Detalles
                                    </button>

                                    <!-- Modal para cada curso -->
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
                                        <i class="bi bi-exclamation-triangle"></i> No hay cursos Cerrados.
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

@endsection

@if (auth()->user()->hasRole('Docente') || auth()->user()->hasRole('Estudiante'))
    @include('FundacionPlantillaUsu.index')
@endif



@if (auth()->user()->hasRole('Administrador'))
    @include('layout')
@endif
