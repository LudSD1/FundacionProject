<?php
namespace App\Http\Controllers;

use App\Models\Respuesta;
use Illuminate\Http\Request;

class ResultadosController extends Controller
{
    // Muestra las respuestas pendientes de calificación
    public function indexPendientes()
    {
        $respuestasPendientes = Respuesta::with('pregunta', 'estudiante')
            ->where('estado_calificacion', 'pendiente')
            ->whereNotNull('respuesta') // Solo preguntas abiertas
            ->get();

        return view('respuestas.pendientes', compact('respuestasPendientes'));
    }



    public function store(Request $request, $id){

        $request->validate([
            'pregunta' => 'required|string|max:255',
            'tipo' => 'nullable|string',
            'puntos' => 'nullable|int',
        ], [
            'pregunta.required' => 'La pregunta es obligatoria.',
            'pregunta.string' => 'La pregunta debe ser una cadena de texto.',
            'pregunta.max' => 'La pregunta no debe superar los 255 caracteres.',
            'tipo.string' => 'El tipo debe ser una cadena de texto.',
            'puntos.number' => 'Los puntos deben ser una cadena de texto.',
        ]);


        Respuesta::create([
            'cuestionario_id' => $id,
            'pregunta' => $request->pregunta,
            'tipo' => $request->tipo_preg,
            'puntos' => $request->puntos,
        ]);


        return back()->with('success', 'Pregunta creada correctamente.');


    }

    // Califica una respuesta
    public function calificar(Request $request, $id)
    {
        $request->validate([
            'puntos_obtenidos' => 'required|numeric|min:0',
        ]);

        $respuesta = Respuesta::findOrFail($id);
        $respuesta->puntos_obtenidos = $request->puntos_obtenidos;
        $respuesta->estado_calificacion = 'calificada';
        $respuesta->save();

        return redirect()->route('respuestas.pendientes')
                         ->with('success', 'Respuesta calificada correctamente.');
    }
}
