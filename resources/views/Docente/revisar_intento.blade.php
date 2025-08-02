@extends('layout')

@section('titulo', 'Revisar Intento')

@section('content')
<div class="container mt-4">
    <h2>Revisión del Intento</h2>
    <p><strong>Estudiante:</strong> {{ $intento->inscrito->estudiantes->name }} {{ $intento->inscrito->estudiantes->lastname1 }} {{ $intento->inscrito->estudiantes->lastname2 }}</p>
    <p><strong>Cuestionario:</strong> {{ $intento->cuestionario->actividad->titulo }}</p>
    <p><strong>Nota Actual:</strong> {{ $intento->nota }}</p>

    <h4>Respuestas:</h4>

    <!-- Navegación de Tabs -->
    <ul class="nav nav-tabs" id="respuestasTabs" role="tablist">
        @foreach ($intento->respuestasEst as $index => $respuesta)
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="tab-{{ $respuesta->id }}" data-bs-toggle="tab"
                    data-bs-target="#contenido-{{ $respuesta->id }}" type="button" role="tab"
                    aria-controls="contenido-{{ $respuesta->id }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    Pregunta {{ $loop->iteration }}
                </button>
            </li>
        @endforeach
    </ul>

    <!-- Contenido de Tabs -->
    <div class="tab-content" id="respuestasTabsContent">
        @foreach ($intento->respuestasEst as $index => $respuesta)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="contenido-{{ $respuesta->id }}"
                role="tabpanel" aria-labelledby="tab-{{ $respuesta->id }}">
                <div class="card mt-4 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Pregunta {{ $loop->iteration }}</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Contenido:</strong> {{ $respuesta->pregunta->enunciado }}</p>
                        <p><strong>Respuesta del Estudiante:</strong> {{ $respuesta->respuesta }}</p>
                        <p><strong>¿Es Correcta?:</strong>
                            <span class="badge {{ $respuesta->es_correcta ? 'bg-success' : 'bg-danger' }}">
                                {{ $respuesta->es_correcta ? 'Sí' : 'No' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <h4 class="mt-4">Actualizar Nota:</h4>
    <form method="POST" action="{{ route('cuestionarios.actualizarNota', [encrypt($intento->cuestionario_id), encrypt($intento->id)]) }}">
        @csrf
        <div class="mb-3">
            <label for="nota" class="form-label">Nueva Nota</label>
            <input type="number" class="form-control" id="nota" name="nota" value="{{ $intento->nota }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Nota</button>
    </form>
</div>
@endsection
