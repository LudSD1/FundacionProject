@section('titulo')

Lista de Tareas Eliminadas

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
                            <th scope="col">Evaluaciones</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse ($tareas as $tareas)
                        <tr>

                            <td scope="row">

                                {{ $loop->iteration }}

                            </td>
                            <td scope="row">
                                {{$tareas->titulo_tarea}}

                            </td>

                            <td>


                                <a href="{{ route('restaurarTarea', [$tareas->id])}}">Restaurar Tarea</a>

                            </td>
                        </tr>


                        @empty
                        <tr>

                            <td>

                                <h4>No hay tareas eliminadas</h4>

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
