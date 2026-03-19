@extends('layout')

@section('titulo')
    Editar Método de Pago
@endsection

@section('content')
<div class="container py-5">
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-header d-flex justify-content-between align-items-center py-4"
             style="background: var(--gradient-primary) !important; border: none;">
            <div class="d-flex align-items-center">
                <div class="bg-white bg-opacity-20 rounded-3 p-2 me-3 d-flex align-items-center justify-content-center"
                     style="width: 48px; height: 48px;">
                    <i class="fas fa-edit text-white fs-4"></i>
                </div>
                <div>
                    <h4 class="mb-0 text-white fw-bold">Editar Método de Pago</h4>
                    <p class="mb-0 text-white text-opacity-75 small">Modificando: {{ $paymentMethod->name }}</p>
                </div>
            </div>
            <a href="{{ route('payment-methods.index') }}" class="btn btn-light btn-sm px-3 fw-bold shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
        </div>
        <div class="card-body p-4">
            @if($errors->any())
                <div class="alert alert-danger">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Por favor corrija los siguientes errores:</h6>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('payment-methods.update', $paymentMethod) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label fw-bold">
                                <i class="fas fa-tag me-1"></i>Nombre del Método *
                            </label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $paymentMethod->name) }}"
                                   placeholder="Ej: Banco Nacional, Tigo Money"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="type" class="form-label fw-bold">
                                <i class="fas fa-list me-1"></i>Tipo de Método *
                            </label>
                            <select class="form-control @error('type') is-invalid @enderror"
                                    id="type"
                                    name="type"
                                    required>
                                <option value="">Seleccione un tipo</option>
                                <option value="bank" {{ old('type', $paymentMethod->type) == 'bank' ? 'selected' : '' }}>Banco</option>
                                <option value="mobile_payment" {{ old('type', $paymentMethod->type) == 'mobile_payment' ? 'selected' : '' }}>Pago Móvil</option>
                                <option value="digital_wallet" {{ old('type', $paymentMethod->type) == 'digital_wallet' ? 'selected' : '' }}>Billetera Digital</option>
                                <option value="cryptocurrency" {{ old('type', $paymentMethod->type) == 'cryptocurrency' ? 'selected' : '' }}>Criptomoneda</option>
                                <option value="other" {{ old('type', $paymentMethod->type) == 'other' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="account_holder" class="form-label fw-bold">
                                <i class="fas fa-user me-1"></i>Titular de la Cuenta
                            </label>
                            <input type="text"
                                   class="form-control @error('account_holder') is-invalid @enderror"
                                   id="account_holder"
                                   name="account_holder"
                                   value="{{ old('account_holder', $paymentMethod->account_holder) }}"
                                   placeholder="Nombre del titular">
                            @error('account_holder')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="account_number" class="form-label fw-bold">
                                <i class="fas fa-hashtag me-1"></i>Número de Cuenta/Teléfono
                            </label>
                            <input type="text"
                                   class="form-control @error('account_number') is-invalid @enderror"
                                   id="account_number"
                                   name="account_number"
                                   value="{{ old('account_number', $paymentMethod->account_number) }}"
                                   placeholder="Número de cuenta o teléfono">
                            @error('account_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="description" class="form-label fw-bold">
                        <i class="fas fa-align-left me-1"></i>Descripción
                    </label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description"
                              name="description"
                              rows="3"
                              placeholder="Descripción adicional del método de pago">{{ old('description', $paymentMethod->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="qr_image" class="form-label fw-bold">
                                <i class="fas fa-qrcode me-1"></i>Imagen QR
                            </label>

                            @if($paymentMethod->qr_image)
                                <div class="mb-3">
                                    <p class="text-muted mb-2">Imagen actual:</p>
                                    <img src="{{ $paymentMethod->qr_image_url }}"
                                         alt="QR actual"
                                         class="img-thumbnail"
                                         style="max-width: 150px;">
                                </div>
                            @endif

                            <input type="file"
                                   class="form-control @error('qr_image') is-invalid @enderror"
                                   id="qr_image"
                                   name="qr_image"
                                   accept="image/*"
                                   onchange="previewImage(this)">
                            <small class="form-text text-muted">
                                Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB
                                @if($paymentMethod->qr_image)
                                    <br>Deje vacío para mantener la imagen actual
                                @endif
                            </small>
                            @error('qr_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Preview de la nueva imagen -->
                        <div id="image-preview" class="mt-3" style="display: none;">
                            <p class="text-muted mb-2">Nueva imagen:</p>
                            <img id="preview-img" src="" alt="Preview" class="img-thumbnail" style="max-width: 150px;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="sort_order" class="form-label fw-bold">
                                <i class="fas fa-sort-numeric-up me-1"></i>Orden de Visualización
                            </label>
                            <input type="number"
                                   class="form-control @error('sort_order') is-invalid @enderror"
                                   id="sort_order"
                                   name="sort_order"
                                   value="{{ old('sort_order', $paymentMethod->sort_order) }}"
                                   min="0">
                            <small class="form-text text-muted">
                                Número para ordenar los métodos de pago (0 = primero)
                            </small>
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="checkbox"
                                       id="is_active"
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', $paymentMethod->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="is_active">
                                    <i class="fas fa-toggle-on me-1"></i>Método Activo
                                </label>
                                <small class="form-text text-muted d-block">
                                    Los métodos inactivos no se mostrarán a los usuarios
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información adicional -->
                <div class="card mt-4 border-0 bg-light rounded-3 shadow-none">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h6 class="mb-0 fw-bold text-primary"><i class="fas fa-info-circle me-2"></i>Información Adicional (Opcional)</h6>
                        <p class="text-muted small mb-0 mt-1">Agregue campos personalizados (ej: Horario, Sucursal)</p>
                    </div>
                    <div class="card-body p-4">
                        <div id="additional-info-container">
                            @if($paymentMethod->additional_info)
                                @foreach($paymentMethod->additional_info as $index => $info)
                                    <div class="row additional-info-row {{ $loop->first ? '' : 'mt-2' }}">
                                        <div class="col-md-5 mb-2 mb-md-0">
                                            <input type="text"
                                                   class="form-control rounded-3"
                                                   name="additional_info[{{ $index }}][key]"
                                                   placeholder="Clave"
                                                   value="{{ $info['key'] }}">
                                        </div>
                                        <div class="col-md-5 mb-2 mb-md-0">
                                            <input type="text"
                                                   class="form-control rounded-3"
                                                   name="additional_info[{{ $index }}][value]"
                                                   placeholder="Valor"
                                                   value="{{ $info['value'] }}">
                                        </div>
                                        <div class="col-md-2">
                                            @if($loop->first)
                                                <button type="button" class="btn btn-success w-100 rounded-3" onclick="addAdditionalInfo()">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-danger w-100 rounded-3" onclick="removeAdditionalInfo(this)">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="row additional-info-row">
                                    <div class="col-md-5 mb-2 mb-md-0">
                                        <input type="text"
                                               class="form-control rounded-3"
                                               name="additional_info[0][key]"
                                               placeholder="Clave (ej: Horario)">
                                    </div>
                                    <div class="col-md-5 mb-2 mb-md-0">
                                        <input type="text"
                                               class="form-control rounded-3"
                                               name="additional_info[0][value]"
                                               placeholder="Valor (ej: 8:00 - 18:00)">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-success w-100 rounded-3" onclick="addAdditionalInfo()">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-5 d-flex justify-content-end pt-4 border-top">
                    <a href="{{ route('payment-methods.index') }}" class="btn btn-outline-secondary px-4 me-2 rounded-3">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary px-4 rounded-3 shadow-sm" style="background: var(--gradient-primary) !important; border: none;">
                        <i class="fas fa-save me-1"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let additionalInfoCounter = {{ $paymentMethod->additional_info ? count($paymentMethod->additional_info) : 1 }};

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('image-preview').style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function addAdditionalInfo() {
        const container = document.getElementById('additional-info-container');
        const newRow = document.createElement('div');
        newRow.className = 'row additional-info-row mt-2';
        newRow.innerHTML = `
            <div class="col-md-5 mb-2 mb-md-0">
                <input type="text"
                       class="form-control rounded-3"
                       name="additional_info[${additionalInfoCounter}][key]"
                       placeholder="Clave">
            </div>
            <div class="col-md-5 mb-2 mb-md-0">
                <input type="text"
                       class="form-control rounded-3"
                       name="additional_info[${additionalInfoCounter}][value]"
                       placeholder="Valor">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100 rounded-3" onclick="removeAdditionalInfo(this)">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        `;
        container.appendChild(newRow);
        additionalInfoCounter++;
    }

    function removeAdditionalInfo(button) {
        button.closest('.additional-info-row').remove();
    }
</script>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endsection

@extends('layout')
