@section('titulo')
    Mi Perfil
@endsection



<style>
    input[type="file"] {
        /* Ocultar el campo de entrada */
        position: absolute;
        clip: rect(0, 0, 0, 0);
        pointer-events: none;
    }

    /* Estilo personalizado para el botón de carga de archivo */
    .custom-file-upload {
        border: 1px solid #ccc;
        display: inline-block;
        padding: 6px 12px;
        cursor: pointer;
        background-color: #f7f7f7;
    }
</style>

@section('contentup')
<div class="container py-5">
    <div class="row g-4">
        <!-- Columna de perfil (izquierda) -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <!-- Sección de avatar -->
                <div class="card-header bg-gradient-primary text-white text-center py-4 border-0">
                    <h4 class="mb-0 fw-semibold">Perfil de Usuario</h4>
                </div>

                <div class="text-center position-relative avatar-container">
                    <div class="profile-header-cover"></div>

                    <!-- Avatar -->
                    <div class="profile-avatar-wrapper">
                        @php
                            $avatarUrl = auth()->user()->avatar
                                ? asset('storage/' . auth()->user()->avatar)
                                : asset('./assets/img/user.png');
                        @endphp

                        <img id="avatar" src="{{ $avatarUrl }}"
                            class="profile-avatar rounded-circle border border-3 border-white shadow"
                            data-bs-toggle="modal" data-bs-target="#avatarModal"
                            alt="Avatar del usuario" title="Cambiar imagen de perfil">
                    </div>
                </div>

                <div class="card-body text-center pt-5 mt-3">
                    <h3 class="fw-bold mb-1">
                        {{ auth()->user()->name }} {{ auth()->user()->lastname1 }}
                    </h3>

                    <span class="badge bg-primary rounded-pill px-3 py-2 mb-3">
                        {{ auth()->user()->roles->pluck('name')[0] }}
                    </span>

                    <div class="text-muted mb-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        {{ auth()->user()->CiudadReside }}, {{ auth()->user()->PaisReside }}
                    </div>

                    <div class="text-muted mb-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-phone me-2"></i>
                        {{ auth()->user()->Celular }}
                    </div>

                    <div class="text-muted d-flex align-items-center justify-content-center">
                        <i class="fas fa-envelope me-2"></i>
                        {{ auth()->user()->email }}
                    </div>

                    <!-- Botones de acción -->
                    <div class="d-grid gap-3 mt-4">
                        <a href="{{ route('EditarperfilIndex', [encrypt(auth()->user()->id)]) }}"
                            class="btn btn-primary">
                            <i class="fas fa-user-edit me-2"></i>Editar Perfil
                        </a>

                        <a href="{{ route('CambiarContrasena', [encrypt(auth()->user()->id)]) }}"
                            class="btn btn-outline-primary">
                            <i class="fas fa-key me-2"></i>Cambiar Contraseña
                        </a>
                    </div>

                    @if (auth()->user()->hasRole('Administrador') || auth()->user()->hasRole('Docente'))
                        <div class="mt-4 pt-4 border-top">
                            <h5 class="mb-3 fw-semibold">Documento CV</h5>
                            @if (auth()->user()->cv_file == '')
                                <div class="alert alert-warning py-2 d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <span>No se ha cargado hoja de vida</span>
                                </div>
                            @else
                                <a href="{{ asset('storage/' . auth()->user()->cv_file) }}"
                                    class="btn btn-outline-info w-100">
                                    <i class="fas fa-file-pdf me-2"></i>Ver Hoja de Vida
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Columna de información (derecha) -->
        <div class="col-lg-8">
            @yield('content')

            <!-- Sección de datos personales -->
            <div class="card shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white p-4 d-flex justify-content-between align-items-center border-0">
                    <h4 class="mb-0 fw-semibold">Información Personal</h4>
                    <span class="badge bg-light text-muted px-3 py-2">
                        <i class="fas fa-lock me-1"></i>Solo lectura
                    </span>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Nombre</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" class="form-control-plaintext ps-2" value="{{ auth()->user()->name }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" class="form-control-plaintext ps-2" value="{{ auth()->user()->email }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small">Apellido Paterno</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-user-tag"></i>
                                </span>
                                <input type="text" class="form-control-plaintext ps-2" value="{{ auth()->user()->lastname1 }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small">Apellido Materno</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-user-tag"></i>
                                </span>
                                <input type="text" class="form-control-plaintext ps-2" value="{{ auth()->user()->lastname2 }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de contacto -->
            <div class="card shadow-sm rounded-4">
                <div class="card-header bg-white p-4 border-0">
                    <h4 class="mb-0 fw-semibold">Información de Contacto</h4>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Teléfono Celular</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-phone"></i>
                                </span>
                                <input type="text" class="form-control-plaintext ps-2" value="{{ auth()->user()->Celular }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small">Ubicación</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-map-marker-alt"></i>
                                </span>
                                <input type="text" class="form-control-plaintext ps-2"
                                       value="{{ auth()->user()->CiudadReside }}, {{ auth()->user()->PaisReside }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para cambiar la foto -->
<div class="modal fade" id="avatarModal" tabindex="-1" aria-labelledby="avatarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 bg-light">
                <h5 class="modal-title" id="avatarModalLabel">
                    <i class="fas fa-camera me-2"></i>Actualizar Foto de Perfil
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body px-4 py-4">
                <!-- Vista previa de la imagen -->
                <div class="text-center mb-4 position-relative">
                    <div class="preview-container mx-auto">
                        <img id="preview" class="rounded-circle border shadow-sm"
                            src="{{ $avatarUrl }}" style="width: 180px; height: 180px; object-fit: cover;">
                        <div class="preview-overlay">
                            <i class="fas fa-camera fa-2x text-white"></i>
                        </div>
                    </div>
                    <div class="small text-muted mt-3">
                        Haga clic en el botón para seleccionar una imagen
                    </div>
                </div>

                <!-- Formulario para subir la imagen -->
                <form method="POST" action="{{ route('avatar') }}" enctype="multipart/form-data" id="uploadForm">
                    @csrf
                    <input type="hidden" name="id" value="{{ auth()->user()->id }}">

                    <div class="mb-4">
                        <div class="input-group">
                            <input type="file" class="form-control" id="avatarInput" name="avatar" accept="image/*">
                            <label class="input-group-text" for="avatarInput">
                                <i class="fas fa-image me-2"></i>Elegir
                            </label>
                        </div>
                        <div class="form-text text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Se recomiendan imágenes cuadradas de al menos 200x200 píxeles
                        </div>
                    </div>

                    <div class="d-flex justify-content-between gap-3">
                        <button type="button" class="btn btn-light flex-grow-1" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="fas fa-upload me-2"></i>Subir Imagen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .preview-container {
        position: relative;
        display: inline-block;
        cursor: pointer;
    }

    .preview-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .preview-container:hover .preview-overlay {
        opacity: 1;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        object-fit: cover;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .profile-avatar:hover {
        transform: scale(1.05);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .avatar-container {
        margin-top: -50px;
    }

    .profile-avatar-wrapper {
        position: absolute;
        top: 30px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1;
    }

    .profile-header-cover {
        height: 100px;
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
        background: linear-gradient(45deg, #1a73e8, #34a853);
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const avatarInput = document.getElementById('avatarInput');
    const previewImg = document.getElementById('preview');
    const avatarImg = document.getElementById('avatar');
    const uploadForm = document.getElementById('uploadForm');

    if (avatarInput) {
        avatarInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                // Validar tamaño (máximo 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'La imagen no debe superar los 5MB',
                        confirmButtonText: 'Entendido'
                    });
                    this.value = '';
                    return;
                }

                // Validar tipo
                if (!file.type.startsWith('image/')) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Por favor, selecciona una imagen válida (JPG, PNG, GIF)',
                        confirmButtonText: 'Entendido'
                    });
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    if (previewImg) previewImg.src = e.target.result;
                    if (avatarImg) avatarImg.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    if (uploadForm) {
        uploadForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(this);

            // Mostrar loading
            Swal.fire({
                title: 'Subiendo imagen...',
                text: 'Por favor espere...',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Enviar el formulario
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    return response.text().then(text => {
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            // Si no es JSON, asumimos éxito
                            return { success: true };
                        }
                    });
                }
                throw new Error('Error en la subida');
            })
            .then(result => {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'La imagen se ha actualizado correctamente',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.reload();
                });
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al subir la imagen. Por favor, intenta de nuevo.',
                    confirmButtonText: 'Entendido'
                });
            });
        });
    }
});
</script>
@endpush

