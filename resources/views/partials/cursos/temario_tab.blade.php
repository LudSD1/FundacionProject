<div class="tab-pane fade show active" id="tab-actividades" role="tabpanel" aria-labelledby="temario-tab">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Contenido del Curso</h3>
        @if(auth()->user()->hasRole('Docente') && $cursos->docente_id == auth()->user()->id)
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTema">
                <i class="fas fa-plus me-2"></i>Añadir Contenido
            </button>
        @endif
    </div>

    <div class="container">
        <ul class="nav nav-tabs nav-fill mb-4" id="temasTabs" role="tablist">
            @foreach($temas as $index => $tema)
                @php
                    $estaDesbloqueado = auth()->user()->hasRole('Docente') ||
                                      (auth()->user()->hasRole('Estudiante') && $tema->estaDesbloqueado($inscritos2->id));
                @endphp
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $index === 0 ? 'active' : '' }} {{ !$estaDesbloqueado ? 'disabled' : '' }}"
                            id="tema-{{ $tema->id }}-tab"
                            data-bs-toggle="{{ $estaDesbloqueado ? 'tab' : 'popover' }}"
                            data-bs-target="{{ $estaDesbloqueado ? '#tema-' . $tema->id : '' }}"
                            type="button" role="tab"
                            aria-controls="{{ $estaDesbloqueado ? 'tema-' . $tema->id : '' }}"
                            {{ !$estaDesbloqueado ? 'data-bs-content="Debes completar el tema anterior para desbloquear este."' : '' }}
                            data-bs-placement="top">
                        Tema {{ $loop->iteration }} {{ !$estaDesbloqueado ? '🔒' : '' }}
                    </button>
                </li>
            @endforeach
        </ul>

        <div class="tab-content" id="temasContent">
            @forelse($temas as $index => $tema)
                @include('partials.cursos.tema_item', ['tema' => $tema, 'index' => $index])
            @empty
                <div class="card mb-3">
                    <div class="card-body">
                        <h5>NO HAY TEMAS DISPONIBLES</h5>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>