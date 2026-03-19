<!-- resources/views/categorias/index.blade.php -->

@extends('layout')

@section('titulo')
    Categorias
@endsection

@section('content')
<div class="container-fluid py-5">
    {{-- Estructura tbl-card moderna --}}
    <div class="tbl-card">
        {{-- Cabecera con lenguaje visual moderno --}}
        <div class="tbl-card-hero">
            <div class="tbl-hero-left">
                <div class="tbl-hero-eyebrow">
                    <i class="fas fa-sitemap"></i> Estructura Académica
                </div>
                <h2 class="tbl-hero-title">Gestión de Categorías</h2>
                <p class="tbl-hero-sub">Organice y administre las categorías de cursos y eventos</p>
            </div>
            <div class="tbl-hero-controls">
                <button class="tbl-hero-btn tbl-hero-btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#crearCategoriaModal">
                    <i class="fas fa-plus"></i> Nueva Categoría
                </button>
            </div>
        </div>

        <div class="card-body p-4">
            <!-- Barra de búsqueda y filtros -->
            <div class="row g-3 align-items-center mb-4">
                <div class="col-md-8">
                    <form action="{{ route('categorias.index') }}" method="GET" class="search-box-table w-100">
                        <input type="hidden" name="tab" value="{{ request('tab', 'activas') }}">
                        <i class="fas fa-search search-icon-table"></i>
                        <input type="text" name="busqueda" class="search-input-table"
                               placeholder="Buscar por nombre o descripción..."
                               value="{{ request('busqueda') }}">
                        @if(request('busqueda'))
                            <a href="{{ route('categorias.index', ['tab' => request('tab', 'activas')]) }}" class="btn-search-clear" title="Limpiar búsqueda">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                        <button type="submit" class="btn-search-icon">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </form>
                </div>
                <div class="col-md-4">
                    {{-- Tabs integrados --}}
                    <div class="adm-tabs-container">
                        <div class="adm-tabs-links">
                            <a class="adm-tab-link {{ request('tab', 'activas') === 'activas' ? 'active' : '' }}"
                               href="{{ route('categorias.index', ['tab' => 'activas', 'busqueda' => request('busqueda')]) }}">
                                <i class="fas fa-check-circle me-1"></i> Activas
                                <span class="badge rounded-pill bg-success ms-1">{{ $countActivas }}</span>
                            </a>
                            <a class="adm-tab-link {{ request('tab') === 'eliminadas' ? 'active' : '' }}"
                               href="{{ route('categorias.index', ['tab' => 'eliminadas', 'busqueda' => request('busqueda')]) }}">
                                <i class="fas fa-trash-alt me-1"></i> Papelera
                                <span class="badge rounded-pill bg-danger ms-1">{{ $countEliminadas }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            <div class="table-container-modern">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th style="width: 80px;"><div class="th-content">ID</div></th>
                            <th><div class="th-content">Información de Categoría</div></th>
                            <th><div class="th-content">Categoría Padre</div></th>
                            <th class="text-center" style="width: 200px;"><div class="th-content text-center w-100">Acciones</div></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categorias as $categoria)
                            <tr class="{{ $categoria->trashed() ? 'opacity-75 bg-light' : '' }}">
                                <td>
                                    <span class="row-number">#{{ $categoria->id }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-3 p-2 me-3">
                                            <i class="fas fa-folder text-primary fs-5"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark fs-6">{{ $categoria->name }}</div>
                                            @if($categoria->description)
                                                <div class="text-muted small italic">{{ Str::limit($categoria->description, 60) }}</div>
                                            @endif
                                            <code class="text-primary mt-1 d-block" style="font-size: 0.7rem;">/{{ $categoria->slug }}</code>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($categoria->parent)
                                        <span class="format-badge">
                                            <i class="fas fa-level-up-alt fa-rotate-90 me-1"></i>
                                            {{ $categoria->parent->name }}
                                        </span>
                                    @else
                                        <span class="text-muted small italic">
                                            <i class="fas fa-minus me-1"></i> Raíz
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if(request('tab') === 'eliminadas')
                                        <div class="d-flex justify-content-center gap-2">
                                            <form action="{{ route('categorias.restore', encrypt($categoria->id)) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-success rounded-pill px-3"
                                                        onclick="return confirm('¿Restaurar esta categoría?')"
                                                        title="Restaurar">
                                                    <i class="fas fa-undo me-1"></i> Restaurar
                                                </button>
                                            </form>
                                            <form action="{{ route('categorias.forceDelete', encrypt($categoria->id)) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                        onclick="return confirm('¿Eliminar permanentemente?')"
                                                        title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <div class="d-flex justify-content-center gap-2">
                                            <button
                                                class="btn btn-sm btn-outline-info rounded-pill px-2"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editarCategoriaModal"
                                                data-id="{{ $categoria->id }}"
                                                data-name="{{ $categoria->name }}"
                                                data-slug="{{ $categoria->slug }}"
                                                data-description="{{ $categoria->description }}"
                                                data-parent="{{ $categoria->parent_id }}"
                                                title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <button class="btn btn-sm btn-outline-danger rounded-pill px-2"
                                                        onclick="return confirm('¿Eliminar esta categoría?')"
                                                        title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                                        <h5 class="fw-bold">No se encontraron categorías</h5>
                                        <p class="small">Intente con otros términos de búsqueda o cree una nueva categoría.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Crear Mejorado -->
