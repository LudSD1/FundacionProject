@extends('layout')

@section('titulo', 'Imágenes del Curso')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Imágenes del Curso</h2>

        <a href="{{ route('Curso' , encrypt($curso->id) ) }}" class="btn-modern btn-primary-custom mb-3"> Volver al Curso</a>
        <!-- Botón crear -->
        <button class="btn-modern btn-primary-custom mb-3" data-bs-toggle="modal" data-bs-target="#crearImagenModal">Agregar Imagen</button>

        <button class="btn-modern btn-accent-custom mb-3 ms-2" data-bs-toggle="modal" data-bs-target="#editarYoutubeModal">
            Editar Enlace de YouTube
        </button>


        <!-- Modal Editar YouTube URL -->
        <div class="modal fade" id="editarYoutubeModal" tabindex="-1" aria-labelledby="editarYoutubeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form method="POST" action="{{ route('cursos.updateYoutube', encrypt($curso->id)) }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editarYoutubeModalLabel">Editar enlace de YouTube del curso</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <label for="youtube_url" class="form-label-modern">URL de YouTube</label>
                            <input type="url" name="youtube_url" id="youtube_url" class="form-control-modern" value="{{ old('youtube_url', $curso->youtube_url) }}" placeholder="https://www.youtube.com/watch?v=...">
                            <div class="mt-3">
                                @if ($curso->youtube_url)
                                    <p>Video actual:</p>
                                    <div class="ratio ratio-16x9">
                                        <iframe width="560" height="315" src="{{ $curso->youtube_url }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                @else
                                    <p><em>No hay video subido</em></p>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn-modern btn-accent-custom" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn-modern btn-primary-custom">Guardar enlace</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <!-- Tabla de imágenes -->
        <table class="table-modern table table-bordered">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Orden</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($imagenes as $imagen)
                    <tr>
                        <td><img src="{{ asset($imagen->url) }}" width="100"></td>
                        <td>{{ $imagen->titulo }}</td>
                        <td>{{ $imagen->descripcion }}</td>
                        <td>{{ $imagen->orden }}</td>
                        <td>
                            <span class="status-badge {{ $imagen->activo ? 'active' : 'inactive' }}">
                                {{ $imagen->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons-cell">
                                <button class="btn-action-modern" data-bs-toggle="modal" data-bs-target="#editarImagenModal{{ $imagen->id }}">Editar</button>

                                @if (!$imagen->activo)
                                    <form method="POST" action="{{ route('curso-imagenes.restore', encrypt($imagen->id)) }}" style="display:inline-block;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn-action-modern" title="Restaurar">
                                            <i class="bi bi-arrow-clockwise"></i> Restaurar
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn-action-modern btn-action-delete" onclick="confirmarInactivacion({{ $imagen->id }})">Inactivar</button>
                                    <form id="form-inactivar-{{ $imagen->id }}" action="{{ route('curso-imagenes.destroy', encrypt($imagen->id)) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>

                    <!-- Modal Editar -->
                    <div class="modal fade" id="editarImagenModal{{ $imagen->id }}" tabindex="-1"
                        aria-labelledby="editarImagenModalLabel{{ $imagen->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <form method="POST" action="{{ route('curso-imagenes.update', $imagen) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editarImagenModalLabel{{ $imagen->id }}">Editar
                                            Imagen</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-2">
                                            <label class="form-label-modern">Título</label>
                                            <input type="text" name="titulo" class="form-control-modern" value="{{ $imagen->titulo }}">
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label-modern">Descripción</label>
                                            <textarea name="descripcion" class="form-control-modern">{{ $imagen->descripcion }}</textarea>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label-modern">Orden</label>
                                            <input type="number" name="orden" class="form-control-modern" value="{{ $imagen->orden }}">
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label-modern">Reemplazar Imagen (opcional)</label>
                                            <input type="file" name="imagen" class="form-control-modern">
                                        </div>
                                        <div class="form-check mt-2">
                                            <input type="checkbox" name="activo" value="1" class="form-check-input" {{ $imagen->activo ? 'checked' : '' }}>
                                            <label class="form-check-label">Activo</label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn-modern btn-accent-custom" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn-modern btn-success-custom">Guardar Cambios</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="bi bi-image"></i>
                                <p>No hay imágenes registradas</p>
                                <button class="btn-modern btn-primary-custom" data-bs-toggle="modal" data-bs-target="#crearImagenModal">
                                    Agregar Imagen
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="crearImagenModal" tabindex="-1" aria-labelledby="crearImagenModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form method="POST" action="{{ route('curso-imagenes.store', $curso) }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="curso_id" value="{{ $curso->id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="crearImagenModalLabel">Agregar Imagen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label class="form-label-modern">Imagen</label>
                            <input type="file" name="imagen" class="form-control-modern" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label-modern">Título</label>
                            <input type="text" name="titulo" class="form-control-modern">
                        </div>
                        <div class="mb-2">
                            <label class="form-label-modern">Descripción</label>
                            <textarea name="descripcion" class="form-control-modern"></textarea>
                        </div>
                        <div class="mb-2">
                            <label class="form-label-modern">Orden</label>
                            <input type="number" name="orden" class="form-control-modern" value="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn-modern btn-accent-custom" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn-modern btn-primary-custom">Guardar Imagen</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    @push('scripts')
        <script>
            function confirmarInactivacion(id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta imagen será desactivada.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, desactivar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-inactivar-' + id).submit();
                    }
                })
            }
        </script>
    @endpush
    @stack('scripts')

@endsection
