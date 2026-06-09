{{-- ================================================
     MODAL 1: ACCESO REQUERIDO (GUEST)
================================================ --}}
@guest
    <div class="modal fade" id="loginRequiredModal" tabindex="-1" aria-labelledby="loginRequiredModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 1.25rem; overflow: hidden;">
                <div class="modal-header border-0 bg-warning text-dark p-4 pb-2">
                    <h5 class="modal-title fw-bold mx-auto" id="loginRequiredModalLabel">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Acceso Requerido
                    </h5>
                    <button type="button" class="btn-close position-absolute end-0 me-4" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-5 pt-3">
                    <div class="mb-4 mt-2">
                        <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 rounded-circle" style="width: 90px; height: 90px;">
                            <i class="bi bi-lock-fill text-warning" style="font-size: 3rem; animation: lockPulse 2s infinite;"></i>
                        </div>
                    </div>
                    <h4 class="mb-3 fw-bold">Sesión necesaria</h4>
                    <p class="text-muted mb-4 fs-6 px-2">
                        Para inscribirte o realizar compras, necesitas iniciar sesión o crear una cuenta gratuita.
                    </p>

                    <div class="alert alert-info border-0 rounded-4 p-4 text-start" style="background: linear-gradient(135deg, rgba(227,242,253,0.8) 0%, rgba(187,222,251,0.8) 100%); border-left: 4px solid var(--color-primary) !important;">
                        <h6 class="alert-heading text-primary fw-bold mb-3">
                            <i class="bi bi-gift-fill me-2"></i>Beneficios de registrarte:
                        </h6>
                        <ul class="mb-0 text-dark opacity-75 list-unstyled">
                            <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2 fw-bold"></i>Acceso a todo el material</li>
                            <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2 fw-bold"></i>Certificados validados</li>
                            <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2 fw-bold"></i>Seguimiento de tu progreso</li>
                            <li><i class="bi bi-check2-circle text-primary me-2 fw-bold"></i>Soporte personalizado</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer justify-content-center border-0 bg-light p-4">
                    <div class="row w-100 g-3">
                        <div class="col-12 col-md-6">
                            <a href="{{ route('login') }}" class="btn btn-primary w-100 py-3 rounded-pill fw-semibold shadow-sm transition-all text-nowrap">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                            </a>
                        </div>
                        <div class="col-12 col-md-6">
                            <a href="{{ route('signin') }}" class="btn btn-outline-primary w-100 py-3 rounded-pill fw-semibold shadow-sm bg-white transition-all text-nowrap">
                                <i class="bi bi-person-plus-fill me-2"></i>Crear Cuenta
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endguest

<style>
    @keyframes lockPulse {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }
    .hover-border-primary:hover { border-color: var(--bs-primary) !important; background-color: rgba(var(--bs-primary-rgb), 0.05); }
    .hover-border-success:hover { border-color: var(--bs-success) !important; background-color: rgba(var(--bs-success-rgb), 0.05); }
    .card-opcion { transition: all 0.3s ease; }
    .card-opcion:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
    .form-floating > label { padding-left: 1.5rem; }
</style>

