@section('titulo')
    Registrar Aporte
@endsection

@section('content')
<div class="container my-4">
    <div class="tbl-card shadow-lg">

        <div class="tbl-card-hero">
            <div class="tbl-hero-left">
                <div class="tbl-hero-eyebrow">
                    <i class="bi bi-cash-stack"></i> Nuevo Aporte
                </div>
                <h2 class="tbl-hero-title">Registrar Aporte/Pago</h2>
                <p class="tbl-hero-sub">
                    Registra un nuevo aporte o pago en el sistema.
                </p>
            </div>
        </div>

        <div class="p-4 p-md-5">
            <form action="{{ route('registrarpagopost') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="step-header mb-4">
                    <h4 class="text-primary fw-bold mb-1">
                        <i class="bi bi-person-fill me-2"></i>Datos del Pagador
                    </h4>
                    <p class="text-muted small">Información básica del pagador.</p>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small text-uppercase">Nombre / Razón Social</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-person text-primary"></i></span>
                            <input type="text" name="pagante" class="form-control bg-light" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small text-uppercase">CI</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-credit-card text-primary"></i></span>
                            <input type="number" name="paganteci" class="form-control bg-light" required>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="step-header mb-4">
                    <h4 class="text-primary fw-bold mb-1">
                        <i class="bi bi-book-fill me-2"></i>Datos del Curso y Estudiante
                    </h4>
                    <p class="text-muted small">Selecciona el curso y el estudiante.</p>
                </div>

                <input type="hidden" id="estudiante_id_hidden" name="estudiante_id_hidden" value="{{ auth()->user()->id }}">

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small text-uppercase">Curso</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-book text-primary"></i></span>
                            <select name="curso_id" class="form-select bg-light">
                                @forelse ($cursos as $curso)
                                    <option value="{{ $curso->id }}">{{ $curso->nombreCurso }}</option>
                                @empty
                                    <option value="">No hay cursos disponibles</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small text-uppercase">Nombre del Estudiante</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-person-badge text-primary"></i></span>
                            <select name="estudiante_id" class="form-select bg-light">
                                @forelse ($estudiantes as $estudiante)
                                    <option value="{{ $estudiante->id }}">{{ $estudiante->name }} {{ $estudiante->lastname1 }} {{ $estudiante->lastname2 }}</option>
                                @empty
                                    <option value="">No hay estudiantes registrados</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="step-header mb-4">
                    <h4 class="text-primary fw-bold mb-1">
                        <i class="bi bi-currency-dollar me-2"></i>Montos del Pago
                    </h4>
                    <p class="text-muted small">Ingresa los montos del aporte.</p>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small text-uppercase">Monto a Pagar</label>
                        <div class="input-group">
                            <input type="number" name="montopagar" class="form-control bg-light" min="1" step="any" required>
                            <span class="input-group-text">Bs.</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small text-uppercase">Monto Cancelado</label>
                        <div class="input-group">
                            <input type="number" name="montocancelado" class="form-control bg-light" min="1" step="any" required>
                            <span class="input-group-text">Bs.</span>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small text-uppercase">Descripción</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-text-paragraph text-primary"></i></span>
                        <textarea id="descripcion" name="descripcion" class="form-control bg-light" rows="4" required></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-center mt-5 pt-4 border-top">
                    <button type="submit" class="tbl-hero-btn tbl-hero-btn-primary px-5 py-2">
                        <i class="bi bi-save me-2"></i> Guardar Aporte
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@include('layout')
