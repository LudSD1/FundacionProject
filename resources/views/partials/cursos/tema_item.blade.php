<div class="tema-item-content" id="tema-{{ $tema->id }}">

    <!-- Header del Tema Mejorado -->
    <div class="theme-header-card mb-4">
        <div class="theme-header-content">
            @if ($tema->imagen)
            <div class="theme-hero-image">
                <img src="{{ asset('storage/' . $tema->imagen) }}" alt="Imagen del tema" class="theme-image">
                <div class="theme-overlay"></div>
            </div>
            @endif

            <div class="theme-info {{ $tema->imagen ? 'with-image' : '' }}">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h1 class="theme-title">{{ $tema->titulo_tema }}</h1>

                    <!-- Contador de subtemas -->
                    <div class="theme-stats">
                        <span class="badge bg-primary">
                            <i class="fas fa-layer-group me-1"></i>
                            {{ count($tema->subtemas) }} Subtemas
                        </span>
                    </div>
                </div>

                <!-- Descripción con acordeón mejorado -->
                <div class="theme-description-section">
                    <button class="description-toggle-btn" type="button" data-bs-toggle="collapse"
                        data-bs-target="#descripcionTema-{{ $tema->id }}" aria-expanded="false"
                        aria-controls="descripcionTema-{{ $tema->id }}">
                        <i class="fas fa-info-circle me-2"></i>
                        <span>Descripción del Tema</span>
                        <i class="toggle-icon fas fa-chevron-down"></i>
                    </button>
                    <div class="collapse" id="descripcionTema-{{ $tema->id }}">
                        <div class="description-content">
                            {!! nl2br(e($tema->descripcion)) !!}
                        </div>
                    </div>
                </div>

                <!-- Acciones del docente -->
                @if (auth()->user()->hasRole('Docente') && $cursos->docente_id == auth()->user()->id)
                <div class="theme-actions">
                    <div class="" role="group">
                        <button class="btn-modern btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalSubtema-{{ $tema->id }}">
                            <i class="fas fa-plus-circle me-1"></i><span class="ms-1">Nuevo Subtema</span>
                        </button>
                        <button class="btn-modern btn-accent-custom" data-bs-toggle="modal" data-bs-target="#modalEditarTema-{{ $tema->id }}">
                            <i class="fas fa-edit me-1"></i><span class="ms-1">Editar</span>
                        </button>
                        <form class="d-inline" action="{{ route('temas.delete', encrypt($tema->id)) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este tema y todos sus subtemas?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-modern btn-orange-custom">
                                <i class="fas fa-trash me-1"></i><span class="ms-1">Eliminar</span>
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Editar Tema Mejorado -->
    <div class="modal fade" id="modalEditarTema-{{ $tema->id }}" tabindex="-1" aria-labelledby="modalEditarTemaLabel-{{ $tema->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-header-content">
                        <i class="fas fa-edit fa-lg me-3"></i>
                        <div>
                            <h5 class="modal-title mb-0" id="modalEditarTemaLabel-{{ $tema->id }}">Editar Tema</h5>
                            <small class="opacity-75">{{ $tema->titulo_tema }}</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('temas.update', encrypt($tema->id)) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="titulo" class="form-label-modern">Título del Tema *</label
                                    ><input type="text" class="form-control-modern" name="titulo" value="{{ $tema->titulo_tema }}" required placeholder="Ingresa el título del tema">
                                </div>
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label-modern">Descripción</label
                                    ><textarea class="form-control-modern" name="descripcion" rows="4" placeholder="Describe el contenido de este tema...">{{ $tema->descripcion }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="image-upload-section">
                                    <label class="form-label">Imagen del Tema</label>
                                    <div class="current-image mb-3">
                                        @if ($tema->imagen)
                                            <img src="{{ asset('storage/' . $tema->imagen) }}"
                                                class="img-thumbnail current-image-preview"
                                                alt="Imagen actual">
                                            <small class="text-muted d-block mt-1">Imagen actual</small>
                                        @else
                                            <div class="no-image-placeholder">
                                                <i class="fas fa-image fa-2x text-muted mb-2"></i>
                                                <small class="text-muted">No hay imagen cargada</small>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="file-upload-wrapper">
                                        <input type="file" class="form-control-modern" name="imagen" accept="image/*" id="imageUpload-{{ $tema->id }}">
                                        <label for="imageUpload-{{ $tema->id }}" class="file-upload-label">
                                            <i class="fas fa-upload me-2"></i>Seleccionar imagen
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-modern btn-accent-custom" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i><span class="ms-1">Cancelar</span>
                        </button>
                        <button type="submit" class="btn-modern btn-primary-custom">
                            <i class="fas fa-save me-1"></i><span class="ms-1">Guardar Cambios</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Lista de Subtemas Mejorada -->
    <div class="subtopics-container">
        <div class="subtopics-header">
            <h3 class="subtopics-title">
                <i class="fas fa-layer-group me-2"></i>
                Contenido del Tema
            </h3>
            <div class="subtopics-progress">
                @if(auth()->user()->hasRole('Estudiante'))
                <div class="progress-info">
                    <small class="text-muted">Progreso: </small>
                    <span class="progress-text">
                        {{ $tema->calcularProgreso($inscritos2->id ?? null) }}% completado
                    </span>
                </div>
                @endif
            </div>
        </div>

        <div class="subtopics-list">
            @forelse ($tema->subtemas as $subtemaIndex => $subtema)
                @php
                    $desbloqueado =
                        auth()->user()->hasRole('Docente') ||
                        (auth()->user()->hasRole('Estudiante') &&
                            $subtema->estaDesbloqueado($inscritos2->id ?? null));
                @endphp

                <div class="subtopic-card {{ !$desbloqueado && auth()->user()->hasRole('Estudiante') ? 'locked' : '' }}"
                    data-subtopic-id="{{ $subtema->id }}">

                    <!-- Header del Subtema -->
                    <div class="subtopic-header">
                        <div class="subtopic-info">
                            <div class="subtopic-number">
                                {{ $subtemaIndex + 1 }}
                            </div>
                            <div class="subtopic-content">
                                <h4 class="subtopic-title">
                                    {{ $subtema->titulo_subtema }}
                                    @if(!$desbloqueado && auth()->user()->hasRole('Estudiante'))
                                    <i class="fas fa-lock ms-2 text-warning"></i>
                                    @endif
                                </h4>
                                @if($subtema->duracion)
                                <small class="subtopic-meta">
                                    <i class="fas fa-clock me-1"></i>
                                    Duración: {{ $subtema->duracion }}
                                </small>
                                @endif
                            </div>
                        </div>

                        <div class="subtopic-actions">
                            @if(auth()->user()->hasRole('Estudiante') && $desbloqueado)
                            <div class="completion-badge">
                                <i class="fas fa-check-circle text-success"></i>
                                <small>Disponible</small>
                            </div>
                            @endif

                            <button class="subtopic-toggle-btn {{ $subtemaIndex === 0 ? 'active' : '' }}"
                                data-bs-toggle="collapse"
                                data-bs-target="#subtemaCollapse-{{ $subtema->id }}"
                                aria-expanded="{{ $subtemaIndex === 0 ? 'true' : 'false' }}">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Contenido del Subtema -->
                    @if ($desbloqueado || auth()->user()->hasRole('Docente'))
                    <div class="subtopic-content-collapse collapse {{ $subtemaIndex === 0 ? 'show' : '' }}"
                        id="subtemaCollapse-{{ $subtema->id }}">
                        <div class="subtopic-body">
                            @include('partials.cursos.subtema_item', [
                                'subtema' => $subtema,
                                'tema' => $tema,
                            ])
                        </div>
                    </div>
                    @elseif(!$desbloqueado && auth()->user()->hasRole('Estudiante'))
                    <div class="subtopic-locked-message">
                        <div class="locked-content">
                            <i class="fas fa-lock fa-2x text-warning mb-2"></i>
                            <h5>Contenido Bloqueado</h5>
                            <p class="text-muted">Completa los requisitos anteriores para desbloquear este contenido.</p>
                        </div>
                    </div>
                    @endif
                </div>
            @empty
                <div class="empty-subtopics">
                    <div class="empty-state">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5>No hay subtemas disponibles</h5>
                        <p class="text-muted">Aún no se han agregado subtemas a este tema.</p>
                        @if (auth()->user()->hasRole('Docente') && $cursos->docente_id == auth()->user()->id)
                        <button class="btn-modern btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalSubtema-{{ $tema->id }}">
                            <i class="fas fa-plus me-1"></i><span class="ms-1">Crear Primer Subtema</span>
                        </button>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
/* Estilos para el tema mejorado */
.theme-header-card {
    background: white;
    border-radius: var(--border-radius-md);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    margin-bottom: 1.5rem;
}

.theme-header-content {
    position: relative;
}

.theme-hero-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.theme-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.theme-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 60%;
    background: linear-gradient(transparent, rgba(0,0,0,0.7));
}

