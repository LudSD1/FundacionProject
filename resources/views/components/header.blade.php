
<header id="header" class=" header-clean">
    <div class="container-fluid px-4">
        <div class="row align-items-center py-3">
            <!-- Logo APRENDO HOY (Izquierda) -->
            <div class="col-auto">
                <a href="{{ route('home') }}" class="logo-aprendo">
                    APRENDO <span class="logo-h-special">H</span>OY
                </a>
            </div>

            <!-- Buscador (Centro expandible) -->
            <div class="col search-col">
                <form action="" method="GET" class="search-form-main">
                    <div class="input-group search-group">
                        <input
                            type="text"
                            name="q"
                            placeholder="Buscar cursos, eventos..."
                            class="form-control search-input-main"
                            id="mainSearchInput">
                        <button type="submit" class="btn btn-search-main">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Logo Fundación (Derecha) -->
            <div class="col-auto logo-fundacion-wrapper" id="logoFundacion">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('assets/img/logof.png') }}"
                         alt="Logo Fundación"
                         class="logo-fundacion-img">
                </a>
            </div>
        </div>
    </div>
</header>


<script>
    // JavaScript para minimizar el logo de fundación
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('mainSearchInput');
    const logoFundacion = document.getElementById('logoFundacion');

    if (searchInput && logoFundacion) {
        // Cuando se enfoca o escribe en el buscador
        searchInput.addEventListener('focus', function() {
            logoFundacion.classList.add('minimized');
        });

        searchInput.addEventListener('input', function() {
            if (this.value.length > 0) {
                logoFundacion.classList.add('minimized');
            }
        });

        // Cuando se pierde el foco y está vacío, mostrar de nuevo
        searchInput.addEventListener('blur', function() {
            if (this.value.length === 0) {
                setTimeout(() => {
                    logoFundacion.classList.remove('minimized');
                }, 200);
            }
        });
    }
});
</script>
