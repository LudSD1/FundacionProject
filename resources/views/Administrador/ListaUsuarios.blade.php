@extends('layout')
@section('titulo') Lista de Usuarios @endsection

@section('content')
<div class="container my-4">
<div class="tbl-card">

    {{-- ╔══════════════════════════════════════╗
         ║  HERO                               ║
         ╚══════════════════════════════════════╝ --}}
    <div class="tbl-card-hero">

        <div class="tbl-hero-left">
            <div class="tbl-hero-eyebrow">
                <i class="bi bi-people-fill"></i> Administración
            </div>
            <h2 class="tbl-hero-title">Lista de Usuarios</h2>
            <p class="tbl-hero-sub">Gestiona estudiantes, docentes y administradores</p>
        </div>

        <div class="tbl-hero-controls">

            {{-- Botones --}}
            <a href="{{ route('CrearUsuario') }}"
               class="tbl-hero-btn tbl-hero-btn-primary"
               data-bs-toggle="tooltip" title="Crear nuevo usuario">
                <i class="bi bi-person-plus-fill"></i> Crear Usuario
            </a>
            <a href="{{ route('ListaUsuariosEliminados') }}"
               class="tbl-hero-btn tbl-hero-btn-glass"
               data-bs-toggle="tooltip" title="Ver usuarios eliminados">
                <i class="bi bi-trash-fill"></i> Eliminados
            </a>

            {{-- Filtro rol --}}
            <form action="{{ route('ListaUsuarios') }}" method="GET" id="roleFilterForm">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <div class="tbl-hero-select-wrap">
                    <i class="bi bi-shield-fill tbl-hero-select-icon"></i>
                    <select name="role" class="tbl-hero-select" id="roleFilterSelect">
                        <option value="">Todos los roles</option>
                        <option value="Administrador" {{ request('role') == 'Administrador' ? 'selected' : '' }}>
                            Administrador
                        </option>
                        <option value="Docente" {{ request('role') == 'Docente' ? 'selected' : '' }}>
                            Docente
                        </option>
                        <option value="Estudiante" {{ request('role') == 'Estudiante' ? 'selected' : '' }}>
                            Estudiante
                        </option>
                    </select>
                </div>
            </form>

            {{-- Buscador --}}
            <form action="{{ route('ListaUsuarios') }}" method="GET">
                <input type="hidden" name="role" value="{{ request('role') }}">
                <div class="tbl-hero-search">
                    <i class="bi bi-search tbl-hero-search-icon"></i>
                    <input type="text"
                           class="tbl-hero-search-input"
                           placeholder="Buscar usuario..."
                           name="search"
                           value="{{ request('search') }}">
                </div>
            </form>

        </div>
    </div>{{-- /tbl-card-hero --}}


    {{-- Filtros activos --}}
    @if(request('search') || request('role'))
    <div class="tbl-filter-bar">
        <div class="tbl-filter-bar-left">
            <i class="bi bi-funnel-fill"></i>
            @if(request('search'))
                Búsqueda: <strong>{{ request('search') }}</strong>
            @endif
            @if(request('role'))
                @if(request('search')) · @endif
                Rol: <span class="tbl-filter-chip">{{ request('role') }}</span>
            @endif
            — <strong>{{ $usuarios->total() }}</strong> resultado(s)
        </div>
        <a href="{{ route('ListaUsuarios') }}" class="tbl-filter-clear">
            <i class="bi bi-x-circle"></i> Limpiar filtros
        </a>
    </div>
    @endif


    {{-- ╔══════════════════════════════════════╗
         ║  TABLA                              ║
         ╚══════════════════════════════════════╝ --}}
    <div class="table-container-modern">
        <table class="table-modern">
            <thead>
                <tr>
                    <th width="5%">
                        <div class="th-content">
                            <i class="bi bi-hash"></i><span>Nº</span>
                        </div>
                    </th>
                    <th width="30%">
                        <div class="th-content">
                            <i class="bi bi-person-badge-fill"></i><span>Nombre y Apellidos</span>
                        </div>
                    </th>
                    <th width="13%">
                        <div class="th-content">
                            <i class="bi bi-shield-fill"></i><span>Rol</span>
                        </div>
                    </th>
                    <th width="14%">
                        <div class="th-content">
                            <i class="bi bi-telephone-fill"></i><span>Celular</span>
                        </div>
                    </th>
                    <th width="22%">
                        <div class="th-content">
                            <i class="bi bi-envelope-fill"></i><span>Correo</span>
                        </div>
                    </th>
                    <th width="16%" class="text-center">
                        <div class="th-content justify-content-center">
                            <i class="bi bi-gear-fill"></i><span>Acciones</span>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $usuario)
                @php $rol = $usuario->getRoleNames()->first(); @endphp
                <tr data-usuario-id="{{ $usuario->id }}">

                    <td><span class="row-number">{{ $loop->iteration }}</span></td>

                    {{-- Nombre --}}
                    <td>
                        <div class="teacher-cell">
                            <div class="tbl-avatar">
                                {{ strtoupper(substr($usuario->name, 0, 1)) }}
                            </div>
                            <span>
                                {{ $usuario->name }}
                                {{ $usuario->lastname1 }}
                                {{ $usuario->lastname2 }}
                            </span>
                        </div>
                    </td>

                    {{-- Rol — FIX 2: sin badge Bootstrap --}}
                    <td>
                        @if($rol === 'Administrador')
                            <span class="role-badge role-badge--admin">
                                <i class="bi bi-shield-fill"></i> Administrador
                            </span>
                        @elseif($rol === 'Docente')
                            <span class="role-badge role-badge--docente">
                                <i class="bi bi-mortarboard-fill"></i> Docente
                            </span>
                        @elseif($rol === 'Estudiante')
                            <span class="role-badge role-badge--estudiante">
                                <i class="bi bi-book-fill"></i> Estudiante
                            </span>
                        @else
                            <span class="role-badge role-badge--none">
                                <i class="bi bi-dash-circle"></i> Sin rol
                            </span>
                        @endif
                    </td>

                    {{-- Celular --}}
                    <td>
                        <div class="teacher-cell">
                            <i class="bi bi-telephone-fill"></i>
                            <span>+{{ $usuario->Celular ?? '—' }}</span>
                        </div>
                    </td>

                    {{-- Email --}}
                    <td>
                        <div class="teacher-cell">
                            <i class="bi bi-envelope-fill"></i>
                            <span class="tbl-email">{{ $usuario->email }}</span>
                        </div>
                    </td>

                    {{-- Acciones --}}
                    <td>
                        <div class="action-buttons-cell">
                            <a class="btn-action-modern btn-view"
                               href="{{ route('perfil', [encrypt($usuario->id)]) }}"
                               data-bs-toggle="tooltip" title="Ver perfil">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <a class="btn-action-modern btn-edit"
                               href="{{ route('perfil', [encrypt($usuario->id)]) }}"
                               data-bs-toggle="tooltip" title="Editar usuario">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            {{-- FIX 9: form con clase para SweetAlert --}}
                            <form action="{{ route('deleteUser', encrypt($usuario->id)) }}"
                                  method="POST"
                                  class="usr-delete-form">
                                @csrf
                                <button type="submit"
                                        class="btn-action-modern btn-delete"
                                        data-bs-toggle="tooltip"
                                        title="Eliminar usuario">
                                    <i class="bi bi-ban"></i>
                                </button>
                            </form>
                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state-table">
                            <div class="empty-icon-table">
                                <i class="bi bi-people"></i>
                            </div>
                            <h5 class="empty-title-table">No se encontraron usuarios</h5>
                            <p class="empty-text-table">
                                @if(request('role'))
                                    No hay usuarios con el rol
                                    <strong>{{ request('role') }}</strong>
                                @else
                                    Comienza creando tu primer usuario
                                @endif
                            </p>
                            <a href="{{ route('CrearUsuario') }}"
                               class="tbl-hero-btn tbl-hero-btn-primary"
                               style="width:auto; margin: 0 auto;">
                                <i class="bi bi-person-plus-fill"></i> Crear Usuario
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    <div class="tbl-pagination">
        {{ $usuarios->appends(['search' => request('search'), 'role' => request('role')])->links('custom-pagination') }}
    </div>

