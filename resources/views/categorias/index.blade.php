<!-- resources/views/categorias/index.blade.php -->

@extends('layout')

@section('titulo')
    Categorias
@endsection

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Categorias</h1>

    <!-- Boton para abrir modal crear -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#crearCategoriaModal">
        Crear Categoria
    </button>

    <!-- Formulario de búsqueda -->
    <form action="{{ route('categorias.index') }}" method="GET" class="mb-3 d-flex">
        <input type="hidden" name="tab" value="{{ request('tab', 'activas') }}">
        <input type="text" name="busqueda" class="form-control me-2" placeholder="Buscar categoría..." value="{{ request('busqueda') }}">
        <button type="submit" class="btn btn-primary">Buscar</button>
        @if(request('busqueda'))
            <a href="{{ route('categorias.index', ['tab' => request('tab', 'activas')]) }}" class="btn btn-secondary ms-2">Limpiar</a>
        @endif
    </form>

    <!-- Tabs para separar categorías activas y eliminadas -->
    <ul class="nav nav-tabs mb-3" id="categoriaTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ request('tab', 'activas') === 'activas' ? 'active' : '' }}"
               href="{{ route('categorias.index', ['tab' => 'activas', 'busqueda' => request('busqueda')]) }}">
                Categorías Activas
                <span class="badge bg-primary ms-1">{{ $countActivas }}</span>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ request('tab') === 'eliminadas' ? 'active' : '' }}"
               href="{{ route('categorias.index', ['tab' => 'eliminadas', 'busqueda' => request('busqueda')]) }}">
                Categorías Eliminadas
                <span class="badge bg-danger ms-1">{{ $countEliminadas }}</span>
            </a>
        </li>
    </ul>

    <!-- Tabla de categorias -->
    <div class="tab-content">
        <div class="tab-pane fade show active">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Padre</th>
                        <th>
                            @if(request('tab') === 'eliminadas')
                                Fecha Eliminación
                            @else
                                Acciones
                            @endif
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categorias as $categoria)
                        <tr class="{{ $categoria->trashed() ? 'table-secondary' : '' }}">
                            <td>{{ $categoria->id }}</td>
                            <td>{{ $categoria->name }}</td>
                            <td>{{ $categoria->parent ? $categoria->parent->name : '-' }}</td>
                            <td>
                                @if(request('tab') === 'eliminadas')
                                    <!-- Solo mostrar restaurar para categorías eliminadas -->
                                    <small class="text-muted d-block">{{ $categoria->deleted_at->format('d/m/Y H:i') }}</small>
                                    <form action="{{ route('categorias.restore', encrypt($categoria->id)) }}" method="POST" style="display:inline-block" class="mt-1">
                                        @csrf
                                        <button class="btn btn-sm btn-success" onclick="return confirm('¿Restaurar esta categoría?')">
                                            <i class="fas fa-undo"></i> Restaurar
                                        </button>
                                    </form>
                                    <form action="{{ route('categorias.forceDelete', encrypt($categoria->id)) }}" method="POST" style="display:inline-block" class="mt-1">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar permanentemente? Esta acción no se puede deshacer.')">
                                            <i class="fas fa-trash"></i> Eliminar permanentemente
                                        </button>
                                    </form>
                                @else
                                    <!-- Acciones para categorías activas -->
                                    <button
                                        class="btn btn-sm btn-info text-white me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editarCategoriaModal"
                                        data-id="{{ $categoria->id }}"
                                        data-name="{{ $categoria->name }}"
                                        data-slug="{{ $categoria->slug }}"
                                        data-description="{{ $categoria->description }}"
                                        data-parent="{{ $categoria->parent_id }}"
                                    >
                                        <i class="fas fa-edit"></i> Editar
                                    </button>

                                    <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta categoría?')">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                @if(request('tab') === 'eliminadas')
                                    <i class="fas fa-trash fa-2x mb-2 d-block"></i>
                                    No hay categorías eliminadas.
                                @else
                                    <i class="fas fa-folder-open fa-2x mb-2 d-block"></i>
                                    No se encontraron categorías activas.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(request('busqueda'))
        <div class="alert alert-info mt-3">
            <i class="fas fa-search"></i>
            Mostrando resultados para: <strong>"{{ request('busqueda') }}"</strong>
            en {{ request('tab') === 'eliminadas' ? 'categorías eliminadas' : 'categorías activas' }}
        </div>
    @endif
</div>

<!-- Modal Crear -->
<div class="modal fade" id="crearCategoriaModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('categorias.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Crear Categoria</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Slug</label>
            <input type="text" name="slug" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Descripción</label>
            <textarea name="description" class="form-control"></textarea>
          </div>
          <div class="mb-3">
            <label>Categoría Padre (opcional)</label>
            <select name="parent_id" class="form-select">
                <option value="">-- Ninguna --</option>
                @foreach ($categorias as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Guardar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Editar -->
<div class="modal fade" id="editarCategoriaModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editarCategoriaForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">Editar Categoria</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit-id">
          <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="name" id="edit-name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Slug</label>
            <input type="text" name="slug" id="edit-slug" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Descripción</label>
            <textarea name="description" id="edit-description" class="form-control"></textarea>
          </div>
          <div class="mb-3">
            <label>Categoría Padre (opcional)</label>
            <select name="parent_id" id="edit-parent" class="form-select">
                <option value="">-- Ninguna --</option>
                @foreach ($categorias as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Actualizar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
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
        document.getElementById('edit-description').value = description
        document.getElementById('edit-parent').value = parentId

        document.getElementById('editarCategoriaForm').action = `/categorias/${id}`
    })
</script>
@endsection
