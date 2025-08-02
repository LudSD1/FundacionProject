
@section('titulo')
Crear Foro
@endsection





@section('content')

<div class="container my-5">
    <div class="mb-4">
      <a href="javascript:history.back()" class="btn btn-outline-primary">
        <i class="bi bi-arrow-left"></i> Volver
      </a>
    </div>

    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Crear Foro</h4>
      </div>
      <div class="card-body">
        <form method="post" action="{{ route('CrearForoPost', encrypt($cursos->id)) }}">
          @csrf
          <input type="hidden" name="curso_id" value="{{ $cursos->id }}">

          <div class="mb-3">
            <label for="nombreForo" class="form-label">Nombre del Foro</label>
            <input type="text" class="form-control" id="nombreForo" name="nombreForo" placeholder="Introduce el nombre del foro">
          </div>

          <div class="mb-3">
            <label for="subtituloForo" class="form-label">Subtítulo</label>
            <input type="text" class="form-control" id="subtituloForo" name="SubtituloForo" placeholder="Introduce un subtítulo opcional">
          </div>

          <div class="mb-3">
            <label for="descripcionForo" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcionForo" name="descripcionForo" rows="6" placeholder="Escribe aquí la descripción del foro"></textarea>
          </div>

          <div class="mb-4">
            <label for="fechaFin" class="form-label">Fecha de Finalización</label>
            <input type="date" class="form-control" id="fechaFin" name="fechaFin">
          </div>

          <div class="d-grid">
            <button type="submit" class="btn btn-success">
              <i class="bi bi-save"></i> Guardar
            </button>
          </div>
        </form>

        @if ($errors->any())
          <div class="alert alert-danger mt-4">
            <h6>Se encontraron los siguientes errores:</h6>
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection


@include('layout')
