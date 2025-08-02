@section('titulo')

Lista de Foros Eliminados

@endsection





@section('content')
<div class="container my-5">
    <!-- Barra de búsqueda -->
    <div class="row justify-content-end mb-4">
      <div class="col-md-6 col-lg-4">
        <form class="input-group">
          <span class="input-group-text"><i class="bi bi-search"></i></span>
          <input type="text" class="form-control" placeholder="Buscar...">
        </form>
      </div>
    </div>

    <!-- Tabla de Foros Eliminados -->
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead >
          <tr>
            <th scope="col">#</th>
            <th scope="col">Nombre del Foro</th>
            <th scope="col" class="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($foro as $foro)
            @if ($foro->cursos_id == $cursos->id)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $foro->nombreForo }}</td>
              <td class="text-end">
                <a href="{{ route('restaurar', encrypt($foro->id)) }}" class="btn btn-outline-success btn-sm">
                  <i class="bi bi-arrow-clockwise"></i> Restaurar
                </a>
              </td>
            </tr>
            @endif
          @empty
            <tr>
              <td colspan="3" class="text-center">
                <div class="alert alert-info my-3">
                  <i class="bi bi-info-circle"></i> No hay foros eliminados.
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Mensaje de éxito -->
    @if(session('success'))
      <div class="alert alert-success mt-4">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
      </div>
    @endif
  </div>
@endsection

@include('layout')
