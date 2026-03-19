@php
    $achievements = \App\Models\Achievement::with(['users', 'inscritos'])
        ->withCount(['users', 'inscritos'])
        ->get();
@endphp




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

    {{-- ============================================================
         GRID DE LOGROS
         ============================================================ --}}
    <div class="ach-grid" id="achGrid">

        @forelse ($achievements as $achievement)
            @php
                $totalUsers = ($achievement->users_count ?? 0) + ($achievement->inscritos_count ?? 0);
                $req         = $achievement->requirement_value ?? 1;
                $progress    = $req > 1 ? min(100, round(($totalUsers / $req) * 100)) : null;
                $isSecret    = $achievement->is_secret;
            @endphp

            <div class="ach-card achievement-item"
                 data-title="{{ strtolower($achievement->title) }}"
                 data-description="{{ strtolower($achievement->description) }}"
                 data-type="{{ strtolower($achievement->type) }}"
                 data-category="{{ strtolower($achievement->category) }}">

                {{-- Franja lateral --}}
                <div class="ach-card-stripe {{ $isSecret ? 'secret' : '' }}"></div>

                <div class="ach-card-body">

                    {{-- Encabezado --}}
                    <div class="ach-card-head">
                        <div class="ach-icon-wrap"><i class="bi bi-star"></i></div>
                        <div class="ach-card-meta">
                            <h5 class="ach-card-title" title="{{ $achievement->title }}">
                                {{ $achievement->title }}
                            </h5>
                            <span class="ach-card-users">
                                <i class="bi bi-people-fill" style="font-size:.7rem;margin-right:.25rem;"></i>
                                {{ $totalUsers }} {{ $totalUsers === 1 ? 'usuario ha obtenido' : 'usuarios han obtenido' }} este logro
                            </span>
                        </div>
                    </div>

                    {{-- Descripción --}}
                    <p class="ach-card-desc">{{ $achievement->description }}</p>

                    {{-- Progreso (solo si requirement_value > 1) --}}
                    @if ($progress !== null)
                        <div class="ach-progress-wrap">
                            <div class="ach-progress-label">
                                <span>Progreso global</span>
                                <span>{{ $totalUsers }}/{{ $req }}</span>
                            </div>
                            <div class="ach-progress-track">
                                <div class="ach-progress-fill" style="width:{{ $progress }}%"></div>
                            </div>
                        </div>
                    @endif

                    {{-- Footer --}}
                    <div class="ach-card-footer">

                        {{-- Badges --}}
                        <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
                            <span class="ach-badge ach-badge-xp">
                                <i class="bi bi-lightning-charge-fill"></i>
                                {{ $achievement->xp_reward }} XP
                            </span>
                            @if ($isSecret)
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
                                    onclick="achView({{ $achievement->id }})"
                                    title="Ver detalles">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="ach-action-btn"
                                    onclick="achEdit({{ $achievement->id }})"
                                    title="Editar">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="ach-action-btn del"
                                    onclick="achDelete({{ $achievement->id }})"
                                    title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
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

    {{-- ============================================================
         MODAL – NUEVO LOGRO
         ============================================================ --}}
    <div class="modal fade ach-modal"
         id="achNewModal"
         tabindex="-1"
         aria-labelledby="achNewModalLabel"
         aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

                {{-- Header --}}
                <div class="modal-header">
                    <h5 class="modal-title" id="achNewModalLabel">
                        <i class="bi bi-trophy me-2" style="color:var(--ach-primary);"></i>
                        Nuevo Logro
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                {{-- Body --}}
                <div class="modal-body">
                    <form id="achNewForm" method="POST" action="">
                        @csrf

                        <div class="ach-field">
                            <label>Nombre del logro</label>
                            <input type="text" name="name" placeholder="Ej. Maestro del quiz" required>
                        </div>

                        <div class="ach-field">
                            <label>Descripción</label>
                            <textarea name="description" placeholder="Describe qué debe hacer el usuario…" required></textarea>
                        </div>

                        <div class="ach-row">
                            <div class="ach-field">
                                <label>XP a otorgar</label>
                                <input type="number" name="xp_reward" min="0" value="100" required>
                            </div>
                            <div class="ach-field">
                                <label>Valor requerido</label>
                                <input type="number" name="requirement_value" min="1" value="1" required>
                            </div>
                        </div>

                        <div class="ach-row">
                            <div class="ach-field">
                                <label>Tipo</label>
                                <select name="type" required>
                                    <option value="QUIZ_MASTER">Maestro de Cuestionarios</option>
                                    <option value="COURSE_COMPLETER">Completador de Cursos</option>
                                    <option value="PERFECT_SCORE">Puntuación Perfecta</option>
                                </select>
                            </div>
                            <div class="ach-field">
                                <label>Categoría</label>
                                <select name="category" required>
                                    <option value="academic">Académico</option>
                                    <option value="participation">Participación</option>
                                    <option value="social">Social</option>
                                </select>
                            </div>
                        </div>

                        <div class="ach-field">
                            <label>Ícono</label>
                            <select name="icon" required>
                                <option value="🎯">🎯  Objetivo</option>
                                <option value="🏆">🏆  Trofeo</option>
                                <option value="⭐">⭐  Estrella</option>
                                <option value="🏅">🏅  Medalla</option>
                                <option value="👑">👑  Corona</option>
                            </select>
                        </div>

                        <div class="ach-switch-row">
                            <label for="achIsSecret">Logro secreto</label>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="is_secret" id="achIsSecret" role="switch">
                            </div>
                        </div>

                    </form>
                </div>

                {{-- Footer --}}
                <div class="modal-footer">
                    <button type="button" class="ach-btn-ghost" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="button" class="ach-btn-primary" onclick="achSave()">
                        <i class="bi bi-check-lg"></i> Guardar logro
                    </button>
                </div>

            </div>
        </div>
    </div>

