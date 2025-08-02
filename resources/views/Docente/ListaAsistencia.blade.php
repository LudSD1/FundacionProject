@extends('layout')

@section('titulo', 'Lista de Asistencia: ' . $cursos->nombreCurso)

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <a href="{{route('Curso', encrypt($cursos->id)) }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <h6 class="m-0 font-weight-bold text-primary">Registrar Asistencia</h6>
            <div>
                <a href="{{ route('darasistencias', encrypt($cursos->id)) }}" class="btn btn-info">
                    <i class="fas fa-user-edit"></i> Asistencia Personalizada
                </a>
            </div>
            <div>
                <a href="{{ route('historialAsistencias', encrypt($cursos->id)) }}" class="btn btn-info">
                    <i class="fas fa-user-edit"></i> Historial de Asistencias
                </a>
            </div>
        </div>

        <div class="card-body">

            <form action="{{ route('darasistenciasPostMultiple', encrypt($cursos->id)) }}" method="POST">
                @csrf
                <div class="form-row align-items-center mb-4">
                    <div class="col-md-4">
                        <label for="fecha_asistencia">Fecha:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                            </div>
                            <input type="date" class="form-control" id="fecha_asistencia" name="fecha_asistencia"
                                   value="{{ now()->format('Y-m-d') }}"
                                   @if($cursos->fecha_fin && now() > $cursos->fecha_fin) disabled @endif>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable">
                        <thead class="">
                            <tr>
                                <th width="5%">#</th>
                                <th width="40%">Estudiante</th>
                                <th width="40%">Tipo de Asistencia</th>
                                <th width="15%">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inscritos as $index => $inscrito)
                                @if($inscrito->cursos_id == $cursos->id)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ $inscrito->estudiantes->name }}
                                            {{ $inscrito->estudiantes->lastname1 }}
                                            {{ $inscrito->estudiantes->lastname2 }}
                                        </td>
                                        <td>
                                            <input type="hidden" name="asistencia[{{ $index }}][inscritos_id]"
                                                   value="{{ $inscrito->id }}">
                                            <input type="hidden" name="asistencia[{{ $index }}][curso_id]"
                                                   value="{{ $cursos->id }}">

                                            <select name="asistencia[{{ $index }}][tipo_asistencia]"
                                                    class="form-control attendance-select"
                                                    @if($cursos->fecha_fin && now() > $cursos->fecha_fin) disabled @endif>
                                                <option value="">Seleccione...</option>
                                                <option value="Presente">Presente</option>
                                                <option value="Retraso">Retraso</option>
                                                <option value="Licencia">Licencia</option>
                                                <option value="Falta">Falta</option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <span class="">Pendiente</span>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="fas fa-users-slash fa-2x mb-2"></i>
                                        <h5>No hay estudiantes inscritos</h5>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(auth()->user()->hasRole('Docente') && (!$cursos->fecha_fin || now() <= $cursos->fecha_fin))
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Guardar Asistencias
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Set current date
    function getCurrentDate() {
        const today = new Date();
        const year = today.getFullYear();
        let month = today.getMonth() + 1;
        let day = today.getDate();

        month = month < 10 ? `0${month}` : month;
        day = day < 10 ? `0${day}` : day;

        return `${year}-${month}-${day}`;
    }

    document.getElementById('fecha_asistencia').value = getCurrentDate();

    // Cambiar color del select según la asistencia
    document.querySelectorAll('.attendance-select').forEach(select => {
        select.addEventListener('change', function() {
            this.classList.remove('border-success', 'border-warning', 'border-info', 'border-danger');

            if(this.value === 'Presente') {
                this.classList.add('border-success');
            } else if(this.value === 'Retraso') {
                this.classList.add('border-warning');
            } else if(this.value === 'Licencia') {
                this.classList.add('border-info');
            } else if(this.value === 'Falta') {
                this.classList.add('border-danger');
            }
        });
    });
});
</script>

<style>
.attendance-select.border-success {
    border-color: #28a745 !important;
}
.attendance-select.border-warning {
    border-color: #ffc107 !important;
}
.attendance-select.border-info {
    border-color: #17a2b8 !important;
}
.attendance-select.border-danger {
    border-color: #dc3545 !important;
}
</style>
@endsection
