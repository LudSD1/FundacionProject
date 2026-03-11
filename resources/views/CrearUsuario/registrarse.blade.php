@section('titulo')
    Regístrate
@endsection



@section('main')


<section id="auth-section" class="rg-wrapper">
    {{-- Fondo con imagen + overlay --}}
    <div class="rg-bg" style="background-image: url('{{ asset('assets/img/bg2.png') }}')"></div>
    <div class="rg-overlay"></div>

    <div class="container rg-container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-9 col-xl-7">

                @guest
                {{-- ══ CARD PRINCIPAL ══ --}}
                <div class="rg-card" data-aos="fade-up">

                    {{-- Header --}}
                    <div class="rg-card-header">
                        <div class="rg-logo">
                            APRENDO <span class="rg-logo-h">H</span>OY
                        </div>
                        <h2 class="rg-title">Crear cuenta nueva</h2>
                        <p class="rg-subtitle">Completa los pasos para registrarte</p>
                    </div>

                    {{-- ── Wizard steps ── --}}
                    <div class="rg-steps">
                        <div class="rg-step active" id="step-ind-1">
                            <div class="rg-step-num">1</div>
                            <div class="rg-step-info">
                                <span class="rg-step-label">Datos</span>
                                <span class="rg-step-sub">Personales</span>
                            </div>
                        </div>
                        <div class="rg-step-line"></div>
                        <div class="rg-step" id="step-ind-2">
                            <div class="rg-step-num">2</div>
                            <div class="rg-step-info">
                                <span class="rg-step-label">Acceso</span>
                                <span class="rg-step-sub">Credenciales</span>
                            </div>
                        </div>
                        <div class="rg-step-line"></div>
                        <div class="rg-step" id="step-ind-3">
                            <div class="rg-step-num">3</div>
                            <div class="rg-step-info">
                                <span class="rg-step-label">Revisar</span>
                                <span class="rg-step-sub">Confirmación</span>
                            </div>
                        </div>
                    </div>

                    {{-- Progreso --}}
                    <div class="rg-progress"><div class="rg-progress-bar" id="rgProgressBar" style="width:33%"></div></div>

                    {{-- Errores --}}
                    @if($errors->any())
                    <div class="rg-alert-error">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    {{-- ══ FORMULARIO ══ --}}
                    <form action="{{ route('registrarse') }}" method="POST" id="registerForm">
                        @csrf

                        {{-- ─── PASO 1: Datos personales ─── --}}
                        <div class="rg-panel active" id="rg-panel-1">
                            <div class="rg-panel-header">
                                <div class="rg-panel-icon"><i class="bi bi-person-fill"></i></div>
                                <div>
                                    <div class="rg-panel-title">Datos Personales</div>
                                    <div class="rg-panel-sub">Ingresa tu nombre completo y país</div>
                                </div>
                            </div>

                            <div class="rg-field">
                                <label class="rg-label"><i class="bi bi-person"></i> Nombre <span class="rg-req">*</span></label>
                                <div class="rg-input-wrap">
                                    <i class="bi bi-person rg-input-icon"></i>
                                    <input type="text" name="name" class="rg-input"
                                           value="{{ old('name') }}" placeholder="Tu nombre" required>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="rg-field mb-0">
                                        <label class="rg-label"><i class="bi bi-person-badge"></i> Apellido Paterno <span class="rg-req">*</span></label>
                                        <div class="rg-input-wrap">
                                            <i class="bi bi-person-badge rg-input-icon"></i>
                                            <input type="text" name="lastname1" class="rg-input"
                                                   value="{{ old('lastname1') }}" placeholder="Apellido paterno" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="rg-field mb-0">
                                        <label class="rg-label"><i class="bi bi-person-badge-fill"></i> Apellido Materno <span class="rg-req">*</span></label>
                                        <div class="rg-input-wrap">
                                            <i class="bi bi-person-badge-fill rg-input-icon"></i>
                                            <input type="text" name="lastname2" class="rg-input"
                                                   value="{{ old('lastname2') }}" placeholder="Apellido materno" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="rg-field">
                                <label class="rg-label"><i class="bi bi-globe"></i> País <span class="rg-req">*</span></label>
                                <div class="rg-input-wrap">
                                    <i class="bi bi-globe rg-input-icon"></i>
                                    <select name="country" id="country" class="rg-select" required>
                                        <option value="">Selecciona tu país</option>
                                    </select>
                                </div>
                            </div>

                            <div class="rg-nav-btns">
                                <span></span>
                                <button type="button" class="rg-btn rg-btn-next" onclick="rgGoStep(2)">
                                    Siguiente <i class="bi bi-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>

                        {{-- ─── PASO 2: Credenciales ─── --}}
                        <div class="rg-panel" id="rg-panel-2">
                            <div class="rg-panel-header">
                                <div class="rg-panel-icon"><i class="bi bi-shield-lock-fill"></i></div>
                                <div>
                                    <div class="rg-panel-title">Credenciales de Acceso</div>
                                    <div class="rg-panel-sub">Configura tu correo y contraseña</div>
                                </div>
                            </div>

                            <div class="rg-field">
                                <label class="rg-label"><i class="bi bi-envelope"></i> Correo electrónico <span class="rg-req">*</span></label>
                                <div class="rg-input-wrap">
                                    <i class="bi bi-envelope rg-input-icon"></i>
                                    <input type="email" name="email" class="rg-input"
                                           value="{{ old('email') }}" placeholder="tu@correo.com" required>
                                </div>
                            </div>

                            <div class="rg-field">
                                <label class="rg-label"><i class="bi bi-lock"></i> Contraseña <span class="rg-req">*</span></label>
                                <div class="rg-input-wrap">
                                    <i class="bi bi-lock rg-input-icon"></i>
                                    <input type="password" name="password" id="password"
                                           class="rg-input rg-input-pwd" placeholder="Mínimo 8 caracteres" required>
                                    <button type="button" class="rg-eye-btn" onclick="rgTogglePwd('password', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                {{-- Fortaleza de contraseña --}}
                                <div class="rg-pwd-strength mt-2">
                                    <div class="rg-pwd-bar" id="pwdBar"></div>
                                </div>
                                <div class="rg-pwd-label" id="pwdLabel"></div>
                            </div>

                            <div class="rg-field">
                                <label class="rg-label"><i class="bi bi-lock-fill"></i> Confirmar Contraseña <span class="rg-req">*</span></label>
                                <div class="rg-input-wrap">
                                    <i class="bi bi-lock-fill rg-input-icon"></i>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="rg-input rg-input-pwd" placeholder="Repite tu contraseña" required>
                                    <button type="button" class="rg-eye-btn" onclick="rgTogglePwd('password_confirmation', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div class="rg-match-msg" id="pwdMatch"></div>
                            </div>

                            <div class="rg-nav-btns">
                                <button type="button" class="rg-btn rg-btn-prev" onclick="rgGoStep(1)">
                                    <i class="bi bi-arrow-left me-1"></i> Anterior
                                </button>
                                <button type="button" class="rg-btn rg-btn-next" onclick="rgGoStep(3)">
                                    Siguiente <i class="bi bi-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>

                        {{-- ─── PASO 3: Confirmación ─── --}}
                        <div class="rg-panel" id="rg-panel-3">
                            <div class="rg-panel-header">
                                <div class="rg-panel-icon"><i class="bi bi-check2-circle"></i></div>
                                <div>
                                    <div class="rg-panel-title">Confirmar Registro</div>
                                    <div class="rg-panel-sub">Revisa tus datos antes de continuar</div>
                                </div>
                            </div>

                            {{-- Resumen --}}
                            <div class="rg-review" id="rgReview">
                                {{-- Se llena con JS --}}
                            </div>

                            {{-- reCAPTCHA --}}
                            <div class="rg-recaptcha">
                                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                            </div>

                            <div class="rg-nav-btns">
                                <button type="button" class="rg-btn rg-btn-prev" onclick="rgGoStep(2)">
                                    <i class="bi bi-arrow-left me-1"></i> Anterior
                                </button>
                                <button type="submit" class="rg-btn rg-btn-submit">
                                    <i class="bi bi-person-plus-fill me-1"></i> Crear Cuenta
                                </button>
                            </div>
                        </div>

                    </form>

                    {{-- Link a login --}}
                    <div class="rg-footer-link">
                        ¿Ya tienes cuenta?
                        <a href="{{ route('login.signin') }}">Inicia sesión aquí</a>
                    </div>

                </div>{{-- fin rg-card --}}

                @else
                {{-- ══ YA AUTENTICADO ══ --}}
                <div class="rg-card rg-card-auth" data-aos="fade-up">
                    <div class="text-center py-3">
                        <div class="rg-auth-icon"><i class="bi bi-check-circle-fill"></i></div>
                        <h4 class="rg-auth-title">¡Ya has iniciado sesión!</h4>
                        <p class="rg-auth-sub">Sesión activa como <strong>{{ Auth::user()->name }}</strong></p>
                        <a href="{{ route('home') }}" class="rg-btn rg-btn-next mt-2">
                            <i class="bi bi-house-door me-1"></i> Ir al inicio
                        </a>
                    </div>
                </div>
                @endguest

            </div>
        </div>
    </div>
