<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Boletin;
use App\Models\Cursos;
use App\Models\Cursos_Horario;
use App\Models\EdadDirigida;
use App\Models\Evaluaciones;
use App\Models\Foro;
use App\Models\Horario;
use App\Models\Inscritos;
use App\Models\Nivel;
use App\Models\NotaEntrega;
use App\Models\NotaEvaluacion;
use App\Models\Recursos;
use App\Models\Tareas;
use App\Models\TareasEntrega;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Illuminate\Support\Facades\Auth;
use App\Charts\BartChart;
use App\Events\CursoEvent;
use App\Helpers\TextHelper;

class CursosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        // $id =  Crypt::decrypt($id);
        $cursos = Cursos::findOrFail($id);
        $recursos = Recursos::where('cursos_id', $id)->get();
        foreach ($recursos as $recurso) {
            $recurso->descripcionRecursos = TextHelper::createClickableLinksAndPreviews($recurso->descripcionRecursos);
        }
        $inscritos = DB::table('inscritos')->where('cursos_id', $id)->where('estudiante_id', auth()->user()->id)->pluck('estudiante_id');

        if (Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Docente')) {
            $horarios = Cursos_Horario::where('curso_id', $id)->withTrashed()->get();
        }else{
            $horarios = Cursos_Horario::where('curso_id', $id)->get();
        }
        $foros = Foro::where('cursos_id', $id)->get();
        $tareas = Tareas::where('cursos_id', $id)->get();
        $evaluaciones = Evaluaciones::where('cursos_id', $id)->get();



        $inscritos2 = Inscritos::where('cursos_id', $id)
        ->where('estudiante_id', auth()->user()->id)
        ->first();

        if ($inscritos2) {
            $boletin = Boletin::where('inscripcion_id', $inscritos2->id)->first();
        } else {
            $boletin = null;
        }


        return view('Cursos')
            ->with('foros', $foros)
            ->with('recursos', $recursos)
            ->with('tareas', $tareas)
            ->with('cursos', $cursos)
            ->with('inscritos', $inscritos)
            ->with('evaluaciones', $evaluaciones)
            ->with('boletin', $boletin)
            ->with('horarios', $horarios);






    }



    public function listaCurso($id)
    {
        $cursos = Cursos::findOrFail($id);
        $inscritos = Inscritos::where('cursos_id', $id)->whereNull('deleted_at')->get();
        // ["cursos"=>$cursos]
        return view('ListaParticipantes')->with('inscritos', $inscritos)->with('cursos', $cursos);
    }

    public function imprimir($id)
    {
        $curso = Cursos::findOrFail($id);
        $inscritos = Inscritos::where('cursos_id', $id)->whereNull('deleted_at')->get();
        // ["cursos"=>$cursos]
        return view('Estudiante.listadeestudiantes')->with('inscritos', $inscritos)->with('curso', $curso);
    }


    public function listaRetirados($id)
    {
        $cursos = Cursos::findOrFail($id);
        $inscritos = Inscritos::where('cursos_id', $id)->onlyTrashed()->get();
        // ["cursos"=>$cursos]
        return view('ListaParticipantesRetirados')->with('inscritos', $inscritos)->with('cursos', $cursos);
    }


    public function EditCIndex($id)
    {

    $cursos= Cursos::findOrFail($id);
    $niveles = Nivel::all();
    $edad = EdadDirigida::all();
    $docente = User::role('Docente')->get();
    $horario = Horario::all();

    return view('Administrador.EditarCursos')->with('docente', $docente)->with('horario', $horario)->with('edad', $edad)->with('niveles', $niveles)->with('cursos', $cursos);


    }
    public function EditC($id, Request $request)
    {

        $request->validate([
            'nombre' => 'required',
            'fecha_ini' => 'required',
            'fecha_fin' => 'required',
            'formato' => 'required',
            'Dias' => [
                'array', // Asegura que sea un arreglo
                'required', // Asegura que al menos un checkbox esté seleccionado
                // Rule::in(['Domingo','Lunes', 'Martes', 'Miercoles','Jueves','Viernes','Sabado']), // Verifica que los valores sean válidos
            ],
        ]);


        $curso = Cursos::findOrFail($id);
        $curso->nombreCurso = $request->nombre;
        $curso->descripcionC = $request->descripcion ?? '';
        $curso->fecha_ini = date("Y-m-d", strtotime($request->fecha_ini));
        $curso->fecha_fin = date("Y-m-d", strtotime($request->fecha_fin));
        $curso->formato = $request->formato;
        $curso->docente_id = $request->docente_id;
        $curso->edadDir_id = $request->edad_id;
        $curso->niveles_id = $request->nivel_id;
        $curso->estado = ($curso->fecha_fin < now()) ? 'Finalizado' : 'Activo';
        $curso->notaAprobacion = $request->nota;

        if ($request->hasFile('archivo')) {
            // Elimina el archivo anterior si existe
            if ($curso->archivoContenidodelCurso) {
                Storage::delete('public/' . $curso->archivoContenidodelCurso);
            }

            // Guarda el nuevo archivo en el sistema de archivos
            $cursoArchivo = $request->file('archivo')->store('ArchivoCurso', 'public');
            // Actualiza el campo del archivo en el modelo Curso
            $curso->archivoContenidodelCurso = $cursoArchivo;
        } else {
            // Si no se proporciona un nuevo archivo, mantén el archivo existente
            $curso->archivoContenidodelCurso = $curso->archivoContenidodelCurso;
        }

        $curso->updated_at = now();
        event(new CursoEvent($curso ,'modificado'));
        $curso->save();

        return back()->with('success', 'El curso ha sido editado correctamente');

    }

    public function eliminarCurso($id){

        $cursos= Cursos::findOrFail($id);
        event(new CursoEvent($cursos ,'borrado'));

        $cursos->delete();

        return redirect(route('Inicio'))->with('success', 'Cursos elimnado Correctamante');
    }


    public function ReporteAsistencia($id)
{
    $asistencia = Asistencia::where('curso_id', $id)->get();

    $writer = SimpleExcelWriter::streamDownload('report.xlsx');

    $writer->addRow(['Curso', 'Nombre','Apellido Paterno','Apellido Materno', 'Fecha', 'Tipo Asistencia']);

    foreach ($asistencia as $asistencia) {
        $writer->addRow([$asistencia->cursos->nombreCurso, $asistencia->inscritos->estudiantes->name,$asistencia->inscritos->estudiantes->lastname1,$asistencia->inscritos->estudiantes->lastname2 , $asistencia->fechaasistencia,$asistencia->tipoAsitencia]);
    }



    $writer->toBrowser();


}



    public function ReporteFinal($id)
    {
        $asistencias = Asistencia::where('curso_id', $id)->get();

        $notasTareas = NotaEntrega::all(); // Filtra las notas de tareas por curso

        $notasEvaluaciones = NotaEvaluacion::all(); // Filtra las notas de evaluaciones por curso


        $inscritos = Inscritos::where('cursos_id', $id)->get();




        // Inicializa el escritor de Excel
        $writer = SimpleExcelWriter::streamDownload('report_final.xlsx');

        // Hoja de Asistencias
        $asistenciasSheet = $writer->addNewSheetAndMakeItCurrent('Asistencias');
        $asistenciasSheet->addRow(['Curso', 'Nombre', 'Apellido Paterno', 'Apellido Materno', 'Fecha', 'Tipo Asistencia']);

        foreach ($asistencias as $asistencia) {

            $asistenciasSheet->addRow([
                $asistencia->cursos->nombreCurso,
                $asistencia->inscritos->estudiantes->name,
                $asistencia->inscritos->estudiantes->lastname1,
                $asistencia->inscritos->estudiantes->lastname2,
                $asistencia->fechaasistencia,
                $asistencia->tipoAsitencia
            ]);

        }

        // Hoja de Tareas
        $tareasSheet = $writer->addNewSheetAndMakeItCurrent('Tareas');
        $tareasSheet->addRow(['Nombre Tarea', 'Nombre', 'Apellido Paterno', 'Apellido Materno', 'NOTA', 'RETROALIMENTACION']);

        foreach ($notasTareas as $notaTarea) {
            if($notaTarea->tarea->cursos_id == $id){
                $tareasSheet->addRow([
                    // $notaTarea->tarea->titulo_tarea,
                    $notaTarea->tarea->titulo_tarea,
                    $notaTarea->inscripcion->estudiantes->name,
                    $notaTarea->inscripcion->estudiantes->lastname1,
                    $notaTarea->inscripcion->estudiantes->lastname2,
                    $notaTarea->nota,
                    $notaTarea->retroalimentacion
                ]);


            }

        }

        // Hoja de Evaluaciones
        $evaluacionesSheet = $writer->addNewSheetAndMakeItCurrent('Evaluaciones');
        $evaluacionesSheet->addRow(['Nombre Evaluación',  'Nombre', 'Apellido Paterno', 'Apellido Materno', 'NOTA', 'RETROALIMENTACION']);

        foreach ($notasEvaluaciones as $notaEvaluacion) {
            if ($notaEvaluacion->evaluacion->cursos_id == $id) {
                $evaluacionesSheet->addRow([
                    $notaEvaluacion->evaluacion->titulo_evaluacion,
                    $notaEvaluacion->inscripcion->estudiantes->name,
                    $notaEvaluacion->inscripcion->estudiantes->lastname1,
                    $notaEvaluacion->inscripcion->estudiantes->lastname2,
                    $notaEvaluacion->nota,
                    $notaEvaluacion->retroalimentacion
                ]);
            }
        }


        $promfinalSheet = $writer->addNewSheetAndMakeItCurrent('PromedioFinal');
        $promfinalSheet->addRow(['Nombre', 'Apellido Paterno', 'Apellido Materno', 'NOTA PROMEDIO TAREAS', 'NOTA PROMEDIO EVALUAICIONES', 'PROMEDIO FINAL']);

        foreach ($inscritos as $inscritos) {
                $promfinalSheet->addRow([
                    $inscritos->estudiantes->name,
                    $inscritos->estudiantes->lastname1,
                    $inscritos->estudiantes->lastname2,
                    round($inscritos->notatarea->avg('nota')),
                    round($inscritos->notaevaluacion->avg('nota')),
                    round(($inscritos->notatarea->avg('nota')+$inscritos->notaevaluacion->avg('nota'))/2),
                ]);
        }





        // Descarga el archivo
        $writer->toBrowser();
    }

    public function restaurarCurso($id){

        $cursoEliminado = Cursos::onlyTrashed()->find($id);
        event(new CursoEvent($cursoEliminado ,'restaurado'));

        $cursoEliminado->restore();

        return back()->with('success', 'Curso restaurado correctamente');

    }




    public function ReporteFinalCurso($id){


        $cursos = Cursos::findorFail($id);

        $asistencias = Asistencia::where('curso_id', $id)->get();
        $tareas = Tareas::where('cursos_id', $id)->get();
        $evaluaciones = Evaluaciones::where('cursos_id', $id)->get();
        $asistencias = Asistencia::where('curso_id', $id)->get();
        $foros = Foro::where('cursos_id', $id)->get();
        $recursos = Recursos::where('cursos_id', $id)->get();

        $notasTareas = NotaEntrega::all(); // Filtra las notas de tareas por curso

        $notasEvaluaciones = NotaEvaluacion::all();


        $inscritos = Inscritos::where('cursos_id', $id)->get();



        $notasEvaluacion = NotaEvaluacion::where('inscripcion_id', $id)->get();
        $notasTareas = NotaEntrega::where('inscripcion_id', $id)->get();


        // Inicializa el contador para cada categoría
        $participanteCount = 0;
        $aprendizCount = 0;
        $habilidosoCount = 0;
        $expertoCount = 0;


        foreach ($inscritos as $inscrito) {
            $promedioNotas = ($inscrito->notatarea->avg('nota') + $inscrito->notaevaluacion->avg('nota')) / 2;

            if ($promedioNotas <= 51) {
                $participanteCount++;
            } elseif ($promedioNotas <= 65) {
                $aprendizCount++;
            } elseif ($promedioNotas <= 75) {
                $habilidosoCount++;
            } elseif ($promedioNotas <= 100) {
                $expertoCount++;
            }
        }













        // Contar la cantidad de cada tipo de asistencia

        $conteoPresentes = $asistencias->where('tipoAsitencia', 'Presente')->count();
        $conteoRetrasos = $asistencias->where('tipoAsitencia', 'Retraso')->count();
        $conteoFaltas = $asistencias->where('tipoAsitencia', 'Falta')->count();
        $conteoLicencias = $asistencias->where('tipoAsitencia', 'Licencia')->count();




        return view('Cursos.SumarioCurso', compact('conteoPresentes', 'conteoRetrasos', 'conteoFaltas', 'conteoLicencias', 'participanteCount', 'aprendizCount', 'habilidosoCount', 'expertoCount') )
                    ->with('cursos', $cursos)->with('asistencias', $asistencias)
                    ->with('inscritos', $inscritos)
                    ->with('foros', $foros)
                    ->with('recursos', $recursos)
                    ->with('tareas', $tareas)
                    ->with('evaluaciones', $evaluaciones);






    }
















}
