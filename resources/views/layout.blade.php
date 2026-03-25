     <!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('titulo')</title>
    <link href="{{ asset('./assets/img/Acceder.png') }}" rel="icon" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{asset('assets/css/dashboard.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/driver.js@latest/dist/driver.js.iife.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@latest/dist/driver.css" />



</head>

<body>
        <!-- Layout Structure -->
        <div class="layout-wrapper">
            <!-- Sidebar -->
            @include('components.sidebar')

            <!-- Main Content Wrapper -->
            <div class="main-content-wrapper">
                <!-- Header -->

                <!-- Main Content -->
                <main class="main-content">

                    @include('components.header')
                    <div class="container-fluid py-4">
                        @yield('contentup')
                        @yield('content')
                        @yield('contentini')
                    </div>
                </main>

                <!-- Footer -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&display=swap');

    .ft-footer {
        background: linear-gradient(135deg, #0d3d6e 0%, #145da0 60%, #1a6db5 100%);
        font-family: 'DM Sans', sans-serif;
        position: relative;
        overflow: hidden;
    }

    /* Patrón de puntos sutil */
    .ft-footer::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255,255,255,.06) 1px, transparent 1px);
        background-size: 24px 24px;
        pointer-events: none;
    }

    /* Brillo superior derecho */
    .ft-footer::after {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 260px; height: 260px;
        background: radial-gradient(circle, rgba(99,190,207,.18) 0%, transparent 65%);
        pointer-events: none;
    }

    .ft-inner {
        position: relative;
        z-index: 1;
        padding: 2.75rem 0 1.5rem;
    }

    /* ── Marca ───────────────────────────────────────────── */
    .ft-brand-name {
        font-family: 'Syne', sans-serif;
        font-size: 1.05rem;
        font-weight: 800;
        color: #fff;
        letter-spacing: .02em;
        margin: 0 0 .4rem;
    }

    .ft-brand-tagline {
        font-size: .78rem;
        color: rgba(255,255,255,.55);
        margin: 0;
        line-height: 1.5;
        max-width: 240px;
    }

    /* ── Links ───────────────────────────────────────────── */
    .ft-col-title {
        font-family: 'Syne', sans-serif;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: rgba(255,255,255,.40);
        margin: 0 0 .85rem;
    }

    .ft-links {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: .45rem;
    }

    .ft-links a {
        font-size: .83rem;
        color: rgba(255,255,255,.70);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        transition: color .2s, gap .2s;
    }
    .ft-links a:hover { color: #fff; gap: .55rem; }
    .ft-links a i { font-size: .7rem; opacity: .6; }

    /* ── Separador ───────────────────────────────────────── */
    .ft-divider {
        border: none;
        border-top: 1px solid rgba(255,255,255,.10);
        margin: 2rem 0 1.25rem;
    }

    /* ── Bottom bar ──────────────────────────────────────── */
    .ft-bottom {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: .75rem;
    }

    .ft-copy {
        font-size: .78rem;
        color: rgba(255,255,255,.45);
        margin: 0;
    }
    .ft-copy a {
        color: rgba(255,255,255,.70);
        text-decoration: none;
        font-weight: 500;
        transition: color .2s;
    }
    .ft-copy a:hover { color: #fff; }

    /* Redes sociales */
    .ft-socials {
        display: flex;
        gap: .4rem;
    }
    .ft-social-btn {
        width: 32px; height: 32px;
        border: 1px solid rgba(255,255,255,.15);
        border-radius: 8px;
        background: rgba(255,255,255,.07);
        color: rgba(255,255,255,.60);
        font-size: .82rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: background .2s, color .2s, border-color .2s, transform .15s;
    }
    .ft-social-btn:hover {
        background: rgba(255,255,255,.18);
        color: #fff;
        border-color: rgba(255,255,255,.35);
        transform: translateY(-2px);
    }

    /* ── Responsive ──────────────────────────────────────── */
    @media (max-width: 767px) {
        .ft-col { margin-bottom: 1.75rem; }
        .ft-brand-tagline { max-width: 100%; }
        .ft-bottom { justify-content: center; text-align: center; }
    }
</style>

<footer class="ft-footer mt-auto">
    <div class="container ft-inner">

         <div class="ft-bottom">
            <p class="ft-copy">
                &copy; <span id="ftYear"></span>
                <a href="#">Fundación Educar para la Vida</a>.
                Todos los derechos reservados.
            </p>
            <div class="ft-socials">
                <a href="#" class="ft-social-btn" title="Facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="ft-social-btn" title="Instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" class="ft-social-btn" title="YouTube"><i class="bi bi-youtube"></i></a>
                <a href="#" class="ft-social-btn" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
            </div>
        </div>

    </div>
</footer>

<script>
    document.getElementById('ftYear').textContent = new Date().getFullYear();
</script>
            </div>
        </div>

    <!-- Include Achievements Component -->
    @role('Estudiante')
    @include('components.achievements')
    @endrole
    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set current year
            document.getElementById('currentYear').textContent = new Date().getFullYear();

            // El toggle del sidebar se maneja en el componente sidebar.blade.php

            // Show floating XP button after delay
            setTimeout(() => {
                const button = document.querySelector('.floating-xp-button');
                if (button) button.classList.add('show');
            }, 1000);

            // Hide/show floating button on scroll
            let lastScrollTop = 0;
            let isScrolling;

            window.addEventListener('scroll', function() {
                clearTimeout(isScrolling);
                isScrolling = setTimeout(function() {
                    let currentScroll = window.pageYOffset || document.documentElement.scrollTop;
                    let button = document.querySelector('.floating-xp-button');

                    if (button) {
                        if (currentScroll > lastScrollTop) {
                            button.classList.remove('show');
                        } else {
                            button.classList.add('show');
                        }
                    }
                    lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
                }, 66);
            });

            // Tab and accordion state persistence
            let activeTab = localStorage.getItem("activeTab");
            if (activeTab) {
                let tab = document.querySelector(`[data-bs-target="${activeTab}"]`);
                if (tab) new bootstrap.Tab(tab).show();
            }

            document.querySelectorAll(".nav-link[data-bs-target]").forEach(tab => {
                tab.addEventListener("click", function(event) {
                    let tabTarget = event.target.getAttribute("data-bs-target");
                    localStorage.setItem("activeTab", tabTarget);
                });
            });

            // Achievement animations
            const xpOffcanvas = document.getElementById('xpOffcanvas');
            if (xpOffcanvas) {
                xpOffcanvas.addEventListener('show.bs.offcanvas', function() {
                    const items = document.querySelectorAll('.achievement-item');
                    items.forEach((item, index) => {
                        item.style.animationDelay = `${0.1 * (index + 1)}s`;
                    });
                });
            }


        });
    </script>

    @if (session('success'))
        <script>
        Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: "{{ session('success') }}",
        confirmButtonText: 'Entendido'
        });
        </script>
    @endif

    @if (session('error'))
        <script>
        Swal.fire({
        icon: 'error',
        title: '¡Error!',
        text: "{{ session('error') }}",
        confirmButtonText: 'Reintentar'
        });
        </script>
    @endif

    @if (session('info'))
        <script>
            Swal.fire({
                icon: 'info',
                title: 'Información',
                text: "{{ session('info') }}",
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                toast: true,
                position: 'top-end'
            });
        </script>
    @endif

    @stack('modals')

    <div class="modal fade" id="ntfDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:540px">
            <div class="modal-content cc-modal">
                <div class="cc-modal-header" id="ntfModalHeader">
                    <div class="cc-modal-icon" id="ntfModalIcon">
                        <i class="bi bi-bell-fill"></i>
                    </div>
                    <div>
                        <h5 class="cc-modal-title">Detalle de Notificación</h5>
                        <small id="ntfModalTime" class="opacity-75"></small>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" id="ntfModalBody">
                </div>
                <div class="cc-modal-footer">
                    <button type="button" class="cc-btn cc-btn-outline" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>


</html>
