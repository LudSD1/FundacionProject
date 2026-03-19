<div class="tab-pane fade" id="tab-recursos" role="tabpanel" aria-labelledby="recursos-tab">

    <div class="tbl-card">
        {{-- Hero Section --}}
        <div class="tbl-card-hero">
            <div class="tbl-card-hero-content">
                <h1 class="tbl-card-hero-title text-white">
                    <i class="bi bi-folder-fill me-2"></i>Material de Apoyo
                </h1>
                <p class="tbl-card-hero-subtitle text-white">
                    Recursos complementarios y material de estudio para este curso.
                </p>
            </div>

            <div class="tbl-card-hero-actions">
                {{-- Filtro de Tipo --}}
                <div class="tbl-hero-select-wrap">
                    <i class="bi bi-filter-circle tbl-hero-select-icon"></i>
                    <select class="tbl-hero-select" id="typeFilter">
                        <option value="all">Todos los tipos</option>
                        <option value="document">Documentos (PDF, Word, etc)</option>
                        <option value="media">Multimedia (Video, Audio)</option>
                        <option value="link">Enlaces y Drive</option>
                    </select>
                </div>

                {{-- Buscador --}}
                <div class="tbl-hero-search">
                    <i class="bi bi-search tbl-hero-search-icon"></i>
                    <input type="text" class="tbl-hero-search-input" id="searchResources"
                        placeholder="Buscar recursos..." autocomplete="off">
                </div>

                @if (auth()->user()->hasRole('Docente') || auth()->user()->hasRole('Administrador'))
                    <div class="d-flex gap-2 mt-2 mt-md-0">
                        <button class="tbl-hero-btn tbl-hero-btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearRecurso">
                            <i class="bi bi-plus-circle-fill"></i>
                            <span>Nuevo Recurso</span>
                        </button>
                        <a href="{{ route('ListaRecursosEliminados', encrypt($cursos->id)) }}" class="tbl-hero-btn tbl-hero-btn-danger">
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
                    <i class="bi bi-files text-primary"></i>
                    <span><strong>{{ $recursos->count() }}</strong> Recursos totales</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-pdf text-danger"></i>
                    <span><strong>{{ $recursos->whereIn('tipoRecurso', ['pdf', 'word', 'excel', 'powerpoint'])->count() }}</strong> Documentos</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-play-btn text-warning"></i>
                    <span><strong>{{ $recursos->whereIn('tipoRecurso', ['video', 'youtube'])->count() }}</strong> Multimedia</span>
                </div>
            </div>
        </div>

        <div class="p-4">
            @if ($recursos->count() > 0)
                <div class="row g-4" id="resourcesGrid">
                    @foreach ($recursos as $recurso)
                        @php
                            $tipo = $recurso->tipoRecurso;
                            $cat = 'document';
                            if (in_array($tipo, ['video', 'youtube', 'audio'])) $cat = 'media';
                            if (in_array($tipo, ['enlace', 'drive', 'docs', 'kahoot', 'canva', 'forms'])) $cat = 'link';

                            $iconos = [
                                'pdf' => 'bi-file-earmark-pdf-fill',
                                'word' => 'bi-file-earmark-word-fill',
                                'excel' => 'bi-file-earmark-excel-fill',
                                'powerpoint' => 'bi-file-earmark-ppt-fill',
                                'video' => 'bi-play-circle-fill',
                                'youtube' => 'bi-youtube',
                                'imagen' => 'bi-image-fill',
                                'enlace' => 'bi-link-45deg',
                                'drive' => 'bi-google',
                                'docs' => 'bi-file-earmark-text-fill',
                                'audio' => 'bi-mic-fill',
                            ];
                            $icono = $iconos[$tipo] ?? 'bi-file-earmark-fill';

                            $colores = [
                                'pdf' => '#ef4444',
                                'word' => '#2b6cb0',
                                'excel' => '#10b981',
                                'powerpoint' => '#f97316',
                                'video' => '#f59e0b',
                                'youtube' => '#ff0000',
                                'enlace' => '#6366f1',
                                'drive' => '#34a853',
                            ];
                            $color = $colores[$tipo] ?? '#1a4789';
                        @endphp

                        <div class=" resource-card-item"
                             data-resource-name="{{ strtolower($recurso->nombreRecurso) }}"
                             data-resource-type="{{ $cat }}">

                            <div class="modern-recurso-card">
                                <div class="recurso-card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-4">
                                        <div class="recurso-icon-box" style="background: {{ $color }}15; color: {{ $color }};">
                                            <i class="bi {{ $icono }}"></i>
                                        </div>
                                        <div class="recurso-badge">
                                            <span class="badge rounded-pill shadow-sm" style="background: {{ $color }}10; color: {{ $color }}; border: 1px solid {{ $color }}30;">
                                                {{ ucfirst($tipo) }}
                                            </span>
                                        </div>
                                    </div>

                                    <h6 class="recurso-title mb-2">
                                        {{ $recurso->nombreRecurso }}
                                    </h6>

                                    <div class="recurso-description text-secondary mb-3">
                                        {!! Str::limit(strip_tags($recurso->descripcionRecursos), 80) !!}
                                    </div>

                                    <div class="recurso-meta mt-auto">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        {{ $recurso->created_at ? $recurso->created_at->format('d/m/Y') : 'Sin fecha' }}
                                    </div>
                                </div>

                                <div class="recurso-card-footer">
                                    <div class="d-flex justify-content-between align-items-center w-100">
                                        @if ($recurso->archivoRecurso)
                                            <a href="{{ route('recursos.descargar', encrypt($recurso->id)) }}" class="btn-download-modern">
                                                <i class="bi bi-cloud-arrow-down-fill me-1"></i> Descargar
                                            </a>
                                        @else
                                            <span class="text-muted small">Solo lectura</span>
                                        @endif

                                        @if (auth()->user()->hasRole('Docente') || auth()->user()->hasRole('Administrador'))
                                            <div class="recurso-admin-actions">
                                                <button class="btn-action-modern btn-info" data-bs-toggle="modal"
                                                    data-bs-target="#modalEditarRecurso-{{ $recurso->id }}"
                                                    title="Editar recurso">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <form class="d-inline ntf-form-delete-rec" action="{{ route('quitarRecurso', encrypt($recurso->id)) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn-action-modern btn-delete" title="Eliminar recurso">
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

                {{-- Mensaje de "No se encontraron resultados" --}}
                <div id="noResourcesResults" class="empty-state-table py-5 d-none">
                    <div class="empty-icon-table">
                        <i class="bi bi-search"></i>
                    </div>
                    <h5 class="empty-title-table">No se encontraron recursos</h5>
                    <p class="empty-text-table">Intenta ajustar tu búsqueda o el filtro de tipo.</p>
                    <button class="tbl-hero-btn tbl-hero-btn-primary mt-3" style="width:auto"
                        onclick="resetRecursoFilters()">
                        <i class="bi bi-arrow-clockwise"></i>
                        Ver todos los recursos
                    </button>
                </div>
            @else
                <div class="empty-state-table py-5">
                    <div class="empty-icon-table">
                        <i class="bi bi-folder-x"></i>
                    </div>
                    <h5 class="empty-title-table">No hay recursos disponibles</h5>
                    <p class="empty-text-table">El instructor aún no ha subido material de apoyo para este curso.</p>

                    @if (auth()->user()->hasRole('Docente') || auth()->user()->hasRole('Administrador'))
                        <button class="tbl-hero-btn tbl-hero-btn-primary mt-3" style="width:auto"
                            data-bs-toggle="modal" data-bs-target="#modalCrearRecurso">
                            <i class="bi bi-plus-circle-fill"></i>
                            Subir Primer Recurso
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Modern Recurso Cards */
    .modern-recurso-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #edf2f7;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .modern-recurso-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border-color: #cbd5e0;
    }

    .recurso-card-body {
        padding: 1.5rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .recurso-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }

    .recurso-title {
        font-weight: 700;
        color: #1a202c;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .recurso-description {
        font-size: 0.85rem;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .recurso-meta {
        font-size: 0.75rem;
        color: #a0aec0;
        font-weight: 600;
    }

    .recurso-card-footer {
        padding: 1rem 1.5rem;
        background: #f8fafc;
        border-top: 1px solid #edf2f7;
        border-radius: 0 0 16px 16px;
    }

    .btn-download-modern {
        color: #10b981;
        font-weight: 700;
        font-size: 0.8rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        transition: opacity 0.2s;
    }

    .btn-download-modern:hover {
        opacity: 0.8;
        color: #059669;
    }

    .recurso-admin-actions {
        display: flex;
        gap: 0.25rem;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchResources');
        const typeFilter = document.getElementById('typeFilter');
        const resourceItems = document.querySelectorAll('.resource-card-item');
        const noResultsMsg = document.getElementById('noResourcesResults');

        function applyFilters() {
            const q = searchInput.value.toLowerCase().trim();
            const type = typeFilter.value;
            let visibleCount = 0;

            resourceItems.forEach(item => {
                const name = item.getAttribute('data-resource-name');
                const cat = item.getAttribute('data-resource-type');

                let matchesSearch = name.includes(q);
                let matchesType = (type === 'all' || cat === type);

                if (matchesSearch && matchesType) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            if (noResultsMsg) {
                if (visibleCount === 0) noResultsMsg.classList.remove('d-none');
                else noResultsMsg.classList.add('d-none');
            }
        }

        window.resetRecursoFilters = function() {
            if (searchInput) searchInput.value = '';
            if (typeFilter) typeFilter.value = 'all';
            applyFilters();
        };

        if (searchInput) searchInput.addEventListener('input', applyFilters);
        if (typeFilter) typeFilter.addEventListener('change', applyFilters);

        // Confirmación para eliminar
        document.querySelectorAll('.ntf-form-delete-rec').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Eliminar recurso?',
                    text: "Esta acción enviará el recurso a la papelera.",
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

