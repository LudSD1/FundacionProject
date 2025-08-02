@section('titulo')
    Lista de Recursos Eliminados
@endsection




@section('content')
<div class="container my-5">
    <!-- Botón volver -->
    <div class="mb-4">
      <a href="javascript:history.back()" class="btn btn-outline-primary">
        <i class="bi bi-arrow-left"></i> Volver
      </a>
    </div>

    <!-- Barra de búsqueda -->
    <div class="row justify-content-end mb-4">
      <div class="col-md-6 col-lg-4">
        <form class="input-group">
          <span class="input-group-text"><i class="bi bi-search"></i></span>
          <input type="text" class="form-control" placeholder="Buscar recurso...">
        </form>
      </div>
    </div>

    <!-- Tabla de recursos eliminados -->
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Nombre del Recurso</th>
            <th scope="col" class="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($recursos as $recursos)
            @if ($recursos->cursos_id == $cursos->id)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $recursos->nombreRecurso }}</td>
                <td class="text-end">
                  <a href="{{ route('RestaurarRecurso', encrypt($recursos->id)) }}" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-arrow-clockwise"></i> Restaurar
                  </a>
                </td>
              </tr>
            @endif
          @empty
            <tr>
              <td colspan="3" class="text-center">
                <div class="alert alert-info my-3">
                  <i class="bi bi-info-circle"></i> No hay recursos eliminados.
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Mensaje de éxito -->
    @if (session('success'))
      <div class="alert alert-success mt-4">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
      </div>
    @endif
  </div>
</div>

@endsection

@include('layout')
