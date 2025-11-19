<?php

namespace App\Http\Controllers;

use App\Models\Opcion;
use App\Models\Pregunta;
use App\Models\Respuesta;
use Illuminate\Http\Request;

class RespuestaController extends Controller
{
    public function store(Request $request, $id)
    {

        $request->validate([
            'respuesta' => 'required|string|max:255',
            'es_correcta' => 'required|boolean',
        ], [
            'pregunta.required' => 'La Respuesta es obligatoria.',
            'pregunta.string' => 'La pregunta debe ser una cadena de texto.',
            'pregunta.max' => 'La pregunta no debe superar los 255 caracteres.',
            'es_correcta.required' => 'La Respuesta es correcta o incorrecta.',
        ]);


        Respuesta::create([
            'pregunta_id' => $id,
            'texto' => $request->respuesta,
            'es_correcta' => $request->es_correcta,
        ]);


        return back()->with('success', 'Pregunta creada correctamente.');
    }


    public function storeVerdaderoFalso($preguntaId)
    {
        $pregunta = Pregunta::findOrFail($preguntaId);

        if ($pregunta->tipo !== 'boolean') {
            return back()->with('error', 'Solo puedes generar respuestas para preguntas de tipo Verdadero/Falso.');
        }

        // Verificar si ya existen respuestas para esta pregunta
        if ($pregunta->respuestas()->exists()) {
            return back()->with('error', 'Las respuestas Verdadero/Falso ya han sido generadas para esta pregunta.');
        }

        // Crear respuestas "Verdadero" y "Falso"
        $pregunta->respuestas()->createMany([
            ['contenido' => 'Verdadero', 'es_correcta' => true],
            ['contenido' => 'Falso', 'es_correcta' => false],
        ]);

        return back()->with('success', 'Respuestas Verdadero/Falso generadas correctamente.');
    }



    public function storeMultiple(Request $request, $preguntaId)
    {
        $request->validate([
            'respuestas' => 'required|array|min:1',
            'respuestas.*.contenido' => 'required|string|max:255',
            'respuestas.*.es_correcta' => 'required|boolean',
        ], [
            'respuestas.required' => 'Debes agregar al menos una respuesta.',
            'respuestas.*.contenido.required' => 'El contenido de cada respuesta es obligatorio.',
            'respuestas.*.contenido.string' => 'El contenido de cada respuesta debe ser una cadena de texto.',
            'respuestas.*.contenido.max' => 'El contenido de cada respuesta no debe superar los 255 caracteres.',
            'respuestas.*.es_correcta.required' => 'Debes indicar si cada respuesta es correcta o incorrecta.',
        ]);

        $pregunta = Pregunta::findOrFail($preguntaId);

        foreach ($request->respuestas as $respuestaData) {
            $pregunta->respuestas()->create([
                'contenido' => $respuestaData['contenido'],
                'es_correcta' => $respuestaData['es_correcta'],
            ]);
        }

        return back()->with('success', 'Respuestas agregadas correctamente.');
    }


    public function storeRespuestasClave(Request $request, $preguntaId)
    {
        $pregunta = Pregunta::findOrFail($preguntaId);

        if ($pregunta->tipo !== 'abierta') {
            return back()->with('error', 'Solo puedes agregar respuestas clave a preguntas abiertas.');
        }

        // Validar las respuestas clave
        $request->validate([
            'respuestas' => 'required|array|min:1',
            'respuestas.*.contenido' => 'required|string|max:255',
        ], [
            'respuestas.required' => 'Debes agregar al menos una respuesta clave.',
            'respuestas.*.contenido.required' => 'El contenido de cada respuesta clave es obligatorio.',
            'respuestas.*.contenido.string' => 'El contenido de cada respuesta clave debe ser una cadena de texto.',
            'respuestas.*.contenido.max' => 'El contenido de cada respuesta clave no debe superar los 255 caracteres.',
        ]);

        // Crear las respuestas clave en la tabla `respuestas`
        foreach ($request->respuestas as $respuestaData) {
            $pregunta->respuestas()->create([
                'contenido' => $respuestaData['contenido'],
                'es_correcta' => true, // Las respuestas clave siempre son correctas
            ]);
        }

        return back()->with('success', 'Respuestas clave agregadas correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'contenido' => 'required|string|max:255',
            'es_correcta' => 'required|boolean',
        ], [
            'contenido.required' => 'El contenido de la respuesta es obligatorio.',
            'contenido.string' => 'El contenido debe ser una cadena de texto.',
            'contenido.max' => 'El contenido no debe superar los 255 caracteres.',
            'es_correcta.required' => 'Debes indicar si la respuesta es correcta o incorrecta.',
        ]);

        $respuesta = Respuesta::findOrFail($id);
        $respuesta->update([
            'contenido' => $request->contenido,
            'es_correcta' => $request->es_correcta,
        ]);

        return back()->with('success', 'Respuesta actualizada correctamente.');
    }

    public function delete($id)
    {
        $opcion = Respuesta::findOrFail($id);
        $opcion->delete();
        return back()->with('success', 'Respuesta eliminada correctamente.');
    }
    public function restore($id)
    {
        $opcion = Respuesta::onlyTrashed()->findOrFail($id);
        $opcion->restore();
        return back()->with('success', 'Pregunta restaurada correctamente.');
    }
}
