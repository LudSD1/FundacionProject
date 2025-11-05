{{--
    Archivo: resources/views/cursos/partials/modal-congreso.blade.php
    Descripción: Modales para registro e inscripción a congresos
--}}

@if ($cursos->tipo == 'congreso' && $cursos->certificados_disponibles)

    {{-- ================================================
         MODAL 1: OPCIONES DE REGISTRO
    ================================================ --}}
    <div class="modal fade" id="opcionesRegistroModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">
                        <i class="bi bi-door-open me-2"></i>Opciones de Registro
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-5">
                    <p class="mb-4 fs-5">¿Cómo deseas continuar?</p>

                    <div class="d-grid gap-3">
                        <button class="btn btn-primary btn-lg py-3" data-bs-dismiss="modal"
                            data-bs-toggle="modal" data-bs-target="#registroCongresoModal">
                            <i class="bi bi-person-plus-fill me-2"></i>Nuevo Registro
                        </button>

                        <button class="btn btn-outline-primary btn-lg py-3" data-bs-dismiss="modal"
                            data-bs-toggle="modal" data-bs-target="#loginModal">
                            <i class="bi bi-person-check-fill me-2"></i>Ya tengo cuenta
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================================
         MODAL 2: LOGIN PARA CONGRESOS
    ================================================ --}}
    <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">
                        <i class="bi bi-person-check-fill me-2"></i>Ingresa tu correo electrónico
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-center mb-4">Si ya estás registrado, ingresa tu correo para obtener el certificado</p>

                    <form action="{{ route('congreso.inscribir') }}" method="POST">
                        @csrf
                        <input type="hidden" name="congreso_id" value="{{ $cursos->id }}">

                        <div class="mb-4">
                            <label for="loginEmail" class="form-label">
                                <i class="bi bi-envelope me-2"></i>Correo Electrónico
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-at"></i>
                                </span>
                                <input type="email" class="form-control" id="loginEmail" name="email"
                                    required placeholder="tu@email.com">
                            </div>
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Ingresa el email con el que te registraste
                            </small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg py-3">
                                <i class="bi bi-award-fill me-2"></i>Obtener Certificado
                            </button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center bg-light">
                    <small class="text-muted">
                        ¿No tienes cuenta?
                        <a href="#" class="fw-bold" data-bs-toggle="modal"
                            data-bs-target="#registroCongresoModal" data-bs-dismiss="modal">
                            Regístrate aquí
                        </a>
                    </small>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================================
         MODAL 3: REGISTRO COMPLETO PARA CONGRESO
    ================================================ --}}
    <div class="modal fade" id="registroCongresoModal" tabindex="-1"
        aria-labelledby="registroCongresoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="registroCongresoModalLabel">
                        <i class="bi bi-person-badge-fill me-2"></i>Registro al Congreso
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    {{-- Mensajes de error --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6 class="alert-heading">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                Por favor, corrige los siguientes errores:
                            </h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('registrarseCongreso', encrypt($cursos->id)) }}" method="POST"
                        id="formRegistroCongreso">
                        @csrf

                        {{-- Campos de nombre y apellidos --}}
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="name" class="form-label">
                                    <i class="bi bi-person me-1"></i>Nombre
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-person-fill"></i>
                                    </span>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Tu nombre" value="{{ old('name') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="lastname1" class="form-label">
                                    <i class="bi bi-person me-1"></i>Apellido Paterno
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-person-fill"></i>
                                    </span>
                                    <input type="text" class="form-control" id="lastname1" name="lastname1"
                                        placeholder="Apellido Paterno" value="{{ old('lastname1') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="lastname2" class="form-label">
                                    <i class="bi bi-person me-1"></i>Apellido Materno
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-person-fill"></i>
                                    </span>
                                    <input type="text" class="form-control" id="lastname2" name="lastname2"
                                        placeholder="Apellido Materno" value="{{ old('lastname2') }}">
                                </div>
                            </div>
                        </div>

                        {{-- Campo de correo electrónico --}}
                        <div class="mb-4">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope me-1"></i>Correo Electrónico
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-at"></i>
                                </span>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="ejemplo@correo.com" value="{{ old('email') }}" required>
                            </div>
                        </div>

                        {{-- Campos de contraseña y confirmación --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock me-1"></i>Contraseña
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-shield-lock-fill"></i>
                                    </span>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Mínimo 8 caracteres" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button"
                                        data-target="password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">
                                    <i class="bi bi-lock-fill me-1"></i>Confirmar Contraseña
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-shield-lock-fill"></i>
                                    </span>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" placeholder="Repite tu contraseña" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button"
                                        data-target="password_confirmation">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Campo de país --}}
                        <div class="mb-4">
                            <label for="country" class="form-label">
                                <i class="bi bi-globe me-1"></i>País
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </span>
                                <select class="form-control" id="country" name="country" required>
                                    <option value="">Selecciona tu país</option>
                                    {{-- Opciones de países se llenarán con JavaScript --}}
                                </select>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold">
                                <i class="bi bi-check2-circle me-2"></i>Confirmar Registro
                            </button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center bg-light">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        ¿Ya tienes una cuenta?
                        <a href="{{ route('login.signin') }}" class="fw-bold">Inicia sesión aquí</a>
                    </small>
                </div>
            </div>
        </div>
    </div>

@endif
