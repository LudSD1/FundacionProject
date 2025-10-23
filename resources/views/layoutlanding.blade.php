<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Aprendo Hoy</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <link href="{{ asset('assets/img/Acceder.png') }}" rel="icon">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="{{ asset('assets2/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets2/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets2/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets2/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets2/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets2/css/style.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/driver.js@latest/dist/driver.js.iife.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@latest/dist/driver.css" />
</head>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Detectar cuando cualquier modal se oculta
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('hidden.bs.modal', function() {
                // Buscar y eliminar backdrop sobrante
                document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop
                    .remove());

                // Asegurar que el body no quede bloqueado
                document.body.classList.remove('modal-open');
                document.body.style.overflow = 'auto'; // Habilitar scroll si estaba bloqueado
                document.body.style.paddingRight = ''; // Corregir desplazamiento
            });
        });
    });
</script>

<body>

    @include('components.headerlanding')

    @yield('hero')
    @yield('main')
    <footer id="footer">
        <div class="footer-top">
            <div class="container">
                <div class="row">

                    <div class="col-lg-3 col-md-6 footer-contact">
                        <h3>Aprendo Hoy</h3>
                        <p>

                            Bolivia <br><br>
                            <strong>Celular:</strong><br>
                            (+591) 72087186 <br>
                            (+591) 4 4284295 <br>
                            (+591) 2 2433208 <br>
                            <strong>Correo Electrónico:</strong> contacto@educarparalavida.org.bo<br>
                        </p>
                    </div>

                    <div class="col-lg-3 col-md-6 footer-links">
                        <h4>Links Asociados</h4>
                        <ul>
                            <li><i class="bx bx-chevron-right"></i> <a
                                    href="https://educarparalavida.org.bo/web/Inicio.html">Inicio</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a
                                    href="https://educarparalavida.org.bo/web/Quienes-somos.html">Quienes Somos</a>
                            </li>
                            <li><i class="bx bx-chevron-right"></i> <a
                                    href="https://educarparalavida.org.bo/web/Proyectos-y-servicios.html">Servicios</a>
                            </li>
                        </ul>
                    </div>


                    <div class="col-lg-3 col-md-6 footer-links">
                        <h4>Nuestras Redes Sociales</h4>
                        <div class="social-links mt-3">
                            <a href="https://x.com/FUNDVIDA2" class="twitter"><i class="bx bxl-twitter"></i></a>
                            <a href="https://www.facebook.com/profile.php?id=100063510101095" class="facebook"><i
                                    class="bx bxl-facebook"></i></a>
                            <a href="https://www.instagram.com/fundeducarparalavida/" class="instagram"><i
                                    class="bx bxl-instagram"></i></a>
                            <a href="https://api.whatsapp.com/send?phone=%3C+59172087186%3E" class="whatsapp"><i
                                    class="bx bxl-whatsapp"></i></a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="container py-4">
            <div class="copyright">
                <script>
                    document.write("&copy; " + new Date().getFullYear() +
                        " <a href='' target='_blank'>Fundación educar para la vida</a>.");
                </script>
            </div>
            <div class="credits">
            </div>
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center md-5"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets2/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('assets2/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets2/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('assets2/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets2/vendor/php-email-form/validate.js') }}"></script>


    <!-- Template Main JS File -->
    <script src="{{ asset('assets2/js/main.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Escuchar el evento cuando un modal se oculta
            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('hidden.bs.modal', function() {
                    // Eliminar backdrop manualmente si aún existe
                    document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop
                        .remove());
                    document.body.classList.remove('modal-open'); // Eliminar clase modal-open
                    document.body.style.paddingRight = ''; // Corregir posibles desplazamientos
                });
            });
        });
    </script>

    <script>
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal-backdrop')) {
                document.querySelectorAll('.modal.show').forEach(modal => {
                    let modalInstance = bootstrap.Modal.getInstance(modal);
                    if (modalInstance) modalInstance.hide();
                });
            }
        });
    </script>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new Swiper(".congresos-slider", {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: true,
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2
                    },
                    1024: {
                        slidesPerView: 3
                    },
                },
            });
        });
    </script>


    <!-- Script para inicializar Swiper -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new Swiper(".mySwiper", {
                loop: true,
                spaceBetween: 20,
                grabCursor: true,
                centeredSlides: true,
                slidesPerView: "auto",
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev"
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true
                },
                breakpoints: {
                    320: {
                        slidesPerView: 1
                    },
                    768: {
                        slidesPerView: 2
                    },
                    1024: {
                        slidesPerView: 3
                    }
                }
            });
        });
    </script>



    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Mejorar la transición del header con el scroll
        document.addEventListener('DOMContentLoaded', function() {
            const header = document.getElementById('header');
            let lastScrollTop = 0;
            let ticking = false;

            function updateHeader() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

                if (scrollTop > 100) {
                    header.classList.add('header-scrolled');
                    header.classList.remove('header-transparent');
                } else {
                    header.classList.add('header-transparent');
                    header.classList.remove('header-scrolled');
                }

                lastScrollTop = scrollTop;
                ticking = false;
            }

            function requestTick() {
                if (!ticking) {
                    requestAnimationFrame(updateHeader);
                    ticking = true;
                }
            }

            window.addEventListener('scroll', requestTick);

            // Inicializar el estado del header
            updateHeader();
        });
    </script>

    <script>
        // Este código muestra una alerta de error con SweetAlert
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: '¡Oops!',
                text: '{{ implode(' ', $errors->all()) }}',
            });
        @endif

        // Mostrar éxito si se ha enviado correctamente
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('success') }}',
            });
        @endif

        // Mostrar información si hay un mensaje informativo
        @if (session('info'))
            Swal.fire({
                icon: 'info',
                title: 'Información',
                text: '{{ session('info') }}',
            });
        @endif
    </script>


</body>

</html>