{{-- ================================================
     CONGRESO REGISTRATION FLOW
================================================ --}}
@if ($cursos->tipo == 'congreso' && $cursos->certificados_disponibles)
    <div class="modal fade" id="opcionesRegistroModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 1.25rem;">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-5 pt-2">
                    <div class="mb-4">
                        <i class="bi bi-envelope-check text-primary" style="font-size: 3.5rem;"></i>
                    </div>
                    <h3 class="fw-bold mb-2">¡Bienvenido!</h3>
                    <p class="text-muted mb-4 fs-6">Para comenzar, por favor ingresa tu correo electrónico.</p>

                    <form id="checkEmailForm" onsubmit="event.preventDefault(); window.checkUserEmail();">
                        <div class="form-floating mb-4 shadow-sm rounded-4">
                            <input type="email" class="form-control rounded-4 border-0 bg-light" id="identificadorEmail" placeholder="tu@email.com" required>
                            <label for="identificadorEmail" class="text-muted"><i class="bi bi-envelope me-2"></i>Correo Electrónico</label>
                        </div>

                        <button type="submit" id="btnCheckEmail" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm transition-all px-4">
                            Continuar <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 1.25rem;">
                <div class="modal-header border-0 bg-success bg-opacity-10 p-4">
                    <h5 class="modal-title fw-bold text-success mx-auto">
                        <i class="bi bi-person-check-fill me-2"></i>Verificación de Asistencia
                    </h5>
                    <button type="button" class="btn-close position-absolute end-0 me-4" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 p-md-5">
                    <p class="text-center text-muted mb-4 fs-6">Ingresa el correo con el que te registraste para acceder a tu certificado.</p>

                    <form action="{{ route('congreso.inscribir') }}" method="POST">
                        @csrf
                        <input type="hidden" name="congreso_id" value="{{ $cursos->id }}">

                        <div class="form-floating mb-4 shadow-sm rounded-4">
                            <input type="email" class="form-control rounded-4 border-0" id="loginEmail" name="email" placeholder="tu@email.com" required>
                            <label for="loginEmail" class="text-muted"><i class="bi bi-envelope me-2"></i>Correo Electrónico</label>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-3 rounded-pill fw-bold shadow-sm transition-all px-4">
                            <i class="bi bi-award-fill me-2"></i>Obtener Certificado
                        </button>
                    </form>
                </div>
                <div class="modal-footer justify-content-center border-0 bg-light p-3 rounded-bottom-4">
                    <small class="text-muted">
                        ¿No tienes cuenta?
                        <a href="#" class="fw-bold text-success text-decoration-none" data-bs-toggle="modal" data-bs-target="#registroCongresoModal" data-bs-dismiss="modal">
                            Regístrate aquí
                        </a>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="registroCongresoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 1.25rem;">
                <div class="modal-header border-0 bg-primary p-4 pb-3">
                    <h5 class="modal-title fw-bold text-white mb-0">
                        <i class="bi bi-person-badge-fill me-2"></i>Ficha de Registro
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 p-md-5 bg-light pb-4">
                    @if ($errors->any())
                        <div class="alert alert-danger shadow-sm border-0 rounded-4 mb-4">
                            <h6 class="alert-heading fw-bold mb-2"><i class="bi bi-exclamation-octagon-fill me-2"></i>Atención:</h6>
                            <ul class="mb-0 small px-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('registrarseCongreso', encrypt($cursos->id)) }}" method="POST" id="formRegistroCongreso">
                        @csrf
                        
                        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                                <h6 class="fw-bold text-primary mb-0"><i class="bi bi-person-vcard me-2"></i>Datos Personales</h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control rounded-3 bg-light border-0" id="name" name="name" placeholder="Nombre" value="{{ old('name') }}" required>
                                            <label for="name" class="text-muted">Nombre(s)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control rounded-3 bg-light border-0" id="lastname1" name="lastname1" placeholder="Paterno" value="{{ old('lastname1') }}" required>
                                            <label for="lastname1" class="text-muted">Ap. Paterno</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control rounded-3 bg-light border-0" id="lastname2" name="lastname2" placeholder="Materno" value="{{ old('lastname2') }}">
                                            <label for="lastname2" class="text-muted">Ap. Materno</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="email" class="form-control rounded-3 bg-light border-0" id="email" name="email" placeholder="Correo" value="{{ old('email') }}" required>
                                            <label for="email" class="text-muted"><i class="bi bi-envelope me-1"></i> Correo Electrónico</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select class="form-select rounded-3 bg-light border-0" id="country" name="country" required>
                                                <option value="" selected disabled>Sector / País</option>
                                                <!-- Llenado por JS dinámico original -->
                                            </select>
                                            <label for="country" class="text-muted"><i class="bi bi-globe-americas me-1"></i> País</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                                <h6 class="fw-bold text-primary mb-0"><i class="bi bi-shield-lock me-2"></i>Seguridad de la Cuenta</h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating position-relative">
                                            <input type="password" class="form-control rounded-3 bg-light border-0 pe-5" id="password" name="password" placeholder="Contraseña" required>
                                            <label for="password" class="text-muted">Contraseña (Mín. 8)</label>
                                            <button type="button" class="btn btn-link link-secondary position-absolute top-50 end-0 translate-middle-y text-decoration-none toggle-password" data-target="password" style="z-index: 5;">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating position-relative">
                                            <input type="password" class="form-control rounded-3 bg-light border-0 pe-5" id="password_confirmation" name="password_confirmation" placeholder="Confirmar" required>
                                            <label for="password_confirmation" class="text-muted">Confirmar Contraseña</label>
                                            <button type="button" class="btn btn-link link-secondary position-absolute top-50 end-0 translate-middle-y text-decoration-none toggle-password" data-target="password_confirmation" style="z-index: 5;">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 py-3 fw-bold shadow-sm transition-all hover-lift w-100 w-md-auto">
                                <i class="bi bi-check2-circle me-2"></i>Confirmar Registro Exitoso
                            </button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center border-0 bg-white p-3 rounded-bottom-4 shadow-sm">
                    <small class="text-muted">
                        ¿Ya tienes una cuenta registrada?
                        <a href="{{ route('login.signin') }}" class="fw-bold text-primary text-decoration-none ms-1">Inicia sesión</a>
                    </small>
                </div>
            </div>
        </div>
    </div>
