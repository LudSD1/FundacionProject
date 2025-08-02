
@section('titulo')
Editar Foro
@endsection


@section('content')

<div class="container my-5">
    <div class="mb-4">
      <a href="javascript:history.back()" class="btn btn-outline-primary">
        <i class="bi bi-arrow-left"></i> Volver
      </a>
    </div>

    <div class="card shadow-sm">
      <div class="card-header bg-warning text-dark">
        <h4 class="mb-0">Editar Foro</h4>
      </div>
      <div class="card-body">
        <form method="post" action="{{ route('EditarForoPost', encrypt($foro->id)) }}">
          @csrf
          <input type="hidden" name="idForo" value="{{ $foro->id }}">
          <input type="hidden" name="curso_id" value="{{ $foro->cursos_id }}">

          <div class="mb-3">
            <label for="nombreForo" class="form-label">Nombre del Foro</label>
            <input type="text" class="form-control" id="nombreForo" name="nombreForo" value="{{ $foro->nombreForo }}" placeholder="Introduce el nombre del foro">
          </div>

          <div class="mb-3">
            <label for="subtituloForo" class="form-label">Subtítulo</label>
            <input type="text" class="form-control" id="subtituloForo" name="SubtituloForo" value="{{ $foro->SubtituloForo }}" placeholder="Introduce un subtítulo opcional">
          </div>

          <div class="mb-3">
            <label for="descripcionForo" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcionForo" name="descripcionForo" rows="6" placeholder="Escribe aquí la descripción del foro">{{ trim($foro->descripcionForo) }}</textarea>
          </div>

          <div class="mb-4">
            <label for="fechaFin" class="form-label">Fecha de Finalización</label>
            <input type="date" class="form-control" id="fechaFin" name="fechaFin" value="{{ $foro->fechaFin }}">
          </div>

          <div class="d-grid">
            <button type="submit" class="btn btn-success">
              <i class="bi bi-pencil-square"></i> Guardar Cambios
            </button>
          </div>
        </form>

@endsection


@include('layout')
