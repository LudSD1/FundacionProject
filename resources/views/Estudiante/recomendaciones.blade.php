@extends('layoutlanding')

@section('main')

{{-- ── Hero Section ────────────────────────────────────────── --}}
<section class="recom-hero-section">
    <div class="recom-hero-bg">
        <div class="recom-hero-blob recom-hero-blob--left"></div>
        <div class="recom-hero-blob recom-hero-blob--right"></div>
    </div>
    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <span class="recom-hero-chip">
                    <i class="bi bi-stars me-1"></i> Motor de Recomendaciones Inteligente
                </span>
                <h1 class="recom-hero-title">
                    Cursos recomendados<br>
                    <span class="recom-hero-highlight">solo para ti</span>
                </h1>
                <p class="recom-hero-subtitle">
                    Nuestro algoritmo analiza tus categorías de interés, nivel de progreso,
                    formato preferido y actividad para ofrecerte los cursos que mejor se
                    adaptan a tu perfil.
                </p>
            </div>
        </div>

        {{-- Mini stats del perfil --}}
        @if($profile['total_enrollments'] > 0)
        <div class="row justify-content-center mt-4">
            <div class="col-lg-10">
                <div class="recom-profile-stats">
                    <div class="recom-stat-item">
                        <i class="bi bi-book-half"></i>
                        <span><strong>{{ $profile['total_enrollments'] }}</strong> cursos inscritos</span>
                    </div>
                    <div class="recom-stat-divider"></div>
                    <div class="recom-stat-item">
                        <i class="bi bi-graph-up-arrow"></i>
                        <span>Progreso promedio: <strong>{{ $profile['avg_progress'] }}%</strong></span>
                    </div>
                    <div class="recom-stat-divider"></div>
                    <div class="recom-stat-item">
                        <i class="bi bi-lightning-charge"></i>
                        <span>Nivel sugerido: <strong>{{ $profile['suggested_level'] }}</strong></span>
                    </div>
                    @if($profile['preferred_format'])
                    <div class="recom-stat-divider"></div>
                    <div class="recom-stat-item">
                        <i class="bi bi-display"></i>
                        <span>Formato: <strong>{{ $profile['preferred_format'] }}</strong></span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

{{-- ── Recommendations Grid ───────────────────────────────── --}}
<section class="recom-courses-section">
    <div class="container">

        @if($recommendations->isEmpty())
            <div class="recom-empty-state">
                <div class="recom-empty-icon">
                    <i class="bi bi-mortarboard"></i>
                </div>
                <h3 class="recom-empty-title">Aún estamos conociéndote</h3>
                <p class="recom-empty-text">
                    Inscríbete en al menos un curso para que podamos generar recomendaciones
                    personalizadas basadas en tus intereses y progreso.
                </p>
                <a href="{{ route('lista.cursos.congresos') }}" class="recom-empty-btn">
                    <i class="bi bi-search me-2"></i> Explorar cursos disponibles
                </a>
            </div>
        @else
            <div class="row g-4" id="recomGrid">
                @foreach($recommendations as $index => $curso)
                    @php
                        $imagen = $curso->imagen
                            ? asset('storage/' . $curso->imagen)
                            : asset('assets/img/bg2.png');
                        $docente = $curso->docente;
                        $avatar  = $docente?->avatar
                            ? asset('storage/' . $docente->avatar)
                            : asset('assets/img/user.png');
                        $rating = round($curso->calificaciones_avg_puntuacion ?? 0, 1);
                        $score  = $curso->recommendation_score ?? 0;
                        $reason = $curso->recommendation_reason ?? 'Recomendado para ti';
                    @endphp

                    <div class="col-md-6 col-xl-4 recom-card-col" style="animation-delay: {{ $index * 80 }}ms">
                        <div class="recom-card" data-curso-id="{{ $curso->id }}">
                            {{-- Imagen --}}
                            <div class="recom-card-img-wrapper">
                                <img src="{{ $imagen }}" alt="{{ $curso->nombreCurso }}" class="recom-card-img" loading="lazy">
                                <div class="recom-card-overlay">
                                    <span class="recom-badge recom-badge--score">
                                        <i class="bi bi-bullseye me-1"></i>{{ $score }}% match
                                    </span>
                                </div>
                                @if($curso->tipo === 'congreso')
                                    <span class="recom-badge recom-badge--tipo recom-badge--congreso">
                                        <i class="bi bi-calendar-event me-1"></i>Congreso
                                    </span>
                                @else
                                    <span class="recom-badge recom-badge--tipo recom-badge--curso">
                                        <i class="bi bi-book me-1"></i>Curso
                                    </span>
                                @endif
                            </div>

                            {{-- Body --}}
                            <div class="recom-card-body">
                                {{-- Razón de recomendación --}}
                                <div class="recom-card-reason">
                                    <i class="bi bi-magic me-1"></i>{{ $reason }}
                                </div>

                                <h3 class="recom-card-title">
                                    <a href="{{ $curso->url }}"
                                       class="recom-card-title-link"
                                       onclick="trackRecommendationClick({{ $curso->id }})">
                                        {{ $curso->nombreCurso }}
                                    </a>
                                </h3>

                                <p class="recom-card-desc">
                                    {{ \Illuminate\Support\Str::limit($curso->descripcionC, 100) }}
                                </p>

                                {{-- Meta info --}}
                                <div class="recom-card-meta">
                                    @if($curso->nivel)
                                        <span class="recom-meta-badge recom-meta-badge--nivel">
                                            <i class="bi bi-bar-chart-steps me-1"></i>{{ $curso->nivel }}
                                        </span>
                                    @endif
                                    @if($curso->formato)
                                        <span class="recom-meta-badge recom-meta-badge--formato">
                                            <i class="bi bi-display me-1"></i>{{ $curso->formato }}
                                        </span>
                                    @endif
                                    @if($rating > 0)
                                        <span class="recom-meta-badge recom-meta-badge--rating">
                                            <i class="bi bi-star-fill me-1"></i>{{ $rating }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Categorías --}}
                                @if($curso->categorias->isNotEmpty())
                                <div class="recom-card-categories">
                                    @foreach($curso->categorias->take(2) as $cat)
                                        <span class="recom-cat-tag">{{ $cat->name }}</span>
                                    @endforeach
                                </div>
                                @endif
                            </div>

                            {{-- Footer --}}
                            <div class="recom-card-footer">
                                <div class="recom-card-price">
                                    @if($curso->precio > 0)
                                        <span class="recom-price">{{ number_format($curso->precio, 2) }} Bs</span>
                                    @else
                                        <span class="recom-price recom-price--free">Gratuito</span>
                                    @endif
                                </div>
                                <a href="{{ $curso->url }}"
                                   class="recom-card-btn"
                                   onclick="trackRecommendationClick({{ $curso->id }})">
                                    Ver curso <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- CTA final --}}
            <div class="text-center mt-5">
                <a href="{{ route('lista.cursos.congresos') }}" class="recom-explore-btn">
                    <i class="bi bi-compass me-2"></i> Explorar todos los cursos
                </a>
            </div>
        @endif

    </div>
