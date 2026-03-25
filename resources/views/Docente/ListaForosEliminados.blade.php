
@extends('layout')

@section('titulo', 'Foros Eliminados')

@section('content')

<div class="container-fluid py-4">
<div class="tbl-card">

    {{-- ╔══════════════════════════════════════╗
         ║  HERO                               ║
         FIX 1+2+3
         ╚══════════════════════════════════════╝ --}}
    <div class="tbl-card-hero">

        <div class="tbl-hero-left">
            {{-- FIX 1: botón volver dentro del hero --}}
            <a href="{{ route('Curso', $cursos->codigoCurso) }}"
               class="tbl-hero-btn tbl-hero-btn-glass prt-back-btn mb-2">
                <i class="bi bi-arrow-left-circle-fill"></i> Volver al Curso
            </a>
            {{-- FIX 3: tbl-hero-eyebrow --}}
            <div class="tbl-hero-eyebrow">
                <i class="bi bi-chat-left-dots-fill"></i> Gestión
            </div>
            <h2 class="tbl-hero-title">Foros Eliminados</h2>
            <p class="tbl-hero-sub">
                Curso: <strong>{{ $cursos->nombreCurso }}</strong>
            </p>
        </div>

        <div class="tbl-hero-controls">
            <div class="tbl-hero-eyebrow">
                <i class="bi bi-trash-fill"></i>
                {{ $foro->where('cursos_id', $cursos->id)->count() }} foros eliminados
            </div>

            {{-- Buscador -- FIX 5 -- --}}
            <div class="tbl-hero-search">
                <i class="bi bi-search tbl-hero-search-icon"></i>
                <input type="text"
                       class="tbl-hero-search-input"
                       id="feSearch"
                       placeholder="Buscar foro..."
                       autocomplete="off">
            </div>
        </div>

    </div>{{-- /tbl-card-hero --}}


    {{-- FIX 4: tbl-filter-bar sin Bootstrap helpers --}}
    <div class="tbl-filter-bar">
        <div class="tbl-filter-bar-left">
            <i class="bi bi-info-circle-fill" style="color:#145da0"></i>
            <span style="font-size:.82rem;color:#374151">
                Los foros restaurados volverán a ser visibles en el curso.
            </span>
        </div>
    </div>

    {{-- ╔══════════════════════════════════════╗
         ║  TABLA                              ║
         FIX 13: sin shadow-none border-0 p-0
         ╚══════════════════════════════════════╝ --}}
    <div class="table-container-modern">
        <table class="table-modern" id="feTable">
            <thead>
                <tr>
                    <th style="width:5%">
                        <div class="th-content"><i class="bi bi-hash"></i></div>
                    </th>
                    <th style="width:42%">
                        <div class="th-content">
                            <i class="bi bi-chat-left-text-fill"></i><span>Información del Foro</span>
                        </div>
                    </th>
                    <th style="width:20%">
                        <div class="th-content">
                            <i class="bi bi-calendar-x-fill"></i><span>Eliminado</span>
                        </div>
                    </th>
                    <th style="width:15%" class="text-center">
                        <div class="th-content justify-content-center">
                            <i class="bi bi-info-circle-fill"></i><span>Estado</span>
                        </div>
                    </th>
                    <th style="width:18%" class="text-center">
                        <div class="th-content justify-content-center">
                            <i class="bi bi-gear-fill"></i><span>Acciones</span>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($foro as $foroItem)
                @if($foroItem->cursos_id == $cursos->id)
                <tr class="fe-row" data-foro-name="{{ strtolower($foroItem->nombreForo) }}">

                    {{-- FIX 14: row-number del sistema --}}
                    <td><span class="row-number">{{ $loop->iteration }}</span></td>

                    {{-- Info del foro --}}
                    <td>
                        <div class="fe-foro-info">
                            <div class="fe-foro-name">{{ $foroItem->nombreForo }}</div>
                            @if($foroItem->SubtituloForo)
                            <div class="fe-foro-sub">{{ $foroItem->SubtituloForo }}</div>
                            @endif
                            <div class="fe-foro-meta">
                                <i class="bi bi-clock-history"></i>
                                Creado: {{ $foroItem->created_at->format('d/m/Y') }}
                            </div>
                        </div>
                    </td>

                    {{-- Fecha eliminación -- FIX 6: status-badge status-secondary no existe --}}
                    <td>
                        <span class="date-badge date-end">
                            <i class="bi bi-calendar-event"></i>
                            {{ $foroItem->deleted_at
                                ? $foroItem->deleted_at->format('d/m/Y H:i')
                                : 'N/A' }}
                        </span>
                    </td>

                    {{-- Estado --}}
                    <td class="text-center">
                        <span class="status-badge status-danger">
                            <i class="bi bi-trash-fill"></i> Eliminado
                        </span>
                    </td>

                    {{-- Acciones --}}
                    <td>
                        <div class="action-buttons-cell">

                            {{-- FIX 9: sin d-inline --}}
                            <form action="{{ route('restaurar', encrypt($foroItem->id)) }}"
                                  method="GET"
                                  class="fe-form-restaurar">
                                <button type="submit"
                                        class="btn-action-modern btn-restore-fe"
                                        title="Restaurar foro">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </button>
                            </form>

                            <button class="btn-action-modern btn-info"
                                    data-bs-toggle="modal"
                                    data-bs-target="#feModal{{ $foroItem->id }}"
                                    title="Ver detalles">
                                <i class="bi bi-eye-fill"></i>
                            </button>

                        </div>
                    </td>

                </tr>
                @endif
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state-table">
                            <div class="empty-icon-table"><i class="bi bi-chat-left-x"></i></div>
                            <h5 class="empty-title-table">No hay foros eliminados</h5>
                            <p class="empty-text-table">Los foros que elimines aparecerán aquí.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>{{-- /tbl-card --}}
