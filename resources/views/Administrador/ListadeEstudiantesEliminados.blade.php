
@section('titulo')
    Lista de Estudiantes

@endsection




@section('content')
    <div class="container my-4">
        <div class="card card-modern">
            <div class="card-header-modern">
                <div class="row align-items-center g-3">
                    <div class="col-lg-6 col-md-12">
                        <div class="action-buttons-header d-flex align-items-center gap-2">
                            <a href="{{ route('ListaEstudiantes') }}" class="btn btn-modern btn-deleted" data-bs-toggle="tooltip" title="Volver a lista de estudiantes">
                                <i class="bi bi-arrow-left me-2"></i>
                                <span>Volver</span>
                            </a>
                            <h5 class="mb-0 text-muted d-inline-flex align-items-center">
                                <i class="bi bi-person-dash me-2"></i>
                                Estudiantes Eliminados
                            </h5>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="search-box-table">
                            <i class="bi bi-search search-icon-table"></i>
                            <input type="text" class="form-control search-input-table" placeholder="Buscar estudiante..." id="searchInput">
                            <div class="search-indicator"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                        <th width="30%">
                            <div class="th-content">
                                <i class="bi bi-person-badge"></i>
                                <span>Nombre y Apellidos</span>
                            </div>
                        </th>
                        <th width="15%">
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
                        <th width="10%">
                            <div class="th-content">
                                <i class="bi bi-hourglass-split"></i>
                                <span>Edad</span>
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
                    @forelse ($estudiantes as $estudiantes)
                        <tr class="curso-row" data-estudiante-id="{{ $estudiantes->id }}">
                            <td>
                                <span class="row-number">{{ $loop->iteration }}</span>
                            </td>
                            <td>
                                <div class="teacher-cell">
                                    <i class="bi bi-person-badge"></i>
                                    <span>{{ $estudiantes->name }} {{ $estudiantes->lastname1 }} {{ $estudiantes->lastname2 }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="teacher-cell">
                                    <i class="bi bi-telephone-fill"></i>
                                    <span>+{{ $estudiantes->Celular }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="teacher-cell">
                                    <i class="bi bi-envelope"></i>
                                    <span>{{ $estudiantes->email }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="teacher-cell">
                                    <i class="bi bi-hourglass-split"></i>
                                    <span>{{ $estudiantes->age() }} años</span>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons-cell">
                                    <a href="{{route('restaurarUsuario', [encrypt($estudiantes->id)])}}" class="btn-action-modern btn-restore" data-bs-toggle="tooltip" title="Restaurar estudiante">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </a>
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
                                    <h5 class="empty-title-table">No hay estudiantes eliminados</h5>
                                    <p class="empty-text-table">No se encontraron registros de estudiantes eliminados.</p>
                                    <a href="{{ route('ListaEstudiantes') }}" class="btn btn-modern btn-deleted">
                                        <i class="bi bi-arrow-left me-2"></i>
                                        Volver a Estudiantes
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <i class="bi bi-check-circle me-2"></i>
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

<!-- SweetAlert2 y búsqueda/tooltip sin jQuery -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips Bootstrap 5
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle]'));
    tooltipTriggerList.forEach(function (tooltipTriggerEl) { new bootstrap.Tooltip(tooltipTriggerEl); });

    // Búsqueda en tiempo real sin jQuery
    var searchInput = document.getElementById('searchInput');
    var tbody = document.querySelector('tbody');
    if (searchInput && tbody) {
      searchInput.addEventListener('input', function() {
        var searchText = searchInput.value.toLowerCase();
        var rows = tbody.querySelectorAll('tr');
        var anyVisible = false;
        rows.forEach(function(row) {
          if (row.classList.contains('no-results')) return;
          var rowText = row.textContent.toLowerCase();
          var visible = rowText.indexOf(searchText) > -1;
          row.style.display = visible ? '' : 'none';
          if (visible) anyVisible = true;
        });
        var noRes = tbody.querySelector('.no-results');
        if (!anyVisible && searchText !== '') {
          if (!noRes) {
            var tr = document.createElement('tr');
            tr.className = 'no-results';
            tr.innerHTML = '<td colspan="6" class="text-center py-4 text-muted">'+
                           '<i class="bi bi-search"></i><br>'+
                           'No se encontraron resultados para "<strong>' + searchText + '</strong>"'+
                           '</td>';
            tbody.appendChild(tr);
          }
        } else if (noRes) {
          noRes.remove();
        }
      });
    }

    // Confirmación de restauración
    document.querySelectorAll('.btn-action-modern.btn-restore').forEach(function(btn) {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var href = btn.getAttribute('href');
        Swal.fire({
          title: '¿Restaurar estudiante?',
          text: 'Esta acción retornará al estudiante eliminado.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#198754',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Sí, restaurar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = href;
          }
        });
      });
    });
  });
</script>

