@section('titulo')
    Usuarios Eliminados
@endsection

@section('content')
<div class="container-fluid py-5">
    {{-- Estructura tbl-card moderna --}}
    <div class="tbl-card">
        {{-- Cabecera con lenguaje visual moderno --}}
        <div class="tbl-card-hero">
            <div class="tbl-hero-left">
                <div class="tbl-hero-eyebrow">
                    <i class="fas fa-user-slash"></i> Papelera de Usuarios
                </div>
                <h2 class="tbl-hero-title">Usuarios Eliminados</h2>
                <p class="tbl-hero-sub">Recupere cuentas de estudiantes, docentes o administradores retirados</p>
            </div>
            <div class="tbl-hero-controls">
                <a href="{{ route('ListaUsuarios') }}" class="tbl-hero-btn tbl-hero-btn-glass">
                    <i class="fas fa-users"></i> Lista de Usuarios
                </a>
            </div>
        </div>

        <div class="card-body p-4">
            <!-- Barra de búsqueda y filtros -->
            <div class="row g-3 mb-4">
                <div class="col-lg-8">
                    <form action="{{ route('ListaUsuariosEliminados') }}" method="GET" class="search-box-table w-100">
                        <input type="hidden" name="role" value="{{ request('role') }}">
                        <i class="fas fa-search search-icon-table"></i>
                        <input type="text" name="search" class="search-input-table"
                               placeholder="Buscar por nombre, correo o celular..."
                               value="{{ request('search') }}">
                        @if(request('search'))
                            <a href="{{ route('ListaUsuariosEliminados', ['role' => request('role')]) }}" class="btn-search-clear">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                        <button type="submit" class="btn-search-icon">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </form>
                </div>
                <div class="col-lg-4">
                    <form action="{{ route('ListaUsuariosEliminados') }}" method="GET" id="roleFilterForm">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i class="fas fa-filter"></i>
                            </span>
                            <select name="role" class="form-select border-start-0 ps-0" onchange="this.form.submit()">
                                <option value="">Todos los roles</option>
                                <option value="Administrador" {{ request('role') == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                                <option value="Docente"        {{ request('role') == 'Docente'        ? 'selected' : '' }}>Docente</option>
                                <option value="Estudiante"     {{ request('role') == 'Estudiante'     ? 'selected' : '' }}>Estudiante</option>
                            </select>
                        </div>
                    </form>
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
                            <th style="width: 48px;"><div class="th-content">#</div></th>
                            <th><div class="th-content">Usuario</div></th>
                            <th><div class="th-content">Rol</div></th>
                            <th><div class="th-content">Contacto</div></th>
                            <th class="text-center"><div class="th-content text-center w-100">Acciones</div></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usuarios as $usuario)
                            <tr class="opacity-75 bg-light">
                                <td><span class="row-number">{{ $loop->iteration }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-secondary bg-opacity-10 rounded-circle p-2 me-3 text-secondary">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $usuario->name }} {{ $usuario->lastname1 }}</div>
                                            <div class="text-muted small"><i class="fas fa-envelope me-1"></i>{{ $usuario->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php $rol = $usuario->getRoleNames()->first(); @endphp
                                    @if($rol == 'Administrador')
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill">
                                            <i class="fas fa-shield-alt me-1"></i> Admin
                                        </span>
                                    @elseif($rol == 'Docente')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill">
                                            <i class="fas fa-chalkboard-teacher me-1"></i> Docente
                                        </span>
                                    @elseif($rol == 'Estudiante')
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill">
                                            <i class="fas fa-user-graduate me-1"></i> Alumno
                                        </span>
                                    @else
                                        <span class="badge bg-light text-muted border px-3 py-2 rounded-pill">Sin rol</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <div class="small fw-bold text-dark"><i class="fas fa-phone-alt me-1 text-muted"></i> +{{ $usuario->Celular }}</div>
                                        <div class="date-badge date-start" style="font-size: 0.7rem;">
                                            <i class="fas fa-calendar-times me-1"></i> Eliminado: {{ $usuario->deleted_at ? $usuario->deleted_at->format('d/m/Y') : 'N/A' }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('perfil', [encrypt($usuario->id)]) }}"
                                           class="btn btn-sm btn-outline-info rounded-pill px-3" title="Ver Perfil">
                                            <i class="fas fa-eye me-1"></i> Perfil
                                        </a>
                                        <a href="{{ route('restaurarUsuario', [encrypt($usuario->id)]) }}"
                                           class="btn btn-sm btn-outline-success rounded-pill px-3"
                                           onclick="mostrarAdvertencia(event, '{{ $rol }}')" title="Restaurar">
                                            <i class="fas fa-undo me-1"></i> Restaurar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-user-check fa-3x mb-3 opacity-25"></i>
                                        <h5 class="fw-bold">No hay usuarios eliminados</h5>
                                        <p class="small">La papelera de usuarios está vacía.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $usuarios->appends(['search' => request('search'), 'role' => request('role')])->links('custom-pagination') }}
            </div>
        </div>
    </div>
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
