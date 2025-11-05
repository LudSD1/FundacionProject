{{-- Modal para usuarios autenticados - Compra de curso --}}
@auth
<div class="modal fade" id="compraCursoModal" tabindex="-1" aria-labelledby="compraCursoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="compraCursoModalLabel">
                    <i class="bi bi-cart-check-fill me-2"></i>
                    {{ $cursos->precio > 0 ? 'Completar Compra' : 'Confirmar Inscripción' }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('registrarpagoPost') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <!-- Información del usuario -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="bi bi-person-circle me-2"></i>Usuario
                        </label>
                        <input type="text" name="user"
                            value="{{ auth()->user()->name }} {{ auth()->user()->lastname1 }} {{ auth()->user()->lastname2 }}"
                            class="form-control" readonly>
                    </div>

                    <hr class="my-4">

                    <!-- Campo oculto con ID del estudiante -->
                    <input type="hidden" name="estudiante_id" value="{{ auth()->user()->id }}">

                    <!-- Curso seleccionado -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="bi bi-book me-2"></i>Curso
                        </label>
                        <select name="curso_id" class="form-select">
                            <option value="{{ $cursos->id }}" selected>
                                {{ $cursos->nombreCurso }}
                                ({{ $cursos->precio > 0 ? 'Bs ' . number_format($cursos->precio, 2) : 'Gratuito' }})
                            </option>
                        </select>
                    </div>

                    @if ($cursos->precio > 0)
                        <!-- Monto a pagar -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="bi bi-cash-coin me-2"></i>Monto a Pagar
                            </label>
                            <div class="input-group">
                                <input type="number" name="montopagar" class="form-control"
                                    value="{{ $cursos->precio }}" min="1" step="any" required readonly>
                                <span class="input-group-text">Bs</span>
                            </div>
                        </div>

                        <!-- Comprobante -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="bi bi-file-earmark-arrow-up me-2"></i>Comprobante de Pago
                            </label>
                            <input type="file" name="comprobante" class="form-control"
                                accept=".pdf,.jpg,.jpeg,.png" required>
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Formatos aceptados: PDF, JPG, PNG (Max. 2MB)
                            </small>
                        </div>
                    @endif

                    <!-- Descripción -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="bi bi-chat-left-text me-2"></i>Descripción
                        </label>
                        <textarea name="descripcion" class="form-control" rows="3" required
                            placeholder="Ingrese detalles adicionales sobre su compra..."></textarea>
                    </div>

                    @if ($cursos->precio > 0)
                        <!-- Métodos de pago -->
                        <div class="mb-4">
                            <h6 class="text-center mb-3">
                                <i class="bi bi-credit-card me-2"></i>Métodos de Pago Disponibles
                            </h6>

                            @if ($metodosPago->where('is_active', true)->count() > 0)
                                <div id="paymentMethodsCarousel" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        @foreach ($metodosPago->where('is_active', true)->sortBy('sort_order') as $index => $metodo)
                                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                <div class="card border-0 shadow-sm">
                                                    <div class="card-body text-center p-4">
                                                        <h6 class="card-title mb-3">{{ $metodo->name }}</h6>

                                                        @if ($metodo->qr_image)
                                                            <div class="mb-3">
                                                                <img src="{{ $metodo->qr_image_url }}"
                                                                    alt="QR {{ $metodo->name }}"
                                                                    class="img-fluid rounded"
                                                                    style="max-height: 250px; max-width: 250px;">
                                                            </div>
                                                        @endif

                                                        @if ($metodo->account_holder)
                                                            <p class="mb-2">
                                                                <strong><i class="bi bi-person me-1"></i>Titular:</strong>
                                                                {{ $metodo->account_holder }}
                                                            </p>
                                                        @endif

                                                        @if ($metodo->account_number)
                                                            <p class="mb-2">
                                                                <strong><i class="bi bi-credit-card-2-front me-1"></i>Cuenta:</strong>
                                                                {{ $metodo->account_number }}
                                                            </p>
                                                        @endif

                                                        @if ($metodo->description)
                                                            <p class="text-muted small mb-2">{{ $metodo->description }}</p>
                                                        @endif

                                                        @if ($metodo->additional_info && count($metodo->additional_info) > 0)
                                                            <div class="mt-3 pt-3 border-top">
                                                                @foreach ($metodo->additional_info as $info)
                                                                    @if (isset($info['key']) && isset($info['value']) && !empty($info['key']) && !empty($info['value']))
                                                                        <small class="d-block text-muted mb-1">
                                                                            <strong>{{ $info['key'] }}:</strong>
                                                                            {{ $info['value'] }}
                                                                        </small>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    @if ($metodosPago->where('is_active', true)->count() > 1)
                                        <!-- Controles del carousel -->
                                        <button class="carousel-control-prev" type="button"
                                            data-bs-target="#paymentMethodsCarousel" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Anterior</span>
                                        </button>
                                        <button class="carousel-control-next" type="button"
                                            data-bs-target="#paymentMethodsCarousel" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Siguiente</span>
                                        </button>

                                        <!-- Indicadores -->
                                        <div class="carousel-indicators">
                                            @foreach ($metodosPago->where('is_active', true)->sortBy('sort_order') as $index => $metodo)
                                                <button type="button" data-bs-target="#paymentMethodsCarousel"
                                                    data-bs-slide-to="{{ $index }}"
                                                    class="{{ $index === 0 ? 'active' : '' }}"
                                                    aria-label="Método {{ $index + 1 }}"></button>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @else
                                <!-- Fallback si no hay métodos configurados -->
                                <div class="text-center p-4">
                                    <i class="bi bi-exclamation-circle text-warning" style="font-size: 3rem;"></i>
                                    <p class="mt-3 mb-0">No hay métodos de pago configurados</p>
                                </div>
                            @endif

                            <small class="text-muted d-block text-center mt-3">
                                <i class="bi bi-shield-check me-1"></i>
                                Por favor adjunte su comprobante de pago
                            </small>
                        </div>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check2-circle me-2"></i>
                        {{ $cursos->precio > 0 ? 'Confirmar Compra' : 'Confirmar Inscripción' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endauth
