@section('titulo', 'Lista de Pagos')

@section('content')
<div class="container-fluid py-5">
    {{-- Estructura tbl-card moderna --}}
    <div class="tbl-card">
        {{-- Cabecera con lenguaje visual moderno --}}
        <div class="tbl-card-hero">
            <div class="tbl-hero-left">
                <div class="tbl-hero-eyebrow">
                    <i class="fas fa-wallet"></i> Tesorería
                </div>
                <h2 class="tbl-hero-title">Gestión de Pagos</h2>
                <p class="tbl-hero-sub">Supervise y confirme los aportes realizados por los estudiantes</p>
            </div>
            <div class="tbl-hero-controls">
                <a href="{{ route('registrarpagoadmin') }}" class="tbl-hero-btn tbl-hero-btn-primary shadow-sm">
                    <i class="fas fa-plus-circle"></i> Registrar Pago
                </a>
            </div>
        </div>

        <div class="card-body p-4">
            {{-- Barra de búsqueda --}}
            <div class="row mb-4">
                <div class="col-12">
                    <div class="search-box-table w-100">
                        <i class="fas fa-search search-icon-table"></i>
                        <input type="text" id="searchInput" name="busqueda" class="search-input-table"
                            placeholder="Buscar por estudiante, monto, curso o fecha…">
                        <span class="search-indicator"></span>
                    </div>
                </div>
            </div>

            <div class="table-container-modern">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th style="width:48px"><div class="th-content">#</div></th>
                            <th><div class="th-content">Estudiante / Curso</div></th>
                            <th><div class="th-content">Fecha de Registro</div></th>
                            <th><div class="th-content">Monto Total</div></th>
                            <th><div class="th-content">Monto Pagado</div></th>
                            <th class="text-center"><div class="th-content text-center w-100">Documentos</div></th>
                            <th class="text-center"><div class="th-content text-center w-100">Gestión</div></th>
                            <th class="text-center"><div class="th-content text-center w-100">Acción</div></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($aportes as $aporte)
                            <tr>
                                <td><span class="row-number">#{{ $loop->iteration }}</span></td>

                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-3 p-2 me-3 text-primary">
                                            <i class="fas fa-user-graduate"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $aporte->datosEstudiante }}</div>
                                            <div class="text-muted small">
                                                <i class="fas fa-book-open me-1"></i> {{ $aporte->curso->nombreCurso }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="date-badge date-start">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ \Carbon\Carbon::parse($aporte->created_at)->format('d/m/Y') }}
                                        <span class="text-muted ms-1" style="font-size: 0.7rem;">{{ \Carbon\Carbon::parse($aporte->created_at)->format('H:i') }}</span>
                                    </div>
                                </td>

                                <td>
                                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-bold">
                                        {{ number_format($aporte->monto_a_pagar, 2) }} Bs.
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-bold">
                                        {{ number_format($aporte->monto_pagado, 2) }} Bs.
                                    </span>
                                </td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('descargar.comprobante', basename($aporte->comprobante)) }}"
                                            class="btn btn-sm btn-outline-primary rounded-pill px-2" title="Descargar comprobante">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <a href="{{ route('recibo.generar', encrypt($aporte->id)) }}" target="_blank"
                                            class="btn btn-sm btn-outline-info rounded-pill px-2" title="Ver recibo">
                                            <i class="fas fa-file-invoice"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2 reenviar-email-btn"
                                            data-id="{{ $aporte->id }}"
                                            data-estudiante="{{ $aporte->user->name }} {{ $aporte->user->lastname1 }}"
                                            data-email="{{ $aporte->user->email }}" title="Reenviar email">
                                            <i class="fas fa-envelope"></i>
                                        </button>
                                    </div>
                                </td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <button class="btn btn-sm btn-outline-warning rounded-pill px-2" data-bs-toggle="modal"
                                            data-bs-target="#editarPagoModal{{ $aporte->id }}" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger rounded-pill px-2 delete-btn" data-id="{{ $aporte->id }}"
                                            title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>

                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 confirm-pago-btn"
                                        style="background: var(--gradient-primary) !important; border: none;"
                                        data-id="{{ $aporte->id }}"
                                        data-estudiante="{{ $aporte->user->name }} {{ $aporte->user->lastname1 }}"
                                        data-curso="{{ $aporte->curso->nombreCurso }}" title="Confirmar pago">
                                        <i class="fas fa-check-circle me-1"></i> Confirmar
                                    </button>
                                </td>
                            </tr>

                        <div class="modal fade" id="editarPagoModal{{ $aporte->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content modal-modern">
                                    <form action="{{ route('pagos.update', $aporte->codigopago) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="codigopago" value="{{ $aporte->codigopago }}">

                                        <div class="modal-header-modern">
                                            <div class="modal-title-wrapper">
                                                <i class="bi bi-pencil-square modal-icon"></i>
                                                <h5 class="modal-title mb-0">Editar Pago</h5>
                                            </div>
                                            <button type="button" class="btn-close-modern" data-bs-dismiss="modal"
                                                aria-label="Cerrar">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>

                                        <div class="modal-body-modern">
                                            <div class="info-field mb-3">
                                                <label class="info-label"><i class="bi bi-person-fill"></i>
                                                    Estudiante</label>
                                                <div class="info-value">
                                                    {{ $aporte->user->name }} {{ $aporte->user->lastname1 }}
                                                    {{ $aporte->user->lastname2 }}
                                                </div>
                                            </div>
                                            <div class="info-field mb-3">
                                                <label class="info-label"><i class="bi bi-book-fill"></i> Curso</label>
                                                <div class="info-value">{{ $aporte->curso->nombreCurso }}</div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="info-label"><i class="bi bi-cash-coin"></i> Monto Pagado
                                                    (Bs.)</label>
                                                <input type="number" name="monto_pagado" class="form-control mt-1"
                                                    value="{{ $aporte->monto_pagado }}" step="0.01" required>
                                            </div>
                                        </div>

                                        <div class="modal-footer-modern">
                                            <button type="button" class="btn-secondary-modern" data-bs-dismiss="modal">
                                                <i class="bi bi-x-circle me-1"></i> Cancelar
                                            </button>
                                            <button type="submit" class="btn-submit">
                                                <i class="bi bi-floppy me-1"></i> Guardar Cambios
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="bi bi-receipt"></i>
                                    <h5>No hay pagos registrados</h5>
                                    <p>Registra el primer pago para comenzar.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>


    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /* ── Búsqueda en tiempo real ──────────────────────── */
            document.getElementById('searchInput').addEventListener('input', function() {
                const q = this.value.toLowerCase();
                document.querySelectorAll('tbody tr').forEach(tr => {
                    tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
                });
            });

            /* ── Helper: loading Swal ────────────────────────── */
            const swalLoading = (title, text) =>
                Swal.fire({
                    title,
                    text,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => Swal.showLoading()
                });

            /* ── Eliminar pago ───────────────────────────────── */
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    Swal.fire({
                        title: '¿Eliminar pago?',
                        text: 'Esta acción no se puede deshacer.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true
                    }).then(r => {
                        if (!r.isConfirmed) return;
                        fetch(`/pagos/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success)
                                    Swal.fire('Eliminado', 'El pago ha sido eliminado.',
                                        'success')
                                    .then(() => location.reload());
                            });
                    });
                });
            });

            /* ── Confirmar pago ──────────────────────────────── */
            document.querySelectorAll('.confirm-pago-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const {
                        id,
                        estudiante,
                        curso
                    } = this.dataset;
                    Swal.fire({
                        title: '¿Confirmar Pago?',
                        html: `<div class="text-start">
                           <p><strong>Estudiante:</strong> ${estudiante}</p>
                           <p><strong>Curso:</strong> ${curso}</p>
                           <p class="text-warning mt-2">
                               <i class="bi bi-exclamation-triangle me-1"></i>
                               Esto habilitará el curso para el estudiante.
                           </p>
                       </div>`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '<i class="bi bi-check-circle me-1"></i> Confirmar',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true
                    }).then(r => {
                        if (!r.isConfirmed) return;
                        swalLoading('Confirmando pago…', 'Por favor espere…');

                        const form = Object.assign(document.createElement('form'), {
                            method: 'POST',
                            action: '{{ route('habilitar.curso', '') }}/' + id,
                            target: '_blank'
                        });
                        const csrf = Object.assign(document.createElement('input'), {
                            type: 'hidden',
                            name: '_token',
                            value: '{{ csrf_token() }}'
                        });
                        form.appendChild(csrf);
                        document.body.appendChild(form);
                        form.submit();

                        setTimeout(() => {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Pago Confirmado!',
                                html: `<div class="text-start">
                                   <p>✅ Pago confirmado exitosamente</p>
                                   <p>📄 Recibo abierto en nueva ventana</p>
                                   <p>📧 Email enviado al estudiante</p>
                                   <p><strong>Estudiante:</strong> ${estudiante}</p>
                                   <p><strong>Curso:</strong> ${curso}</p>
                               </div>`,
                                confirmButtonText: 'Entendido'
                            }).then(() => location.reload());
                        }, 2000);
                    });
                });
            });

            /* ── Reenviar email ──────────────────────────────── */
            document.querySelectorAll('.reenviar-email-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const {
                        id,
                        estudiante,
                        email
                    } = this.dataset;
                    Swal.fire({
                        title: '¿Reenviar Email?',
                        html: `<div class="text-start">
                           <p><strong>Estudiante:</strong> ${estudiante}</p>
                           <p><strong>Email:</strong> ${email}</p>
                           <p class="text-info mt-2">
                               <i class="bi bi-info-circle me-1"></i>
                               Se reenviará el recibo por correo.
                           </p>
                       </div>`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#17a2b8',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '<i class="bi bi-envelope me-1"></i> Reenviar',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true
                    }).then(r => {
                        if (!r.isConfirmed) return;
                        swalLoading('Enviando email…', 'Por favor espere…');

                        fetch(`{{ route('recibo.reenviar', '') }}/${id}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => {
                                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                                return res.json();
                            })
                            .then(data => {
                                Swal.fire({
                                    icon: data.success ? 'success' : 'error',
                                    title: data.success ? '¡Email Enviado!' :
                                        'Error',
                                    text: data.message || (data.success ? '' :
                                        'Error desconocido'),
                                    confirmButtonText: 'Entendido'
                                });
                            })
                            .catch(err => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error de Conexión',
                                    text: 'No se pudo enviar el email: ' + err
                                        .message,
                                    confirmButtonText: 'Entendido'
                                });
                            });
                    });
                });
            });

        });
    </script>
@endsection

@include('layout')
