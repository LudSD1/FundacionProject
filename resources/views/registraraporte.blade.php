@section('titulo')
    Registrar Aporte
@endsection



@section('content')


<div class="container mt-5">
    <div class="card p-4 shadow">
        <h1 class="mb-4 text-center">Tabla de Aportes/Pagos</h1>

        <form action="{{ route('registrarpagopost') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Nombre / Razón Social -->
            <div class="mb-3">
                <label class="form-label">Nombre / Razón Social:</label>
                <input type="text" name="pagante" class="form-control" required>
            </div>

            <!-- CI -->
            <div class="mb-3">
                <label class="form-label">CI:</label>
                <input type="number" name="paganteci" class="form-control" required>
            </div>

            <hr>

            <!-- Campo oculto con ID del estudiante -->
            <input type="hidden" id="estudiante_id" name="estudiante_id" value="{{ auth()->user()->id }}">

            <div class="row">
                <!-- Curso -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Curso:</label>
                    <select name="curso_id" class="form-select">
                        @forelse ($cursos as $curso)
                            <option value="{{ $curso->id }}">{{ $curso->nombreCurso }}</option>
                        @empty
                            <option value="">No hay cursos disponibles</option>
                        @endforelse
                    </select>
                </div>

                <!-- Nombre del Estudiante -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nombre del Estudiante:</label>
                    <select name="estudiante_id" class="form-select">
                        @forelse ($estudiantes as $estudiante)
                            <option value="{{ $estudiante->id }}">{{ $estudiante->name }} {{ $estudiante->lastname1 }} {{ $estudiante->lastname2 }}</option>
                        @empty
                            <option value="">No hay estudiantes registrados</option>
                        @endforelse
                    </select>
                </div>
            </div>

            <div class="row">
                <!-- Monto a Pagar -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Monto a Pagar:</label>
                    <div class="input-group">
                        <input type="number" name="montopagar" class="form-control" min="1" step="any" required>
                        <span class="input-group-text">Bs.</span>
                    </div>
                </div>

                <!-- Monto Cancelado -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Monto Cancelado:</label>
                    <div class="input-group">
                        <input type="number" name="montocancelado" class="form-control" min="1" step="any" required>
                        <span class="input-group-text">Bs.</span>
                    </div>
                </div>
            </div>

            <!-- Descripción -->
            <div class="mb-3">
                <label class="form-label">Descripción:</label>
                <textarea id="descripcion" name="descripcion" class="form-control" rows="4" required></textarea>
            </div>

            <!-- Botón de enviar -->
            <div class="text-center">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save"></i> Guardar
                </button>
            </div>
        </form>

    </div>
</div>





@endsection




@include('layout')