@endsection




<script>
    function resizeAndSubmit() {
        const fileInput = document.getElementById('avatarInput');
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');

        const file = fileInput.files[0];
        const reader = new FileReader();

        reader.onload = function(event) {
            const img = new Image();
            img.onload = function() {
                const MAX_WIDTH = 512;
                const MAX_HEIGHT = 512;
                let width = img.width;
                let height = img.height;

                if (width > height) {
                    if (width > MAX_WIDTH) {
                        height *= MAX_WIDTH / width;
                        width = MAX_WIDTH;
                    }
                } else {
                    if (height > MAX_HEIGHT) {
                        width *= MAX_HEIGHT / height;
                        height = MAX_HEIGHT;
                    }
                }

                canvas.width = width;
                canvas.height = height;

                ctx.drawImage(img, 0, 0, width, height);
                const resizedImageData = canvas.toDataURL('image/jpeg');

                // Crear un nuevo FormData y agregar la imagen redimensionada
                const formData = new FormData(document.getElementById('uploadForm'));
                const resizedImageBlob = dataURItoBlob(resizedImageData);
                formData.set('avatar', resizedImageBlob, 'avatar.jpg');

                // Enviar el formulario al servidor
                fetch('tu/ruta/de/envio', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        // Manejar la respuesta del servidor
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            };

            img.src = event.target.result;
        };

        reader.readAsDataURL(file);
    }

    function dataURItoBlob(dataURI) {
        const byteString = atob(dataURI.split(',')[1]);
        const mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
        const ab = new ArrayBuffer(byteString.length);
        const ia = new Uint8Array(ab);

        for (let i = 0; i < byteString.length; i++) {
            ia[i] = byteString.charCodeAt(i);
        }

        return new Blob([ab], {
            type: mimeString
        });
    }
</script>

<script>
    document.getElementById('avatarInput').addEventListener('change', function() {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('avatarForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the form from submitting normally

        // You can add your code here to save the avatar, for example, sending it to a server via AJAX.
        // Here's a simple example:
        var avatarDataUrl = document.getElementById('avatar').src;
        console.log('Avatar data URL:', avatarDataUrl);
    });
</script>



@include('layout')
