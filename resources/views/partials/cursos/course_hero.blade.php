
<div class="container-fluid ch-outer">
    <div class="ch-hero">
        @if($cursos->imagen)
            <div class="ch-hero-bg"
                 style="background-image:url('{{ asset('storage/'.$cursos->imagen) }}')"></div>
        @endif
        <div class="ch-hero-overlay"></div>

        <div class="ch-hero-body container">
            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">

                {{-- Título + badges --}}
                <div class="ch-hero-text">
                    <div class="ch-eyebrow">
                        @if($cursos->tipo === 'curso')
                            <i class="bi bi-book-fill"></i> Curso
                        @else
                            <i class="bi bi-calendar-event-fill"></i> Congreso
                        @endif
                        @if($cursos->formato ?? false)
                            <span class="ch-sep">·</span>
                            <i class="bi bi-globe2"></i> {{ $cursos->formato }}
                        @endif
                    </div>

                    <h1 class="ch-title">{{ $cursos->nombreCurso }}</h1>

                    <div class="ch-badges">
                        @php
                            $estadoClass = match($cursos->estado) {
                                'Activo'                 => 'ch-badge-green',
                                'Certificado Disponible' => 'ch-badge-orange',
                                default                  => 'ch-badge-gray',
                            };
                            $estadoIcon = match($cursos->estado) {
                                'Activo'                 => 'bi-check-circle-fill',
                                'Certificado Disponible' => 'bi-patch-check-fill',
                                default                  => 'bi-pause-circle-fill',
                            };
                        @endphp
                        <span class="ch-badge {{ $estadoClass }}">
                            <i class="bi {{ $estadoIcon }}"></i> {{ $cursos->estado }}
                        </span>

                        @if($cursos->duracion)
                            <span class="ch-badge ch-badge-glass">
                                <i class="bi bi-clock"></i> {{ $cursos->duracion }} horas
                            </span>
                        @endif

                        @if($cursos->tipo === 'congreso')
                            <span class="ch-badge ch-badge-glass">
                                <i class="bi bi-gift-fill"></i> Gratuito
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Botón collapse --}}
                <button class="ch-collapse-btn" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#courseInfo"
                        aria-expanded="true"
                        id="chCollapseBtn">
                    <i class="bi bi-chevron-up ch-chevron-icon"></i>
                    <span class="ch-collapse-label">Ocultar detalles</span>
                </button>

            </div>
        </div>
    </div>


    <div id="courseInfo" class="collapse show">
        <div class="container ch-panel-wrap">
            <div class="ch-panel">
                <div class="ch-panel-main">
                    <div class="ch-card">
                        <div class="ch-card-header">
                            <div class="ch-card-icon">
                                <i class="bi bi-file-text-fill"></i>
                            </div>
                            <h5 class="ch-card-title">Descripción del Curso</h5>
                        </div>
                        <p class="ch-description">{{ $cursos->descripcionC ?? 'Sin descripción disponible.' }}</p>

                        @if($cursos->youtube_url)
                            <div class="mt-4 pt-3 border-top">
                                <div class="d-flex align-items-center mb-3 text-primary">
                                    <i class="bi bi-play-circle-fill fs-5 me-2"></i>
                                    <h6 class="mb-0 fw-bold">Video de Presentación</h6>
                                </div>
                                <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-sm border border-light">
                                    @php
                                        $url = $cursos->youtube_url;
                                        if (str_contains($url, 'watch?v=')) {
                                            $url = str_replace('watch?v=', 'embed/', $url);
                                        } elseif (str_contains($url, 'youtu.be/')) {
                                            $url = str_replace('youtu.be/', 'youtube.com/embed/', $url);
                                        }
                                        // Asegurar que no tenga parámetros extras que rompan el embed simple
                                        if (str_contains($url, '&')) {
                                            $url = explode('&', $url)[0];
                                        }
                                    @endphp
                                    <iframe src="{{ $url }}"
                                            title="Presentación del Curso"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                            allowfullscreen>
                                    </iframe>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="ch-panel-sidebar">
                    <div class="ch-card ch-teacher-card">
                        <div class="ch-card-header">
                            <div class="ch-card-icon">
                                <i class="bi bi-person-video3"></i>
                            </div>
                            <h5 class="ch-card-title">Docente</h5>
                        </div>
                        @if($cursos->docente)
                            <a href="{{ route('perfil', encrypt($cursos->docente->id)) }}" class="ch-teacher-link">
                                <div class="ch-teacher-avatar">
                                    {{ strtoupper(substr($cursos->docente->name, 0, 1)) }}
                                </div>
                                <div class="ch-teacher-info">
                                    <span class="ch-teacher-name">
                                        {{ $cursos->docente->name }}
                                        {{ $cursos->docente->lastname1 }}
                                        {{ $cursos->docente->lastname2 }}
                                    </span>
                                    <span class="ch-teacher-sub">Ver perfil completo</span>
                                </div>
                                <i class="bi bi-chevron-right ch-teacher-arrow"></i>
                            </a>
                        @else
                            <p class="text-muted mb-0" style="font-size:.85rem">Sin docente asignado</p>
                        @endif
                    </div>

                    <div class="ch-card ch-actions-card">
                        <div class="ch-card-header">
                            <div class="ch-card-icon ch-card-icon--orange">
                                <i class="bi bi-lightning-fill"></i>
                            </div>
                            <h5 class="ch-card-title">Acciones Rápidas</h5>
                        </div>

                        <div class="ch-action-list">

                            <a class="ch-action" href="{{ route('listacurso', encrypt($cursos->id)) }}">
                                <i class="bi bi-people-fill"></i>
                                <span>Participantes</span>
                                <i class="bi bi-chevron-right ch-action-arrow"></i>
                            </a>

                            @if($cursos->tipo == 'curso')
                                <button class="ch-action" type="button"
                                        data-bs-toggle="modal" data-bs-target="#modalHorario">
                                    <i class="bi bi-calendar3"></i>
                                    <span>Horarios</span>
                                    <i class="bi bi-chevron-right ch-action-arrow"></i>
                                </button>

                                <a class="ch-action"
                                   href="{{ route('historialAsistencias', encrypt($cursos->id)) }}">
                                    <i class="bi bi-clipboard-check-fill"></i>
                                    <span>Asistencias</span>
                                    <i class="bi bi-chevron-right ch-action-arrow"></i>
                                </a>
                            @endif

                            {{-- Menú Gestionar (Docente / Admin) --}}
                            @if($esDocente || auth()->user()->hasRole('Administrador'))
                                <div class="ch-manage-wrap">
                                    <button class="ch-action ch-action--manage"
                                            type="button"
                                            id="chManageBtn"
                                            aria-expanded="false">
                                        <i class="bi bi-gear-fill"></i>
                                        <span>Gestionar Curso</span>
                                        <i class="bi bi-chevron-down ch-action-arrow ch-manage-chevron"></i>
                                    </button>

                                    <div class="ch-manage-menu" id="chManageMenu">

                                        <a class="ch-manage-item"
                                           href="{{ route('curso-imagenes.index', $cursos->codigoCurso) }}">
                                            <i class="bi bi-images text-primary"></i>
                                            Imágenes de Presentación
                                        </a>

                                        @if($esDocente || auth()->user()->hasRole('Administrador'))
                                            <a class="ch-manage-item"
                                               href="{{ route('editarCurso', [encrypt($cursos->id)]) }}">
                                                <i class="bi bi-pencil-square" style="color:#0ea5e9"></i>
                                                Editar Curso
                                            </a>
                                        @endif

                                        @if($esDocente && $cursos->tipo == 'curso')
                                            <hr class="ch-manage-divider">
                                            <a class="ch-manage-item" href="#"
                                               data-bs-toggle="modal" data-bs-target="#modalCrearHorario">
                                                <i class="bi bi-calendar-plus-fill text-success"></i>
                                                Crear Horarios
                                            </a>
                                            <a class="ch-manage-item"
                                               href="{{ route('repF', [encrypt($cursos->id)]) }}"
                                               onclick="mostrarAdvertencia2(event)">
                                                <i class="bi bi-star-fill text-warning"></i>
                                                Calificaciones
                                            </a>
                                            <a class="ch-manage-item"
                                               href="{{ route('asistencias', [encrypt($cursos->id)]) }}">
                                                <i class="bi bi-check2-square text-success"></i>
                                                Dar Asistencia
                                            </a>
                                            <a class="ch-manage-item"
                                               href="{{ route('cursos.elementos-eliminados', encrypt($cursos->id)) }}">
                                                <i class="bi bi-trash3-fill text-danger"></i>
                                                Elementos Eliminados
                                            </a>
                                        @endif

                                        @if(auth()->user()->hasRole('Administrador') && !empty($cursos->archivoContenidodelCurso))
                                            <a class="ch-manage-item"
                                               href="{{ asset('storage/'.$cursos->archivoContenidodelCurso) }}"
                                               target="_blank">
                                                <i class="bi bi-file-earmark-pdf-fill text-danger"></i>
                                                Ver Plan del Curso
                                            </a>
                                        @endif

                                        <hr class="ch-manage-divider">

                                        @if($cursos->estado === 'Certificado Disponible')
                                            <button type="button" class="ch-manage-item"
                                                    data-bs-toggle="modal" data-bs-target="#certificadoModal">
                                                <i class="bi bi-patch-check-fill text-warning"></i>
                                                Obtener Certificado
                                            </button>
                                        @endif

                                        @if($cursos->estado == 'Activo')
                                            @if($cursos->registros_habilitados)
                                                <form action="{{ route('cursos.activarCertificados', ['id' => $cursos->id]) }}"
                                                      method="POST">
                                                    @csrf
                                                    <button type="submit" class="ch-manage-item w-100">
                                                        <i class="bi bi-patch-check text-success"></i>
                                                        Activar Certificados
                                                    </button>
                                                </form>
                                            @else
                                                <div class="ch-manage-item text-muted opacity-75">
                                                    <i class="bi bi-clock-history"></i>
                                                    Activación automática en: {{ \Carbon\Carbon::parse($cursos->fecha_fin)->subHour()->diffForHumans() }}
                                                </div>
                                            @endif
                                        @endif

                                        @if(auth()->user()->hasRole('Administrador') || $esDocente)
                                            @if(!isset($template))
                                                <a class="ch-manage-item" href="#"
                                                   data-bs-toggle="modal" data-bs-target="#modalCertificado">
                                                    <i class="bi bi-cloud-upload-fill text-primary"></i>
                                                    Subir Plantilla de Certificado
                                                </a>
                                            @else
                                                <a class="ch-manage-item" href="#"
                                                   data-bs-toggle="modal" data-bs-target="#modalEditarCertificado">
                                                    <i class="bi bi-pencil-fill text-primary"></i>
                                                    Actualizar Plantilla
                                                </a>
                                            @endif
                                            <a class="ch-manage-item"
                                               href="{{ route('certificados.vistaPrevia', encrypt($cursos->id)) }}"
                                               target="_blank">
                                                <i class="bi bi-eye-fill" style="color:#0ea5e9"></i>
                                                Vista Previa del Certificado
                                            </a>
                                        @endif

                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>


