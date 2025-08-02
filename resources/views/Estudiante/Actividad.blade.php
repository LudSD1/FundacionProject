@section('content')
<div class="container mt-4">
    <a href="{{ route('Curso', encrypt($actividades->subtema->tema->curso_id))}}" class="btn btn-primary mb-4">
        <i class="bi bi-arrow-left-circle"></i> Volver
    </a>

    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="card-title">{{ $actividades->titulo_tarea }}</h2>
            <p class="card-text">{{ $actividades->descripcion }}</p>

            {{-- Archivo relacionado (comentado actualmente) --}}
            {{--
            @if ($actividades->archivo != '')
            <div class="mt-3">
                <h5>Archivo de Tarea</h5>
                <a href="{{ asset('storage/'. $tareas->archivoTarea) }}" class="btn btn-outline-secondary">Ver Recurso</a>
            </div>
            @endif
            --}}

            <div class="row mt-4">
                <div class="col-md-6">
                    <h5><i class="bi bi-calendar-check"></i> Fecha de habilitación:</h5>
                    <p>{{ $actividades->fecha_inicio }}</p>
                </div>
                <div class="col-md-6">
                    <h5><i class="bi bi-calendar-x"></i> Fecha de vencimiento:</h5>
                    <p>{{ $actividades->fecha_limite }}</p>
                </div>
            </div>

            <div class="alert alert-info mt-3">
                <strong>Ponderación:</strong> La {{ $actividades->tipoActividad->nombre }} vale hasta {{ $actividades->getPuntajeMaximoAttribute() }} puntos.
            </div>

            @if (auth()->user()->hasRole('Estudiante'))

                {{-- Calificación --}}
                @forelse ($notas as $nota)
                    @if ($nota->inscripcion->estudiante_id == auth()->user()->id && $nota->actividad_id == $actividades->id)
                        <div class="alert alert-success">
                            <h4>Calificado</h4>
                            <p><strong>Nota:</strong> {{ $nota->nota }}</p>
                            <h5>Retroalimentación:</h5>
                            <blockquote class="blockquote">
                                "{{ $nota->retroalimentacion }}"
                            </blockquote>
                        </div>
                    @endif
                @empty
                    <div class="alert alert-warning">
                        <strong>Sin calificar</strong>
                    </div>
                @endforelse

                {{-- Entregas --}}
                <div class="mt-4">
                    <h4>Entregas</h4>
                    @forelse ($actividades->entregas as $entrega)
                        @if ($entrega->user_id == auth()->user()->id)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <p><strong>Fecha de Entrega:</strong> {{ $entrega->fecha_entrega ?? 'Sin fecha' }}</p>
                                    <p><strong>Comentario:</strong> {{ $entrega->comentario ?? 'Sin comentario' }}</p>
                                    <a href="{{ asset('storage/' . $entrega->archivo) }}" target="_blank" class="btn btn-sm btn-info">Ver Archivo</a>
                                    @if (!$actividades->fecha_limite || now() <= \Carbon\Carbon::parse($actividades->fecha_limite))
                                        <a href="{{ route('quitarEntrega', encrypt($entrega->id)) }}" class="btn btn-sm btn-danger ml-2">Quitar Entrega</a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="alert alert-info">Aún no se hicieron entregas</div>
                    @endforelse
                </div>

                {{-- Formulario de entrega --}}
                @if (
                    ($actividades->subtema->tema->curso->fecha_fin && \Carbon\Carbon::now() > \Carbon\Carbon::parse($actividades->subtema->tema->curso->fecha_fin)) ||
                    ($actividades->fecha_limite && \Carbon\Carbon::now() > \Carbon\Carbon::parse($actividades->fecha_limite))
                )
                    <div class="alert alert-secondary mt-4">
                        Esta actividad ya no recibe entregas
                    </div>
                @else
                    <form action="{{ route('subirArchivo', encrypt($actividades->id)) }}" method="POST" enctype="multipart/form-data" class="mt-4">
                        @csrf
                        <h5>Subir nueva entrega</h5>
                        <input type="hidden" name="actividad_id" value="{{ $actividades->id }}">
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

                        <div class="form-group">
                            <label for="archivo">Archivo</label>
                            <input type="file" name="archivo" class="form-control-file" required>
                        </div>

                        <div class="form-group mt-3">
                            <label for="comentario">Comentario (opcional)</label>
                            <textarea name="comentario" class="form-control" rows="3" placeholder="Escribe un comentario"></textarea>
                        </div>

                        <button type="submit" class="btn btn-dark mt-3">Enviar Tarea</button>
                    </form>
                @endif

            @endif
        </div>
    </div>
</div>
@endsection

@include('layout')
