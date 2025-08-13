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
use App\Models\Temas;
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
use App\Models\Categoria;
use App\Models\CertificateTemplate;
use App\Models\Expositores;
use App\Models\Tema;
use App\Models\TipoActividad;
use App\Models\TipoEvaluacion;
use App\Services\QrTokenService;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Actividad;
use App\Exports\CursoReporteExport;
use Maatwebsite\Excel\Facades\Excel;

class CursosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */ protected $qrTokenService;

    public function __construct(QrTokenService $qrTokenService)
    {
        $this->qrTokenService = $qrTokenService;
    }

    public function index($id)
    {
        // Obtener el curso
        $cursos = Cursos::findOrFail($id);

        // Obtener el template del certificado
        $certificado_template = CertificateTemplate::where('curso_id', $id)->first();

        // Verificar si el usuario está inscrito en el curso
        $inscritos = Inscritos::where('cursos_id', $id)
            ->where('estudiante_id', auth()->user()->id)
            ->exists();  // Esto devuelve un booleano

        // Verificar si el pago está completado
        $pago_completado = Inscritos::where('cursos_id', $id)
            ->where('estudiante_id', auth()->user()->id)
            ->pluck('pago_completado')->first(); // Esto devuelve un solo valor

        $user = Auth::user();
        $esEstudiante = $user->hasRole('Estudiante');
        $esDocente = $user->id == $cursos->docente_id;
        $pagoIncompleto = $pago_completado == 0; // Aquí comparamos con 0 si no ha completado el pago
        $esCursoNormal = $cursos->tipo == 'curso';

        $expositores = Expositores::all();

        // Si es estudiante y no está inscrito
        if (!$inscritos && $esEstudiante) {
            return redirect()->back()->with('error', 'No estás inscrito en este curso.');
        }

        // Si es estudiante y no ha completado el pago de un curso normal
        elseif ($esEstudiante && $esCursoNormal && $pagoIncompleto) {
            return view('LoadingPage.Loading');
        }



        // Obtener recursos, temas, evaluaciones, foros y horarios
        $recursos = Recursos::where('cursos_id', $id)->get();
        $temas = Tema::where('curso_id', $id)->get();
        $foros = Foro::where('cursos_id', $id)->get();

        $horarios = Auth::user()->hasRole(['Administrador', 'Docente'])
            ? Cursos_Horario::where('curso_id', $id)->withTrashed()->get()
            : Cursos_Horario::where('curso_id', $id)->get();

        // Obtener el boletín del usuario
        $inscritos2 = Inscritos::where('cursos_id', $id)
            ->where('estudiante_id', auth()->user()->id)
            ->first();


        $boletin = $inscritos2 ? Boletin::where('inscripcion_id', $inscritos2->id)->first() : null;

        // Generar el token y el código QR
        $token = $this->qrTokenService->generarToken($id);
        $urlInscripcion = route('inscribirse.qr', ['id' => $id, 'token' => $token->token]);
        $qrCode = $this->qrTokenService->generarQrCode($urlInscripcion);

        // Procesar descripciones de recursos
        foreach ($recursos as $recurso) {
            $recurso->descripcionRecursos = TextHelper::createClickableLinksAndPreviews($recurso->descripcionRecursos);
        }

        $tiposActividades = TipoActividad::all();
        $tiposEvaluaciones = TipoEvaluacion::all();


        return view('Cursos', [
            'foros' => $foros,
            'recursos' => $recursos,
            'temas' => $temas,
            'cursos' => $cursos,
            'tiposEvaluaciones' => $tiposEvaluaciones,
            'tiposActividades' => $tiposActividades,
            'inscritos' => $inscritos,
            'inscritos2' => $inscritos2,
            'boletin' => $boletin,
            'horarios' => $horarios,
            'qrCode' => $qrCode,
            'template' => $certificado_template,
            'expositores' => $expositores,
            'esDocente' =>  $esDocente,
        ]);
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
        $horarios = Cursos_Horario::where('curso_id', $id)->get();
        return view('Estudiante.listadeestudiantes')->with('inscritos', $inscritos)->with('curso', $curso)->with('horarios', $horarios);
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

        $cursos = Cursos::findOrFail($id);
        $docente = User::role('Docente')->get();
        $horario = Horario::all();
        $categorias = Categoria::all();

        return view('Administrador.EditarCursos')->with('docente', $docente)->with('horario', $horario)->with('cursos', $cursos)->with('categorias', $categorias);
    }
    public function EditC($id, Request $request)
    {
        $user = auth()->user();

        // Reglas base que aplican a todos
        $validationRules = [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'formato' => 'required|in:Presencial,Virtual,Híbrido',
            'tipo' => 'required|in:curso,congreso',
            'nota' => 'nullable|numeric|min:0|max:100',
            'archivo' => 'nullable|file|mimes:pdf|max:20480', // PDF hasta 20MB
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Imagen hasta 5MB
            'eliminar_archivo' => 'nullable|boolean',
            'eliminar_imagen' => 'nullable|boolean'
        ];




        // Solo admin puede cambiar fechas y docente
        if ($user->hasRole('Administrador')) {
            $validationRules['fecha_ini'] = 'required|date_format:Y-m-d\TH:i';
            $validationRules['fecha_fin'] = 'required|date_format:Y-m-d\TH:i|after_or_equal:fecha_ini';
            $validationRules['docente_id'] = 'required|exists:users,id';
            $validationRules['duracion'] = 'required|integer|min:1';
            $validationRules['cupos'] = 'required|integer|min:1';
            $validationRules['precio'] = 'required|numeric|min:0';
        }

        $request->validate($validationRules);

        $curso = Cursos::findOrFail($id);

        // Campos básicos para todos
        $curso->nombreCurso = $request->nombre;
        $curso->descripcionC = $request->descripcion ?? null;
        $curso->formato = $request->formato;
        $curso->tipo = $request->tipo;
        $curso->notaAprobacion = $request->nota ?? null;
        $curso->edad_dirigida = $request->edad_id ?? null;
        $curso->nivel = $request->nivel_id ?? null;

        if ($request->hasFile('archivo')) {
            // Eliminar archivo anterior si existe
            if ($curso->archivoContenidodelCurso) {
                Storage::delete('public/' . $curso->archivoContenidodelCurso);
            }
            $curso->archivoContenidodelCurso = $request->file('archivo')->store('cursos/pdf', 'public');
        } elseif ($request->has('eliminar_archivo')) {
            // Eliminar archivo si se marcó el checkbox
            Storage::delete('public/' . $curso->archivoContenidodelCurso);
            $curso->archivoContenidodelCurso = null;
        }

        // Manejo de la imagen
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($curso->imagen) {
                Storage::delete('public/' . $curso->imagen);
            }
            $curso->imagen = $request->file('imagen')->store('cursos/imagenes', 'public');
        } elseif ($request->has('eliminar_imagen')) {
            // Eliminar imagen si se marcó el checkbox
            Storage::delete('public/' . $curso->imagen);
            $curso->imagen = null;
        }
        $curso->fecha_ini = Carbon::createFromFormat('Y-m-d\TH:i', $request->fecha_ini)->format('Y-m-d H:i:s');
        $curso->fecha_fin = Carbon::createFromFormat('Y-m-d\TH:i', $request->fecha_fin)->format('Y-m-d H:i:s');
        $curso->estado = Carbon::parse($request->fecha_fin)->isFuture() ? 'Activo' : 'Expirado';





        // Solo admin actualiza estos campos
        if ($user->hasRole('Administrador')) {
            $curso->docente_id = $request->docente_id;
            $curso->duracion = $request->duracion;
            $curso->cupos = $request->cupos;
            $curso->precio = $request->precio;
            $curso->visibilidad = $request->visibilidad;
        }


        $curso->save();

        return back()->with('success', 'Curso actualizado correctamente');
    }

    public function updateCategories(Request $request, $id) // o Curso $curso si usas route model binding
    {
        $curso = Cursos::findOrFail($id);
        $curso->categorias()->sync($request->categorias ?? []);

        return back()->with('success', 'Curso actualizado correctamente');
    }


    public function eliminarCurso($id)
    {

        $cursos = Cursos::findOrFail($id);
        event(new CursoEvent($cursos, 'borrado'));

        $cursos->delete();

        return redirect(route('Inicio'))->with('success', 'Cursos elimnado Correctamante');
    }


    public function ReporteAsistencia($id)
    {
        $asistencias = Asistencia::where('curso_id', $id)->get();

        $writer = SimpleExcelWriter::streamDownload('report.xlsx');

        $writer->addRow(['Curso', 'Nombre', 'Apellido Paterno', 'Apellido Materno', 'Fecha', 'Tipo Asistencia']);

        foreach ($asistencias as $asistencia) {
            $writer->addRow([$asistencia->cursos->nombreCurso, $asistencia->inscritos->estudiantes->name, $asistencia->inscritos->estudiantes->lastname1, $asistencia->inscritos->estudiantes->lastname2, $asistencia->fechaasistencia, $asistencia->tipoAsitencia]);
        }



        $writer->toBrowser();
    }


    public function ReporteFinal($id)
    {
        try {
            // Obtener inscritos con sus asistencias
            $inscritos = Inscritos::where('cursos_id', $id)
                ->with(['estudiantes', 'asistencia', 'cursos'])
                ->get();

            if ($inscritos->isEmpty()) {
                return back()->with('error', 'No hay inscritos en este curso.');
            }

            // Obtener actividades del curso
            $actividades = Actividad::whereHas('subtema.tema', function ($query) use ($id) {
                $query->where('curso_id', $id);
            })->with([
                'subtema.tema',
                'tipoActividad',
                'calificacionesEntregas',
                'cuestionario.intentos',
                'intentosCuestionarios'
            ])->get();

            // Crear el nombre del archivo
            $nombreArchivo = 'reporte_curso_' . $id . '_' . date('Y-m-d_H-i-s') . '.xlsx';

            // Crear y descargar el archivo Excel
            return Excel::download(new CursoReporteExport($inscritos, $actividades), $nombreArchivo);
        } catch (\Exception $e) {
            \Log::error("Error generando reporte: " . $e->getMessage());
            return back()->with('error', 'Error generando el reporte: ' . $e->getMessage());
        }
    }



    public function restaurarCurso($id)
    {

        $cursoEliminado = Cursos::onlyTrashed()->find($id);
        event(new CursoEvent($cursoEliminado, 'restaurado'));

        $cursoEliminado->restore();

        return back()->with('success', 'Curso restaurado correctamente');
    }

    public function ReporteFinalCurso($id)
    {
        $cursos = Cursos::findorFail($id);

        // Obtener asistencias una sola vez
        $asistencias = Asistencia::where('curso_id', $id)->get();

        $temas = Tema::where('curso_id', $id)
            ->with(['subtemas.actividades.calificacionesEntregas', 'subtemas.actividades.cuestionarios'])
            ->get();

        $foros = Foro::where('cursos_id', $id)->get();
        $recursos = Recursos::where('cursos_id', $id)->get();

        // Obtener notas filtradas por curso
        $notasEntregas = NotaEntrega::whereHas('actividad.subtema.tema', function ($query) use ($id) {
            $query->where('curso_id', $id);
        })->get();

        // Obtener notas de cuestionarios
        $notasCuestionarios = DB::table('intentos_cuestionarios')
            ->join('cuestionarios', 'intentos_cuestionarios.cuestionario_id', '=', 'cuestionarios.id')
            ->join('actividades', 'cuestionarios.actividad_id', '=', 'actividades.id')
            ->join('subtemas', 'actividades.subtema_id', '=', 'subtemas.id')
            ->join('temas', 'subtemas.tema_id', '=', 'temas.id')
            ->where('temas.curso_id', $id)
            ->select('intentos_cuestionarios.*')
            ->get();

        $inscritos = Inscritos::where('cursos_id', $id)->get();

        // Inicializa el contador para cada categoría
        $participanteCount = 0;
        $aprendizCount = 0;
        $habilidosoCount = 0;
        $expertoCount = 0;

        foreach ($inscritos as $inscrito) {
            $notasInscrito = $notasEntregas->where('inscripcion_id', $inscrito->id);
            $cuestionariosInscrito = $notasCuestionarios->where('inscripcion_id', $inscrito->id);

            // Calcular promedio combinado de actividades y cuestionarios
            $sumaNotas = 0;
            $cantidadNotas = 0;

            // Sumar notas de actividades
            if ($notasInscrito->count() > 0) {
                $sumaNotas += $notasInscrito->sum('nota');
                $cantidadNotas += $notasInscrito->count();
            }

            // Sumar notas de cuestionarios
            if ($cuestionariosInscrito->count() > 0) {
                $sumaNotas += $cuestionariosInscrito->sum('calificacion');
                $cantidadNotas += $cuestionariosInscrito->count();
            }

            // Calcular promedio final
            $promedioFinal = $cantidadNotas > 0 ? $sumaNotas / $cantidadNotas : 0;

            // Clasificar según el promedio final
            if ($promedioFinal >= 90) {
                $expertoCount++;
            } elseif ($promedioFinal >= 75) {
                $habilidosoCount++;
            } elseif ($promedioFinal >= 60) {
                $aprendizCount++;
            } else {
                $participanteCount++;
            }
        }

        // Contar la cantidad de cada tipo de asistencia
        $conteoPresentes = $asistencias->where('tipoAsitencia', 'Presente')->count();
        $conteoRetrasos = $asistencias->where('tipoAsitencia', 'Retraso')->count();
        $conteoFaltas = $asistencias->where('tipoAsitencia', 'Falta')->count();
        $conteoLicencias = $asistencias->where('tipoAsitencia', 'Licencia')->count();

        return view('Cursos.SumarioCurso', compact(
            'conteoPresentes',
            'conteoRetrasos',
            'conteoFaltas',
            'conteoLicencias',
            'participanteCount',
            'aprendizCount',
            'habilidosoCount',
            'expertoCount',
            'cursos',
            'asistencias',
            'inscritos',
            'foros',
            'recursos',
            'temas',
            'notasEntregas',
            'notasCuestionarios'
        ));
    }

    public function activarCertificados($id)
    {
        $curso = Cursos::findOrFail($id);

        // Verificar que la fecha de fin no haya pasado
        if (now()->greaterThan(Carbon::parse($curso->fecha_fin)->endOfDay())) {
            return back()->with('error', 'El periodo para activar certificados ha expirado.');
        }

        // Marcar certificados como activados
        $curso->certificados_activados = true;
        $curso->save();

        return back()->with('success', 'Certificados activados correctamente.');
    }

    public function updateYoutube(Request $request, Cursos $curso)
    {
        $request->validate([
            'youtube_url' => 'nullable|url|max:255',
        ]);

        $curso->update([
            'youtube_url' => $request->youtube_url,
        ]);

        return back()->with('success', 'Enlace de YouTube actualizado correctamente.');
    }
}
