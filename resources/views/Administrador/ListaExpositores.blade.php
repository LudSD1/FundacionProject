@extends('layout')

@section('titulo')
    Lista de expositores
@endsection

@section('content')
<div class="container my-4">
    <div class="tbl-card">

        {{-- ╔══════════════════════════════════════╗
             ║  HERO                               ║
             ╚══════════════════════════════════════╝ --}}
        <div class="tbl-card-hero">

            <div class="tbl-hero-left">
                <div class="tbl-hero-eyebrow">
                    <i class="bi bi-mic-fill"></i> Gestión
                </div>
                <h2 class="tbl-hero-title">Lista de Expositores</h2>
                <p class="tbl-hero-sub">Administra los expositores disponibles para congresos</p>
            </div>

            <div class="tbl-hero-controls">
                <button type="button"
                        class="tbl-hero-btn tbl-hero-btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#crearExpositorModal">
                    <i class="bi bi-person-plus-fill"></i> Crear Expositor
                </button>

                {{-- Buscador --}}
                <form id="formBusquedaExpositores"
                      action="{{ route('ListaExpositores') }}"
                      method="GET">
                    <div class="tbl-hero-search">
                        <i class="bi bi-search tbl-hero-search-icon"></i>
                        <input type="text"
                               class="tbl-hero-search-input"
                               placeholder="Buscar expositor..."
                               name="search"
                               value="{{ request('search') }}">
                    </div>
                </form>
            </div>

        </div>{{-- /tbl-card-hero --}}

        {{-- Filtro activo --}}
        @if(request('search'))
        <div class="tbl-filter-bar">
            <div class="tbl-filter-bar-left">
                <i class="bi bi-funnel-fill"></i>
                Búsqueda: <strong>{{ request('search') }}</strong>
            </div>
            <a href="{{ route('ListaExpositores') }}" class="tbl-filter-clear">
                <i class="bi bi-x-circle"></i> Limpiar
            </a>
        </div>
        @endif

        {{-- ╔══════════════════════════════════════╗
             ║  TABLA                              ║
             ╚══════════════════════════════════════╝ --}}
        <div class="table-container-modern">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th width="6%">
                            <div class="th-content">
                                <i class="bi bi-hash"></i><span>Nº</span>
                            </div>
                        </th>
                        <th width="24%">
                            <div class="th-content">
                                <i class="bi bi-person-badge-fill"></i><span>Nombre</span>
                            </div>
                        </th>
                        <th width="20%">
                            <div class="th-content">
                                <i class="bi bi-briefcase-fill"></i><span>Especialidad</span>
                            </div>
                        </th>
                        <th width="20%">
                            <div class="th-content">
                                <i class="bi bi-building"></i><span>Empresa</span>
                            </div>
                        </th>
                        <th width="10%">
                            <div class="th-content">
                                <i class="bi bi-circle-half"></i><span>Estado</span>
                            </div>
                        </th>
                        <th width="20%" class="text-center">
                            <div class="th-content justify-content-center">
                                <i class="bi bi-gear-fill"></i><span>Acciones</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expositores as $expositor)
                    <tr>
                        <td><span class="row-number">{{ $loop->iteration }}</span></td>

                        {{-- Nombre con avatar --}}
                        <td>
                            <div class="teacher-cell">
                                <div class="tbl-avatar">
                                    {{ strtoupper(substr($expositor->nombre, 0, 1)) }}
                                </div>
                                <span>{{ $expositor->nombre }}</span>
                            </div>
                        </td>

                        {{-- Especialidad --}}
                        <td>
                            <div class="teacher-cell">
                                <i class="bi bi-briefcase-fill"></i>
                                <span>{{ $expositor->especialidad ?? '—' }}</span>
                            </div>
                        </td>

                        {{-- Empresa --}}
                        <td>
                            <div class="teacher-cell">
                                <i class="bi bi-building"></i>
                                <span>{{ $expositor->empresa ?? '—' }}</span>
                            </div>
                        </td>

                        {{-- Estado — FIX 5: status-badge del sistema --}}
                        <td>
                            @if($expositor->trashed())
                                <span class="status-badge status-inactive">
                                    <i class="bi bi-circle-fill"></i> Inactivo
                                </span>
                            @else
                                <span class="status-badge status-active">
                                    <i class="bi bi-circle-fill"></i> Activo
                                </span>
                            @endif
                        </td>

                        {{-- Acciones --}}
                        <td>
                            <div class="action-buttons-cell">

                                <button class="btn-action-modern btn-edit"
                                        data-bs-toggle="tooltip"
                                        title="Editar expositor"
                                        onclick="editarExpositor({{ $expositor->id }})">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                @if($expositor->trashed())
                                {{-- Activar --}}
                                <form id="form-activar-{{ $expositor->id }}"
                                      method="POST"
                                      action="{{ route('expositores.restore', encrypt($expositor->id)) }}"
                                      class="d-inline">
                                    @csrf
                                    <button type="button"
                                            class="btn-action-modern btn-restore"
                                            data-bs-toggle="tooltip"
                                            title="Activar expositor"
                                            onclick="confirmarActivacion({{ $expositor->id }})">
                                        <i class="bi bi-check2-circle"></i>
                                    </button>
                                </form>
                                @else
                                {{-- Desactivar --}}
                                <form id="form-eliminar-{{ $expositor->id }}"
                                      method="POST"
                                      action="{{ route('expositores.destroy', encrypt($expositor->id)) }}"
                                      class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="button"
                                            class="btn-action-modern btn-delete"
                                            data-bs-toggle="tooltip"
                                            title="Desactivar expositor"
                                            onclick="confirmarDesactivacion({{ $expositor->id }})">
                                        <i class="bi bi-slash-circle"></i>
                                    </button>
                                </form>
                                @endif

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state-table">
                                <div class="empty-icon-table">
                                    <i class="bi bi-mic-mute"></i>
                                </div>
                                <h5 class="empty-title-table">No hay expositores registrados</h5>
                                <p class="empty-text-table">
                                    Agrega un expositor usando el botón "Crear Expositor".
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación (si aplica) --}}
        @if(isset($expositores) && method_exists($expositores, 'hasPages') && $expositores->hasPages())
        <div class="tbl-pagination">
            {{ $expositores->appends(['search' => request('search')])->links('custom-pagination') }}
        </div>
        @endif

    </div>{{-- /tbl-card --}}
    </div>{{-- /container --}}


    <!-- MODAL: Crear Expositor -->
    <div class="modal fade" id="crearExpositorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 shadow overflow-hidden">
                <div class="modal-header border-0 py-3 text-white" style="background: var(--gradient-primary) !important;">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-user-plus me-2"></i> Nuevo Expositor
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formCrearExpositor" method="POST" action="{{ route('expositores.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Nombre completo *</label>
                                <input type="text" name="nombre" class="form-control rounded-3 py-2" required placeholder="Ej: Juan Pérez">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Cargo / Especialidad *</label>
                                <input type="text" name="especialidad" class="form-control rounded-3 py-2" required placeholder="Ej: Especialista en Marketing">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Empresa / Institución *</label>
                                <input type="text" name="empresa" class="form-control rounded-3 py-2" required placeholder="Ej: Universidad Central">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Foto de perfil</label>
                                <input type="file" name="imagen" class="form-control rounded-3 py-2" accept="image/*">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted text-uppercase">Biografía corta</label>
                                <textarea name="biografia" class="form-control rounded-3" rows="3" placeholder="Breve descripción profesional..."></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted text-uppercase">LinkedIn (URL)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-primary"><i class="fab fa-linkedin"></i></span>
                                    <input type="url" name="linkedin" class="form-control rounded-end-3 py-2 border-start-0" placeholder="https://linkedin.com/in/usuario">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light p-3">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" style="background: var(--gradient-primary) !important; border: none;">
                            <i class="fas fa-save me-2"></i> Guardar Expositor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: Editar Expositor -->
    <div class="modal fade" id="editarExpositorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 shadow overflow-hidden">
                <div class="modal-header border-0 py-3 text-white" style="background: var(--gradient-secondary) !important;">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-user-edit me-2"></i> Editar Expositor
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditarExpositor" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Nombre completo *</label>
                                <input type="text" name="nombre" id="edit_nombre" class="form-control rounded-3 py-2" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Especialidad *</label>
                                <input type="text" name="especialidad" id="edit_especialidad" class="form-control rounded-3 py-2" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Empresa *</label>
                                <input type="text" name="empresa" id="edit_empresa" class="form-control rounded-3 py-2" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Foto de perfil</label>
                                <input type="file" name="imagen" class="form-control rounded-3 py-2" accept="image/*">
                                <small class="text-muted italic">Dejar vacío para no cambiar la imagen</small>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted text-uppercase">Biografía</label>
                                <textarea name="biografia" id="edit_biografia" class="form-control rounded-3" rows="3"></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted text-uppercase">LinkedIn (URL)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-primary"><i class="fab fa-linkedin"></i></span>
                                    <input type="url" name="linkedin" id="edit_linkedin" class="form-control rounded-end-3 py-2 border-start-0">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light p-3">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-info rounded-pill px-4 fw-bold text-white shadow-sm" style="background: var(--gradient-secondary) !important; border: none;">
                            <i class="fas fa-save me-2"></i> Actualizar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar tooltips Bootstrap 5
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function (tooltipTriggerEl) { new bootstrap.Tooltip(tooltipTriggerEl); });

            // Buscador en tiempo real (opcional, adicional al envío de formulario)
            const searchInput = document.querySelector('.tbl-hero-search-input');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const q = this.value.toLowerCase();
                    document.querySelectorAll('tbody tr').forEach(row => {
                        if (!row.classList.contains('empty-row')) {
                            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
                        }
                    });
                });
            }
        });

        // Función para cargar datos en el modal de edición
        function editarExpositor(id) {
            Swal.fire({
                title: 'Cargando...',
                text: 'Obteniendo información del expositor',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            fetch(`/expositores/${id}/edit`)
                .then(resp => {
                    if (!resp.ok) throw new Error('Error al cargar datos');
                    return resp.json();
                })
                .then(data => {
                    Swal.close();
                    const form = document.getElementById('formEditarExpositor');
                    form.action = `/expositores/${id}`;

                    document.getElementById('edit_id').value = data.id;
                    document.getElementById('edit_nombre').value = data.nombre;
                    document.getElementById('edit_especialidad').value = data.especialidad;
                    document.getElementById('edit_empresa').value = data.empresa;
                    document.getElementById('edit_biografia').value = data.biografia || '';
                    document.getElementById('edit_linkedin').value = data.linkedin || '';

                    const modal = new bootstrap.Modal(document.getElementById('editarExpositorModal'));
                    modal.show();
                })
                .catch(err => {
                    Swal.fire('Error', 'No se pudo cargar la información del expositor', 'error');
                });
        }

        // Manejar envío de formulario de creación vía AJAX (opcional, para mejor UX)
        document.getElementById('formCrearExpositor').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            Swal.fire({
                title: 'Guardando...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(resp => resp.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({ icon: 'success', title: '¡Éxito!', text: data.success, timer: 1500, showConfirmButton: false })
                    .then(() => location.reload());
                } else {
                    throw new Error(data.message || 'Error al guardar');
                }
            })
            .catch(err => {
                Swal.fire('Error', err.message, 'error');
            });
        });

        // Manejar envío de formulario de edición vía AJAX
        document.getElementById('formEditarExpositor').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            Swal.fire({
                title: 'Actualizando...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-HTTP-Method-Override': 'PUT'
                }
            })
            .then(resp => resp.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'Actualizado', text: data.success, timer: 1500, showConfirmButton: false })
                    .then(() => location.reload());
                } else {
                    throw new Error(data.message || 'Error al actualizar');
                }
            })
            .catch(err => {
                Swal.fire('Error', err.message, 'error');
            });
        });

        // Confirmación para desactivar
        function confirmarDesactivacion(id) {
            Swal.fire({
                title: '¿Desactivar expositor?',
                text: 'El expositor ya no aparecerá en las listas activas.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, desactivar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`form-eliminar-${id}`).submit();
                }
            });
        }

        // Confirmación para activar
        function confirmarActivacion(id) {
            Swal.fire({
                title: '¿Activar expositor?',
                text: 'El expositor volverá a estar disponible en el sistema.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, activar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`form-activar-${id}`).submit();
                }
            });
        }
    </script>

@endsection