.theme-info {
    padding: 2rem;
}

.theme-info.with-image {
    position: relative;
    margin-top: -60px;
    z-index: 2;
}

.theme-title {
    color: var(--color-primary);
    font-weight: 700;
    margin-bottom: 0.5rem;
    font-size: 2rem;
}

.theme-stats .badge {
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
}

/* Descripción del tema */
.theme-description-section {
    margin: 1.5rem 0;
}

.description-toggle-btn {
    background: var(--gradient-secondary);
    color: white;
    border: none;
    padding: 1rem 1.5rem;
    border-radius: var(--border-radius-sm);
    width: 100%;
    text-align: left;
    font-weight: 500;
    transition: all 0.3s ease;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.description-toggle-btn:hover {
    background: var(--color-secondary);
    transform: translateY(-1px);
}

.description-content {
    background: #f8f9fa;
    border-radius: var(--border-radius-sm);
    padding: 1.5rem;
    margin-top: 1rem;
    border-left: 4px solid var(--color-accent1);
    line-height: 1.6;
}

/* Acciones del docente */
.theme-actions {
    margin-top: 1.5rem;
}

.theme-actions .btn-group {
    gap: 0.5rem;
}

/* Lista de subtemas */
.subtopics-container {
    background: white;
    border-radius: var(--border-radius-md);
    box-shadow: var(--shadow-md);
    overflow: hidden;
}

.subtopics-header {
    background: var(--gradient-primary);
    color: white;
    padding: 1.5rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.subtopics-title {
    margin: 0;
    font-weight: 600;
}

.subtopics-progress {
    text-align: right;
}

.progress-text {
    font-weight: 600;
    color: var(--color-accent1);
}

/* Tarjetas de subtemas */
.subtopics-list {
    padding: 1.5rem;
}

.subtopic-card {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: var(--border-radius-sm);
    margin-bottom: 1rem;
    transition: all 0.3s ease;
    overflow: hidden;
}

.subtopic-card:hover {
    border-color: var(--color-accent1);
    box-shadow: var(--shadow-sm);
}

.subtopic-card.locked {
    opacity: 0.7;
    background: #f8f9fa;
}

.subtopic-header {
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    background: white;
}

.subtopic-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex: 1;
}

.subtopic-number {
    background: var(--gradient-primary);
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.1rem;
}

.subtopic-content {
    flex: 1;
}

.subtopic-title {
    color: var(--color-primary);
    margin: 0;
    font-weight: 600;
    font-size: 1.2rem;
}

.subtopic-meta {
    color: #6c757d;
    font-size: 0.9rem;
}

.subtopic-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.completion-badge {
    text-align: center;
}

.completion-badge small {
    font-size: 0.8rem;
    color: var(--color-success);
}

.subtopic-toggle-btn {
    background: none;
    border: 1px solid #e9ecef;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.subtopic-toggle-btn:hover {
    background: var(--color-accent1);
    color: white;
    border-color: var(--color-accent1);
}

.subtopic-toggle-btn.active {
    transform: rotate(180deg);
}

/* Contenido bloqueado */
.subtopic-locked-message {
    padding: 3rem 2rem;
    text-align: center;
    background: #f8f9fa;
}

.locked-content {
    max-width: 300px;
    margin: 0 auto;
}

/* Estado vacío */
.empty-subtopics {
    padding: 3rem 2rem;
    text-align: center;
}

.empty-state {
    max-width: 400px;
    margin: 0 auto;
}

/* Modal mejorado */
.modal-header-content {
    display: flex;
    align-items: center;
}

.image-upload-section {
    text-align: center;
}

.current-image-preview {
    max-height: 150px;
    width: auto;
}

.no-image-placeholder {
    padding: 2rem;
    border: 2px dashed #e9ecef;
    border-radius: var(--border-radius-sm);
    color: #6c757d;
}

.file-upload-wrapper {
    position: relative;
}

.file-upload-label {
    display: block;
    padding: 0.75rem 1rem;
    background: var(--gradient-secondary);
    color: white;
    border-radius: var(--border-radius-sm);
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.file-upload-label:hover {
    background: var(--color-secondary);
}

.modern-input {
    border: 1px solid #e0e0e0;
    border-radius: var(--border-radius-sm);
    padding: 0.75rem;
    transition: all 0.2s ease;
}

.modern-input:focus {
    border-color: var(--color-accent3);
    box-shadow: 0 0 0 0.2rem rgba(57, 166, 203, 0.25);
}

/* Responsive */
@media (max-width: 768px) {
    .theme-info {
        padding: 1.5rem 1rem;
    }

    .theme-title {
        font-size: 1.5rem;
    }

    .subtopics-header {
        padding: 1rem;
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }

    .subtopic-header {
        padding: 1rem;
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .subtopic-actions {
        width: 100%;
        justify-content: space-between;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Reubicar modales de edición bajo <body> para evitar desplazamientos por transform/overflow
    document.querySelectorAll('[id^="modalEditarTema-"]').forEach(function(modal){ if(modal.parentElement !== document.body){ document.body.appendChild(modal); } });
    // Animación para botones de toggle
    const toggleButtons = document.querySelectorAll('.subtopic-toggle-btn');
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            this.classList.toggle('active');
        });
    });

    // Efecto hover para tarjetas de subtemas
    const subtopicCards = document.querySelectorAll('.subtopic-card');
    subtopicCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            if (!this.classList.contains('locked')) {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = 'var(--shadow-md)';
            }
        });

        card.addEventListener('mouseleave', function() {
            if (!this.classList.contains('locked')) {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            }
        });
    });

    // Previsualización de imagen en el modal
    const imageUploads = document.querySelectorAll('input[type="file"][name="imagen"]');
    imageUploads.forEach(input => {
        input.addEventListener('change', function() {
            const preview = this.closest('.image-upload-section').querySelector('.current-image-preview');
            const noImage = this.closest('.image-upload-section').querySelector('.no-image-placeholder');

            if (this.files && this.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    if (preview) {
                        preview.src = e.target.result;
                    } else if (noImage) {
                        noImage.innerHTML = `<img src="${e.target.result}" class="img-thumbnail current-image-preview" alt="Vista previa">`;
                    }
                }

                reader.readAsDataURL(this.files[0]);
            }
        });
    });
});
</script>
