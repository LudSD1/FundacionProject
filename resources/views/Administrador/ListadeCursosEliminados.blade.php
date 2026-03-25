@extends('layout')

@section('titulo') Cursos Eliminados @endsection

@section('content')

<div class="container my-4">
<div class="tbl-card">

    {{-- ╔══════════════════════════════════════╗
         ║  HERO                               ║
         ╚══════════════════════════════════════╝ --}}
    <div class="tbl-card-hero">

        <div class="tbl-hero-left">
            <div class="tbl-hero-eyebrow">
                <i class="bi bi-trash-fill"></i> Administración
            </div>
            <h2 class="tbl-hero-title">Cursos Eliminados</h2>
            <p class="tbl-hero-sub">Restaura cursos que fueron eliminados anteriormente</p>
        </div>

        <div class="tbl-hero-controls">
            @if(auth()->user()->hasRole('Administrador'))
            <a href="{{ route('CrearCurso') }}" class="tbl-hero-btn tbl-hero-btn-primary">
                <i class="bi bi-plus-circle-fill"></i>
                <span>Crear Curso</span>
            </a>
            <a href="{{ route('ListadeCursos') }}" class="tbl-hero-btn tbl-hero-btn-glass">
                <i class="bi bi-arrow-left-circle-fill"></i>
                <span>Volver a Cursos</span>
            </a>
            @endif

            {{-- Buscador --}}
            <div class="tbl-hero-search">
                <i class="bi bi-search tbl-hero-search-icon"></i>
                <input type="text"
                       class="tbl-hero-search-input"
                       placeholder="Buscar curso..."
                       id="ceSearchInput">
            </div>
        </div>

    </div>{{-- /tbl-card-hero --}}


    {{-- ╔══════════════════════════════════════╗
         ║  TABLA (solo Admin)                 ║
         ╚══════════════════════════════════════╝ --}}
    @if(auth()->user()->hasRole('Administrador'))
    <div class="table-container-modern">
        <table class="table-modern" id="ceTable">
            <thead>
                <tr>
                    <th width="5%">
                        <div class="th-content"><i class="bi bi-hash"></i><span>Nº</span></div>
                    </th>
                    <th width="20%">
                        <div class="th-content"><i class="bi bi-book-fill"></i><span>Nombre Curso</span></div>
                    </th>
                    <th width="15%">
                        <div class="th-content"><i class="bi bi-person-fill"></i><span>Docente</span></div>
                    </th>
                    <th width="10%">
                        <div class="th-content"><i class="bi bi-calendar-check"></i><span>Inicio</span></div>
                    </th>
                    <th width="10%">
                        <div class="th-content"><i class="bi bi-calendar-x"></i><span>Fin</span></div>
                    </th>
                    <th width="10%">
                        <div class="th-content"><i class="bi bi-display"></i><span>Formato</span></div>
                    </th>
                    <th width="10%">
                        <div class="th-content"><i class="bi bi-tags-fill"></i><span>Tipo</span></div>
                    </th>
                    <th width="20%" class="text-center">
                        <div class="th-content justify-content-center">
                            <i class="bi bi-gear-fill"></i><span>Acciones</span>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($cursos as $curso)
                @php
                    $tipoSlug  = strtolower($curso->tipo ?? 'curso');
                    $tipoLabel = $tipoSlug === 'congreso' ? 'Evento' : ucfirst($tipoSlug);
                    $tipoIcon  = $tipoSlug === 'congreso' ? 'bi-calendar-event-fill' : 'bi-mortarboard-fill';
                @endphp
                <tr class="curso-row" data-course-id="{{ $curso->id }}">

                    <td><span class="row-number">{{ $loop->iteration }}</span></td>

                    <td>
                        {{-- FIX 7: modal movido fuera del tbody, aquí solo el trigger --}}
                        <div class="course-name-cell"
                             data-bs-toggle="modal"
                             data-bs-target="#ceModal{{ $curso->id }}">
                            <i class="bi bi-journal-bookmark-fill course-icon"></i>
                            <span class="course-name">
                                {{ ucfirst(strtolower($curso->nombreCurso)) }}
                            </span>
                        </div>
                    </td>

                    <td>
                        <div class="teacher-cell">
                            <i class="bi bi-person-badge"></i>
                            <span>{{ $curso->docente
                                ? $curso->docente->name.' '.$curso->docente->lastname1.' '.$curso->docente->lastname2
                                : 'N/A' }}</span>
                        </div>
                    </td>

                    <td>
                        <span class="date-badge date-start">
                            <i class="bi bi-calendar-event"></i>
                            {{ $curso->fecha_ini ?? 'N/A' }}
                        </span>
                    </td>

                    <td>
                        <span class="date-badge date-end">
                            <i class="bi bi-calendar-event"></i>
                            {{ $curso->fecha_fin ?? 'N/A' }}
                        </span>
                    </td>

                    <td>
                        <span class="format-badge">
                            <i class="bi bi-laptop"></i>
                            {{ $curso->formato ?? 'N/A' }}
                        </span>
                    </td>

                    <td>
                        <span class="type-badge type-{{ $tipoSlug }}">
                            <i class="bi {{ $tipoIcon }}"></i>
                            {{ $tipoLabel }}
                        </span>
                    </td>

                    <td>
                        <div class="action-buttons-cell">
                            {{-- FIX 10: codigoCurso igual que el resto del proyecto --}}
                            <a class="btn-action-modern btn-view"
                               href="{{ route('Curso', $curso->codigoCurso) }}"
                               data-bs-toggle="tooltip"
                               title="Ver Curso">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            {{-- FIX 8: btn-info para Detalles --}}
                            <button class="btn-action-modern btn-info"
                                    data-bs-toggle="modal"
                                    data-bs-target="#ceModal{{ $curso->id }}"
                                    title="Detalles">
                                <i class="bi bi-info-circle-fill"></i>
                            </button>
                            {{-- FIX 6: btn-view (verde) para Restaurar, no btn-delete --}}
                            <a class="btn-action-modern btn-restore-ce"
                               href="{{ route('restaurarCurso', encrypt($curso->id)) }}"
                               data-bs-toggle="tooltip"
                               title="Restaurar Curso">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state-table">
                            <div class="empty-icon-table"><i class="bi bi-trash"></i></div>
                            <h5 class="empty-title-table">No hay cursos eliminados</h5>
                            <p class="empty-text-table">Los cursos que elimines aparecerán aquí para poder restaurarlos.</p>
                            <a href="{{ route('CrearCurso') }}"
                               class="tbl-hero-btn tbl-hero-btn-primary"
                               style="width:auto;margin:0 auto">
                                <i class="bi bi-plus-circle-fill"></i> Crear Curso
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif

