@extends('Cursos')


@section('content')
<div class="container text-center">
    <h1>Escanea este código QR para inscribirte</h1>
    <p>Curso: <strong>{{ $curso->nombreCurso }}</strong></p>
    <div class="mt-4">
        {!! $qrCode !!}
    </div>
    <a href="{{ route('cursos.index') }}" class="btn btn-primary mt-3">Volver a los cursos</a>
</div>
@endsection
