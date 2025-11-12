@section('titulo')
    Lista de Estudiantes
@endsection


@section('content')
    <div class="container my-4">
        <div class="card card-modern">
            <div class="card-header-modern">
                <div class="row align-items-center g-3">
                    <div class="col-lg-6 col-md-12">
                        <div class="action-buttons-header">
                            <a href="{{ route('CrearEstudiante') }}" class="btn btn-modern btn-create" data-bs-toggle="tooltip" title="Crear nuevo estudiante">
                                <i class="bi bi-person-plus me-2"></i>
                                <span>Crear Estudiante</span>
                            </a>
                            <a href="{{ route('ListaEstudiantesEliminados') }}" class="btn btn-modern btn-deleted" data-bs-toggle="tooltip" title="Ver estudiantes eliminados">
                                <i class="bi bi-trash-fill me-2"></i>
                                <span>Estudiantes Eliminados</span>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <form action="{{ route('ListaEstudiantes') }}" method="GET" class="w-100">
                            <div class="search-box-table">
                                <i class="bi bi-search search-icon-table"></i>
                                <input type="text"
                                       class="form-control search-input-table"
                                       placeholder="Buscar estudiante..."
                                       name="search"
                                       id="searchInput"
                                       value="{{ request('search') }}">
                                <div class="search-indicator"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @if (request('search'))
            <div class="alert alert-info mt-3">
                Mostrando resultados para: <strong>{{ request('search') }}</strong>
                <a href="{{ route('ListaEstudiantes') }}" class="float-right">Limpiar búsqueda</a>
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
                    @forelse ($estudiantes as $estudiante)
                        <tr class="curso-row" data-estudiante-id="{{ $estudiante->id }}">
                            <td>
                                <span class="row-number">{{ $loop->iteration }}</span>
                            </td>
                            <td>
                                <div class="teacher-cell">
                                    <i class="bi bi-person-badge"></i>
                                    <span>{{ $estudiante->name }} {{ $estudiante->lastname1 }} {{ $estudiante->lastname2 }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="teacher-cell">
                                    <i class="bi bi-telephone-fill"></i>
                                    <span>+{{ $estudiante->Celular }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="teacher-cell">
                                    <i class="bi bi-envelope"></i>
                                    <span>{{ $estudiante->email }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons-cell">
                                    <a class="btn-action-modern btn-view" href="{{ route('perfil', [encrypt($estudiante->id)]) }}" data-bs-toggle="tooltip" title="Ver perfil">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <form action="{{ route('deleteUser', encrypt($estudiante->id)) }}" method="POST" class="d-inline">
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
                                    <h5 class="empty-title-table">No hay estudiantes registrados</h5>
                                    <p class="empty-text-table">Comienza creando tu primer estudiante</p>
                                    <a href="{{ route('CrearEstudiante') }}" class="btn btn-modern btn-create">
                                        <i class="bi bi-person-plus me-2"></i>
                                        Crear Estudiante
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $estudiantes->appends(['search' => request('search')])->links('custom-pagination') }}
        </div>
    </div>

    
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
          text: 'Esta acción eliminará el estudiante permanentemente.',
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
