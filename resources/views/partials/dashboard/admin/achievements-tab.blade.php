@php
    $achievements = \App\Models\Achievement::with(['inscritos'])
        ->withCount(['inscritos'])
        ->withTrashed()
        ->get();

    // Calcular métricas
    $totalAchievements = $achievements->count();
    $activeAchievements = $achievements->whereNull('deleted_at')->count();
    $secretAchievements = $achievements->where('is_secret', true)->count();

    // Calcular total de usuarios que han ganado logros
    $totalUsersWithAchievements = 0;
    foreach ($achievements as $ach) {
        $totalUsersWithAchievements += $ach->inscritos_count ?? 0;
    }
@endphp

<style>
    :root {
        --rpt-primary: #145da0;
        --rpt-bg-light: #f8fafc;
        --rpt-border: #e2e8f0;
    }

    .rpt-metric-card {
        background: #fff;
        border-radius: 1.25rem;
        padding: 1.25rem;
        border: 1px solid var(--rpt-border);
        display: flex;
        align-items: center;
        gap: 1.25rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
    }

    .rpt-metric-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.1);
        border-color: var(--rpt-primary);
    }

    .rpt-metric-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        flex-shrink: 0;
    }

    /* Estilos para Achievements */
    :root {
        --ach-primary: #145da0;
        --ach-secret: #f59e0b;
        --ach-bg: #f8fafc;
        --ach-border: #e2e8f0;
        --ach-text: #1e293b;
        --ach-muted: #64748b;
    }

    .ach-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
    }

    .ach-card {
        position: relative;
        background: #fff;
        border-radius: 1.25rem;
        border: 1px solid var(--ach-border);
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
    }

    .ach-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 32px -12px rgba(20, 93, 160, 0.15);
        border-color: var(--ach-primary);
    }

    .ach-card-stripe {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, var(--ach-primary), #2c7be5);
        z-index: 1;
    }

    .ach-card-stripe.secret {
        background: linear-gradient(90deg, var(--ach-secret), #fbbf24);
    }

    .ach-card-body {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .ach-card-head {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .ach-icon-wrap {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 2px solid #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .ach-card-meta {
        flex: 1;
        min-width: 0;
    }

    .ach-card-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--ach-text);
        margin: 0;
        line-height: 1.2;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .ach-card-users {
        font-size: 0.75rem;
        color: var(--ach-muted);
        font-weight: 600;
        display: flex;
        align-items: center;
        margin-top: 0.35rem;
    }

    .ach-card-desc {
        color: var(--ach-muted);
        font-size: 0.9rem;
        margin: 0;
        line-height: 1.5;
    }

    .ach-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding-top: 0.75rem;
        border-top: 1px dashed var(--ach-border);
    }

    .ach-actions {
        display: flex;
        gap: 0.4rem;
    }

    .ach-empty {
        grid-column: 1/-1;
        text-align: center;
        padding: 4rem 1rem;
        color: var(--ach-muted);
    }

    .ach-empty i {
        font-size: 4rem;
        opacity: 0.3;
        margin-bottom: 1rem;
        display: block;
    }

    .ach-card.trashed { opacity: 0.7; filter: grayscale(0.5); border: 2px dashed #cbd5e1; }
    .ach-card.trashed .ach-card-stripe { background: #94a3b8 !important; }
</style>

<div class="ntf-wrap">
    {{-- Header estilo ListaUsuarios --}}
    <div class="tbl-card-hero" style="margin: 0 -1rem 1rem -1rem; border-radius: 0;">
        <div class="tbl-hero-left">
            <div class="tbl-hero-eyebrow">
                <i class="bi bi-trophy-fill"></i> Logros y Reconocimientos
            </div>
            <h2 class="tbl-hero-title">Gestión de Logros</h2>
            <p class="tbl-hero-sub">Gestiona los logros que los usuarios pueden obtener</p>
        </div>

        <div class="tbl-hero-controls">
            <button class="tbl-hero-btn tbl-hero-btn-primary" data-bs-toggle="modal"
                    data-bs-target="#achNewModal">
                <i class="bi bi-plus-lg"></i> Nuevo Logro
            </button>

            <div class="tbl-hero-search">
                <i class="bi bi-search tbl-hero-search-icon"></i>
                <input type="text" class="tbl-hero-search-input" id="achSearch"
                       placeholder="Buscar logro..."
                       autocomplete="off">
            </div>
        </div>
    </div>

    {{-- Metric Cards de Resumen --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6 col-6">
            <div class="rpt-metric-card shadow-sm">
                <div class="rpt-metric-icon bg-primary-subtle text-primary">
                    <i class="bi bi-trophy"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="small text-muted fw-medium">Total Logros</div>
                    <div class="fw-bold fs-5 text-dark">{{ $totalAchievements }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-6">
            <div class="rpt-metric-card shadow-sm">
                <div class="rpt-metric-icon bg-success-subtle text-success">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="small text-muted fw-medium">Activos</div>
                    <div class="fw-bold fs-5 text-dark">{{ $activeAchievements }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="rpt-metric-card shadow-sm">
                <div class="rpt-metric-icon bg-warning-subtle text-warning">
                    <i class="bi bi-lock"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="small text-muted fw-medium">Secretos</div>
                    <div class="fw-bold fs-5 text-dark">{{ $secretAchievements }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="rpt-metric-card shadow-sm">
                <div class="rpt-metric-icon bg-info-subtle text-info">
                    <i class="bi bi-people"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="small text-muted fw-medium">Usuarios con Logros</div>
                    <div class="fw-bold fs-5 text-dark">{{ $totalUsersWithAchievements }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tarjetas de Logros --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="ach-grid" id="achGrid">
                @forelse ($achievements as $achievement)
                    @php
                        $totalUsers = $achievement->inscritos_count ?? 0;
                        $req         = $achievement->requirement_value ?? 1;
                        $progress    = $req > 1 ? min(100, round(($totalUsers / $req) * 100)) : null;
                        $isSecret    = $achievement->is_secret;
                        $isTrashed   = $achievement->trashed();
                    @endphp

                    <div class="ach-card achievement-item {{ $isTrashed ? 'trashed' : '' }}"
                         data-title="{{ strtolower($achievement->title) }}"
                         data-description="{{ strtolower($achievement->description) }}"
                         data-type="{{ strtolower($achievement->type) }}"
                         data-category="{{ strtolower($achievement->category) }}">

                        <div class="ach-card-stripe {{ $isSecret ? 'secret' : '' }}"></div>

                        <div class="ach-card-body">
                            <div class="ach-card-head">
                                <div class="ach-icon-wrap">
                                    <span style="font-size: 1.5rem;">{{ $achievement->icon }}</span>
                                </div>
                                <div class="ach-card-meta">
                                    <h5 class="ach-card-title" title="{{ $achievement->title }}">
                                        {{ $achievement->title }}
                                    </h5>
                                    <span class="ach-card-users">
                                        <i class="bi bi-people-fill" style="font-size:.7rem;margin-right:.25rem;"></i>
                                        {{ $totalUsers }} {{ $totalUsers === 1 ? 'usuario' : 'usuarios' }}
                                    </span>
                                </div>
                            </div>

                            <p class="ach-card-desc">{{ Str::limit($achievement->description, 80) }}</p>

                            <div class="ach-card-footer">
                                <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
                                    <span class="badge bg-light text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-bold">
                                        <i class="bi bi-lightning-charge-fill"></i>
                                        {{ $achievement->xp_reward }} XP
                                    </span>
                                    @if ($isTrashed)
                                        <span class="badge bg-light text-muted border px-3 py-2 rounded-pill fw-bold">
                                            <i class="bi bi-eye-slash-fill"></i> Inactivo
                                        </span>
                                    @elseif ($isSecret)
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2 rounded-pill fw-bold">
                                            <i class="bi bi-lock-fill"></i> Secreto
                                        </span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill fw-bold">
                                            <i class="bi bi-globe2"></i> Público
                                        </span>
                                    @endif
                                </div>

                                <div class="ach-actions">
                                    <button class="btn-action-modern btn-info"
                                            data-bs-toggle="modal"
                                            data-bs-target="#achViewModal-{{ $achievement->id }}"
                                            title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn-action-modern btn-info"
                                            data-bs-toggle="modal"
                                            data-bs-target="#achEditModal-{{ $achievement->id }}"
                                            title="Editar">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    @if ($isTrashed)
                                        <button class="btn-action-modern btn-success"
                                                onclick="confirmRestoreAch({{ $achievement->id }})"
                                                title="Activar logro">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>
                                        <form id="restore-ach-{{ $achievement->id }}" action="{{ route('restore.logro', $achievement->id) }}" method="POST" style="display:none;">
                                            @csrf
                                        </form>
                                    @else
                                        <button class="btn-action-modern btn-delete"
                                                onclick="confirmDeleteAch({{ $achievement->id }})"
                                                title="Desactivar logro">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        <form id="delete-ach-{{ $achievement->id }}" action="{{ route('delete.logro', $achievement->id) }}" method="POST" style="display:none;">
                                            @csrf
                                        </form>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>

                @empty
                    <div class="ach-empty" id="achEmpty">
                        <i class="bi bi-trophy"></i>
                        <p>No hay logros creados todavía.</p>
                        <button class="tbl-hero-btn tbl-hero-btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#achNewModal">
                            Crear primer logro
                        </button>
                    </div>
                @endforelse

                <div class="ach-empty d-none" id="achNoResults">
                    <i class="bi bi-search"></i>
                    <p>Sin resultados para tu búsqueda.</p>
                    <button class="btn btn-outline-primary rounded-pill" onclick="achClearSearch()">
                        <i class="bi bi-x-lg"></i> Limpiar búsqueda
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Nuevo Logro --}}
<div class="modal fade" id="achNewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-light border-bottom-0 p-4">
                <h5 class="modal-title fw-bold text-primary">
                    <i class="bi bi-trophy me-2"></i>Nuevo Logro
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="achNewForm" method="POST" action="{{ route('logro.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Nombre del logro</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-fonts text-primary"></i></span>
                            <input type="text" name="title" class="form-control bg-light" placeholder="Ej. Maestro del quiz" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Descripción</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-text-paragraph text-primary"></i></span>
                            <textarea name="description" class="form-control bg-light" rows="3" placeholder="Describe qué debe hacer el usuario…" required></textarea>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small text-uppercase">XP a otorgar</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-star text-primary"></i></span>
                                <input type="number" name="xp_reward" class="form-control bg-light" min="0" max="100" value="50" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small text-uppercase">Valor requerido</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-123 text-primary"></i></span>
                                <input type="number" name="requirement_value" class="form-control bg-light" min="1" value="1" required>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small text-uppercase">Tipo</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-tag text-primary"></i></span>
                                <select name="type" class="form-select bg-light" required>
                                    <optgroup label="Académicos">
                                        <option value="QUIZ_MASTER">Maestro de Cuestionarios</option>
                                        <option value="RESOURCE_EXPLORER">Explorador de Recursos</option>
                                    </optgroup>
                                    <optgroup label="Cursos">
                                        <option value="COURSE_ENROLL">Inscripción a Curso</option>
                                        <option value="COURSE_COLLECTOR">Coleccionista de Cursos</option>
                                        <option value="COURSE_FINISHER">Finisher de Cursos</option>
                                        <option value="MODULE_MASTER">Dominador de Temas</option>
                                    </optgroup>
                                    <optgroup label="Eventos">
                                        <option value="CONGRESS_ENROLL">Inscripción a Congreso</option>
                                        <option value="CONGRESS_PARTICIPANT">Asistente de Congresos</option>
                                    </optgroup>
                                    <optgroup label="Social">
                                        <option value="FORUM_CONTRIBUTOR">Contribuidor del Foro</option>
                                    </optgroup>
                                    <optgroup label="Engagement">
                                        <option value="EARLY_BIRD">Madrugador</option>
                                        <option value="STREAK_MASTER">Racha de Constancia</option>
                                    </optgroup>
                                    <optgroup label="Especiales">
                                        <option value="NIGHT_OWL">Explorador Nocturno</option>
                                        <option value="SPEED_RUNNER">Velocista</option>
                                        <option value="DAILY_ACTIVITIES">Maratón de Estudio</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small text-uppercase">Categoría</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-folder text-primary"></i></span>
                                <select name="category" class="form-select bg-light" required>
                                    <option value="academic">Académico</option>
                                    <option value="courses">Cursos</option>
                                    <option value="events">Eventos</option>
                                    <option value="social">Social</option>
                                    <option value="engagement">Engagement</option>
                                    <option value="special">Especial</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Ícono</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-trophy text-primary"></i></span>
                            <select name="icon" class="form-select bg-light" required>
                                <option value="🏆">🏆 Trofeo</option>
                                <option value="🏅">🏅 Medalla</option>
                                <option value="🎯">🎯 Diana</option>
                                <option value="💭">💭 Diálogo</option>
                                <option value="📚">📚 Libros</option>
                                <option value="🌅">🌅 Amanecer</option>
                                <option value="🔥">🔥 Fuego</option>
                                <option value="🎓">🎓 Graduación</option>
                                <option value="📘">📘 Libro</option>
                                <option value="✅">✅ Check</option>
                                <option value="📄">📄 Documento</option>
                                <option value="📜">📜 Pergamino</option>
                                <option value="🌙">🌙 Luna</option>
                                <option value="⚡">⚡ Rayo</option>
                                <option value="🏃">🏃 Corredor</option>
                                <option value="👑">👑 Corona</option>
                                <option value="⭐">⭐ Estrella</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_secret" id="achIsSecretNew" value="1">
                        <label class="form-check-label fw-bold" for="achIsSecretNew">Logro secreto</label>
                    </div>
                    <div class="modal-footer px-0 pb-0 pt-3 border-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="tbl-hero-btn tbl-hero-btn-primary px-4">
                            <i class="bi bi-check-lg me-2"></i>Guardar Logro
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@foreach ($achievements as $achievement)
    {{-- Modal Ver Detalles --}}
    <div class="modal fade" id="achViewModal-{{ $achievement->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-light border-bottom-0 p-4">
                    <h5 class="modal-title fw-bold text-primary">
                        <i class="bi bi-eye-fill me-2"></i>Detalles del Logro
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <div class="mb-3">
                        <span style="font-size: 4rem;">{{ $achievement->icon }}</span>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">{{ $achievement->title }}</h4>
                    <p class="text-muted mb-4">{{ $achievement->description }}</p>

                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="p-3 bg-light rounded-4 border">
                                <div class="small text-muted mb-1">Recompensa</div>
                                <div class="fw-bold text-primary fs-5">{{ $achievement->xp_reward }} XP</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded-4 border">
                                <div class="small text-muted mb-1">Requisito</div>
                                <div class="fw-bold text-dark fs-5">{{ $achievement->requirement_value }} {{ Str::plural('unidad', $achievement->requirement_value) }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center gap-3">
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 rounded-pill fw-bold">
                            <i class="bi bi-tag-fill me-1"></i> {{ ucfirst($achievement->type) }}
                        </span>
                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-2 rounded-pill fw-bold">
                            <i class="bi bi-folder-fill me-1"></i> {{ ucfirst($achievement->category) }}
                        </span>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3 border-0 justify-content-center">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Editar Logro --}}
    <div class="modal fade" id="achEditModal-{{ $achievement->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-light border-bottom-0 p-4">
                    <h5 class="modal-title fw-bold text-primary">
                        <i class="bi bi-pencil-square me-2"></i>Editar Logro
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="achEditForm-{{ $achievement->id }}" method="POST" action="{{ route('update.logro', $achievement->id) }}">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small text-uppercase">Nombre del logro</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-fonts text-primary"></i></span>
                                <input type="text" name="title" class="form-control bg-light" value="{{ $achievement->title }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small text-uppercase">Descripción</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-text-paragraph text-primary"></i></span>
                                <textarea name="description" class="form-control bg-light" rows="3" required>{{ $achievement->description }}</textarea>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">XP a otorgar</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-star text-primary"></i></span>
                                    <input type="number" name="xp_reward" class="form-control bg-light" min="0" max="100" value="{{ $achievement->xp_reward }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Valor requerido</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-123 text-primary"></i></span>
                                    <input type="number" name="requirement_value" class="form-control bg-light" min="1" value="{{ $achievement->requirement_value }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Tipo</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-tag text-primary"></i></span>
                                    <select name="type" class="form-select bg-light" required>
                                        <optgroup label="Académicos">
                                            <option value="QUIZ_MASTER" {{ $achievement->type == 'QUIZ_MASTER' ? 'selected' : '' }}>Maestro de Cuestionarios</option>
                                            <option value="RESOURCE_EXPLORER" {{ $achievement->type == 'RESOURCE_EXPLORER' ? 'selected' : '' }}>Explorador de Recursos</option>
                                        </optgroup>
                                        <optgroup label="Cursos">
                                            <option value="COURSE_ENROLL" {{ $achievement->type == 'COURSE_ENROLL' ? 'selected' : '' }}>Inscripción a Curso</option>
                                            <option value="COURSE_COLLECTOR" {{ $achievement->type == 'COURSE_COLLECTOR' ? 'selected' : '' }}>Coleccionista de Cursos</option>
                                            <option value="COURSE_FINISHER" {{ $achievement->type == 'COURSE_FINISHER' ? 'selected' : '' }}>Finisher de Cursos</option>
                                            <option value="MODULE_MASTER" {{ $achievement->type == 'MODULE_MASTER' ? 'selected' : '' }}>Dominador de Temas</option>
                                        </optgroup>
                                        <optgroup label="Eventos">
                                            <option value="CONGRESS_ENROLL" {{ $achievement->type == 'CONGRESS_ENROLL' ? 'selected' : '' }}>Inscripción a Congreso</option>
                                            <option value="CONGRESS_PARTICIPANT" {{ $achievement->type == 'CONGRESS_PARTICIPANT' ? 'selected' : '' }}>Asistente de Congresos</option>
                                        </optgroup>
                                        <optgroup label="Social">
                                            <option value="FORUM_CONTRIBUTOR" {{ $achievement->type == 'FORUM_CONTRIBUTOR' ? 'selected' : '' }}>Contribuidor del Foro</option>
                                        </optgroup>
                                        <optgroup label="Engagement">
                                            <option value="EARLY_BIRD" {{ $achievement->type == 'EARLY_BIRD' ? 'selected' : '' }}>Madrugador</option>
                                            <option value="STREAK_MASTER" {{ $achievement->type == 'STREAK_MASTER' ? 'selected' : '' }}>Racha de Constancia</option>
                                        </optgroup>
                                        <optgroup label="Especiales">
                                            <option value="NIGHT_OWL" {{ $achievement->type == 'NIGHT_OWL' ? 'selected' : '' }}>Explorador Nocturno</option>
                                            <option value="SPEED_RUNNER" {{ $achievement->type == 'SPEED_RUNNER' ? 'selected' : '' }}>Velocista</option>
                                            <option value="DAILY_ACTIVITIES" {{ $achievement->type == 'DAILY_ACTIVITIES' ? 'selected' : '' }}>Maratón de Estudio</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Categoría</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-folder text-primary"></i></span>
                                    <select name="category" class="form-select bg-light" required>
                                        <option value="academic" {{ $achievement->category == 'academic' ? 'selected' : '' }}>Académico</option>
                                        <option value="courses" {{ $achievement->category == 'courses' ? 'selected' : '' }}>Cursos</option>
                                        <option value="events" {{ $achievement->category == 'events' ? 'selected' : '' }}>Eventos</option>
                                        <option value="social" {{ $achievement->category == 'social' ? 'selected' : '' }}>Social</option>
                                        <option value="engagement" {{ $achievement->category == 'engagement' ? 'selected' : '' }}>Engagement</option>
                                        <option value="special" {{ $achievement->category == 'special' ? 'selected' : '' }}>Especial</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small text-uppercase">Ícono</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-trophy text-primary"></i></span>
                                <select name="icon" class="form-select bg-light" required>
                                    @php
                                        $iconOptions = [
                                            '🏆' => 'Trofeo', '🏅' => 'Medalla', '🎯' => 'Diana',
                                            '💭' => 'Diálogo', '📚' => 'Libros', '🌅' => 'Amanecer',
                                            '🔥' => 'Fuego', '🎓' => 'Graduación', '📘' => 'Libro',
                                            '✅' => 'Check', '📄' => 'Documento', '📜' => 'Pergamino',
                                            '🌙' => 'Luna', '⚡' => 'Rayo', '🏃' => 'Corredor',
                                            '👑' => 'Corona', '⭐' => 'Estrella',
                                        ];
                                    @endphp
                                    @foreach($iconOptions as $emoji => $label)
                                        <option value="{{ $emoji }}" {{ $achievement->icon == $emoji ? 'selected' : '' }}>{{ $emoji }} {{ $label }}</option>
                                    @endforeach
                                    @if(!array_key_exists($achievement->icon, $iconOptions))
                                        <option value="{{ $achievement->icon }}" selected>{{ $achievement->icon }} (personalizado)</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_secret" id="achIsSecretEdit-{{ $achievement->id }}" value="1" {{ $achievement->is_secret ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="achIsSecretEdit-{{ $achievement->id }}">Logro secreto</label>
                        </div>
                        <div class="modal-footer px-0 pb-0 pt-3 border-0">
                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="tbl-hero-btn tbl-hero-btn-primary px-4">
                                <i class="bi bi-check-lg me-2"></i>Actualizar Logro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
    'use strict';

    let achItems = [];

    function init() {
        achItems = Array.from(document.querySelectorAll('.achievement-item'));
        setupSearch();
        setupModals();
    }

    function setupModals() {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            if (modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }
        });
    }

    function setupSearch() {
        const input = document.getElementById('achSearch');
        if (!input) return;
        input.addEventListener('input', filterAchievements);
    }

    function filterAchievements() {
        const term = document.getElementById('achSearch').value.trim().toLowerCase();
        if (!term) { showAll(); return; }
        let visible = 0;
        achItems.forEach(el => {
            const match = ['title','description','type','category'].some(attr => (el.dataset[attr] || '').includes(term));
            el.style.display = match ? '' : 'none';
            if (match) visible++;
        });
        document.getElementById('achNoResults')?.classList.toggle('d-none', visible > 0);
    }

    function showAll() {
        achItems.forEach(el => (el.style.display = ''));
        document.getElementById('achNoResults')?.classList.add('d-none');
    }

    window.achClearSearch = function () {
        const input = document.getElementById('achSearch');
        if (input) { input.value = ''; filterAchievements(); input.focus(); }
    };

    window.confirmDeleteAch = function(id) {
        Swal.fire({
            title: '¿Desactivar logro?',
            text: "El logro ya no podrá ser obtenido por nuevos usuarios.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Sí, desactivar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Procesando...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                document.getElementById('delete-ach-' + id).submit();
            }
        });
    };

    window.confirmRestoreAch = function(id) {
        Swal.fire({
            title: '¿Activar logro?',
            text: "El logro volverá a estar disponible para todos los usuarios.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Sí, activar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Procesando...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                document.getElementById('restore-ach-' + id).submit();
            }
        });
    };

    document.readyState === 'loading' ? document.addEventListener('DOMContentLoaded', init) : init();
})();
</script>
