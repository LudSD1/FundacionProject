<div class="tab-pane fade show active" id="tab-actividades" role="tabpanel" aria-labelledby="temario-tab">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Contenido del Curso</h3>
            <p class="text-muted mb-0">Explora los temas y subtemas del curso</p>
        </div>
        @if (auth()->user()->hasRole('Docente') && $cursos->docente_id == auth()->user()->id)
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTema">
                <i class="fas fa-plus me-2"></i>Añadir Contenido
            </button>
        @endif
    </div>

    <style>
        .temas-accordion {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .tema-card {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .tema-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            border-color: var(--color-primary, #1a4789);
        }

        .tema-card.locked {
            opacity: 0.6;
            background: #f8f9fa;
        }

        .tema-header {
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s ease;
            border-bottom: 2px solid transparent;
        }

        .tema-card.active .tema-header {
            background: linear-gradient(135deg, var(--color-primary, #1a4789) 0%, #2d5aa0 100%);
            color: white;
            border-bottom-color: rgba(255, 255, 255, 0.2);
        }

        .tema-header:hover:not(.locked) {
            background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
        }

        .tema-card.active .tema-header:hover {
            background: linear-gradient(135deg, #2d5aa0 0%, #1a4789 100%);
        }

        .tema-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex: 1;
        }

        .tema-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            color: var(--color-primary, #1a4789);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
            flex-shrink: 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .tema-card.active .tema-number {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .tema-title-section {
            flex: 1;
        }

        .tema-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
            color: #333;
        }

        .tema-card.active .tema-title {
            color: white;
        }

        .tema-meta {
            display: flex;
            gap: 1rem;
            margin-top: 0.25rem;
            font-size: 0.85rem;
            color: #6c757d;
        }

        .tema-card.active .tema-meta {
            color: rgba(255, 255, 255, 0.9);
        }

        .tema-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.2);
            font-size: 0.8rem;
        }

        .tema-icon {
            font-size: 1.25rem;
            transition: transform 0.3s ease;
            color: var(--color-primary, #1a4789);
        }

        .tema-card.active .tema-icon {
            color: white;
            transform: rotate(180deg);
        }

        .tema-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease;
        }

        .tema-card.active .tema-content {
            max-height: 10000px;
        }

        .tema-body {
            padding: 1.5rem;
            background: white;
        }

        .empty-temas {
            text-align: center;
            padding: 3rem 1rem;
            background: white;
            border-radius: 12px;
            border: 2px dashed #dee2e6;
        }

        .empty-temas i {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 1rem;
        }

        .lock-icon {
            margin-left: 0.5rem;
            font-size: 1.1rem;
        }
    </style>

    <div class="temas-accordion">
        @forelse($temas as $index => $tema)
            @php
                $estaDesbloqueado =
                    auth()->user()->hasRole('Docente') ||
                    (auth()->user()->hasRole('Estudiante') && $tema->estaDesbloqueado($inscritos2->id));
            @endphp
            <div class="tema-card {{ $index === 0 && $estaDesbloqueado ? 'active' : '' }} {{ !$estaDesbloqueado ? 'locked' : '' }}"
                data-tema-id="{{ $tema->id }}">
                <div class="tema-header {{ !$estaDesbloqueado ? 'locked' : '' }}"
                    onclick="{{ $estaDesbloqueado ? 'toggleTema(this)' : 'showLockMessage()' }}">
                    <div class="tema-info">
                        <div class="tema-number">{{ $loop->iteration }}</div>
                        <div class="tema-title-section">
                            <h4 class="tema-title">
                                {{ $tema->titulo_tema }}
                                @if (!$estaDesbloqueado)
                                    <i class="fas fa-lock lock-icon"></i>
                                @endif
                            </h4>
                            <div class="tema-meta">
                                <span class="tema-badge">
                                    <i class="fas fa-layer-group"></i>
                                    {{ count($tema->subtemas) }} Subtemas
                                </span>
                                @if (auth()->user()->hasRole('Estudiante'))
                                    @php
                                        $progreso = $tema->calcularProgreso($inscritos2->id);
                                    @endphp
                                    <span class="tema-badge">
                                        <i class="fas fa-chart-line"></i>
                                        {{ $progreso }}% Completado
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <i class="fas fa-chevron-down tema-icon"></i>
                </div>
                <div class="tema-content">
                    <div class="tema-body">
                        @if ($estaDesbloqueado)
                            @include('partials.cursos.tema_item', ['tema' => $tema, 'index' => $index])
                        @else
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-lock me-2"></i>
                                <strong>Tema bloqueado:</strong> Debes completar el tema anterior para desbloquear este
                                contenido.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-temas">
                <i class="fas fa-book-open"></i>
                <h4 class="mt-3 mb-2">No hay temas disponibles</h4>
                <p class="text-muted">Aún no se ha agregado contenido a este curso.</p>
                @if (auth()->user()->hasRole('Docente') && $cursos->docente_id == auth()->user()->id)
                    <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#modalTema">
                        <i class="fas fa-plus me-2"></i>Crear Primer Tema
                    </button>
                @endif
            </div>
        @endforelse
    </div>

    <script>
        function toggleTema(header) {
            const card = header.closest('.tema-card');
            const isActive = card.classList.contains('active');

            // Cerrar todos los temas
            document.querySelectorAll('.tema-card').forEach(c => {
                c.classList.remove('active');
            });

            // Abrir el tema clickeado si estaba cerrado
            if (!isActive) {
                card.classList.add('active');
                // Scroll suave al tema
                setTimeout(() => {
                    card.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest'
                    });
                }, 100);
            }
        }

        function showLockMessage() {
            Swal.fire({
                icon: 'info',
                title: 'Tema Bloqueado',
                text: 'Debes completar el tema anterior para desbloquear este contenido.',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#1a4789'
            });
        }

        // Inicializar: abrir el primer tema desbloqueado
        document.addEventListener('DOMContentLoaded', function() {
            const firstUnlocked = document.querySelector('.tema-card:not(.locked)');
            if (firstUnlocked) {
                firstUnlocked.classList.add('active');
            }
        });
    </script>
</div>