@endif

{{-- ================================================
     MODAL DE COMPRA / COMPRA CURSO (AUTH)
================================================ --}}
@auth
    <div class="modal fade" id="compraCursoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 1.25rem;">
                <div class="modal-header border-0 bg-primary p-4">
                    <h5 class="modal-title fw-bold text-white">
                        <i class="bi bi-cart-check-fill me-2"></i>
                        {{ $cursos->precio > 0 ? 'Completar Compra' : 'Confirmar Inscripción' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('registrarpagoPost') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4 p-md-5 bg-light">
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-body p-4">
                                <div class="row align-items-center g-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-bold mb-1"><i class="bi bi-person-vcard me-1"></i> Estudiante</label>
                                        <div class="p-3 bg-light rounded-3 fw-medium text-dark border">
                                            {{ auth()->user()->name }} {{ auth()->user()->lastname1 }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-bold mb-1"><i class="bi bi-book me-1"></i> Curso / Evento</label>
                                        <div class="p-3 bg-light rounded-3 fw-medium text-dark border text-truncate">
                                            {{ $cursos->nombreCurso }}
                                        </div>
                                        <input type="hidden" name="curso_id" value="{{ $cursos->id }}">
                                        <input type="hidden" name="estudiante_id" value="{{ auth()->user()->id }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($cursos->precio > 0)
                            <div class="row g-4 mb-4">
                                <div class="col-md-5">
                                    <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-4 border-warning">
                                        <div class="card-body p-4 d-flex flex-column justify-content-center">
                                            <label class="form-label text-muted small fw-bold"><i class="bi bi-cash-coin me-1"></i> Total a Pagar</label>
                                            <div class="display-6 fw-bold text-dark mb-0">Bs {{ number_format($cursos->precio, 2) }}</div>
                                            <input type="hidden" name="montopagar" value="{{ $cursos->precio }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="card border-0 shadow-sm rounded-4 h-100">
                                        <div class="card-body p-4">
                                            <label class="form-label text-muted small fw-bold"><i class="bi bi-receipt me-1"></i> Subir Comprobante</label>
                                            <input type="file" name="comprobante" class="form-control form-control-lg bg-light border-0" accept=".pdf,.jpg,.jpeg,.png" required>
                                            <div class="form-text mt-2"><i class="bi bi-info-circle me-1"></i>Solo PDF, JPG o PNG. Tamaño máximo 2MB.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-body p-4">
                                <label class="form-label text-muted small fw-bold"><i class="bi bi-chat-left-text me-1"></i> Notas / Descripción</label>
                                <textarea name="descripcion" class="form-control bg-light border-0 rounded-3" rows="2" required placeholder="Algún detalle adicional sobre la inscripción..."></textarea>
                            </div>
                        </div>

                        @if ($cursos->precio > 0)
                            <div class="card border-0 shadow-sm rounded-4 bg-white">
                                <div class="card-body p-4">
                                    <h6 class="fw-bold text-center mb-4 text-primary"><i class="bi bi-credit-card-2-front me-2"></i>Opciones de Depósito</h6>
                                    
                                    @if ($metodosPago->where('is_active', true)->count() > 0)
                                        <div id="paymentMethodsCarousel" class="carousel carousel-dark slide" data-bs-ride="carousel">
                                            <div class="carousel-inner px-5 py-2">
                                                @foreach ($metodosPago->where('is_active', true)->sortBy('sort_order') as $index => $metodo)
                                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                        <div class="text-center">
                                                            <div class="badge bg-primary-subtle text-primary mb-3 px-3 py-2 rounded-pill fs-6">{{ $metodo->name }}</div>
                                                            @if ($metodo->qr_image)
                                                                <div class="mb-4">
                                                                    <img src="{{ $metodo->qr_image_url }}" alt="QR" class="img-fluid rounded-4 shadow-sm border p-2 bg-white" style="max-height: 200px;">
                                                                </div>
                                                            @endif
                                                            <div class="row text-start justify-content-center mx-auto g-2" style="max-width: 400px;">
                                                                @if ($metodo->account_holder)
                                                                    <div class="col-12 bg-light p-2 rounded-3 border">
                                                                        <span class="text-muted small d-block">Titular</span>
                                                                        <span class="fw-bold">{{ $metodo->account_holder }}</span>
                                                                    </div>
                                                                @endif
                                                                @if ($metodo->account_number)
                                                                    <div class="col-12 bg-light p-2 rounded-3 border">
                                                                        <span class="text-muted small d-block">Número de Cuenta</span>
                                                                        <span class="fw-bold font-monospace fs-5">{{ $metodo->account_number }}</span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            
                                            @if ($metodosPago->where('is_active', true)->count() > 1)
                                                <button class="carousel-control-prev" type="button" data-bs-target="#paymentMethodsCarousel" data-bs-slide="prev">
                                                    <span class="carousel-control-prev-icon shadow-sm rounded-circle bg-white p-3" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Anterior</span>
                                                </button>
                                                <button class="carousel-control-next" type="button" data-bs-target="#paymentMethodsCarousel" data-bs-slide="next">
                                                    <span class="carousel-control-next-icon shadow-sm rounded-circle bg-white p-3" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Siguiente</span>
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        <div class="text-center p-4 bg-light rounded-4">
                                            <i class="bi bi-exclamation-circle text-warning mb-2" style="font-size: 2.5rem;"></i>
                                            <p class="mb-0 fw-medium">No hay métodos de pago configurados</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer border-0 p-4 bg-white rounded-bottom-4">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-2"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                            <i class="bi bi-check2-circle me-2"></i>{{ $cursos->precio > 0 ? 'Confirmar Compra' : 'Confirmar Inscripción' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endauth

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ===== REINICIAR TOGGLE PASSWORD VISIBILITY PARA MODALS =====
        document.querySelectorAll('.toggle-password').forEach(button => {
            // Remove listeners in case they were added multiple times
            const newBtn = button.cloneNode(true);
            button.parentNode.replaceChild(newBtn, button);
            
            newBtn.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');
                
                if (input && input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('bi-eye', 'bi-eye-slash');
                } else if (input) {
                    input.type = 'password';
                    icon.classList.replace('bi-eye-slash', 'bi-eye');
                }
            });
        });
    });
</script>

<script>
window.checkUserEmail = function() {
    const email = document.getElementById('identificadorEmail').value;
    const btn = document.getElementById('btnCheckEmail');
    
    if(!email) return;

    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Verificando...';
    btn.disabled = true;

    fetch('{{ route("api.check.email") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ email: email })
    })
    .then(response => response.json())
    .then(data => {
        btn.innerHTML = 'Continuar <i class="bi bi-arrow-right ms-2"></i>';
        btn.disabled = false;

        const modalEl = document.getElementById('opcionesRegistroModal');
        const modalInstance = bootstrap.Modal.getInstance(modalEl);
        if (modalInstance) {
            modalInstance.hide();
        }

        // Wait a slight delay for modal transition
        setTimeout(() => {
            if (data.exists) {
                document.getElementById('loginEmail').value = email;
                new bootstrap.Modal(document.getElementById('loginModal')).show();
            } else {
                document.getElementById('email').value = email;
                new bootstrap.Modal(document.getElementById('registroCongresoModal')).show();
            }
        }, 400);
    })
    .catch(error => {
        console.error('Error:', error);
        btn.innerHTML = 'Continuar <i class="bi bi-arrow-right ms-2"></i>';
        btn.disabled = false;
        alert('Ocurrió un error de red al verificar el correo. Por favor, intenta nuevamente.');
    });
}
</script>