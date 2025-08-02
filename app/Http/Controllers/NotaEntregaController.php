<?php

namespace App\Http\Controllers;

use App\Models\Inscritos;
use App\Models\NotaEntrega;
use App\Models\Tareas;
use App\Models\TareasEntrega;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotaEntregaController extends Controller
{
    //
    public function CuestionarioResultado(Request $request) {


        dd($request->all());
        // Validar y procesar los datos recibidos
        $scorePercentage = $request->input('score_percentage');




        $tarea = Tareas::findOrFail($request->input('tarea_id'));
        $incripcion = Inscritos::where('cursos_id', $tarea->cursos_id)->where('estudiante_id', $request->input('estudiante_id'))->first();
        // Guardar los resultados en la base de datos o en el sistema de archivos
        // Ejemplo de guardado en la base de datos:
        $notaentrega = new NotaEntrega();
        $notaentrega->nota = $scorePercentage;
        $notaentrega->retroalimentacion = 'Cuestionario Resuelto el ' . Carbon::now()->format('Y-m-d');
        $notaentrega->tarea_id =  $tarea->id;
        $notaentrega->inscripcion_id = $incripcion->id;
        $notaentrega->save();

        $entrega = new TareasEntrega();

        $entrega->estudiante_id = $request->estudiante_id;
        $entrega->tarea_id = $request->tarea_id;



        $entrega ->save();




        // Redirigir o devolver una respuesta según sea necesario
        return response()->json(['message' => 'Resultados guardados correctamente'], 200);

    }
}
