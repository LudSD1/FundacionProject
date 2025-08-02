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
    <form action="{{ route('categorias.index') }}" method="GET" class="mb-3 d-flex">
        <input type="text" name="busqueda" class="form-control me-2" placeholder="Buscar categoría..." value="{{ request('busqueda') }}">
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>

    <!-- Tabla de categorias -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Padre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categorias as $categoria)
                <tr>
                    <td>{{ $categoria->id }}</td>
                    <td>{{ $categoria->name }}</td>
                    <td>{{ $categoria->parent ? $categoria->parent->name : '-' }}</td>
                    <td>
                        <button
                            class="btn btn-sm btn-info text-white"
                            data-bs-toggle="modal"
                            data-bs-target="#editarCategoriaModal"
                            data-id="{{ $categoria->id }}"
                            data-name="{{ $categoria->name }}"
                            data-slug="{{ $categoria->slug }}"
                            data-description="{{ $categoria->description }}"
                            data-parent="{{ $categoria->parent_id }}"
                        >Editar</button>

                        @if ($categoria->trashed())
                            <form action="{{ route('categorias.restore', encrypt($categoria->id)) }}" method="POST" style="display:inline-block">
                                @csrf
                                <button class="btn btn-sm btn-warning">Restaurar</button>
                            </form>
                        @else
                            <form action="{{ route('categorias.destroy', encrypt($categoria)) }}" method="POST" style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar?')">Eliminar</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">No se encontraron categorías.</td>
                </tr>
            @endforelse
        </tbody>

    </table>
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
