@php
    $estado = request('estado', 'all');
    $q = request('q');
    $query = \App\Models\Cursos::with('docente')->latest();
    $hoy = \Carbon\Carbon::today();

    if ($estado !== 'all') {
        if ($estado === 'Activo') {
            $query->whereDate('fecha_ini', '<=', $hoy)->whereDate('fecha_fin', '>=', $hoy);
        } elseif ($estado === 'Inactivo') {
            $query->whereDate('fecha_ini', '>', $hoy);
        } elseif ($estado === 'Finalizado') {
            $query->whereDate('fecha_fin', '<', $hoy);
        }
    }

    if ($q) {
        $query->where('nombreCurso', 'like', '%' . $q . '%');
    }

    $reports = $query->paginate(10);
@endphp

<div class="row mb-3">
    <div class="col-md-4">
        <form id="searchForm" method="GET" action="">
            <input type="hidden" name="tab" value="reportes">
            <input type="hidden" name="estado" value="{{ request('estado', 'all') }}">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control search-input" name="q" value="{{ request('q') }}"
                    placeholder="Buscar cursos...">
            </div>
        </form>
    </div>
    <div class="col-md-4">
        <form id="filterForm" method="GET" action="">
            <input type="hidden" name="tab" value="reportes">
            <input type="hidden" name="q" value="{{ request('q') }}">
            <select class="form-select filter-select" name="estado" onchange="this.form.submit()">
                <option value="all" {{ request('estado') == 'all' ? 'selected' : '' }}>Todos los cursos</option>
                <option value="Activo" {{ request('estado') == 'Activo' ? 'selected' : '' }}>Activos</option>
                <option value="Inactivo" {{ request('estado') == 'Inactivo' ? 'selected' : '' }}>Inactivos</option>
                <option value="Finalizado" {{ request('estado') == 'Finalizado' ? 'selected' : '' }}>Finalizados
                </option>
            </select>
        </form>
    </div>
    <div class="col-md-4 text-end">
        <a href="?tab=reportes&{{ http_build_query(request()->except('tab', 'page')) }}&export=cursos"
            class="btn btn-outline-primary btn-sm">
            <i class="bi bi-download"></i> Exportar Cursos
        </a>
    </div>
</div>


<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Curso</th>
                <th>Instructor</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reports as $curso)
                <tr class="course-row" data-estado="{{ $curso->estado }}"
                    data-nombre="{{ strtolower($curso->nombreCurso) }}">
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="{{ $curso->imagen ? asset('storage/' . $curso->imagen) : asset('images/default-course.jpg') }}"
                                alt="Thumbnail" class="me-2" style="width: 40px; height: 40px; object-fit: cover;"
                                onerror="this.onerror=null;this.src='{{ asset('assets/img/bg2.png') }}';">
                            <span>{{ $curso->nombreCurso }}</span>
                        </div>
                    </td>
                    <td>{{ $curso->docente->name }}</td>
                    <td>
                        <span class="text-truncate d-inline-block" style="max-width: 200px;">
                            {{ $curso->descripcionC }}
                        </span>
                    </td>
                    <td>
                        <span
                            class="badge {{ $curso->estado === 'Activo' ? 'bg-success' : ($curso->estado === 'Finalizado' ? 'bg-secondary' : 'bg-warning') }}">
                            {{ $curso->estado }}
                        </span>
                    </td>
                    <td>
                        <span data-bs-toggle="tooltip"
                            title="Inicio: {{ $curso->fecha_ini }} - Fin: {{ $curso->fecha_fin }}">
                            {{ \Carbon\Carbon::parse($curso->fecha_ini)->format('d/m/Y') }}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group">
                            <!-- Ver detalles -->
                            <a href="{{ route('rfc', encrypt($curso->id)) }}" class="btn btn-outline-primary btn-sm"
                                data-bs-toggle="tooltip" title="Ver detalles">
                                <i class="bi bi-eye"></i>
                            </a>
                            <!-- Editar -->
                            <a href="{{ route('editarCurso', encrypt($curso->id)) }}" class="btn btn-outline-secondary btn-sm"
                                data-bs-toggle="tooltip" title="Editar curso">
                                <i class="bi bi-pencil"></i>
                            </a>

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4">
                        <div class="empty-state">
                            <i class="bi bi-journal-x display-4 text-muted"></i>
                            <p class="mt-3 mb-0">No hay cursos para mostrar</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-between align-items-center mt-3">
    <div>
        Mostrando {{ $reports->firstItem() ?? 0 }} -
        {{ $reports->lastItem() ?? 0 }} de
        {{ $reports->total() }} cursos
    </div>
    <div>
        {{ $reports->links('vendor.pagination.custom') }}
    </div>
</div>
