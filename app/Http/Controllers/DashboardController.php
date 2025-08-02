<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Curso;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function notificacionesReportes()
    {
        // Obtener usuarios con ubicación
        $usuarios_con_ubicacion = User::whereNotNull('PaisReside')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with(['profile']) // Asumiendo que tienes una relación con el perfil del usuario
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar ?? null,
                    'country' => $user->PaisReside,
                    'country_code' => $user->country_code,
                    'city' => $user->CiudadReside,
                    'latitude' => $user->latitude,
                    'longitude' => $user->longitude,
                    'last_activity' => $user->last_activity ?? $user->updated_at
                ];
            });

        return view('partials.dashboard.admin.notificaciones-reportes', [
            'usuarios_con_ubicacion' => $usuarios_con_ubicacion,
            'cursos' => Curso::with('inscritos')->get()
        ]);
    }
}
