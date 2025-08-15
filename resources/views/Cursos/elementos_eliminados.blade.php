@extends('layout')

@section('titulo')
Elementos eliminados del curso: {{ $curso->nombreCurso }}
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Elementos eliminados del curso: {{ $curso->nombreCurso }}</h4>
                    <a href="{{ route('Curso', encrypt($curso->id)) }}" class="btn btn-secondary">Volver al curso</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="alert alert-info">
                        <p>Este curso tiene <strong>{{ $cantidadInscritos }}</strong> estudiantes inscritos.</p>
                        <p>La restauración de elementos permitirá que los estudiantes vuelvan a acceder a estos recursos.</p>
                    </div>

                    <ul class="nav nav-tabs" id="elementosTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" id="temas-tab" data-bs-toggle="tab" data-bs-target="#temas" type="button" role="tab">
                                Temas ({{ $temasEliminados->count() }})
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="subtemas-tab" data-bs-toggle="tab" data-bs-target="#subtemas" type="button" role="tab">
                                Subtemas ({{ $subtemasEliminados->count() }})
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="actividades-tab" data-bs-toggle="tab" data-bs-target="#actividades" type="button" role="tab">
                                Actividades ({{ $actividadesEliminadas->count() }})
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="foros-tab" data-bs-toggle="tab" data-bs-target="#foros" type="button" role="tab">
                                Foros ({{ $forosEliminados->count() }})
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="recursos-tab" data-bs-toggle="tab" data-bs-target="#recursos" type="button" role="tab">
                                Recursos ({{ $recursosEliminados->count() }})
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="elementosTabsContent">
                        <!-- Temas eliminados -->
                        <div class="tab-pane fade show active" id="temas" role="tabpanel" aria-labelledby="temas-tab">
                            @if($temasEliminados->count() > 0)
                                <div class="table-responsive mt-3">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Título</th>
                                                <th>Descripción</th>
                                                <th>Fecha eliminación</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($temasEliminados as $tema)
                                                <tr>
                                                    <td>{{ $tema->titulo_tema }}</td>
                                                    <td>{{ Str::limit($tema->descripcion, 100) }}</td>
                                                    <td>{{ $tema->deleted_at->format('d/m/Y H:i') }}</td>
                                                    <td>
                                                        <form action="{{ route('cursos.restaurar-elemento') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="tipo" value="tema">
                                                            <input type="hidden" name="id" value="{{ $tema->id }}">
                                                            <input type="hidden" name="curso_id" value="{{ $curso->id }}">
                                                            <button type="submit" class="btn btn-sm btn-success">Restaurar</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="mt-3">No hay temas eliminados para este curso.</p>
                            @endif
                        </div>

                        <!-- Subtemas eliminados -->
                        <div class="tab-pane fade" id="subtemas" role="tabpanel" aria-labelledby="subtemas-tab">
                            @if($subtemasEliminados->count() > 0)
                                <div class="table-responsive mt-3">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Título</th>
                                                <th>Tema</th>
                                                <th>Descripción</th>
                                                <th>Fecha eliminación</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($subtemasEliminados as $subtema)
                                                <tr>
                                                    <td>{{ $subtema->titulo_subtema }}</td>
                                                    <td>{{ $subtema->tema->titulo_tema ?? 'Tema eliminado' }}</td>
                                                    <td>{{ Str::limit($subtema->descripcion, 100) }}</td>
                                                    <td>{{ $subtema->deleted_at->format('d/m/Y H:i') }}</td>
                                                    <td>
                                                        <form action="{{ route('cursos.restaurar-elemento') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="tipo" value="subtema">
                                                            <input type="hidden" name="id" value="{{ $subtema->id }}">
                                                            <input type="hidden" name="curso_id" value="{{ $curso->id }}">
                                                            <button type="submit" class="btn btn-sm btn-success">Restaurar</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="mt-3">No hay subtemas eliminados para este curso.</p>
                            @endif
                        </div>

                        <!-- Actividades eliminadas -->
                        <div class="tab-pane fade" id="actividades" role="tabpanel" aria-labelledby="actividades-tab">
                            @if($actividadesEliminadas->count() > 0)
                                <div class="table-responsive mt-3">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Título</th>
                                                <th>Subtema</th>
                                                <th>Tipo</th>
                                                <th>Fecha eliminación</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($actividadesEliminadas as $actividad)
                                                <tr>
                                                    <td>{{ $actividad->titulo }}</td>
                                                    <td>{{ $actividad->subtema->titulo_subtema ?? 'Subtema eliminado' }}</td>
                                                    <td>{{ $actividad->tipoActividad->nombre ?? 'Desconocido' }}</td>
                                                    <td>{{ $actividad->deleted_at->format('d/m/Y H:i') }}</td>
                                                    <td>
                                                        <form action="{{ route('cursos.restaurar-elemento') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="tipo" value="actividad">
                                                            <input type="hidden" name="id" value="{{ $actividad->id }}">
                                                            <input type="hidden" name="curso_id" value="{{ $curso->id }}">
                                                            <button type="submit" class="btn btn-sm btn-success">Restaurar</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="mt-3">No hay actividades eliminadas para este curso.</p>
                            @endif
                        </div>

                        <!-- Foros eliminados -->
                        <div class="tab-pane fade" id="foros" role="tabpanel" aria-labelledby="foros-tab">
                            @if($forosEliminados->count() > 0)
                                <div class="table-responsive mt-3">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Título</th>
                                                <th>Descripción</th>
                                                <th>Fecha eliminación</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($forosEliminados as $foro)
                                                <tr>
                                                    <td>{{ $foro->titulo ?? 'Sin título' }}</td>
                                                    <td>{{ Str::limit($foro->descripcion ?? 'Sin descripción', 100) }}</td>
                                                    <td>{{ $foro->deleted_at->format('d/m/Y H:i') }}</td>
                                                    <td>
                                                        <form action="{{ route('cursos.restaurar-elemento') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="tipo" value="foro">
                                                            <input type="hidden" name="id" value="{{ $foro->id }}">
                                                            <input type="hidden" name="curso_id" value="{{ $curso->id }}">
                                                            <button type="submit" class="btn btn-sm btn-success">Restaurar</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="mt-3">No hay foros eliminados para este curso.</p>
                            @endif
                        </div>

                        <!-- Recursos eliminados -->
                        <div class="tab-pane fade" id="recursos" role="tabpanel" aria-labelledby="recursos-tab">
                            @if($recursosEliminados->count() > 0)
                                <div class="table-responsive mt-3">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Título</th>
                                                <th>Descripción</th>
                                                <th>Fecha eliminación</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recursosEliminados as $recurso)
                                                <tr>
                                                    <td>{{ $recurso->titulo ?? 'Sin título' }}</td>
                                                    <td>{{ Str::limit($recurso->descripcion ?? 'Sin descripción', 100) }}</td>
                                                    <td>{{ $recurso->deleted_at->format('d/m/Y H:i') }}</td>
                                                    <td>
                                                        <form action="{{ route('cursos.restaurar-elemento') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="tipo" value="recurso">
                                                            <input type="hidden" name="id" value="{{ $recurso->id }}">
                                                            <input type="hidden" name="curso_id" value="{{ $curso->id }}">
                                                            <button type="submit" class="btn btn-sm btn-success">Restaurar</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="mt-3">No hay recursos eliminados para este curso.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
