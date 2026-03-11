@php
    use Carbon\Carbon;
@endphp

@section('hero')
<section id="hero">
    <div class="container h-100">
        <div class="row align-items-center h-100 gy-5">
            <div class="col-lg-6 order-2 order-lg-1" data-aos="fade-right" data-aos-duration="800">
                <div class="hero-text-wrap">
                    <div class="hero-badge">
                        <i class="bi bi-stars me-1"></i> Plataforma Educativa
                    </div>

                    <h1>
                        Transforma tu futuro con
                        <span class="hero-brand">APRENDO HOY</span>
                    </h1>

                    <p class="hero-lead">
                        Descubre cursos especializados, eventos de desarrollo profesional
                        y oportunidades de crecimiento personal. Aprende a tu ritmo,
                        donde quieras y cuando quieras.
                    </p>

                    <div class="hero-stats">
                        <div class="hero-stat">
                            <span class="hero-stat-num">{{ $cursos->count() + $congresos->count() }}</span>
                            <span class="hero-stat-label">Cursos activos</span>
                        </div>
                        <div class="hero-stat-divider"></div>
                        <div class="hero-stat">
                            <span class="hero-stat-num">100%</span>
                            <span class="hero-stat-label">En línea</span>
                        </div>
                        <div class="hero-stat-divider"></div>
                        <div class="hero-stat">
                            <span class="hero-stat-num">🎓</span>
                            <span class="hero-stat-label">Certificado</span>
                        </div>
                    </div>

                    <div class="hero-actions">
                        <a href="{{ route('lista.cursos.congresos') }}" class="hero-btn-primary">
                            <i class="bi bi-grid-fill me-2"></i>
                            Explorar Cursos
                        </a>
                        {{-- <a href="#features" class="hero-btn-outline">
                            <i class="bi bi-play-circle me-2"></i>
                            Cómo funciona
                        </a> --}}
                    </div>
                </div>
            </div>

            {{-- ── Columna derecha: carousel ── --}}
            <div class="col-lg-6 order-1 order-lg-2" data-aos="fade-left" data-aos-duration="800" data-aos-delay="200">
                @php
                    $destacados = collect();
                    if ($congresos->count()) $destacados = $destacados->merge($congresos->take(3));
                    if ($cursos->count())    $destacados = $destacados->merge($cursos->take(3));
                @endphp

                @if($destacados->count())
                <div class="hc-wrapper">
                    {{-- Decoración de fondo --}}
                    <div class="hc-deco hc-deco-1"></div>
                    <div class="hc-deco hc-deco-2"></div>

                    <div id="heroCarousel" class="carousel slide hc-carousel" data-bs-ride="carousel" data-bs-interval="4500">

                        {{-- Indicators --}}
                        <div class="carousel-indicators hc-indicators">
                            @foreach($destacados as $index => $item)
                                <button type="button"
                                    data-bs-target="#heroCarousel"
                                    data-bs-slide-to="{{ $index }}"
                                    class="{{ $index == 0 ? 'active' : '' }}"
                                    aria-label="Slide {{ $index + 1 }}"></button>
                            @endforeach
                        </div>

                        {{-- Slides --}}
                        <div class="carousel-inner">
                            @foreach($destacados as $index => $item)
                                @php
                                    $esCongreso  = $item->tipo === 'congreso' || $item->tipo === 'Congreso';
                                    $fecha_ini   = \Carbon\Carbon::parse($item->fecha_ini);
                                    $fecha_fin   = \Carbon\Carbon::parse($item->fecha_fin);
                                    $mismoMes    = $fecha_ini->month == $fecha_fin->month;
                                    $hoy         = now();
                                    $activo      = $hoy->between($fecha_ini, $fecha_fin);
                                    $proximo     = $hoy->lt($fecha_ini);
                                @endphp
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <div class="hc-card">

                                        {{-- Imagen --}}
                                        <div class="hc-card-img">
                                            @if($item->imagen)
                                                <img src="{{ asset('storage/'.$item->imagen) }}"
                                                     alt="{{ $item->nombreCurso }}"
                                                     loading="{{ $index == 0 ? 'eager' : 'lazy' }}">
                                            @else
                                                <img src="{{ asset($esCongreso ? 'assets2/img/congress.jpg' : 'assets2/img/curso.jpg') }}"
                                                     alt="{{ $item->nombreCurso }}"
                                                     loading="{{ $index == 0 ? 'eager' : 'lazy' }}">
                                            @endif
                                            {{-- Overlay gradiente --}}
                                            <div class="hc-card-img-overlay"></div>

                                            {{-- Badge tipo --}}
                                            <span class="hc-badge {{ $esCongreso ? 'hc-badge-event' : 'hc-badge-course' }}">
                                                {{ $esCongreso ? '🎯 Evento' : '📚 Curso' }}
                                            </span>

                                            {{-- Estado --}}
                                            <span class="hc-status {{ $activo ? 'hc-status-active' : ($proximo ? 'hc-status-soon' : 'hc-status-done') }}">
                                                <span class="hc-status-dot"></span>
                                                {{ $activo ? 'En curso' : ($proximo ? 'Próximo' : 'Finalizado') }}
                                            </span>
                                        </div>

                                        {{-- Contenido --}}
                                        <div class="hc-card-body">
                                            <h3 class="hc-card-title">
                                                {{ Str::limit($item->nombreCurso, 55) }}
                                            </h3>

                                            <div class="hc-card-meta">
                                                <span class="hc-meta-item">
                                                    <i class="bi bi-calendar-event"></i>
                                                    @if($mismoMes)
                                                        {{ $fecha_ini->format('d') }} - {{ $fecha_fin->format('d') }}
                                                        de {{ $fecha_ini->locale('es')->isoFormat('MMMM YYYY') }}
                                                    @else
                                                        {{ $fecha_ini->locale('es')->isoFormat('D MMM') }} —
                                                        {{ $fecha_fin->locale('es')->isoFormat('D MMM YYYY') }}
                                                    @endif
                                                </span>
                                                @if($item->formato)
                                                <span class="hc-meta-item">
                                                    <i class="bi bi-display"></i> {{ $item->formato }}
                                                </span>
                                                @endif
                                            </div>

                                            <p class="hc-card-desc">
                                                {{ Str::limit($item->descripcionC, 90) }}
                                            </p>

                                            <div class="hc-card-footer">
                                                @if($item->docente)
                                                <div class="hc-docente">
                                                    <div class="hc-docente-avatar">
                                                        {{ strtoupper(substr($item->docente->name, 0, 1)) }}
                                                    </div>
                                                    <span>{{ $item->docente->name }} {{ $item->docente->lastname1 }}</span>
                                                </div>
                                                @endif
                                                <a href="{{ route('Curso', $item->codigoCurso) }}" class="hc-card-btn">
                                                    Ver más <i class="bi bi-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Controles --}}
                        <button class="hc-ctrl hc-ctrl-prev" type="button"
                            data-bs-target="#heroCarousel" data-bs-slide="prev">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button class="hc-ctrl hc-ctrl-next" type="button"
                            data-bs-target="#heroCarousel" data-bs-slide="next">
                            <i class="bi bi-chevron-right"></i>
                        </button>

                    </div>{{-- fin carousel --}}
                </div>{{-- fin hc-wrapper --}}

                @else
                    <div class="text-center">
                        <img src="assets2/img/hero-img.png" class="img-fluid" alt="Hero">
                    </div>
                @endif
            </div>

        </div>
    </div>
