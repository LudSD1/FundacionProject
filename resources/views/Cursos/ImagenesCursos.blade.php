@extends('layout')

@section('titulo', 'Imágenes del Curso')

@section('content')
<div class="container-fluid py-4 px-md-5">
    <div class="tbl-card shadow-sm">
        {{-- Hero Header --}}
        <div class="tbl-card-hero">
            <div class="tbl-hero-left">
                <a href="{{ route('Curso', $curso->codigoCurso ?? $curso->id ) }}" class="btn-modern btn-accent-custom mb-3" style="background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2);">
                    <i class="bi bi-arrow-left"></i> Volver al Curso
                </a>
                <div class="tbl-hero-eyebrow">
                    <i class="bi bi-collection-play-fill"></i> Recursos Multimedia
                </div>
                <h2 class="tbl-hero-title">Imágenes del Curso</h2>
                <p class="tbl-hero-sub">Gestiona la identidad visual y el material promocional de tu curso.</p>

                @if($curso->youtube_url)
                    <div class="mt-4" style="max-width: 400px;">
                        <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-sm border border-white-50">
                            <iframe src="{{ $curso->youtube_url }}" 
                                    title="YouTube video player" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                    allowfullscreen>
                            </iframe>
                        </div>
                        <small class="text-white-50 mt-2 d-block">
                            <i class="bi bi-youtube me-1"></i> Video actual del curso
                        </small>
                    </div>
                @endif
            </div>

            <div class="tbl-hero-controls">
                <div class="d-flex flex-wrap gap-2 justify-content-end">
                    <button class="btn-modern btn-primary" data-bs-toggle="modal" data-bs-target="#crearImagenModal">
                        <i class="bi bi-plus-lg"></i> Agregar Imagen
                    </button>
                    <button class="btn-modern" style="background: #ff0000; color: white;" data-bs-toggle="modal" data-bs-target="#editarYoutubeModal">
                        <i class="bi bi-youtube"></i> Video del Curso
                    </button>
                </div>
            </div>
        </div>

        <div class="p-4 p-lg-5">
            {{-- Listado de Imágenes --}}
            <div class="table-responsive">
                <table class=" table-modern ">
                    <thead>
                        <tr>
                            <th style="width:140px;">Imagen</th>
                            <th>Título y Detalles</th>
                            <th style="width:100px;" class="text-center">Orden</th>
                            <th style="width:120px;" class="text-center">Estado</th>
                            <th style="width:200px;" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($imagenes as $imagen)
                            <tr>
                                <td>
                                    <div class="position-relative">
                                        <img src="{{ asset($imagen->url) }}"
                                             class="rounded-3 shadow-sm object-fit-cover w-100"
                                             style="height:70px;"
                                             alt="{{ $imagen->titulo }}">
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark mb-1">{{ $imagen->titulo }}</div>
                                    <div class="text-muted small text-truncate" style="max-width: 300px;">
                                        {{ $imagen->descripcion ?: 'Sin descripción' }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border fw-medium">#{{ $imagen->orden }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $imagen->activo ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-pill px-3 py-1">
                                        <i class="bi {{ $imagen->activo ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }} me-1"></i>
                                        {{ $imagen->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn-action-modern btn-edit"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editarImagenModal{{ $imagen->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        @if (!$imagen->activo)
                                            <form method="POST" action="{{ route('curso-imagenes.restore', encrypt($imagen->id)) }}" class="restore-form">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn-action-modern btn-restore" title="Restaurar">
                                                    <i class="bi bi-arrow-clockwise"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="btn-action-modern btn-delete"
                                                    onclick="confirmarInactivacion({{ $imagen->id }})" title="Inactivar">
                                                <i class="bi bi-eye-slash"></i>
                                            </button>
                                            <form id="form-inactivar-{{ $imagen->id }}"
                                                  action="{{ route('curso-imagenes.destroy', encrypt($imagen->id)) }}"
                                                  method="POST" style="display:none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            {{-- Modal Editar Imagen --}}
                            <div class="modal fade" id="editarImagenModal{{ $imagen->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <form method="POST" action="{{ route('curso-imagenes.update', $imagen) }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                            <div class="modal-header bg-primary text-white p-4 border-0">
                                                <h5 class="modal-title fw-bold">
                                                    <i class="bi bi-pencil-square me-2"></i>Actualizar Imagen
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4 p-lg-5">
                                                <div class="row g-4">
                                                    <div class="col-md-5">
                                                        <div class="text-center">
                                                            <p class="fw-bold text-start mb-2">Vista Previa Actual</p>
                                                            <img src="{{ asset($imagen->url) }}"
                                                                 class="img-fluid rounded-4 shadow-sm mb-4 w-100 object-fit-cover"
                                                                 style="height:200px;"
                                                                 id="preview-edit-{{ $imagen->id }}">
                                                            <label class="btn btn-outline-primary w-100 py-2 cursor-pointer">
                                                                <i class="bi bi-camera me-2"></i>Cambiar Imagen
                                                                <input type="file" name="imagen" class="d-none" accept="image/*"
                                                                       onchange="previewEditImage(this, 'preview-edit-{{ $imagen->id }}')">
                                                            </label>
                                                            <div class="form-text mt-2 small">Formatos: JPG, PNG, GIF (Máx 5MB)</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-7">
                                                        <div class="mb-3">
                                                            <label class="fw-bold mb-2">Título de la Imagen</label>
                                                            <input type="text" name="titulo" class="form-control"
                                                                   value="{{ $imagen->titulo }}" placeholder="Ej: Banner Principal">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="fw-bold mb-2">Descripción</label>
                                                            <textarea name="descripcion" class="form-control" rows="3"
                                                                      placeholder="Escribe una breve descripción...">{{ $imagen->descripcion }}</textarea>
                                                        </div>
                                                        <div class="row g-3">
                                                            <div class="col-6">
                                                                <label class="fw-bold mb-2">Orden</label>
                                                                <input type="number" name="orden" class="form-control"
                                                                       value="{{ $imagen->orden }}" min="0">
                                                            </div>
                                                            <div class="col-6 d-flex align-items-end">
                                                                <div class="form-check form-switch mb-2">
                                                                    <input type="checkbox" name="activo" value="1"
                                                                           class="form-check-input" id="activo-{{ $imagen->id }}"
                                                                           {{ $imagen->activo ? 'checked' : '' }}>
                                                                    <label class="form-check-label fw-bold" for="activo-{{ $imagen->id }}">Activa</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light p-4 border-0">
                                                <button type="button" class="btn btn-link text-muted fw-bold text-decoration-none" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold text-white">Guardar Cambios</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="text-center py-5">
                                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px;">
                                            <i class="bi bi-images text-muted fs-1"></i>
                                        </div>
                                        <h4 class="text-muted fw-bold">No hay imágenes aún</h4>
                                        <p class="text-muted mb-4">Agrega imágenes para darle identidad visual a tu curso.</p>
                                        <button class="btn btn-primary rounded-pill px-5 text-white" data-bs-toggle="modal" data-bs-target="#crearImagenModal">
                                            <i class="bi bi-plus-lg me-2"></i>Comenzar ahora
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Agregar Imagen --}}
<div class="modal fade" id="crearImagenModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form method="POST" action="{{ route('curso-imagenes.store', $curso) }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="curso_id" value="{{ $curso->id }}">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-primary text-white p-4 border-0">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle me-2"></i>Nueva Imagen
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 p-lg-5">
                    <div class="row g-4">
                        <div class="col-md-5">
                            <label class="fw-bold mb-2">Imagen del Recurso</label>
                            <div class="upload-zone p-4 text-center border-2 border-dashed rounded-4" style="cursor: pointer; background: #f8fafc;" onclick="document.getElementById('inputImagenNueva').click()">
                                <div id="uploadPlaceholder">
                                    <i class="bi bi-cloud-arrow-up fs-1 text-primary d-block mb-3"></i>
                                    <p class="fw-bold mb-1">Subir archivo</p>
                                    <p class="text-muted small">Arrastra o haz clic aquí</p>
                                </div>
                                <img id="previewNueva" class="img-fluid rounded-4 d-none object-fit-cover w-100" style="height:200px;">
                            </div>
                            <input type="file" name="imagen" id="inputImagenNueva" class="d-none" accept="image/*" required onchange="previewNuevaImagen(this)">
                        </div>
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label class="fw-bold mb-2">Título</label>
                                <input type="text" name="titulo" class="form-control" placeholder="Ej: Portada Principal">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold mb-2">Descripción</label>
                                <textarea name="descripcion" class="form-control" rows="3" placeholder="Describe el propósito de esta imagen..."></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold mb-2">Orden de aparición</label>
                                <input type="number" name="orden" class="form-control" value="0" min="0">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light p-4 border-0">
                    <button type="button" class="btn btn-link text-muted fw-bold text-decoration-none" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold text-white">Subir Imagen</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Video YouTube --}}
<div class="modal fade" id="editarYoutubeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form method="POST" action="{{ route('cursos.updateYoutube', encrypt($curso->id)) }}">
            @csrf
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-danger text-white p-4 border-0">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-youtube me-2"></i>Video Promocional
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 p-lg-5">
                    <div class="mb-4">
                        <label class="fw-bold mb-2">URL del Video de YouTube</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-link-45deg"></i></span>
                            <input type="url" name="youtube_url" id="youtube_url"
                                   class="form-control border-start-0"
                                   value="{{ old('youtube_url', $curso->youtube_url) }}"
                                   placeholder="https://www.youtube.com/watch?v=..."
                                   oninput="actualizarPreviewYT(this.value)">
                        </div>
                    </div>

                    <div id="yt-preview-container" class="{{ $curso->youtube_url ? '' : 'd-none' }}">
                        <p class="fw-bold mb-2"><i class="bi bi-play-circle me-2"></i>Vista Previa</p>
                        <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-sm">
                            <iframe id="yt-preview-iframe" src="{{ $curso->youtube_url ?? '' }}" allowfullscreen></iframe>
                        </div>
                    </div>

                    <div id="yt-no-video" class="{{ $curso->youtube_url ? 'd-none' : '' }} text-center py-5 bg-light rounded-4">
                        <i class="bi bi-youtube fs-1 text-muted d-block mb-2"></i>
                        <p class="text-muted small">Pega el enlace de YouTube para ver la previsualización.</p>
                    </div>
                </div>
                <div class="modal-footer bg-light p-4 border-0">
                    <button type="button" class="btn btn-link text-muted fw-bold text-decoration-none" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-5 fw-bold">Guardar Video</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmarInactivacion(id) {
        Swal.fire({
            title: '¿Inactivar imagen?',
            text: "La imagen dejará de ser visible para los estudiantes.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Sí, inactivar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
            customClass: { popup: 'rounded-4 shadow-lg border-0' }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Procesando...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                document.getElementById('form-inactivar-' + id).submit();
            }
        });
    }

    function previewNuevaImagen(input) {
        const preview = document.getElementById('previewNueva');
        const placeholder = document.getElementById('uploadPlaceholder');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                placeholder.classList.add('d-none');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewEditImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => { preview.src = e.target.result; };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function actualizarPreviewYT(url) {
        const iframe = document.getElementById('yt-preview-iframe');
        const container = document.getElementById('yt-preview-container');
        const placeholder = document.getElementById('yt-no-video');
        const embedUrl = convertirUrlYT(url.trim());
        if (embedUrl) {
            iframe.src = embedUrl;
            container.classList.remove('d-none');
            placeholder.classList.add('d-none');
        } else {
            iframe.src = '';
            container.classList.add('d-none');
            placeholder.classList.remove('d-none');
        }
    }

    function convertirUrlYT(url) {
        if (!url) return null;
        if (url.includes('youtube.com/embed/')) return url;
        const shortMatch = url.match(/youtu\.be\/([^?&]+)/);
        if (shortMatch) return `https://www.youtube.com/embed/${shortMatch[1]}`;
        const watchMatch = url.match(/[?&]v=([^&]+)/);
        if (watchMatch) return `https://www.youtube.com/embed/${watchMatch[1]}`;
        return null;
    }
</script>
@endsection
