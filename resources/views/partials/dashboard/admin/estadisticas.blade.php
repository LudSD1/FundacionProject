
<div class="container py-2">
    <div class="row g-3">

        {{-- Cursos --}}
        <div class="col-xl-3 col-md-6">
            <div class="st-card st-card--red">
                <div class="st-card-body">
                    <div>
                        <div class="st-label">Cursos</div>
                        <div class="st-num">{{ $totalCursos ?? 0 }}</div>
                    </div>
                    <div class="st-icon st-icon--red">
                        <i class="bi bi-bar-chart-fill"></i>
                    </div>
                </div>
                <div class="st-bar st-bar--red"></div>
            </div>
        </div>

        {{-- Estudiantes --}}
        <div class="col-xl-3 col-md-6">
            <div class="st-card st-card--orange">
                <div class="st-card-body">
                    <div>
                        <div class="st-label">Estudiantes</div>
                        <div class="st-num">{{ $totalEstudiantes ?? 0 }}</div>
                    </div>
                    <div class="st-icon st-icon--orange">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
                <div class="st-bar st-bar--orange"></div>
            </div>
        </div>

        {{-- Docentes --}}
        <div class="col-xl-3 col-md-6">
            <div class="st-card st-card--blue">
                <div class="st-card-body">
                    <div>
                        <div class="st-label">Docentes</div>
                        <div class="st-num">{{ $totalDocentes ?? 0 }}</div>
                    </div>
                    <div class="st-icon st-icon--blue">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                </div>
                <div class="st-bar st-bar--blue"></div>
            </div>
        </div>

        {{-- Inscripciones --}}
        <div class="col-xl-3 col-md-6">
            <div class="st-card st-card--green">
                <div class="st-card-body">
                    <div>
                        <div class="st-label">Inscripciones</div>
                        <div class="st-num">{{ $totalInscripciones ?? 0 }}</div>
                    </div>
                    <div class="st-icon st-icon--green">
                        <i class="bi bi-clipboard-check-fill"></i>
                    </div>
                </div>
                <div class="st-bar st-bar--green"></div>
            </div>
        </div>

    </div>
</div>