</section>
@endsection



@section('main')

    <main id="main">
        {{-- Buscador --}}
        <section id="congress-list" class="lp-section lp-section-dark">
            <div class="container">

                {{-- Header --}}
                <div class="lp-section-header" data-aos="fade-up">
                    <div class="lp-section-eyebrow lp-eyebrow-orange">
                        <i class="bi bi-calendar-event-fill me-1"></i> Eventos y Congresos
                    </div>
                    <h2 class="lp-section-title">Últimos <span>Eventos</span></h2>
                    <p class="lp-section-sub">
                        Eventos especializados con certificación gratuita. Conecta con expertos
                        y amplía tu red profesional.
                    </p>
                </div>

                @if($congresos->count())

                {{-- Grid de cards --}}
                <div class="lp-cards-track" id="eventosTrack" data-aos="fade-up" data-aos-delay="100">
                    @foreach($congresos as $index => $congreso)
                        @php
                            $fi = \Carbon\Carbon::parse($congreso->fecha_ini);
                            $ff = \Carbon\Carbon::parse($congreso->fecha_fin);
                            $ahora   = now();
                            $activo  = $ahora->between($fi, $ff);
                            $proximo = $ahora->lt($fi);
                            $label   = $activo ? 'En curso' : ($proximo ? 'Próximo' : 'Finalizado');
                            $dot     = $activo ? 'dot-green' : ($proximo ? 'dot-blue' : 'dot-grey');
                        @endphp
                        <div class="lp-card lp-card-event" data-aos="fade-up" data-aos-delay="{{ 100 + $index * 60 }}">

                            {{-- Imagen --}}
                            <div class="lp-card-img">
                                @if($congreso->imagen)
                                    <img src="{{ asset('storage/'.$congreso->imagen) }}" alt="{{ $congreso->nombreCurso }}">
                                @else
                                    <img src="{{ asset('assets2/img/congress.jpg') }}" alt="{{ $congreso->nombreCurso }}">
                                @endif
                                <div class="lp-card-overlay"></div>
                                <span class="lp-card-badge lp-badge-event">🎯 Evento</span>
                                <span class="lp-card-status {{ $dot }}">
                                    <span class="lp-dot"></span>{{ $label }}
                                </span>
                            </div>

                            {{-- Cuerpo --}}
                            <div class="lp-card-body">
                                <h3 class="lp-card-title">{{ Str::limit($congreso->nombreCurso, 60) }}</h3>

                                <div class="lp-card-meta">
                                    <span class="lp-meta">
                                        <i class="bi bi-calendar3"></i>
                                        @if($fi->month == $ff->month)
                                            {{ $fi->format('d') }} – {{ $ff->format('d') }} de {{ $fi->locale('es')->isoFormat('MMMM YYYY') }}
                                        @else
                                            {{ $fi->locale('es')->isoFormat('D MMM') }} – {{ $ff->locale('es')->isoFormat('D MMM YYYY') }}
                                        @endif
                                    </span>
                                    @if($congreso->formato)
                                    <span class="lp-meta">
                                        <i class="bi bi-display"></i> {{ $congreso->formato }}
                                    </span>
                                    @endif
                                </div>

                                <p class="lp-card-desc">{{ Str::limit($congreso->descripcionC, 95) }}</p>

                                <div class="lp-card-footer">
                                    @if($congreso->precio == 0 || $congreso->precio === null)
                                        <span class="lp-price-free"><i class="bi bi-award-fill me-1"></i>Gratuito</span>
                                    @else
                                        <span class="lp-price">Bs {{ number_format($congreso->precio, 2) }}</span>
                                    @endif
                                    <a href="{{ route('Curso', $congreso->codigoCurso) }}" class="lp-btn-card">
                                        Ver evento <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Ver todos --}}
                <div class="text-center mt-5" data-aos="fade-up">
                    <a href="{{ route('lista.cursos.congresos') }}" class="lp-btn-outline-dark">
                        <i class="bi bi-grid-fill me-2"></i>Ver todos los eventos
                    </a>
                </div>

                @else
                <div class="lp-empty" data-aos="fade-up">
                    <div class="lp-empty-icon">📅</div>
                    <h4>No hay eventos disponibles</h4>
                    <p>Vuelve pronto para ver los próximos congresos y eventos.</p>
                </div>
                @endif

            </div>
        </section>


        <section id="cursos" class="lp-section lp-section-light">
            <div class="container">

                {{-- Header --}}
                <div class="lp-section-header" data-aos="fade-up">
                    <div class="lp-section-eyebrow lp-eyebrow-blue">
                        <i class="bi bi-mortarboard-fill me-1"></i> Cursos Online
                    </div>
                    <h2 class="lp-section-title">Últimos <span>Cursos</span></h2>
                    <p class="lp-section-sub">
                        Aprende online, obtén certificaciones y desarrolla habilidades
                        que marquen la diferencia en tu carrera.
                    </p>
                </div>

                @if($cursos->count())

                <div class="lp-cards-track" id="cursosTrack" data-aos="fade-up" data-aos-delay="100">
                    @foreach($cursos as $index => $curso)
                        @php
                            $fi = \Carbon\Carbon::parse($curso->fecha_ini);
                            $ff = \Carbon\Carbon::parse($curso->fecha_fin);
                            $ahora   = now();
                            $activo  = $ahora->between($fi, $ff);
                            $proximo = $ahora->lt($fi);
                            $label   = $activo ? 'En curso' : ($proximo ? 'Próximo' : 'Finalizado');
                            $dot     = $activo ? 'dot-green' : ($proximo ? 'dot-blue' : 'dot-grey');
                        @endphp
                        <div class="lp-card lp-card-course" data-aos="fade-up" data-aos-delay="{{ 100 + $index * 60 }}">

                            <div class="lp-card-img">
                                @if($curso->imagen)
                                    <img src="{{ asset('storage/'.$curso->imagen) }}" alt="{{ $curso->nombreCurso }}">
                                @else
                                    <img src="{{ asset('assets2/img/curso.jpg') }}" alt="{{ $curso->nombreCurso }}">
                                @endif
                                <div class="lp-card-overlay"></div>
                                <span class="lp-card-badge lp-badge-course">📚 Curso</span>
                                <span class="lp-card-status {{ $dot }}">
                                    <span class="lp-dot"></span>{{ $label }}
                                </span>
                            </div>

                            <div class="lp-card-body">
                                <h3 class="lp-card-title">{{ Str::limit($curso->nombreCurso, 60) }}</h3>

                                <div class="lp-card-meta">
                                    <span class="lp-meta">
                                        <i class="bi bi-calendar3"></i>
                                        @if($fi->month == $ff->month)
                                            {{ $fi->format('d') }} – {{ $ff->format('d') }} de {{ $fi->locale('es')->isoFormat('MMMM YYYY') }}
                                        @else
                                            {{ $fi->locale('es')->isoFormat('D MMM') }} – {{ $ff->locale('es')->isoFormat('D MMM YYYY') }}
                                        @endif
                                    </span>
                                    @if($curso->duracion)
                                    <span class="lp-meta">
                                        <i class="bi bi-clock"></i> {{ $curso->duracion }}h
                                    </span>
                                    @endif
                                </div>

                                <p class="lp-card-desc">{{ Str::limit($curso->descripcionC, 95) }}</p>

                                {{-- Docente --}}
                                @if($curso->docente)
                                <div class="lp-docente">
                                    <div class="lp-docente-av">{{ strtoupper(substr($curso->docente->name, 0, 1)) }}</div>
                                    <span>{{ $curso->docente->name }} {{ $curso->docente->lastname1 }}</span>
                                </div>
                                @endif

                                <div class="lp-card-footer">
                                    @if($curso->precio == 0 || $curso->precio === null)
                                        <span class="lp-price-free"><i class="bi bi-award-fill me-1"></i>Gratuito</span>
                                    @else
                                        <span class="lp-price">Bs {{ number_format($curso->precio, 2) }}</span>
                                    @endif
                                    <a href="{{ route('Curso', $curso->codigoCurso) }}" class="lp-btn-card">
                                        Ver curso <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-5" data-aos="fade-up">
                    <a href="{{ route('lista.cursos.congresos') }}" class="lp-btn-solid">
                        <i class="bi bi-grid-fill me-2"></i>Ver todos los cursos
                    </a>
                </div>

                @else
                <div class="lp-empty" data-aos="fade-up">
                    <div class="lp-empty-icon">📚</div>
                    <h4>No hay cursos disponibles</h4>
                    <p>Vuelve pronto para ver los próximos cursos.</p>
                </div>
                @endif

            </div>
        </section>





        <section id="features" class="features">
            <div class="container">
                <div class="section-title">
                    <h2>🚀 ¿Por qué elegir APRENDO HOY?</h2>
                    <p>Descubre una plataforma educativa innovadora diseñada para tu éxito. Con tecnología avanzada,
                        contenido de calidad y un enfoque centrado en el estudiante, transformamos tu experiencia de
                        aprendizaje.</p>
                </div>

                <div class="row align-items-center">
                    <div class="col-lg-6 order-2 order-lg-1">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="icon-box" data-aos="fade-up">
                                    <i class="bx bx-receipt"></i>
                                    <h4>📊 Evaluación Personalizada</h4>
                                    <p>Análisis detallado de tu progreso con recomendaciones personalizadas para optimizar
                                        tu aprendizaje.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="icon-box" data-aos="fade-up" data-aos-delay="100">
                                    <i class="bx bx-cube-alt"></i>
                                    <h4>🎨 Interfaz Intuitiva</h4>
                                    <p>Diseño moderno y fácil navegación que te permite acceder a todos los recursos sin
                                        complicaciones.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="icon-box" data-aos="fade-up" data-aos-delay="200">
                                    <i class="bx bx-images"></i>
                                    <h4>📚 Recursos Educativos</h4>
                                    <p>Contenido multimedia de alta calidad: videos, lecturas y ejercicios prácticos creados
                                        por expertos.</p>
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
                                    <p>Foros, sesiones en vivo y actividades colaborativas que hacen del aprendizaje una
                                        experiencia dinámica.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="icon-box" data-aos="fade-up" data-aos-delay="500">
                                    <i class="bx bx-id-card"></i>
                                    <h4>🌐 Acceso Ilimitado</h4>
                                    <p>Estudia cuando y donde quieras con acceso completo a todos nuestros cursos desde
                                        cualquier dispositivo.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 order-1 order-lg-2 text-center">
                        <div class="features-image" data-aos="fade-left" data-aos-delay="100">
                            <img src="assets2/img/details-5.png" class="img-fluid" alt="Características"
                                style="max-width: 100%; height: auto;">
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
