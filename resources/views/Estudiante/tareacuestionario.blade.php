@section('titulo')
    Tarea {{ $tareas->titulo_tarea }}
@endsection


@section('content')
<div class="border p-3">
<a href="javascript:history.back()" class="btn btn-primary">
    &#9668; Volver
</a>
<br>
<br>
<div class="container">
    <h1>{{$tareas->titulo_tarea}}</h1>
    <p>{!! preg_replace('/(https?:\/\/\S+)/', '<a href="$1" target="_blank">$1</a>', $tareas->descripcionTarea) !!} </p>

    @if ($tareas->archivoTarea != '')
    <div class="archivo-tarea">
        <h3>Archivo de Tarea</h3>
        <a href="{{asset('storage/'. $tareas->archivoTarea)}}">VER RECURSO</a>
    </div>
    @endif


    <div class="fechas">
        <h3>Fecha de habilitación {{$tareas->fecha_habilitacion}}</h3>
        <h3>Fecha de vencimiento {{$tareas->fecha_vencimiento}}</h3>
    </div>

    <div class="ponderacion">
        <h3>Ponderación de Tarea es sobre {{$tareas->puntos}}</h3>
    </div>

    @if (auth()->user()->hasRole('Estudiante'))
        @forelse ($notas as $nota)
            @if ($nota->inscripcion->estudiante_id == auth()->user()->id && $nota->tarea_id == $tareas->id)
                <div class="calificacion">
                    <h2 class="badge-success">CALIFICADO NOTA: {{$nota->nota}}</h2>
                    <h3>Retroalimentación</h3>
                    <h5>"{{$nota->retroalimentacion}}"</h5>
                </div>
                <br>
            @endif
        @empty
            <h2 class="badge-danger">Sin calificar</h2>
        @endforelse

        <div class="entregas">
            <h2>ENTREGAS</h2>
            @forelse ($entregas as $entrega)
                @if ($entrega->estudiante_id == auth()->user()->id && $entrega->tarea_id == $tareas->id)
                    <br>
                    <a href="{{asset('storage/'. $entrega->ArchivoEntrega)}}">VER ENTREGA</a>
                    <br>

                    @if ($tareas->cursos->fecha_fin && \Carbon\Carbon::now() > \Carbon\Carbon::parse($tareas->cursos->fecha_fin) || $tareas->fecha_vencimiento && \Carbon\Carbon::now() > \Carbon\Carbon::parse($tareas->fecha_vencimiento))

                    @else
                    <a href="{{route('quitarEntrega', encrypt($entrega->id))}}">Quitar Entrega</a>
                    @endif

                    <br>
                @endif
            @empty
                <h2 class="badge badge-info">Aún no se hicieron entregas</h2>
            @endforelse
        </div>

        <hr>


        @if ($tareas->cursos->fecha_fin && \Carbon\Carbon::now() > \Carbon\Carbon::parse($tareas->cursos->fecha_fin) || $tareas->fecha_vencimiento && \Carbon\Carbon::now() > \Carbon\Carbon::parse($tareas->fecha_vencimiento))
        <h2>Esta acttividad ya no recibe entregas</h2>

        @else

        <form action="{{route('subirTarea')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <h3>
                <input type="text" name="tarea_id" value="{{$tareas->id}}" hidden>
                <input type="text" name="estudiante_id" value="{{auth()->user()->id}}" hidden>
                Agregar Archivo
            </h3>

            <input type="file" name="entrega">
            <br>
            <br>
            <input type="submit" class="btn btn-dark" value="Enviar Tarea">
        </form>

        @endif


        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <h2 class="bg-danger alert-danger">{{$error}}</h2>
            @endforeach
        @endif
    @endif

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif


</div>

@endsection

@include('layout')
