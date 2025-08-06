@php
    $achievements = \App\Models\Achievement::with('users')->get();
@endphp

<div class="row mb-3">
    <div class="col-md-6">
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control" id="searchAchievements" placeholder="Buscar logros..."
                autocomplete="off">
        </div>
    </div>
    <div class="col-md-6 text-end">
        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newAchievementModal">
            <i class="bi bi-plus-lg"></i> Nuevo Logro
        </button>
    </div>
</div>

<div class="row g-4" id="achievementsContainer">
    @forelse ($achievements as $achievement)
        <div class="col-md-4 achievement-item" data-title="{{ strtolower($achievement->title) }}"
            data-description="{{ strtolower($achievement->description) }}"
            data-type="{{ strtolower($achievement->type) }}" data-category="{{ strtolower($achievement->category) }}">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="achievement-icon me-3" style="font-size: 2.5rem;">
                            {{ $achievement->icon }}
                        </div>
                        <div>
                            <h5 class="card-title mb-1">{{ $achievement->title }}</h5>
                            <p class="card-subtitle text-muted">
                                {{ $achievement->users->count() ?? 0 }} usuarios han obtenido este logro
                            </p>
                        </div>
                    </div>

                    <p class="card-text">{{ $achievement->description }}</p>

                    @if ($achievement->requirement_value > 1)
                        <div class="progress mb-3">
                            @php
                                $progress = $achievement->users->count()
                                    ? ($achievement->users->count() / $achievement->requirement_value) * 100
                                    : 0;
                                $progress = min(100, $progress);
                            @endphp
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progress }}%"
                                aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $achievement->users->count() ?? 0 }}/{{ $achievement->requirement_value }}
                            </div>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-primary me-2">{{ $achievement->xp_reward }} XP</span>
                            <span class="badge bg-{{ $achievement->is_secret ? 'danger' : 'info' }}">
                                {{ $achievement->is_secret ? 'Secreto' : 'Público' }}
                            </span>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-outline-primary btn-sm"
                                onclick="viewAchievement({{ $achievement->id }})" data-bs-toggle="tooltip"
                                title="Ver detalles">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-outline-secondary btn-sm"
                                onclick="editAchievement({{ $achievement->id }})" data-bs-toggle="tooltip"
                                title="Editar">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm"
                                onclick="deleteAchievement({{ $achievement->id }})" data-bs-toggle="tooltip"
                                title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12" id="emptyState">
            <div class="empty-state text-center py-5">
                <i class="bi bi-trophy display-4 text-muted"></i>
                <p class="mt-3 mb-0">No hay logros disponibles</p>
                <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#newAchievementModal">
                    Crear primer logro
                </button>
            </div>
        </div>
    @endforelse
</div>

<!-- Estado vacío para búsquedas sin resultados -->
<div class="col-12 d-none" id="noResults">
    <div class="empty-state text-center py-5">
        <i class="bi bi-search display-4 text-muted"></i>
        <p class="mt-3 mb-0">No se encontraron logros que coincidan con tu búsqueda</p>
        <button class="btn btn-outline-secondary mt-3" onclick="clearSearch()">
            Limpiar búsqueda
        </button>
    </div>
</div>

