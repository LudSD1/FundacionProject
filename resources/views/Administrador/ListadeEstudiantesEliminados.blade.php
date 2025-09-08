
@section('titulo')
    Lista de Estudiantes

@endsection




@section('content')
<div class="container-fluid">
    <!-- Header con título -->
    <div class="row mb-4">
        <div class="col-12">

            <div class="d-flex justify-content-between align-items-center">
                     <a href="{{ route('ListaEstudiantes') }}" class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Volver
                </a>
                <h4 class="mb-0 text-muted">
                    <i class="fas fa-user-graduate me-2"></i>
                    Estudiantes Eliminados
                </h4>
            </div>
        </div>
    </div>

    <!-- Barra de búsqueda -->
    <div class="row mb-3">
        <div class="col-lg-6 col-md-8">
            <div class="input-group">
                <span class="input-group-text bg-light">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input class="form-control" placeholder="Buscar estudiante..." type="text" id="searchInput">
            </div>
        </div>
    </div>

    <!-- Tabla responsive -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="fw-semibold">#</th>
                            <th scope="col" class="fw-semibold">Nombre y Apellidos</th>
                            <th scope="col" class="fw-semibold">Celular</th>
                            <th scope="col" class="fw-semibold">Correo</th>
                            <th scope="col" class="fw-semibold">Edad</th>
                            <th scope="col" class="fw-semibold text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($estudiantes as $estudiantes)
                        <tr>
                            <td class="text-muted">
                                {{ $loop->iteration }}
                            </td>
                            <td class="fw-medium">
                                {{ $estudiantes->name }}
                                {{ $estudiantes->lastname1 }}
                                {{ $estudiantes->lastname2 }}
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ $estudiantes->Celular }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">{{ $estudiantes->email }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info text-white">
                                    {{ $estudiantes->age() }} años
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">

                                    <a href="{{route('restaurarUsuario', [encrypt($estudiantes->id)])}}"
                                       onclick="mostrarAdvertencia(event)"
                                       class="btn btn-outline-success"
                                       title="Restaurar estudiante">
                                        <i class="fas fa-undo"></i>
                                        <span class="d-none d-lg-inline ms-1">Restaurar</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center text-muted">
                                    <i class="fas fa-user-graduate-slash fa-3x mb-3 opacity-50"></i>
                                    <h5 class="mb-1">No hay estudiantes eliminados</h5>
                                    <p class="mb-0">No se encontraron registros de estudiantes eliminados.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Alertas de éxito -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
$(document).ready(function() {
    // Manejo del evento de entrada en el campo de búsqueda
    $('#searchInput').on('input', function() {
        var searchText = $(this).val().toLowerCase();

        // Filtra las filas de la tabla basándote en el valor del campo de búsqueda
        $('tbody tr').each(function() {
            var rowText = $(this).text().toLowerCase();
            if (rowText.indexOf(searchText) > -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });

        // Mostrar mensaje si no hay resultados
        var visibleRows = $('tbody tr:visible').length;
        if (visibleRows === 0 && searchText !== '') {
            if ($('.no-results').length === 0) {
                $('tbody').append(
                    '<tr class="no-results">' +
                    '<td colspan="6" class="text-center py-4 text-muted">' +
                    '<i class="fas fa-search-minus fa-2x mb-2 opacity-50"></i><br>' +
                    'No se encontraron resultados para "<strong>' + searchText + '</strong>"' +
                    '</td>' +
                    '</tr>'
                );
            }
        } else {
            $('.no-results').remove();
        }
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function mostrarAdvertencia(event) {
    event.preventDefault();

    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción retornará a este estudiante eliminado. ¿Estás seguro de que deseas continuar?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, restaurar',
        cancelButtonText: 'Cancelar',
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-success me-2',
            cancelButton: 'btn btn-secondary'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirige al usuario al enlace original
            window.location.href = event.target.closest('a').getAttribute('href');
        }
    });
}
</script>

@endsection

@include('layout')

