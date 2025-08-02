@section('titulo')
    Detalles del Método de Pago
@endsection

@section('content')
<div class="container py-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h2 class="mb-0"><i class="fas fa-eye me-2"></i>Detalles del Método de Pago</h2>
            <div>
                <a href="{{ route('payment-methods.edit', $paymentMethod) }}" class="btn btn-light me-2">
                    <i class="fas fa-edit me-1"></i> Editar
                </a>
                <a href="{{ route('payment-methods.index') }}" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <!-- Información básica -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información Básica</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Nombre:</label>
                                        <p class="mb-0">{{ $paymentMethod->name }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Tipo:</label>
                                        <p class="mb-0">
                                            <span class="badge bg-secondary">{{ $paymentMethod->type_name }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Titular de la Cuenta:</label>
                                        <p class="mb-0">{{ $paymentMethod->account_holder ?? 'No especificado' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Número de Cuenta/Teléfono:</label>
                                        <p class="mb-0">{{ $paymentMethod->account_number ?? 'No especificado' }}</p>
                                    </div>
                                </div>
                            </div>

                            @if($paymentMethod->description)
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Descripción:</label>
                                    <p class="mb-0">{{ $paymentMethod->description }}</p>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Estado:</label>
                                        <p class="mb-0">
                                            @if($paymentMethod->is_active)
                                                <span class="badge bg-success">Activo</span>
                                            @else
                                                <span class="badge bg-warning">Inactivo</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Orden de Visualización:</label>
                                        <p class="mb-0">
                                            <span class="badge bg-info">{{ $paymentMethod->sort_order }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información adicional -->
                    @if($paymentMethod->additional_info && count($paymentMethod->additional_info) > 0)
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Información Adicional</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($paymentMethod->additional_info as $info)
                                        @if(isset($info['key']) && isset($info['value']) && !empty($info['key']) && !empty($info['value']))
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold text-muted">{{ $info['key'] }}:</label>
                                                <p class="mb-0">{{ $info['value'] }}</p>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Fechas -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-calendar me-2"></i>Información de Registro</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Fecha de Creación:</label>
                                        <p class="mb-0">{{ $paymentMethod->created_at->format('d/m/Y H:i:s') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Última Actualización:</label>
                                        <p class="mb-0">{{ $paymentMethod->updated_at->format('d/m/Y H:i:s') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QR Code -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-qrcode me-2"></i>Código QR</h5>
                        </div>
                        <div class="card-body text-center">
                            @if($paymentMethod->qr_image)
                                <img src="{{ $paymentMethod->qr_image_url }}"
                                     alt="QR {{ $paymentMethod->name }}"
                                     class="img-fluid mb-3"
                                     style="max-width: 100%; border: 1px solid #ddd; border-radius: 8px;">

                                <div class="d-grid gap-2">
                                    <a href="{{ $paymentMethod->qr_image_url }}"
                                       download="QR_{{ $paymentMethod->name }}.png"
                                       class="btn btn-primary">
                                        <i class="fas fa-download me-1"></i>Descargar QR
                                    </a>
                                    <button type="button"
                                            class="btn btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#qrModal">
                                        <i class="fas fa-expand me-1"></i>Ver en Grande
                                    </button>
                                </div>
                            @else
                                <div class="text-muted py-5">
                                    <i class="fas fa-qrcode fa-3x mb-3"></i>
                                    <p>No hay código QR disponible</p>
                                    <a href="{{ route('payment-methods.edit', $paymentMethod) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-plus me-1"></i>Agregar QR
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Acciones rápidas -->
                    <div class="card mt-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Acciones</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('payment-methods.edit', $paymentMethod) }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-1"></i>Editar Método
                                </a>

                                <form action="{{ route('payment-methods.toggle-status', $paymentMethod) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="btn btn-{{ $paymentMethod->is_active ? 'warning' : 'success' }} w-100"
                                            onclick="return confirm('¿Está seguro de {{ $paymentMethod->is_active ? 'desactivar' : 'activar' }} este método de pago?')">
                                        <i class="fas fa-{{ $paymentMethod->is_active ? 'pause' : 'play' }} me-1"></i>
                                        {{ $paymentMethod->is_active ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </form>

                                <form action="{{ route('payment-methods.destroy', $paymentMethod) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-danger w-100"
                                            onclick="return confirm('¿Está seguro de eliminar este método de pago? Esta acción no se puede deshacer.')">
                                        <i class="fas fa-trash me-1"></i>Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mostrar QR en grande -->
@if($paymentMethod->qr_image)
    <div class="modal fade" id="qrModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-qrcode me-2"></i>{{ $paymentMethod->name }} - Código QR
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ $paymentMethod->qr_image_url }}"
                         alt="QR {{ $paymentMethod->name }}"
                         class="img-fluid"
                         style="max-width: 100%;">

                    <div class="mt-4">
                        <h6>{{ $paymentMethod->name }}</h6>
                        @if($paymentMethod->account_holder)
                            <p class="mb-1"><strong>Titular:</strong> {{ $paymentMethod->account_holder }}</p>
                        @endif
                        @if($paymentMethod->account_number)
                            <p class="mb-1"><strong>Cuenta:</strong> {{ $paymentMethod->account_number }}</p>
                        @endif
                        @if($paymentMethod->description)
                            <p class="text-muted">{{ $paymentMethod->description }}</p>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <a href="{{ $paymentMethod->qr_image_url }}"
                       download="QR_{{ $paymentMethod->name }}.png"
                       class="btn btn-primary">
                        <i class="fas fa-download me-1"></i>Descargar
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endsection

@extends('layout')