</section>
@endsection

@include('layoutlanding')

<!-- Core JS Files -->
@if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "{{ $errors->first('email') }}",
            timer: 5000,
            showConfirmButton: false,
        });
    </script>
@endif


<script>
    // Array of countries
    const countries = [
        "Canada", "Estados Unidos", "México", "Belice", "Costa Rica", "Cuba", "El Salvador", "Guatemala",
        "Honduras", "Nicaragua", "Panamá",
        "República Dominicana",

        "Argentina", "Bolivia", "Brasil", "Chile", "Colombia", "Ecuador", "Guyana", "Paraguay", "Perú", "Surinam",
        "Uruguay", "Venezuela",

        "Alemania", "Francia", "España", "Italia", "Reino Unido", "Portugal", "Países Bajos", "Bélgica", "Suiza",
        "Austria", "Grecia", "Suecia", "Noruega",

        "China", "India", "Japón", "Corea del Sur", "Indonesia", "Filipinas", "Malasia", "Singapur", "Tailandia",
        "Vietnam", "Israel", "Turquía", "Arabia Saudita",

        "Australia", "Nueva Zelanda"
    ];

    // Function to populate select dropdown
    function populateCountries(selectElement) {
        // Clear existing options except the first (default)
        while (selectElement.options.length > 1) {
            selectElement.remove(1);
        }

        // Sort countries alphabetically
        countries.sort();

        // Add countries to dropdown
        countries.forEach(country => {
            const option = new Option(country, country);
            selectElement.add(option);
        });
    }

    // Event listener to populate dropdown when page loads
    document.addEventListener('DOMContentLoaded', () => {
        const countrySelect = document.getElementById('country');
        if (countrySelect) {
            populateCountries(countrySelect);
        }

        // Sistema de navegación por pasos
        const steps = document.querySelectorAll('.registration-step');
        const progressSteps = document.querySelectorAll('.progress-steps .step');

        // Función para mostrar un paso específico
        function showStep(stepNumber) {
            steps.forEach((step, index) => {
                if (index + 1 === stepNumber) {
                    step.classList.add('active');
                    step.style.display = 'block';
                } else {
                    step.classList.remove('active');
                    step.style.display = 'none';
                }
            });

            // Actualizar indicador de progreso
            progressSteps.forEach((step, index) => {
                if (index + 1 <= stepNumber) {
                    step.classList.add('active');
                } else {
                    step.classList.remove('active');
                }
            });

            // Actualizar datos de revisión en el paso 3
            if (stepNumber === 3) {
                updateReviewData();
            }
        }

        // Función para actualizar datos de revisión
        function updateReviewData() {
            const name = document.getElementById('name').value || 'No especificado';
            const lastname1 = document.getElementById('lastname1').value || 'No especificado';
            const lastname2 = document.getElementById('lastname2').value || 'No especificado';
            const country = document.getElementById('country').value || 'No especificado';
            const email = document.getElementById('email').value || 'No especificado';

            document.getElementById('review-data').innerHTML = `
                <div class="row">
                    <div class="col-6 mb-2"><strong>Nombre:</strong></div>
                    <div class="col-6 mb-2">${name}</div>
                    <div class="col-6 mb-2"><strong>Apellido Paterno:</strong></div>
                    <div class="col-6 mb-2">${lastname1}</div>
                    <div class="col-6 mb-2"><strong>Apellido Materno:</strong></div>
                    <div class="col-6 mb-2">${lastname2}</div>
                    <div class="col-6 mb-2"><strong>País:</strong></div>
                    <div class="col-6 mb-2">${country}</div>
                    <div class="col-6 mb-2"><strong>Email:</strong></div>
                    <div class="col-6 mb-2">${email}</div>
                </div>
            `;
        }

        // Validar campos antes de avanzar
        function validateStep(stepNumber) {
            if (stepNumber === 1) {
                const name = document.getElementById('name').value.trim();
                const lastname1 = document.getElementById('lastname1').value.trim();
                const lastname2 = document.getElementById('lastname2').value.trim();
                const country = document.getElementById('country').value;

                if (!name || !lastname1 || !lastname2 || !country) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campos incompletos',
                        text: 'Por favor completa todos los campos de datos personales',
                        confirmButtonColor: '#145DA0'
                    });
                    return false;
                }
                return true;
            } else if (stepNumber === 2) {
                const email = document.getElementById('email').value.trim();
                const password = document.getElementById('password').value;
                const passwordConfirmation = document.getElementById('password_confirmation').value;

                if (!email || !password || !passwordConfirmation) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campos incompletos',
                        text: 'Por favor completa todos los campos de credenciales',
                        confirmButtonColor: '#145DA0'
                    });
                    return false;
                }

                if (password !== passwordConfirmation) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Contraseñas no coinciden',
                        text: 'Las contraseñas deben ser iguales',
                        confirmButtonColor: '#dc3545'
                    });
                    return false;
                }

                if (password.length < 8) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Contraseña muy corta',
                        text: 'La contraseña debe tener al menos 8 caracteres',
                        confirmButtonColor: '#145DA0'
                    });
                    return false;
                }
                return true;
            }
            return true;
        }

        // Botones siguiente
        document.querySelectorAll('.btn-next').forEach(btn => {
            btn.addEventListener('click', function() {
                const currentStep = parseInt(this.closest('.registration-step').dataset.step);
                const nextStep = parseInt(this.dataset.next);

                if (validateStep(currentStep)) {
                    showStep(nextStep);
                }
            });
        });

        // Botones anterior
        document.querySelectorAll('.btn-prev').forEach(btn => {
            btn.addEventListener('click', function() {
                const prevStep = parseInt(this.dataset.prev);
                showStep(prevStep);
            });
        });

        // Inicializar: mostrar solo el primer paso
        showStep(1);
    });
