<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Level;
use App\Models\UserXP;
use App\Models\Inscritos;
use App\Models\Cursos;
use Illuminate\Http\Request;
use App\Services\XPService;
use Illuminate\Support\Facades\Auth;
use App\Models\XPEvent;

class AchievementController extends Controller
{
    protected $xpService;

    public function __construct(XPService $xpService)
    {
        $this->xpService = $xpService;
    }

    public function index()
    {
        $user = Auth::user();
        $inscrito = Inscritos::where('estudiante_id', $user->id)->first();

        // Calcular XP real desde xp_events (fuente de verdad)
        $totalXP = \DB::table('xp_events')
            ->where('users_id', $user->id)
            ->sum('xp');

        // Calcular nivel y progreso desde tabla levels
        $currentLevel = \App\Models\Level::getCurrentLevel($totalXP);
        $nextLevel = \App\Models\Level::getNextLevel($totalXP);

        $currentLevelNumber = $currentLevel ? $currentLevel->level_number : 1;
        $currentLevelXp = $currentLevel ? $currentLevel->required_xp : 0;
        $nextLevelXp = $nextLevel ? $nextLevel->required_xp : 100;

        // Obtener todos los logros con progreso
        $achievements = collect();
        if ($inscrito) {
            $achievements = Achievement::all()->map(function ($achievement) use ($inscrito) {
                $achievement->isUnlocked = $achievement->isUnlockedByInscrito($inscrito);
                $achievement->current_progress = $this->calculateProgress($achievement, $inscrito);
                return $achievement;
            });
        }

        $unlockedAchievements = $achievements->where('isUnlocked', true)->count();
        $totalAchievements = $achievements->count();
        $completionPercentage = $totalAchievements > 0
            ? round(($unlockedAchievements / $totalAchievements) * 100)
            : 0;

        // Calcular ranking
        $userRank = $inscrito
            ? $this->xpService->getUserRank($inscrito)
            : '-';

        return view('profile.achievements', [
            'userLevel' => $currentLevelNumber,
            'currentXP' => $totalXP,
            'nextLevelXP' => $nextLevelXp,
            'totalXP' => $totalXP,
            'achievements' => $achievements,
            'unlockedAchievements' => $unlockedAchievements,
            'totalAchievements' => $totalAchievements,
            'completionPercentage' => $completionPercentage,
            'userRank' => $userRank
        ]);
    }

    protected function calculateProgress($achievement, $inscrito)
    {
        try {
            switch ($achievement->type) {
                case 'QUIZ_MASTER':
                    // Cuestionarios aprobados con nota perfecta
                    return $inscrito->intentosCuestionarios()
                        ->where('puntaje_obtenido', '>=', \DB::raw('puntaje_total'))
                        ->count();

                case 'FORUM_CONTRIBUTOR':
                    // Mensajes en foros
                    return \DB::table('foro_mensajes')
                        ->where('estudiante_id', $inscrito->estudiante_id)
                        ->count();

                case 'RESOURCE_EXPLORER':
                    // Recursos vistos/completados
                    return $inscrito->actividadCompletions()
                        ->where('tipo', 'recurso')
                        ->count();

                case 'EARLY_BIRD':
                    // Entregas antes de fecha límite
                    return $inscrito->notaEntrega()
                        ->whereColumn('created_at', '<', 'fecha_limite')
                        ->count();

                case 'STREAK_MASTER':
                    // Días consecutivos con actividad (simplificado)
                    return 0;

                case 'COURSE_COLLECTOR':
                    // Cursos inscritos del usuario
                    return Inscritos::where('estudiante_id', $inscrito->estudiante_id)->count();

                case 'COURSE_FINISHER':
                    // Cursos completados
                    return Inscritos::where('estudiante_id', $inscrito->estudiante_id)
                        ->where('completado', true)
                        ->count();

                case 'CONGRESS_PARTICIPANT':
                    // Congresos inscritos
                    return Inscritos::where('estudiante_id', $inscrito->estudiante_id)
                        ->whereHas('cursos', fn($q) => $q->where('tipo', 'congreso'))
                        ->count();

                case 'MODULE_MASTER':
                    // Subtemas completados
                    return \DB::table('subtema_inscritos')
                        ->where('inscrito_id', $inscrito->id)
                        ->where('completado', true)
                        ->count();

                default:
                    return 0;
            }
        } catch (\Exception $e) {
            return 0;
        }
    }


    public function unlockAchievement(Request $request)
    {
        $user = auth()->user();
        $achievement = Achievement::findOrFail($request->achievement_id);

        // Verificar si el usuario ya tiene el logro
        if ($user->hasAchievement($achievement->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Ya tienes este logro'
            ]);
        }

        // Registrar el XP ganado
        XPEvent::create([
            'users_id' => $user->id,
            'xp' => $achievement->xp_reward,
            'description' => "Logro desbloqueado: {$achievement->title}",
            'type' => 'achievement'
        ]);

        // Asignar el logro al usuario
        $user->achievements()->attach($achievement->id);

        // Obtener el nivel actual del usuario
        $totalXP = XPEvent::where('users_id', $user->id)->sum('xp');
        $currentLevel = Level::getCurrentLevel($totalXP);
        $nextLevel = Level::getNextLevel($currentLevel->level_number);

        // Calcular progreso al siguiente nivel
        $xpForCurrentLevel = $currentLevel->xp_required;
        $xpForNextLevel = $nextLevel ? $nextLevel->xp_required : $xpForCurrentLevel;
        $xpProgress = $totalXP - $xpForCurrentLevel;
        $xpNeeded = $xpForNextLevel - $xpForCurrentLevel;
        $progressToNext = ($xpNeeded > 0) ? min(100, ($xpProgress / $xpNeeded) * 100) : 0;

        return response()->json([
            'success' => true,
            'achievement' => $achievement,
            'xp_reward' => $achievement->xp_reward,
            'current_level' => $currentLevel,
            'progress_to_next' => $progressToNext,
            'total_xp' => $totalXP
        ]);
    }


    public function getProgress()
    {
        $user = auth()->user();
        $totalXP = XPEvent::where('users_id', $user->id)->sum('xp');
        $currentLevel = Level::getCurrentLevel($totalXP);
        $nextLevel = Level::getNextLevel($currentLevel->level_number);

        // Calcular progreso al siguiente nivel
        $xpForCurrentLevel = $currentLevel->xp_required;
        $xpForNextLevel = $nextLevel ? $nextLevel->xp_required : $xpForCurrentLevel;
        $xpProgress = $totalXP - $xpForCurrentLevel;
        $xpNeeded = $xpForNextLevel - $xpForCurrentLevel;
        $progressToNext = ($xpNeeded > 0) ? min(100, ($xpProgress / $xpNeeded) * 100) : 0;

        // Obtener los últimos logros desbloqueados
        $recentAchievements = $user->achievements()
            ->orderBy('achievement_user.created_at', 'desc')
            ->take(3)
            ->get();

        return response()->json([
            'current_level' => $currentLevel,
            'next_level' => $nextLevel,
            'total_xp' => $totalXP,
            'progress_to_next' => $progressToNext,
            'recent_achievements' => $recentAchievements
        ]);
    }
}
