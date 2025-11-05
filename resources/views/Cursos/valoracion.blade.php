


    <section class="mt-" id="valoraciones">
        <div class="container">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-header bg-light">
                            <h3 class="mb-0">
                                <i class="bi bi-star-fill text-warning me-2"></i>Valoraciones del Curso
                            </h3>
                        </div>
                        <div class="card-body">
                            <!-- Resumen de Calificaciones -->
                            <div class="row align-items-center mb-4">
                                <div class="col-md-3 text-center">
                                    <div class="display-4 fw-bold text-primary">
                                        {{ number_format($cursos->calificaciones_avg_puntuacion, 1) }}
                                    </div>
                                    <div class="stars mb-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i
                                                class="bi bi-star-fill {{ $i <= round($cursos->calificaciones_avg_puntuacion) ? 'text-warning' : 'text-secondary' }}"></i>
                                        @endfor
                                    </div>
                                    <small class="text-muted">Basado en {{ $cursos->calificaciones_count }}
                                        valoraciones</small>
                                </div>
                                <div class="col-md-9">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <div class="row align-items-center mb-2">
                                            <div class="col-2 text-end">
                                                <small>{{ $i }} <i
                                                        class="bi bi-star-fill text-warning"></i></small>
                                            </div>
                                            <div class="col-7">
                                                <div class="progress" style="height: 8px;">
                                                    @php
                                                        $percentage =
                                                            $cursos->calificaciones_count > 0
                                                                ? ($cursos->calificaciones
                                                                        ->where('puntuacion', $i)
                                                                        ->count() /
                                                                        $cursos->calificaciones_count) *
                                                                    100
                                                                : 0;
                                                    @endphp
                                                    <div class="progress-bar bg-warning" role="progressbar"
                                                        style="width: {{ $percentage }}%"
                                                        aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <small
                                                    class="text-muted">{{ $cursos->calificaciones->where('puntuacion', $i)->count() }}</small>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>



                            <!-- Formulario de Valoración (solo para usuarios inscritos) -->
                            @if ($usuarioInscrito && !$usuarioCalifico)
                                <div class="rating-form mb-5 p-4 bg-light rounded-3">
                                    <h5><i class="bi bi-pencil-square me-2"></i>Deja tu valoración</h5>
                                    <form action="{{ route('cursos.calificar', encrypt($cursos->id)) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Tu calificación:</label>
                                            <div class="rating-stars-input">
                                                @for ($i = 5; $i >= 1; $i--)
                                                    <input type="radio" id="star{{ $i }}" name="puntuacion"
                                                        value="{{ $i }}" required>
                                                    <label for="star{{ $i }}">★</label>
                                                @endfor
                                            </div>
                                            @error('puntuacion')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="comentario" class="form-label">Comentario
                                                (opcional):</label>
                                            <textarea name="comentario" class="form-control" rows="3" placeholder="¿Qué te pareció el curso?">{{ old('comentario') }}</textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-send-fill me-1"></i> Enviar Valoración
                                        </button>
                                    </form>
                                </div>
                            @elseif($usuarioCalifico)
                                <div class="alert alert-info">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <i class="bi bi-info-circle-fill me-2"></i>
                                            Ya calificaste este curso con {{ $calificacionUsuario->puntuacion }}
                                            estrellas.
                                            @if ($calificacionUsuario->comentario)
                                                <div class="mt-2">
                                                    <strong>Tu comentario:</strong>
                                                    <p class="mb-0">{{ $calificacionUsuario->comentario }}</p>
                                                </div>
                                            @endif
                                        </div>
                                        <button class="btn btn-sm btn-outline-warning"
                                            onclick="editarCalificacion({{ $calificacionUsuario->id }}, {{ $calificacionUsuario->puntuacion }}, '{{ $calificacionUsuario->comentario }}')">
                                            <i class="bi bi-pencil"></i> Editar
                                        </button>
                                    </div>
                                </div>
                            @elseif(!Auth::check())
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <a href="{{ route('login') }}" class="alert-link">Inicia sesión</a> y
                                    únete al curso para poder calificar.
                                </div>
                            @endif

                            <!-- Listado de Valoraciones -->
                            <h5 class="mt-4 mb-3"><i class="bi bi-chat-square-quote-fill me-2"></i>Últimas
                                valoraciones</h5>

                            @forelse($calificacionesRecientes as $calificacion)
                                <div class="card mb-3 border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="mb-1">{{ $calificacion->user->name }}</h6>
                                                <div class="stars small">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i
                                                            class="bi bi-star-fill {{ $i <= $calificacion->puntuacion ? 'text-warning' : 'text-secondary' }}"></i>
                                                    @endfor
                                                    <small
                                                        class="text-muted ms-2">{{ $calificacion->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-1">
                                                @if (auth()->id() === $calificacion->user_id)
                                                    <button type="button" class="btn btn-sm btn-outline-warning"
                                                        onclick="editarCalificacion({{ $calificacion->id }}, {{ $calificacion->puntuacion }}, '{{ $calificacion->comentario }}')">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <form
                                                        action="{{ route('calificaciones.destroy', encrypt($calificacion->id)) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('¿Estás seguro de eliminar esta valoración?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                @hasrole('Administrador')
                                                    <form
                                                        action="{{ route('calificaciones.destroy', encrypt($calificacion->id)) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('¿Estás seguro de eliminar esta valoración?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endrole
                                            </div>
                                        </div>
                                        @if ($calificacion->comentario)
                                            <p class="mt-2 mb-0">{{ $calificacion->comentario }}</p>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <i class="bi bi-chat-square-text text-muted" style="font-size: 2rem;"></i>
                                    <p class="text-muted mt-2">Aún no hay valoraciones para este curso</p>
                                </div>
                            @endforelse

                            @if ($cursos->calificaciones_count > 5)
                                <div class="text-center mt-3">
                                    <a href="{{ route('cursos.allRatings', encrypt($cursos->id)) }}"
                                        class="btn btn-outline-primary">
                                        <i class="bi bi-list-ul me-1"></i> Ver todas las valoraciones
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal para Editar Valoración -->
    <div class="modal fade" id="editarCalificacionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square me-2"></i>Editar tu valoración
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditarCalificacion" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Tu calificación:</label>
                            <div class="rating-stars-input" id="editStarsContainer">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="edit_star{{ $i }}" name="puntuacion"
                                        value="{{ $i }}" required>
                                    <label for="edit_star{{ $i }}">★</label>
                                @endfor
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_comentario" class="form-label">Comentario (opcional):</label>
                            <textarea name="comentario" id="edit_comentario" class="form-control" rows="3"
                                placeholder="¿Qué te pareció el curso?"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check-lg me-1"></i>Actualizar Valoración
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts para manejar la edición -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function editarCalificacion(id, puntuacion, comentario) {
            // Actualizar la acción del formulario
            document.getElementById('formEditarCalificacion').action = `/calificaciones/${id}`;

            // Establecer la puntuación actual
            document.getElementById(`edit_star${puntuacion}`).checked = true;

            // Establecer el comentario actual
            document.getElementById('edit_comentario').value = comentario || '';

            // Mostrar el modal
            new bootstrap.Modal(document.getElementById('editarCalificacionModal')).show();
        }

        // Manejar el envío del formulario de edición
        document.getElementById('formEditarCalificacion').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const actionUrl = this.action;

            fetch(actionUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-HTTP-Method-Override': 'PUT'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Cerrar modal
                        bootstrap.Modal.getInstance(document.getElementById('editarCalificacionModal')).hide();

                        // Mostrar mensaje de éxito
                        Swal.fire({
                            icon: 'success',
                            title: '¡Actualizado!',
                            text: data.message || 'Tu valoración ha sido actualizada correctamente',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // Recargar la página después de un breve delay
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Ocurrió un error al actualizar la valoración'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error inesperado'
                    });
                });
        });
    </script>






