@extends('layout')

@section('content')
<div class="container">
    <h2>Boletín del estudiante: {{ $inscritos->estudiantes->name }} {{ $inscritos->estudiantes->lastname1 }} {{ $inscritos->estudiantes->lastname2 }}</h2>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Resumen</h5>
            <p><strong>Promedio de actividades:</strong> {{ $resumen['promedio_actividades'] }}</p>
            <p><strong>Porcentaje de asistencia:</strong> {{ $resumen['porcentaje_asistencia'] }}%</p>
            <p><strong>Nota final:</strong> {{ $resumen['nota_final'] }}</p>
            <p><strong>Estado:</strong> {{ $resumen['estado'] }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('boletinPost' , encrypt($inscritos->id)) }}">
        @csrf

        <input type="hidden" name="estudiante" value="{{ $inscritos->id }}">
        <input type="hidden" name="notafinal" value="{{ $resumen['nota_final'] }}">

        <div class="form-group mb-3">
            <label for="comentario">Comentario del boletín</label>
            <textarea name="comentario" id="comentario" class="form-control" rows="3" required></textarea>
        </div>



        <button type="submit" class="btn btn-primary">Guardar Boletín</button>
    </form>

    <hr>

    <h4>Detalle de Actividades</h4>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Tema</th>
                <th>Subtema</th>
                <th>Actividad</th>
                <th>Tipo</th>
                <th>Nota</th>
                <th>Estado</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($actividadesData as $actividad)
                <tr>
                    <td>{{ $actividad['tema'] }}</td>
                    <td>{{ $actividad['subtema'] }}</td>
                    <td>{{ $actividad['actividad'] }}</td>
                    <td>{{ $actividad['tipo'] }}</td>
                    <td>{{ $actividad['nota'] }}</td>
                    <td>{{ $actividad['estado'] }}</td>
                    <td>{{ $actividad['fecha'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
