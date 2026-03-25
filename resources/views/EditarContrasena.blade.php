@extends('layout')

@section('titulo', 'Cambiar Contraseña')

@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="tbl-card shadow-lg">

                    <div class="tbl-card-hero">
                        <div class="tbl-hero-left">
                            <a href="javascript:history.back()" class="tbl-hero-btn tbl-hero-btn-glass prt-back-btn mb-2">
                                <i class="bi bi-arrow-left-circle-fill"></i> Volver
                            </a>
                            <div class="tbl-hero-eyebrow">
                                <i class="bi bi-shield-lock-fill"></i> Seguridad
                            </div>
                            <h2 class="tbl-hero-title">Cambiar Contraseña</h2>
                            <p class="tbl-hero-sub text-white-50">
                                Actualiza tus credenciales para mantener tu cuenta segura.
                            </p>
                        </div>
                        <div class="tbl-hero-controls d-none d-md-block">
                            <div class="tbl-avatar bg-white text-white"
                                style="width: 60px; height: 60px; font-size: 1.5rem;">
                                <i class="bi bi-key-fill"></i>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 p-md-5 bg-white">
                        <form action="{{ route('cambiarContrasenaPost', encrypt(auth()->user()->id)) }}" method="POST"
                            id="passwordForm">
                            @csrf

                            <div class="mb-4">
                                <label for="oldpassword" class="form-label fw-bold text-muted small mb-2 text-uppercase">
                                    <i class="bi bi-unlock me-1"></i> Contraseña Antigua
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-lock-fill text-primary"></i>
                                    </span>
                                    <input type="password" class="form-control border-start-0 bg-light" id="oldpassword"
                                        name="oldpassword" placeholder="••••••••" required>
                                    <button class="btn btn-light border border-start-0" type="button"
                                        onclick="togglePassword('oldpassword', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <hr class="my-4 opacity-50">

                            <div class="mb-4">
                                <label for="password" class="form-label fw-bold text-muted small mb-2 text-uppercase">
                                    <i class="bi bi-shield-plus me-1"></i> Nueva Contraseña
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-shield-lock text-primary"></i>
                                    </span>
                                    <input type="password" class="form-control border-start-0 bg-light" id="password"
                                        name="password" oninput="checkPasswordStrength()" placeholder="Mínimo 8 caracteres"
                                        required>
                                    <button class="btn btn-light border border-start-0" type="button"
                                        onclick="togglePassword('password', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div id="password-strength-container" class="mt-2" style="display: none;">
                                    <div class="progress" style="height: 5px;">
                                        <div id="strength-bar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                                    </div>
                                    <div id="password-strength" class="small mt-1 fw-semibold"></div>
                                </div>
                            </div>

                            <div class="mb-5">
                                <label for="password_confirmation"
                                    class="form-label fw-bold text-muted small mb-2 text-uppercase">
                                    <i class="bi bi-check2-circle me-1"></i> Confirmar Nueva Contraseña
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-shield-check text-primary"></i>
                                    </span>
                                    <input type="password" class="form-control border-start-0 bg-light"
                                        id="password_confirmation" name="password_confirmation"
                                        placeholder="Repite tu nueva contraseña" required>
                                    <button class="btn btn-light border border-start-0" type="button"
                                        onclick="togglePassword('password_confirmation', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="tbl-hero-btn tbl-hero-btn-primary py-3 fs-5">
                                    <i class="bi bi-save-fill me-2"></i> Guardar Cambios
                                </button>
                            </div>

                        </form>
                    </div>

                    <div class="p-3 bg-light border-top text-center">
                        <p class="text-muted small mb-0">
                            <i class="bi bi-info-circle me-1"></i>
                            Para una contraseña segura, usa una combinación de letras, números y símbolos.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    .input-group-text { border-radius: 8px 0 0 8px !important; }
    .form-control { border-radius: 0 !important; height: 45px; }
    .btn.border-start-0 { border-radius: 0 8px 8px 0 !important; }
    #password-strength-container { transition: all 0.3s ease; }
    .progress-bar { transition: width 0.5s ease, background-color 0.5s ease; }
</style>

{{-- ✅ 1. Primero carga SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ✅ 2. Luego los mensajes y funciones --}}
<script>
    // ✅ Mensajes de sesión y errores
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: @json(session('success')),
            confirmButtonColor: '#145da0'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: @json(session('error')),
            confirmButtonColor: '#145da0'
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Validación fallida',
            text: @json($errors->first()),
            confirmButtonColor: '#145da0'
        });
    @endif

    function togglePassword(fieldId, button) {
        let input = document.getElementById(fieldId);
        let icon = button.querySelector("i");
        if (input.type === "password") {
            input.type = "text";
            icon.classList.replace("bi-eye", "bi-eye-slash");
        } else {
            input.type = "password";
            icon.classList.replace("bi-eye-slash", "bi-eye");
        }
    }

    function checkPasswordStrength() {
        let password = document.getElementById("password").value;
        let strengthText = document.getElementById("password-strength");
        let strengthBar = document.getElementById("strength-bar");
        let container = document.getElementById("password-strength-container");

        if (password.length === 0) { container.style.display = "none"; return; }

        container.style.display = "block";
        let score = 0;

        if (password.length >= 6) score += 25;
        if (password.length >= 8) score += 25;
        if (password.match(/[a-z]/) && password.match(/[A-Z]/)) score += 25;
        if (password.match(/[0-9]/) && password.match(/[^a-zA-Z0-9]/)) score += 25;

        strengthBar.style.width = score + "%";

        if (score <= 25) {
            strengthText.textContent = "Débil 🔴";
            strengthText.className = "small mt-1 fw-semibold text-danger";
            strengthBar.className = "progress-bar bg-danger";
        } else if (score <= 75) {
            strengthText.textContent = "Moderada 🟡";
            strengthText.className = "small mt-1 fw-semibold text-warning";
            strengthBar.className = "progress-bar bg-warning";
        } else {
            strengthText.textContent = "Fuerte ✅";
            strengthText.className = "small mt-1 fw-semibold text-success";
            strengthBar.className = "progress-bar bg-success";
        }
    }

    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmation = document.getElementById('password_confirmation').value;

        if (password !== confirmation) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Contraseñas no coinciden',
                text: 'La nueva contraseña y su confirmación deben ser idénticas.',
                confirmButtonColor: '#145da0'
            });
            return false;
        }

        if (password.length < 8) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Contraseña muy corta',
                text: 'La nueva contraseña debe tener al menos 8 caracteres.',
                confirmButtonColor: '#145da0'
            });
            return false;
        }
    });
</script>
