@section('title')
<div class="container-fluid bg-light py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12">
                <h1 class="h2 mb-0 text-primary fw-bold">
                    <i class="bi bi-credit-card-2-front me-2"></i>
                    Pagos
                </h1>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container py-5">
    <div class=" justify-content-center">
        <div class="col-12">
            <!-- Card Container -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <h2 class="h5 mb-0 text-primary fw-bold">
                            Historial de Aportes
                        </h2>
                        <!-- Aquí puedes agregar botones adicionales si son necesarios -->
                    </div>
                </div>

                <div class="card-body p-0">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 text-dark">Estudiante</th>
                                    <th class="px-4 py-3 text-dark">Monto</th>
                                    <th class="px-4 py-3 text-dark">Descripción</th>
                                    <th class="px-4 py-3 text-dark text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($aportes as $aporte)
                                    @if ($aporte->estudiante_id == auth()->user()->id)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-primary bg-opacity-10 text-primary me-3">
                                                        <i class="bi bi-person text-white "></i>
                                                    </div>
                                                    <span>{{ $aporte->datosEstudiante }}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                    {{ number_format($aporte->monto, 2) }} Bs.
                                            </td>
                                            <td class="px-4 py-3 text-muted">
                                                {{ $aporte->DescripcionDelPago }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <a href="{{ route('recibo.generar', encrypt($aporte->id)) }}" target="_blank"
                                                   class="btn btn-light btn-sm px-3 py-2 rounded-pill hover-primary">
                                                    <i class="bi bi-file-earmark-text me-2"></i>
                                                    Ver Recibo
                                                </a>

                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="bi bi-inbox text-muted mb-3" style="font-size: 2rem;"></i>
                                                <p class="text-muted mb-0">Aún no se ha realizado ningún pago.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Paginación -->
                @if($aportes->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        <div class="d-flex justify-content-end">
                            {{ $aportes->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-circle {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .hover-primary {
        transition: all 0.3s ease;
    }

    .hover-primary:hover {
        background-color: var(--secondary-color) !important;
        color: white !important;
    }

    .table > :not(caption) > * > * {
        border-bottom-width: 1px;
        border-bottom-color: rgba(0,0,0,0.05);
    }

    .pagination {
        margin-bottom: 0;
    }

    .page-link {
        color: var(--primary-color);
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
        margin: 0 0.2rem;
    }

    .page-link:hover {
        color: var(--secondary-color);
        background-color: #f8f9fa;
    }

    .page-item.active .page-link {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
</style>
@endsection

@include('FundacionPlantillaUsu.index')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.reenviar-recibo').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const aporteId = this.getAttribute('data-id');
            Swal.fire({
                title: '¿Reenviar recibo?',
                text: 'Se enviará el recibo al correo registrado.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, reenviar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/aportes/${aporteId}/reenviar-recibo`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('¡Enviado!', data.message, 'success');
                        } else {
                            Swal.fire('Error', data.message || 'No se pudo enviar el recibo.', 'error');
                        }
                    })
                    .catch(() => {
                        Swal.fire('Error', 'Ocurrió un error inesperado.', 'error');
                    });
                }
            });
        });
    });
});
</script>



