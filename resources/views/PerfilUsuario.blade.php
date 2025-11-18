@section('titulo')

Perfil {{$usuario->name}} {{$usuario->lastname1}} {{$usuario->lastname2}}

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
    <div class="row">
        <!-- Columna de perfil (izquierda) -->
        <div class="col-lg-4 mb-4">
            <div class="card-modern">
                <div class="card-header-modern text-center">
                    <h4 class="card-title-modern mb-0">Perfil de Usuario</h4>
                </div>

                <div class="text-center position-relative avatar-container">
                    <!-- Overlay de fondo para la imagen de perfil -->
                    <div class="profile-header-cover bg-gradient-info" style="height: 100px;"></div>

                    <!-- Avatar -->
                    <div class="profile-avatar-wrapper">
                        @php
                            $avatarUrl = $usuario->avatar
                                ? asset('storage/' . $usuario->avatar)
                                : asset('./assets/img/user.png');
                        @endphp

                        <img id="avatar" src="{{ $avatarUrl }}"
                            class="profile-avatar rounded-circle border border-white shadow"
                            data-toggle="modal" data-target="#avatarModal"
                            alt="Avatar del usuario" title="Cambiar imagen de perfil">
                    </div>
                </div>

                <div class="card-body text-center pt-5 mt-3">
                    <h3 class="font-weight-bold">
                        {{ $usuario->name }} {{ $usuario->lastname1 }}
                    </h3>

                    <div class="text-bg-secondary rounded mb-3">
                        {{ $usuario->roles->pluck('name')[0] }}
                    </div>

                    <div class="text-muted mb-3">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        {{ $usuario->CiudadReside }}, {{ $usuario->PaisReside }}
                    </div>

                    <div class="text-muted mb-3">
                        <i class="fas fa-phone mr-2"></i>
                        {{ $usuario->Celular }}
                    </div>

                    <div class="text-muted">
                        <i class="fas fa-envelope mr-2"></i>
                        {{ $usuario->email }}
                    </div>

                    <!-- Botones de acción -->
                    @if (auth()->user()->hasRole('Administrador') )
                    <div class="mt-4">
                        <a href="{{ route('EditarperfilUser', [encrypt($usuario->id)]) }}" class="btn-modern btn-primary-custom w-100">
                            <i class="fas fa-user-edit mr-1"></i><span class="ms-1">Editar Perfil</span>
                        </a>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('CambiarContrasena', [encrypt($usuario->id)]) }}" class="btn-modern btn-accent-custom w-100">
                            <i class="fas fa-key mr-1"></i><span class="ms-1">Cambiar Contraseña</span>
                        </a>
                    </div>
                    @endif

                    @if (auth()->user()->hasRole('Administrador') || $usuario->hasRole('Docente'))
                        <div class="mt-4 p-3 border-top">
                            <h5 class="mb-3">Documento CV</h5>
                            @if ($usuario->cv_file == '')
                                <div class="alert alert-warning py-2">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    No se ha cargado hoja de vida
                                </div>
                            @else
                                <a href="{{ asset('storage/' . $usuario->cv_file) }}" class="btn-modern btn-accent-custom w-100">
                                    <i class="fas fa-file-pdf mr-1"></i><span class="ms-1">Ver Hoja de Vida</span>
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
            <div class="card-modern mb-4">
                <div class="card-header-modern d-flex justify-content-between align-items-center">
                    <h4 class="card-title-modern mb-0">Información Personal</h4>
                    <span class="badge bg-light text-muted">
                        <i class="fas fa-lock mr-1"></i> Solo lectura
                    </span>
                </div>

                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small text-muted">Nombre</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control-modern" value="{{ $usuario->name }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small text-muted">Correo Electrónico</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input type="email" class="form-control-modern" value="{{ $usuario->email }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small text-muted">Apellido Paterno</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light"><i class="fas fa-user-tag"></i></span>
                                    </div>
                                    <input type="text" class="form-control-modern" value="{{ $usuario->lastname1 }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small text-muted">Apellido Materno</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light"><i class="fas fa-user-tag"></i></span>
                                    </div>
                                    <input type="text" class="form-control-modern" value="{{ $usuario->lastname2 }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de contacto -->
            <div class="card-modern">
                <div class="card-header-modern">
                    <h4 class="card-title-modern mb-0">Información de Contacto</h4>
                </div>

                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small text-muted">Teléfono Celular</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light"><i class="fas fa-phone"></i></span>
                                    </div>
                                    <input type="text" class="form-control-modern" value="{{ $usuario->Celular }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small text-muted">Ubicación</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light"><i class="fas fa-map-marker-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control-modern"
                                           value="{{ $usuario->CiudadReside }}, {{ $usuario->PaisReside }}" readonly>
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
<div class="modal fade" id="avatarModal" tabindex="-1" role="dialog" aria-labelledby="avatarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="avatarModalLabel">
                    <i class="fas fa-camera mr-2"></i>Actualizar Foto de Perfil
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <!-- Vista previa de la imagen -->
                <div class="text-center mb-4">
                    <img id="preview" class="rounded-circle border shadow"
                        src="{{ $avatarUrl }}" width="150" height="150">

                </div>

            </div>
        </div>
    </div>
</div>

<!-- Estilos adicionales -->
<style>
    .avatar-container {
        margin-top: -50px;
    }

    .profile-avatar-wrapper {
        position: absolute;
        top: 30px;
        left: 50%;
        transform: translateX(-50%);
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        object-fit: cover;
        cursor: pointer;
        transition: all 0.3s;
    }

    .profile-avatar:hover {
        transform: scale(1.05);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .profile-header-cover {
        border-top-left-radius: calc(0.3rem - 1px);
        border-top-right-radius: calc(0.3rem - 1px);
    }
</style>

<!-- Script para vista previa -->
<script>
    document.getElementById('avatarInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            if (!file.type.startsWith('image/')) {
                alert('Por favor, selecciona una imagen válida.');
                return;
            }

            // Actualizar el nombre del archivo en el label
            const fileName = file.name;
            const label = document.querySelector('label.custom-file-label');
            label.textContent = fileName.length > 25 ? fileName.substring(0, 22) + '...' : fileName;

            // Mostrar la vista previa de la imagen
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
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