</section>

{{-- ── Click Tracking Script ──────────────────────────────── --}}
<script>
function trackRecommendationClick(cursoId) {
    fetch('{{ route("recomendaciones.click") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ curso_id: cursoId })
    }).catch(() => {}); // fire-and-forget
}

// Animate cards on load
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.recom-card-col').forEach((col, i) => {
        col.classList.add('recom-animate-in');
    });
});
</script>

{{-- ── Scoped CSS ─────────────────────────────────────────── --}}
<style>
/* ═══════════════════════════════════════════════════════════
   RECOMMENDATION ENGINE — STYLES
   ═══════════════════════════════════════════════════════════ */

/* ── Hero ────────────────────────────────────────────────── */
.recom-hero-section {
    position: relative;
    padding: 4rem 0 3rem;
    overflow: hidden;
    background: linear-gradient(135deg, #0f0c29 0%, #1a1a4e 50%, #24243e 100%);
}

.recom-hero-bg {
    position: absolute;
    inset: 0;
    overflow: hidden;
    pointer-events: none;
}

.recom-hero-blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    opacity: 0.3;
}

.recom-hero-blob--left {
    width: 400px;
    height: 400px;
    background: #667eea;
    top: -100px;
    left: -100px;
}

.recom-hero-blob--right {
    width: 350px;
    height: 350px;
    background: #f093fb;
    bottom: -50px;
    right: -80px;
}

.recom-hero-chip {
    display: inline-block;
    padding: 0.4rem 1.2rem;
    border-radius: 50px;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    color: #c4b5fd;
    font-size: 0.85rem;
    font-weight: 500;
    letter-spacing: 0.5px;
    margin-bottom: 1.5rem;
    border: 1px solid rgba(196, 181, 253, 0.2);
}

.recom-hero-title {
    color: #fff;
    font-size: 2.5rem;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 1rem;
}

.recom-hero-highlight {
    background: linear-gradient(135deg, #667eea, #f093fb);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.recom-hero-subtitle {
    color: rgba(255,255,255,0.7);
    font-size: 1.05rem;
    line-height: 1.7;
    max-width: 600px;
    margin: 0 auto;
}

/* Profile stats strip */
.recom-profile-stats {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1.5rem;
    flex-wrap: wrap;
    padding: 1rem 1.5rem;
    border-radius: 16px;
    background: rgba(255,255,255,0.06);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.1);
}

.recom-stat-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: rgba(255,255,255,0.85);
    font-size: 0.9rem;
}

.recom-stat-item i {
    color: #c4b5fd;
    font-size: 1.1rem;
}

.recom-stat-item strong {
    color: #fff;
}

.recom-stat-divider {
    width: 1px;
    height: 20px;
    background: rgba(255,255,255,0.15);
}