<div class="modal fade" id="certificadoModal" tabindex="-1" aria-labelledby="certificadoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="certificadoModalLabel">Descarga tu Certificado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
        </div>
    </div>
</div>




@include('partials.cursos.modals.certificadosPlantilla')
@include('partials.cursos.modals.horario')



<style>
    /* ---- Modales ---- */
    .modal-header.bg-primary { border-bottom: none; }
    .modal-footer.bg-light   { border-top: 1px solid #dee2e6; }

    /* ---- Tabla de horarios ---- */
    .table-hover tbody tr:hover { background-color: rgba(0,123,255,.05); }

    @media (max-width: 768px) {
        .modal-dialog { margin: .5rem; }
        .table-responsive { font-size: .875rem; }
        .d-flex.gap-2 { flex-direction: column; gap: .25rem !important; }
        .btn-sm { padding: .25rem .5rem; font-size: .75rem; }
    }

    /* ---- Botones ---- */
    .btn { transition: transform .2s ease, box-shadow .2s ease; }
    .btn:hover { transform: translateY(-1px); box-shadow: 0 2px 4px rgba(0,0,0,.1); }
    .table tbody tr { transition: background-color .2s ease; }

    /* ---- Sidebar: Actividades ---- */
    .activities-calendar { display: flex; flex-direction: column; gap: .75rem; }

    .activity-item {
        display: flex; align-items: center; gap: .75rem;
        padding: .75rem; background: #fff; border-radius: 12px;
        border: 1px solid #e9ecef; text-decoration: none; color: inherit;
        transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,.05);
        animation: fadeInUp .5s ease forwards; opacity: 0;
    }
    .activity-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,.1);
        border-color: var(--color-secondary, #39a6cb);
        text-decoration: none;
    }
    .activity-item.urgent {
        border-left: 4px solid #dc3545;
        background: linear-gradient(135deg, #fff 0%, #fff5f5 100%);
    }
    .activity-item:nth-child(1) { animation-delay: .1s; }
    .activity-item:nth-child(2) { animation-delay: .2s; }
    .activity-item:nth-child(3) { animation-delay: .3s; }
    .activity-item:nth-child(4) { animation-delay: .4s; }
    .activity-item:nth-child(5) { animation-delay: .5s; }

    .activity-icon {
        width: 40px; height: 40px; border-radius: 10px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 1.1rem;
        background: linear-gradient(135deg, var(--color-primary,#1a4789) 0%, var(--color-secondary,#39a6cb) 100%);
    }
    .activity-item.urgent .activity-icon {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    }
    .activity-info { flex: 1; min-width: 0; }
    .activity-title { font-size: .875rem; font-weight: 600; color: #333; margin-bottom: .25rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .activity-date  { font-size: .75rem; color: #6c757d; display: flex; align-items: center; gap: .25rem; }

    /* ---- Sidebar: Certificado ---- */
    .certificate-card {
        background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
        border-radius: 16px; padding: 1.5rem; text-align: center;
        box-shadow: 0 4px 12px rgba(255,215,0,.3); border: 2px solid #f0c800;
        position: relative; overflow: hidden;
        animation: fadeInScale .6s ease forwards;
    }
    .certificate-card::before {
        content: ''; position: absolute; top: -50%; left: -50%;
        width: 200%; height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,.3) 0%, transparent 70%);
        animation: shimmer 3s infinite;
    }
    .certificate-card.pending {
        background: linear-gradient(135deg, #e9ecef 0%, #f8f9fa 100%);
        border-color: #dee2e6; box-shadow: 0 2px 8px rgba(0,0,0,.1);
    }
    .certificate-card.pending::before { display: none; }

    .certificate-icon {
        font-size: 3rem; margin-bottom: 1rem; color: #b8860b;
        position: relative; z-index: 1; animation: pulse 2s infinite;
    }
    .certificate-card.pending .certificate-icon { color: #6c757d; animation: none; }
    .certificate-title { font-size: 1.1rem; font-weight: 700; color: #333; margin-bottom: .5rem; position: relative; z-index: 1; }
    .certificate-text  { font-size: .875rem; color: #555; margin-bottom: 1rem; position: relative; z-index: 1; }

    .btn-certificate {
        background: linear-gradient(135deg, #1a4789 0%, #055c9d 100%);
        color: #fff; border: none; border-radius: 12px;
        padding: .75rem 1.5rem; font-weight: 600; font-size: .875rem;
        transition: transform .3s ease, box-shadow .3s ease, background .3s ease;
        box-shadow: 0 4px 8px rgba(26,71,137,.3);
        position: relative; z-index: 1; width: 100%;
    }
    .btn-certificate:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(26,71,137,.4);
        background: linear-gradient(135deg, #055c9d 0%, #1a4789 100%);
        color: #fff;
    }
    .btn-certificate:active { transform: translateY(0); }

    /* ---- Keyframes ---- */
    @keyframes shimmer {
        0%,100% { transform: translate(-50%,-50%) rotate(0deg); }
        50%      { transform: translate(-30%,-30%) rotate(180deg); }
    }
    @keyframes pulse {
        0%,100% { transform: scale(1); }
        50%      { transform: scale(1.1); }
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInScale {
        from { opacity: 0; transform: scale(.9); }
        to   { opacity: 1; transform: scale(1); }
    }

    /* ---- Responsive sidebar ---- */
    @media (max-width: 992px) {
        .activity-item { padding: .5rem; }
        .activity-icon { width: 35px; height: 35px; font-size: 1rem; }
        .certificate-card { padding: 1rem; }
        .certificate-icon { font-size: 2.5rem; }
    }

    /* ---- Dropdown submenu (legacy) ---- */
    .dropdown-submenu .dropdown-menu {
        top: 0; left: 100%; margin-top: -1px;
        display: none; position: absolute;
    }
    .dropdown-submenu:hover .dropdown-menu { display: block; }
</style>



<script>
(function () {

    /* ── 1. Toggle collapse (texto + ícono) ── */
    const btn   = document.getElementById('chCollapseBtn');
    const panel = document.getElementById('courseInfo');
    const label = btn?.querySelector('.ch-collapse-label');

    if (panel && btn) {
        panel.addEventListener('show.bs.collapse', () => {
            btn.setAttribute('aria-expanded', 'true');
            if (label) label.textContent = 'Ocultar detalles';
        });
        panel.addEventListener('hide.bs.collapse', () => {
            btn.setAttribute('aria-expanded', 'false');
            if (label) label.textContent = 'Ver detalles';
        });
    }

    /* ── 2. Menú "Gestionar Curso" personalizado ── */
    const manageBtn  = document.getElementById('chManageBtn');
    const manageMenu = document.getElementById('chManageMenu');

    manageBtn?.addEventListener('click', function (e) {
        e.stopPropagation();
        const open = manageMenu?.classList.toggle('open');
        manageBtn.classList.toggle('open', open);
        manageBtn.setAttribute('aria-expanded', String(open));
    });

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.ch-manage-wrap')) {
            manageMenu?.classList.remove('open');
            manageBtn?.classList.remove('open');
            manageBtn?.setAttribute('aria-expanded', 'false');
        }
    });

    /* ── 3. Modal editar horario — poblar campos ── */
    document.querySelectorAll('.btn-editar-horario').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('edit_dia').value         = this.dataset.dia;
            document.getElementById('edit_hora_inicio').value = this.dataset.horaInicio;
            document.getElementById('edit_hora_fin').value    = this.dataset.horaFin;
            document.getElementById('formEditarHorario').action =
                "{{ route('horarios.update', '') }}/" + this.dataset.id;
        });
    });

    /* ── 4. Preview de imagen en modales de certificado ── */
    window.previewImage = function (input, selector) {
        const img         = document.querySelector(selector);
        const placeholder = img?.closest('.preview-container')?.querySelector('.preview-placeholder');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                img.src = e.target.result;
                img.classList.remove('d-none');
                if (placeholder) placeholder.style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    };

    /* ── 5. Sincronización range ↔ number (tamaño de fuente) ── */
    document.querySelectorAll('.font-size-range').forEach(range => {
        const num = range.closest('.input-group')?.querySelector('.font-size-number');
        if (!num) return;
        range.addEventListener('input', () => { num.value   = range.value; });
        num.addEventListener('input',   () => { range.value = num.value;   });
    });

    /* ── 6. Actualizar color hex mostrado ── */
    document.querySelectorAll('input[type="color"]').forEach(input => {
        const span = input.closest('.d-flex')?.querySelector('.color-value');
        if (span) input.addEventListener('input', () => { span.textContent = input.value; });
    });

})();
</script>
