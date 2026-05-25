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

        <div class="p-3">
            @if ($recursos->count() > 0)

                @php
                    $iconosRec = [
                        'pdf' => 'bi-file-earmark-pdf-fill', 'word' => 'bi-file-earmark-word-fill',
                        'excel' => 'bi-file-earmark-excel-fill', 'powerpoint' => 'bi-file-earmark-ppt-fill',
                        'video' => 'bi-play-circle-fill', 'youtube' => 'bi-youtube',
                        'imagen' => 'bi-image-fill', 'enlace' => 'bi-link-45deg',
                        'drive' => 'bi-google', 'docs' => 'bi-file-earmark-text-fill',
                        'audio' => 'bi-mic-fill', 'forms' => 'bi-ui-checks',
                        'kahoot' => 'bi-controller', 'canva' => 'bi-brush-fill',
                        'archivos-adjuntos' => 'bi-file-earmark-zip-fill',
                    ];
                    $coloresRec = [
                        'pdf' => '#ef4444', 'word' => '#2b6cb0', 'excel' => '#10b981',
                        'powerpoint' => '#f97316', 'video' => '#f59e0b', 'youtube' => '#ff0000',
                        'enlace' => '#6366f1', 'drive' => '#34a853', 'imagen' => '#8e24aa',
                        'audio' => '#00897b', 'docs' => '#4285f4', 'forms' => '#00897b',
                        'kahoot' => '#46178f', 'canva' => '#00c4cc',
                        'archivos-adjuntos' => '#5d4037',
                    ];
                @endphp

                {{-- Table-style list --}}
                <div class="table-container-modern">
                    <table class="table-modern" id="recursosTable">
                        <thead>
                            <tr>
                                <th class="th-content" style="width:40%">Recurso</th>
                                <th class="th-content" style="width:15%">Tipo</th>
                                <th class="th-content" style="width:20%">Descripción</th>
                                <th class="th-content" style="width:12%">Fecha</th>
                                <th class="th-content" style="width:13%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recursos as $recurso)
                                @php
                                    $tipo  = $recurso->tipoRecurso;
                                    $cat   = 'document';
                                    if (in_array($tipo, ['video', 'youtube', 'audio'])) $cat = 'media';
                                    if (in_array($tipo, ['enlace', 'drive', 'docs', 'kahoot', 'canva', 'forms'])) $cat = 'link';
                                    $icono = $iconosRec[$tipo] ?? 'bi-file-earmark-fill';
                                    $color = $coloresRec[$tipo] ?? '#145da0';
                                @endphp

                                <tr class="resource-table-row"
                                    data-resource-name="{{ strtolower($recurso->nombreRecurso) }}"
                                    data-resource-type="{{ $cat }}">

                                    {{-- Recurso (ícono + nombre) --}}
                                    <td>
                                        <div class="si-item-row" style="border:none;padding:0">
                                            <div class="si-item-icon" style="color:{{ $color }}">
                                                <i class="bi {{ $icono }}"></i>
                                            </div>
                                            <span class="si-item-name">{{ $recurso->nombreRecurso }}</span>
                                        </div>
                                    </td>

                                    {{-- Tipo badge --}}
                                    <td>
                                        <span class="cl-lesson-badge cl-badge--open">
                                            {{ ucfirst($tipo) }}
                                        </span>
                                    </td>

                                    {{-- Descripción --}}
                                    <td>
                                        <span class="si-item-type">
                                            {!! Str::limit(strip_tags($recurso->descripcionRecursos), 60) !!}
                                        </span>
                                    </td>

                                    {{-- Fecha --}}
                                    <td>
                                        <span class="si-item-type">
                                            {{ $recurso->created_at ? $recurso->created_at->format('d/m/Y') : '—' }}
                                        </span>
                                    </td>

                                    {{-- Acciones --}}
                                    <td>
                                        <div class="si-item-actions">
                                            @if ($recurso->archivoRecurso)
                                                <a href="{{ route('recursos.descargar', encrypt($recurso->id)) }}"
                                                   class="si-action-link" title="Descargar">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            @endif

                                            @if (auth()->user()->hasRole('Docente') || auth()->user()->hasRole('Administrador'))
                                                <button class="si-action-btn" data-bs-toggle="modal"
                                                    data-bs-target="#modalEditarRecurso-{{ $recurso->id }}"
                                                    title="Editar recurso">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <form class="d-inline ntf-form-delete-rec"
                                                      action="{{ route('quitarRecurso', encrypt($recurso->id)) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="si-action-btn si-action-btn--danger"
                                                            title="Eliminar recurso">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- No results message --}}
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput   = document.getElementById('searchResources');
        const typeFilter    = document.getElementById('typeFilter');
        const resourceRows  = document.querySelectorAll('.resource-table-row');
        const noResultsMsg  = document.getElementById('noResourcesResults');

        function applyFilters() {
            const q    = searchInput?.value.toLowerCase().trim() || '';
            const type = typeFilter?.value || 'all';
            let count  = 0;

            resourceRows.forEach(row => {
                const name = row.getAttribute('data-resource-name');
                const cat  = row.getAttribute('data-resource-type');
                const show = name.includes(q) && (type === 'all' || cat === type);
                row.style.display = show ? '' : 'none';
                if (show) count++;
            });

            if (noResultsMsg) {
                noResultsMsg.classList.toggle('d-none', count > 0);
            }
        }

        window.resetRecursoFilters = function() {
            if (searchInput) searchInput.value = '';
            if (typeFilter) typeFilter.value = 'all';
            applyFilters();
        };

        searchInput?.addEventListener('input', applyFilters);
        typeFilter?.addEventListener('change', applyFilters);

        // Delete confirmation
        document.querySelectorAll('.ntf-form-delete-rec').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (typeof Swal !== 'undefined') {
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
                } else {
                    if (confirm('¿Eliminar?')) this.submit();
                }
            });
        });
    });
</script>
