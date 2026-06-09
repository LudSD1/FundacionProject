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

    @stack('css')

</head>

<body>
        <div class="layout-wrapper">
            @include('components.sidebar')
            <div class="main-content-wrapper">
                <main class="main-content">
                    @include('components.header')
                    <div class="container-fluid py-4">
                        @yield('contentup')
                        @yield('content')
                        @yield('contentini')
                    </div>
                </main>

<footer class="ft-footer mt-auto">
    <div class="container ft-inner">

         <div class="ft-bottom">
            <p class="ft-copy">
                &copy; <span id="ftYear"></span>
                <a href="#">Fundación Educar para la Vida</a>.
                Todos los derechos reservados.
            </p>
            <div class="ft-socials">
                <a href="https://www.facebook.com/profile.php?id=100063510101095" class="ft-social-btn" title="Facebook"><i class="bi bi-facebook"></i></a>
                <a href="https://www.instagram.com/fundeducarparalavida/" class="ft-social-btn" title="Instagram"><i class="bi bi-instagram"></i></a>
                <a href="https://x.com/FUNDVIDA2" class="ft-social-btn" title="YouTube"><i class="bi bi-twitter-x"></i></a>
                <a href="https://wa.me/59172087186" class="ft-social-btn" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
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
    @stack('scripts')
</body>


</html>