</div>{{-- /tbl-card --}}
</div>{{-- /container --}}


{{-- FIX 7: modales fuera del tbody — HTML válido --}}
@if(auth()->user()->hasRole('Administrador'))
@foreach($cursos as $curso)
<div class="modal fade" id="ceModal{{ $curso->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content cc-modal">

            {{-- FIX 5: cc-modal-header del sistema --}}
            <div class="cc-modal-header">
                <div class="cc-modal-icon">
                    <i class="bi bi-book-half"></i>
                </div>
                <div>
                    <h5 class="cc-modal-title">Detalles del Curso</h5>
                    <small class="opacity-75">{{ ucfirst(strtolower($curso->nombreCurso)) }}</small>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <div class="lc-detail-grid">
                    @php
                        $detalles = [
                            ['bi-bookmark-star-fill',  'Nombre',        ucfirst(strtolower($curso->nombreCurso))],
                            ['bi-bar-chart-fill',      'Nivel',         $curso->nivel ? ucfirst(strtolower($curso->nivel)) : 'N/A'],
                            ['bi-person-badge-fill',   'Instructor',    $curso->docente ? $curso->docente->name.' '.$curso->docente->lastname1.' '.$curso->docente->lastname2 : 'N/A'],
                            ['bi-people-fill',         'Edad Dirigida', $curso->edad_dirigida ? ucfirst(strtolower($curso->edad_dirigida)) : 'N/A'],
                            ['bi-calendar-check-fill', 'Fecha Inicio',  $curso->fecha_ini ?? 'N/A'],
                            ['bi-calendar-x-fill',     'Fecha Fin',     $curso->fecha_fin ?? 'N/A'],
                            ['bi-display-fill',        'Formato',       $curso->formato ?? 'N/A'],
                            ['bi-tags-fill',           'Tipo',          ucfirst(strtolower($curso->tipo ?? 'N/A'))],
                        ];
                    @endphp
                    @foreach($detalles as [$icon, $label, $val])
                    <div class="lc-detail-item">
                        <div class="lc-detail-icon"><i class="bi {{ $icon }}"></i></div>
                        <div>
                            <div class="lc-detail-label">{{ $label }}</div>
                            <div class="lc-detail-val">{{ $val }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- FIX 5: cc-modal-footer del sistema --}}
            <div class="cc-modal-footer">
                <button type="button" class="cc-btn cc-btn-outline" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cerrar
                </button>
                <a href="{{ route('restaurarCurso', encrypt($curso->id)) }}"
                   class="cc-btn cc-btn-primary">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>Restaurar
                </a>
            </div>

        </div>
    </div>
</div>
@endforeach
@endif

@endsection


<style>
/* btn-restore-ce: verde para restaurar (semánticamente correcto) */
.btn-restore-ce {
    background: rgba(20,93,160,.09);
    color:      #145da0;
}
.btn-restore-ce:hover {
    background: #145da0;
    color:      #fff;
    box-shadow: 0 4px 10px rgba(20,93,160,.28);
    transform:  scale(1.15);
}
</style>
<script>
(function () {
    document.addEventListener('DOMContentLoaded', function () {

        /* Búsqueda client-side */
        document.getElementById('ceSearchInput')
            ?.addEventListener('input', function () {
                const q = this.value.toLowerCase();
                document.querySelectorAll('#ceTable tbody tr').forEach(row => {
                    row.style.display =
                        row.textContent.toLowerCase().includes(q) ? '' : 'none';
                });
            });

        /* Tooltips */
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
            new bootstrap.Tooltip(el);
        });

    });
})();
</script>