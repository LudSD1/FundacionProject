@props([
    'text' => 'Verificar Email',
    'class' => 'btn btn-primary',
    'icon' => 'bi-envelope-check',
    'size' => 'md'
])

@auth
    @if(!Auth::user()->hasVerifiedEmail())
        <button
            type="button"
            class="{{ $class }} {{ $size === 'sm' ? 'btn-sm' : ($size === 'lg' ? 'btn-lg' : '') }}"
            onclick="enviarVerificacion()"
            title="Enviar email de verificación"
        >
            <i class="bi {{ $icon }} me-2"></i>
            {{ $text }}
        </button>
    @else
        <span class="badge bg-success">
            <i class="bi bi-check-circle me-1"></i>
            Email Verificado
        </span>
    @endif
@endauth
