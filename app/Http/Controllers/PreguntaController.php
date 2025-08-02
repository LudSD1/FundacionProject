<?php

namespace App\Http\Controllers;

use App\Models\Cuestionario;
use App\Models\Pregunta;
use Illuminate\Http\Request;

class PreguntaController extends Controller
{
    public function store(Request $request, $cuestionarioId){


        $request->validate([
            'preguntas' => 'required|array',
            'preguntas.*.enunciado' => 'required|string|max:255',
            'preguntas.*.tipo' => 'required|in:opcion_multiple,abierta,boolean',
            'preguntas.*.puntaje' => 'required|integer|min:1',
        ], [
            'preguntas.required' => 'Las preguntas son obligatorias.',
            'preguntas.*.enunciado.required' => 'El enunciado es obligatorio.',
            'preguntas.*.tipo.required' => 'El tipo de pregunta es obligatorio.',
            'preguntas.*.puntaje.required' => 'El puntaje es obligatorio.',
        ]);



        $cuestionario = Cuestionario::findOrFail($cuestionarioId);

        foreach ($request->preguntas as $preguntaData) {
            $cuestionario->preguntas()->create($preguntaData);
        }

        return back()->with('success', 'Pregunta creada correctamente.');


    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'enunciado' => 'required|string|max:255',
            'tipo' => 'required|in:opcion_multiple,abierta,boolean',
            'puntaje' => 'required|integer|min:1',
        ], [
            'enunciado.required' => 'El enunciado es obligatorio.',
            'tipo.required' => 'El tipo de pregunta es obligatorio.',
            'puntaje.required' => 'El puntaje es obligatorio.',
        ]);

        $pregunta = Pregunta::findOrFail($id);

        $pregunta->update([
            'enunciado' => $request->enunciado,
            'tipo' => $request->tipo,
            'puntaje' => $request->puntaje,
        ]);

        return back()->with('success', 'Pregunta actualizada correctamente.');
    }

    public function delete($id){
        $pregunta = Pregunta::findOrFail($id);
        $pregunta->delete();
        return back()->with('success', 'Pregunta eliminada correctamente.');

    }
    public function restore($id)
    {
        $pregunta = Pregunta::onlyTrashed()->findOrFail($id); // Busca en los registros eliminados
        $pregunta->restore(); // Restaura la pregunta
        return back()->with('success', 'Pregunta restaurada correctamente.');
    }

}
