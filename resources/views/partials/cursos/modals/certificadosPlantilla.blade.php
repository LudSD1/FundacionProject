{{-- Modal: Subir Plantilla de Certificado --}}
<div class="modal fade ach-modal" id="modalCertificado" tabindex="-1" aria-labelledby="modalCertificadoLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <form action="{{ route('certificates.store', $cursos->id) }}" method="POST"
                  enctype="multipart/form-data" novalidate>
                @csrf
                <div class="modal-header bg-primary text-white p-4 border-0">
                    <h5 class="modal-title fw-bold text-white" id="modalCertificadoLabel">
                        <i class="bi bi-file-earmark-plus me-2"></i>Nueva Plantilla de Certificado
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        {{-- Frontal --}}
                        <div class="col-lg-6">
                            <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                                <div class="card-header bg-light border-0 py-3">
                                    <h6 class="card-title mb-0 fw-bold text-primary">
                                        <i class="bi bi-image me-2"></i>Parte Frontal
                                    </h6>
                                </div>
                                <div class="card-body p-4">
                                    <div class="mb-4">
                                        <label class="form-label fw-bold text-secondary mb-2">Seleccionar archivo</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-upload"></i></span>
                                            <input type="file" name="template_front" class="form-control border-start-0 ps-0"
                                                   accept="image/*" required
                                                   onchange="previewImage(this, '#preview-front')">
                                        </div>
                                        <div class="form-text mt-2">
                                            <i class="bi bi-info-circle me-1"></i>Recomendado: 1754 x 1240 px (A4 horizontal)
                                        </div>
                                    </div>
                                    <div class="preview-container bg-light rounded-3 d-flex align-items-center justify-content-center overflow-hidden"
                                         id="preview-container-front" style="min-height: 200px; border: 2px dashed #e2e8f0;">
                                        <div class="preview-placeholder text-center text-muted p-4">
                                            <i class="bi bi-cloud-arrow-up fs-1 d-block mb-2 opacity-50"></i>
                                            <span class="small fw-medium">Vista previa frontal</span>
                                        </div>
                                        <img id="preview-front" class="img-fluid d-none"
                                             style="max-height:250px; object-fit: contain;" alt="Vista previa frontal" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Trasera --}}
                        <div class="col-lg-6">
                            <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                                <div class="card-header bg-light border-0 py-3">
                                    <h6 class="card-title mb-0 fw-bold text-primary">
                                        <i class="bi bi-image-fill me-2"></i>Parte Trasera
                                    </h6>
                                </div>
                                <div class="card-body p-4">
                                    <div class="mb-4">
                                        <label class="form-label fw-bold text-secondary mb-2">Seleccionar archivo</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-upload"></i></span>
                                            <input type="file" name="template_back" class="form-control border-start-0 ps-0"
                                                   accept="image/*" required
                                                   onchange="previewImage(this, '#preview-back')">
                                        </div>
                                        <div class="form-text mt-2">
                                            <i class="bi bi-info-circle me-1"></i>Opcional: Información adicional al reverso
                                        </div>
                                    </div>
                                    <div class="preview-container bg-light rounded-3 d-flex align-items-center justify-content-center overflow-hidden"
                                         id="preview-container-back" style="min-height: 200px; border: 2px dashed #e2e8f0;">
                                        <div class="preview-placeholder text-center text-muted p-4">
                                            <i class="bi bi-cloud-arrow-up fs-1 d-block mb-2 opacity-50"></i>
                                            <span class="small fw-medium">Vista previa trasera</span>
                                        </div>
                                        <img id="preview-back" class="img-fluid d-none"
                                             style="max-height:250px; object-fit: contain;" alt="Vista previa trasera" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Configuración de texto --}}
                    <div class="card border-0 shadow-sm rounded-3 overflow-hidden mt-4">
                        <div class="card-header bg-light border-0 py-3">
                            <h6 class="card-title mb-0 fw-bold text-primary">
                                <i class="bi bi-sliders me-2"></i>Personalización del Certificado
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary mb-2">
                                        <i class="bi bi-palette me-1"></i>Color de Texto
                                    </label>
                                    <div class="d-flex align-items-center gap-3 bg-light p-2 rounded-3">
                                        <input type="color" name="primary_color"
                                               class="form-control form-control-color border-0 bg-transparent" value="#145da0"
                                               style="width: 50px; height: 40px;">
                                        <span class="text-dark fw-bold color-value">#145da0</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary mb-2">
                                        <i class="bi bi-fonts me-1"></i>Tipografía
                                    </label>
                                    <select name="font_family" class="form-select border-0 bg-light py-2">
                                        @foreach(['Arial','Times New Roman','Helvetica','Courier New','Georgia','Verdana'] as $font)
                                            <option value="{{ $font }}" {{ $font == 'Arial' ? 'selected' : '' }}>{{ $font }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary mb-2">
                                        <i class="bi bi-textarea-t me-1"></i>Tamaño Base
                                    </label>
                                    <div class="input-group bg-light rounded-3 overflow-hidden">
                                        <input type="number" name="font_size"
                                               class="form-control border-0 bg-light py-2"
                                               min="8" max="72" value="16">
                                        <span class="input-group-text border-0 bg-light text-muted">px</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light p-4 border-0">
                    <button type="button" class="btn btn-link text-secondary fw-bold text-decoration-none" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                        <i class="bi bi-cloud-check-fill me-2"></i>Guardar Plantilla
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: Actualizar Plantilla de Certificado --}}
@if($cursos->certificateTemplate)
    @php $template = $cursos->certificateTemplate; @endphp
    <div class="modal fade ach-modal" id="modalEditarCertificado" tabindex="-1"
         aria-labelledby="modalEditarCertificadoLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <form action="{{ route('certificates.update', $cursos->id) }}" method="POST"
                      enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="modal-header bg-warning text-white p-4 border-0">
                        <h5 class="modal-title fw-bold text-white" id="modalEditarCertificadoLabel">
                            <i class="bi bi-pencil-square me-2"></i>Actualizar Plantilla
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="alert alert-warning border-0 bg-warning bg-opacity-10 d-flex align-items-center rounded-3 mb-4" role="alert">
                            <i class="bi bi-exclamation-circle-fill me-3 fs-4 text-warning"></i>
                            <div class="text-dark small">
                                <strong class="d-block mb-1">Nota importante:</strong>
                                Solo sube archivos si deseas reemplazar las imágenes actuales. Los campos de personalización se pueden actualizar en cualquier momento.
                            </div>
                        </div>

                        <div class="row g-4">
                            {{-- Frontal --}}
                            <div class="col-lg-6">
                                <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                                    <div class="card-header bg-light border-0 py-3">
                                        <h6 class="card-title mb-0 fw-bold text-dark">
                                            <i class="bi bi-image me-2 text-warning"></i>Plantilla Frontal
                                        </h6>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="text-center mb-4 bg-light p-2 rounded-3">
                                            <img src="{{ asset('storage/' . $template->template_front_path) }}"
                                                 class="img-fluid rounded shadow-sm"
                                                 style="max-height:120px;" alt="Frontal actual">
                                            <div class="mt-2 small text-muted fw-medium">Imagen actual</div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-secondary small">Reemplazar imagen</label>
                                            <input type="file" name="template_front" class="form-control form-control-sm border-warning border-opacity-25"
                                                   accept="image/*" onchange="previewImage(this, '#edit-preview-front')">
                                        </div>
                                        <div class="preview-container bg-light rounded-3 d-flex align-items-center justify-content-center overflow-hidden"
                                             style="min-height: 120px; border: 2px dashed #ffc10744;">
                                            <img id="edit-preview-front" class="img-fluid d-none"
                                                 style="max-height:150px; object-fit: contain;" alt="Nueva frontal">
                                            <div class="text-muted small edit-placeholder">Nueva vista previa</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Trasera --}}
                            <div class="col-lg-6">
                                <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                                    <div class="card-header bg-light border-0 py-3">
                                        <h6 class="card-title mb-0 fw-bold text-dark">
                                            <i class="bi bi-image-fill me-2 text-warning"></i>Plantilla Trasera
                                        </h6>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="text-center mb-4 bg-light p-2 rounded-3">
                                            <img src="{{ asset('storage/' . $template->template_back_path) }}"
                                                 class="img-fluid rounded shadow-sm"
                                                 style="max-height:120px;" alt="Trasera actual">
                                            <div class="mt-2 small text-muted fw-medium">Imagen actual</div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-secondary small">Reemplazar imagen</label>
                                            <input type="file" name="template_back" class="form-control form-control-sm border-warning border-opacity-25"
                                                   accept="image/*" onchange="previewImage(this, '#edit-preview-back')">
                                        </div>
                                        <div class="preview-container bg-light rounded-3 d-flex align-items-center justify-content-center overflow-hidden"
                                             style="min-height: 120px; border: 2px dashed #ffc10744;">
                                            <img id="edit-preview-back" class="img-fluid d-none"
                                                 style="max-height:150px; object-fit: contain;" alt="Nueva trasera">
                                            <div class="text-muted small edit-placeholder">Nueva vista previa</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Configuración de texto --}}
                        <div class="card border-0 shadow-sm rounded-3 overflow-hidden mt-4">
                            <div class="card-header bg-light border-0 py-3">
                                <h6 class="card-title mb-0 fw-bold text-dark">
                                    <i class="bi bi-sliders me-2 text-warning"></i>Personalización
                                </h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold text-secondary mb-2">Color de Texto</label>
                                        <div class="d-flex align-items-center gap-3 bg-light p-2 rounded-3">
                                            <input type="color" name="primary_color"
                                                   class="form-control form-control-color border-0 bg-transparent"
                                                   value="{{ $template->primary_color ?? '#145da0' }}"
                                                   style="width: 50px; height: 40px;">
                                            <span class="text-dark fw-bold color-value">{{ $template->primary_color ?? '#145da0' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold text-secondary mb-2">Tipografía</label>
                                        <select name="font_family" class="form-select border-0 bg-light py-2">
                                            @foreach(['Arial','Times New Roman','Helvetica','Courier New','Georgia','Verdana'] as $font)
                                                <option value="{{ $font }}" {{ ($template->font_family ?? '') == $font ? 'selected' : '' }}>
                                                    {{ $font }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold text-secondary mb-2">Tamaño Base</label>
                                        <div class="input-group bg-light rounded-3 overflow-hidden">
                                            <input type="number" name="font_size"
                                                   class="form-control border-0 bg-light py-2"
                                                   min="8" max="72" value="{{ $template->font_size ?? 16 }}">
                                            <span class="input-group-text border-0 bg-light text-muted">px</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-4 border-0">
                        <button type="button" class="btn btn-link text-secondary fw-bold text-decoration-none" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm">
                            <i class="bi bi-arrow-repeat me-2"></i>Actualizar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<script>
function previewImage(input, previewId) {
    const file = input.files[0];
    const preview = document.querySelector(previewId);
    const placeholder = input.closest('.card-body').querySelector('.preview-placeholder, .edit-placeholder');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            if(placeholder) placeholder.classList.add('d-none');
        }
        reader.readAsDataURL(file);
    } else {
        preview.src = '';
        preview.classList.add('d-none');
        if(placeholder) placeholder.classList.remove('d-none');
    }
}

// Actualizar valor hexadecimal al cambiar el color picker
document.querySelectorAll('input[type="color"]').forEach(picker => {
    picker.addEventListener('input', function() {
        this.nextElementSibling.textContent = this.value.toUpperCase();
    });
});

// Portar modales al body para evitar z-index issues
document.addEventListener('DOMContentLoaded', function() {
    const modals = document.querySelectorAll('.ach-modal');
    modals.forEach(modal => {
        document.body.appendChild(modal);
    });
});
</script>
