  <!-- Header -->
<div class="header">
    <div class="container-fluid">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-main rounded">
            <div class="container d-flex justify-content-between align-items-center">
                <!-- Left Logo -->
                <a class="navbar-brand" href="{{ route('Inicio') }}">
                    <img src="{{ asset('assets/img/logof.png') }}" alt="Logo" class="img-fluid">
                </a>

                <div class="d-flex align-items-center">
                    <!-- Right Logo -->
                    <a class="logo-acceder" href="{{ route('Inicio') }}">
                        <img src="{{ asset('assets/img/Acceder.png') }}" alt="Acceder" class="img-fluid">
                    </a>
                </div>
            </div>
        </nav>
        @yield('contentup')
    </div>
</div>
