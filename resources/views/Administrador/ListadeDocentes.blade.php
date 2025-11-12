@section('titulo')
    Lista de Docentes
@endsection


@section('content')
    <div class="container my-4">
        <div class="card card-modern">
            <div class="card-header-modern">
                <div class="row align-items-center g-3">
                    <div class="col-lg-6 col-md-12">
                        <div class="action-buttons-header">
                            <a href="{{ route('CrearDocente') }}" class="btn btn-modern btn-create">
                                <i class="bi bi-person-plus me-2"></i>
                                <span>Crear Docente</span>
                            </a>
                            <a href="{{ route('DocentesEliminados') }}" class="btn btn-modern btn-deleted">
                                <i class="bi bi-trash-fill me-2"></i>
                                <span>Docentes Eliminados</span>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <form action="{{ route('ListaDocentes') }}" method="GET" class="w-100">
                            <div class="search-box-table">
                                <i class="bi bi-search search-icon-table"></i>
                                <input type="text"
                                       class="form-control search-input-table"
                                       placeholder="Buscar docente..."
                                       name="search"
                                       id="searchInput"
                                       value="{{ request('search') }}">
                                <div class="search-indicator"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @if (request('search'))
            <div class="alert alert-info mt-3">
                Mostrando resultados para: <strong>{{ request('search') }}</strong>
                <a href="{{ route('ListaDocentes') }}" class="float-right">Limpiar búsqueda</a>
            </div>
        @endif

        <div class="table-responsive table-container-modern">
            <table class="table table-modern align-middle">
                <thead class="">
                    <tr>
                        <th width="5%">
                            <div class="th-content">
                                <i class="bi bi-hash"></i>
                                <span>Nº</span>
                            </div>
                        </th>
                        <th width="35%">
                            <div class="th-content">
                                <i class="bi bi-person-badge"></i>
                                <span>Nombre y Apellidos</span>
                            </div>
                        </th>
                        <th width="20%">
                            <div class="th-content">
                                <i class="bi bi-telephone-fill"></i>
                                <span>Celular</span>
                            </div>
                        </th>
                        <th width="20%">
                            <div class="th-content">
                                <i class="bi bi-envelope-fill"></i>
                                <span>Correo</span>
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
                    @forelse ($docentes as $docente)
                        <tr class="curso-row" data-docente-id="{{ $docente->id }}">
                            <td>
                                <span class="row-number">{{ $loop->iteration }}</span>
                            </td>
                            <td>
                                <div class="teacher-cell">
                                    <i class="bi bi-person-badge"></i>
                                    <span>{{ $docente->name }} {{ $docente->lastname1 }} {{ $docente->lastname2 }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="teacher-cell">
                                    <i class="bi bi-telephone-fill"></i>
                                    <span>+{{ $docente->Celular }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="teacher-cell">
                                    <i class="bi bi-envelope"></i>
                                    <span>{{ $docente->email }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons-cell">
                                    <a class="btn-action-modern btn-view" href="{{ route('perfil', [encrypt($docente->id)]) }}"
                                       data-bs-toggle="tooltip" title="Ver perfil">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <form action="{{ route('deleteUser', encrypt($docente->id)) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn-action-modern btn-delete" data-bs-toggle="tooltip" title="Eliminar">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                <div class="empty-state-table">
                                    <div class="empty-icon-table">
                                        <i class="bi bi-exclamation-triangle"></i>
                                    </div>
                                    <h5 class="empty-title-table">No hay docentes registrados</h5>
                                    <p class="empty-text-table">Comienza creando tu primer docente</p>
                                    <a href="{{ route('CrearDocente') }}" class="btn btn-modern btn-create">
                                        <i class="bi bi-person-plus me-2"></i>
                                        Crear Docente
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $docentes->appends(['search' => request('search')])->links('custom-pagination') }}
        </div>
    </div>







    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
@endsection

@include('layout')

<!-- SweetAlert2 para confirmación y tooltips -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips de Bootstrap 5
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (tooltipTriggerEl) { new bootstrap.Tooltip(tooltipTriggerEl); });

    // Confirmación de eliminación
    document.querySelectorAll('.btn-action-modern.btn-delete').forEach(function(btn) {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const form = btn.closest('form');
        Swal.fire({
          title: '¿Estás seguro?',
          text: 'Esta acción eliminará el docente permanentemente.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Sí, eliminar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) form.submit();
        });
      });
    });
  });
</script>
