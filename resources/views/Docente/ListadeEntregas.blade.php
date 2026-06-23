@extends('layout')

@section('titulo')
    Calificación de Actividad: {{ $actividad->titulo }}
@endsection
@section('content')
    <div class="container-fluid py-4">
        <div class="back-button-wrapper mb-4">
            <a href="{{ route('Curso', $actividad->subtema->tema->curso->codigoCurso ?? $actividad->subtema->tema->curso->id) }}" class="btn-back-modern">
                <i class="bi bi-arrow-left-circle-fill"></i>
                <span>Volver a Actividades</span>
            </a>
        </div>

        <div class="tbl-card">
            {{-- Hero Section --}}
            <div class="tbl-card-hero">
                <div class="tbl-card-hero-content">
                    <h1 class="tbl-card-hero-title text-white">
                        <i class="bi bi-journal-check me-2"></i>Calificación de Actividad
                    </h1>
                    <p class="tbl-card-hero-subtitle text-white">
                        <span class="fw-bold">{{ $actividad->titulo }}</span>
                    </p>
                </div>

                <div class="tbl-card-hero-actions">
                    <div class="d-flex gap-2 mt-2 mt-md-0 justify-content-end">
                        <div class="ec-role-badge {{ $vencido ? 'bg-danger text-white' : 'bg-warning text-dark' }}">
                            <i class="bi bi-clock-fill me-1"></i> {{ $vencido ? 'Vencido' : 'En periodo de calificación' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================================================
     Tabla de Calificaciones — versión optimizada
     ============================================================ --}}

            <div class="cal-wrap mt-3">

                {{-- Barra superior: título + filtros --}}
                <div class="cal-topbar d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <span class="cal-title">{{ $actividad->titulo }}</span>
                        <span class="cal-badge cal-badge-info">
                            <i class="bi bi-star-fill"></i> {{ $actividad->puntos }} pts máx.
                        </span>
                        <div class="position-relative ms-md-3">
                            <i class="bi bi-search position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                            <input type="text" class="form-control rounded-pill ps-5 border-0 shadow-sm" style="width: 250px; font-size: 0.9rem; padding-top: 0.4rem; padding-bottom: 0.4rem;" id="searchInput" placeholder="Buscar estudiante..." autocomplete="off">
                        </div>
                    </div>
                    <div class="cal-filters mt-3 mt-md-0" id="calFilters">
                        <button class="cal-pill active" data-filter="todos">Todos</button>
                        <button class="cal-pill" data-filter="pendiente">Por revisar</button>
                        <button class="cal-pill" data-filter="calificado">Calificados</button>
                        <button class="cal-pill" data-filter="sin_entrega">Sin entrega</button>
                    </div>
                </div>

                {{-- Tarjetas de resumen --}}
                <div class="cal-stats">
                    <div class="cal-stat">
                        <span class="cal-stat-lbl">Total</span>
                        <span class="cal-stat-val" id="stTotal">{{ $inscritos->count() }}</span>
                    </div>
                    <div class="cal-stat">
                        <span class="cal-stat-lbl">Calificados</span>
                        @php $totalCalificados = $nota->count(); @endphp
                        <span class="cal-stat-val cal-green" id="stCal">{{ $totalCalificados }}</span>
                    </div>
                    <div class="cal-stat">
                        <span class="cal-stat-lbl">Por revisar</span>
                        @php $totalPendientes = $entregas->count() - $totalCalificados; @endphp
                        <span class="cal-stat-val cal-amber" id="stPend">{{ max(0, $totalPendientes) }}</span>
                    </div>
                    <div class="cal-stat">
                        <span class="cal-stat-lbl">Sin entrega</span>
                        @php $sinEntrega = $inscritos->count() - $entregas->count(); @endphp
                        <span class="cal-stat-val cal-muted" id="stSin">{{ max(0, $sinEntrega) }}</span>
                    </div>
                </div>

                {{-- Formulario + tabla --}}
                <form action="{{ route('calificarT', encrypt($actividad->id)) }}" method="POST" id="calificationForm">
                    @csrf
                    <div class="cal-table-wrap">
                        <table class="cal-table" id="calTable">
                            <thead>
                                <tr>
                                    <th style="width:4%">#</th>
                                    <th style="width:31%">
                                        <i class="bi bi-person-fill"></i> Estudiante
                                    </th>
                                    <th style="width:16%">
                                        <i class="bi bi-hash"></i> Calificación
                                    </th>
                                    <th style="width:18%">
                                        <i class="bi bi-cloud-download-fill"></i> Entrega
                                    </th>
                                    <th style="width:19%">
                                        <i class="bi bi-chat-dots-fill"></i> Retroalimentación
                                    </th>
                                    <th style="width:12%; text-align:center">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inscritos as $index => $inscrito)
                                    @php
                                        $notaExistente = $nota
                                            ->where('inscripcion_id', $inscrito->id)
                                            ->where('actividad_id', $actividad->id)
                                            ->first();
                                        $entrega = $entregas->firstWhere('user_id', $inscrito->estudiante_id);
                                        $hasEntrega = (bool) $entrega;

                                        // Excluimos ceros automáticos generados por guardados masivos previos.
                                        $isCalif = false;
                                        if ($notaExistente) {
                                            if ($notaExistente->nota > 0 || !empty($notaExistente->retroalimentacion)) {
                                                $isCalif = true;
                                            }
                                        }

                                        $rowState = $isCalif
                                            ? 'calificado'
                                            : ($hasEntrega
                                                ? 'pendiente'
                                                : 'sin_entrega');
                                    @endphp

                                    <tr class="cal-row cal-row--{{ $rowState }}" data-state="{{ $rowState }}">

                                        {{-- # --}}
                                        <td class="cal-num">{{ $loop->iteration }}</td>

                                        {{-- Estudiante --}}
                                        <td>
                                            <div class="cal-student">
                                                <div class="cal-avatar cal-avatar--{{ $index % 4 }}">
                                                    {{ substr($inscrito->estudiantes->name, 0, 1) }}{{ substr($inscrito->estudiantes->lastname1, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="cal-name">
                                                        {{ $inscrito->estudiantes->name }}
                                                        {{ $inscrito->estudiantes->lastname1 }}
                                                    </div>
                                                    <div class="cal-sub">{{ $inscrito->estudiantes->lastname2 }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Calificación --}}
                                        <td>
                                            <div class="cal-grade-group">
                                                <input type="number" class="cal-grade-input"
                                                    name="entregas[{{ $index }}][notaTarea]" min="0"
                                                    max="{{ $actividad->puntos }}"
                                                    value="{{ $isCalif ? $notaExistente->nota : '' }}"
                                                    {{ $vencido || !$hasEntrega ? 'disabled' : '' }}
                                                    placeholder="--">
                                                <span class="cal-grade-of">/{{ $actividad->puntos }}</span>
                                            </div>
                                            <input type="hidden" name="entregas[{{ $index }}][id]"
                                                value="{{ $notaExistente->id ?? '' }}">
                                            <input type="hidden" name="entregas[{{ $index }}][id_tarea]"
                                                value="{{ $actividad->id }}">
                                            <input type="hidden" name="entregas[{{ $index }}][id_inscripcion]"
                                                value="{{ $inscrito->id }}">
                                        </td>

                                        {{-- Entrega --}}
                                        <td>
                                            @if ($hasEntrega)
                                                <a href="{{ asset('storage/' . $entrega->archivo) }}"
                                                    class="cal-link-file" target="_blank">
                                                    <i class="bi bi-file-earmark-text-fill"></i> Ver tarea
                                                </a>
                                            @else
                                                <span class="cal-badge cal-badge-warn">
                                                    <i class="bi bi-exclamation-circle"></i> No entregado
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Retroalimentación --}}
                                        <td>
                                            <button type="button"
                                                class="cal-btn-fb {{ $notaExistente && $notaExistente->retroalimentacion ? 'cal-btn-fb--active' : '' }}"
                                                data-bs-toggle="modal" data-bs-target="#fbModal{{ $index }}"
                                                {{ !$hasEntrega ? 'disabled' : '' }}>
                                                <i class="bi bi-chat-dots"></i>
                                                {{ $notaExistente && $notaExistente->retroalimentacion ? 'Ver comentario' : 'Agregar' }}
                                            </button>
                                        </td>

                                        {{-- Estado --}}
                                        <td style="text-align:center">
                                            @if ($isCalif)
                                                <span class="cal-badge cal-badge-ok">
                                                    <i class="bi bi-check2"></i> Listo
                                                </span>
                                            @elseif ($hasEntrega)
                                                <span class="cal-badge cal-badge-warn">
                                                    <i class="bi bi-clock"></i> Pendiente
                                                </span>
                                            @else
                                                <span class="cal-badge cal-badge-muted">
                                                    <i class="bi bi-dash"></i> Sin entrega
                                                </span>
                                            @endif
                                        </td>
                                    </tr>

                                    {{-- Modal retroalimentación --}}
                                    <div class="modal fade" id="fbModal{{ $index }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="cal-modal-content">
                                                <div class="cal-modal-header">
                                                    <span class="cal-modal-title">
                                                        <i class="bi bi-chat-quote me-2"></i>Retroalimentación
                                                    </span>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                </div>
                                                <div class="cal-modal-body">
                                                    <label class="cal-modal-label">
                                                        Para: {{ $inscrito->estudiantes->name }}
                                                        {{ $inscrito->estudiantes->lastname1 }}
                                                    </label>
                                                    <textarea class="cal-textarea" name="entregas[{{ $index }}][retroalimentacion]" rows="5"
                                                        placeholder="Escribe tus comentarios u observaciones...">{{ $notaExistente->retroalimentacion ?? '' }}</textarea>

                                                    @if ($notaExistente && $notaExistente->retroalimentacion)
                                                        <div class="cal-fb-preview">
                                                            <span class="cal-fb-preview-lbl">Retroalimentación
                                                                actual:</span>
                                                            <span
                                                                class="cal-fb-preview-text">{{ $notaExistente->retroalimentacion }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="cal-modal-footer">
                                                    <button type="button" class="cal-btn cal-btn-secondary"
                                                        data-bs-dismiss="modal">Cerrar</button>
                                                    <button type="button" class="cal-btn cal-btn-primary save-feedback"
                                                        data-bs-dismiss="modal">Guardar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Barra de guardado --}}
                    @if (!$vencido)
                        <div class="cal-save-bar">
                            <span class="cal-hint" id="calHint">
                                {{ $nota->count() }} de {{ $inscritos->count() }} calificados
                            </span>
                            <button type="submit" class="cal-btn cal-btn-primary cal-btn-save">
                                <i class="bi bi-cloud-check-fill"></i> Guardar calificaciones
                            </button>
                        </div>
                    @else
                        <div class="cal-alert-vencido">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            El período de calificación ha finalizado y ya no es posible realizar cambios.
                        </div>
                    @endif
                </form>
            </div>



        </div>
    </div>

    <style>
        /* =========================================================
           ESTILOS DE LA NUEVA TABLA CALIFICACIONES (cal-*)
           ========================================================= */
        .cal-wrap { background: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 24px; }
        .cal-topbar { border-bottom: 1px solid #eef2f6; padding-bottom: 15px; }
        .cal-title { font-size: 1.25rem; font-weight: 700; color: #1e293b; margin-right: 12px; }
        .cal-badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; border-radius: 50rem; font-size: 0.8rem; font-weight: 600; }
        .cal-badge-info { background: #e0f2fe; color: #0284c7; }
        .cal-badge-ok { background: #dcfce7; color: #166534; }
        .cal-badge-warn { background: #fef9c3; color: #854d0e; }
        .cal-badge-muted { background: #f1f5f9; color: #64748b; }

        .cal-filters { display: flex; gap: 8px; background: #f8fafc; padding: 6px; border-radius: 50rem; }
        .cal-pill { border: none; background: transparent; padding: 8px 16px; border-radius: 50rem; font-size: 0.85rem; font-weight: 600; color: #64748b; transition: all 0.2s; }
        .cal-pill:hover { background: #e2e8f0; color: #334155; }
        .cal-pill.active { background: #1a4789; color: #fff; box-shadow: 0 4px 10px rgba(26,71,137,0.25); }

        .cal-stats { display: flex; gap: 20px; align-items: center; justify-content: space-between; margin: 24px 0; background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px solid #eef2f6; }
        .cal-stat { display: flex; flex-direction: column; flex: 1; border-right: 1px solid #e2e8f0; padding-left: 10px; }
        .cal-stat:last-child { border-right: none; }
        .cal-stat-lbl { font-size: 0.75rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700; margin-bottom: 5px; }
        .cal-stat-val { font-size: 1.75rem; font-weight: 800; color: #1e293b; line-height: 1; }
        .cal-green { color: #10b981; }
        .cal-amber { color: #f59e0b; }
        .cal-muted { color: #94a3b8; }

        .cal-table-wrap { overflow-x: auto; border-radius: 12px; border: 1px solid #eef2f6; background: #fff; }
        .cal-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .cal-table th { background: #f8fafc; color: #475569; font-weight: 700; font-size: 0.8rem; padding: 16px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; white-space: nowrap; }
        .cal-table td { padding: 16px; vertical-align: middle; border-bottom: 1px solid #eef2f6; background: #fff; transition: background-color 0.3s; }

        .cal-row td:first-child { border-left: 4px solid transparent; }
        .cal-row--pendiente td { background-color: #fffbeb !important; }
        .cal-row--pendiente td:first-child { border-left-color: #f59e0b !important; }

        .cal-row--calificado td { background-color: #f0fdf4 !important; }
        .cal-row--calificado td:first-child { border-left-color: #10b981 !important; }

        .cal-num { font-weight: 700; color: #94a3b8; font-size: 0.9rem; }
        .cal-student { display: flex; align-items: center; gap: 14px; }
        .cal-avatar { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.95rem; color: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.08); }
        .cal-avatar--0 { background: linear-gradient(135deg, #3b82f6, #2563eb); }
        .cal-avatar--1 { background: linear-gradient(135deg, #10b981, #059669); }
        .cal-avatar--2 { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .cal-avatar--3 { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
        .cal-name { font-weight: 700; color: #1e293b; font-size: 0.95rem; margin-bottom: 2px; }
        .cal-sub { font-size: 0.8rem; color: #64748b; }

        .cal-grade-group { display: flex; align-items: center; background: #f8fafc; border-radius: 8px; padding: 4px 10px; width: fit-content; border: 2px solid transparent; transition: all 0.2s; }
        .cal-grade-group:focus-within { border-color: #3b82f6; background: #fff; box-shadow: 0 0 0 4px rgba(59,130,246,0.1); }
        .cal-grade-input { border: none; background: transparent; width: 55px; text-align: center; font-weight: 800; color: #1e293b; font-size: 1.1rem; outline: none; padding: 4px 0; }
        .cal-grade-of { font-size: 0.85rem; color: #94a3b8; font-weight: 700; }

        .cal-link-file { display: inline-flex; align-items: center; gap: 6px; color: #2563eb; background: #eff6ff; padding: 8px 14px; border-radius: 8px; font-size: 0.85rem; font-weight: 700; text-decoration: none; transition: all 0.2s; }
        .cal-link-file:hover { background: #dbeafe; color: #1d4ed8; transform: translateY(-1px); box-shadow: 0 4px 6px rgba(37,99,235,0.1); }

        .cal-btn-fb { border: 1px solid #cbd5e1; background: #fff; color: #64748b; padding: 8px 14px; border-radius: 8px; font-size: 0.85rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; }
        .cal-btn-fb:hover { border-color: #94a3b8; color: #334155; background: #f8fafc; }
        .cal-btn-fb--active { border-color: #10b981; color: #10b981; background: #f0fdf4; }
        .cal-btn-fb--active:hover { background: #ecfdf5; border-color: #059669; color: #059669; }

        .cal-save-bar { display: flex; justify-content: space-between; align-items: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid #eef2f6; }
        .cal-hint { font-size: 0.95rem; color: #64748b; font-weight: 600; }
        .cal-alert-vencido { background: #fef2f2; color: #b91c1c; padding: 16px; border-radius: 12px; text-align: center; font-weight: 700; margin-top: 24px; border: 1px solid #fecaca; }

        .cal-modal-content { background: #fff; border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
        .cal-modal-header { background: #1a4789; color: #fff; padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: none; }
        .cal-modal-title { font-weight: 800; font-size: 1.15rem; }
        .cal-modal-body { padding: 24px; }
        .cal-modal-label { display: block; font-weight: 700; color: #1e293b; margin-bottom: 12px; font-size: 0.95rem; }
        .cal-textarea { width: 100%; border: 2px solid #e2e8f0; border-radius: 12px; padding: 16px; font-size: 0.95rem; outline: none; resize: vertical; transition: border-color 0.2s; color: #334155; }
        .cal-textarea:focus { border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59,130,246,0.1); }
        .cal-fb-preview { margin-top: 20px; background: #f8fafc; border-left: 4px solid #3b82f6; padding: 16px; border-radius: 0 12px 12px 0; }
        .cal-fb-preview-lbl { display: block; font-size: 0.8rem; font-weight: 800; color: #64748b; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
        .cal-fb-preview-text { font-size: 0.95rem; color: #334155; font-style: italic; line-height: 1.5; }
        .cal-modal-footer { padding: 20px 24px; background: #f8fafc; border-top: 1px solid #eef2f6; display: flex; justify-content: flex-end; gap: 12px; }
        .cal-btn { padding: 10px 24px; border-radius: 10px; font-weight: 700; font-size: 0.95rem; border: none; transition: all 0.2s; }
        .cal-btn-secondary { background: #e2e8f0; color: #475569; }
        .cal-btn-secondary:hover { background: #cbd5e1; color: #1e293b; }
        .cal-btn-primary { background: #1a4789; color: #fff; box-shadow: 0 4px 6px rgba(26,71,137,0.2); }
        .cal-btn-primary:hover { background: #113264; box-shadow: 0 6px 12px rgba(26,71,137,0.3); transform: translateY(-1px); }

        /* Ocultar input arrows */
        .cal-grade-input::-webkit-outer-spin-button,
        .cal-grade-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        .cal-grade-input[type=number] { -moz-appearance: textfield; }

        @media(max-width: 768px) {
            .cal-stats { flex-direction: column; gap: 15px; text-align: center; }
            .cal-stat { border-right: none; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; padding-left: 0; }
            .cal-stat:last-child { border-bottom: none; padding-bottom: 0; }
            .cal-filters { overflow-x: auto; padding-bottom: 5px; }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const pills = document.querySelectorAll('#calFilters .cal-pill');
            const tableRows = document.querySelectorAll('#calTable tbody tr.cal-row');

            let currentFilter = 'todos';

            function applyFilters() {
                const searchValue = searchInput ? searchInput.value.toLowerCase().trim() : '';

                tableRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    const state = row.dataset.state;

                    const matchesSearch = text.includes(searchValue);
                    const matchesPill = currentFilter === 'todos' || state === currentFilter;

                    row.style.display = (matchesSearch && matchesPill) ? '' : 'none';
                });
            }

            if (searchInput) {
                searchInput.addEventListener('input', applyFilters);
            }

            pills.forEach(pill => {
                pill.addEventListener('click', (e) => {
                    e.preventDefault();
                    pills.forEach(p => p.classList.remove('active'));
                    pill.classList.add('active');
                    currentFilter = pill.dataset.filter;
                    applyFilters();
                });
            });

            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            function saveToLocalStorage() {
                document.querySelectorAll('.cal-grade-input').forEach(input => {
                    localStorage.setItem(input.getAttribute('name'), input.value);
                });

                document.querySelectorAll('.cal-textarea').forEach(textarea => {
                    const name = textarea.getAttribute('name');
                    localStorage.setItem(name, textarea.value);
                });

                window.isFormModified = true;
            }

            function loadFromLocalStorage() {
                document.querySelectorAll('.cal-grade-input').forEach(input => {
                    const savedValue = localStorage.getItem(input.getAttribute('name'));
                    if (savedValue !== null) {
                        input.value = savedValue;
                    }
                });

                document.querySelectorAll('.cal-textarea').forEach(textarea => {
                    const name = textarea.getAttribute('name');
                    const savedValue = localStorage.getItem(name);
                    if (savedValue !== null) {
                        textarea.value = savedValue;
                    }
                });
            }

            function updateStats() {
                const total = tableRows.length;
                let calificados = 0;
                let pendientes = 0;
                let sin_entrega = 0;

                tableRows.forEach(row => {
                    if (row.dataset.state === 'calificado') calificados++;
                    else if (row.dataset.state === 'pendiente') pendientes++;
                    else sin_entrega++;
                });

                const stCal = document.getElementById('stCal');
                const stPend = document.getElementById('stPend');
                const stSin = document.getElementById('stSin');
                const calHint = document.getElementById('calHint');

                if (stCal) stCal.textContent = calificados;
                if (stPend) stPend.textContent = pendientes;
                if (stSin) stSin.textContent = sin_entrega;
                if (calHint) calHint.textContent = `${calificados} de ${total} calificados`;
            }

            document.querySelectorAll('.cal-grade-input').forEach(input => {
                input.addEventListener('input', function() {
                    saveToLocalStorage();

                    const row = this.closest('tr.cal-row');
                    if (row && this.value !== '') {
                        if (row.dataset.state === 'pendiente') {
                            row.classList.remove('cal-row--pendiente');
                            row.classList.add('cal-row--calificado');
                            row.dataset.state = 'calificado';

                            const badge = row.querySelector('td:last-child .cal-badge');
                            if(badge) {
                                badge.className = 'cal-badge cal-badge-ok';
                                badge.innerHTML = '<i class="bi bi-check2"></i> Listo';
                            }

                            updateStats();
                        }
                    }
                });
            });

            document.querySelectorAll('.cal-textarea').forEach(el => {
                el.addEventListener('input', saveToLocalStorage);
            });

            document.querySelectorAll('.save-feedback').forEach(btn => {
                btn.addEventListener('click', function() {
                    saveToLocalStorage();
                    const modal = this.closest('.modal');
                    if (modal) {
                        const targetId = '#' + modal.id;
                        const button = document.querySelector(`[data-bs-target="${targetId}"]`);
                        if (button) {
                            button.classList.add('cal-btn-fb--active');
                            button.innerHTML = '<i class="bi bi-chat-dots-fill"></i> Ver comentario';
                        }
                    }
                });
            });

            loadFromLocalStorage();

            window.addEventListener('beforeunload', function(e) {
                if (window.isFormModified) {
                    e.preventDefault();
                    e.returnValue = 'Tiene cambios sin guardar. ¿Está seguro de que quiere salir?';
                    return e.returnValue;
                }
            });

            const form = document.getElementById('calificationForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (e.isTrusted) {
                        e.preventDefault();

                        Swal.fire({
                            title: '¿Guardar calificaciones?',
                            text: "Se actualizarán solo las notas de los estudiantes evaluados.",
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#10b981',
                            cancelButtonColor: '#64748b',
                            confirmButtonText: 'Sí, guardar',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Antes de enviar, deshabilitamos las filas que no tienen calificación
                                // Esto evita enviar arrays nulos y saltar la validación requerida del backend,
                                // así como evitar autoguardar ceros indeseados en la Base de Datos.
                                document.querySelectorAll('#calTable tbody tr.cal-row').forEach((row, idx) => {
                                    const gradeInput = row.querySelector('.cal-grade-input');
                                    if (gradeInput && gradeInput.value === '') {
                                        // Deshabilitamos los inputs de la tabla (incluyendo campos hidden)
                                        row.querySelectorAll('input, button').forEach(el => el.disabled = true);
                                        // Buscamos el modal correspondiente en el DOM para inhabilitar el textarea de feedback
                                        const modal = document.getElementById('fbModal' + idx);
                                        if (modal) {
                                            modal.querySelectorAll('textarea, input').forEach(el => el.disabled = true);
                                        }
                                    }
                                });

                                document.querySelectorAll('.cal-grade-input, .cal-textarea').forEach(el => {
                                    localStorage.removeItem(el.getAttribute('name'));
                                });
                                window.isFormModified = false;

                                Swal.fire({
                                    title: 'Guardando...',
                                    didOpen: () => Swal.showLoading(),
                                    allowOutsideClick: false
                                });

                                form.submit();
                            }
                        });
                    }
                });
            }
        });
    </script>
@endsection