<div class="modal fade" id="crearCategoriaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow overflow-hidden">
            <form action="{{ route('categorias.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 py-3 text-white" style="background: var(--gradient-primary) !important;">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-plus-circle me-2"></i> Crear Nueva Categoría
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">NOMBRE DE CATEGORÍA *</label>
                            <input type="text" name="name" class="form-control rounded-3 py-2" required
                                   placeholder="Ej: Programación Web">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">SLUG (URL) *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted" style="font-size: 0.8rem;">/</span>
                                <input type="text" name="slug" class="form-control rounded-end-3 py-2 border-start-0" required
                                       placeholder="ej-programacion-web">
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">DESCRIPCIÓN BREVE</label>
                            <textarea name="description" class="form-control rounded-3" rows="3"
                                      placeholder="De qué trata esta categoría..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">CATEGORÍA PADRE</label>
                            <select name="parent_id" class="form-select rounded-3 py-2">
                                <option value="">-- Ninguna (Categoría principal) --</option>
                                @foreach ($categorias_all as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light p-3">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" style="background: var(--gradient-primary) !important; border: none;">
                        <i class="fas fa-save me-2"></i> Guardar Categoría
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Mejorado -->
<div class="modal fade" id="editarCategoriaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow overflow-hidden">
            <form id="editarCategoriaForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header border-0 py-3 text-white" style="background: var(--gradient-secondary) !important;">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-edit me-2"></i> Editar Categoría
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">NOMBRE DE CATEGORÍA *</label>
                            <input type="text" name="name" id="edit-name" class="form-control rounded-3 py-2" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">SLUG (URL) *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted" style="font-size: 0.8rem;">/</span>
                                <input type="text" name="slug" id="edit-slug" class="form-control rounded-end-3 py-2 border-start-0" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">DESCRIPCIÓN BREVE</label>
                            <textarea name="description" id="edit-description" class="form-control rounded-3" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">CATEGORÍA PADRE</label>
                            <select name="parent_id" id="edit-parent" class="form-select rounded-3 py-2">
                                <option value="">-- Ninguna (Categoría principal) --</option>
                                @foreach ($categorias_all as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
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

<script>
    const editarModal = document.getElementById('editarCategoriaModal')
    editarModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget
        const id = button.getAttribute('data-id')
        const name = button.getAttribute('data-name')
        const slug = button.getAttribute('data-slug')
        const description = button.getAttribute('data-description')
        const parentId = button.getAttribute('data-parent')

        document.getElementById('edit-id').value = id
        document.getElementById('edit-name').value = name
        document.getElementById('edit-slug').value = slug
        document.getElementById('edit-description').value = description || ''
        document.getElementById('edit-parent').value = parentId || ''

        document.getElementById('editarCategoriaForm').action = `/categorias/${id}`
    })
</script>
@endsection
