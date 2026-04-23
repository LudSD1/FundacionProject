{{--
    Widget compacto de recomendaciones.
    Uso: @include('partials._recomendaciones_widget')
    Requiere: usuario autenticado con rol Estudiante.
--}}

@auth
@if(auth()->user()->hasRole('Estudiante'))
@php
    $recomService = app(\App\Services\RecommendationService::class);
    $widgetRecs = $recomService->getRecommendations(auth()->user(), 3);
@endphp

@if($widgetRecs->isNotEmpty())
<div class="recom-widget">
    <div class="recom-widget-header">
        <h5 class="recom-widget-title">
            <i class="bi bi-stars me-2"></i>Recomendados para ti
        </h5>
        <a href="{{ route('recomendaciones.index') }}" class="recom-widget-seeall">
            Ver todos <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="recom-widget-grid">
        @foreach($widgetRecs as $rec)
            @php
                $wImg = $rec->imagen
                    ? asset('storage/' . $rec->imagen)
                    : asset('assets/img/bg2.png');
            @endphp
            <a href="{{ $rec->url }}" class="recom-widget-card" onclick="typeof trackRecommendationClick === 'function' && trackRecommendationClick({{ $rec->id }})">
                <div class="recom-widget-card-img" style="background-image: url('{{ $wImg }}')">
                    <span class="recom-widget-score">{{ $rec->recommendation_score }}%</span>
                </div>
                <div class="recom-widget-card-body">
                    <h6 class="recom-widget-card-title">{{ \Illuminate\Support\Str::limit($rec->nombreCurso, 45) }}</h6>
                    <span class="recom-widget-reason">
                        <i class="bi bi-magic me-1"></i>{{ $rec->recommendation_reason ?? 'Recomendado' }}
                    </span>
                </div>
            </a>
        @endforeach
    </div>
</div>

<style>
.recom-widget {
    border-radius: 16px;
    background: #fff;
    border: 1px solid rgba(0,0,0,0.06);
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    overflow: hidden;
    margin-bottom: 1.5rem;
}

.recom-widget-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.2rem;
    background: linear-gradient(135deg, #0f0c29, #1a1a4e);
}

.recom-widget-title {
    color: #fff;
    font-size: 0.95rem;
    font-weight: 600;
    margin: 0;
}

.recom-widget-seeall {
    color: #c4b5fd;
    font-size: 0.82rem;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
}

.recom-widget-seeall:hover {
    color: #f093fb;
}

.recom-widget-grid {
    padding: 0.8rem;
    display: flex;
    flex-direction: column;
    gap: 0.6rem;
}

.recom-widget-card {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    padding: 0.6rem;
    border-radius: 12px;
    text-decoration: none;
    transition: background 0.2s, transform 0.2s;
}

.recom-widget-card:hover {
    background: #f8f9fc;
    transform: translateX(4px);
}

.recom-widget-card-img {
    width: 60px;
    height: 60px;
    min-width: 60px;
    border-radius: 10px;
    background-size: cover;
    background-position: center;
    position: relative;
}

.recom-widget-score {
    position: absolute;
    bottom: -4px;
    right: -4px;
    padding: 0.1rem 0.4rem;
    border-radius: 6px;
    background: rgba(102, 126, 234, 0.9);
    color: #fff;
    font-size: 0.65rem;
    font-weight: 700;
}

.recom-widget-card-body {
    flex: 1;
    min-width: 0;
}

.recom-widget-card-title {
    font-size: 0.85rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 0.2rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.recom-widget-reason {
    font-size: 0.72rem;
    color: #667eea;
    display: flex;
    align-items: center;
}
</style>
@endif
@endif
@endauth
