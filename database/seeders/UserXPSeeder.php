<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserXPSeeder extends Seeder
{
    public function run()
    {
        // Obtener todos los usuarios con rol de estudiante
        $estudiantes = User::role('Estudiante')->get();

        foreach ($estudiantes as $estudiante) {
            // Crear registro de XP para cada estudiante
            DB::table('user_xp')->updateOrInsert(
                ['inscrito_id' => $estudiante->id],
                [
                    'current_xp' => rand(0, 1000),
                    'total_xp_earned' => rand(1000, 5000),
                    'current_level' => rand(1, 5),
                    'last_activity_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
} 