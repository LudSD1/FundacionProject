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
use App\Models\Subtema;
use App\Services\AdminLogger;
use App\Services\YouTubeEmbedService;
use Maatwebsite\Excel\Facades\Excel;

class CursosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */ protected $qrTokenService;
    protected $youTubeEmbedService;

    public function __construct(QrTokenService $qrTokenService, YouTubeEmbedService $youTubeEmbedService)
    {
        $this->qrTokenService = $qrTokenService;
        $this->youTubeEmbedService = $youTubeEmbedService;
    }

    public function index(Cursos $curso)
    {
        $user = Auth::user();

        // Obtener el template del certificado
        $certificado_template = CertificateTemplate::where('curso_id', $curso->id)->first();

        // Verificar si el usuario está inscrito en el curso
        $inscrito = Inscritos::where('cursos_id', $curso->id)
            ->where('estudiante_id', $user->id)
            ->first(); // Lo usamos para varios datos

        $inscritoExiste = (bool) $inscrito;
        $pago_completado = $inscrito ? $inscrito->pago_completado : 0;

        $esEstudiante = $user->hasRole('Estudiante');
        $esDocente = $user->id === $curso->docente_id;
        $esCursoNormal = $curso->tipo === 'curso';
        $pagoIncompleto = $pago_completado == 0;

        $expositores = Expositores::all();

        // Si es estudiante y no está inscrito
        if ($esEstudiante && !$inscritoExiste) {
            return redirect()->back()->with('error', 'No estás inscrito en este curso.');
        }

        // Si es estudiante y no ha completado el pago de un curso normal
        if ($esEstudiante && $esCursoNormal && $pagoIncompleto) {
            return view('LoadingPage.Loading');
        }

        // Obtener recursos, temas, foros y horarios
        $recursos = Recursos::where('cursos_id', $curso->id)->get();
        $temas = Tema::where('curso_id', $curso->id)->get();
        $foros = Foro::where('cursos_id', $curso->id)->get();

        $horariosQuery = Cursos_Horario::where('curso_id', $curso->id);
        $horarios = $user->hasRole(['Administrador', 'Docente'])
            ? $horariosQuery->withTrashed()->get()
            : $horariosQuery->get();

        // Obtener boletín (si existe inscripción)
        $boletin = $inscrito
            ? Boletin::where('inscripcion_id', $inscrito->id)->first()
            : null;

        // Generar token y QR
        $token = $this->qrTokenService->generarToken($curso->id);
        $urlInscripcion = route('inscribirse.qr', [
            'id' => $curso->id,
            'token' => $token->token,
        ]);
        $qrCode = $this->qrTokenService->generarQrCode($urlInscripcion);

        // Procesar descripciones de recursos
        foreach ($recursos as $recurso) {
            $recurso->descripcionRecursos = TextHelper::createClickableLinksAndPreviews(
                $recurso->descripcionRecursos
            );
        }

        $tiposActividades = TipoActividad::all();
        $tiposEvaluaciones = TipoEvaluacion::all();

        return view('Cursos', [
            'foros' => $foros,
            'recursos' => $recursos,
            'temas' => $temas,
            'cursos' => $curso,
            'tiposEvaluaciones' => $tiposEvaluaciones,
            'tiposActividades' => $tiposActividades,
            'inscritos' => $inscritoExiste,
            'inscritos2' => $inscrito,
            'boletin' => $boletin,
            'horarios' => $horarios,
            'qrCode' => $qrCode,
            'template' => $certificado_template,
            'expositores' => $expositores,
            'esDocente' => $esDocente,
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
        try {
            $user = auth()->user();

            // Reglas base
            $validationRules = [
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
                'formato' => 'required|in:Presencial,Virtual,Híbrido',
                'tipo' => 'required|in:curso,congreso',
                'nota' => 'nullable|numeric|min:0|max:100',
                'archivo' => 'nullable|file|mimes:pdf|max:20480',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'eliminar_archivo' => 'nullable|boolean',
                'eliminar_imagen' => 'nullable|boolean',
                'fecha_ini' => 'required|date_format:Y-m-d\TH:i',
                'fecha_fin' => 'required|date_format:Y-m-d\TH:i|after_or_equal:fecha_ini',
            ];

            // Reglas extra solo para admins
            if ($user->hasRole('Administrador')) {
                $validationRules['docente_id'] = 'required|exists:users,id';
                $validationRules['duracion'] = 'required|integer|min:1';
                $validationRules['cupos'] = 'required|integer|min:1';
                $validationRules['precio'] = 'required|numeric|min:0';
            }

            $request->validate($validationRules, [
                'nombre.required' => 'El nombre del curso es obligatorio.',
                'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
                'formato.required' => 'Debe seleccionar un formato.',
                'formato.in' => 'El formato debe ser Presencial, Virtual o Híbrido.',
                'tipo.required' => 'Debe indicar el tipo de evento.',
                'tipo.in' => 'El tipo debe ser curso o congreso.',
                'nota.numeric' => 'La nota debe ser un número.',
                'nota.min' => 'La nota mínima permitida es 0.',
                'nota.max' => 'La nota máxima permitida es 100.',
                'archivo.mimes' => 'El archivo debe ser un PDF.',
                'archivo.max' => 'El archivo no puede superar los 2MB.',
                'imagen.image' => 'El archivo debe ser una imagen.',
                'imagen.mimes' => 'La imagen debe ser de tipo jpeg, png, jpg o gif.',
                'imagen.max' => 'La imagen no puede superar los 2MB.',
                'fecha_ini.required' => 'La fecha de inicio es obligatoria.',
                'fecha_ini.date_format' => 'La fecha de inicio debe tener el formato válido.',
                'fecha_fin.required' => 'La fecha de fin es obligatoria.',
                'fecha_fin.date_format' => 'La fecha de fin debe tener el formato válido.',
                'fecha_fin.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
            ]);


            $curso = Cursos::findOrFail($id);

            // Datos básicos
            $curso->nombreCurso = $request->nombre;
            $curso->descripcionC = $request->descripcion ?? null;
            $curso->formato = $request->formato;
            $curso->tipo = $request->tipo;
            $curso->notaAprobacion = $request->nota ?? null;
            $curso->edad_dirigida = $request->edad_id ?? null;
            $curso->nivel = $request->nivel_id ?? null;

            // Manejo de archivo PDF
            if ($request->hasFile('archivo')) {
                if ($curso->archivoContenidodelCurso) {
                    Storage::delete('public/' . $curso->archivoContenidodelCurso);
                }
                $curso->archivoContenidodelCurso = $request->file('archivo')->store('cursos/pdf', 'public');
            } elseif ($request->boolean('eliminar_archivo')) {
                Storage::delete('public/' . $curso->archivoContenidodelCurso);
                $curso->archivoContenidodelCurso = null;
            }

            // Manejo de la imagen
            if ($request->hasFile('imagen')) {
                if ($curso->imagen) {
                    Storage::delete('public/' . $curso->imagen);
                }
                $curso->imagen = $request->file('imagen')->store('cursos/imagenes', 'public');
            } elseif ($request->boolean('eliminar_imagen')) {
                Storage::delete('public/' . $curso->imagen);
                $curso->imagen = null;
            }

            // Solo admin puede modificar fechas y demás
            if ($user->hasRole('Administrador')) {
                $curso->fecha_ini = Carbon::createFromFormat('Y-m-d\TH:i', $request->fecha_ini)->format('Y-m-d H:i:s');
                $curso->fecha_fin = Carbon::createFromFormat('Y-m-d\TH:i', $request->fecha_fin)->format('Y-m-d H:i:s');
                $curso->estado = Carbon::parse($request->fecha_fin)->isFuture() ? 'Activo' : 'Expirado';

                $curso->docente_id = $request->docente_id;
                $curso->duracion = $request->duracion;
                $curso->cupos = $request->cupos;
                $curso->precio = $request->precio;
                $curso->visibilidad = $request->visibilidad;
            }

            $curso->save();

            // Log de auditoría
            AdminLogger::info('Curso actualizado', [
                'curso_id'   => $curso->id,
                'curso_nombre' => $curso->nombreCurso,
                'editado_por'  => $user->id,
                'rol_usuario'  => $user->getRoleNames()->first(),
            ]);

            return back()->with('success', 'Curso actualizado correctamente');
        } catch (\Exception $e) {
            AdminLogger::error('Error al editar curso', $e);
            return back()->withErrors(['info' => 'Ocurrió un error al actualizar el curso.']);
        }
    }


    public function updateCategories(Request $request, $id) // o Curso $curso si usas route model binding
    {
        try {
            $curso = Cursos::findOrFail($id);

            // // Validar que categorías sean un array de enteros existentes en la tabla categorias
            // $request->validate([
            //     'categorias' => 'nullable|array',
            //     'categorias.*' => 'exists:categorias,id'
            // ], [
            //     'categorias.array' => 'El formato de categorías no es válido.',
            //     'categorias.*.exists' => 'Alguna de las categorías seleccionadas no existe.'
            // ]);

            // Actualizar categorías
            $curso->categorias()->sync($request->categorias ?? []);

            // Registrar en logs de administración
            AdminLogger::info('Categorías de curso actualizadas', [
                'curso_id'   => $curso->id,
                'curso_nombre' => $curso->nombreCurso,
                'categorias_asignadas' => $request->categorias ?? [],
                'admin_id' => auth()->id(),
            ]);

            return back()->with('success', 'Categorías actualizadas correctamente');
        } catch (\Exception $e) {
            AdminLogger::error('Error al actualizar categorías de curso', $e);

            return back()->withErrors(['error' => 'No se pudo actualizar las categorías.']);
        }
    }


    public function eliminarCurso($id)
    {
        try {
            $curso = Cursos::findOrFail($id);

            // Lanzar evento
            event(new CursoEvent($curso, 'borrado'));

            // Soft delete (si usas SoftDeletes)
            $curso->delete();

            // Registrar en log de administración
            AdminLogger::info('Curso eliminado', [
                'curso_id'   => $curso->id,
                'curso_nombre' => $curso->nombreCurso,
                'eliminado_por' => auth()->id(),
            ]);

            return redirect(route('Inicio'))->with('success', 'Curso eliminado correctamente');
        } catch (\Exception $e) {
            AdminLogger::error('Error al eliminar curso', $e);
            return back()->withErrors(['error' => 'No se pudo eliminar el curso.']);
        }
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
        try {
            $cursoEliminado = Cursos::onlyTrashed()->findOrFail($id);

            // Restaurar el curso
            $cursoEliminado->restore();

            // Lanzar evento (si lo usas para notificaciones, etc.)
            event(new CursoEvent($cursoEliminado, 'restaurado'));

            // Registrar en logs de administración
            AdminLogger::info('Curso restaurado', [
                'curso_id'   => $cursoEliminado->id,
                'curso_nombre' => $cursoEliminado->nombreCurso,
            ]);

            return back()->with('success', 'Curso restaurado correctamente');
        } catch (\Exception $e) {
            AdminLogger::error('Error al restaurar curso', $e);

            return back()->withErrors(['error' => 'No se pudo restaurar el curso.']);
        }
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

        $embedUrl = $this->youTubeEmbedService->obtenerUrlEmbed($request->youtube_url);

        $curso->update([
            'youtube_url' => $embedUrl ?? null,
        ]);



        return back()->with('success', 'Enlace de YouTube actualizado correctamente.');
    }


    public function elementosEliminados($id)
    {
        $curso = Cursos::findOrFail($id);

        // Obtener temas eliminados del curso
        $temasEliminados = Tema::onlyTrashed()
            ->where('curso_id', $id)
            ->get();

        // Obtener IDs de temas (incluyendo los no eliminados) para buscar subtemas
        $todosTemasIds = Tema::where('curso_id', $id)->withTrashed()->pluck('id');

        // Obtener subtemas eliminados relacionados con el curso
        $subtemasEliminados = Subtema::onlyTrashed()
            ->whereIn('tema_id', $todosTemasIds)
            ->get();

        // Obtener IDs de subtemas (incluyendo los no eliminados) para buscar actividades
        $todosSubtemasIds = Subtema::whereIn('tema_id', $todosTemasIds)->withTrashed()->pluck('id');

        // Obtener actividades eliminadas relacionadas con el curso
        $actividadesEliminadas = Actividad::onlyTrashed()
            ->whereIn('subtema_id', $todosSubtemasIds)
            ->get();

        // Obtener foros eliminados del curso
        $forosEliminados = Foro::onlyTrashed()
            ->where('cursos_id', $id)
            ->get();

        // Obtener recursos eliminados del curso
        $recursosEliminados = Recursos::onlyTrashed()
            ->where('cursos_id', $id)
            ->get();

        // Contar inscritos en el curso para comparación
        $cantidadInscritos = Inscritos::where('cursos_id', $id)->count();

        return view('Cursos.elementos_eliminados', compact(
            'curso',
            'temasEliminados',
            'subtemasEliminados',
            'actividadesEliminadas',
            'forosEliminados',
            'recursosEliminados',
            'cantidadInscritos'
        ));
    }

    public function restaurarElemento(Request $request)
    {
        $tipo = $request->tipo;
        $id = $request->id;
        $cursoId = $request->curso_id;

        try {
            switch ($tipo) {
                case 'tema':
                    $elemento = Tema::onlyTrashed()->findOrFail($id);
                    break;
                case 'subtema':
                    $elemento = Subtema::onlyTrashed()->findOrFail($id);
                    break;
                case 'actividad':
                    $elemento = Actividad::onlyTrashed()->findOrFail($id);
                    break;
                case 'foro':
                    $elemento = Foro::onlyTrashed()->findOrFail($id);
                    break;
                case 'recurso':
                    $elemento = Recursos::onlyTrashed()->findOrFail($id);
                    break;
                default:
                    return redirect()->back()->with('error', 'Tipo de elemento no válido');
            }

            // Restaurar el elemento
            $elemento->restore();

            return redirect()->back()->with('success', 'Elemento restaurado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al restaurar: ' . $e->getMessage());
        }
    }
}
