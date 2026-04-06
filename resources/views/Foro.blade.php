@extends('layout')

@section('titulo', 'Foro: ' . $foro->nombreForo)

@section('content')
<div class="container my-4">
    <div class="tbl-card shadow-lg">

        {{-- ===== HERO ===== --}}
        <div class="tbl-card-hero">
            <div class="tbl-hero-left">
                <a href="{{ route('Curso', $foro->cursos->codigoCurso ?? $foro->cursos->id) }}"
                   class="tbl-hero-btn tbl-hero-btn-glass prt-back-btn mb-2">
                    <i class="bi bi-arrow-left-circle-fill"></i> Volver al Curso
                </a>
                <div class="tbl-hero-eyebrow">
                    <i class="bi bi-chat-dots-fill"></i> Foro de Discusión
                </div>
                <h2 class="tbl-hero-title">{{ $foro->nombreForo }}</h2>
                <p class="tbl-hero-sub text-white-50">
                    {{ $foro->SubtituloForo ?: 'Espacio de intercambio académico y consultas.' }}
                </p>
            </div>
            <div class="tbl-hero-controls text-end">
                <div class="ec-role-badge mb-2 d-inline-block">
                    <i class="bi bi-people-fill me-1"></i> {{ $foro->foromensaje->count() }} Mensajes
                </div>
                <div class="d-flex gap-2 justify-content-end">
                    <button class="tbl-hero-btn tbl-hero-btn-glass btn-sm"
                        data-bs-toggle="modal" data-bs-target="#commentModal">
                        <i class="bi bi-plus-circle me-1"></i> Nueva Discusión
                    </button>
                    @if (auth()->user()->id == $foro->docente_id)
                        <button class="tbl-hero-btn tbl-hero-btn-glass btn-sm"
                            data-bs-toggle="modal" data-bs-target="#modalEditarForo-{{ $foro->id }}">
                            <i class="bi bi-pencil-square me-1"></i> Editar
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="p-4 p-md-5">

            {{-- ===== DESCRIPCIÓN DEL FORO ===== --}}
            <div class="st-card p-4 border-0 bg-light rounded-4 mb-5">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary-subtle text-primary p-2 rounded-3 me-3">
                        <i class="bi bi-info-circle-fill fs-4"></i>
                    </div>
                    <h5 class="mb-0 fw-bold">Acerca de este Foro</h5>
                </div>
                <div class="text-muted">
                    {!! nl2br(e($foro->descripcionForo)) !!}
                </div>
                <div class="mt-3 pt-3 border-top d-flex flex-wrap gap-3">
                    <small class="text-muted">
                        <i class="bi bi-calendar-event me-1"></i>
                        Creado: {{ $foro->created_at->format('d/m/Y') }}
                    </small>
                    @if ($foro->fechaFin)
                        <small class="text-muted">
                            <i class="bi bi-clock-history me-1"></i>
                            Cierra: {{ $foro->fechaFin }}
                        </small>
                    @endif
                    <small class="text-muted">
                        <i class="bi bi-eye me-1"></i>
                        {{ $foro->vistas_count ?? 0 }} vistas
                    </small>
                </div>
            </div>

            {{-- ===== LISTADO DE DISCUSIONES ===== --}}
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <h4 class="text-primary fw-bold mb-0">
                    <i class="bi bi-chat-left-quote-fill me-2"></i>Intervenciones Recientes
                </h4>
            </div>

            <div class="messages-list">
                @forelse ($forosmensajes as $mensaje)
                    <div class="forum-message-card mb-4 border rounded-4 bg-white shadow-sm overflow-hidden">
                        <div class="p-4">

                            {{-- Cabecera del mensaje --}}
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-primary text-white me-3">
                                        {{ strtoupper(substr($mensaje->estudiantes->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark">
                                            {{ $mensaje->estudiantes->name }} {{ $mensaje->estudiantes->lastname1 }}
                                        </h6>
                                        <small class="text-muted">{{ $mensaje->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle border"
                                        type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
                                        <li>
                                            <button class="dropdown-item py-2"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editMessageModal-{{ $mensaje->id }}">
                                                <i class="bi bi-pencil me-2 text-primary"></i> Editar Mensaje
                                            </button>
                                        </li>
                                        <li>
                                            <form action="{{ route('foro.mensaje.delete', encrypt($mensaje->id)) }}"
                                                method="POST" class="delete-form">
                                                @csrf
                                                <button type="submit" class="dropdown-item py-2 text-danger">
                                                    <i class="bi bi-trash me-2"></i> Eliminar Mensaje
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            {{-- Contenido del mensaje --}}
                            <h5 class="text-primary fw-bold mb-2">{{ $mensaje->tituloMensaje }}</h5>
                            <p class="text-dark mb-3" style="line-height:1.6;">{{ $mensaje->mensaje }}</p>

                            <button class="btn btn-light btn-sm rounded-pill px-3 border shadow-sm"
                                data-bs-toggle="modal" data-bs-target="#replyModal-{{ $mensaje->id }}">
                                <i class="bi bi-reply-fill me-1"></i> Responder
                            </button>

                            {{-- Respuestas --}}
                            @if ($mensaje->respuestas->count() > 0)
                                <div class="replies-section mt-4 pt-4 border-top">
                                    @foreach ($mensaje->respuestas as $respuesta)
                                        <div class="reply-item mb-3 p-3 bg-light rounded-4 border-start border-primary border-4 shadow-sm">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle-sm bg-secondary text-white me-2">
                                                        {{ strtoupper(substr($respuesta->estudiantes->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <span class="small fw-bold text-dark d-block">
                                                            {{ $respuesta->estudiantes->name }}
                                                        </span>
                                                        <small class="text-muted" style="font-size:0.7rem;">
                                                            {{ $respuesta->created_at->format('d/m/Y H:i') }}
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn btn-link btn-sm text-muted p-0"
                                                        type="button" data-bs-toggle="dropdown">
                                                        <i class="bi bi-three-dots"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 small">
                                                        <li>
                                                            <button class="dropdown-item py-1"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editRespuestaModal-{{ $respuesta->id }}">
                                                                <i class="bi bi-pencil me-1"></i> Editar
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('foro.respuesta.delete', encrypt($respuesta->id)) }}"
                                                                method="POST" class="delete-form">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item py-1 text-danger">
                                                                    <i class="bi bi-trash me-1"></i> Eliminar
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <p class="small mb-0 text-dark">{{ $respuesta->mensaje }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width:80px;height:80px;">
                            <i class="bi bi-chat-square-dots text-muted fs-1"></i>
                        </div>
                        <h5 class="text-muted fw-bold">No hay intervenciones aún</h5>
                        <p class="text-muted small">¡Sé el primero en iniciar una discusión constructiva!</p>
                        <button class="tbl-hero-btn tbl-hero-btn-primary px-4 mt-2"
                            data-bs-toggle="modal" data-bs-target="#commentModal">
                            <i class="bi bi-plus-circle me-2"></i> Iniciar Discusión
                        </button>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</div>


{{-- ===== TOASTS DE NOTIFICACIÓN ===== --}}
@if (session('xp_earned'))
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index:1500;">
        <div class="toast show bg-success text-white border-0 shadow-lg rounded-4" role="alert">
            <div class="toast-body d-flex align-items-center p-3">
                <i class="bi bi-stars fs-4 me-3"></i>
                <div>
                    <strong class="d-block">¡XP Ganado!</strong>
                    <span class="small">Has ganado {{ session('xp_earned') }} XP por participar.</span>
                </div>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
@endif


{{-- ===== MODALES ===== --}}
@foreach ($forosmensajes as $mensaje)
    @include('partials.modals.edit-message',  ['mensaje'  => $mensaje])
    @include('partials.modals.reply-message', ['mensaje'  => $mensaje, 'foro' => $foro])
    @foreach ($mensaje->respuestas as $respuesta)
        @include('partials.modals.edit-reply', ['respuesta' => $respuesta])
    @endforeach
@endforeach

@include('partials.modals.new-comment', ['foro' => $foro])


{{-- ===== ESTILOS ===== --}}
<style>
    /* Badge de rol */
    .ec-role-badge {
        background: rgba(255, 165, 0, 0.15);
        color: #ffa500;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 800;
        border: 1px solid rgba(255, 165, 0, 0.3);
    }

    /* Avatares */
    .avatar-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.2rem;
        border: 2px solid #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        flex-shrink: 0;
    }

    .avatar-circle-sm {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.8rem;
        flex-shrink: 0;
    }

    /* Tarjetas de mensaje */
    .forum-message-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .forum-message-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
    }

    /* Respuestas */
    .reply-item {
        transition: background-color 0.2s ease;
    }
    .reply-item:hover {
        background: #f1f5f9 !important;
    }

    /* Toasts */
    .toast {
        border-radius: 1rem !important;
    }
</style>


{{-- ===== SCRIPTS ===== --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* -------------------------------------------------------
       1. CONFIRMACIÓN DE ELIMINACIÓN
    ------------------------------------------------------- */
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const submittedForm = this;

            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Eliminarás permanentemente este contenido. Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
            }).then(result => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Eliminando...',
                        didOpen: () => Swal.showLoading(),
                        allowOutsideClick: false,
                    });
                    submittedForm.submit();
                }
            });
        });
    });

    /* -------------------------------------------------------
       2. AUTO-OCULTAR TOASTS
    ------------------------------------------------------- */
    document.querySelectorAll('.toast').forEach(toastEl => {
        new bootstrap.Toast(toastEl, { delay: 5000 }).show();
    });

    /* -------------------------------------------------------
       3. SONIDO DE XP (opcional, falla silenciosamente)
    ------------------------------------------------------- */
    @if (session('xp_earned'))
        new Audio('/sounds/xp-gain.mp3').play().catch(() => {});
    @endif

});
</script>

@endsection
