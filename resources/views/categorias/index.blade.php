<!-- resources/views/categorias/index.blade.php -->

@extends('layout')

@section('titulo')
    Categorias
@endsection

@section('content')
<div class="container-fluid mt-4">
    <!-- Header con título y botón crear -->
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">
                <i class="fas fa-folder me-2 text-primary"></i>
                Gestión de Categorías
            </h2>
            <p class="text-muted mb-0">Administra las categorías del sistema</p>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearCategoriaModal">
                <i class="fas fa-plus me-2"></i>
                Nueva Categoría
            </button>
        </div>
    </div>

    <!-- Barra de búsqueda mejorada -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('categorias.index') }}" method="GET" class="row g-3">
                <input type="hidden" name="tab" value="{{ request('tab', 'activas') }}">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="busqueda" class="form-control"
                               placeholder="Buscar por nombre, descripción..."
                               value="{{ request('busqueda') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>
                            Buscar
                        </button>
                        @if(request('busqueda'))
                            <a href="{{ route('categorias.index', ['tab' => request('tab', 'activas')]) }}"
                               class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>
                                Limpiar
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabs mejorados -->
    <div class="card shadow-sm">
        <div class="card-header bg-white border-0">
            <ul class="nav nav-pills nav-fill" id="categoriaTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ request('tab', 'activas') === 'activas' ? 'active' : '' }}"
                       href="{{ route('categorias.index', ['tab' => 'activas', 'busqueda' => request('busqueda')]) }}">
                        <i class="fas fa-folder-open me-2"></i>
                        Categorías Activas
                        <span class="badge bg-success ms-2">{{ $countActivas }}</span>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ request('tab') === 'eliminadas' ? 'active' : '' }}"
                       href="{{ route('categorias.index', ['tab' => 'eliminadas', 'busqueda' => request('busqueda')]) }}">
                        <i class="fas fa-trash me-2"></i>
                        Categorías Eliminadas
                        <span class="badge bg-danger ms-2">{{ $countEliminadas }}</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Tabla mejorada -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="fw-semibold">ID</th>
                            <th scope="col" class="fw-semibold">
                                <i class="fas fa-tag me-1"></i>
                                Nombre
                            </th>
                            <th scope="col" class="fw-semibold">
                                <i class="fas fa-sitemap me-1"></i>
                                Categoría Padre
                            </th>
                            <th scope="col" class="fw-semibold text-center">
                                @if(request('tab') === 'eliminadas')
                                    <i class="fas fa-calendar me-1"></i>
                                    Eliminación / Acciones
                                @else
                                    <i class="fas fa-cogs me-1"></i>
                                    Acciones
                                @endif
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categorias as $categoria)
                            <tr class="{{ $categoria->trashed() ? 'table-light' : '' }}">
                                <td>
                                    <span class="badge bg-light text-dark">#{{ $categoria->id }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center"
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-folder text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-semibold">{{ $categoria->name }}</h6>
                                            @if($categoria->description)
                                                <small class="text-muted">{{ Str::limit($categoria->description, 50) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($categoria->parent)
                                        <span class="badge bg-info text-white">
                                            <i class="fas fa-folder me-1"></i>
                                            {{ $categoria->parent->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">
                                            <i class="fas fa-minus"></i>
                                            Sin padre
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(request('tab') === 'eliminadas')
                                        <!-- Para categorías eliminadas -->
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $categoria->deleted_at->format('d/m/Y H:i') }}
                                            </small>
                                        </div>
                                        <div class="btn-group-vertical btn-group-sm" role="group">
                                            <form action="{{ route('categorias.restore', encrypt($categoria->id)) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-outline-success btn-sm"
                                                        onclick="return confirm('¿Restaurar esta categoría?')"
                                                        title="Restaurar categoría">
                                                    <i class="fas fa-undo me-1"></i>
                                                    Restaurar
                                                </button>
                                            </form>
                                            <form action="{{ route('categorias.forceDelete', encrypt($categoria->id)) }}" method="POST" class="d-inline mt-1">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-outline-danger btn-sm"
                                                        onclick="return confirm('¿Eliminar permanentemente? Esta acción no se puede deshacer.')"
                                                        title="Eliminar permanentemente">
                                                    <i class="fas fa-trash me-1"></i>
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <!-- Para categorías activas -->
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button
                                                class="btn btn-outline-info"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editarCategoriaModal"
                                                data-id="{{ $categoria->id }}"
                                                data-name="{{ $categoria->name }}"
                                                data-slug="{{ $categoria->slug }}"
                                                data-description="{{ $categoria->description }}"
                                                data-parent="{{ $categoria->parent_id }}"
                                                title="Editar categoría">
                                                <i class="fas fa-edit"></i>
                                                <span class="d-none d-lg-inline ms-1">Editar</span>
                                            </button>

                                            <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('delete')
                                                <button class="btn btn-outline-danger"
                                                        onclick="return confirm('¿Eliminar esta categoría?')"
                                                        title="Eliminar categoría">
                                                    <i class="fas fa-trash"></i>
                                                    <span class="d-none d-lg-inline ms-1">Eliminar</span>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center text-muted">
                                        @if(request('tab') === 'eliminadas')
                                            <i class="fas fa-trash fa-3x mb-3 opacity-50"></i>
                                            <h5 class="mb-1">No hay categorías eliminadas</h5>
                                            <p class="mb-0">Todas las categorías están activas.</p>
                                        @else
                                            <i class="fas fa-folder-open fa-3x mb-3 opacity-50"></i>
                                            <h5 class="mb-1">No se encontraron categorías</h5>
                                            <p class="mb-0">Crea tu primera categoría para comenzar.</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Información de búsqueda -->
    @if(request('busqueda'))
        <div class="alert alert-info mt-3" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-search me-2"></i>
                <div>
                    Mostrando resultados para: <strong>"{{ request('busqueda') }}"</strong>
                    en {{ request('tab') === 'eliminadas' ? 'categorías eliminadas' : 'categorías activas' }}
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Modal Crear Mejorado -->
<div class="modal fade" id="crearCategoriaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('categorias.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i>
                        Crear Nueva Categoría
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-tag me-1"></i>
                                    Nombre *
                                </label>
                                <input type="text" name="name" class="form-control" required
                                       placeholder="Ej: Tecnología">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-link me-1"></i>
                                    Slug *
                                </label>
                                <input type="text" name="slug" class="form-control" required
                                       placeholder="Ej: tecnologia">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-align-left me-1"></i>
                            Descripción
                        </label>
                        <textarea name="description" class="form-control" rows="3"
                                  placeholder="Descripción opcional de la categoría"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-sitemap me-1"></i>
                            Categoría Padre (opcional)
                        </label>
                        <select name="parent_id" class="form-select">
                            <option value="">-- Ninguna (Categoría principal) --</option>
                            @foreach ($categorias as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Guardar Categoría
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Mejorado -->
<div class="modal fade" id="editarCategoriaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editarCategoriaForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>
                        Editar Categoría
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-tag me-1"></i>
                                    Nombre *
                                </label>
                                <input type="text" name="name" id="edit-name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-link me-1"></i>
                                    Slug *
                                </label>
                                <input type="text" name="slug" id="edit-slug" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-align-left me-1"></i>
                            Descripción
                        </label>
                        <textarea name="description" id="edit-description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-sitemap me-1"></i>
                            Categoría Padre (opcional)
                        </label>
                        <select name="parent_id" id="edit-parent" class="form-select">
                            <option value="">-- Ninguna (Categoría principal) --</option>
                            @foreach ($categorias as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i>
                        Actualizar Categoría
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Cancelar
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