<!-- Modal para nuevo logro -->
<div class="modal fade" id="newAchievementModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Logro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="newAchievementForm" method="POST" action="">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">XP a otorgar</label>
                        <input type="number" class="form-control" name="xp_reward" required min="0"
                            value="100">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipo</label>
                        <select class="form-select" name="type" required>
                            <option value="QUIZ_MASTER">Maestro de Cuestionarios</option>
                            <option value="COURSE_COMPLETER">Completador de Cursos</option>
                            <option value="PERFECT_SCORE">Puntuación Perfecta</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Valor Requerido</label>
                        <input type="number" class="form-control" name="requirement_value" required min="1"
                            value="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Categoría</label>
                        <select class="form-select" name="category" required>
                            <option value="academic">Académico</option>
                            <option value="participation">Participación</option>
                            <option value="social">Social</option>
                        </select>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_secret" id="is_secret">
                        <label class="form-check-label" for="is_secret">Logro Secreto</label>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ícono</label>
                        <select class="form-select" name="icon" required>
                            <option value="🎯">🎯 Objetivo</option>
                            <option value="🏆">🏆 Trofeo</option>
                            <option value="⭐">⭐ Estrella</option>
                            <option value="🏅">🏅 Medalla</option>
                            <option value="👑">👑 Corona</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="saveAchievement()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Variables globales
    let allAchievements = [];
    let currentSearchTerm = '';

    // Inicializar cuando el DOM esté listo
    (function() {
        'use strict';

        // Esperar a que el DOM esté completamente cargado
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeAchievements);
        } else {
            initializeAchievements();
        }

        function initializeAchievements() {
            console.log('Inicializando sistema de logros...');

            // Obtener todos los elementos de logros
            allAchievements = Array.from(document.querySelectorAll('.achievement-item'));
            console.log('Logros encontrados:', allAchievements.length);

            // Configurar el buscador
            setupSearch();

            // Inicializar tooltips si está disponible Bootstrap
            if (typeof bootstrap !== 'undefined') {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }
        }

        function setupSearch() {
            const searchInput = document.getElementById('searchAchievements');

            if (!searchInput) {
                console.error('Campo de búsqueda no encontrado');
                return;
            }

            console.log('Configurando búsqueda...');

            // Eventos de búsqueda
            searchInput.addEventListener('input', performSearch);
            searchInput.addEventListener('keyup', performSearch);
            searchInput.addEventListener('paste', function() {
                // Usar timeout para permitir que el paste se complete
                setTimeout(performSearch, 10);
            });

            // Búsqueda inicial si hay texto
            if (searchInput.value.trim() !== '') {
                performSearch();
            }
        }

        window.performSearch = function() {
            const searchInput = document.getElementById('searchAchievements');
            const searchTerm = searchInput.value.trim().toLowerCase();

            console.log('Búsqueda:', searchTerm);

            currentSearchTerm = searchTerm;

            if (searchTerm === '') {
                // Mostrar todos los logros
                showAllAchievements();
                return;
            }

            let visibleCount = 0;

            allAchievements.forEach(function(achievement) {
                const title = achievement.getAttribute('data-title') || '';
                const description = achievement.getAttribute('data-description') || '';
                const type = achievement.getAttribute('data-type') || '';
                const category = achievement.getAttribute('data-category') || '';

                const matches = title.includes(searchTerm) ||
                    description.includes(searchTerm) ||
                    type.includes(searchTerm) ||
                    category.includes(searchTerm);

                if (matches) {
                    achievement.style.display = '';
                    achievement.classList.remove('d-none');
                    visibleCount++;
                } else {
                    achievement.style.display = 'none';
                    achievement.classList.add('d-none');
                }
            });

            // Mostrar/ocultar mensaje de "sin resultados"
            const noResults = document.getElementById('noResults');
            const emptyState = document.getElementById('emptyState');

            if (visibleCount === 0) {
                if (noResults) {
                    noResults.classList.remove('d-none');
                }
                if (emptyState) {
                    emptyState.classList.add('d-none');
                }
            } else {
                if (noResults) {
                    noResults.classList.add('d-none');
                }
            }

            console.log('Resultados visibles:', visibleCount);
        };

        function showAllAchievements() {
            allAchievements.forEach(function(achievement) {
                achievement.style.display = '';
                achievement.classList.remove('d-none');
            });

            // Ocultar mensaje de sin resultados
            const noResults = document.getElementById('noResults');
            if (noResults) {
                noResults.classList.add('d-none');
            }
        }

        // Función para limpiar búsqueda
        window.clearSearch = function() {
            const searchInput = document.getElementById('searchAchievements');
            if (searchInput) {
                searchInput.value = '';
                performSearch();
                searchInput.focus();
            }
        };

        // Funciones para los botones de acción
        window.viewAchievement = function(id) {
            console.log('Ver logro:', id);
            // Implementar vista de logro
        };

        window.editAchievement = function(id) {
            console.log('Editar logro:', id);
            // Implementar edición de logro
        };

        window.deleteAchievement = function(id) {
            console.log('Eliminar logro:', id);
            // Implementar eliminación de logro
        };

        window.saveAchievement = function() {
            console.log('Guardar logro');
            // Implementar guardado de logro
        };

        // Fix para modal de Bootstrap
        window.openNewAchievementModal = function() {
            const modal = document.getElementById('newAchievementModal');
            if (modal && typeof bootstrap !== 'undefined') {
                const modalInstance = new bootstrap.Modal(modal);
                modalInstance.show();
            }
        };

        window.closeNewAchievementModal = function() {
            const modal = document.getElementById('newAchievementModal');
            if (modal && typeof bootstrap !== 'undefined') {
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) {
                    modalInstance.hide();
                }
            }
        };

        // Event listeners adicionales para el modal
        document.addEventListener('click', function(e) {
            // Cerrar modal al hacer clic fuera
            if (e.target.classList.contains('modal')) {
                const modal = e.target;
                if (modal.id === 'newAchievementModal') {
                    closeNewAchievementModal();
                }
            }
        });

        // Manejar tecla ESC para cerrar modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeNewAchievementModal();
            }
        });

    })();
</script>
