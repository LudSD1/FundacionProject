@section('titulo', 'Lista de Pagos')
@section('content')
<div class="card card-modern">

    <div class="container-fluid my-4">

        <div class="row mb-4 align-items-center g-3">
            <div class="col-md-4 col-lg-3">
                <a href="{{ route('registrarpagoadmin') }}" class="btn-modern btn-create w-100">
                    <i class="bi bi-plus-circle"></i>
                    <span class="ms-1">Registrar Pago</span>
                </a>
            </div>
            <div class="col-md-8 col-lg-9">
                <div class="search-box-table">
                    <i class="bi bi-search search-icon-table"></i>
                    <input type="text" id="searchInput" name="busqueda" class="search-input-table"
                        placeholder="Buscar por estudiante, monto, fecha…">
                    <span class="search-indicator"></span>
                </div>
            </div>
        </div>

        <div class="table-container-modern">
            <table class="table-modern table table-hover align-middle">
                <thead>
                    <tr>
                        <th style="width:48px">#</th>
                        <th>Estudiante</th>
                        <th>Fecha</th>
                        <th>Monto a Pagar</th>
                        <th>Monto Cancelado</th>
                        <th style="width:130px">Documentos</th>
                        <th style="width:90px">Editar / Elim.</th>
                        <th style="width:160px"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($aportes as $aporte)
                        <tr>
                            {{-- # --}}
                            <td><span class="row-number">#{{ $loop->iteration }}</span></td>

                            {{-- Estudiante --}}
                            <td>{{ $aporte->datosEstudiante }}</td>

                            {{-- Fecha --}}
                            <td>
                                <span class="text-muted" style="font-size:.85rem;">
                                    {{ \Carbon\Carbon::parse($aporte->created_at)->format('d/m/Y H:i') }}
                                </span>
                            </td>

                            {{-- Montos --}}
                            <td><strong>{{ number_format($aporte->monto_a_pagar, 2) }} Bs.</strong></td>
                            <td>{{ number_format($aporte->monto_pagado, 2) }} Bs.</td>

                            {{-- Documentos --}}
                            <td>
                                <div class="action-buttons-cell">
                                    <a href="{{ route('descargar.comprobante', basename($aporte->comprobante)) }}"
                                        class="btn-action-modern" title="Descargar comprobante">
                                        <i class="bi bi-download"></i>
                                    </a>
                                    <a href="{{ route('recibo.generar', encrypt($aporte->id)) }}" target="_blank"
                                        class="btn-action-modern" title="Ver recibo">
                                        <i class="bi bi-receipt"></i>
                                    </a>
                                    <button type="button" class="btn-action-modern reenviar-email-btn"
                                        data-id="{{ $aporte->id }}"
                                        data-estudiante="{{ $aporte->user->name }} {{ $aporte->user->lastname1 }}"
                                        data-email="{{ $aporte->user->email }}" title="Reenviar email">
                                        <i class="bi bi-envelope"></i>
                                    </button>
                                </div>
                            </td>

                            {{-- Editar / Eliminar --}}
                            <td>
                                <div class="action-buttons-cell">
                                    <button class="btn-action-modern btn-edit" data-bs-toggle="modal"
                                        data-bs-target="#editarPagoModal{{ $aporte->id }}" title="Editar">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn-action-modern btn-delete delete-btn" data-id="{{ $aporte->id }}"
                                        title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>

                            {{-- Confirmar Pago --}}
                            <td>
                                <button type="button" class="btn-modern btn-submit confirm-pago-btn"
                                    data-id="{{ $aporte->id }}"
                                    data-estudiante="{{ $aporte->user->name }} {{ $aporte->user->lastname1 }}"
                                    data-curso="{{ $aporte->curso->nombreCurso }}" title="Confirmar pago">
                                    <i class="bi bi-check-circle me-1"></i> Confirmar Pago
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
