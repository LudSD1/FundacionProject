@section('titulo')
    Métodos de Pago
@endsection

@section('content')
<div class="container py-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h2 class="mb-0"><i class="fas fa-credit-card me-2"></i>Gestión de Métodos de Pago</h2>
            <a href="{{ route('payment-methods.create') }}" class="btn btn-light">
                <i class="fas fa-plus me-1"></i> Nuevo Método
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Orden</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Titular</th>
                            <th>Número/Cuenta</th>
                            <th>QR</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paymentMethods as $method)
                            <tr class="{{ $method->trashed() ? 'table-secondary' : '' }}">
                                <td>
                                    <span class="badge bg-info">{{ $method->sort_order }}</span>
                                </td>
                                <td>
                                    <strong>{{ $method->name }}</strong>
                                    @if($method->trashed())
                                        <span class="badge bg-danger ms-2">Eliminado</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $method->type_name }}</span>
                                </td>
                                <td>{{ $method->account_holder ?? '-' }}</td>
                                <td>{{ $method->account_number ?? '-' }}</td>
                                <td class="text-center">
                                    @if($method->qr_image)
                                        <img src="{{ $method->qr_image_url }}"
                                             alt="QR {{ $method->name }}"
                                             class="img-thumbnail"
                                             style="width: 50px; height: 50px; cursor: pointer;"
                                             data-bs-toggle="modal"
                                             data-bs-target="#qrModal{{ $method->id }}">
                                    @else
                                        <span class="text-muted">Sin QR</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!$method->trashed())
                                        @if($method->is_active)
                                            <span class="badge bg-success">Activo</span>
                                        @else
                                            <span class="badge bg-warning">Inactivo</span>
                                        @endif
                                    @else
                                        <span class="badge bg-danger">Eliminado</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @if(!$method->trashed())
                                            <a href="{{ route('payment-methods.show', $method) }}"
                                               class="btn btn-sm btn-outline-info"
                                               title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('payment-methods.edit', $method) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('payment-methods.toggle-status', $method) }}"
                                                  method="POST"
                                                  class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-{{ $method->is_active ? 'warning' : 'success' }}"
                                                        title="{{ $method->is_active ? 'Desactivar' : 'Activar' }}"
                                                        onclick="return confirm('¿Está seguro de {{ $method->is_active ? 'desactivar' : 'activar' }} este método de pago?')">
                                                    <i class="fas fa-{{ $method->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('payment-methods.destroy', $method) }}"
                                                  method="POST"
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Eliminar"
                                                        onclick="return confirm('¿Está seguro de eliminar este método de pago?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('payment-methods.restore', $method->id) }}"
                                                  method="POST"
                                                  class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-success"
                                                        title="Restaurar"
                                                        onclick="return confirm('¿Está seguro de restaurar este método de pago?')">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal para mostrar QR -->
                            @if($method->qr_image)
                                <div class="modal fade" id="qrModal{{ $method->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">QR - {{ $method->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="{{ $method->qr_image_url }}"
                                                     alt="QR {{ $method->name }}"
                                                     class="img-fluid">
                                                <div class="mt-3">
                                                    <p><strong>{{ $method->name }}</strong></p>
                                                    @if($method->account_holder)
                                                        <p>Titular: {{ $method->account_holder }}</p>
                                                    @endif
                                                    @if($method->account_number)
                                                        <p>Cuenta: {{ $method->account_number }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                <a href="{{ $method->qr_image_url }}"
                                                   download="QR_{{ $method->name }}.png"
                                                   class="btn btn-primary">
                                                    <i class="fas fa-download me-1"></i>Descargar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-credit-card fa-3x mb-3"></i>
                                        <p>No hay métodos de pago registrados</p>
                                        <a href="{{ route('payment-methods.create') }}" class="btn btn-primary">
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

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endsection

@extends('layout')