/* ── Courses Section ─────────────────────────────────────── */
.recom-courses-section {
    padding: 3rem 0 5rem;
    background: #f8f9fc;
}

/* ── Course Card ─────────────────────────────────────────── */
.recom-card-col {
    opacity: 0;
    transform: translateY(24px);
    transition: opacity 0.5s ease, transform 0.5s ease;
}

.recom-card-col.recom-animate-in {
    opacity: 1;
    transform: translateY(0);
}

.recom-card {
    display: flex;
    flex-direction: column;
    height: 100%;
    border-radius: 16px;
    overflow: hidden;
    background: #fff;
    border: 1px solid rgba(0,0,0,0.06);
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.recom-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 32px rgba(102, 126, 234, 0.15);
}

.recom-card-img-wrapper {
    position: relative;
    height: 180px;
    overflow: hidden;
}

.recom-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.recom-card:hover .recom-card-img {
    transform: scale(1.06);
}

.recom-card-overlay {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 2;
}

.recom-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.3rem 0.7rem;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
    backdrop-filter: blur(8px);
}

.recom-badge--score {
    background: rgba(102, 126, 234, 0.9);
    color: #fff;
}

.recom-badge--tipo {
    position: absolute;
    top: 10px;
    left: 10px;
}

.recom-badge--curso {
    background: rgba(16, 185, 129, 0.9);
    color: #fff;
}

.recom-badge--congreso {
    background: rgba(245, 158, 11, 0.9);
    color: #fff;
}

.recom-card-body {
    flex: 1;
    padding: 1.2rem;
    display: flex;
    flex-direction: column;
}

.recom-card-reason {
    display: inline-flex;
    align-items: center;
    padding: 0.3rem 0.8rem;
    border-radius: 8px;
    background: linear-gradient(135deg, rgba(102,126,234,0.08), rgba(240,147,251,0.08));
    color: #667eea;
    font-size: 0.78rem;
    font-weight: 500;
    margin-bottom: 0.8rem;
    border: 1px solid rgba(102,126,234,0.15);
}

.recom-card-reason i {
    color: #f093fb;
}

.recom-card-title {
    font-size: 1.05rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.recom-card-title-link {
    color: #1e293b;
    text-decoration: none;
    transition: color 0.2s;
}

.recom-card-title-link:hover {
    color: #667eea;
}

.recom-card-desc {
    color: #64748b;
    font-size: 0.88rem;
    line-height: 1.6;
    margin-bottom: 0.8rem;
    flex: 1;
}

.recom-card-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.4rem;
    margin-bottom: 0.8rem;
}

.recom-meta-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.2rem 0.6rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 500;
    background: #f1f5f9;
    color: #475569;
}

.recom-meta-badge--rating {
    background: #fef3c7;
    color: #92400e;
}

.recom-card-categories {
    display: flex;
    gap: 0.4rem;
    flex-wrap: wrap;
}

.recom-cat-tag {
    padding: 0.15rem 0.5rem;
    border-radius: 4px;
    background: #ede9fe;
    color: #6d28d9;
    font-size: 0.72rem;
    font-weight: 500;
}

.recom-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.2rem;
    border-top: 1px solid rgba(0,0,0,0.05);
    background: #fafbfe;
}

.recom-price {
    font-weight: 700;
    font-size: 1rem;
    color: #1e293b;
}

.recom-price--free {
    color: #10b981;
}

.recom-card-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 10px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #fff;
    font-weight: 600;
    font-size: 0.85rem;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
}

.recom-card-btn:hover {
    background: linear-gradient(135deg, #5a6fd6, #6a4098);
    color: #fff;
    transform: translateX(3px);
}

/* ── Empty State ─────────────────────────────────────────── */
.recom-empty-state {
    text-align: center;
    padding: 5rem 2rem;
}

.recom-empty-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #f093fb);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
}

.recom-empty-icon i {
    font-size: 2rem;
    color: #fff;
}

.recom-empty-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.recom-empty-text {
    color: #64748b;
    font-size: 1rem;
    max-width: 500px;
    margin: 0 auto 1.5rem;
}

.recom-empty-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.7rem 1.5rem;
    border-radius: 12px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #fff;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s;
}

.recom-empty-btn:hover {
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
}

/* ── Explore Button ──────────────────────────────────────── */
.recom-explore-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.8rem 2rem;
    border-radius: 12px;
    background: #fff;
    color: #667eea;
    font-weight: 600;
    text-decoration: none;
    border: 2px solid #667eea;
    transition: all 0.3s;
}

.recom-explore-btn:hover {
    background: #667eea;
    color: #fff;
    transform: translateY(-2px);
}

/* ── Responsive ──────────────────────────────────────────── */
@media (max-width: 768px) {
    .recom-hero-title {
        font-size: 1.8rem;
    }

    .recom-hero-section {
        padding: 3rem 0 2rem;
    }

    .recom-profile-stats {
        flex-direction: column;
        gap: 0.8rem;
    }

    .recom-stat-divider {
        width: 40px;
        height: 1px;
    }
}
</style>

@endsection
