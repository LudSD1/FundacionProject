@extends('layout')

@section('titulo')
    Métodos de Pago
@endsection

@section('content')
<div class="container-fluid py-5">
    <div class="tbl-card">
        <div class="tbl-card-hero">
            <div class="tbl-hero-left">
                <a href="{{ route('Inicio') }}" class="tbl-hero-btn tbl-hero-btn-glass mb-3" style="background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2);">
                    <i class="fas fa-home"></i> Volver al Inicio
                </a>
                <div class="tbl-hero-eyebrow">
                    <i class="fas fa-shield-alt"></i> Administración
                </div>
                <h2 class="tbl-hero-title">Gestión de Métodos de Pago</h2>
                <p class="tbl-hero-sub">Administre las opciones de pago disponibles para los estudiantes</p>
            </div>
            <div class="tbl-hero-controls">
                <div class="d-flex flex-column gap-3 align-items-end">
                    <div class="search-group" style="background: rgba(255,255,255,0.1); border-radius: 0.75rem; padding: 0.25rem; display: flex; width: 250px;">
                        <span class="p-2"><i class="fas fa-search text-white-50"></i></span>
                        <input type="text" id="pmSearch" class="form-control" placeholder="Buscar métodos..."
                               style="background: transparent; border: none; color: white; padding: 0.5rem;">
                    </div>
                    <button type="button" class="tbl-hero-btn tbl-hero-btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#createPaymentModal">
                        <i class="fas fa-plus"></i> Nuevo Método
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            @if(session('success'))
                <div class="p-3">
                    <div class="alert alert-success alert-dismissible fade show mb-0 rounded-3 border-0 shadow-sm" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif

            <div class="table-container-modern">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th><div class="th-content">Orden</div></th>
                            <th><div class="th-content">Nombre</div></th>
                            <th><div class="th-content">Tipo</div></th>
                            <th><div class="th-content">Titular</div></th>
                            <th><div class="th-content">Número/Cuenta</div></th>
                            <th><div class="th-content text-center w-100">QR</div></th>
                            <th><div class="th-content">Estado</div></th>
                            <th><div class="th-content text-center w-100">Acciones</div></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paymentMethods as $method)
                            <tr class="{{ $method->trashed() ? 'opacity-75 bg-light' : '' }}">
                                <td><span class="row-number">{{ $method->sort_order }}</span></td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $method->name }}</div>
                                    @if($method->trashed())
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 mt-1" style="font-size: 0.65rem;">ELIMINADO</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="format-badge">
                                        <i class="fas fa-tag"></i> {{ $method->type_name }}
                                    </span>
                                </td>
                                <td>
                                    <div class="teacher-cell">
                                        <i class="fas fa-user-circle"></i>
                                        <span>{{ $method->account_holder ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <code class="bg-light px-2 py-1 rounded text-primary fw-bold" style="font-size: 0.85rem;">
                                        {{ $method->account_number ?? '-' }}
                                    </code>
                                </td>
                                <td class="text-center">
                                    @if($method->qr_image)
                                        <div class="position-relative d-inline-block">
                                            <img src="{{ $method->qr_image_url }}"
                                                 alt="QR {{ $method->name }}"
                                                 class="rounded border shadow-sm"
                                                 style="width: 42px; height: 42px; cursor: pointer; object-fit: cover;"
                                                 data-bs-toggle="modal"
                                                 data-bs-target="#qrModal{{ $method->id }}">
                                        </div>
                                    @else
                                        <span class="text-muted small italic">Sin QR</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!$method->trashed())
                                        @if($method->is_active)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                                <i class="fas fa-check-circle me-1"></i> Activo
                                            </span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">
                                                <i class="fas fa-clock me-1"></i> Inactivo
                                            </span>
                                        @endif
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                            <i class="fas fa-trash me-1"></i> Eliminado
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @if(!$method->trashed())
                                            <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-2"
                                                    data-bs-toggle="modal" data-bs-target="#editPaymentModal{{ $method->id }}"
                                                    title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <form action="{{ route('payment-methods.toggle-status', $method) }}" method="POST" class="form-toggle-status">
                                                @csrf
                                                @method('PATCH')
                                                <button type="button" class="btn btn-sm btn-outline-{{ $method->is_active ? 'warning' : 'success' }} rounded-pill px-2 btn-status"
                                                        title="{{ $method->is_active ? 'Desactivar' : 'Activar' }}"
                                                        data-active="{{ $method->is_active ? 'true' : 'false' }}">
                                                    <i class="fas fa-{{ $method->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('payment-methods.destroy', $method) }}" method="POST" class="form-delete">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2 btn-delete-pm" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('payment-methods.restore', $method->id) }}" method="POST" class="form-restore">
                                                @csrf
                                                @method('PATCH')
                                                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 btn-restore-pm">
                                                    <i class="fas fa-undo me-1"></i> Restaurar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-credit-card fa-3x mb-3 opacity-25"></i>
                                        <p class="mb-0 fw-bold">No hay métodos de pago registrados</p>
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

{{-- Modal Crear --}}
<div class="modal fade payment-modal" id="createPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white p-4 border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-plus-circle me-2"></i>Nuevo Método de Pago
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('payment-methods.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="fw-bold mb-1 small text-uppercase">Nombre *</label>
                            <input type="text" name="name" class="form-control rounded-3" placeholder="Ej: Banco Nacional" required>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold mb-1 small text-uppercase">Tipo *</label>
                            <select name="type" class="form-select rounded-3" required>
                                <option value="bank">Banco</option>
                                <option value="mobile_payment">Pago Móvil</option>
                                <option value="digital_wallet">Billetera Digital</option>
                                <option value="cryptocurrency">Criptomoneda</option>
                                <option value="other">Otro</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold mb-1 small text-uppercase">Titular</label>
                            <input type="text" name="account_holder" class="form-control rounded-3" placeholder="Nombre completo">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold mb-1 small text-uppercase">Número/Cuenta</label>
                            <input type="text" name="account_number" class="form-control rounded-3" placeholder="Número de cuenta">
                        </div>
                        <div class="col-12">
                            <label class="fw-bold mb-1 small text-uppercase">Descripción</label>
                            <textarea name="description" class="form-control rounded-3" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold mb-1 small text-uppercase">Imagen QR</label>
                            <input type="file" name="qr_image" class="form-control rounded-3" accept="image/*">
                        </div>
                        <div class="col-md-3">
                            <label class="fw-bold mb-1 small text-uppercase">Orden</label>
                            <input type="number" name="sort_order" class="form-control rounded-3" value="0">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                                <label class="form-check-label fw-bold small text-uppercase">Activo</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3 border-0">
                    <button type="button" class="btn btn-link text-muted fw-bold text-decoration-none" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Guardar Método</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($paymentMethods as $method)
    {{-- Modal QR --}}
    @if($method->qr_image)
        <div class="modal fade payment-modal" id="qrModal{{ $method->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-4 overflow-hidden shadow">
                    <div class="modal-header border-0 py-3 text-white bg-primary">
                        <h5 class="modal-title fw-bold">
                            <i class="fas fa-qrcode me-2"></i>QR - {{ $method->name }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center p-4">
                        <div class="bg-light p-3 rounded-4 mb-3 d-inline-block shadow-sm">
                            <img src="{{ $method->qr_image_url }}" alt="QR" class="img-fluid rounded" style="max-width: 280px;">
                        </div>
                        <h5 class="fw-bold text-primary mb-1">{{ $method->name }}</h5>
                        <p class="text-muted small mb-0">{{ $method->type_name }}</p>
                    </div>
                    <div class="modal-footer border-0 bg-light p-3">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cerrar</button>
                        <a href="{{ $method->qr_image_url }}" download class="btn btn-primary rounded-pill px-4">
                            <i class="fas fa-download me-1"></i> Descargar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Editar --}}
    @if(!$method->trashed())
        <div class="modal fade payment-modal" id="editPaymentModal{{ $method->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="modal-header bg-warning text-dark p-4 border-0">
                        <h5 class="modal-title fw-bold">
                            <i class="fas fa-edit me-2"></i>Editar Método de Pago
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('payment-methods.update', $method) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="fw-bold mb-1 small text-uppercase">Nombre *</label>
                                    <input type="text" name="name" class="form-control rounded-3" value="{{ $method->name }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-bold mb-1 small text-uppercase">Tipo *</label>
                                    <select name="type" class="form-select rounded-3" required>
                                        <option value="bank" {{ $method->type == 'bank' ? 'selected' : '' }}>Banco</option>
                                        <option value="mobile_payment" {{ $method->type == 'mobile_payment' ? 'selected' : '' }}>Pago Móvil</option>
                                        <option value="digital_wallet" {{ $method->type == 'digital_wallet' ? 'selected' : '' }}>Billetera Digital</option>
                                        <option value="cryptocurrency" {{ $method->type == 'cryptocurrency' ? 'selected' : '' }}>Criptomoneda</option>
                                        <option value="other" {{ $method->type == 'other' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-bold mb-1 small text-uppercase">Titular</label>
                                    <input type="text" name="account_holder" class="form-control rounded-3" value="{{ $method->account_holder }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-bold mb-1 small text-uppercase">Número/Cuenta</label>
                                    <input type="text" name="account_number" class="form-control rounded-3" value="{{ $method->account_number }}">
                                </div>
                                <div class="col-12">
                                    <label class="fw-bold mb-1 small text-uppercase">Descripción</label>
                                    <textarea name="description" class="form-control rounded-3" rows="2">{{ $method->description }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-bold mb-1 small text-uppercase">Actualizar QR</label>
                                    <input type="file" name="qr_image" class="form-control rounded-3" accept="image/*">
                                </div>
                                <div class="col-md-3">
                                    <label class="fw-bold mb-1 small text-uppercase">Orden</label>
                                    <input type="number" name="sort_order" class="form-control rounded-3" value="{{ $method->sort_order }}">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $method->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold small text-uppercase">Activo</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-light p-3 border-0">
                            <button type="button" class="btn btn-link text-muted fw-bold text-decoration-none" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold">Actualizar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mover modales al body
    const modals = document.querySelectorAll('.payment-modal');
    modals.forEach(modal => {
        if (modal.parentElement !== document.body) {
            document.body.appendChild(modal);
        }
    });

    // Buscador en tiempo real
    const pmSearch = document.getElementById('pmSearch');
    const tableRows = document.querySelectorAll('.table-modern tbody tr');

    if (pmSearch) {
        pmSearch.addEventListener('input', function() {
            const term = this.value.toLowerCase().trim();

            tableRows.forEach(row => {
                if (row.querySelector('td[colspan]')) return; // Saltar fila "no hay resultados"

                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });
    }

    // Confirmación Cambio de Estado
    document.querySelectorAll('.btn-status').forEach(btn => {
        btn.addEventListener('click', function() {
            const isActive = this.getAttribute('data-active') === 'true';
            const form = this.closest('form');

            Swal.fire({
                title: isActive ? '¿Desactivar método?' : '¿Activar método?',
                text: isActive ? 'El método dejará de mostrarse a los estudiantes.' : 'El método volverá a estar visible.',
                icon: isActive ? 'warning' : 'question',
                showCancelButton: true,
                confirmButtonColor: isActive ? '#f59e0b' : '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: isActive ? 'Sí, desactivar' : 'Sí, activar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                customClass: { popup: 'rounded-4 shadow-lg border-0' }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Procesando...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                    form.submit();
                }
            });
        });
    });

    // Confirmación Eliminar
    document.querySelectorAll('.btn-delete-pm').forEach(btn => {
        btn.addEventListener('click', function() {
            const form = this.closest('form');
            Swal.fire({
                title: '¿Eliminar método de pago?',
                text: "Esta acción enviará el método a la papelera.",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                customClass: { popup: 'rounded-4 shadow-lg border-0' }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Procesando...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                    form.submit();
                }
            });
        });
    });

    // Confirmación Restaurar
    document.querySelectorAll('.btn-restore-pm').forEach(btn => {
        btn.addEventListener('click', function() {
            const form = this.closest('form');
            Swal.fire({
                title: '¿Restaurar método?',
                text: "El método volverá a estar disponible en la lista principal.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sí, restaurar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                customClass: { popup: 'rounded-4 shadow-lg border-0' }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Procesando...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection
