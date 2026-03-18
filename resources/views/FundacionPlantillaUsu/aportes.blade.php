@extends('estudiante.index')

@section('title', 'Pagos — Historial de Aportes')

@section('content')

<div class="pag-wrap">
    <div class="container pag-container">
        <div class="pag-hero">
            <div class="pag-hero-overlay"></div>
            <div class="pag-hero-body">
                <div>
                    <div class="pag-hero-eyebrow">
                        <i class="bi bi-credit-card-2-front"></i> Mi Cuenta
                    </div>
                    <h2 class="pag-hero-title">Historial de Pagos</h2>
                    <p class="pag-hero-sub">Consulta y descarga tus recibos y facturas</p>
                </div>
                @php
                    $totalAportes = $aportes->where('estudiante_id', auth()->user()->id)->count();
                @endphp
                <div class="pag-hero-count">
                    <span class="pag-hero-count-num">{{ $totalAportes }}</span>
                    <span class="pag-hero-count-lbl">
                        pago{{ $totalAportes !== 1 ? 's' : '' }} registrado{{ $totalAportes !== 1 ? 's' : '' }}
                    </span>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="pag-alert-success" role="alert" id="pagFlashAlert">
            <i class="bi bi-check-circle-fill pag-alert-icon"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="pag-alert-close" onclick="document.getElementById('pagFlashAlert').remove()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        @endif

        <div class="pag-card">

            <div class="pag-card-header">
                <div class="pag-card-header-left">
                    <div class="pag-card-header-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <h3 class="pag-card-title">Historial de Aportes</h3>
                        <p class="pag-card-sub">Todos tus pagos realizados</p>
                    </div>
                </div>
            </div>

            <div class="pag-table-wrap">
                <table class="pag-table">
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th>Monto</th>
                            <th>Descripción</th>
                            <th class="pag-th-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($aportes as $aporte)
                        @if($aporte->estudiante_id == auth()->user()->id)

                        <tr class="pag-row">
                            {{-- Estudiante --}}
                            <td class="pag-td">
                                <div class="pag-student">
                                    <div class="pag-avatar">
                                        {{ strtoupper(substr($aporte->datosEstudiante ?? 'E', 0, 1)) }}
                                    </div>
                                    <span class="pag-student-name">{{ $aporte->datosEstudiante }}</span>
                                </div>
                            </td>

                            {{-- Monto --}}
                            <td class="pag-td">
                                <span class="pag-monto">
                                    {{ number_format($aporte->monto, 2) }}
                                    <span class="pag-monto-cur">Bs.</span>
                                </span>
                            </td>

                            {{-- Descripción --}}
                            <td class="pag-td">
                                <span class="pag-desc">{{ $aporte->DescripcionDelPago }}</span>
                            </td>

                            {{-- Acciones --}}
                            <td class="pag-td pag-td-actions">
                                <a href="{{ route('recibo.generar', encrypt($aporte->id)) }}"
                                   target="_blank"
                                   class="cc-btn cc-btn-sm pag-btn-recibo">
                                    <i class="bi bi-file-earmark-text me-1"></i>Recibo
                                </a>
                                <a href="{{ route('factura.siat', encrypt($aporte->id)) }}"
                                   target="_blank"
                                   class="cc-btn cc-btn-sm pag-btn-factura">
                                    <i class="bi bi-receipt me-1"></i>Factura
                                </a>
                            </td>
                        </tr>

                        @endif
                        @empty

                        {{-- Estado vacío --}}
                        <tr>
                            <td colspan="4" class="pag-empty-td">
                                <div class="pag-empty">
                                    <div class="pag-empty-icon"><i class="bi bi-inbox"></i></div>
                                    <h5 class="pag-empty-title">Sin pagos registrados</h5>
                                    <p class="pag-empty-sub">Aún no se ha realizado ningún pago.</p>
                                </div>
                            </td>
                        </tr>

                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            @if($aportes->hasPages())
            <div class="pag-pagination-wrap">
                {{ $aportes->links() }}
            </div>
            @endif

        </div>{{-- /pag-card --}}
    </div>
</div>

@endsection


<script>
    (function () {
        document.addEventListener('DOMContentLoaded', function () {

            /* ── Reenviar recibo (si aplica) ── */
            document.querySelectorAll('.reenviar-recibo').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const aporteId = this.getAttribute('data-id');

                    Swal.fire({
                        title            : '¿Reenviar recibo?',
                        text             : 'Se enviará el recibo al correo registrado.',
                        icon             : 'question',
                        showCancelButton : true,
                        confirmButtonText: 'Sí, reenviar',
                        cancelButtonText : 'Cancelar',
                        // FIX 12: colores del sistema
                        confirmButtonColor: '#145da0',
                        cancelButtonColor : '#94a3b8',
                    }).then(result => {
                        if (!result.isConfirmed) return;

                        fetch(`/aportes/${aporteId}/reenviar-recibo`, {
                            method : 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                                'Accept'      : 'application/json',
                            },
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon             : 'success',
                                    title            : '¡Enviado!',
                                    text             : data.message,
                                    confirmButtonColor: '#145da0',
                                });
                            } else {
                                Swal.fire({
                                    icon             : 'error',
                                    title            : 'Error',
                                    text             : data.message || 'No se pudo enviar el recibo.',
                                    confirmButtonColor: '#145da0',
                                });
                            }
                        })
                        .catch(() => {
                            Swal.fire({
                                icon             : 'error',
                                title            : 'Error',
                                text             : 'Ocurrió un error inesperado.',
                                confirmButtonColor: '#145da0',
                            });
                        });
                    });
                });
            });

            /* ── Auto-dismiss flash alert después de 4s ── */
            const flash = document.getElementById('pagFlashAlert');
            if (flash) {
                setTimeout(() => {
                    flash.style.transition = 'opacity .4s ease';
                    flash.style.opacity    = '0';
                    setTimeout(() => flash.remove(), 400);
                }, 4000);
            }

        });
    })();
    </script>