

@php
use Carbon\Carbon;
@endphp

@section('hero')
<section id="hero" class="d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 d-lg-flex flex-lg-column justify-content-center align-items-stretch pt-5 pt-lg-0 order-2 order-lg-1"
                data-aos="fade-up">
                <div>
                    <h1 class="fw-bold">"Aprende a tu ritmo, donde quieras y cuando quieras. ¡Tu futuro comienza
                        aquí!"</h1>
                    {{-- <h2>Lorem ipsum dolor sit amet, tota senserit percipitur ius ut, usu et fastidii forensibus voluptatibus. His ei nihil feugait</h2> --}}
                    <a href="{{ route('lista.cursos.congresos') }}" class="download-btn"><i
                            class="bi bi-collection"></i> Ir a la lista de Cursos</a>
                </div>
            </div>
            <div class="col-lg-6 d-lg-flex flex-lg-column align-items-stretch order-1 order-lg-2 hero-img"
                data-aos="fade-up">
                <img src="assets2/img/hero-img.png" class="img-fluid" alt="Hero Image">
            </div>
        </div>
    </div>
</section><!-- End Hero -->
@endsection



@section('main')

<main id="main">
    <!-- Congress List Section -->
    <!-- Últimos Congresos - Card Swiper -->
    <section id="congress-list" class="py-5 bg-light">
        <div class="container">
            <!-- Título de la sección -->
            <div class="text-center mb-4">
                <h2 class="fw-bold">Últimos Congresos</h2>
                <p class="text-muted">Explora los congresos próximos y regístrate para participar en eventos de alto
                    impacto.</p>
            </div>

            <!-- Swiper Slider -->
            <div class="swiper congresosSwiper">
                <div class="swiper-wrapper">
                    @forelse ($congresos as $congreso)
                        @php
                            $fecha_ini = Carbon::parse($congreso->fecha_ini);
                            $fecha_fin = Carbon::parse($congreso->fecha_fin);
                        @endphp
                        <div class="swiper-slide">
                            <div class="card border-0 shadow-sm">
                                <img src="{{ asset('assets2/img/congress.jpg') }}" class="card-img-top"
                                    style="height: 200px; object-fit: cover; border-radius: 10px 10px 0 0;"
                                    alt="Imagen de congreso">
                                <div class="card-body text-center">
                                    <h5 class="card-title fw-bold">{{ $congreso->nombreCurso }}</h5>
                                    <p class="text-muted small">
                                        📅
                                        @if ($fecha_ini->month == $fecha_fin->month)
                                            {{ $fecha_ini->format('d') }} - {{ $fecha_fin->format('d') }} de
                                            {{ $fecha_ini->locale('es')->isoFormat('MMMM') }}
                                        @else
                                            {{ $fecha_ini->format('d') }} de
                                            {{ $fecha_ini->locale('es')->isoFormat('MMMM') }} -
                                            {{ $fecha_fin->format('d') }} de
                                            {{ $fecha_fin->locale('es')->isoFormat('MMMM') }}
                                        @endif
                                    </p>
                                    <p class="small text-muted">{{ Str::limit($congreso->descripcionC, 100) }}</p>
                                    <a href="{{ route('congreso.detalle', $congreso->id) }}"
                                        class="btn btn-primary btn-sm">
                                        Inscribirse
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center">
                            <h4 class="text-muted">No hay Congresos Disponibles</h4>
                        </div>
                    @endforelse
                </div>

                <!-- Botones de Navegación -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>

                <!-- Paginación -->
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <!-- Swiper.js (CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Script para inicializar Swiper -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new Swiper(".congresosSwiper", {
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

    <!-- Cursos Section -->
    <section id="cursos" class="py-5 bg-light">
        <div class="container" data-aos="fade-up">
            <div class="section-title text-center mb-4">
                <h2>Últimos Cursos</h2>
                <p class="text-muted">Descubre tu potencial, aprende online.</p>
            </div>

            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    @forelse ($cursos as $curso)
                        @php
                            $fecha_ini = Carbon::parse($curso->fecha_ini);
                            $fecha_fin = Carbon::parse($curso->fecha_fin);
                        @endphp
                        <div class="swiper-slide">
                            <div class="card border-0 shadow-sm">
                                <img src="{{ asset('assets2/img/curso.jpg') }}" class="card-img-top"
                                    style="height: 150px; object-fit: cover; border-radius: 10px;"
                                    alt="Imagen de curso">
                                <div class="card-body text-center">
                                    <h5 class="card-title fw-bold">{{ $curso->nombreCurso }}</h5>
                                    <p class="card-text text-muted">
                                        📅
                                        @if ($fecha_ini->month == $fecha_fin->month)
                                            {{ $fecha_ini->format('d') }} - {{ $fecha_fin->format('d') }} de
                                            {{ $fecha_ini->locale('es')->isoFormat('MMMM') }}
                                        @else
                                            {{ $fecha_ini->format('d') }} de
                                            {{ $fecha_ini->locale('es')->isoFormat('MMMM') }} -
                                            {{ $fecha_fin->format('d') }} de
                                            {{ $fecha_fin->locale('es')->isoFormat('MMMM') }}
                                        @endif
                                    </p>
                                    <p class="card-text">{{ $curso->descripcionC }}</p>
                                    <a href="{{ route('congreso.detalle', $curso->id) }}"
                                        class="btn btn-primary btn-sm">
                                        Inscribirse
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center">
                            <h4>No hay Cursos Disponibles</h4>
                        </div>
                    @endforelse
                </div>

                <!-- Botones de navegación Swiper -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>

                <!-- Paginación -->
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>


    <!-- App Features Section -->
    <section id="features" class="features">
        <div class="container">
            <div class="section-title">
                <h2>Sistema de Cursos</h2>
                <p>Nuestro sistema de cursos está diseñado para satisfacer tus necesidades educativas, ofreciendo
                    una experiencia de aprendizaje única y accesible para todos. Con características avanzadas y un
                    enfoque en la interactividad y la seguridad, estamos aquí para ayudarte a alcanzar tus metas
                    académicas y profesionales.</p>
            </div>

            <div class="row no-gutters">
                <div class="col-xl-7 d-flex align-items-stretch order-2 order-lg-1">
                    <div class="content d-flex flex-column justify-content-center">
                        <div class="row">
                            <div class="col-md-6 icon-box" data-aos="fade-up">
                                <i class="bx bx-receipt"></i>
                                <h4>Evaluativo</h4>
                                <p>Proporciona una evaluación detallada y personalizada para cada estudiante,
                                    ayudándolos a comprender mejor sus fortalezas y áreas de mejora.</p>
                            </div>
                            <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="100">
                                <i class="bx bx-cube-alt"></i>
                                <h4>Interfaz</h4>
                                <p>Ofrece una interfaz intuitiva y fácil de usar que facilita la navegación y el
                                    acceso a todos los recursos educativos disponibles.</p>
                            </div>
                            <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="200">
                                <i class="bx bx-images"></i>
                                <h4>Recursos Educativos</h4>
                                <p>Accede a una amplia variedad de recursos educativos, incluyendo videos, lecturas,
                                    y ejercicios prácticos diseñados por expertos en la materia.</p>
                            </div>
                            <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="300">
                                <i class="bx bx-shield"></i>
                                <h4>Seguridad</h4>
                                <p>Garantizamos la máxima seguridad de tus datos personales y académicos, utilizando
                                    las últimas tecnologías en cifrado y protección de información.</p>
                            </div>
                            <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="400">
                                <i class="bx bx-atom"></i>
                                <h4>Interactividad</h4>
                                <p>Fomenta la interactividad a través de foros de discusión, sesiones en vivo, y
                                    actividades colaborativas que enriquecen el proceso de aprendizaje.</p>
                            </div>
                            <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="500">
                                <i class="bx bx-id-card"></i>
                                <h4>Acceso a cursos</h4>
                                <p>Disfruta de acceso ilimitado a una vasta selección de cursos, disponibles en
                                    cualquier momento y desde cualquier dispositivo.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="image col-xl-5 d-flex align-items-stretch justify-content-center order-1 order-lg-2"
                    data-aos="fade-left" data-aos-delay="100">
                    <img src="assets2/img/details-5.png" class="img-fluid" alt="Características" width="600px"
                        height="auto">
                </div>
            </div>
        </div>
    </section>

    <!-- Details Section -->
    <section id="details" class="details">
        <div class="container">
            <div class="row content">
                <div class="col-md-4" data-aos="fade-right">
                    <img src="assets2/img/details-1.png" class="img-fluid" alt="Beneficios de los cursos">
                </div>
                <div class="col-md-8 pt-4" data-aos="fade-up">
                    <h3>Obtén resultados beneficiosos con los cursos y talleres que se ofrecen.</h3>
                    <p class="fst-italic">
                        Nuestros cursos y congresos te brindan las herramientas y el conocimiento práctico que
                        necesitas para alcanzar tus metas profesionales. Aprende de expertos en la industria y
                        desarrolla habilidades.
                    </p>
                    <ul>
                        <li><i class="bi bi-check"></i> Certificación Internacional.</li>
                        <li><i class="bi bi-check"></i> Pago accesible a través de aplicaciones.</li>
                        <li><i class="bi bi-check"></i> Aprendizaje adecuado a tus necesidades.</li>
                        <li><i class="bi bi-check"></i> Temáticas de impacto.</li>
                    </ul>
                    <p>
                        "Amplía tus conocimientos y habilidades con nuestra oferta integral de cursos y congresos.
                        Los cursos te brindan una formación profunda y práctica en áreas específicas, mientras que
                        los congresos te exponen a las últimas tendencias y
                        te conectan con profesionales de tu sector. ¡Combina ambas experiencias y maximiza tu
                        potencial de crecimiento!"
                    </p>
                </div>
            </div>

            <div class="row content">
                <div class="col-md-4 order-1 order-md-2" data-aos="fade-left">
                    <img src="assets2/img/details-2.png" class="img-fluid" alt="Beneficios adicionales">
                </div>
                <div class="col-md-8 pt-5 order-2 order-md-1" data-aos="fade-up">
                    <h3>Beneficios adicionales</h3>
                    <p class="fst-italic">
                        Participa en actividades colaborativas y discusiones en foros que fomentan la interactividad
                        y el aprendizaje activo. Con acceso ilimitado a nuestros cursos, puedes estudiar cuando y
                        donde quieras, adaptando tu educación a tu estilo de vida.
                    </p>
                    <p>
                        Nuestro compromiso es ofrecerte una educación de calidad, con un soporte constante y
                        recursos actualizados que te ayudarán a alcanzar tus metas académicas y profesionales. Únete
                        a nuestra comunidad de estudiantes y descubre una nueva forma de aprender.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact">
        <div class="container" data-aos="fade-up">
            <div class="section-title">
                <h2><i class="bi bi-chat-dots"></i> CONTÁCTANOS</h2>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-6 info">
                            <i class="bx bx-map"></i>
                            <h4>Direcciones</h4>
                            <p>
                                OFICINA CENTRAL: Av. Melchor Pérez de Olguín e Idelfonso Murgía Nro. 1253 Cochabamba
                                - Bolivia
                            </p>
                            <p>
                                OFICINA REGIONAL: Calle Pinilla Edifico Arcadia Nro. 2588 La Paz - Bolivia
                            </p>
                        </div>
                        <div class="col-lg-6 info">
                            <i class="bx bx-phone"></i>
                            <h4>Llámanos</h4>
                            <p>(+591) 72087186 <br>(+591) 4 4284295 <br>(+591) 2 2433208</p>
                        </div>
                        <div class="col-lg-6 info">
                            <i class="bx bx-envelope"></i>
                            <h4>Correo</h4>
                            <p>contacto@educarparalavida.org.bo</p>
                        </div>
                        <div class="col-lg-6 info">
                            <i class="bx bx-time-five"></i>
                            <h4>Horas de Oficina</h4>
                            <p>Lun - Vier: 9AM a 5PM</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

@endsection

@include('layoutlanding')