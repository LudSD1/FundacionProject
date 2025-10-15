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
    <link href="{{ asset('./assets/js/plugins/nucleo/css/nucleo.css') }}" rel="stylesheet" />
    <link href="{{ asset('./assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/driver.js@latest/dist/driver.js.iife.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@latest/dist/driver.css" />



</head>

<body>
    <!-- Include Sidebar Component -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="content">
        <!-- Include Header Component -->
        @include('components.header')

        <!-- Main Content Area -->
        <div class="container-fluid mt-4">
            @yield('content')
            @yield('contentini')
        </div>

        <!-- Footer -->
        <footer class="footer mt-5 py-4">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start text-muted">
                        &copy; <span id="currentYear"></span>
                        <a href="#" class="text-decoration-none">Fundación Educar para la Vida</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Include Achievements Component -->
    @role('Estudiante')
    @include('components.achievements')
    @endrole
    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('./assets/js/plugins/jquery/dist/jquery.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set current year
            document.getElementById('currentYear').textContent = new Date().getFullYear();

            // Sidebar toggle
            document.getElementById('toggleSidebar').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('collapsed');
            });

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

</body>

</html>
