{{--
    Archivo: resources/views/cursos/partials/scripts.blade.php
    Descripción: Scripts para countdown timer, toggle password y carga de países
--}}

{{-- ================================================
     SCRIPT 1: COUNTDOWN TIMER
================================================ --}}
<script>
    // Fecha de finalización del curso/congreso
    const endDate = new Date("{{ $cursos->fecha_fin }}".replace(' ', 'T')).getTime();

    const countdown = setInterval(function() {
        const now = new Date().getTime();
        const distance = endDate - now;

        // Cálculos de tiempo
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Elemento del timer
        const timerElement = document.getElementById("countdown-timer");

        if (timerElement) {
            if (distance > 0) {
                // Mostrar tiempo restante con formato y icono
                timerElement.innerHTML = `
                    <i class="bi bi-hourglass-split me-2"></i>
                    ${days}d ${hours}h ${minutes}m ${seconds}s
                `;
                timerElement.className = "badge bg-primary-subtle text-primary px-3 py-2";
            } else {
                // Tiempo agotado
                clearInterval(countdown);
                timerElement.innerHTML = '<i class="bi bi-x-circle me-2"></i>¡Tiempo agotado!';
                timerElement.className = "badge bg-danger-subtle text-danger px-3 py-2";

                // Deshabilitar todos los botones relacionados
                const buttonsToDisable = [
                    'button[data-bs-target="#opcionesRegistroModal"]',
                    'button[data-bs-target="#registroCongresoModal"]',
                    'button[data-bs-target="#loginModal"]',
                    'form[action*="certificados.obtener"] button[type="submit"]'
                ];

                buttonsToDisable.forEach(selector => {
                    document.querySelectorAll(selector).forEach(button => {
                        button.disabled = true;
                        button.classList.remove('btn-primary', 'btn-success');
                        button.classList.add('btn-secondary');
                        button.innerHTML = '<i class="bi bi-lock-fill me-2"></i>Tiempo agotado';
                    });
                });

                // Mostrar alerta al usuario
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-warning alert-dismissible fade show mt-3';
                alertDiv.innerHTML = `
                    <strong><i class="bi bi-exclamation-triangle-fill me-2"></i>Atención:</strong>
                    El tiempo para obtener el certificado ha finalizado.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;

                if (timerElement.parentElement) {
                    timerElement.parentElement.appendChild(alertDiv);
                }
            }
        }
    }, 1000);
</script>

{{-- ================================================
     SCRIPT 2: TOGGLE PASSWORD Y CARGA DE PAÍSES
================================================ --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ===== TOGGLE PASSWORD VISIBILITY =====
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (input && icon) {
                    if (input.type === 'password') {
                        // Mostrar contraseña
                        input.type = 'text';
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                        this.setAttribute('aria-label', 'Ocultar contraseña');
                    } else {
                        // Ocultar contraseña
                        input.type = 'password';
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                        this.setAttribute('aria-label', 'Mostrar contraseña');
                    }
                }
            });
        });

        // ===== CARGAR PAÍSES EN SELECT =====
        const countries = [
            // América del Norte
            "Canadá", "Estados Unidos", "México",

            // América Central y el Caribe
            "Belice", "Costa Rica", "Cuba", "El Salvador", "Guatemala", "Honduras",
            "Nicaragua", "Panamá", "República Dominicana", "Jamaica", "Haití",
            "Trinidad y Tobago", "Bahamas", "Barbados",

            // América del Sur
            "Argentina", "Bolivia", "Brasil", "Chile", "Colombia", "Ecuador",
            "Guyana", "Paraguay", "Perú", "Surinam", "Uruguay", "Venezuela",

            // Europa
            "Alemania", "Austria", "Bélgica", "Bulgaria", "Croacia", "Dinamarca",
            "España", "Francia", "Grecia", "Hungría", "Irlanda", "Italia",
            "Noruega", "Países Bajos", "Polonia", "Portugal", "Reino Unido",
            "República Checa", "Rumania", "Suecia", "Suiza",

            // Asia
            "Arabia Saudita", "China", "Corea del Norte", "Corea del Sur",
            "Filipinas", "India", "Indonesia", "Irán", "Iraq", "Israel",
            "Japón", "Malasia", "Pakistán", "Singapur", "Tailandia",
            "Turquía", "Vietnam", "Emiratos Árabes Unidos",

            // Oceanía
            "Australia", "Nueva Zelanda", "Fiji",

            // África
            "Egipto", "Marruecos", "Sudáfrica", "Nigeria", "Kenia", "Ghana",
            "Argelia", "Túnez"
        ];

        const countrySelect = document.getElementById('country');
        if (countrySelect) {
            // Ordenar países alfabéticamente
            countries.sort((a, b) => a.localeCompare(b, 'es'));

            // Agregar países al select
            countries.forEach(country => {
                const option = document.createElement('option');
                option.value = country;
                option.textContent = country;
                countrySelect.appendChild(option);
            });

            // Si hay un país pre-seleccionado (old value)
            const oldCountry = "{{ old('country') }}";
            if (oldCountry && countrySelect) {
                countrySelect.value = oldCountry;
            }
        }

        // ===== VALIDACIÓN DE CONTRASEÑAS EN TIEMPO REAL =====
        const password = document.getElementById('password');
        const passwordConfirmation = document.getElementById('password_confirmation');

        if (password && passwordConfirmation) {
            passwordConfirmation.addEventListener('input', function() {
                if (password.value !== this.value) {
                    this.setCustomValidity('Las contraseñas no coinciden');
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                } else {
                    this.setCustomValidity('');
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });

            password.addEventListener('input', function() {
                if (passwordConfirmation.value && password.value !== passwordConfirmation.value) {
                    passwordConfirmation.setCustomValidity('Las contraseñas no coinciden');
                    passwordConfirmation.classList.add('is-invalid');
                    passwordConfirmation.classList.remove('is-valid');
                } else if (passwordConfirmation.value) {
                    passwordConfirmation.setCustomValidity('');
                    passwordConfirmation.classList.remove('is-invalid');
                    passwordConfirmation.classList.add('is-valid');
                }
            });
        }
    });
</script>

{{-- ================================================
     ESTILOS ADICIONALES PARA LOS SCRIPTS
================================================ --}}
<style>
    /* Animación de fade in para los modales */
    .modal.fade .modal-dialog {
        transform: scale(0.95);
        transition: transform 0.3s ease-out;
    }

    .modal.show .modal-dialog {
        transform: scale(1);
    }

    /* Efecto de focus en inputs */
    .form-control:focus,
    .form-select:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 0.2rem rgba(26, 71, 137, 0.15);
        transform: translateY(-2px);
    }

    /* Estados de validación */
    .form-control.is-valid {
        border-color: #28a745;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(.375em + .1875rem) center;
        background-size: calc(.75em + .375rem) calc(.75em + .375rem);
    }

    .form-control.is-invalid {
        border-color: #dc3545;
    }

    /* Mejora en los botones de toggle password */
    .toggle-password {
        cursor: pointer;
        transition: all 0.3s ease;
        border-left: none;
    }

    .toggle-password:hover {
        background-color: var(--color-accent1);
        color: white;
        border-color: var(--color-primary);
    }

    .toggle-password:focus {
        box-shadow: none;
        background-color: var(--color-accent1);
        color: white;
    }

    /* Animación de error en formularios */
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }

    .alert-danger {
        animation: shake 0.5s;
        border-left: 5px solid #dc3545;
        border-radius: 10px;
    }

    /* Loading spinner para cuando se envía el formulario */
    .btn.loading {
        position: relative;
        pointer-events: none;
    }

    .btn.loading::after {
        content: "";
        position: absolute;
        width: 16px;
        height: 16px;
        top: 50%;
        left: 50%;
        margin-left: -8px;
        margin-top: -8px;
        border: 2px solid transparent;
        border-radius: 50%;
        border-top-color: white;
        animation: spin 0.6s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Mejora visual del countdown timer */
    #countdown-timer {
        font-family: 'Courier New', monospace;
        letter-spacing: 1px;
        font-weight: 600;
    }
</style>

{{-- ================================================
     SCRIPT OPCIONAL: LOADING EN BOTONES DE SUBMIT
================================================ --}}
<script>
    // Agregar loading spinner a botones de submit
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.disabled) {
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;

                // Si el formulario no se envía (por validación), remover loading
                setTimeout(() => {
                    if (!form.checkValidity()) {
                        submitBtn.classList.remove('loading');
                        submitBtn.disabled = false;
                    }
                }, 100);
            }
        });
    });
</script>