</div>{{-- /ach-module --}}


{{-- ============================================================
     JAVASCRIPT
     ============================================================ --}}
<script>
(function () {
    'use strict';

    /* ── Estado ─────────────────────────────────────────── */
    let achItems = [];

    /* ── Init ────────────────────────────────────────────── */
    function init() {
        achItems = Array.from(document.querySelectorAll('.achievement-item'));
        setupSearch();
        setupModal();
        initTooltips();
    }

    /* ── Búsqueda ────────────────────────────────────────── */
    function setupSearch() {
        const input = document.getElementById('achSearch');
        if (!input) return;

        input.addEventListener('input', filterAchievements);

        // Paste necesita pequeño delay
        input.addEventListener('paste', () => setTimeout(filterAchievements, 16));
    }

    function filterAchievements() {
        const term = document.getElementById('achSearch').value.trim().toLowerCase();

        if (!term) { showAll(); return; }

        let visible = 0;
        achItems.forEach(el => {
            const match = ['title','description','type','category']
                .some(attr => (el.dataset[attr] || '').includes(term));

            el.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        toggleNoResults(visible === 0);
    }

    function showAll() {
        achItems.forEach(el => (el.style.display = ''));
        toggleNoResults(false);
    }

    function toggleNoResults(show) {
        document.getElementById('achNoResults')?.classList.toggle('d-none', !show);
    }

    window.achClearSearch = function () {
        const input = document.getElementById('achSearch');
        if (input) { input.value = ''; filterAchievements(); input.focus(); }
    };

    /* ── Modal ───────────────────────────────────────────── */
    function setupModal() {
        const el = document.getElementById('achNewModal');
        if (!el) return;

        // Mover bajo <body> para evitar conflictos con transform/overflow
        if (el.parentElement !== document.body) document.body.appendChild(el);

        el.addEventListener('hidden.bs.modal', () => {
            document.getElementById('achNewForm')?.reset();
        });
    }

    /* ── Tooltips ────────────────────────────────────────── */
    function initTooltips() {
        if (typeof bootstrap === 'undefined') return;
        document.querySelectorAll('[title]').forEach(el => new bootstrap.Tooltip(el));
    }

    /* ── Acciones públicas ───────────────────────────────── */
    window.achView   = id => console.log('Ver logro:', id);
    window.achEdit   = id => console.log('Editar logro:', id);
    window.achDelete = id => console.log('Eliminar logro:', id);

    window.achSave = function () {
        const form  = document.getElementById('achNewForm');
        const name  = form?.querySelector('input[name="name"]');

        if (name && !name.value.trim()) { name.focus(); return; }

        // TODO: enviar formulario vía fetch / submit
        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('achNewModal'));
        modal.hide();
        form?.reset();
    };

    /* ── Arranque ────────────────────────────────────────── */
    document.readyState === 'loading'
        ? document.addEventListener('DOMContentLoaded', init)
        : init();

})();
</script>