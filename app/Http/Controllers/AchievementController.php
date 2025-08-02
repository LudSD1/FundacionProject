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

        if (!$inscrito) {
            return view('profile.no-achievements', [
                'message' => 'Necesitas estar inscrito en un curso para ver tus logros.'
            ]);
        }

        // Obtener estadísticas de XP
        $stats = $this->xpService->getUserStats($inscrito);

        // Obtener todos los logros con progreso
        $achievements = Achievement::with(['users' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->get()->map(function ($achievement) use ($inscrito) {
            $achievement->isUnlocked = $achievement->users->isNotEmpty();
            $achievement->current_progress = $this->calculateProgress($achievement, $inscrito);
            return $achievement;
        });

        $unlockedAchievements = $achievements->where('isUnlocked', true)->count();
        $totalAchievements = $achievements->count();
        $completionPercentage = round(($unlockedAchievements / $totalAchievements) * 100);

        return view('profile.achievements', [
            'userLevel' => $stats['current_level'],
            'currentXP' => $stats['current_xp'],
            'nextLevelXP' => $stats['next_level_xp'],
            'totalXP' => $stats['current_xp'],
            'achievements' => $achievements,
            'unlockedAchievements' => $unlockedAchievements,
            'totalAchievements' => $totalAchievements,
            'completionPercentage' => $completionPercentage,
            'userRank' => $stats['rank']
        ]);
    }

    protected function calculateProgress($achievement, $inscrito)
    {
        switch ($achievement->type) {
            case 'QUIZ_MASTER':
                return $inscrito->perfectQuizzes()->count();
            
            case 'FORUM_CONTRIBUTOR':
                return $inscrito->forumPosts()->count();
            
            case 'RESOURCE_EXPLORER':
                return $inscrito->resourceViews()->count();
            
            case 'EARLY_BIRD':
                return $inscrito->earlySubmissions()->count();
            
            case 'STREAK_MASTER':
                return $inscrito->currentStreak;
            
            case 'NIGHT_OWL':
                return $inscrito->nightActivities()->count();
            
            case 'SPEED_RUNNER':
                return $inscrito->speedyQuizzes()->count();
            
            case 'FORUM_LIKES':
                return $inscrito->forumLikes()->count();
            
            case 'DAILY_ACTIVITIES':
                return $inscrito->todayActivities()->count();
            
            default:
                return 0;
        }
    }

    /**
     * Desbloquear un logro para el usuario
     */
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

    /**
     * Obtener el progreso actual del usuario
     */
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