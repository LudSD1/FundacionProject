@section('titulo')
    Usuarios Eliminados
@endsection

@section('content')
<div class="container my-4">
    <div class="card card-modern">
        <div class="card-header-modern">
            <div class="row align-items-center g-3">
                <div class="col-lg-4 col-md-12">
                    <div class="action-buttons-header">
                        <a href="{{ route('ListaUsuarios') }}" class="btn btn-modern btn-create" data-bs-toggle="tooltip" title="Volver a lista de usuarios">
                            <i class="bi bi-arrow-left me-2"></i>
                            <span>Volver</span>
                        </a>
                    </div>
                </div>

                {{-- Filtro de Roles --}}
                <div class="col-lg-4 col-md-6">
                    <form action="{{ route('ListaUsuariosEliminados') }}" method="GET" id="roleFilterForm">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <select name="role" class="form-select search-input-table" onchange="document.getElementById('roleFilterForm').submit()" style="padding-left: 1rem;">
                            <option value="">🔖 Todos los roles</option>
                            <option value="Administrador" {{ request('role') == 'Administrador' ? 'selected' : '' }}>🛡️ Administrador</option>
                            <option value="Docente"        {{ request('role') == 'Docente'        ? 'selected' : '' }}>🎓 Docente</option>
                            <option value="Estudiante"     {{ request('role') == 'Estudiante'     ? 'selected' : '' }}>📚 Estudiante</option>
                        </select>
                    </form>
                </div>

                {{-- Buscador --}}
                <div class="col-lg-4 col-md-6">
                    <form action="{{ route('ListaUsuariosEliminados') }}" method="GET" class="w-100">
                        <input type="hidden" name="role" value="{{ request('role') }}">
                        <div class="search-box-table">
                            <i class="bi bi-search search-icon-table"></i>
                            <input type="text"
                                   class="form-control search-input-table"
                                   placeholder="Buscar usuario eliminado..."
                                   name="search"
                                   value="{{ request('search') }}">
                            <div class="search-indicator"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Alerta de filtros activos --}}
    @if(request('search') || request('role'))
        <div class="alert alert-info mt-3 d-flex justify-content-between align-items-center">
            <span>
                @if(request('search'))
                    Búsqueda: <strong>{{ request('search') }}</strong>
                @endif
                @if(request('role'))
                    &nbsp; Rol: <strong class="badge bg-primary">{{ request('role') }}</strong>
                @endif
                &nbsp;— <strong>{{ $usuarios->total() }}</strong> resultado(s) encontrado(s)
            </span>
            <a href="{{ route('ListaUsuariosEliminados') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-x-circle me-1"></i> Limpiar filtros
            </a>
        </div>
    @endif

    <div class="table-responsive table-container-modern">
        <table class="table table-modern align-middle">
            <thead>
                <tr>
                    <th width="5%">
                        <div class="th-content">
                            <i class="bi bi-hash"></i><span>Nº</span>
                        </div>
                    </th>
                    <th width="28%">
                        <div class="th-content">
                            <i class="bi bi-person-badge"></i><span>Nombre y Apellidos</span>
                        </div>
                    </th>
                    <th width="12%">
                        <div class="th-content">
                            <i class="bi bi-shield-fill"></i><span>Rol</span>
                        </div>
                    </th>
                    <th width="15%">
                        <div class="th-content">
                            <i class="bi bi-telephone-fill"></i><span>Celular</span>
                        </div>
                    </th>
                    <th width="25%">
                        <div class="th-content">
                            <i class="bi bi-envelope-fill"></i><span>Correo</span>
                        </div>
                    </th>
                    <th width="15%" class="text-center">
                        <div class="th-content justify-content-center">
                            <i class="bi bi-gear-fill"></i><span>Acciones</span>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($usuarios as $usuario)
                    <tr class="curso-row">
                        <td>
                            <span class="row-number">{{ $loop->iteration }}</span>
                        </td>
                        <td>
                            <div class="teacher-cell">
                                <i class="bi bi-person-badge"></i>
                                <span>{{ $usuario->name }} {{ $usuario->lastname1 }} {{ $usuario->lastname2 }}</span>
                            </div>
                        </td>
                        <td>
                            @php $rol = $usuario->getRoleNames()->first(); @endphp
                            @if($rol == 'Administrador')
                                <span class="badge bg-danger">🛡️ Administrador</span>
                            @elseif($rol == 'Docente')
                                <span class="badge bg-success">🎓 Docente</span>
                            @elseif($rol == 'Estudiante')
                                <span class="badge bg-primary">📚 Estudiante</span>
                            @else
                                <span class="badge bg-secondary">Sin rol</span>
                            @endif
                        </td>
                        <td>
                            <div class="teacher-cell">
                                <i class="bi bi-telephone-fill"></i>
                                <span>+{{ $usuario->Celular }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="teacher-cell">
                                <i class="bi bi-envelope"></i>
                                <span>{{ $usuario->email }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons-cell">
                                <a class="btn-action-modern btn-view"
                                   href="{{ route('perfil', [encrypt($usuario->id)]) }}"
                                   data-bs-toggle="tooltip" title="Ver perfil">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a class="btn-action-modern btn-create"
                                   href="{{ route('restaurarUsuario', [encrypt($usuario->id)]) }}"
                                   onclick="mostrarAdvertencia(event, '{{ $rol }}')"
                                   data-bs-toggle="tooltip" title="Restaurar usuario">
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
                                    <i class="bi bi-person-check"></i>
                                </div>
                                <h5 class="empty-title-table">No hay usuarios eliminados</h5>
                                <p class="empty-text-table">
                                    @if(request('role'))
                                        No se encontraron <strong>{{ request('role') }}s</strong> eliminados.
                                    @else
                                        No se encontraron registros de usuarios eliminados.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $usuarios->appends(['search' => request('search'), 'role' => request('role')])->links('custom-pagination') }}
    </div>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'Entendido'
                });
            });
        </script>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function mostrarAdvertencia(event, rol) {
    event.preventDefault();
    const href = event.target.closest('a').getAttribute('href');

    Swal.fire({
        title: '¿Estás seguro?',
        text: `Esta acción restaurará a este ${rol || 'usuario'}. ¿Deseas continuar?`,
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
            window.location.href = href;
        }
    });
}
</script>

@endsection

@include('layout')