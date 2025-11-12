<style>
    .stat-card {
        border-radius: 10px;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.15);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
        font-size: 1.5rem;
    }
</style>

<div class="container py-1">
    <div class="row g-3">

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card shadow-sm p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase mb-1">Cursos</h6>
                        <h3 class="fw-bold">0</h3>
                    </div>
                    <div class="stat-icon bg-danger text-white">
                        <i class="bi bi-bar-chart-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card shadow-sm p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase mb-1">Estudiantes</h6>
                        <h3 class="fw-bold">0</h3>
                    </div>
                    <div class="stat-icon bg-warning text-white">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card shadow-sm p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase mb-1">Docentes</h6>
                        <h3 class="fw-bold">0</h3>
                    </div>
                    <div class="stat-icon bg-primary text-white">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card shadow-sm p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase mb-1">Inscripciones</h6>
                        <h3 class="fw-bold">0</h3>
                    </div>
                    <div class="stat-icon bg-info text-white">
                        <i class="bi bi-clipboard-check-fill"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
