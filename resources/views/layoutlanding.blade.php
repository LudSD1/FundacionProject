<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Aprendo Hoy</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <link href="{{ asset('assets/img/Acceder.png') }}" rel="icon">
    <link rel="preconnect" href="https://fonts.bunny.net">

    {{-- CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{ asset('assets2/css/style.css') }}" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<style>
    :root {
        --body-padding: {{ Auth::check() ? '125px' : '0px' }};
    }
</style>

<body >

    @include('components.headerlanding')


    @yield('hero')
    @yield('main')

    @include('components.footer')

    @role('estudiante')
        @include('components.achievements')
    @endrole

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/lite-youtube-embed@0.2.0/src/lite-yt-embed.js" defer></script>

    <script src="{{ asset('assets2/js/main.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            if (typeof AOS !== 'undefined') AOS.init();

            const header = document.getElementById('header');
            let ticking = false;

            function updateHeader() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                header.classList.toggle('header-scrolled', scrollTop > 100);
                header.classList.toggle('header-transparent', scrollTop <= 100);
                ticking = false;
            }

            window.addEventListener('scroll', function () {
                if (!ticking) {
                    requestAnimationFrame(updateHeader);
                    ticking = true;
                }
            });

            updateHeader();
            document.querySelectorAll('.modal').forEach(function (modal) {
                modal.addEventListener('hidden.bs.modal', function () {
                    document.querySelectorAll('.modal-backdrop').forEach(function (b) { b.remove(); });
                    document.body.classList.remove('modal-open');
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                });
            });

            document.addEventListener('click', function (event) {
                if (event.target.classList.contains('modal-backdrop')) {
                    document.querySelectorAll('.modal.show').forEach(function (modal) {
                        const instance = bootstrap.Modal.getInstance(modal);
                        if (instance) instance.hide();
                    });
                }
            });

            const congresosEl = document.querySelector('.congresos-slider');
            if (congresosEl) {
                new Swiper(congresosEl, {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    loop: true,
                    pagination: { el: '.swiper-pagination', clickable: true },
                    breakpoints: {
                        768:  { slidesPerView: 2 },
                        1024: { slidesPerView: 3 },
                    },
                });
            }

            // ── Swiper: mySwiper ──
            const mySwiperEl = document.querySelector('.mySwiper');
            if (mySwiperEl) {
                new Swiper(mySwiperEl, {
                    loop: true,
                    spaceBetween: 20,
                    grabCursor: true,
                    centeredSlides: true,
                    slidesPerView: 'auto',
                    autoplay: { delay: 3000, disableOnInteraction: false },
                    navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
                    pagination: { el: '.swiper-pagination', clickable: true },
                    breakpoints: {
                        320:  { slidesPerView: 1 },
                        768:  { slidesPerView: 2 },
                        1024: { slidesPerView: 3 },
                    },
                });
            }

        });
    </script>

    {{-- ── SweetAlert: mensajes de sesión ── --}}
    <script>
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: '¡Oops!',
                text: '{{ implode(' ', $errors->all()) }}',
            });
        @endif

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('success') }}',
            });
        @endif

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
