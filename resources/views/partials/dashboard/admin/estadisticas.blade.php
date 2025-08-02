<div class="container py-1">
    <!-- Tarjetas de estadísticas -->
    <div class="row g-2">
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="text-muted text-uppercase mb-1">Cursos</h5>
                        <span class="h2 fw-bold">{{ count($cursos) }}</span>
                    </div>
                    <div class="icon bg-danger text-white rounded-circle p-3">
                        <i class="bi bi-bar-chart-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="text-muted text-uppercase mb-1">Estudiantes</h5>
                        <span class="h2 fw-bold">{{ count($estudiantes) }}</span>
                    </div>
                    <div class="icon bg-warning text-white rounded-circle p-3">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="text-muted text-uppercase mb-1">Docentes</h5>
                        <span class="h2 fw-bold">{{ count($docentes) }}</span>
                    </div>
                    <div class="icon bg-primary text-white rounded-circle p-3">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="text-muted text-uppercase mb-1">Inscripciones</h5>
                        <span class="h2 fw-bold">{{ count($inscritos) }}</span>
                    </div>
                    <div class="icon bg-info text-white rounded-circle p-3">
                        <i class="bi bi-clipboard-check-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 