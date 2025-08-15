<div class="tab-pane fade" id="tab-recursos" role="tabpanel" aria-labelledby="recursos-tab">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0">Material de apoyo</h5>
        </div>
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            @if (auth()->user()->id == $cursos->docente_id)
                <div>

                    <a href="{{ route('CrearRecursos', encrypt($cursos->id)) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> Nuevo Recurso
                    </a>
                    <a href="{{ route('ListaRecursosEliminados', encrypt($cursos->id)) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-list me-1"></i> Recursos Eliminados
                    </a>
                </div>
            @endif

        </div>
        @if ($recursos->count() > 0)
            <div class="row g-3">
                @foreach ($recursos as $recurso)
                    <div class="col-md-5 col-lg-12">
                        <div class="card h-100 border">
                            <div class="card-body">
                                <div class="d-flex">

                                    <div class="flex-grow-1">
                                        <h6 class="card-title">{{ $recurso->nombreRecurso }}</h6>
                                        <p class="card-text text-muted small mb-2">{!! $recurso->descripcionRecursos !!}</p>
                                        {{-- <small class="text-muted">
                                            <i class="fas fa-file-{{ $recurso->tipo_icono }} me-1"></i>
                                            {{ strtoupper($recurso->extension) }}
                                            {{ $recurso->tamanio_formateado }}
                                        </small> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-top">
                                @if (isset($recurso->archivoRecurso))
                                    <!-- Método recomendado -->
                                    <a href="{{ route('recursos.descargar', encrypt($recurso->id)) }}" class="btn btn-primary">
                                        <i class="bi bi-download"></i> Descargar
                                    </a>
                                @endif
                                <a href="{{ route('editarRecursos', encrypt($recurso->id)) }}"
                                    class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit me-1"></i> Editar
                                </a>
                                <a href="{{ route('quitarRecurso', encrypt($recurso->id)) }}"
                                    class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash me-1"></i> Quitar
                                </a>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                <h5>No hay recursos disponibles</h5>
                <p class="text-muted">El instructor aún no ha subido material de apoyo</p>
            </div>
        @endif
    </div>
</div>