</div>{{-- /tbl-card --}}
</div>{{-- /container --}}
@endsection


<style>
/* ── Badges de rol (solo esta vista los usa) ── */
.role-badge {
    display:       inline-flex;
    align-items:   center;
    gap:           .3rem;
    font-size:     .73rem;
    font-weight:   700;
    padding:       .24rem .7rem;
    border-radius: 50px;
    white-space:   nowrap;
}
.role-badge i { font-size: .7rem; }

.role-badge--admin      { background: rgba(220,38,38,.10);  color: #dc2626; }
.role-badge--docente    { background: rgba(22,163,74,.10);  color: #16a34a; }
.role-badge--estudiante { background: rgba(20,93,160,.10);  color: #145da0; }
.role-badge--none       { background: rgba(100,116,139,.09);color: #64748b; }

/* Email truncado */
.tbl-email {
    max-width:     180px;
    overflow:      hidden;
    text-overflow: ellipsis;
    white-space:   nowrap;
    display:       inline-block;
    vertical-align: middle;
    font-size:     .85rem;
    color:         #64748b;
}
</style>


<script>
(function () {
    document.addEventListener('DOMContentLoaded', function () {

        /* FIX 5: sin onchange inline */
        document.getElementById('roleFilterSelect')
            ?.addEventListener('change', function () {
                document.getElementById('roleFilterForm')?.submit();
            });

        /* Tooltips */
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
            new bootstrap.Tooltip(el);
        });

        document.querySelectorAll('.usr-delete-form').forEach(form => {
            form.querySelector('button[type="submit"]')
                ?.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    Swal.fire({
                        title            : '¿Eliminar usuario?',
                        text             : 'Esta acción no se puede deshacer.',
                        icon             : 'warning',
                        showCancelButton : true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor : '#145da0',
                        confirmButtonText : 'Sí, eliminar',
                        cancelButtonText  : 'Cancelar',
                    }).then(r => { if (r.isConfirmed) form.submit(); });
                });
        });

    });
})();
</script>