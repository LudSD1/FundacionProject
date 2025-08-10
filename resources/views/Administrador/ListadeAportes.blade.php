@section('titulo', 'Lista de Pagos')
@section('content')
<div class="container-fluid my-4">
    <div class="row mb-3 align-items-center">
        <div class="col-md-4 mb-2">
            <a href="{{ route('registrarpagoadmin') }}" class="btn btn-success w-100">
                <i class="bi bi-plus-circle"></i> Registrar Pago
            </a>
        </div>
        <div class="col-md-8">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" id="searchInput" class="form-control" placeholder="Buscar...">
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="">
                <tr>
                    <th>#</th>
                    <th>Datos Estudiante</th>
                    <th>Fecha del Pago</th>
                    <th>Monto a Pagar</th>
                    <th>Monto Cancelado</th>
                    <th>Comprobante</th>
                    <th>Acciones</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($aportes as $aportes)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $aportes->datosEstudiante }}</td>
                        <td>{{ $aportes->created_at }}</td>
                        <td>{{ $aportes->monto_a_pagar }} Bs.</td>
                        <td>{{ $aportes->monto_pagado }} Bs.</td>
                                                <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('descargar.comprobante', basename($aportes->comprobante)) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-download"></i> Comprobante
                                </a>
                                <a href="{{ route('recibo.generar',encrypt($aportes->id)) }}" target="_blank" class="btn btn-sm btn-success">
                                    <i class="bi bi-receipt"></i> Recibo
                                </a>
                                <button type="button" class="btn btn-sm btn-info reenviar-email-btn"
                                        data-id="{{ $aportes->id }}"
                                        data-estudiante="{{ $aportes->user->name }} {{ $aportes->user->lastname1 }}"
                                        data-email="{{ $aportes->user->email }}"
                                        title="Reenviar Email">
                                    <i class="bi bi-envelope"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-secondary test-email-btn"
                                        data-id="{{ $aportes->id }}"
                                        title="Test Email">
                                    <i class="bi bi-bug"></i>
                                </button>
                            </div>
                        </td>
                        <td>
                            <!-- Botón Editar -->
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editarPagoModal{{ $aportes->id }}">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <!-- Botón Eliminar -->
                            <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $aportes->id }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-success confirm-pago-btn"
                                    data-id="{{ $aportes->id }}"
                                    data-estudiante="{{ $aportes->user->name }} {{ $aportes->user->lastname1 }}"
                                    data-curso="{{ $aportes->curso->nombreCurso }}"
                                    title="Confirmar Pago">
                                <i class="bi bi-check-circle"></i> Confirmar Pago
                            </button>
                        </td>
                    </tr>

                    <!-- Modal Editar Pago -->
                    <div class="modal fade" id="editarPagoModal{{ $aportes->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('pagos.update', $aportes->codigopago) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="codigopago" value="{{ $aportes->codigopago }}">

                                    <div class="modal-header">
                                        <h5 class="modal-title">Editar Pago</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>

                                    <div class="modal-body">
                                        <p><strong>Estudiante:</strong> {{ $aportes->user->name }} {{ $aportes->user->lastname1 }} {{ $aportes->user->lastname2 }}</p>
                                        <p><strong>Curso:</strong> {{ $aportes->curso->nombreCurso }}</p>

                                        <div class="mb-3">
                                            <label class="form-label">Monto Pagado:</label>
                                            <input type="number" name="monto_pagado" class="form-control" value="{{ $aportes->monto_pagado }}" step="0.01" required>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            <h5 class="text-muted">No hay pagos registrados</h5>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    // Filtro de búsqueda
    $(document).ready(function () {
        $('#searchInput').on('input', function () {
            var searchText = $(this).val().toLowerCase();
            $('tbody tr').each(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(searchText) > -1);
            });
        });
    });

    // SweetAlert para eliminar
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                const pagoId = this.dataset.id;

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminarlo'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/pagos/${pagoId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Eliminado', 'El pago ha sido eliminado.', 'success')
                                    .then(() => window.location.reload());
                            }
                        });
                    }
                });
            });
        });

                // SweetAlert para confirmar pago
        document.querySelectorAll('.confirm-pago-btn').forEach(button => {
            button.addEventListener('click', function () {
                const pagoId = this.dataset.id;
                const estudiante = this.dataset.estudiante;
                const curso = this.dataset.curso;

                Swal.fire({
                    title: '¿Confirmar Pago?',
                    html: `
                        <div class="text-start">
                            <p><strong>Estudiante:</strong> ${estudiante}</p>
                            <p><strong>Curso:</strong> ${curso}</p>
                            <p class="text-warning"><i class="bi bi-exclamation-triangle me-2"></i>Esta acción habilitará el curso para el estudiante.</p>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="bi bi-check-circle me-2"></i>Sí, confirmar',
                    cancelButtonText: '<i class="bi bi-x-circle me-2"></i>Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Mostrar loading
                        Swal.fire({
                            title: 'Confirmando pago...',
                            text: 'Por favor espere...',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Crear formulario dinámicamente y enviarlo
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ route("habilitar.curso", "") }}/' + pagoId;
                        form.target = '_blank'; // Abrir en nueva ventana

                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = '{{ csrf_token() }}';

                        form.appendChild(csrfToken);
                        document.body.appendChild(form);
                        form.submit();

                        // Cerrar loading y mostrar éxito
                        setTimeout(() => {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Pago Confirmado!',
                                html: `
                                    <div class="text-start">
                                        <p>✅ El pago ha sido confirmado exitosamente</p>
                                        <p>📄 El recibo se ha abierto en una nueva ventana</p>
                                        <p>📧 Se ha enviado un email con el recibo al estudiante</p>
                                        <p><strong>Estudiante:</strong> ${estudiante}</p>
                                        <p><strong>Curso:</strong> ${curso}</p>
                                    </div>
                                `,
                                confirmButtonText: 'Entendido'
                            }).then(() => {
                                window.location.reload(); // Recargar la página
                            });
                        }, 2000);
                    }
                });
            });
        });

        // SweetAlert para reenviar email
        document.querySelectorAll('.reenviar-email-btn').forEach(button => {
            button.addEventListener('click', function () {
                const pagoId = this.dataset.id;
                const estudiante = this.dataset.estudiante;
                const email = this.dataset.email;

                Swal.fire({
                    title: '¿Reenviar Email?',
                    html: `
                        <div class="text-start">
                            <p><strong>Estudiante:</strong> ${estudiante}</p>
                            <p><strong>Email:</strong> ${email}</p>
                            <p class="text-info"><i class="bi bi-info-circle me-2"></i>Se enviará nuevamente el recibo por email.</p>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#17a2b8',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="bi bi-envelope me-2"></i>Sí, reenviar',
                    cancelButtonText: '<i class="bi bi-x-circle me-2"></i>Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Mostrar loading
                        Swal.fire({
                            title: 'Enviando email...',
                            text: 'Por favor espere...',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Enviar petición AJAX para reenviar email
                        const url = `{{ route('recibo.reenviar', '') }}/${pagoId}`;
                        console.log('Enviando petición a:', url);

                        fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            console.log('Response status:', response.status);
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Response data:', data);
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Email Enviado!',
                                    text: data.message,
                                    confirmButtonText: 'Entendido'
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message || 'Error desconocido',
                                    confirmButtonText: 'Entendido'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error completo:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error de Conexión',
                                text: 'No se pudo enviar el email. Error: ' + error.message,
                                confirmButtonText: 'Entendido'
                            });
                        });
                    }
                });
            });
        });


    });
</script>
@endsection

@include('layout')