</script>




<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Script para cambiar la visibilidad de la contraseña
    document.addEventListener('DOMContentLoaded', function() {
        // Obtener elementos
        const togglePassword = document.getElementById('togglePassword');
        const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');
        const passwordField = document.getElementById('password');
        const passwordConfirmationField = document.getElementById('password_confirmation');

        // Añadir evento de clic al botón de contraseña
        if (togglePassword) {
            togglePassword.addEventListener('click', function() {
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                const icon = type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
                this.innerHTML = `<i class="${icon}"></i>`;
            });
        }

        // Añadir evento de clic al botón de confirmación de contraseña
        if (togglePasswordConfirmation) {
            togglePasswordConfirmation.addEventListener('click', function() {
                const type = passwordConfirmationField.getAttribute('type') === 'password' ? 'text' :
                    'password';
                passwordConfirmationField.setAttribute('type', type);
                const icon = type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
                this.innerHTML = `<i class="${icon}"></i>`;
            });
        }

        // SweetAlert Notifications
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#075092'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                confirmButtonColor: '#dc3545'
            });
        @endif


    });
</script>


<script>
    // ── Wizard ──────────────────────────────────────────
    let rgCur = 1;
    const RG_TOTAL = 3;
    const rgProgress = [33, 66, 100];

    function rgGoStep(n) {
        // Validar antes de avanzar
        if (n > rgCur && !rgValidateStep(rgCur)) return;

        // Saliente
        document.getElementById('rg-panel-' + rgCur)?.classList.remove('active');
        const indPrev = document.getElementById('step-ind-' + rgCur);
        indPrev?.classList.remove('active');
        if (n > rgCur) {
            indPrev?.classList.add('done');
            indPrev?.querySelector('.rg-step-num') &&
                (indPrev.querySelector('.rg-step-num').innerHTML = '<i class="bi bi-check-lg"></i>');
            // Línea
            const lines = document.querySelectorAll('.rg-step-line');
            if (lines[rgCur - 1]) lines[rgCur - 1].classList.add('done');
        } else {
            // Retrocede: quitar done del actual
            indPrev?.classList.remove('done');
            const num = indPrev?.querySelector('.rg-step-num');
            if (num) num.textContent = rgCur;
            const lines = document.querySelectorAll('.rg-step-line');
            if (lines[n - 1]) lines[n - 1].classList.remove('done');
        }

        rgCur = n;

        // Entrante
        document.getElementById('rg-panel-' + rgCur)?.classList.add('active');
        const indNext = document.getElementById('step-ind-' + rgCur);
        indNext?.classList.add('active');
        indNext?.classList.remove('done');
        const num = indNext?.querySelector('.rg-step-num');
        if (num && !num.querySelector('i')) num.textContent = rgCur;

        // Progress bar
        document.getElementById('rgProgressBar').style.width = rgProgress[rgCur - 1] + '%';

        // Si llega al paso 3, llenar resumen
        if (rgCur === 3) rgBuildReview();

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // ── Validación por paso ──────────────────────────────
    function rgValidateStep(step) {
        if (step === 1) {
            const name = document.querySelector('[name="name"]')?.value.trim();
            const l1   = document.querySelector('[name="lastname1"]')?.value.trim();
            const l2   = document.querySelector('[name="lastname2"]')?.value.trim();
            const ctr  = document.getElementById('country')?.value;
            if (!name || !l1 || !l2 || !ctr) {
                rgShowToast('Completa todos los campos del paso 1.');
                return false;
            }
        }
        if (step === 2) {
            const email = document.querySelector('[name="email"]')?.value.trim();
            const pwd   = document.getElementById('password')?.value;
            const conf  = document.getElementById('password_confirmation')?.value;
            if (!email || !pwd || !conf) {
                rgShowToast('Completa todos los campos del paso 2.');
                return false;
            }
            if (pwd !== conf) {
                rgShowToast('Las contraseñas no coinciden.');
                return false;
            }
            if (pwd.length < 8) {
                rgShowToast('La contraseña debe tener al menos 8 caracteres.');
                return false;
            }
        }
        return true;
    }

    // ── Toast de error ──────────────────────────────────
    function rgShowToast(msg) {
        let t = document.getElementById('rgToast');
        if (!t) {
            t = document.createElement('div');
            t.id = 'rgToast';
            t.style.cssText = 'position:fixed;bottom:1.5rem;left:50%;transform:translateX(-50%);' +
                'background:#dc2626;color:#fff;padding:.65rem 1.4rem;border-radius:50px;' +
                'font-size:.85rem;font-weight:600;z-index:9999;box-shadow:0 6px 20px rgba(0,0,0,.2);' +
                'transition:opacity .3s ease';
            document.body.appendChild(t);
        }
        t.textContent = msg;
        t.style.opacity = '1';
        clearTimeout(t._timer);
        t._timer = setTimeout(() => t.style.opacity = '0', 3000);
    }

    // ── Resumen paso 3 ──────────────────────────────────
    function rgBuildReview() {
        const data = [
            { icon: 'bi-person',        key: 'Nombre',    val: document.querySelector('[name="name"]')?.value },
            { icon: 'bi-person-badge',  key: 'Ap. Paterno', val: document.querySelector('[name="lastname1"]')?.value },
            { icon: 'bi-person-badge-fill', key: 'Ap. Materno', val: document.querySelector('[name="lastname2"]')?.value },
            { icon: 'bi-globe',         key: 'País',      val: document.getElementById('country')?.options[document.getElementById('country')?.selectedIndex]?.text },
            { icon: 'bi-envelope',      key: 'Correo',    val: document.querySelector('[name="email"]')?.value },
            { icon: 'bi-lock',          key: 'Contraseña', val: '••••••••' },
        ];
        const box = document.getElementById('rgReview');
        if (!box) return;
        box.innerHTML = data.map(d => `
            <div class="rg-review-row">
                <div class="rg-review-icon"><i class="bi ${d.icon}"></i></div>
                <span class="rg-review-key">${d.key}</span>
                <span class="rg-review-val">${d.val || '—'}</span>
            </div>`).join('');
    }

    // ── Ver/ocultar contraseña ───────────────────────────
    function rgTogglePwd(id, btn) {
        const input = document.getElementById(id);
        if (!input) return;
        const isText = input.type === 'text';
        input.type = isText ? 'password' : 'text';
        btn.querySelector('i').className = isText ? 'bi bi-eye' : 'bi bi-eye-slash';
    }

    // ── Fortaleza de contraseña ──────────────────────────
    document.getElementById('password')?.addEventListener('input', function () {
        const v = this.value;
        let score = 0;
        if (v.length >= 8)              score++;
        if (/[A-Z]/.test(v))            score++;
        if (/[0-9]/.test(v))            score++;
        if (/[^A-Za-z0-9]/.test(v))     score++;

        const bar   = document.getElementById('pwdBar');
        const label = document.getElementById('pwdLabel');
        const map   = [
            { pct: '0%',   bg: '',          txt: '' },
            { pct: '25%',  bg: '#dc2626',   txt: '🔴 Muy débil',  col: '#dc2626' },
            { pct: '50%',  bg: '#f97316',   txt: '🟠 Débil',      col: '#f97316' },
            { pct: '75%',  bg: '#eab308',   txt: '🟡 Regular',    col: '#eab308' },
            { pct: '100%', bg: '#16a34a',   txt: '🟢 Fuerte',     col: '#16a34a' },
        ];
        if (bar && map[score]) {
            bar.style.width      = map[score].pct;
            bar.style.background = map[score].bg;
        }
        if (label && map[score]) {
            label.textContent  = map[score].txt;
            label.style.color  = map[score].col || '';
        }
    });

    // ── Confirmar coincidencia contraseñas ───────────────
    document.getElementById('password_confirmation')?.addEventListener('input', function () {
        const pwd  = document.getElementById('password')?.value;
        const msg  = document.getElementById('pwdMatch');
        if (!msg) return;
        if (!this.value) { msg.textContent = ''; return; }
        if (this.value === pwd) {
            msg.textContent = '✅ Las contraseñas coinciden';
            msg.style.color = '#16a34a';
        } else {
            msg.textContent = '❌ Las contraseñas no coinciden';
            msg.style.color = '#dc2626';
        }
    });

    // ── Lista de países ──────────────────────────────────
    (async function loadCountries() {
        const sel = document.getElementById('country');
        if (!sel) return;
        try {
            const res  = await fetch('https://restcountries.com/v3.1/all?fields=name,cca2');
            const list = await res.json();
            list.sort((a, b) => a.name.common.localeCompare(b.name.common));

            // Bolivia primero
            const bo = list.find(c => c.cca2 === 'BO');
            if (bo) {
                const opt = new Option(bo.name.common, bo.name.common);
                opt.selected = true;
                sel.add(opt);
                const sep = document.createElement('option');
                sep.disabled = true;
                sep.textContent = '──────────';
                sel.add(sep);
            }
            list.forEach(c => {
                if (c.cca2 !== 'BO') sel.add(new Option(c.name.common, c.name.common));
            });
        } catch {
            // Fallback: opciones básicas
            ['Bolivia','Argentina','Brasil','Chile','Colombia','Ecuador',
             'México','Paraguay','Perú','Uruguay','Venezuela'].forEach(p => {
                sel.add(new Option(p, p));
            });
        }
    })();
    </script>