</div>{{-- /container-fluid --}}


{{-- ╔══════════════════════════════════════╗
     ║  MODALES DETALLE                    ║
     FIX 7: fuera del @section, no en @push('modals')
     FIX 8: cc-modal del sistema
     ╚══════════════════════════════════════╝ --}}
@foreach($foro as $foroItem)
@if($foroItem->cursos_id == $cursos->id)
<div class="modal fade" id="feModal{{ $foroItem->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content cc-modal">

            <div class="cc-modal-header" style="background:linear-gradient(135deg,#0d2244,#145da0)">
                <div class="cc-modal-icon">
                    <i class="bi bi-chat-left-text-fill"></i>
                </div>
                <div>
                    <h5 class="cc-modal-title">Detalles del Foro</h5>
                    <small class="opacity-75">{{ $foroItem->nombreForo }}</small>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <div class="lc-detail-grid">

                    <div class="lc-detail-item" style="grid-column:1/-1">
                        <div class="lc-detail-icon"><i class="bi bi-chat-left-fill"></i></div>
                        <div>
                            <div class="lc-detail-label">Nombre del Foro</div>
                            <div class="lc-detail-val">{{ $foroItem->nombreForo }}</div>
                        </div>
                    </div>

                    @if($foroItem->SubtituloForo)
                    <div class="lc-detail-item" style="grid-column:1/-1">
                        <div class="lc-detail-icon"><i class="bi bi-fonts"></i></div>
                        <div>
                            <div class="lc-detail-label">Subtítulo</div>
                            <div class="lc-detail-val">{{ $foroItem->SubtituloForo }}</div>
                        </div>
                    </div>
                    @endif

                    <div class="lc-detail-item" style="grid-column:1/-1">
                        <div class="lc-detail-icon"><i class="bi bi-file-text-fill"></i></div>
                        <div style="flex:1">
                            <div class="lc-detail-label">Descripción</div>
                            <div class="lc-detail-val" style="white-space:normal;line-height:1.5">
                                {{ $foroItem->descripcionForo ?: 'Sin descripción' }}
                            </div>
                        </div>
                    </div>

                    <div class="lc-detail-item">
                        <div class="lc-detail-icon"><i class="bi bi-calendar-check-fill"></i></div>
                        <div>
                            <div class="lc-detail-label">Creado</div>
                            <div class="lc-detail-val">{{ $foroItem->created_at->format('d/m/Y') }}</div>
                        </div>
                    </div>

                    <div class="lc-detail-item">
                        <div class="lc-detail-icon" style="background:rgba(220,38,38,.10);color:#dc2626">
                            <i class="bi bi-trash-fill"></i>
                        </div>
                        <div>
                            <div class="lc-detail-label">Eliminado</div>
                            <div class="lc-detail-val">
                                {{ $foroItem->deleted_at
                                    ? $foroItem->deleted_at->format('d/m/Y H:i')
                                    : 'N/A' }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="cc-modal-footer">
                <button type="button" class="cc-btn cc-btn-outline" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cerrar
                </button>
                {{-- FIX 9: sin d-inline --}}
                <form action="{{ route('restaurar', encrypt($foroItem->id)) }}"
                      method="GET"
                      class="fe-form-restaurar">
                    <button type="submit" class="cc-btn cc-btn-primary">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Restaurar Foro
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endif
@endforeach

@endsection


<style>
/* ── Celda info del foro ── */
.fe-foro-info  { display: flex; flex-direction: column; gap: .15rem; }
.fe-foro-name  { font-size: .88rem; font-weight: 700; color: #0f172a; }
.fe-foro-sub   { font-size: .78rem; color: #64748b; }
.fe-foro-meta  {
    font-size:   .72rem;
    color:       #94a3b8;
    display:     flex;
    align-items: center;
    gap:         .28rem;
}

/* ── status-danger (puede no estar en table.css) ── */
.status-danger { background: rgba(220,38,38,.10); color: #dc2626; }

/* ── Botón restaurar: azul del sistema ── */
.btn-restore-fe {
    background: rgba(20,93,160,.09);
    color:      #145da0;
}
.btn-restore-fe:hover {
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
        document.getElementById('feSearch')
            ?.addEventListener('input', function () {
                const q = this.value.toLowerCase().trim();
                document.querySelectorAll('.fe-row').forEach(row => {
                    const name = row.getAttribute('data-foro-name') || '';
                    row.style.display = name.includes(q) ? '' : 'none';
                });
            });

        /* Confirmación restaurar */
        document.querySelectorAll('.fe-form-restaurar').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const f = this;
                Swal.fire({
                    title            : '¿Restaurar este foro?',
                    text             : 'El foro volverá a estar disponible en el curso.',
                    icon             : 'question',
                    showCancelButton : true,
                    confirmButtonColor: '#145da0',   /* FIX 11 */
                    cancelButtonColor : '#94a3b8',
                    confirmButtonText : 'Sí, restaurar',
                    cancelButtonText  : 'Cancelar',
                    reverseButtons    : true,
                }).then(r => { if (r.isConfirmed) f.submit(); });
            });
        });

        /* Tooltips */
        document.querySelectorAll('[title]').forEach(el => {
            if (el.getAttribute('data-bs-toggle') !== 'modal')
                new bootstrap.Tooltip(el, { trigger: 'hover' });
        });

    });
})();
</script>