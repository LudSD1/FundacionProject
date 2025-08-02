<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\EdadDirigida;
use App\Models\Horario;
use App\Models\Nivel;
use Carbon\Carbon;
use CursoSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // 1. Datos básicos del sistema
            Roles::class, // Roles y permisos
            TipoEvaluacionesSeeder::class, // Tipos de evaluaciones
            TipoActividadesSeeder::class, // Tipos de actividades
            XpEventTypesSeeder::class, // Tipos de eventos XP
            LevelsTableSeeder::class, // Niveles de usuario

            // 2. Categorías y estructura
            CategoriaSeeder::class, // Categorías de cursos

            // 3. Usuarios iniciales
            Administrador::class, // Usuario administrador y docentes iniciales

            // 4. Gamificación
            AchievementsTableSeeder::class, // Logros del sistema

            // 5. Contenido inicial
            CursoSeeeder::class, // Cursos de ejemplo
            CursosSeeder::class, // Cursos adicionales
            RecursosSeeder::class, // Recursos educativos iniciales
            ExpositoresSeeder::class, // Expositores iniciales


        ]);
    }
}
