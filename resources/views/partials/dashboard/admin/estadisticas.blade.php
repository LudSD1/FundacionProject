
<div class="container py-2">
    <div class="row g-3">

        {{-- Cursos --}}
        <div class="col-xl-3 col-md-6">
            <div class="st-card st-card--red">
                <div class="st-card-body">
                    <div>
                        <div class="st-label">Total Cursos</div>
                        <div class="st-num">{{ $totalCursos ?? 0 }}</div>
                        <div class="small text-muted mt-1">
                            <span class="text-success fw-bold">{{ $cursosActivos ?? 0 }}</span> activos
                        </div>
                    </div>
                    <div class="st-icon st-icon--red">
                        <i class="bi bi-journal-bookmark-fill"></i>
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
                        <div class="small text-muted mt-1">Usuarios registrados</div>
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
                        <div class="small text-muted mt-1">Profesores activos</div>
                    </div>
                    <div class="st-icon st-icon--blue">
                        <i class="bi bi-person-badge-fill"></i>
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
                        <div class="small text-muted mt-1">En todos los cursos</div>
                    </div>
                    <div class="st-icon st-icon--green">
                        <i class="bi bi-clipboard-check-fill"></i>
                    </div>
                </div>
                <div class="st-bar st-bar--green"></div>
            </div>
        </div>

        {{-- Nuevas Estadísticas --}}
        
        {{-- Actividades --}}
        <div class="col-xl-3 col-md-6">
            <div class="st-card" style="border-left: 4px solid #6f42c1;">
                <div class="st-card-body">
                    <div>
                        <div class="st-label">Actividades</div>
                        <div class="st-num">{{ $totalActividades ?? 0 }}</div>
                        <div class="small text-muted mt-1">Tareas y cuestionarios</div>
                    </div>
                    <div class="st-icon" style="background: rgba(111, 66, 193, 0.1); color: #6f42c1;">
                        <i class="bi bi-card-checklist"></i>
                    </div>
                </div>
                <div class="st-bar" style="background: #6f42c1;"></div>
            </div>
        </div>

        {{-- Foros --}}
        <div class="col-xl-3 col-md-6">
            <div class="st-card" style="border-left: 4px solid #0dcaf0;">
                <div class="st-card-body">
                    <div>
                        <div class="st-label">Foros</div>
                        <div class="st-num">{{ $totalForos ?? 0 }}</div>
                        <div class="small text-muted mt-1">Discusiones abiertas</div>
                    </div>
                    <div class="st-icon" style="background: rgba(13, 202, 240, 0.1); color: #0dcaf0;">
                        <i class="bi bi-chat-dots-fill"></i>
                    </div>
                </div>
                <div class="st-bar" style="background: #0dcaf0;"></div>
            </div>
        </div>

        {{-- Certificados --}}
        <div class="col-xl-3 col-md-6">
            <div class="st-card" style="border-left: 4px solid #ffc107;">
                <div class="st-card-body">
                    <div>
                        <div class="st-label">Certificados</div>
                        <div class="st-num">{{ $totalCertificados ?? 0 }}</div>
                        <div class="small text-muted mt-1">Emitidos a estudiantes</div>
                    </div>
                    <div class="st-icon" style="background: rgba(255, 193, 7, 0.1); color: #ffc107;">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                </div>
                <div class="st-bar" style="background: #ffc107;"></div>
            </div>
        </div>

        {{-- Categorías --}}
        <div class="col-xl-3 col-md-6">
            <div class="st-card" style="border-left: 4px solid #20c997;">
                <div class="st-card-body">
                    <div>
                        <div class="st-label">Categorías</div>
                        <div class="st-num">{{ $totalCategorias ?? 0 }}</div>
                        <div class="small text-muted mt-1">Áreas de estudio</div>
                    </div>
                    <div class="st-icon" style="background: rgba(32, 201, 151, 0.1); color: #20c997;">
                        <i class="bi bi-tags-fill"></i>
                    </div>
                </div>
                <div class="st-bar" style="background: #20c997;"></div>
            </div>
        </div>

    </div>
</div>


