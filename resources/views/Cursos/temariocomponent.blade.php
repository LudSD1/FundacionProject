@if ($cursos->tipo == 'curso')
    <section class="mt-5" id="temario">
        <div class="container">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-light">
                        <h3 class="mb-0">
                            <i class="bi bi-journal-text text-primary me-2"></i>Temario del Curso
                        </h3>
                    </div>
                    <div class="card-body">
                        @forelse ($cursos->temas->sortBy('orden') as $i => $tema)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">{{ $tema->titulo_tema }}</h5>
                                    <button class="btn btn-sm btn-outline-primary" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#descripcionTema{{ $i }}"
                                        aria-expanded="false" aria-controls="descripcionTema{{ $i }}">
                                        Ver descripción
                                    </button>
                                </div>
                                <div class="collapse mt-2" id="descripcionTema{{ $i }}">
                                    <div class="card card-body">
                                        {{ $tema->descripcion }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">No hay temas registrados para este curso.</p>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </section>
@else
    <section>
        <div class="container">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-header bg-light">
                            <h3 class="mb-0">
                                <i class="bi bi-people text-primary me-2"></i>Facilitadores del Evento
                            </h3>
                        </div>
                        <div class="card-body">
                            @if ($cursos->expositores->isNotEmpty())
                                <div id="expositoresCarousel" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">

                                        @foreach ($cursos->expositores as $index => $expositor)
                                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                <div class="d-flex flex-column align-items-center text-center">
                                                    {{-- Imagen del expositor (usa una imagen por defecto si no tiene) --}}

                                                    <img src="{{ $expositor->imagen ? asset('storage/' . $expositor->imagen) : asset('assets2/img/talker.png') }}"
                                                        class="rounded-circle mb-3" alt="{{ $expositor->nombre }}"
                                                        style="width: 150px; height: 150px; object-fit: cover;">


                                                    {{-- Información del expositor --}}
                                                    <h5 class="fw-bold">{{ $expositor->nombre }}</h5>
                                                    <p class="text-muted mb-1">
                                                        {{ $expositor->pivot->cargo ?? 'Cargo no especificado' }}
                                                    </p>
                                                    <p class="mb-0"><strong>Tema:</strong>
                                                        {{ $expositor->pivot->tema ?? 'Tema no definido' }}</p>
                                                    {{-- <p class="mb-0"><strong>Fecha:</strong>
                                                            {{ \Carbon\Carbon::parse($expositor->pivot->fecha_presentacion)->format('d/m/Y') ?? 'No asignada' }}
                                                        </p> --}}
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>

                                    {{-- Controles --}}
                                    <button class="carousel-control-prev" type="button"
                                        data-bs-target="#expositoresCarousel" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Anterior</span>
                                    </button>
                                    <button class="carousel-control-next" type="button"
                                        data-bs-target="#expositoresCarousel" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Siguiente</span>
                                    </button>
                                </div>
                            @else
                                <p class="text-muted">No hay expositores asignados.</p>
                            @endif
                        </div>
                    </div>
            </div>
        </div>
    </section>

@endif
