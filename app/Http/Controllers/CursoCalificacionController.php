<?php

namespace App\Http\Controllers;

use App\Models\CursoCalificacion;
use App\Models\Cursos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CursoCalificacionController extends Controller
{
    // app/Http/Controllers/CalificacionController.php
    public function store(Request $request, Cursos $curso)
    {
        $request->validate([
            'puntuacion' => 'required|numeric|min:1|max:5',
            'comentario' => 'nullable|string|max:500'
        ]);

        // Verificar duplicado
        $existingRating = $curso->calificaciones()
            ->where('user_id', auth()->id())
            ->first();

        if ($existingRating) {
            return back()->with('error', 'Ya has calificado este curso');
        }


        // Lista de palabras prohibidas
        $palabrasProhibidas = [
            'idiota',
            'imbécil',
            'estúpido',
            'tonto',
            'bobo',
            'burro',
            'animal',
            'payaso',

            'mierda',
            'puta',
            'puto',
            'joder',
            'coño',
            'gilipollas',
            'pendejo',
            'cabron',
            'cabrona',
            'chingar',
            'chingado',
            'chingada',
            'culero',
            'pelotudo',
            'boludo',
            'cagada',

            'asqueroso',
            'repugnante',
            'maldito',
            'desgraciado',
            'pervertido',
            'tarado',
            'malnacido',

            'negro',
            'indio',
            'mongólico',
            'retrasado',
            'sidoso',
            'maricón',
            'marica',
            'trava',

            'zorra',
            'perra',
            'gorda',
            'fea',
            'cerda',
            'calva',
            'pelada',
            'babosa',
            'despeinada',

            'mrd',
            'ptm',
            'hdp',
            'qlo',
            'vrg',
            'ctm',
            'hp',
            'pndjo',
            'wn',
            'wtf',
            'hpta',
            'pkm'
        ];

        $comentario = strtolower($request->comentario); // normalizar
        foreach ($palabrasProhibidas as $palabra) {
            $comentario = preg_replace('/\b' . preg_quote($palabra, '/') . '\b/i', str_repeat('*', strlen($palabra)), $comentario);
        }

        // Reemplazar palabras ofensivas con ***
        if ($comentario) {
            foreach ($palabrasProhibidas as $palabra) {
                $comentario = preg_replace('/\b' . preg_quote($palabra, '/') . '\b/i', str_repeat('*', strlen($palabra)), $comentario);
            }
        }

        $curso->calificaciones()->create([
            'user_id' => auth()->id(),
            'puntuacion' => $request->puntuacion,
            'comentario' => $comentario
        ]);

        return back()->with('success', 'Gracias por tu calificación!');
    }



    public function destroy(CursoCalificacion $calificacion)
    {
        // Verificar que el usuario es dueño de la calificación
        if ($calificacion->user_id != Auth::id() && !Auth::user()->hasRole('Administrador')) {
            return back()->with('error', 'No tienes permiso para eliminar esta calificación');
        }

        $calificacion->delete();
        return back()->with('success', 'Calificación eliminada');
    }

    // Ver todas las calificaciones (opcional)
    public function index(Cursos $curso)
    {
        $calificaciones = $curso->calificaciones()
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('cursos.calificaciones', compact('curso', 'calificaciones'));
    }
}
