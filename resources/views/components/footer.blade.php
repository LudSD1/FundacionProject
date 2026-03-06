<footer id="footer" role="contentinfo" aria-labelledby="footer-heading">
    <div class="footer-top py-5">
        <div class="container">

            <!-- Encabezado centrado -->
            <div class="row mb-5">
                <div class="col-12 footer-brand">
                    <h3 id="footer-heading" class="footer-title mb-3">Aprendo Hoy</h3>
                    <p class="footer-description">
                        Educación de calidad para construir un mejor futuro
                    </p>
                </div>
            </div>

            <!-- 3 columnas uniformes -->
            <div class="row g-4">

                <!-- Contacto -->
                <section class="col-lg-4 col-md-6 footer-contact" aria-label="Contacto">
                    <h4 class="footer-title mb-4">Contacto</h4>
                    <div class="contact-item">
                        <div class="d-flex align-items-start">
                            <i class="bx bx-envelope fs-5 me-3 mt-1"></i>
                            <div>
                                <strong class="d-block mb-2 text-white">Correo Electrónico</strong>
                                <a href="mailto:contacto@educarparalavida.org.bo" class="footer-link"
                                    aria-label="Enviar correo a contacto arroba educar para la vida punto org punto bo">
                                    contacto@educarparalavida.org.bo
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Enlaces -->
                <nav class="col-lg-4 col-md-6 footer-links" aria-label="Enlaces asociados">
                    <h4 class="footer-title mb-4">Links Asociados</h4>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <a href="https://educarparalavida.org.bo/web/Inicio.html" target="_blank" rel="noopener"
                                class="footer-nav-link" aria-label="Abrir Inicio en una nueva pestaña">
                                <i class="bx bx-chevron-right"></i>
                                <span>Inicio</span>
                            </a>
                        </li>
                        <li class="mb-3">
                            <a href="https://educarparalavida.org.bo/web/Quienes-somos.html" target="_blank"
                                rel="noopener" class="footer-nav-link"
                                aria-label="Abrir Quiénes Somos en una nueva pestaña">
                                <i class="bx bx-chevron-right"></i>
                                <span>Quiénes Somos</span>
                            </a>
                        </li>
                        <li class="mb-3">
                            <a href="https://educarparalavida.org.bo/web/Proyectos-y-servicios.html" target="_blank"
                                rel="noopener" class="footer-nav-link"
                                aria-label="Abrir Servicios en una nueva pestaña">
                                <i class="bx bx-chevron-right"></i>
                                <span>Servicios</span>
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- Redes Sociales -->
                <section class="col-lg-4 col-md-6 footer-social" aria-label="Redes sociales">
                    <h4 class="footer-title mb-4">Síguenos</h4>
                    <p class="footer-description mb-4">
                        Conéctate con nosotros en redes sociales
                    </p>
                    <div class="social-links">
                        <a href="https://x.com/FUNDVIDA2" class="social-btn social-twitter" target="_blank"
                            rel="noopener" aria-label="Abrir perfil en X de Fundación">
                            <i class="bi bi-twitter-x" aria-hidden="true"></i>
                        </a>
                        <a href="https://www.facebook.com/profile.php?id=100063510101095"
                            class="social-btn social-facebook" target="_blank" rel="noopener"
                            aria-label="Abrir página en Facebook">
                            <i class="bi bi-facebook" aria-hidden="true"></i>
                        </a>
                        <a href="https://www.instagram.com/fundeducarparalavida/" class="social-btn social-instagram"
                            target="_blank" rel="noopener" aria-label="Abrir perfil en Instagram">
                            <i class="bi bi-instagram" aria-hidden="true"></i>
                        </a>
                        <a href="https://wa.me/59172087186" class="social-btn social-whatsapp" target="_blank"
                            rel="noopener" aria-label="Abrir chat de WhatsApp">
                            <i class="bi bi-whatsapp" aria-hidden="true"></i>
                        </a>
                    </div>
                </section>

            </div>
        </div>
    </div>


    <!-- Parte inferior -->
    <div class="footer-bottom py-4">
        <div class="container">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
                <div class="copyright text-center text-md-start">
                    <span>
                        &copy; <span id="copyright-year"></span>
                        <a href="#" class="footer-bottom-link" aria-label="Fundación educar para la vida">
                            Fundación Educar para la Vida
                        </a>. Todos los derechos reservados.
                    </span>
                </div>


            </div>
        </div>
    </div>

    <script>
        document.getElementById('copyright-year').textContent = new Date().getFullYear();
    </script>
</footer>
