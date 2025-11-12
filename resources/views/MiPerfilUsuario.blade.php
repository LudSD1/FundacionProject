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

@section('content')
<div class="container py-5 profile-container-modern">
    <div class="row g-4">
        <!-- Columna de perfil (izquierda) -->
        <div class="col-lg-4">
            <div class="profile-card-modern">
                <!-- Header con gradiente -->
                <div class="profile-header-modern">
                    <div class="profile-header-content">
                        <i class="bi bi-person-circle profile-header-icon"></i>
                        <h4 class="profile-header-title">Perfil de Usuario</h4>
                    </div>
                </div>

                <!-- Avatar con cover -->
                <div class="profile-cover-section">
                    <div class="profile-cover-bg"></div>

                    <div class="profile-avatar-wrapper">
                        @php
                            $avatarUrl = auth()->user()->avatar
                                ? asset('storage/' . auth()->user()->avatar)
                                : asset('./assets/img/user.png');
                        @endphp

                        <div class="avatar-container-modern">
                            <img id="avatar" src="{{ $avatarUrl }}"
                                class="avatar-image-modern"
                                data-bs-toggle="modal"
                                data-bs-target="#avatarModal"
                                alt="Avatar del usuario"
                                title="Cambiar imagen de perfil">
                            <div class="avatar-edit-indicator">
                                <i class="bi bi-camera-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información del usuario -->
                <div class="profile-info-section">
                    <h3 class="profile-user-name">
                        {{ auth()->user()->name }} {{ auth()->user()->lastname1 }}
                    </h3>

                    <span class="profile-role-badge">
                        <i class="bi bi-shield-check me-1"></i>
                        {{ auth()->user()->roles->pluck('name')[0] }}
                    </span>

                    <!-- Información de contacto -->
                    <div class="profile-contact-info">
                        <div class="contact-item">
                            <i class="bi bi-geo-alt-fill"></i>
                            <span>{{ auth()->user()->CiudadReside }}, {{ auth()->user()->PaisReside }}</span>
                        </div>

                        <div class="contact-item">
                            <i class="bi bi-telephone-fill"></i>
                            <span>{{ auth()->user()->Celular }}</span>
                        </div>

                        <div class="contact-item">
                            <i class="bi bi-envelope-fill"></i>
                            <span>{{ auth()->user()->email }}</span>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="profile-actions">
                        <a href="{{ route('EditarperfilIndex', [encrypt(auth()->user()->id)]) }}"
                            class="btn-profile-action btn-primary-action">
                            <i class="bi bi-pencil-square me-2"></i>
                            <span>Editar Perfil</span>
                        </a>

                        <a href="{{ route('CambiarContrasena', [encrypt(auth()->user()->id)]) }}"
                            class="btn-profile-action btn-secondary-action">
                            <i class="bi bi-key-fill me-2"></i>
                            <span>Cambiar Contraseña</span>
                        </a>
                    </div>

                    <!-- CV Section -->
                    @if (auth()->user()->hasRole('Administrador') || auth()->user()->hasRole('Docente'))
                        <div class="cv-section">
                            <div class="cv-header">
                                <i class="bi bi-file-earmark-text-fill"></i>
                                <h5>Documento CV</h5>
                            </div>
                            @if (auth()->user()->cv_file == '')
                                <div class="cv-alert">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                    <span>No se ha cargado hoja de vida</span>
                                </div>
                            @else
                                <a href="{{ asset('storage/' . auth()->user()->cv_file) }}"
                                    class="btn-cv-view">
                                    <i class="bi bi-file-pdf-fill me-2"></i>
                                    <span>Ver Hoja de Vida</span>
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
            <div class="info-card-modern">
                <div class="info-card-header">
                    <div class="info-card-title-wrapper">
                        <i class="bi bi-person-lines-fill"></i>
                        <h4>Información Personal</h4>
                    </div>
                    <span class="readonly-badge">
                        <i class="bi bi-lock-fill me-1"></i>
                        Solo lectura
                    </span>
                </div>

                <div class="info-card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-field">
                                <label class="info-label">
                                    <i class="bi bi-person-fill"></i>
                                    Nombre
                                </label>
                                <div class="info-value">
                                    {{ auth()->user()->name }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-field">
                                <label class="info-label">
                                    <i class="bi bi-envelope-fill"></i>
                                    Correo Electrónico
                                </label>
                                <div class="info-value">
                                    {{ auth()->user()->email }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-field">
                                <label class="info-label">
                                    <i class="bi bi-person-badge-fill"></i>
                                    Apellido Paterno
                                </label>
                                <div class="info-value">
                                    {{ auth()->user()->lastname1 }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-field">
                                <label class="info-label">
                                    <i class="bi bi-person-badge"></i>
                                    Apellido Materno
                                </label>
                                <div class="info-value">
                                    {{ auth()->user()->lastname2 }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de contacto -->
            <div class="info-card-modern mt-4">
                <div class="info-card-header">
                    <div class="info-card-title-wrapper">
                        <i class="bi bi-telephone-fill"></i>
                        <h4>Información de Contacto</h4>
                    </div>
                </div>

                <div class="info-card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-field">
                                <label class="info-label">
                                    <i class="bi bi-phone-fill"></i>
                                    Teléfono Celular
                                </label>
                                <div class="info-value">
                                    {{ auth()->user()->Celular }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-field">
                                <label class="info-label">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    Ubicación
                                </label>
                                <div class="info-value">
                                    {{ auth()->user()->CiudadReside }}, {{ auth()->user()->PaisReside }}
                                </div>
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
        <div class="modal-content modal-modern">
            <div class="modal-header-avatar">
                <div class="modal-title-wrapper">
                    <i class="bi bi-camera-fill modal-icon-avatar"></i>
                    <h5 class="modal-title" id="avatarModalLabel">
                        Actualizar Foto de Perfil
                    </h5>
                </div>
                <button type="button" class="btn-close-avatar" data-bs-dismiss="modal" aria-label="Cerrar">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="modal-body-avatar">
                <!-- Vista previa de la imagen -->
                <div class="preview-section">
                    <div class="preview-container-avatar">
                        <img id="preview" src="{{ $avatarUrl }}" alt="Preview">
                        <div class="preview-overlay-avatar">
                            <i class="bi bi-camera fa-2x"></i>
                        </div>
                    </div>
                    <p class="preview-hint">
                        <i class="bi bi-info-circle me-1"></i>
                        Haga clic en el botón para seleccionar una imagen
                    </p>
                </div>

                <!-- Formulario -->
                <form method="POST" action="{{ route('avatar') }}" enctype="multipart/form-data" id="uploadForm">
                    @csrf
                    <input type="hidden" name="id" value="{{ auth()->user()->id }}">

                    <div class="upload-section">
                        <div class="upload-input-wrapper">
                            <input type="file"
                                   class="upload-input"
                                   id="avatarInput"
                                   name="avatar"
                                   accept="image/*">
                            <label class="upload-label" for="avatarInput">
                                <i class="bi bi-cloud-upload me-2"></i>
                                Seleccionar Imagen
                            </label>
                        </div>
                        <div class="upload-hint">
                            <i class="bi bi-lightbulb me-1"></i>
                            Se recomiendan imágenes cuadradas de al menos 200x200 píxeles
                        </div>
                    </div>

                    <div class="modal-actions-avatar">
                        <button type="button" class="btn-modal-action btn-cancel" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>
                            Cancelar
                        </button>
                        <button type="submit" class="btn-modal-action btn-upload">
                            <i class="bi bi-upload me-2"></i>
                            Subir Imagen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- Modal para cambiar la foto -->
    {{-- <div class="modal fade" id="avatarModal" tabindex="-1" aria-labelledby="avatarModalLabel" aria-hidden="true">
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
                            <img id="preview" class="rounded-circle border shadow-sm" src="{{ $avatarUrl }}"
                                style="width: 180px; height: 180px; object-fit: cover;">
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
                                <input type="file" class="form-control" id="avatarInput" name="avatar"
                                    accept="image/*">
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
    </div> --}}

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
            background: rgba(0, 0, 0, 0.5);
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


@endsection


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
                                    return {
                                        success: true
                                    };
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
