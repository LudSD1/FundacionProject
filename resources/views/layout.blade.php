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

    <style>
        /* Estilos personalizados para Driver.js */
        .driver-popover {
            border-radius: 12px !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
            border: none !important;
        }

        .driver-popover-title {
            font-weight: 600 !important;
            color: #0d6efd !important;
            font-size: 1.1em !important;
        }

        .driver-popover-description {
            color: #495057 !important;
            line-height: 1.5 !important;
        }

        .driver-popover-footer {
            border-top: 1px solid #e9ecef !important;
            padding-top: 12px !important;
        }

        .driver-popover-next-btn,
        .driver-popover-prev-btn,
        .driver-popover-close-btn {
            border-radius: 8px !important;
            transition: all 0.2s ease !important;
        }

        .driver-popover-next-btn {
            background: linear-gradient(135deg, #0d6efd, #0056b3) !important;
        }

        .driver-popover-next-btn:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3) !important;
        }

        .driver-highlighted {
            animation: pulse-highlight 2s infinite !important;
        }

        @keyframes pulse-highlight {
            0% {
                box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(13, 110, 253, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(13, 110, 253, 0);
            }
        }

        .guide-btn {
            background: linear-gradient(135deg, #17a2b8, #138496);
            border: none;
            box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3);
            transition: all 0.3s ease;
        }

        .guide-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(23, 162, 184, 0.4);
        }

        .guide-btn i {
            animation: bounce 2s infinite;
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-5px);
            }

            60% {
                transform: translateY(-3px);
            }
        }
    </style>

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
