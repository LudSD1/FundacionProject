@section('titulo')
    Dar Asistencia
@endsection





@section('content')
    <div class="border p-3">
        <a href="javascript:history.back()" class="btn btn-primary">
            &#9668; Volver
        </a>
        <br>
        <form method="POST" action="{{ route('darasistenciasPostIndividual', encrypt($cursos->id)) }}">
            @csrf
            <div class="card-body p-3">
                <div class="row">
                    <input type="text" name="curso_id" value="{{ $cursos->id }}" hidden>
                    <div class="col-md-6 mb-md-0 mb-4">
                        <h3>Fecha</h3>
                        <div class="card card-body border card-plain border-radius-lg d-flex align-items-center flex-row">


                            <input type="date" name="fecha" id=""
                                @if ($cursos->fecha_fin && \Carbon\Carbon::now() > \Carbon\Carbon::parse($cursos->fecha_fin)) disabled
                        @else @endif>

                        </div>
                    </div>

                    <div class="col-md-6">
                        <h3>Estudiante</h3>
                        <div class="card card-body border card-plain border-radius-lg d-flex align-items-center flex-row">
                            <select name="estudiante" id="" class="mb-0 bg-transparent border-0"
                                @if ($cursos->fecha_fin && \Carbon\Carbon::now() > \Carbon\Carbon::parse($cursos->fecha_fin)) disabled
                            @else @endif>

                                @forelse ($inscritos as $inscritos)
                                    <option value="{{ $inscritos->id }}">
                                        {{ $inscritos->estudiantes->name . ' ' . $inscritos->estudiantes->lastname1 . ' ' . $inscritos->estudiantes->lastname2 }}
                                    </option>
                                @empty
                                    <option value="">
                                        NO HAY ESTUDIANTES REGISTRADOS
                                    </option>
                                @endforelse


                            </select>
                        </div>


                    </div>

                    <div class="col-md-6">
                        <h3>Tipo de Asistencia</h3>
                        <div class="card card-body border card-plain border-radius-lg d-flex align-items-center flex-row">
                            <select name="asistencia" class="form-control"
                                @if ($cursos->fecha_fin && \Carbon\Carbon::now() > \Carbon\Carbon::parse($cursos->fecha_fin)) disabled
                            @else @endif>
                                <option value="">Selecciona un tipo de asistencia</option>
                                <option value="Presente">Presente</option>
                                <option value="Retraso">Retraso</option>
                                <option value="Licencia">Licencia</option>
                                <option value="Falta">Falta</option>
                            </select>
                        </div>


                    </div>

                </div>
                <br><br>

                @if ($cursos->fecha_fin && \Carbon\Carbon::now() > \Carbon\Carbon::parse($cursos->fecha_fin))
                @else
                <input class="btn btn-lg btn-success" type="submit" value="DAR ASISTENCIA">
                @endif


            </div>
        </form>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <h2 class="bg-danger alert-danger">{{ $error }}</h2>
            @endforeach
        @endif


        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif



    @endsection

    @extends('layout')
