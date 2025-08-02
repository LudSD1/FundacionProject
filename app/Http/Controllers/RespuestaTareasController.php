<?php

namespace App\Http\Controllers;

use App\Models\RespuestaTareas;
use Illuminate\Http\Request;

class RespuestaTareasController extends Controller
{
    public function crearRespuesta(Request $request){

        $messages = [
            'pregunta_id' => 'El campo id de la pregunta es necesario.',
            'respuesta.required' => 'El campo respuesta de la tarea es obligatorio.',
            'vf.required' => 'El campo fecha de habilitación es obligatorio.',
        ];

        $request->validate([
            'pregunta_id' => 'required',
            'respuesta' => 'required',
            'vf' => 'required',
        ], $messages);

        $respuesta = new RespuestaTareas();

        $respuesta->pregunta_id = $request->pregunta_id;
        $respuesta->texto_respuesta = $request->respuesta;
        $respuesta->es_correcta = $request->vf;

        $respuesta->save();

        return redirect()->route('respuestas', $request->pregunta_id)->with([ 'success' => 'Respuesta Creada Correctamente']);
    }

    public function actualizarRespuesta(Request $request, $id){
        $messages = [
            'texto_respuesta.required' => 'El campo respuesta de la tarea es obligatorio.',
            'es_correcta.required' => 'El campo fecha de habilitación es obligatorio.',
        ];

        $request->validate([
            'texto_respuesta' => 'required',
            'es_correcta' => 'required',
        ], $messages);

        $respuesta = RespuestaTareas::findOrFail($id);

        $respuesta->texto_respuesta = $request->texto_respuesta;
        $respuesta->es_correcta = $request->es_correcta;

        $respuesta->save();

        return redirect()->route('respuestas', $respuesta->pregunta_id)->with([ 'success' => 'Respuesta Actualizada Correctamente']);
    }


    public function eliminarRespuesta($id){
        $respuesta = RespuestaTareas::findOrFail($id);
        $pregunta_id = $respuesta->pregunta_id; // Guardamos el ID de la pregunta antes de eliminar la respuesta
        $respuesta->delete();

        return redirect()->route('respuestas', $pregunta_id)->with([ 'success' => 'Respuesta Eliminada Correctamente']);
    }


    public function restaurarRespuesta($id){
        $respuesta = RespuestaTareas::withTrashed()->findOrFail($id);
        $respuesta->restore();

        return redirect()->route('respuestas', $respuesta->pregunta_id)->with([ 'success' => 'Respuesta Restaurada Correctamente']);
    }




}
