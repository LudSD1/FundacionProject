@extends('layout')

@section('titulo')
    Calificación de Actividad: {{ $actividad->titulo }}
@endsection
@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <a href="javascript:history.back()" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <h6 class="m-0 font-weight-bold text-primary">{{$actividad->tipoActividad->nombre}}: {{ $actividad->titulo }}</h6>
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Buscar estudiante..."
                           id="searchInput">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">

            <form action="{{ route('calificarT', encrypt($actividad->id)) }}" method="POST" id="calificationForm">
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead class="thead-dark">
                            <tr>
                                <th width="5%">#</th>
                                <th width="30%">Estudiante</th>
                                <th width="15%">Calificación (0-{{ $actividad->getPuntajeMaximoAttribute() }})</th>
                                <th width="25%">Entrega</th>
                                <th width="25%">Retroalimentación</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($inscritos as $index => $inscrito)
                            @php
                                $notaExistente = $nota->where('inscripcion_id', $inscrito->id)->where('actividad_id', $actividad->id)->first();
                                $entrega = $entregas->firstWhere('user_id', $inscrito->estudiante_id);

                            @endphp

                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ $inscrito->estudiantes->name }}
                                    {{ $inscrito->estudiantes->lastname1 }}
                                    {{ $inscrito->estudiantes->lastname2 }}
                                </td>
                                <td>
                                    <input type="number" class="form-control calification-input"
                                           name="entregas[{{$index}}][notaTarea]"
                                           min="0" max="{{$actividad->puntos}}"
                                           value="{{ $notaExistente->nota ?? 0 }}"
                                           {{ $vencido ? 'disabled' : 'required' }}>

                                    <input type="hidden" name="entregas[{{$index}}][id]"
                                           value="{{ $notaExistente->id ?? '' }}">
                                    <input type="hidden" name="entregas[{{$index}}][id_tarea]"
                                           value="{{ $actividad->id }}">
                                    <input type="hidden" name="entregas[{{$index}}][id_inscripcion]"
                                           value="{{ $inscrito->id }}">
                                </td>
                                <td>
                                    @if($entrega)
                                        <a href="{{ asset('storage/' . $entrega->archivo) }}"
                                           class="btn btn-sm btn-info" target="_blank" data-toggle="tooltip"
                                           title="Ver entrega">
                                            <i class="fas fa-eye"></i> Ver Tarea
                                        </a>
                                    @else
                                    <span class="badge bg-warning text-dark">No entregado</span>
                                    @endif
                                </td>
                                <td>
                                    @if($notaExistente && $notaExistente->retroalimentacion)
                                        <small class="text-muted">Anterior: {{ $notaExistente->retroalimentacion }}</small>
                                        <br>
                                    @endif

                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#feedbackModal{{$index}}">
                                        <i class="fas fa-comment"></i> Retroalimentar
                                    </button>

                                    <div class="modal fade" id="feedbackModal{{$index}}" tabindex="-1" aria-labelledby="feedbackModalLabel{{$index}}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="feedbackModalLabel{{$index}}">Retroalimentación para {{ $inscrito->estudiantes->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <textarea class="form-control feedback-textarea" name="entregas[{{$index}}][retroalimentacion]" rows="5" placeholder="Escribe la retroalimentación aquí...">{{ $notaExistente->retroalimentacion ?? '' }}</textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                    <button type="button" class="btn btn-primary save-feedback" data-bs-dismiss="modal">Guardar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                @if(!$vencido)
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save"></i> Guardar Calificaciones
                        </button>
                    </div>
                @else
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle"></i> El período de calificación ha finalizado.
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
    // Búsqueda en tiempo real
    $("#searchInput").on("keyup", function() {
        const value = $(this).val().toLowerCase();
        $("#dataTable tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Inicializar tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Auto-guardado en localStorage
    function saveToLocalStorage() {
        $('.calification-input').each(function() {
            localStorage.setItem($(this).attr('name'), $(this).val());
        });

        $('.feedback-textarea').each(function() {
            const key = $(this).data('storage-key');
            if (key) {
                localStorage.setItem(key, $(this).val());
            }
        });

        window.isFormModified = true;
    }

    // Cargar datos guardados
    function loadFromLocalStorage() {
        $('.calification-input').each(function() {
            const savedValue = localStorage.getItem($(this).attr('name'));
            if (savedValue !== null) {
                $(this).val(savedValue);
            }
        });

        $('.feedback-textarea').each(function() {
            const key = $(this).data('storage-key');
            if (key) {
                const savedValue = localStorage.getItem(key);
                if (savedValue !== null) {
                    $(this).val(savedValue);
                }
            }
        });
    }

    // Event listeners
    $('.calification-input, .feedback-textarea').on('input', saveToLocalStorage);
    $('.save-feedback').click(saveToLocalStorage);

    // Cargar datos al iniciar
    loadFromLocalStorage();

    // Confirmar antes de salir si hay cambios
    window.addEventListener('beforeunload', function(e) {
        if (window.isFormModified) {
            e.preventDefault();
            e.returnValue = 'Tiene cambios sin guardar. ¿Está seguro de que quiere salir?';
            return e.returnValue;
        }
    });

    // Limpiar localStorage al enviar el formulario
    $('#calificationForm').submit(function() {
        $('.calification-input').each(function() {
            localStorage.removeItem($(this).attr('name'));
        });

        $('.feedback-textarea').each(function() {
            const key = $(this).data('storage-key');
            if (key) {
                localStorage.removeItem(key);
            }
        });

        window.isFormModified = false;
    });
});
</script>
@endsection
