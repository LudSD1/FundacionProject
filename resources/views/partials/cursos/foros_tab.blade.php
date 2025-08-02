<div class="tab-pane fade" id="tab-foros" role="tabpanel" aria-labelledby="foros-tab">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Discusiones</h5>
            @if (auth()->user()->id == $cursos->docente_id )
            <div>

            <a href="{{ route('CrearForo', encrypt($cursos->id)) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Nuevo Tema
            </a>
            <a href="{{ route('forosE', encrypt($cursos->id)) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-trash"></i> Foros Eliminados
            </a>
            {{-- <a href="{{ route('eliminad', $cursos->id) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Nuevo Tema
            </a> --}}

            </div>

            @endif

        </div>
        <div class="card-body">
            @if($foros->count() > 0)
            <div class="list-group list-group-flush forum-list">
                @foreach($foros as $tema)
                <div class="list-group-item list-group-item-action p-0 mb-3 border rounded">
                    <a href="{{ route('foro', encrypt($tema->id)) }}" class="text-decoration-none text-reset p-3 d-block">
                        <div class="d-flex w-100 justify-content-between align-items-start">
                            <div class="flex-grow-1 me-3">
                                <div class="d-flex align-items-center mb-2">
                                    <h6 class="mb-0 fw-bold text-primary">{{ $tema->nombreForo }}</h6>
                                    @if($tema->foromensaje->count() > 0)
                                        <span class="badge bg-info ms-2">
                                            <i class="fas fa-comment-dots me-1"></i> {{ $tema->foromensaje->count() }}
                                        </span>
                                    @endif
                                </div>
                                <p class="mb-2 text-muted">{{ Str::limit($tema->contenido, 150) }}</p>

                                <div class="d-flex align-items-center text-muted small">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-clock me-1"></i>
                                        <span>{{ $tema->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex-shrink-0">
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-eye me-1"></i> {{ $tema->vistas_count ?? 0 }}
                                </span>
                            </div>
                        </div>
                    </a>

                    @if(auth()->user()->id == $cursos->docente_id)
                    <div class="bg-light p-2 border-top d-flex justify-content-end">
                        <a href="{{ route('EditarForo', encrypt($tema->id)) }}" class="btn btn-sm btn-outline-warning me-2"
                           data-bs-toggle="tooltip" title="Editar foro">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="{{ route('quitarForo', encrypt($tema->id)) }}"
                            class="btn btn-sm btn-outline-danger"
                            title="Eliminar foro"
                            onclick="confirmarEliminacion(event, this.href)">
                             <i class="fas fa-trash-alt"></i>
                         </a>
                    </div>
                    @endif
                    @hasrole('Estudiante')

                    {{-- <form class="m-2" method="POST" action="{{ route('foros.completar', $tema->id) }}">
                        @csrf
                        <input type="hidden" name="inscritos_id" value="{{ $inscritos2->id }}">
                        <button type="submit" class="btn btn-success btn-sm">Marcar como Completado</button>
                    </form> --}}


                    @endhasrole
                </div>
                @endforeach
            </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                    <h5>No hay temas de discusión</h5>
                </div>
            @endif
        </div>
    </div>
</div>


<script>
    function confirmarEliminacion(event, url) {
        event.preventDefault();

        Swal.fire({
            title: '¿Eliminar foro?',
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }
    </script>



