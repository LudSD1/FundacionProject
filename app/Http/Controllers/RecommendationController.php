<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RecommendationController extends Controller
{
    protected RecommendationService $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Vista principal de recomendaciones personalizadas.
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $recommendations = $this->recommendationService->getRecommendations($user, 6);
        $profile = $this->recommendationService->buildUserProfile($user);

        return view('Estudiante.recomendaciones', compact('recommendations', 'profile'));
    }

    /**
     * Endpoint JSON para carga AJAX de recomendaciones.
     */
    public function getRecommendationsJson(Request $request)
    {
        /** @var User $user */
        $user  = Auth::user();
        $limit = $request->input('limit', 6);
        $limit = min(max($limit, 1), 12); // entre 1 y 12

        $recommendations = $this->recommendationService->getRecommendations($user, $limit);

        return response()->json([
            'success' => true,
            'data'    => $recommendations->map(fn($curso) => [
                'id'               => $curso->id,
                'nombreCurso'      => $curso->nombreCurso,
                'descripcionC'     => Str::limit($curso->descripcionC, 120),
                'imagen'           => $curso->imagen,
                'formato'          => $curso->formato,
                'nivel'            => $curso->nivel,
                'precio'           => $curso->precio,
                'tipo'             => $curso->tipo,
                'url'              => $curso->url,
                'rating'           => round($curso->calificaciones_avg_puntuacion ?? 0, 1),
                'inscritos_count'  => $curso->inscritos_count ?? 0,
                'score'            => $curso->recommendation_score,
                'reason'           => $curso->recommendation_reason,
            ]),
        ]);
    }

    /**
     * Registra un click en una recomendación.
     */
    public function trackClick(Request $request)
    {
        $request->validate([
            'curso_id' => 'required|integer|exists:cursos,id',
        ]);

        $this->recommendationService->trackClick(Auth::id(), $request->curso_id);

        return response()->json(['success' => true]);
    }
}
