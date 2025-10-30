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
    <footer id="footer" role="contentinfo" aria-labelledby="footer-heading">
        <div class="footer-top">
          <div class="container">
            <div class="row">

              <!-- Contacto -->
              <section class="col-lg-4 col-md-6 footer-contact" aria-labelledby="footer-heading">
                <h3 id="footer-heading">Aprendo Hoy</h3>


        

                <p class="mb-0">
                  <strong>Correo Electrónico:</strong>
                  <a href="mailto:contacto@educarparalavida.org.bo" aria-label="Enviar correo a contacto arroba educar para la vida punto org punto bo">
                    contacto@educarparalavida.org.bo
                  </a>
                </p>
              </section>

              <!-- Enlaces -->
              <nav class="col-lg-4 col-md-6 footer-links" aria-label="Enlaces asociados">
                <h4>Links Asociados</h4>
                <ul class="list-unstyled">
                  <li>
                    <i class="bx bx-chevron-right"></i>
                    <a href="https://educarparalavida.org.bo/web/Inicio.html" target="_blank" rel="noopener" aria-label="Abrir Inicio en una nueva pestaña">Inicio</a>
                  </li>
                  <li>
                    <i class="bx bx-chevron-right"></i>
                    <a href="https://educarparalavida.org.bo/web/Quienes-somos.html" target="_blank" rel="noopener" aria-label="Abrir Quienes Somos en una nueva pestaña">Quiénes Somos</a>
                  </li>
                  <li>
                    <i class="bx bx-chevron-right"></i>
                    <a href="https://educarparalavida.org.bo/web/Proyectos-y-servicios.html" target="_blank" rel="noopener" aria-label="Abrir Servicios en una nueva pestaña">Servicios</a>
                  </li>
                </ul>
              </nav>

              <!-- Redes Sociales -->
              <section class="col-lg-4 col-md-6 footer-links" aria-label="Redes sociales">
                <h4>Nuestras Redes Sociales</h4>
                <div class="social-links mt-3">
                  <a href="https://x.com/FUNDVIDA2" class="twitter" target="_blank" rel="noopener" aria-label="Abrir perfil en X de Fundación">
                    <i class="bx bxl-twitter" aria-hidden="true"></i>
                  </a>
                  <a href="https://www.facebook.com/profile.php?id=100063510101095" class="facebook" target="_blank" rel="noopener" aria-label="Abrir página en Facebook">
                    <i class="bx bxl-facebook" aria-hidden="true"></i>
                  </a>
                  <a href="https://www.instagram.com/fundeducarparalavida/" class="instagram" target="_blank" rel="noopener" aria-label="Abrir perfil en Instagram">
                    <i class="bx bxl-instagram" aria-hidden="true"></i>
                  </a>
                  <a href="https://wa.me/59172087186" class="whatsapp" target="_blank" rel="noopener" aria-label="Abrir chat de WhatsApp">
                    <i class="bx bxl-whatsapp" aria-hidden="true"></i>
                  </a>
                </div>
              </section>

            </div>
          </div>
        </div>

        <!-- Parte inferior -->
        <div class="container py-4">
          <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2">
            <div class="copyright m-0">
              <span>
                &copy; <span id="copyright-year"></span>
                <a href="#" aria-label="Fundación educar para la vida">Fundación Educar para la Vida</a>.
              </span>
            </div>

            <ul class="list-inline m-0">
              <li class="list-inline-item"><a href="#" aria-label="Ver política de privacidad">Política de privacidad</a></li>
              <li class="list-inline-item">|</li>
              <li class="list-inline-item"><a href="#" aria-label="Ver términos y condiciones">Términos</a></li>
              <li class="list-inline-item">|</li>
              <li class="list-inline-item"><a href="#" aria-label="Contactar">Contacto</a></li>
            </ul>
          </div>

          <script>
            document.getElementById('copyright-year').textContent = new Date().getFullYear();
          </script>
        </div>
      </footer>


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
