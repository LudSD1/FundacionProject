<?php

namespace App\Http\Controllers;

use App\Models\PreguntaTarea;
use App\Models\RespuestaTareas;
use Error;
use Illuminate\Http\Request;

class PreguntaTareaController extends Controller
{
    public function store(Request $request){


        $request->validate([
            'pregunta' => 'required|string', // Verifica la unicidad en la tabla 'niveles' columna 'nombre'
            'puntos' => 'required', // Verifica la unicidad en la tabla 'niveles' columna 'nombre'
        ], [
            'pregunta.required' => 'La pregunta es un campo obligatorio.',
            'puntos.required' => 'La puntuación de la pregunta es necesaria',
        ]);

        if ($request->tipo == 'short') {
            $request->validate([
                'respuestaCorrectashort' => 'required|string', // Verifica la unicidad en la tabla 'niveles' columna 'nombre'
            ], [
                'respuestaCorrectashort.required' => 'La respuesta es un campo obligatorio.',
            ]);

        $pregunta = new PreguntaTarea();
        $pregunta->tarea_id = $request->tarea_id;
        $pregunta->texto_pregunta = $request->pregunta;
        $pregunta->puntos = $request->puntos;
        $pregunta->tarea_id = $request->tarea_id;

        $pregunta->save();

        $respuesta = new RespuestaTareas();

        $ultimo_id_creado = PreguntaTarea::max('id');
        $respuesta->pregunta_id = $ultimo_id_creado;
        $respuesta->texto_respuesta = $request->respuestaCorrectashort;
        $respuesta->es_correcta = true;
        $respuesta->save();


        return redirect(route('cuestionario', $request->tarea_id))->with('success', 'Pregunta creada con exito');






        }elseif ($request->tipo == 'verdaderofalso') {
            $request->validate([
                'vf' => 'required',
            ], [
                'vf.required' => 'Selecciona una respuesta.',
            ]);

            $pregunta = new PreguntaTarea();
            $pregunta->tarea_id = $request->tarea_id;
            $pregunta->tipo_preg = 'vf';
            $pregunta->texto_pregunta = $request->pregunta;
            $pregunta->puntos = $request->puntos;
            $pregunta->save();

            // Determinar la respuesta correcta y la incorrecta
            if ($request->vf == '1') {
                // Si la respuesta es verdadera
                $texto_respuesta_correcta = 'Verdadero';
                $texto_respuesta_incorrecta = 'Falso';
            } else {
                // Si la respuesta es falsa
                $texto_respuesta_correcta = 'Falso';
                $texto_respuesta_incorrecta = 'Verdadero';
            }

            // Crear la respuesta correcta
            $respuesta_correcta = new RespuestaTareas();
            $respuesta_correcta->pregunta_id = $pregunta->id;
            $respuesta_correcta->texto_respuesta = $texto_respuesta_correcta;
            $respuesta_correcta->es_correcta = true;
            $respuesta_correcta->save();

            // Crear la respuesta incorrecta
            $respuesta_incorrecta = new RespuestaTareas();
            $respuesta_incorrecta->pregunta_id = $pregunta->id;
            $respuesta_incorrecta->texto_respuesta = $texto_respuesta_incorrecta;
            $respuesta_incorrecta->es_correcta = false;
            $respuesta_incorrecta->save();

            return redirect(route('cuestionario', $request->tarea_id))->with('success', 'Pregunta creada con éxito');
        }elseif ($request->tipo == 'multiple'){
            $request->validate([
                'respuestaCorrecta' => 'required|string',
            ], [
                'respuestaCorrecta.required' => 'La respuesta es un campo obligatorio.',
            ]);

            $pregunta = new PreguntaTarea();
            $pregunta->tarea_id = $request->tarea_id;
            $pregunta->tipo_preg = 'multiple';
            $pregunta->texto_pregunta = $request->pregunta;
            $pregunta->puntos = $request->puntos;
            $pregunta->save();

            // Guardar la respuesta correcta
            $respuesta = new RespuestaTareas();
            $respuesta->pregunta_id = PreguntaTarea::max('id');
            $respuesta->texto_respuesta = $request->respuestaCorrecta;
            $respuesta->es_correcta = true;
            $respuesta->save();





            return redirect(route('cuestionario', $request->tarea_id))->with('success', 'Pregunta creada con éxito');}

            }


            public function delete($id){

                $pregunta = PreguntaTarea::find($id);

                $tarea_id = $pregunta->tarea_id;

                $pregunta->delete();

                return redirect(route('cuestionario', $tarea_id))->with('success', 'Eliminado correctamente');



            }




    }
