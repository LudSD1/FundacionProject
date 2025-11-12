@extends('layout')

@section('titulo')
    Lista de expositores
@endsection

@section('content')
    <div class="container my-4">
        <div class="card card-modern">
            <div class="card-header-modern">
                <div class="row align-items-center g-3">
                    <div class="col-lg-6 col-md-12">
                        <div class="action-buttons-header d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-modern btn-primary" data-bs-toggle="modal" data-bs-target="#crearExpositorModal" title="Crear nuevo expositor">
                                <i class="bi bi-person-plus me-2"></i>
                                <span>Crear Expositor</span>
                            </button>
                            <h5 class="mb-0 text-muted d-inline-flex align-items-center">
                                <i class="bi bi-people-fill me-2"></i>
                                Lista de expositores
                            </h5>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <form id="formBusquedaExpositores" action="{{ route('ListaExpositores') }}" method="GET" class="d-inline-block w-100 w-md-auto">
                            <div class="search-box-table">
                                <i class="bi bi-search search-icon-table"></i>
                                <input class="form-control search-input-table" placeholder="Buscar expositor..." name="search" type="text" value="{{ request('search') }}">
                                <button type="submit" class="btn btn-modern btn-search-icon" aria-label="Buscar" title="Buscar">
                                    <i class="bi bi-search"></i>
                                </button>
                                <div class="search-indicator"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive table-container-modern">
            <table class="table table-modern align-middle">
                <thead>
                    <tr>
                        <th width="6%">
                            <div class="th-content">
                                <i class="bi bi-hash"></i>
                                <span>Nº</span>
                            </div>
                        </th>
                        <th width="24%">
                            <div class="th-content">
                                <i class="bi bi-person-badge"></i>
                                <span>Nombre</span>
                            </div>
                        </th>
                        <th width="20%">
                            <div class="th-content">
                                <i class="bi bi-briefcase-fill"></i>
                                <span>Cargo</span>
                            </div>
                        </th>
                        <th width="20%">
                            <div class="th-content">
                                <i class="bi bi-building"></i>
                                <span>Empresa</span>
                            </div>
                        </th>
                        <th width="10%">
                            <div class="th-content">
                                <i class="bi bi-circle-half"></i>
                                <span>Estado</span>
                            </div>
                        </th>
                        <th width="20%" class="text-center">
                            <div class="th-content justify-content-center">
                                <i class="bi bi-gear-fill"></i>
                                <span>Acciones</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($expositores as $expositor)
                        <tr>
                            <td><span class="row-number">{{ $loop->iteration }}</span></td>
                            <td>
                                <div class="teacher-cell">
                                    <i class="bi bi-person-badge"></i>
                                    <span>{{ $expositor->nombre }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="teacher-cell">
                                    <i class="bi bi-briefcase-fill"></i>
                                    <span>{{ $expositor->especialidad }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="teacher-cell">
                                    <i class="bi bi-building"></i>
                                    <span>{{ $expositor->empresa }}</span>
                                </div>
                            </td>
                            <td>
                                @if ($expositor->trashed())
                                    <span class="badge bg-danger">Inactivo</span>
                                @else
                                    <span class="badge bg-success">Activo</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons-cell justify-content-center">
                                    <button onclick="editarExpositor({{ $expositor->id }})" class="btn-action-modern btn-edit" data-bs-toggle="tooltip" title="Editar expositor">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    @if ($expositor->trashed())
                                        <form id="form-activar-{{ $expositor->id }}" method="POST" action="{{ route('expositores.restore', encrypt($expositor->id)) }}" class="form-activar d-inline">
                                            @csrf
                                            <button type="button" class="btn-action-modern btn-restore" data-bs-toggle="tooltip" title="Activar expositor" onclick="confirmarActivacion({{ $expositor->id }})">
                                                <i class="bi bi-check"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form id="form-eliminar-{{ $expositor->id }}" method="POST" action="{{ route('expositores.destroy', encrypt($expositor->id)) }}" class="form-eliminar d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn-action-modern btn-delete" data-bs-toggle="tooltip" title="Desactivar expositor" onclick="confirmarDesactivacion({{ $expositor->id }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="empty-state-table">
                                    <div class="empty-icon-table">
                                        <i class="bi bi-exclamation-triangle"></i>
                                    </div>
                                    <h5 class="empty-title-table">No hay expositores registrados</h5>
                                    <p class="empty-text-table">Agrega un expositor usando el botón "Crear Expositor".</p>
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
        // Inicializar tooltips Bootstrap 5
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) { new bootstrap.Tooltip(tooltipTriggerEl); });

        // Asegurar envío de búsqueda al hacer Enter o click en el botón
        (function() {
            var form = document.getElementById('formBusquedaExpositores');
            if (form) {
                var input = form.querySelector('input[name="search"]');
                var btn = form.querySelector('.btn-search-icon');
                if (input) {
                    input.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter') {
                            form.submit();
                        }
                    });
                }
                if (btn) {
                    btn.addEventListener('click', function() { form.submit(); });
                }
            }
        })();

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
            if (window.$) {
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
            } else {
                // Fallback sin jQuery
                fetch(`/expositores/${id}/edit`)
                    .then(function(resp) {
                        if (!resp.ok) throw new Error('Error al cargar expositor');
                        return resp.json();
                    })
                    .then(function(data) {
                        var form = document.getElementById('formEditarExpositor');
                        if (form) form.setAttribute('action', `/expositores/${id}`);
                        document.getElementById('edit_id').value = data.id || '';
                        document.getElementById('edit_nombre').value = data.nombre || '';
                        document.getElementById('edit_especialidad').value = data.especialidad || '';
                        document.getElementById('edit_empresa').value = data.empresa || '';
                        document.getElementById('edit_biografia').value = data.biografia || '';
                        document.getElementById('edit_linkedin').value = data.linkedin || '';

                        var modalEl = document.getElementById('editarExpositorModal');
                        if (modalEl) {
                            var modal = new bootstrap.Modal(modalEl);
                            modal.show();
                        }
                    })
                    .catch(function(err) {
                        Swal.fire('Error', 'No se pudo cargar la información del expositor', 'error');
                    });
            }
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
