<!DOCTYPE html>
<html lang="es" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', 'Iniciar sesión')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/img/Acceder.png') }}">

    <!-- CSS Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets/css/font-awesome.css') }}" rel="stylesheet">

    <style>
        :root {
            --primary-color: #075092;
            --primary-light: rgba(20, 93, 160, 0.3);
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: linear-gradient(var(--primary-light), var(--primary-light)),
                        url('{{ asset('assets/img/bg2.png') }}') no-repeat center center;
            background-size: cover;
        }

        .login-card {
            max-width: 400px;
            margin: auto;
            padding: 2rem;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .diagonal-header {
            position: relative;
            background: white;
            overflow: hidden;
        }

        .diagonal-header::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 40%;
            height: 100%;
            background: var(--primary-color);
            transform: skewX(-15deg) translateX(10%);
            z-index: 0;
        }

        .header-content {
            position: relative;
            z-index: 1;
        }

        .form-control, .btn {
            border-radius: 2rem;
        }

        main {
            flex: 1;
            padding: 3rem 0;
        }

        footer {
            background-color: #f8f9fa;
            padding: 2rem 0;
            margin-top: auto;
        }

        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            background-color: #e9ecef;
            color: var(--primary-color);
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-3px);
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="diagonal-header shadow-sm">
        <div class="container header-content py-3">
            <div class="row align-items-center">
                <div class="col-auto">
                    <a href="{{ route('home') }}" class="d-inline-block">
                        <img src="{{ asset('assets/img/Acceder.png') }}" alt="Logo" height="35">
                    </a>
                </div>
                <div class="col text-end">
                    <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-4 me-2">Ir al Inicio</a>

                    @yield('btn-bar')

                </div>
                <div class="col-auto">
                    <img src="{{ asset('assets/img/logof.png') }}" alt="Logo Fundación" height="55">
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3">Aprendo Hoy</h5>
                    <address class="mb-0">
                        <p class="mb-1">Bolivia</p>
                        <p class="mb-1">+591 72087186</p>
                        <p class="mb-0">contacto@educarparalavida.org.bo</p>
                    </address>
                </div>
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3">Enlaces</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="https://educarparalavida.org.bo/web/Inicio.html" class="text-decoration-none text-dark">Inicio</a></li>
                        <li class="mb-2"><a href="https://educarparalavida.org.bo/web/Quienes-somos.html" class="text-decoration-none text-dark">Quiénes somos</a></li>
                        <li><a href="https://educarparalavida.org.bo/web/Proyectos-y-servicios.html" class="text-decoration-none text-dark">Servicios</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3">Síguenos</h5>
                    <div class="social-links">
                        <a href="https://x.com/FUNDVIDA2" class="me-2"><i class="bi bi-twitter"></i></a>
                        <a href="https://www.facebook.com/profile.php?id=100063510101095" class="me-2"><i class="bi bi-facebook"></i></a>
                        <a href="https://www.instagram.com/fundeducarparalavida/" class="me-2"><i class="bi bi-instagram"></i></a>
                        <a href="https://api.whatsapp.com/send?phone=59172087186"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <p class="text-center mb-0">
                &copy; <script>document.write(new Date().getFullYear())</script> Fundación Educar para la Vida
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Password Toggle Function
        function togglePasswordVisibility(button) {
            const input = button.previousElementSibling;
            const icon = button.querySelector('i');

            input.type = input.type === 'password' ? 'text' : 'password';
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        }

        // SweetAlert Notifications
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#075092'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                confirmButtonColor: '#dc3545'
            });
        @endif
    </script>
</body>
</html>
