@section('titulo')
    Perfil {{ $usuario->name }} {{ $usuario->lastname1 }} {{ $usuario->lastname2 }}
@endsection

@section('content')
<div class="container py-5 profile-container-modern">
    <div class="row g-4">

        {{-- ═══ COLUMNA IZQUIERDA ═══════════════════════════ --}}
        <div class="col-lg-4">
            <div class="profile-card-modern">

                {{-- Cover --}}
                <div class="profile-cover-section"></div>

                {{-- Avatar --}}
                <div class="avatar-container">
                    @php
                        $avatarUrl   = $usuario->avatar
                            ? asset('storage/' . $usuario->avatar)
                            : asset('./assets/img/user.png');
                        $isOwnProfile = auth()->user()->id === $usuario->id;
                        $isAdmin      = auth()->user()->hasRole('Administrador');
                    @endphp

                    <div class="avatar-container-modern {{ $isOwnProfile ? 'cursor-pointer' : '' }}">
                        <img id="avatar"
                             src="{{ $avatarUrl }}"
                             class="avatar-image-modern"
                             @if($isOwnProfile)
                                 data-bs-toggle="modal"
                                 data-bs-target="#avatarModal"
                                 title="Cambiar imagen de perfil"
                             @endif
                             alt="Avatar de {{ $usuario->name }}">

                        @if($isOwnProfile)
                            <div class="avatar-edit-indicator">
                                <i class="bi bi-camera-fill"></i>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Info --}}
                <div class="profile-info-section">
                    <h3 class="profile-user-name">
                        {{ $usuario->name }} {{ $usuario->lastname1 }}
                    </h3>

                    <span class="profile-role-badge">
                        <i class="bi bi-shield-check"></i>
                        {{ $usuario->roles->pluck('name')[0] ?? 'Sin rol' }}
                    </span>

                    {{-- Contact --}}
                    <div class="profile-contact-info">
                        <div class="contact-item">
                            <i class="bi bi-geo-alt-fill"></i>
                            <span>{{ $usuario->CiudadReside }}, {{ $usuario->PaisReside }}</span>
                        </div>
                        <div class="contact-item">
                            <i class="bi bi-telephone-fill"></i>
                            <span>{{ $usuario->Celular }}</span>
                        </div>
                        <div class="contact-item">
                            <i class="bi bi-envelope-fill"></i>
                            <span>{{ $usuario->email }}</span>
                        </div>
                    </div>

                    {{-- Actions --}}
                    @if ($isAdmin || $isOwnProfile)
                        <div class="profile-actions">
                            @if($isOwnProfile)
                                <a href="{{ route('EditarperfilIndex', [encrypt($usuario->id)]) }}"
                                   class="btn-profile-action btn-primary-action">
                                    <i class="bi bi-pencil-square"></i>
                                    <span>Editar Perfil</span>
                                </a>
                            @else
                                <a href="{{ route('EditarperfilUser', [encrypt($usuario->id)]) }}"
                                   class="btn-profile-action btn-primary-action">
                                    <i class="bi bi-pencil-square"></i>
                                    <span>Editar Usuario</span>
                                </a>
                            @endif

                            <a href="{{ route('CambiarContrasena', [encrypt($usuario->id)]) }}"
                               class="btn-profile-action btn-secondary-action">
                                <i class="bi bi-key-fill"></i>
                                <span>Cambiar Contraseña</span>
                            </a>
                        </div>
                    @endif

                    {{-- CV --}}
                    @if ($isAdmin || $usuario->hasRole('Docente'))
                        <div class="cv-section">
                            <div class="cv-header">
                                <i class="bi bi-file-earmark-text-fill"></i>
                                <h5>Documento CV</h5>
                            </div>

                            @if ($usuario->cv_file == '')
                                <div class="cv-alert">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                    <span>No se ha cargado hoja de vida</span>
                                </div>
                            @else
                                <a href="{{ asset('storage/' . $usuario->cv_file) }}"
                                   class="btn-cv-view" target="_blank">
                                    <i class="bi bi-file-pdf-fill"></i>
                                    <span>Ver Hoja de Vida</span>
                                </a>
                            @endif
                        </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- ═══ COLUMNA DERECHA ══════════════════════════════ --}}
        <div class="col-lg-8">

            {{-- Información Personal --}}
            <div class="info-card-modern">
                <div class="info-card-header">
                    <div class="info-card-title-wrapper">
                        <i class="bi bi-person-lines-fill"></i>
                        <h4>Información Personal</h4>
                    </div>
                    <span class="readonly-badge">
                        <i class="bi bi-lock-fill me-1"></i>Solo lectura
                    </span>
                </div>
                <div class="info-card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-field">
                                <label class="info-label">
                                    <i class="bi bi-person-fill"></i> Nombre
                                </label>
                                <div class="info-value">{{ $usuario->name }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-field">
                                <label class="info-label">
                                    <i class="bi bi-envelope-fill"></i> Correo Electrónico
                                </label>
                                <div class="info-value">{{ $usuario->email }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-field">
                                <label class="info-label">
                                    <i class="bi bi-person-badge-fill"></i> Apellido Paterno
                                </label>
                                <div class="info-value">{{ $usuario->lastname1 }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-field">
                                <label class="info-label">
                                    <i class="bi bi-person-badge"></i> Apellido Materno
                                </label>
                                <div class="info-value">{{ $usuario->lastname2 }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Información de Contacto --}}
            <div class="info-card-modern mt-4">
                <div class="info-card-header">
                    <div class="info-card-title-wrapper">
                        <i class="bi bi-telephone-fill"></i>
                        <h4>Información de Contacto</h4>
                    </div>
                </div>
                <div class="info-card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-field">
                                <label class="info-label">
                                    <i class="bi bi-phone-fill"></i> Teléfono Celular
                                </label>
                                <div class="info-value">{{ $usuario->Celular }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-field">
                                <label class="info-label">
                                    <i class="bi bi-geo-alt-fill"></i> Ubicación
                                </label>
                                <div class="info-value">
                                    {{ $usuario->CiudadReside }}, {{ $usuario->PaisReside }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tutor --}}
            @if (count($tutor) > 0)
                @foreach($tutor as $t)
                    <div class="info-card-modern mt-4">
                        <div class="info-card-header">
                            <div class="info-card-title-wrapper">
                                <i class="bi bi-person-heart"></i>
                                <h4>Datos del Tutor / Representante</h4>
                            </div>
                        </div>
                        <div class="info-card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="info-field">
                                        <label class="info-label">Nombre Completo</label>
                                        <div class="info-value">
                                            {{ $t->nombreTutor }} {{ $t->appaternoTutor }} {{ $t->apmaternoTutor }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-field">
                                        <label class="info-label">Cédula de Identidad</label>
                                        <div class="info-value">{{ $t->CI }}</div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="info-field">
                                        <label class="info-label">Dirección</label>
                                        <div class="info-value">{{ $t->Direccion }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            {{-- Datos Profesionales (Docente) --}}
            @if ($usuario->hasRole('Docente') && count($atributosD) > 0)
                <div class="info-card-modern mt-4">
                    <div class="info-card-header">
                        <div class="info-card-title-wrapper">
                            <i class="bi bi-mortarboard-fill"></i>
                            <h4>Formación y Experiencia</h4>
                        </div>
                    </div>
                    <div class="info-card-body">
                        @foreach ($atributosD as $at)
                            <div class="row g-3 mb-4 pb-3" style="border-bottom:1px solid rgba(0,0,0,0.07);">
                                <div class="col-md-4">
                                    <div class="info-field">
                                        <label class="info-label">Formación</label>
                                        <div class="info-value">{{ $at->formacion }}</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-field">
                                        <label class="info-label">Especialización</label>
                                        <div class="info-value">{{ $at->Especializacion }}</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-field">
                                        <label class="info-label">Experiencia</label>
                                        <div class="info-value">{{ $at->ExperienciaL }} años</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if(count($trabajos) > 0)
                            <div class="section-mini-title mt-2">
                                <i class="bi bi-briefcase-fill"></i> Historial Laboral
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Empresa</th>
                                            <th>Cargo</th>
                                            <th>Periodo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($trabajos as $tr)
                                            <tr>
                                                <td>{{ $tr->empresa }}</td>
                                                <td>{{ $tr->cargo }}</td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($tr->fecha_inicio)->format('d/m/Y') }} –
                                                    {{ $tr->fecha_fin
                                                        ? \Carbon\Carbon::parse($tr->fecha_fin)->format('d/m/Y')
                                                        : 'Actualidad' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @yield('extra_content')

        </div>{{-- /col-lg-8 --}}
    </div>{{-- /row --}}
</div>{{-- /container --}}


{{-- ═══ MODAL AVATAR (solo perfil propio) ════════════════ --}}
@if($isOwnProfile)
<div class="modal fade" id="avatarModal" tabindex="-1" aria-labelledby="avatarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content modal-modern">

            <div class="modal-header-avatar">
                <div class="modal-title-wrapper">
                    <i class="bi bi-camera-fill modal-icon-avatar"></i>
                    <h5 class="modal-title" id="avatarModalLabel">Actualizar Foto de Perfil</h5>
                </div>
                <button type="button" class="btn-close-avatar" data-bs-dismiss="modal" aria-label="Cerrar">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="modal-body-avatar">
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

                <form method="POST" action="{{ route('avatar') }}" enctype="multipart/form-data" id="uploadForm">
                    @csrf
                    <input type="hidden" name="id" value="{{ $usuario->id }}">

                    <div class="upload-section">
                        <input type="file" class="upload-input" id="avatarInput" name="avatar" accept="image/*">
                        <label class="upload-label" for="avatarInput">
                            <i class="bi bi-cloud-upload me-2"></i>
                            Seleccionar Imagen
                        </label>
                        <div class="upload-hint">
                            <i class="bi bi-lightbulb me-1"></i>
                            Imágenes cuadradas de al menos 200×200 píxeles
                        </div>
                    </div>

                    <div class="modal-actions-avatar">
                        <button type="button" class="btn-modal-action btn-cancel" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i> Cancelar
                        </button>
                        <button type="submit" class="btn-modal-action btn-upload">
                            <i class="bi bi-upload me-2"></i> Subir Imagen
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const avatarInput = document.getElementById('avatarInput');
    const previewImg  = document.getElementById('preview');
    const avatarImg   = document.getElementById('avatar');
    const uploadForm  = document.getElementById('uploadForm');

    avatarInput?.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        if (file.size > 5 * 1024 * 1024) {
            Swal.fire({ icon:'error', title:'Error', text:'La imagen no debe superar los 5 MB.', confirmButtonText:'Entendido' });
            this.value = ''; return;
        }
        if (!file.type.startsWith('image/')) {
            Swal.fire({ icon:'error', title:'Error', text:'Selecciona una imagen válida (JPG, PNG, GIF).', confirmButtonText:'Entendido' });
            this.value = ''; return;
        }

        const reader = new FileReader();
        reader.onload = e => {
            if (previewImg) previewImg.src = e.target.result;
            if (avatarImg)  avatarImg.src  = e.target.result;
        };
        reader.readAsDataURL(file);
    });

    uploadForm?.addEventListener('submit', function (e) {
        e.preventDefault();
        Swal.fire({ title:'Subiendo imagen…', text:'Por favor espere…', allowOutsideClick:false, showConfirmButton:false, didOpen:()=>Swal.showLoading() });

        fetch(this.action, { method:'POST', body:new FormData(this) })
            .then(r => r.ok ? r.text().then(t => { try { return JSON.parse(t); } catch { return { success:true }; } }) : Promise.reject())
            .then(() => Swal.fire({ icon:'success', title:'¡Éxito!', text:'Imagen actualizada correctamente.', showConfirmButton:false, timer:1500 }).then(() => location.reload()))
            .catch(() => Swal.fire({ icon:'error', title:'Error', text:'Hubo un problema al subir la imagen. Intenta de nuevo.', confirmButtonText:'Entendido' }));
    });
});
</script>
@endif

@endsection


@include('layout')
