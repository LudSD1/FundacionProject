@php
    $achievements = \App\Models\Achievement::with(['users', 'inscritos'])
        ->withCount(['users', 'inscritos'])
        ->withTrashed()
        ->get();
@endphp
<style>
    .ach-card.trashed { opacity: 0.7; filter: grayscale(0.5); border: 2px dashed #cbd5e1; }
    .ach-card.trashed .ach-card-stripe { background: #94a3b8 !important; }
    .ach-badge-inactive { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
    .ach-action-btn.restore { color: #10b981; }
    .ach-action-btn.restore:hover { background: #ecfdf5; }
</style>

<div class="ach-module">
    <div class="ach-topbar">
        <div class="ach-search-wrap">
            <i class="bi bi-search ach-search-ico"></i>
            <input type="text"
                   id="achSearch"
                   placeholder="Buscar por nombre, tipo, categoría…"
                   autocomplete="off">
        </div>
        <button class="ach-btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#achNewModal">
            <i class="bi bi-plus-lg"></i> Nuevo logro
        </button>
    </div>


    <div class="ach-grid" id="achGrid">

        @forelse ($achievements as $achievement)
            @php
                $totalUsers = ($achievement->users_count ?? 0) + ($achievement->inscritos_count ?? 0);
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

                {{-- Franja lateral --}}
                <div class="ach-card-stripe {{ $isSecret ? 'secret' : '' }}"></div>

                <div class="ach-card-body">

                    {{-- Encabezado --}}
                    <div class="ach-card-head">
                        <div class="ach-icon-wrap">
                            <span style="font-size: 1.5rem;">{{ $achievement->icon ?? '⭐' }}</span>
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

                    {{-- Descripción --}}
                    <p class="ach-card-desc">{{ Str::limit($achievement->description, 80) }}</p>

                    {{-- Footer --}}
                    <div class="ach-card-footer">

                        {{-- Badges --}}
                        <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
                            <span class="ach-badge ach-badge-xp">
                                <i class="bi bi-lightning-charge-fill"></i>
                                {{ $achievement->xp_reward }} XP
                            </span>
                            @if ($isTrashed)
                                <span class="ach-badge ach-badge-inactive">
                                    <i class="bi bi-eye-slash-fill"></i> Inactivo
                                </span>
                            @elseif ($isSecret)
                                <span class="ach-badge ach-badge-sec">
                                    <i class="bi bi-lock-fill"></i> Secreto
                                </span>
                            @else
                                <span class="ach-badge ach-badge-pub">
                                    <i class="bi bi-globe2"></i> Público
                                </span>
                            @endif
                        </div>

                        {{-- Acciones --}}
                        <div class="ach-actions">
                            <button class="ach-action-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#achViewModal-{{ $achievement->id }}"
                                    title="Ver detalles">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="ach-action-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#achEditModal-{{ $achievement->id }}"
                                    title="Editar">
                                <i class="bi bi-pencil"></i>
                            </button>
                            @if ($isTrashed)
                                <button class="ach-action-btn restore"
                                        onclick="confirmRestoreAch({{ $achievement->id }})"
                                        title="Activar logro">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </button>
                                <form id="restore-ach-{{ $achievement->id }}" action="{{ route('restore.logro', $achievement->id) }}" method="POST" style="display:none;">
                                    @csrf
                                </form>
                            @else
                                <button class="ach-action-btn del"
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
                <button class="ach-btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#achNewModal">
                    Crear primer logro
                </button>
            </div>
        @endforelse

        {{-- Sin resultados en búsqueda --}}
        <div class="ach-empty d-none" id="achNoResults">
            <i class="bi bi-search"></i>
            <p>Sin resultados para tu búsqueda.</p>
            <button class="ach-btn-ghost" onclick="achClearSearch()">
                <i class="bi bi-x-lg"></i> Limpiar búsqueda
            </button>
        </div>

    </div>

    {{-- Modal Nuevo Logro --}}
    <div class="modal fade ach-modal" id="achNewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-primary text-white p-4 border-0">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-trophy me-2"></i>Nuevo Logro
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="achNewForm" method="POST" action="{{ route('logro.store') }}">
                        @csrf
                        <div class="ach-field mb-3">
                            <label class="fw-bold mb-1">Nombre del logro</label>
                            <input type="text" name="title" class="form-control" placeholder="Ej. Maestro del quiz" required>
                        </div>
                        <div class="ach-field mb-3">
                            <label class="fw-bold mb-1">Descripción</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Describe qué debe hacer el usuario…" required></textarea>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="fw-bold mb-1">XP a otorgar</label>
                                <input type="number" name="xp_reward" class="form-control" min="0" max="100" value="50" required>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-bold mb-1">Valor requerido</label>
                                <input type="number" name="requirement_value" class="form-control" min="1" value="1" required>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="fw-bold mb-1">Tipo</label>
                                <select name="type" class="form-select" required>
                                    <option value="QUIZ_MASTER">Maestro de Cuestionarios</option>
                                    <option value="COURSE_COMPLETER">Completador de Cursos</option>
                                    <option value="PERFECT_SCORE">Puntuación Perfecta</option>
                                    <option value="PARTICIPATION_IN_CLASS">Participación en Clase</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-bold mb-1">Categoría</label>
                                <select name="category" class="form-select" required>
                                    <option value="academic">Académico</option>
                                    <option value="participation">Participación</option>
                                    <option value="social">Social</option>
                                </select>
                            </div>
                        </div>
                        <div class="ach-field mb-3">
                            <label class="fw-bold mb-1">Ícono</label>
                            <select name="icon" class="form-select" required>
                                <option value="🎯">🎯 Objetivo</option>
                                <option value="🏆">🏆 Trofeo</option>
                                <option value="⭐">⭐ Estrella</option>
                                <option value="🏅">🏅 Medalla</option>
                                <option value="👑">👑 Corona</option>
                                <option value="🚀">🚀 Cohete</option>
                                <option value="🔥">🔥 Fuego</option>
                                <option value="💎">💎 Diamante</option>
                            </select>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_secret" id="achIsSecretNew" value="1">
                            <label class="form-check-label fw-bold" for="achIsSecretNew">Logro secreto</label>
                        </div>
                        <div class="modal-footer px-0 pb-0 pt-3 border-0">
                            <button type="button" class="btn btn-link text-muted fw-bold text-decoration-none" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold text-white">Guardar Logro</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>

    @foreach ($achievements as $achievement)
        {{-- Modal Ver Detalles --}}
        <div class="modal fade ach-modal" id="achViewModal-{{ $achievement->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="modal-header bg-info text-white p-4 border-0">
                        <h5 class="modal-title fw-bold">
                            <i class="bi bi-eye-fill me-2"></i>Detalles del Logro
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 text-center">
                        <div class="mb-3">
                            <span style="font-size: 4rem;">{{ $achievement->icon ?? '⭐' }}</span>
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
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                <i class="bi bi-tag-fill me-1"></i> {{ ucfirst($achievement->type) }}
                            </span>
                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">
                                <i class="bi bi-folder-fill me-1"></i> {{ ucfirst($achievement->category) }}
                            </span>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3 border-0 justify-content-center">
                        <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Editar Logro --}}
        <div class="modal fade ach-modal" id="achEditModal-{{ $achievement->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="modal-header bg-warning text-dark p-4 border-0">
                        <h5 class="modal-title fw-bold">
                            <i class="bi bi-pencil-square me-2"></i>Editar Logro
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form id="achEditForm-{{ $achievement->id }}" method="POST" action="{{ route('update.logro', $achievement->id) }}">
                            @csrf
                            @method('PATCH')
                            <div class="ach-field mb-3">
                                <label class="fw-bold mb-1">Nombre del logro</label>
                                <input type="text" name="title" class="form-control" value="{{ $achievement->title }}" required>
                            </div>
                            <div class="ach-field mb-3">
                                <label class="fw-bold mb-1">Descripción</label>
                                <textarea name="description" class="form-control" rows="3" required>{{ $achievement->description }}</textarea>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="fw-bold mb-1">XP a otorgar</label>
                                    <input type="number" name="xp_reward" class="form-control" min="0" max="100" value="{{ $achievement->xp_reward }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-bold mb-1">Valor requerido</label>
                                    <input type="number" name="requirement_value" class="form-control" min="1" value="{{ $achievement->requirement_value }}" required>
                                </div>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="fw-bold mb-1">Tipo</label>
                                    <select name="type" class="form-select" required>
                                        <option value="QUIZ_MASTER" {{ $achievement->type == 'QUIZ_MASTER' ? 'selected' : '' }}>Maestro de Cuestionarios</option>
                                        <option value="COURSE_COMPLETER" {{ $achievement->type == 'COURSE_COMPLETER' ? 'selected' : '' }}>Completador de Cursos</option>
                                        <option value="PERFECT_SCORE" {{ $achievement->type == 'PERFECT_SCORE' ? 'selected' : '' }}>Puntuación Perfecta</option>
                                        <option value="PARTICIPATION_IN_CLASS" {{ $achievement->type == 'PARTICIPATION_IN_CLASS' ? 'selected' : '' }}>Participación en Clase</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-bold mb-1">Categoría</label>
                                    <select name="category" class="form-select" required>
                                        <option value="academic" {{ $achievement->category == 'academic' ? 'selected' : '' }}>Académico</option>
                                        <option value="participation" {{ $achievement->category == 'participation' ? 'selected' : '' }}>Participación</option>
                                        <option value="social" {{ $achievement->category == 'social' ? 'selected' : '' }}>Social</option>
                                    </select>
                                </div>
                            </div>
                            <div class="ach-field mb-3">
                                <label class="fw-bold mb-1">Ícono</label>
                                <select name="icon" class="form-select" required>
                                    <option value="🎯" {{ $achievement->icon == '🎯' ? 'selected' : '' }}>🎯 Objetivo</option>
                                    <option value="🏆" {{ $achievement->icon == '🏆' ? 'selected' : '' }}>🏆 Trofeo</option>
                                    <option value="⭐" {{ $achievement->icon == '⭐' ? 'selected' : '' }}>⭐ Estrella</option>
                                    <option value="🏅" {{ $achievement->icon == '🏅' ? 'selected' : '' }}>🏅 Medalla</option>
                                    <option value="👑" {{ $achievement->icon == '👑' ? 'selected' : '' }}>👑 Corona</option>
                                    <option value="🚀" {{ $achievement->icon == '🚀' ? 'selected' : '' }}>🚀 Cohete</option>
                                    <option value="🔥" {{ $achievement->icon == '🔥' ? 'selected' : '' }}>🔥 Fuego</option>
                                    <option value="💎" {{ $achievement->icon == '💎' ? 'selected' : '' }}>💎 Diamante</option>
                                </select>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_secret" id="achIsSecretEdit-{{ $achievement->id }}" value="1" {{ $achievement->is_secret ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="achIsSecretEdit-{{ $achievement->id }}">Logro secreto</label>
                            </div>
                            <div class="modal-footer px-0 pb-0 pt-3 border-0">
                                <button type="button" class="btn btn-link text-muted fw-bold text-decoration-none" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold">Actualizar Logro</button>
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
        // Mover todos los modales al final del body para evitar problemas de z-index y overflow
        const modals = document.querySelectorAll('.ach-modal');
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
            reverseButtons: true,
            customClass: { popup: 'rounded-4 shadow-lg border-0' }
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
            reverseButtons: true,
            customClass: { popup: 'rounded-4 shadow-lg border-0' }
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
