@section('titulo')

Lista de Paticipantes {{$cursos->nombreCurso}}

@endsection




@section('content')
<div class="col-lg-12 row">

    <form class="navbar-search navbar-search form-inline mr-3 d-none d-md-flex ml-lg-auto">
        <div class="input-group input-group-alternative">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
          </div>
          <input class="form-control" placeholder="Buscar" type="text">
        </div>
    </form>
</div>
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Nro</th>
                            <th scope="col">Nombre y Apellidos</th>
                            <th scope="col">Celular</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse ($inscritos as $inscritos)
                        @if ($inscritos->cursos_id == $cursos->id)
                        <tr>

                            <td scope="row">

                                {{ $loop->iteration }}

                            </td>
                            <td scope="row">
                                {{ $inscritos->estudiantes->name }}
                                {{ $inscritos->estudiantes->lastname1 }}
                                {{ $inscritos->estudiantes->lastname2 }}
                            </td>
                            <td>
                                {{ $inscritos->estudiantes->Celular }}
                            </td>

                            <td>

                                <a href="{{ route('perfil', [encrypt($inscritos->estudiantes->id)])}}">Ver Más</a> /
                                <a href="{{ route('quitar', [encrypt($inscritos->id)])}}">Quitar incscripciÓn</a>/
                                <a href="{{ route('boletin', [encrypt($inscritos->id)])}}">Ver Boletín</a>

                            </td>
                        </tr>
                        @endif


                        @empty
                        <tr>

                            <td>

                                <h4>NO HAY ALUMNOS RETIRADOS</h4>

                            </td>
                        </tr>




                        @endforelse


                    </tbody>
                </table>

                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

@endsection

@include('layout')
