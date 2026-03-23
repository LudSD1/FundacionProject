@extends('layout')

@section('titulo', 'Boletín: ' . $inscritos->estudiantes->name)

@section('content')
<div class="container my-4">
    <div class="tbl-card">

        {{-- ╔══════════════════════════════════════╗
             ║  HERO — CABECERA AZUL               ║
             ╚══════════════════════════════════════╝ --}}
        <div class="tbl-card-hero">
            <div class="tbl-hero-left">
                <a href="{{ route('listacurso', ['id' => encrypt($inscritos->cursos_id)]) }}"
                   class="tbl-hero-btn tbl-hero-btn-glass prt-back-btn mb-2">
                    <i class="bi bi-arrow-left-circle-fill"></i> Volver a Lista
                </a>
                <div class="tbl-hero-eyebrow">
                    <i class="bi bi-journal-check"></i> Reporte de Calificaciones
                </div>
                <h2 class="tbl-hero-title">Boletín del Estudiante</h2>
                <p class="tbl-hero-sub">
                    Participante: <strong>{{ $inscritos->estudiantes->name }} {{ $inscritos->estudiantes->lastname1 }} {{ $inscritos->estudiantes->lastname2 }}</strong>
                </p>
            </div>

            <div class="tbl-hero-controls text-end">
                <div class="tbl-avatar bg-white text-primary mb-2 mx-auto ms-md-auto" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    {{ strtoupper(substr($inscritos->estudiantes->name, 0, 1)) }}
                </div>
                <div class="text-white opacity-75 small">
                    <i class="bi bi-calendar3 me-1"></i> {{ now()->format('d/m/Y') }}
                </div>
            </div>
        </div>{{-- /tbl-card-hero --}}

        {{-- Tarjetas de Estadísticas Rápidas --}}
        <div class="row g-3 p-3 bg-light border-bottom">
            <div class="col-md-3">
                <div class="st-card st-card--blue">
                    <div class="st-card-body">
                        <div>
                            <div class="st-label">Promedio Actividades</div>
                            <div class="st-num">{{ $resumen['promedio_actividades'] }}</div>
                        </div>
                        <div class="st-icon st-icon--blue">
                            <i class="bi bi-graph-up"></i>
                        </div>
                    </div>
                    <div class="st-bar st-bar--blue"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="st-card st-card--green">
                    <div class="st-card-body">
                        <div>
                            <div class="st-label">Asistencia</div>
                            <div class="st-num">{{ $resumen['porcentaje_asistencia'] }}%</div>
                        </div>
                        <div class="st-icon st-icon--green">
                            <i class="bi bi-person-check-fill"></i>
                        </div>
                    </div>
                    <div class="st-bar st-bar--green"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="st-card st-card--orange">
                    <div class="st-card-body">
                        <div>
                            <div class="st-label">Nota Final</div>
                            <div class="st-num">{{ $resumen['nota_final'] }}</div>
                        </div>
                        <div class="st-icon st-icon--orange">
                            <i class="bi bi-award-fill"></i>
                        </div>
                    </div>
                    <div class="st-bar st-bar--orange"></div>
                </div>
            </div>
            <div class="col-md-3">
                @php
                    $isAprobado = strtolower($resumen['estado']) == 'aprobado';
                    $stateColor = $isAprobado ? 'green' : 'red';
                    $stateIcon = $isAprobado ? 'bi-check-circle-fill' : 'bi-x-circle-fill';
                @endphp
                <div class="st-card st-card--{{ $stateColor }}">
                    <div class="st-card-body">
                        <div>
                            <div class="st-label">Estado</div>
                            <div class="st-num" style="font-size: 1.2rem;">{{ strtoupper($resumen['estado']) }}</div>
                        </div>
                        <div class="st-icon st-icon--{{ $stateColor }}">
                            <i class="bi {{ $stateIcon }}"></i>
                        </div>
                    </div>
                    <div class="st-bar st-bar--{{ $stateColor }}"></div>
                </div>
            </div>
        </div>

        {{-- Formulario de Comentarios --}}
        <div class="p-4 bg-white border-bottom">
            <form method="POST" action="{{ route('boletinPost' , encrypt($inscritos->id)) }}" id="boletinForm">
                @csrf
                <input type="hidden" name="estudiante" value="{{ $inscritos->id }}">
                <input type="hidden" name="notafinal" value="{{ $resumen['nota_final'] }}">

                <div class="row align-items-center">
                    <div class="col-md-9">
                        <div class="form-group">
                            <label for="comentario" class="form-label fw-bold text-muted small mb-2">
                                <i class="bi bi-chat-left-dots me-1"></i> COMENTARIO DEL DOCENTE
                            </label>
                            <textarea name="comentario" id="comentario" class="form-control border-light-subtle bg-light"
                                      rows="2" placeholder="Escriba aquí sus observaciones sobre el desempeño del estudiante..." required></textarea>
                        </div>
                    </div>
                    <div class="col-md-3 text-center text-md-end mt-3 mt-md-0">
                        <button type="submit" class="tbl-hero-btn tbl-hero-btn-primary w-100">
                            <i class="bi bi-save-fill"></i> Guardar Boletín
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Detalle de Actividades --}}
        <div class="p-0">
            <div class="p-3 bg-light-subtle border-bottom">
                <h5 class="mb-0 text-primary small fw-bold">
                    <i class="bi bi-list-stars me-2"></i>DESGLOSE DE ACTIVIDADES
                </h5>
            </div>
            <div class="table-container-modern">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th width="20%"><div class="th-content"><i class="bi bi-bookmark-fill"></i><span>Tema / Subtema</span></div></th>
                            <th width="30%"><div class="th-content"><i class="bi bi-activity"></i><span>Actividad</span></div></th>
                            <th width="15%"><div class="th-content"><i class="bi bi-tags"></i><span>Tipo</span></div></th>
                            <th width="10%" class="text-center"><div class="th-content justify-content-center"><i class="bi bi-star-fill"></i><span>Nota</span></div></th>
                            <th width="15%" class="text-center"><div class="th-content justify-content-center"><i class="bi bi-info-circle"></i><span>Estado</span></div></th>
                            <th width="10%" class="text-end"><div class="th-content justify-content-end"><i class="bi bi-calendar"></i><span>Fecha</span></div></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($actividadesData as $actividad)
                            <tr>
                                <td>
                                    <div class="small fw-bold text-dark">{{ $actividad['tema'] }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">{{ $actividad['subtema'] }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $actividad['actividad'] }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-primary border rounded-pill px-3">
                                        {{ $actividad['tipo'] }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold fs-5 {{ $actividad['nota'] >= 70 ? 'text-success' : 'text-danger' }}">
                                        {{ $actividad['nota'] }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $statusClass = match(strtolower($actividad['estado'])) {
                                            'entregado', 'completado' => 'status-active',
                                            'pendiente' => 'status-pending',
                                            default => 'status-danger'
                                        };
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">
                                        {{ ucfirst($actividad['estado']) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="small text-muted">{{ $actividad['fecha'] }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state-table">
                                        <div class="empty-icon-table"><i class="bi bi-clipboard-x"></i></div>
                                        <h5 class="empty-title-table">Sin actividades</h5>
                                        <p class="empty-text-table">No hay registros de actividades para este estudiante.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
