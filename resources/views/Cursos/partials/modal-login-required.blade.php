{{-- Modal para usuarios no autenticados --}}
@guest
<div class="modal fade" id="loginRequiredModal" tabindex="-1" aria-labelledby="loginRequiredModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="loginRequiredModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Acceso Requerido
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-5">
                <div class="mb-4">
                    <i class="bi bi-lock-fill" style="font-size: 4rem; color: var(--orange-accent);"></i>
                </div>
                <h4 class="mb-3 fw-bold">Debes iniciar sesión para continuar</h4>
                <p class="text-muted mb-4">
                    Para realizar una compra o inscribirte necesitas tener una cuenta en nuestro sistema.
                </p>

                <div class="alert alert-info text-start">
                    <h6 class="alert-heading">
                        <i class="bi bi-gift-fill me-2"></i>Beneficios de registrarte:
                    </h6>
                    <ul class="mb-0 ps-4">
                        <li>Acceso a todos los cursos</li>
                        <li>Certificados digitales</li>
                        <li>Seguimiento de tu progreso</li>
                        <li>Soporte personalizado</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer justify-content-center bg-light p-4">
                <div class="d-grid gap-3 w-100">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg py-3">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                    </a>
                    <a href="{{ route('signin') }}" class="btn btn-success btn-lg py-3">
                        <i class="bi bi-person-plus-fill me-2"></i>Crear Cuenta Gratis
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endguest

<style>
    /* Estilos específicos para el modal de login required */
    #loginRequiredModal .modal-content {
        border: none;
        box-shadow: 0 20px 60px rgba(255, 165, 0, 0.2);
    }

    #loginRequiredModal .modal-header.bg-warning {
        background: var(--gradient-orange) !important;
    }

    #loginRequiredModal .bi-lock-fill {
        animation: lockPulse 2s infinite;
    }

    @keyframes lockPulse {
        0%, 100% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.1);
            opacity: 0.8;
        }
    }

    #loginRequiredModal .alert-info {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        border-left: 4px solid var(--color-primary);
        border-radius: 10px;
    }

    #loginRequiredModal .alert-info .alert-heading {
        color: var(--color-primary);
        font-weight: 600;
    }

    #loginRequiredModal .btn-lg {
        font-weight: 600;
        border-radius: 25px;
        transition: all 0.3s ease;
    }

    #loginRequiredModal .btn-lg:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(26, 71, 137, 0.3);
    }
</style>
