<div class="tab-pane fade" id="tab-foros" role="tabpanel" aria-labelledby="foros-tab">

    <div class="tbl-card">
        {{-- Hero Section --}}
        <div class="tbl-card-hero">
            <div class="tbl-card-hero-content">
                <h1 class="tbl-card-hero-title text-white">
                    <i class="bi bi-chat-dots-fill me-2"></i>Foros de Discusión
                </h1>
                <p class="tbl-card-hero-subtitle text-white">
                    Comunidad de aprendizaje e intercambio de ideas para este curso.
                </p>
            </div>

            <div class="tbl-card-hero-actions">
                {{-- Filtro de Tiempo --}}
                <div class="tbl-hero-select-wrap">
                    <i class="bi bi-calendar-range tbl-hero-select-icon"></i>
                    <select class="tbl-hero-select" id="timeFilter">
                        <option value="all">Todo el tiempo</option>
                        <option value="today">Hoy</option>
                        <option value="week">Esta semana</option>
                        <option value="month">Este mes</option>
                        <option value="year">Este año</option>
                    </select>
                </div>

                {{-- Buscador --}}
                <div class="tbl-hero-search">
                    <i class="bi bi-search tbl-hero-search-icon"></i>
                    <input type="text" class="tbl-hero-search-input" id="searchForums"
                        placeholder="Buscar en foros..." autocomplete="off">
                </div>

                @if (auth()->user()->hasRole('Docente') || auth()->user()->hasRole('Administrador'))
                    <div class="d-flex gap-2 mt-2 mt-md-0">
                        <button class="tbl-hero-btn tbl-hero-btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearForo">
                            <i class="bi bi-plus-circle-fill"></i>
                            <span>Nuevo Foro</span>
                        </button>
                        <a href="{{ route('forosE', encrypt($cursos->id)) }}" class="tbl-hero-btn tbl-hero-btn-danger">
                            <i class="bi bi-trash-fill"></i>
                            <span>Papelera</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Stats Bar --}}
        <div class="tbl-filter-bar bg-light border-bottom">
            <div class="tbl-filter-bar-left d-flex gap-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-chat-left-text text-primary"></i>
                    <span><strong>{{ $foros->count() }}</strong> Foros</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-chat-dots text-success"></i>
                    <span><strong>{{ $foros->sum(fn($f) => $f->foromensaje->count()) }}</strong> Mensajes</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-eye text-info"></i>
                    <span><strong>{{ $foros->sum('vistas_count') ?? 0 }}</strong> Visitas</span>
                </div>
            </div>
        </div>

        <div class="p-4">
            @if ($foros->count() > 0)
                <div class="row g-4" id="forumsGrid">
                    @foreach ($foros as $foro)
                        <div class=" forum-card-item"
                             data-forum-name="{{ strtolower($foro->nombreForo) }}"
                             data-messages="{{ $foro->foromensaje->count() }}"
                             data-created="{{ $foro->created_at->toISOString() }}">

                            <div class="modern-foro-card">
                                <div class="foro-card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-4">
                                        <div class="foro-icon-box">
                                            <i class="bi bi-chat-right-quote-fill"></i>
                                        </div>
                                        <div class="foro-badges">
                                            @if ($foro->foromensaje->count() > 10)
                                                <span class="badge bg-warning text-dark rounded-pill shadow-sm">
                                                    <i class="bi bi-fire me-1"></i>Popular
                                                </span>
                                            @elseif($foro->created_at->diffInDays(now()) <= 2)
                                                <span class="badge bg-success rounded-pill shadow-sm">
                                                    <i class="bi bi-stars me-1"></i>Nuevo
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <h5 class="foro-title mb-2">
                                        <a href="{{ route('foro', encrypt($foro->id)) }}" class="stretched-link text-decoration-none text-dark">
                                            {{ $foro->nombreForo }}
                                        </a>
                                    </h5>

                                    @if ($foro->SubtituloForo)
                                        <p class="foro-subtitle text-muted mb-3">{{ $foro->SubtituloForo }}</p>
                                    @endif

                                    <p class="foro-description text-secondary mb-4">
                                        {{ Str::limit($foro->descripcionForo ?? $foro->contenido, 150) }}
                                    </p>

                                    <div class="foro-meta-grid">
                                        <div class="foro-meta-item">
                                            <i class="bi bi-chat-fill"></i>
                                            <span>{{ $foro->foromensaje->count() }} Mensajes</span>
                                        </div>
                                        <div class="foro-meta-item">
                                            <i class="bi bi-eye-fill"></i>
                                            <span>{{ $foro->vistas_count ?? 0 }} Visitas</span>
                                        </div>
                                        <div class="foro-meta-item">
                                            <i class="bi bi-clock-fill"></i>
                                            <span>{{ $foro->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="foro-card-footer">
                                    <div class="d-flex justify-content-between align-items-center w-100">
                                        <span class="btn-view-foro">
                                            Participar en el foro <i class="bi bi-arrow-right ms-2"></i>
                                        </span>

                                        @if (auth()->user()->hasRole('Docente') || auth()->user()->hasRole('Administrador'))
                                            <div class="foro-admin-actions position-relative" style="z-index: 2;">
                                                <button class="btn-action-modern btn-info me-1" data-bs-toggle="modal"
                                                    data-bs-target="#modalEditarForo-{{ $foro->id }}"
                                                    title="Editar foro">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <form class="d-inline ntf-form-delete" action="{{ route('quitarForo', encrypt($foro->id)) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn-action-modern btn-delete" title="Eliminar foro">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Mensaje de "No se encontraron resultados" para filtros --}}
                <div id="noResultsMsg" class="empty-state-table py-5 d-none">
                    <div class="empty-icon-table">
                        <i class="bi bi-search"></i>
                    </div>
                    <h5 class="empty-title-table">No se encontraron foros</h5>
                    <p class="empty-text-table">Intenta ajustar tu búsqueda o el filtro de tiempo.</p>
                    <button class="tbl-hero-btn tbl-hero-btn-primary mt-3" style="width:auto"
                        onclick="resetForoFilters()">
                        <i class="bi bi-arrow-clockwise"></i>
                        Ver todos los foros
                    </button>
                </div>
            @else
                <div class="empty-state-table py-5">
                    <div class="empty-icon-table">
                        <i class="bi bi-chat-square-dots"></i>
                    </div>
                    <h5 class="empty-title-table">No hay foros de discusión</h5>
                    <p class="empty-text-table">Aún no se han creado temas para discutir en este curso.</p>

                    @if (auth()->user()->id == $cursos->docente_id || auth()->user()->hasRole('Administrador'))
                        <button class="tbl-hero-btn tbl-hero-btn-primary mt-3" style="width:auto"
                            data-bs-toggle="modal" data-bs-target="#modalCrearForo">
                            <i class="bi bi-plus-circle-fill"></i>
                            Crear Primer Foro
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Modern Foro Cards */
    .modern-foro-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #edf2f7;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .modern-foro-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border-color: #cbd5e0;
    }

    .foro-card-body {
        padding: 1.5rem;
        flex-grow: 1;
    }

    .foro-icon-box {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #1a4789, #2b6cb0);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 4px 12px rgba(26, 71, 137, 0.2);
    }

    .foro-title {
        font-weight: 700;
        color: #1a202c;
        line-height: 1.4;
    }

    .foro-subtitle {
        font-size: 0.875rem;
        font-weight: 500;
    }

    .foro-description {
        font-size: 0.9375rem;
        line-height: 1.6;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .foro-meta-grid {
        display: flex;
        gap: 1.25rem;
        padding-top: 1rem;
        border-top: 1px solid #f7fafc;
    }

    .foro-meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #718096;
        font-size: 0.8125rem;
        font-weight: 600;
    }

    .foro-meta-item i {
        color: #a0aec0;
    }

    .foro-card-footer {
        padding: 1.25rem 1.5rem;
        background: #f8fafc;
        border-top: 1px solid #edf2f7;
        border-radius: 0 0 16px 16px;
    }

    .btn-view-foro {
        color: #1a4789;
        font-weight: 700;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        transition: gap 0.2s;
    }

    .modern-foro-card:hover .btn-view-foro {
        gap: 0.5rem;
    }

    /* Modal Tooltips & Buttons */
    .btn-action-modern {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchForums');
        const timeFilter = document.getElementById('timeFilter');
        const forumItems = document.querySelectorAll('.forum-card-item');
        const noResultsMsg = document.getElementById('noResultsMsg');

        function applyFilters() {
            const q = searchInput.value.toLowerCase().trim();
            const time = timeFilter.value;
            const now = new Date();
            let visibleCount = 0;

            forumItems.forEach(item => {
                const name = item.getAttribute('data-forum-name');
                const created = new Date(item.getAttribute('data-created'));

                let matchesSearch = name.includes(q);
                let matchesTime = true;

                if (time !== 'all') {
                    const diffTime = Math.abs(now - created);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                    if (time === 'today') {
                        matchesTime = created.toDateString() === now.toDateString();
                    } else if (time === 'week') {
                        matchesTime = diffDays <= 7;
                    } else if (time === 'month') {
                        matchesTime = diffDays <= 30;
                    } else if (time === 'year') {
                        matchesTime = diffDays <= 365;
                    }
                }

                if (matchesSearch && matchesTime) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Mostrar/ocultar mensaje de no resultados
            if (noResultsMsg) {
                if (visibleCount === 0) {
                    noResultsMsg.classList.remove('d-none');
                } else {
                    noResultsMsg.classList.add('d-none');
                }
            }
        }

        window.resetForoFilters = function() {
            if (searchInput) searchInput.value = '';
            if (timeFilter) timeFilter.value = 'all';
            applyFilters();
        };

        if (searchInput) searchInput.addEventListener('input', applyFilters);
        if (timeFilter) timeFilter.addEventListener('change', applyFilters);

        // Confirmación para eliminar
        document.querySelectorAll('.ntf-form-delete').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Eliminar foro?',
                    text: "Esta acción enviará el foro a la papelera.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) this.submit();
                });
            });
        });
    });
</script>
