@extends('layout')

@section('titulo')
    Métodos de Pago
@endsection

@section('content')
<div class="container-fluid py-5">
    {{-- Usamos la estructura tbl-card definida en tables.css --}}
    <div class="tbl-card">
        {{-- Cabecera con lenguaje visual moderno --}}
        <div class="tbl-card-hero">
            <div class="tbl-hero-left">
                <div class="tbl-hero-eyebrow">
                    <i class="fas fa-shield-alt"></i> Administración
                </div>
                <h2 class="tbl-hero-title">Gestión de Métodos de Pago</h2>
                <p class="tbl-hero-sub">Administre las opciones de pago disponibles para los estudiantes</p>
            </div>
            <div class="tbl-hero-controls">
                <a href="{{ route('payment-methods.create') }}" class="tbl-hero-btn tbl-hero-btn-primary shadow-sm">
                    <i class="fas fa-plus"></i> Nuevo Método
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            @if(session('success'))
                <div class="p-3">
                    <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="p-3">
                    <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
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
                                <td>
                                    <span class="row-number">{{ $method->sort_order }}</span>
                                </td>
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
                                            <div class="position-absolute bottom-0 right-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 16px; height: 16px; font-size: 0.6rem;">
                                                <i class="fas fa-search-plus"></i>
                                            </div>
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
                                            <a href="{{ route('payment-methods.show', $method) }}" class="btn btn-sm btn-outline-primary rounded-pill px-2" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('payment-methods.edit', $method) }}" class="btn btn-sm btn-outline-info rounded-pill px-2" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('payment-methods.toggle-status', $method) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-{{ $method->is_active ? 'warning' : 'success' }} rounded-pill px-2"
                                                        title="{{ $method->is_active ? 'Desactivar' : 'Activar' }}"
                                                        onclick="return confirm('¿Está seguro de {{ $method->is_active ? 'desactivar' : 'activar' }} este método de pago?')">
                                                    <i class="fas fa-{{ $method->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('payment-methods.destroy', $method) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2" title="Eliminar"
                                                        onclick="return confirm('¿Está seguro de eliminar este método de pago?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('payment-methods.restore', $method->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3"
                                                        onclick="return confirm('¿Está seguro de restaurar este método de pago?')">
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
                                        <a href="{{ route('payment-methods.create') }}" class="btn btn-primary mt-3 rounded-pill px-4">
                                            <i class="fas fa-plus me-1"></i> Crear primer método
                                        </a>
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

{{-- Modales de QR fuera de la tabla --}}
@foreach($paymentMethods as $method)
    @if($method->qr_image)
        <div class="modal fade" id="qrModal{{ $method->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-4 overflow-hidden shadow">
                    <div class="modal-header border-0 py-3 text-white" style="background: var(--gradient-primary) !important;">
                        <h5 class="modal-title fw-bold">
                            <i class="fas fa-qrcode me-2"></i>QR - {{ $method->name }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center p-4">
                        <div class="bg-light p-3 rounded-4 mb-4 d-inline-block shadow-sm">
                            <img src="{{ $method->qr_image_url }}"
                                 alt="QR {{ $method->name }}"
                                 class="img-fluid rounded"
                                 style="max-width: 280px;">
                        </div>
                        <div class="mt-2">
                            <h5 class="fw-bold text-primary mb-1">{{ $method->name }}</h5>
                            <p class="text-muted small mb-3">{{ $method->type_name }}</p>

                            <div class="row g-2 justify-content-center">
                                @if($method->account_holder)
                                    <div class="col-10">
                                        <div class="bg-light p-2 rounded text-start">
                                            <small class="text-muted d-block" style="font-size: 0.65rem;">TITULAR</small>
                                            <span class="fw-bold">{{ $method->account_holder }}</span>
                                        </div>
                                    </div>
                                @endif
                                @if($method->account_number)
                                    <div class="col-10">
                                        <div class="bg-light p-2 rounded text-start">
                                            <small class="text-muted d-block" style="font-size: 0.65rem;">CUENTA / TELÉFONO</small>
                                            <span class="fw-bold">{{ $method->account_number }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light p-3">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cerrar</button>
                        <a href="{{ $method->qr_image_url }}" download="QR_{{ $method->name }}.png" class="btn btn-primary rounded-pill px-4" style="background: var(--gradient-primary) !important; border: none;">
                            <i class="fas fa-download me-1"></i> Descargar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endsection
