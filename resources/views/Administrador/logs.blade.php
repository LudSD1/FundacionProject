@extends('layout')

@section('titulo', 'Logs del Sistema')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>
                        Logs del Sistema
                    </h4>
                    <div>
                        <a href="{{ route('Inicio') }}" class="btn btn-light btn-sm me-2">
                            <i class="fas fa-home me-1"></i>
                            Inicio
                        </a>
                        <button onclick="location.reload()" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-sync-alt me-1"></i>
                            Actualizar
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    @if(empty($logs) || (count($logs) == 1 && empty($logs[0])))
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No hay logs disponibles en este momento.
                        </div>
                    @else
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                Mostrando las últimas {{ count(array_filter($logs)) }} entradas del log
                            </small>
                        </div>

                        <div class="log-container" style="max-height: 600px; overflow-y: auto;">
                            @foreach($logs as $index => $log)
                                @if(!empty(trim($log)))
                                    <div class="log-entry mb-2 p-3 border-start border-3
                                        @if(str_contains(strtolower($log), 'error'))
                                            border-danger bg-light-danger
                                        @elseif(str_contains(strtolower($log), 'warning'))
                                            border-warning bg-light-warning
                                        @elseif(str_contains(strtolower($log), 'info'))
                                            border-info bg-light-info
                                        @else
                                            border-secondary bg-light
                                        @endif
                                        rounded">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <code class="text-dark d-block" style="white-space: pre-wrap; font-size: 0.9rem;">{{ $log }}</code>
                                            </div>
                                            <small class="text-muted ms-2">
                                                #{{ count($logs) - $index }}
                                            </small>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <div class="mt-3 text-center">
                            <button onclick="scrollToTop()" class="btn btn-secondary btn-sm me-2">
                                <i class="fas fa-arrow-up me-1"></i>
                                Ir al inicio
                            </button>
                            <button onclick="scrollToBottom()" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-down me-1"></i>
                                Ir al final
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-light-danger {
    background-color: #f8d7da !important;
}
.bg-light-warning {
    background-color: #fff3cd !important;
}
.bg-light-info {
    background-color: #d1ecf1 !important;
}
.log-container {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    background-color: #f8f9fa;
}
.log-entry {
    transition: all 0.2s ease;
}
.log-entry:hover {
    transform: translateX(2px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>

<script>
function scrollToTop() {
    document.querySelector('.log-container').scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

function scrollToBottom() {
    const container = document.querySelector('.log-container');
    container.scrollTo({
        top: container.scrollHeight,
        behavior: 'smooth'
    });
}

// Auto-scroll al final al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    scrollToBottom();
});
</script>
@endsection
