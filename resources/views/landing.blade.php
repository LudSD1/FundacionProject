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
                        <h1 class="fw-bold">Transforma tu futuro con <span style="color: #FFA500;">APRENDO HOY</span></h1>
                        <p class="lead mb-4" style="font-size: 1.3rem; color: rgba(255,255,255,0.9);">
                            Descubre cursos especializados, eventos de desarrollo profesional y oportunidades de crecimiento
                            personal.
                            Aprende a tu ritmo, donde quieras y cuando quieras.
                        </p>
                        <a href="{{ route('lista.cursos.congresos') }}" class="download-btn">
                           Explorar Cursos y Eventos
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 d-lg-flex flex-lg-column align-items-stretch order-1 order-lg-2 hero-img"
                    data-aos="fade-up">
                    <img src="assets2/img/hero-img.png" class="img-fluid" alt="Hero Image">
                </div>
            </div>
        </div>
    </section>
@endsection



@section('main')  

    <main id="main">
        {{-- Buscador --}}
        <section id="congress-list" class="py-5 bg-light">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="fw-bold">🎯 Últimos Eventos y Congresos</h2>
                    <p class="text-muted">Explora nuestros eventos especializados y regístrate para participar con
                        certificación gratuita.
                        Conecta con expertos y amplía tu red profesional.</p>
                </div>

                @if ($congresos->count())
                    <div id="eventosCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($congresos->chunk(3) as $chunkIndex => $chunk)
                                <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">
                                    <div class="row">
                                        @foreach ($chunk as $congreso)
                                            @php
                                                $fecha_ini = \Carbon\Carbon::parse($congreso->fecha_ini);
                                                $fecha_fin = \Carbon\Carbon::parse($congreso->fecha_fin);
                                            @endphp
                                            <div class="col-md-4 mb-4">
                                                <div class="card h-100 shadow-sm">
                                                    <img src="{{ asset('assets2/img/congress.jpg') }}" class="card-img-top"
                                                        style="height: 200px; object-fit: cover;" alt="Imagen de congreso">
                                                    <div class="card-body text-center d-flex flex-column">
                                                        <h5 class="card-title fw-bold">{{ $congreso->nombreCurso }}</h5>
                                                        <p class="text-muted small">
                                                            📅
                                                            @if ($fecha_ini->month == $fecha_fin->month)
                                                                {{ $fecha_ini->format('d') }} -
                                                                {{ $fecha_fin->format('d') }} de
                                                                {{ $fecha_ini->locale('es')->isoFormat('MMMM') }}
                                                            @else
                                                                {{ $fecha_ini->format('d') }} de
                                                                {{ $fecha_ini->locale('es')->isoFormat('MMMM') }} -
                                                                {{ $fecha_fin->format('d') }} de
                                                                {{ $fecha_fin->locale('es')->isoFormat('MMMM') }}
                                                            @endif
                                                        </p>
                                                        <p class="small text-muted mb-3">
                                                            {{ Str::limit($congreso->descripcionC, 100) }}</p>
                                                        <a href="{{ route('evento.detalle', encrypt($congreso->id)) }}"
                                                            class="btn btn-primary btn-sm mt-auto">Inscribirse</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#eventosCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#eventosCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                        <h4 class="text-muted mt-3">No hay Congresos Disponibles</h4>
                        <p class="text-muted">Por favor, vuelve más tarde para ver los próximos eventos.</p>
                    </div>
                @endif
            </div>
        </section>



        <section id="cursos" class="py-5 bg-white">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="fw-bold">📚 Últimos Cursos Online</h2>
                    <p class="text-muted">Descubre tu potencial con nuestros cursos especializados. Aprende online,
                        obtén certificaciones y desarrolla habilidades que marquen la diferencia en tu carrera.</p>
                </div>

                @if ($cursos->count())
                    <div id="cursosCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($cursos->chunk(3) as $chunkIndex => $chunk)
                                <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">
                                    <div class="row">
                                        @foreach ($chunk as $curso)
                                            @php
                                                $fecha_ini = \Carbon\Carbon::parse($curso->fecha_ini);
                                                $fecha_fin = \Carbon\Carbon::parse($curso->fecha_fin);
                                            @endphp
                                            <div class="col-md-4 mb-4">
                                                <div class="card h-100 shadow-sm">
                                                    <img src="{{ asset('assets2/img/curso.jpg') }}" class="card-img-top"
                                                        style="height: 150px; object-fit: cover;" alt="Imagen de curso">
                                                    <div class="card-body text-center d-flex flex-column">
                                                        <h5 class="card-title fw-bold">{{ $curso->nombreCurso }}</h5>
                                                        <p class="text-muted small">
                                                            📅
                                                            @if ($fecha_ini->month == $fecha_fin->month)
                                                                {{ $fecha_ini->format('d') }} -
                                                                {{ $fecha_fin->format('d') }} de
                                                                {{ $fecha_ini->locale('es')->isoFormat('MMMM') }}
                                                            @else
                                                                {{ $fecha_ini->format('d') }} de
                                                                {{ $fecha_ini->locale('es')->isoFormat('MMMM') }} -
                                                                {{ $fecha_fin->format('d') }} de
                                                                {{ $fecha_fin->locale('es')->isoFormat('MMMM') }}
                                                            @endif
                                                        </p>
                                                        <p class="small text-muted mb-3">
                                                            {{ Str::limit($curso->descripcionC, 100) }}</p>
                                                        <a href="{{ route('evento.detalle', $curso) }}"
                                                            class="btn btn-primary btn-sm mt-auto">Inscribirse</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#cursosCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#cursosCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-book text-muted" style="font-size: 3rem;"></i>
                        <h4 class="text-muted mt-3">No hay Cursos Disponibles</h4>
                        <p class="text-muted">Por favor, vuelve más tarde para ver los próximos cursos.</p>
                    </div>
                @endif
            </div>
        </section>



        <section id="features" class="features">
            <div class="container">
                <div class="section-title">
                    <h2>🚀 ¿Por qué elegir APRENDO HOY?</h2>
                    <p>Descubre una plataforma educativa innovadora diseñada para tu éxito. Con tecnología avanzada,
                        contenido de calidad y un enfoque centrado en el estudiante, transformamos tu experiencia de aprendizaje.</p>
                </div>

                <div class="row align-items-center">
                    <div class="col-lg-6 order-2 order-lg-1">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="icon-box" data-aos="fade-up">
                                    <i class="bx bx-receipt"></i>
                                    <h4>📊 Evaluación Personalizada</h4>
                                    <p>Análisis detallado de tu progreso con recomendaciones personalizadas para optimizar tu aprendizaje.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="icon-box" data-aos="fade-up" data-aos-delay="100">
                                    <i class="bx bx-cube-alt"></i>
                                    <h4>🎨 Interfaz Intuitiva</h4>
                                    <p>Diseño moderno y fácil navegación que te permite acceder a todos los recursos sin complicaciones.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="icon-box" data-aos="fade-up" data-aos-delay="200">
                                    <i class="bx bx-images"></i>
                                    <h4>📚 Recursos Educativos</h4>
                                    <p>Contenido multimedia de alta calidad: videos, lecturas y ejercicios prácticos creados por expertos.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="icon-box" data-aos="fade-up" data-aos-delay="300">
                                    <i class="bx bx-shield"></i>
                                    <h4>🔒 Máxima Seguridad</h4>
                                    <p>Protección avanzada de tus datos con tecnología de cifrado de última generación.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="icon-box" data-aos="fade-up" data-aos-delay="400">
                                    <i class="bx bx-atom"></i>
                                    <h4>🤝 Aprendizaje Interactivo</h4>
                                    <p>Foros, sesiones en vivo y actividades colaborativas que hacen del aprendizaje una experiencia dinámica.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="icon-box" data-aos="fade-up" data-aos-delay="500">
                                    <i class="bx bx-id-card"></i>
                                    <h4>🌐 Acceso Ilimitado</h4>
                                    <p>Estudia cuando y donde quieras con acceso completo a todos nuestros cursos desde cualquier dispositivo.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 order-1 order-lg-2 text-center">
                        <div class="features-image" data-aos="fade-left" data-aos-delay="100">
                            <img src="assets2/img/details-5.png" class="img-fluid" alt="Características" style="max-width: 100%; height: auto;">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- App Features Section -->

        <!-- Details Section -->
        <section id="details" class="details">
            <div class="container">
                <div class="row align-items-center mb-5">
                    <div class="col-lg-6 order-2 order-lg-1">
                        <div class="details-content" data-aos="fade-up">
                            <h3>Obtén resultados beneficiosos con los cursos y talleres que se ofrecen.</h3>
                            <p class="fst-italic">
                                Nuestros cursos y congresos te brindan las herramientas y el conocimiento práctico que
                                necesitas para alcanzar tus metas profesionales. Aprende de expertos en la industria y
                                desarrolla habilidades.
                            </p>
                            <ul class="benefits-list">
                                <li><i class="bi bi-check"></i> Certificación Internacional.</li>
                                <li><i class="bi bi-check"></i> Pago accesible a través de aplicaciones.</li>
                                <li><i class="bi bi-check"></i> Aprendizaje adecuado a tus necesidades.</li>
                                <li><i class="bi bi-check"></i> Temáticas de impacto.</li>
                            </ul>
                            <p class="highlight-text">
                                "Amplía tus conocimientos y habilidades con nuestra oferta integral de cursos y congresos.
                                Los cursos te brindan una formación profunda y práctica en áreas específicas, mientras que
                                los congresos te exponen a las últimas tendencias y
                                te conectan con profesionales de tu sector. ¡Combina ambas experiencias y maximiza tu
                                potencial de crecimiento!"
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-6 order-1 order-lg-2 text-center">
                        <div class="details-image" data-aos="fade-right">
                            <img src="assets2/img/details-1.png" class="img-fluid" alt="Beneficios de los cursos">
                        </div>
                    </div>
                </div>

                <div class="row align-items-center">
                    <div class="col-lg-6 text-center">
                        <div class="details-image" data-aos="fade-left">
                            <img src="assets2/img/details-2.png" class="img-fluid" alt="Beneficios adicionales">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="details-content" data-aos="fade-up">
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
            </div>
        </section>

        <!-- Collaborators Section -->
        <section id="collaborators" class="collaborators">
            <div class="container">
                <div class="section-title">
                    <h2>🤝 Nuestros Colaboradores</h2>
                    <p>Instituciones y organizaciones que confían en nosotros para brindar educación de calidad</p>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="collaborators-grid">
                            <div class="collaborator-item" data-aos="fade-up" data-aos-delay="100">
                                <img src="https://cdn.jsdelivr.net/gh/LudSD1/aphcolaboradores@main/logos/logo1.png"
                                     alt="Colaborador 1" class="img-fluid">
                            </div>
                            {{-- <div class="collaborator-item" data-aos="fade-up" data-aos-delay="200">
                                <img src="https://cdn.jsdelivr.net/gh/LudSD1/aphcolaboradores@main/logos/logo2.png"
                                     alt="Colaborador 2" class="img-fluid">
                            </div>
                            <div class="collaborator-item" data-aos="fade-up" data-aos-delay="300">
                                <img src="https://cdn.jsdelivr.net/gh/LudSD1/aphcolaboradores@main/logos/logo3.png"
                                     alt="Colaborador 3" class="img-fluid">
                            </div>
                            <div class="collaborator-item" data-aos="fade-up" data-aos-delay="400">
                                <img src="https://cdn.jsdelivr.net/gh/LudSD1/aphcolaboradores@main/logos/logo4.png"
                                     alt="Colaborador 4" class="img-fluid">
                            </div>
                            <div class="collaborator-item" data-aos="fade-up" data-aos-delay="500">
                                <img src="https://cdn.jsdelivr.net/gh/LudSD1/aphcolaboradores@main/logos/logo5.png"
                                     alt="Colaborador 5" class="img-fluid">
                            </div>
                            <div class="collaborator-item" data-aos="fade-up" data-aos-delay="600">
                                <img src="https://cdn.jsdelivr.net/gh/LudSD1/aphcolaboradores@main/logos/logo6.png"
                                     alt="Colaborador 6" class="img-fluid">
                            </div> --}}
                        </div>
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
