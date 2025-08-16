@extends('layout')

@section('titulo')
    Lista de expositores
@endsection

@section('content')
    <div class="container my-4">
        <div class="border p-3 rounded shadow-sm bg-light">
            <div class="row align-items-center">
                <div class="col-md-6 mb-2">
                    <!-- Botón para abrir el modal de creación -->
                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                        data-bs-target="#crearExpositorModal">
                        <i class="bi bi-person-plus"></i> Crear Expositor
                    </button>
                </div>
                <div class="col-md-6 text-md-end">
                    <form action="{{ route('ListaExpositores') }}" method="GET" class="d-inline-block w-100 w-md-auto">
                        <div class="input-group">
                            <button type="submit" class="input-group-text"><i class="fa fa-search"></i></button>
                            <input class="form-control" placeholder="Buscar expositor..." name="search" type="text"
                                value="{{ request('search') }}">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabla de expositores -->
        <div class="table-responsive mt-3">
            <table class="table table-hover table-striped align-middle">
                <thead>
                    <tr>
                        <th>Nro</th>
                        <th>Nombre</th>
                        <th>Cargo</th>
                        <th>Empresa</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($expositores as $expositor)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $expositor->nombre }}</td>
                            <td>{{ $expositor->especialidad }}</td>
                            <td>{{ $expositor->empresa }}</td>
                            <td>
                                @if ($expositor->trashed())
                                    <span class="badge bg-danger">Inactivo</span>
                                @else
                                    <span class="badge bg-success">Activo</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button onclick="editarExpositor({{ $expositor->id }})" class="btn btn-sm btn-warning">
                                    <i class="fa fa-edit"></i>
                                </button>

                                @if ($expositor->trashed())
                                <form id="form-activar-{{ $expositor->id }}" method="POST"
                                    action="{{ route('expositores.restore', encrypt($expositor->id)) }}" class="form-activar d-inline">
                                    @csrf
                                    <button type="button" class="btn btn-sm btn-success"
                                        onclick="confirmarActivacion({{ $expositor->id }})">
                                        <i class="fa fa-check"></i>
                                    </button>
                                </form>
                                @else
                                <form id="form-eliminar-{{ $expositor->id }}" method="POST"
                                    action="{{ route('expositores.destroy', encrypt($expositor->id)) }}"
                                    class="form-eliminar d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="confirmarDesactivacion({{ $expositor->id }})">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="alert alert-warning m-0">
                                    <i class="bi bi-exclamation-triangle"></i> No hay expositores registrados
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL: Crear Expositor -->
    <div class="modal fade" id="crearExpositorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Nuevo Expositor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formCrearExpositor" method="POST" action="{{ route('expositores.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nombre completo</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Cargo</label>
                                <input type="text" name="especialidad" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Empresa</label>
                                <input type="text" name="empresa" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Foto de perfil</label>
                                <input type="file" name="imagen" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Biografía</label>
                            <textarea name="biografia" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">LinkedIn (URL)</label>
                                <input type="url" name="linkedin" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: Editar Expositor (ÚNICO) -->
    <div class="modal fade" id="editarExpositorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">Editar Expositor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditarExpositor" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nombre completo</label>
                                <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Especialidad</label>
                                <input type="text" name="especialidad" id="edit_especialidad" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Empresa</label>
                                <input type="text" name="empresa" id="edit_empresa" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Foto de perfil</label>
                                <input type="file" name="imagen" class="form-control">
                                <small class="text-muted">Dejar en blanco para mantener la imagen actual</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Biografía</label>
                            <textarea name="biografia" id="edit_biografia" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">LinkedIn (URL)</label>
                                <input type="url" name="linkedin" id="edit_linkedin" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts -->
    <script>
        // Crear expositor
        $('#formCrearExpositor').submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            $.ajax({
                url: "{{ route('expositores.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#crearExpositorModal').modal('hide');
                    Swal.fire('Éxito', response.success, 'success');
                    setTimeout(() => location.reload(), 1500);
                },
                error: function(xhr) {
                    Swal.fire('Error', xhr.responseJSON.message, 'error');
                }
            });
        });

        // Mostrar modal de edición (CORREGIDO)
        function editarExpositor(id) {
            $.get(`/expositores/${id}/edit`, function(data) {
                // Actualizar el action del formulario dinámicamente
                $('#formEditarExpositor').attr('action', `/expositores/${id}`);

                // Rellenar los campos del formulario
                $('#edit_id').val(data.id);
                $('#edit_nombre').val(data.nombre);
                $('#edit_especialidad').val(data.especialidad);
                $('#edit_empresa').val(data.empresa);
                $('#edit_biografia').val(data.biografia);
                $('#edit_linkedin').val(data.linkedin);

                // Mostrar el modal
                $('#editarExpositorModal').modal('show');
            }).fail(function(xhr) {
                Swal.fire('Error', 'No se pudo cargar la información del expositor', 'error');
            });
        }

        // Actualizar expositor (CORREGIDO)
        $('#formEditarExpositor').submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const actionUrl = $(this).attr('action');

            $.ajax({
                url: actionUrl,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-HTTP-Method-Override': 'PUT'
                },
                success: function(response) {
                    $('#editarExpositorModal').modal('hide');
                    Swal.fire('Éxito', response.success, 'success');
                    setTimeout(() => location.reload(), 1500);
                },
                error: function(xhr) {
                    Swal.fire('Error', xhr.responseJSON.message, 'error');
                }
            });
        });

        // Confirmación para desactivar expositor
        function confirmarDesactivacion(id) {
            Swal.fire({
                title: '¿Desactivar expositor?',
                text: 'Esta acción lo marcará como inactivo.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, desactivar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`form-eliminar-${id}`).submit();
                }
            });
        }

        // Confirmación para activar expositor
        function confirmarActivacion(id) {
            Swal.fire({
                title: '¿Activar expositor?',
                text: 'Esta acción lo marcará como activo.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, activar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`form-activar-${id}`).submit();
                }
            });
        }
    </script>

@endsection
