@extends('layout')

@section('content')

<div class="ach-outer">
    <div class="ach-hero">
        <div class="ach-hero-glow"></div>
        <div class="container ach-hero-body">
            <div class="ach-level-wrap">
                <div class="ach-level-orb">
                    <span class="ach-level-num">{{ $userLevel }}</span>
                    <span class="ach-level-label">NVL</span>
                </div>
                <div class="ach-xp-block">
                    <div class="d-flex justify-content-between align-items-baseline mb-1">
                        <span class="ach-xp-title">Experiencia</span>
                        <span class="ach-xp-fraction">
                            <strong>{{ number_format($currentXP) }}</strong>
                            <span class="text-white-50"> / {{ number_format($nextLevelXP) }} XP</span>
                        </span>
                    </div>
                    <div class="ach-xp-track">
                        <div class="ach-xp-fill"
                             style="width:{{ $nextLevelXP > 0 ? min(($currentXP / $nextLevelXP) * 100, 100) : 0 }}%">
                            <span class="ach-xp-glow-dot"></span>
                        </div>
                    </div>
                    <div class="ach-xp-sub mt-1">
                        {{ number_format($nextLevelXP - $currentXP) }} XP para el siguiente nivel
                    </div>
                </div>
                <div class="ach-hero-stats">
                    <div class="ach-hero-stat">
                        <span class="ach-hero-stat-val">{{ number_format($totalXP) }}</span>
                        <span class="ach-hero-stat-lbl">XP Total</span>
                    </div>
                    <div class="ach-hero-stat-div"></div>
                    <div class="ach-hero-stat">
                        <span class="ach-hero-stat-val">#{{ $userRank }}</span>
                        <span class="ach-hero-stat-lbl">Ranking</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="container ach-stats-row">
        <div class="ach-stat-card">
            <div class="ach-stat-icon ach-stat-icon--total">
                <i class="bi bi-trophy-fill"></i>
            </div>
            <div>
                <div class="ach-stat-val">{{ $totalAchievements }}</div>
                <div class="ach-stat-lbl">Logros Totales</div>
            </div>
        </div>
        <div class="ach-stat-card">
            <div class="ach-stat-icon ach-stat-icon--unlocked">
                <i class="bi bi-patch-check-fill"></i>
            </div>
            <div>
                <div class="ach-stat-val">{{ $unlockedAchievements }}</div>
                <div class="ach-stat-lbl">Desbloqueados</div>
            </div>
        </div>
        <div class="ach-stat-card">
            <div class="ach-stat-icon ach-stat-icon--locked">
                <i class="bi bi-lock-fill"></i>
            </div>
            <div>
                <div class="ach-stat-val">{{ $totalAchievements - $unlockedAchievements }}</div>
                <div class="ach-stat-lbl">Por desbloquear</div>
            </div>
        </div>
        <div class="ach-stat-card">
            <div class="ach-stat-icon ach-stat-icon--pct">
                <i class="bi bi-bar-chart-fill"></i>
            </div>
            <div>
                <div class="ach-stat-val">{{ $completionPercentage }}%</div>
                <div class="ach-stat-lbl">Completado</div>
            </div>
        </div>
    </div>

    {{-- ===== LISTA DE LOGROS ===== --}}
    <div class="container pb-5">

        <div class="ach-section-header">
            <h4 class="ach-section-title">
                <i class="bi bi-stars me-2"></i>Mis Logros
            </h4>
            {{-- Filtros rápidos --}}
            <div class="ach-filters">
                <button class="ach-filter active" data-filter="all">Todos</button>
                <button class="ach-filter" data-filter="unlocked">Desbloqueados</button>
                <button class="ach-filter" data-filter="locked">Pendientes</button>
            </div>
        </div>

        <div class="ach-grid" id="achGrid">
            @forelse($achievements as $achievement)
                <div class="ach-card {{ $achievement->isUnlocked ? 'ach-card--unlocked' : 'ach-card--locked' }}"
                     data-state="{{ $achievement->isUnlocked ? 'unlocked' : 'locked' }}">

                    {{-- Ícono --}}
                    <div class="ach-card-icon {{ $achievement->isUnlocked ? '' : 'ach-card-icon--locked' }}">
                        <span class="ach-emoji">{{ $achievement->icon }}</span>
                        @if($achievement->isUnlocked)
                            <div class="ach-card-icon-ring"></div>
                        @endif
                    </div>

                    {{-- Contenido --}}
                    <div class="ach-card-body">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h5 class="ach-card-title mb-0">{{ $achievement->title }}</h5>
                            <span class="ach-xp-badge">+{{ $achievement->xp_reward }} XP</span>
                        </div>
                        <p class="ach-card-desc">{{ $achievement->description }}</p>

                        @if($achievement->isUnlocked)
                            <div class="ach-unlocked-badge">
                                <i class="bi bi-check-circle-fill me-1"></i> Desbloqueado
                            </div>
                        @else
                            @php
                                $pct = $achievement->requirement_value > 0
                                    ? min(($achievement->current_progress / $achievement->requirement_value) * 100, 100)
                                    : 0;
                            @endphp
                            <div class="ach-progress-wrap">
                                <div class="ach-progress-track">
                                    <div class="ach-progress-fill" style="width:{{ $pct }}%"></div>
                                </div>
                                <span class="ach-progress-label">
                                    {{ $achievement->current_progress ?? 0 }} / {{ $achievement->requirement_value }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="ach-empty">
                    <i class="bi bi-trophy ach-empty-icon"></i>
                    <h5>Aún no hay logros disponibles</h5>
                    <p>¡Participa en actividades para desbloquear logros!</p>
                </div>
            @endforelse
        </div>

    </div>
</div>


{{-- ===== ESTILOS ===== --}}
<style>
    /* ─── Variables ─── */
    :root {
        --ach-primary:   #1a4789;
        --ach-accent:    #39a6cb;
        --ach-gold:      #f59e0b;
        --ach-gold-soft: #fef3c7;
        --ach-green:     #10b981;
        --ach-surface:   #f8fafc;
        --ach-border:    #e2e8f0;
        --ach-text:      #1e293b;
        --ach-muted:     #64748b;
    }

    .ach-outer { background: var(--ach-surface); min-height: 100vh; }

    /* ─── Hero ─── */
    .ach-hero {
        background: linear-gradient(135deg, var(--ach-primary) 0%, #0f2d5e 60%, #07193a 100%);
        padding: 3rem 0 5rem;
        position: relative;
        overflow: hidden;
    }
    .ach-hero-glow {
        position: absolute; inset: 0;
        background: radial-gradient(ellipse 70% 80% at 80% 50%, rgba(57,166,203,.18) 0%, transparent 70%),
                    radial-gradient(ellipse 40% 50% at 20% 80%, rgba(245,158,11,.1) 0%, transparent 60%);
        pointer-events: none;
    }
    .ach-hero-body { position: relative; z-index: 1; }

    /* ─── Level wrap ─── */
    .ach-level-wrap {
        display: flex;
        align-items: center;
        gap: 2rem;
        flex-wrap: wrap;
    }

    /* ─── Orb de nivel ─── */
    .ach-level-orb {
        width: 90px; height: 90px; border-radius: 50%;
        background: conic-gradient(var(--ach-gold) 0%, #fbbf24 40%, #d97706 100%);
        box-shadow: 0 0 0 4px rgba(245,158,11,.3), 0 0 30px rgba(245,158,11,.4);
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        flex-shrink: 0;
        animation: orbPulse 3s ease-in-out infinite;
    }
    .ach-level-num  { font-size: 2rem; font-weight: 900; color: #1a1a1a; line-height: 1; }
    .ach-level-label{ font-size: .55rem; font-weight: 800; color: #7c5009; letter-spacing: .12em; }

    @keyframes orbPulse {
        0%,100% { box-shadow: 0 0 0 4px rgba(245,158,11,.3), 0 0 30px rgba(245,158,11,.4); }
        50%      { box-shadow: 0 0 0 8px rgba(245,158,11,.15), 0 0 50px rgba(245,158,11,.6); }
    }

    /* ─── XP block ─── */
    .ach-xp-block  { flex: 1; min-width: 220px; }
    .ach-xp-title  { color: rgba(255,255,255,.7); font-size: .8rem; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; }
    .ach-xp-fraction { color: #fff; font-size: .95rem; }
    .ach-xp-sub    { color: rgba(255,255,255,.5); font-size: .78rem; }

    .ach-xp-track  {
        height: 10px; border-radius: 99px;
        background: rgba(255,255,255,.12);
        overflow: visible; position: relative;
    }
    .ach-xp-fill   {
        height: 100%; border-radius: 99px;
        background: linear-gradient(90deg, var(--ach-accent), var(--ach-gold));
        position: relative;
        transition: width 1s cubic-bezier(.4,0,.2,1);
        box-shadow: 0 0 10px rgba(57,166,203,.5);
    }
    .ach-xp-glow-dot {
        position: absolute; right: -1px; top: 50%;
        transform: translateY(-50%);
        width: 16px; height: 16px; border-radius: 50%;
        background: #fff;
        box-shadow: 0 0 8px var(--ach-gold), 0 0 20px var(--ach-accent);
        animation: dotPulse 1.5s ease-in-out infinite;
    }
    @keyframes dotPulse {
        0%,100% { transform: translateY(-50%) scale(1); }
        50%      { transform: translateY(-50%) scale(1.4); }
    }

    /* ─── Stats en hero ─── */
    .ach-hero-stats {
        display: flex; align-items: center; gap: 1.25rem;
        background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 16px; padding: 1rem 1.5rem;
        backdrop-filter: blur(8px);
        flex-shrink: 0;
    }
    .ach-hero-stat      { text-align: center; }
    .ach-hero-stat-val  { display: block; font-size: 1.5rem; font-weight: 800; color: #fff; line-height: 1; }
    .ach-hero-stat-lbl  { display: block; font-size: .7rem; color: rgba(255,255,255,.5); margin-top: .2rem; text-transform: uppercase; letter-spacing: .06em; }
    .ach-hero-stat-div  { width: 1px; height: 36px; background: rgba(255,255,255,.2); }

    /* ─── Tarjetas de estadísticas ─── */
    .ach-stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-top: -2.5rem;
        margin-bottom: 2.5rem;
        position: relative; z-index: 2;
    }
    .ach-stat-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        display: flex; align-items: center; gap: 1rem;
        box-shadow: 0 4px 20px rgba(0,0,0,.08);
        border: 1px solid var(--ach-border);
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .ach-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(0,0,0,.12);
    }
    .ach-stat-icon {
        width: 48px; height: 48px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; flex-shrink: 0;
    }
    .ach-stat-icon--total    { background: #fef3c7; color: var(--ach-gold); }
    .ach-stat-icon--unlocked { background: #d1fae5; color: var(--ach-green); }
    .ach-stat-icon--locked   { background: #f1f5f9; color: var(--ach-muted); }
    .ach-stat-icon--pct      { background: #dbeafe; color: var(--ach-primary); }
    .ach-stat-val { font-size: 1.6rem; font-weight: 800; color: var(--ach-text); line-height: 1; }
    .ach-stat-lbl { font-size: .75rem; color: var(--ach-muted); margin-top: .2rem; }

    /* ─── Sección header + filtros ─── */
    .ach-section-header {
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .ach-section-title {
        font-size: 1.25rem; font-weight: 700;
        color: var(--ach-text); margin: 0;
    }
    .ach-filters { display: flex; gap: .5rem; }
    .ach-filter {
        padding: .35rem .9rem; border-radius: 99px; border: 1.5px solid var(--ach-border);
        background: #fff; color: var(--ach-muted); font-size: .8rem; font-weight: 600;
        cursor: pointer; transition: all .2s ease;
    }
    .ach-filter:hover  { border-color: var(--ach-primary); color: var(--ach-primary); }
    .ach-filter.active { background: var(--ach-primary); border-color: var(--ach-primary); color: #fff; }

    /* ─── Grid de logros ─── */
    .ach-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.25rem;
    }

    /* ─── Tarjeta de logro ─── */
    .ach-card {
        background: #fff;
        border-radius: 18px;
        border: 1.5px solid var(--ach-border);
        padding: 1.25rem;
        display: flex; align-items: flex-start; gap: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,.05);
        transition: transform .25s ease, box-shadow .25s ease;
        animation: cardFadeIn .4s ease both;
    }
    .ach-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 28px rgba(0,0,0,.1);
    }
    .ach-card--unlocked {
        border-color: rgba(16,185,129,.35);
        background: linear-gradient(135deg, #fff 0%, #f0fdf4 100%);
    }
    .ach-card--locked { opacity: .75; }
    .ach-card--locked:hover { opacity: 1; }

    @keyframes cardFadeIn {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Stagger de entrada */
    .ach-card:nth-child(1)  { animation-delay: .05s; }
    .ach-card:nth-child(2)  { animation-delay: .10s; }
    .ach-card:nth-child(3)  { animation-delay: .15s; }
    .ach-card:nth-child(4)  { animation-delay: .20s; }
    .ach-card:nth-child(5)  { animation-delay: .25s; }
    .ach-card:nth-child(6)  { animation-delay: .30s; }
    .ach-card:nth-child(7)  { animation-delay: .35s; }
    .ach-card:nth-child(8)  { animation-delay: .40s; }

    /* ─── Ícono del logro ─── */
    .ach-card-icon {
        width: 60px; height: 60px; border-radius: 16px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        position: relative;
        background: var(--ach-gold-soft);
        box-shadow: 0 4px 12px rgba(245,158,11,.2);
    }
    .ach-card-icon--locked {
        background: #f1f5f9;
        box-shadow: none;
        filter: grayscale(1);
    }
    .ach-emoji { font-size: 1.9rem; line-height: 1; }
    .ach-card-icon-ring {
        position: absolute; inset: -4px;
        border-radius: 20px;
        border: 2px solid var(--ach-gold);
        animation: ringPulse 2.5s ease-in-out infinite;
    }
    @keyframes ringPulse {
        0%,100% { opacity: .6; transform: scale(1); }
        50%      { opacity: 1;  transform: scale(1.05); }
    }

    /* ─── Body tarjeta ─── */
    .ach-card-body  { flex: 1; min-width: 0; }
    .ach-card-title { font-size: .95rem; font-weight: 700; color: var(--ach-text); }
    .ach-card-desc  { font-size: .8rem; color: var(--ach-muted); margin-bottom: .75rem; line-height: 1.4; }

    .ach-xp-badge {
        background: var(--ach-primary);
        color: #fff; border-radius: 99px;
        padding: .2rem .65rem; font-size: .72rem; font-weight: 700;
        white-space: nowrap; flex-shrink: 0;
    }

    .ach-unlocked-badge {
        display: inline-flex; align-items: center;
        background: #d1fae5; color: var(--ach-green);
        border-radius: 99px; padding: .25rem .75rem;
        font-size: .78rem; font-weight: 700;
    }

    /* ─── Barra de progreso interna ─── */
    .ach-progress-wrap  { display: flex; align-items: center; gap: .75rem; }
    .ach-progress-track {
        flex: 1; height: 7px; border-radius: 99px;
        background: #e2e8f0; overflow: hidden;
    }
    .ach-progress-fill  {
        height: 100%; border-radius: 99px;
        background: linear-gradient(90deg, var(--ach-accent), var(--ach-primary));
        transition: width .8s ease;
    }
    .ach-progress-label { font-size: .72rem; color: var(--ach-muted); white-space: nowrap; font-weight: 600; }

    /* ─── Estado vacío ─── */
    .ach-empty {
        grid-column: 1 / -1;
        text-align: center; padding: 4rem 2rem;
        color: var(--ach-muted);
    }
    .ach-empty-icon { font-size: 4rem; display: block; margin-bottom: 1rem; opacity: .4; }

    /* ─── Responsive ─── */
    @media (max-width: 992px) {
        .ach-stats-row { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 640px) {
        .ach-level-wrap  { flex-direction: column; align-items: flex-start; gap: 1.25rem; }
        .ach-hero-stats  { width: 100%; justify-content: center; }
        .ach-stats-row   { grid-template-columns: repeat(2, 1fr); margin-top: -1.5rem; }
        .ach-grid        { grid-template-columns: 1fr; }
        .ach-section-header { flex-direction: column; align-items: flex-start; }
    }
</style>


{{-- ===== SCRIPTS ===== --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Filtros de logros ── */
    const filters  = document.querySelectorAll('.ach-filter');
    const cards    = document.querySelectorAll('.ach-card');

    filters.forEach(btn => {
        btn.addEventListener('click', function () {
            filters.forEach(f => f.classList.remove('active'));
            this.classList.add('active');

            const filter = this.dataset.filter;
            cards.forEach(card => {
                const show = filter === 'all' || card.dataset.state === filter;
                card.style.display = show ? '' : 'none';
            });
        });
    });

});
</script>

@endsection
