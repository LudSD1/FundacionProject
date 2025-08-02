<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Inscritos;
use App\Models\Level;
use App\Services\XPService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class XPController extends Controller
{
    protected $xpService;

    public function __construct(XPService $xpService)
    {
        $this->xpService = $xpService;
    }

    public function index()
    {
        $user = Auth::user();
        $inscripciones = $user->inscritos()->with(['cursos'])->get();
        
        // Obtener historial de XP y calcular total
        $xpHistory = \DB::table('xp_events')
            ->where('users_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $totalXP = $xpHistory->sum('xp');
        
        // Obtener nivel actual y siguiente
        $currentLevel = Level::getCurrentLevel($totalXP);
        $nextLevel = Level::getNextLevel($totalXP);
        
        // Calcular progreso al siguiente nivel
        $progressToNext = 0;
        if ($nextLevel) {
            $xpForNextLevel = $nextLevel->required_xp - ($currentLevel ? $currentLevel->required_xp : 0);
            $currentProgress = $totalXP - ($currentLevel ? $currentLevel->required_xp : 0);
            $progressToNext = ($currentProgress / $xpForNextLevel) * 100;
        }
        
        // Obtener logros desbloqueados
        $unlockedAchievements = Achievement::whereHas('inscritos', function($query) use ($inscripciones) {
            $query->whereIn('inscrito_id', $inscripciones->pluck('id'));
        })->get();
        
        // Obtener logros disponibles (no secretos y no desbloqueados)
        $availableAchievements = Achievement::whereDoesntHave('inscritos', function($query) use ($inscripciones) {
            $query->whereIn('inscrito_id', $inscripciones->pluck('id'));
        })->where('is_secret', false)->get();

        return view('perfil.xp', compact(
            'totalXP',
            'currentLevel',
            'nextLevel',
            'progressToNext',
            'unlockedAchievements',
            'availableAchievements',
            'xpHistory',
            'inscripciones'
        ));
    }
}
