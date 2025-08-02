<div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="tema-{{ $tema->id }}" role="tabpanel" aria-labelledby="tema-{{ $tema->id }}-tab">
    <div class="card my-3">
        <div class="card-body">
            <h1>{{ $tema->titulo_tema }}</h1>

            @if($tema->imagen)
                <img class="img-fluid" src="{{ asset('storage/' . $tema->imagen) }}" alt="Imagen del tema" style="max-width: 500px; height: auto;">
            @endif

            <div class="my-3">
                <button class="btn btn-link" type="button" data-bs-toggle="collapse"
                        data-bs-target="#descripcionTema-{{ $tema->id }}" aria-expanded="false"
                        aria-controls="descripcionTema-{{ $tema->id }}">
                    Ver Descripción del Tema
                </button>
                <div class="collapse" id="descripcionTema-{{ $tema->id }}">
                    <div class="card card-body">
                        {!! nl2br(e($tema->descripcion)) !!}
                    </div>
                </div>
            </div>

            @if(auth()->user()->hasRole('Docente') && $cursos->docente_id == auth()->user()->id)
                <div class="mb-3">
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#modalSubtema-{{ $tema->id }}">
                        <i class="fas fa-plus me-1"></i> Agregar Subtema
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                            data-bs-target="#modalEditarTema-{{ $tema->id }}">
                        <i class="fas fa-edit me-1"></i> Editar Tema
                    </button>
                </div>
            @endif
            <div class="modal fade" id="modalEditarTema-{{ $tema->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-edit me-2"></i>Editar Tema: {{ $tema->titulo_tema }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="{{ route('temas.update', encrypt($tema->id)) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="titulo" class="form-label">Título*</label>
                                    <input type="text" class="form-control" name="titulo" value="{{ $tema->titulo_tema }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    <textarea class="form-control" name="descripcion" rows="3">{{ $tema->descripcion }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Imagen Actual</label>
                                    @if($tema->imagen)
                                        <img src="{{ asset('storage/'.$tema->imagen) }}" class="img-thumbnail mb-2" style="max-height: 150px;">
                                    @else
                                        <p class="text-muted">No hay imagen cargada</p>
                                    @endif
                                    <input type="file" class="form-control" name="imagen" accept="image/*">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i> Cancelar
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="accordion" id="subtemasAccordion-{{ $tema->id }}">
                @foreach($tema->subtemas as $subtemaIndex => $subtema)
                    @php
                        $desbloqueado = auth()->user()->hasRole('Docente') ||
                                      (auth()->user()->hasRole('Estudiante') && $subtema->estaDesbloqueado($inscritos2->id ?? null));
                    @endphp

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="subtemaHeading-{{ $subtema->id }}">
                            @if(!$desbloqueado && auth()->user()->hasRole('Estudiante'))
                                <button class="accordion-button collapsed" type="button" disabled>
                                    {{ $subtema->titulo_subtema }} <i class="fas fa-lock ms-2"></i>
                                </button>
                            @else
                                <button class="accordion-button {{ $subtemaIndex === 0 ? '' : 'collapsed' }}"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#subtemaCollapse-{{ $subtema->id }}"
                                        aria-expanded="{{ $subtemaIndex === 0 ? 'true' : 'false' }}"
                                        aria-controls="subtemaCollapse-{{ $subtema->id }}">
                                    {{ $subtema->titulo_subtema }}
                                </button>
                            @endif
                        </h2>

                        @if($desbloqueado || auth()->user()->hasRole('Docente'))
                            <div id="subtemaCollapse-{{ $subtema->id }}"
                                 class="accordion-collapse collapse {{ $subtemaIndex === 0 ? 'show' : '' }}"
                                 aria-labelledby="subtemaHeading-{{ $subtema->id }}"
                                 data-bs-parent="#subtemasAccordion-{{ $tema->id }}">
                                <div class="accordion-body">
                                    @include('partials.cursos.subtema_item', [
                                        'subtema' => $subtema,
                                        'tema' => $tema
                                    ])
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

