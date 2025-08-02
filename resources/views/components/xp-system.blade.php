@auth
    <!-- Estilos para el sistema de XP -->
    <style>
        .xbox-xp-notification {
            position: fixed;
            top: 50px;
            right: -400px;
            width: 350px;
            background: rgba(26, 71, 137, 0.95);
            color: white;
            border-radius: 10px;
            padding: 20px;
            z-index: 9999;
            transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            backdrop-filter: blur(5px);
            box-shadow: 0 0 20px rgba(57, 166, 203, 0.2);
            opacity: 0;
        }

        .xbox-xp-notification.show {
            right: 20px;
            opacity: 1;
        }

        .xbox-xp-content {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .xbox-xp-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            animation: pulse 2s infinite;
        }

        .xbox-xp-details {
            flex-grow: 1;
        }

        .xbox-xp-title {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
            color: var(--secondary-color);
        }

        .xbox-xp-description {
            font-size: 14px;
            margin: 5px 0 0;
            opacity: 0.9;
        }

        .xbox-progress-bar {
            height: 4px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
            margin-top: 10px;
            overflow: hidden;
        }

        .xbox-progress-fill {
            height: 100%;
            background: var(--secondary-color);
            width: 0%;
            transition: width 1s ease;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(57, 166, 203, 0.4);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(57, 166, 203, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(57, 166, 203, 0);
            }
        }

        /* Botón flotante estilo Xbox */
        .xbox-floating-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(26, 71, 137, 0.2);
        }

        .xbox-floating-button:hover {
            transform: translateY(-5px);
            background: var(--secondary-color);
            box-shadow: 0 6px 20px rgba(57, 166, 203, 0.3);
        }

        .xbox-floating-button i {
            color: white;
            font-size: 24px;
        }

        /* Panel lateral estilo Xbox */
        .xbox-sidebar {
            position: fixed;
            top: 0;
            right: -400px;
            width: 380px;
            height: 100vh;
            background: var(--primary-color);
            padding: 30px;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            z-index: 1050;
            overflow-y: auto;
            box-shadow: -5px 0 25px rgba(0, 0, 0, 0.1);
        }

        .xbox-sidebar.show {
            right: 0;
        }

        .xbox-achievement {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            transform: translateX(50px);
            opacity: 0;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .xbox-achievement.show {
            transform: translateX(0);
            opacity: 1;
        }

        .xbox-close-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 1060;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .xbox-close-button:hover {
            transform: rotate(90deg);
            color: var(--secondary-color);
            background-color: rgba(255, 255, 255, 0.2);
        }

        .xbox-close-button i {
            font-size: 28px;
            line-height: 1;
        }
    </style>

    <!-- Botón flotante estilo Xbox -->
    <div class="xbox-floating-button" id="xboxXPButton">
        <i class="bi bi-trophy-fill"></i>
    </div>

    <!-- Panel lateral estilo Xbox -->
    <div class="xbox-sidebar" id="xboxSidebar">
        <button class="xbox-close-button" id="xboxCloseButton">
            <i class="bi bi-x"></i>
        </button>

        <h3 class="mb-4 text-white">Mi Progreso</h3>

        <!-- Nivel actual -->
        <div class="xbox-achievement show" style="background: rgba(255, 255, 255, 0.15)">
            <div class="d-flex align-items-center gap-3">
                <div class="xbox-xp-icon">
                    {{ $currentLevel ? $currentLevel->level_number : 1 }}
                </div>
                <div>
                    <h4 class="mb-0 text-white">Nivel {{ $currentLevel ? $currentLevel->level_number : 1 }}</h4>
                    <p class="mb-0 text-white-50">{{ number_format($totalXP) }} XP Total</p>
                </div>
            </div>
            <div class="xbox-progress-bar mt-3">
                <div class="xbox-progress-fill" style="width: {{ $progressToNext }}%"></div>
            </div>
        </div>

        <!-- Últimos logros -->
        <h5 class="mt-4 mb-3 text-white-50">Últimos Logros</h5>
        @forelse($unlockedAchievements as $index => $achievement)
            <div class="xbox-achievement" style="animation-delay: {{ $index * 0.1 }}s">
                <div class="d-flex align-items-center gap-3">
                    <div class="xbox-xp-icon">
                        {!! $achievement->icon !!}
                    </div>
                    <div>
                        <h6 class="mb-0 text-white">{{ $achievement->title }}</h6>
                        <small class="text-white-50">+{{ $achievement->xp_reward }} XP</small>
                    </div>
                </div>
            </div>
        @empty
            <div class="xbox-achievement show">
                <p class="text-white-50 mb-0">Aún no has desbloqueado ningún logro</p>
            </div>
        @endforelse

        <a href="{{ route('perfil.xp') }}" class="btn w-100 mt-4"
           style="background: var(--secondary-color); color: white; border: none;">
            Ver todos mis logros
        </a>
    </div>

    <!-- Notificación estilo Xbox -->
    <div class="xbox-xp-notification" id="xboxNotification">
        <div class="xbox-xp-content">
            <div class="xbox-xp-icon">
                <i class="bi bi-trophy-fill"></i>
            </div>
            <div class="xbox-xp-details">
                <p class="xbox-xp-title">¡Logro Desbloqueado!</p>
                <p class="xbox-xp-description" id="achievementText"></p>
            </div>
        </div>
        <div class="xbox-progress-bar">
            <div class="xbox-progress-fill"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const xboxButton = document.getElementById('xboxXPButton');
            const xboxSidebar = document.getElementById('xboxSidebar');
            const xboxCloseButton = document.getElementById('xboxCloseButton');
            const achievements = document.querySelectorAll('.xbox-achievement');

            // Mostrar el panel lateral
            xboxButton.addEventListener('click', function() {
                xboxSidebar.classList.add('show');
                // Animar los logros secuencialmente
                achievements.forEach((achievement, index) => {
                    setTimeout(() => {
                        achievement.classList.add('show');
                    }, index * 100);
                });
            });

            // Cerrar el panel lateral
            xboxCloseButton.addEventListener('click', function() {
                xboxSidebar.classList.remove('show');
                // Resetear las animaciones de los logros
                achievements.forEach(achievement => {
                    achievement.classList.remove('show');
                });
            });

            // Función para mostrar notificación de logro
            window.showXboxAchievement = function(title, xp) {
                const notification = document.getElementById('xboxNotification');
                const progressFill = notification.querySelector('.xbox-progress-fill');
                document.getElementById('achievementText').textContent = title + ' (+' + xp + ' XP)';

                notification.classList.add('show');
                progressFill.style.width = '100%';

                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => {
                        progressFill.style.width = '0%';
                    }, 500);
                }, 4000);
            };

            // Ejemplo de uso:
            // showXboxAchievement('¡Primer Cuestionario Perfecto!', 100);
        });
    </script>
@else
    <!-- Modal para usuarios no autenticados -->
    <div class="xbox-floating-button" data-bs-toggle="modal" data-bs-target="#xboxRegisterModal">
        <i class="bi bi-trophy-fill"></i>
    </div>

    <div class="modal fade" id="xboxRegisterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header border-0" style="background: var(--primary-color);">
                    <h5 class="modal-title text-white">¡Únete a la aventura!</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="xbox-xp-icon mx-auto mb-3">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                    <h4 class="mb-3" style="color: var(--primary-color)">Desbloquea tu potencial</h4>
                    <p class="mb-4 text-muted">Regístrate para comenzar a ganar XP y desbloquear logros mientras aprendes.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('signin') }}" class="btn text-white" style="background: var(--primary-color);">Registrarme ahora</a>
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary">Ya tengo cuenta</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endauth
