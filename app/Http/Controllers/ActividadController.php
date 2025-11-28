<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\ActividadCompletion;
use App\Models\EntregaArchivo;
use App\Models\IntentoCuestionario;
use App\Models\Cuestionario;
use App\Models\Inscritos;
use App\Models\NotaEntrega;
use App\Traits\CalificacionTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\XPService;
use App\Services\AchievementService;
use Carbon\Carbon;

class ActividadController extends Controller
{
    use CalificacionTrait;

    protected $xpService;
    protected $achievementService;

    public function __construct(XPService $xpService, AchievementService $achievementService)
    {
        $this->xpService = $xpService;
        $this->achievementService = $achievementService;
    }

    public function index($id)
    {
        $actividades = Actividad::with(['entregas'])->findOrFail($id);
        $notas = NotaEntrega::where('actividad_id', $id)->get();
        $inscritos = Inscritos::all();



        return view('Estudiante.Actividad',)->with('actividades', $actividades)
            ->with('inscritos', $inscritos)
            ->with('notas', $notas);
    }


    public function subirArchivo(Request $request, $id)
    {
        $request->validate([
            'actividad_id' => 'required|integer',
            'user_id' => 'required|integer',
            'archivo' => 'required|file|max:2048',
            'comentario' => 'nullable|string|max:1000',
        ]);

        $archivo = $request->file('archivo')->store('entregas', 'public');
        $actividad = Actividad::findOrFail($request->actividad_id);
        $inscrito = Inscritos::where('estudiante_id', $request->user_id)
            ->where('cursos_id', $actividad->subtema->tema->curso->id)
            ->firstOrFail();

        EntregaArchivo::create([
            'actividad_id' => $request->actividad_id,
            'user_id' => $request->user_id,
            'archivo' => $archivo,
            'comentario' => $request->comentario,
            'fecha_entrega' => now(),
        ]);

        // Otorgar XP por entrega
        $baseXP = 30;
        $bonusEntregaTemprana = 0;

        // Bonus por entrega temprana
        if ($actividad->fecha_limite && now() < $actividad->fecha_limite) {
            $diasAntes = now()->diffInDays($actividad->fecha_limite);
            $bonusEntregaTemprana = min($diasAntes * 5, 20); // Máximo 20 XP extra

            // Verificar logro de entregas tempranas
            $earlySubmissions = EntregaArchivo::where('user_id', $request->user_id)
                ->whereHas('actividad', function ($q) {
                    $q->whereNotNull('fecha_limite')
                        ->whereRaw('fecha_entrega < DATE_SUB(fecha_limite, INTERVAL 1 DAY)');
                })->count();

            $this->achievementService->checkAndAwardAchievements($inscrito, 'EARLY_BIRD', $earlySubmissions);
        }

        // Verificar actividad nocturna
        if (now()->hour >= 0 && now()->hour < 4) {
            $nightActivities = ActividadCompletion::where('inscrito_id', $inscrito->id)
                ->whereTime('created_at', '>=', '00:00:00')
                ->whereTime('created_at', '<', '04:00:00')
                ->count();

            $this->achievementService->checkAndAwardAchievements($inscrito, 'NIGHT_OWL', $nightActivities);
        }

        // Verificar múltiples actividades en un día
        $actividadesHoy = ActividadCompletion::where('inscrito_id', $inscrito->id)
            ->whereDate('created_at', now()->toDateString())
            ->count();

        if ($actividadesHoy >= 5) {
            $this->achievementService->checkAndAwardAchievements($inscrito, 'DAILY_ACTIVITIES', $actividadesHoy);
        }

        $totalXP = $baseXP + $bonusEntregaTemprana;
        $this->xpService->addXP($inscrito, $totalXP, "Entrega de actividad - {$actividad->titulo}");

        return back()->with('success', 'Tarea enviada correctamente.');
    }





    public function completarActividad(Request $request, $actividadId)
    {
        $request->validate([
            'inscritos_id' => 'required|exists:inscritos,id',
        ]);

        $actividad = Actividad::with(['subtema.tema.curso', 'tipoActividad', 'cuestionario'])->findOrFail($actividadId);
        $inscritoId = $request->inscritos_id;

        // Log para debugging
        \Log::info('Intentando completar actividad', [
            'actividad_id' => $actividadId,
            'inscrito_id' => $inscritoId,
            'tipo_actividad' => $actividad->tipoActividad->nombre ?? 'N/A',
            'tipo_curso' => $actividad->subtema->tema->curso->tipo ?? 'N/A',
            'tiene_cuestionario' => $actividad->cuestionario ? 'Si' : 'No'
        ]);

        // Verificar si ya está completada
        if ($actividad->isCompletedByInscrito($inscritoId)) {
            return back()->with('info', 'Esta actividad ya está marcada como completada.');
        }

        // Verificar requisitos según el tipo de curso
        $tipoCurso = $actividad->subtema->tema->curso->tipo;

        // Para congresos, permitir marcar como completado directamente
        if ($tipoCurso === 'congreso') {
            \Log::info('Actividad de congreso - marcando como completada directamente');
            $this->marcarActividadCompletada($actividad, $inscritoId);
            return back()->with('success', 'Actividad marcada como completada.');
        }

        // Verificar qué tiene la actividad: cuestionario, calificación, o ambos
        $tieneCuestionario = $actividad->cuestionario !== null;
        $tieneCalificacion = NotaEntrega::where('inscripcion_id', $inscritoId)
            ->where('actividad_id', $actividad->id)
            ->exists();

        \Log::info('Estado de la actividad', [
            'tiene_cuestionario_creado' => $tieneCuestionario,
            'tiene_calificacion' => $tieneCalificacion
        ]);

        // Si tiene cuestionario, verificar que esté completado
        if ($tieneCuestionario) {
            $cuestionarioCompletado = DB::table('intentos_cuestionarios')
                ->join('cuestionarios', 'intentos_cuestionarios.cuestionario_id', '=', 'cuestionarios.id')
                ->where('cuestionarios.actividad_id', $actividad->id)
                ->where('intentos_cuestionarios.inscrito_id', $inscritoId)
                ->whereNotNull('intentos_cuestionarios.finalizado_en')
                ->exists();

            \Log::info('Verificación de cuestionario', [
                'cuestionario_completado' => $cuestionarioCompletado
            ]);

            if (!$cuestionarioCompletado) {
                return back()->with('error', 'Debes completar el cuestionario antes de marcar esta actividad como completada.');
            }

            // Si tiene cuestionario Y está completado, marcar como completada
            $this->marcarActividadCompletada($actividad, $inscritoId);
            \Log::info('Actividad con cuestionario marcada como completada');
            return back()->with('success', 'Actividad marcada como completada.');
        }

        // Si NO tiene cuestionario, verificar que tenga calificación
        if (!$tieneCuestionario) {
            // Obtener TODAS las calificaciones para esta actividad (para debugging)
            $todasLasNotas = NotaEntrega::where('actividad_id', $actividad->id)->get();

            // Buscar la calificación específica del inscrito
            $notaEntrega = NotaEntrega::where('inscripcion_id', $inscritoId)
                ->where('actividad_id', $actividad->id)
                ->first();

            \Log::info('Verificación de calificación - DETALLADO', [
                'tiene_calificacion' => $notaEntrega ? true : false,
                'inscrito_id_buscado' => $inscritoId,
                'actividad_id' => $actividad->id,
                'nota_encontrada' => $notaEntrega ? $notaEntrega->toArray() : null,
                'total_notas_actividad' => $todasLasNotas->count(),
                'todas_las_notas_actividad' => $todasLasNotas->map(function ($nota) {
                    return [
                        'id' => $nota->id,
                        'inscripcion_id' => $nota->inscripcion_id,
                        'actividad_id' => $nota->actividad_id,
                        'nota' => $nota->nota
                    ];
                })->toArray(),
                'total_notas_inscrito' => NotaEntrega::where('inscripcion_id', $inscritoId)->count()
            ]);

            if (!$notaEntrega) {
                return back()->with('error', 'Esta actividad debe ser calificada por el docente antes de marcarla como completada. (Inscrito ID: ' . $inscritoId . ', Actividad ID: ' . $actividad->id . ')');
            }

            // Si tiene calificación, marcar como completada
            $this->marcarActividadCompletada($actividad, $inscritoId);
            \Log::info('Actividad con calificación marcada como completada');
            return back()->with('success', 'Actividad marcada como completada.');
        }

        // Si llegamos aquí, algo salió mal
        \Log::error('No se pudo determinar cómo completar la actividad', [
            'actividad_id' => $actividad->id,
            'inscrito_id' => $inscritoId
        ]);

        return back()->with('error', 'No se pudo completar la actividad. Contacta al administrador.');
    }




    public function ocultar($id)
    {
        $actividad = Actividad::findOrFail($id);
        $actividad->update(['es_publica' => false]);

        return redirect()->back()->with('success', 'La actividad ha sido ocultada.');
    }

    public function mostrar($id)
    {
        $actividad = Actividad::findOrFail($id);
        $actividad->update(['es_publica' => true]);

        return redirect()->back()->with('success', 'La actividad ahora es visible.');
    }



    public function listadeEntregas($id)
    {

        $actividad = Actividad::findOrFail($id);
        $entregas = EntregaArchivo::where('actividad_id', $id)->get();
        $inscritos = Inscritos::where('cursos_id', $actividad->subtema->tema->curso->id)->get();
        $nota = NotaEntrega::where('actividad_id', $id)->get();

        $vencido = ($actividad->subtema->tema->curso->fecha_fin && Carbon::parse($actividad->subtema->tema->curso->fecha_fin)->isPast()) ||
            ($actividad->fecha_limite && Carbon::parse($actividad->fecha_limite)->isPast());


        return view('Docente.ListadeEntregas')
            ->with('inscritos', $inscritos)
            ->with('nota', $nota)
            ->with('actividad', $actividad)
            ->with('vencido', $vencido)
            ->with('entregas', $entregas);
    }

    public function listadeEntregasCalificar(Request $request, $id)
    {
        $request->validate([
            'entregas.*.notaTarea' => 'required|numeric|min:0|max:100',
            'entregas.*.retroalimentacion' => 'nullable|string|max:1000',
            'entregas.*.id_inscripcion' => 'required|integer',
        ]);

        $actividad = Actividad::findOrFail($id);
        $calificar = $request->input('entregas');

        DB::transaction(function () use ($calificar, $id, $actividad) {
            foreach ($calificar as $calificarItem) {
                if (!empty($calificarItem['id'])) {
                    $nota = NotaEntrega::findOrFail($calificarItem['id']);
                    $nota->nota = $calificarItem['notaTarea'];
                    $nota->retroalimentacion = $calificarItem['retroalimentacion'] ?? null;
                    $nota->save();
                } else {
                    $nota = NotaEntrega::create([
                        'nota' => $calificarItem['notaTarea'],
                        'retroalimentacion' => $calificarItem['retroalimentacion'] ?? null,
                        'actividad_id' => $id,
                        'inscripcion_id' => $calificarItem['id_inscripcion'],
                    ]);
                }

                // Otorgar XP basado en la calificación
                $inscrito = Inscritos::findOrFail($calificarItem['id_inscripcion']);
                $baseXP = 20;
                $bonusCalificacion = round($calificarItem['notaTarea'] / 2); // Máximo 50 XP por 100%

                $this->xpService->addXP(
                    $inscrito,
                    $baseXP + $bonusCalificacion,
                    "Calificación de actividad - Nota: {$calificarItem['notaTarea']}/100"
                );

                // Marcar como completada si tiene calificación
                if ($this->verificarCalificacionActividad($actividad, $calificarItem['id_inscripcion'])) {
                    $this->marcarActividadCompletada($actividad, $calificarItem['id_inscripcion']);
                }
            }
        });

        return back()->with('success', 'Calificaciones y retroalimentaciones guardadas correctamente.');
    }


    public function store(Request $request, $cursoId)
    {


        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'nullable|date',
            'fecha_limite' => 'nullable|date',
            'orden' => 'nullable|integer',
            'es_publica' => 'nullable|boolean',
            'es_obligatoria' => 'nullable|boolean',
            'subtema_id' => 'nullable|exists:subtemas,id', // Validar que el subtema exista
            'tipo_actividad_id' => 'required|exists:tipo_actividades,id', // Validar que el tipo de actividad exista
            'tipos_evaluacion' => 'required|array', // Validar que sea un array
            'tipos_evaluacion.*.tipo_evaluacion_id' => 'required|exists:tipo_evaluaciones,id', // Validar cada tipo de evaluación
            'tipos_evaluacion.*.puntaje_maximo' => 'required|integer|min:0', // Validar puntaje máximo
            'tipos_evaluacion.*.es_obligatorio' => 'required|boolean', // Validar si es obligatorio
        ]);


        $ordenMaximo = Actividad::where('subtema_id', $data['subtema_id'] ?? null)->max('orden');
        $data['orden'] = is_null($ordenMaximo) ? 1 : $ordenMaximo + 1;


        $actividad = Actividad::create($data);

        foreach ($data['tipos_evaluacion'] as $tipoEvaluacion) {
            DB::table('actividad_tipos_evaluacion')->insert([
                'actividad_id' => $actividad->id,
                'tipo_evaluacion_id' => $tipoEvaluacion['tipo_evaluacion_id'],
                'puntaje_maximo' => $tipoEvaluacion['puntaje_maximo'],
                'es_obligatorio' => $tipoEvaluacion['es_obligatorio'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }




        return redirect()->route('Curso', encrypt($cursoId))
            ->with('success', 'Actividad creada exitosamente.');
    }



    public function update(Request $request, $encryptedId)
    {
        // ✅ Desencriptar el ID
        try {
            $id = $encryptedId;
            $actividad = Actividad::findOrFail($id);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Actividad no encontrada.');
        }

        // ✅ Validación mejorada
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'nullable|date',
            'fecha_limite' => 'nullable|date|after_or_equal:fecha_inicio',
            'tipo_actividad_id' => 'required|exists:tipo_actividades,id',
            'tipos_evaluacion' => 'required|array|min:1',
            'tipos_evaluacion.*.tipo_evaluacion_id' => 'required|exists:tipo_evaluaciones,id',
            'tipos_evaluacion.*.puntaje_maximo' => 'required|integer|min:1|max:1000',
            'tipos_evaluacion.*.es_obligatorio' => 'required|in:0,1',
            'archivo' => 'nullable|file|mimes:jpg,jpeg,png,gif,doc,docx,xls,xlsx,ppt,pptx,pdf,txt,mp4,mp3,wav,ogg,zip,rar|max:10240', // 10MB max
        ]);

        try {
            DB::beginTransaction();

            // ✅ Manejar archivo si se subió uno nuevo
            if ($request->hasFile('archivo')) {
                // Eliminar archivo anterior si existe
                if ($actividad->archivo && Storage::disk('public')->exists($actividad->archivo)) {
                    Storage::disk('public')->delete($actividad->archivo);
                }

                // Guardar nuevo archivo
                $archivoPath = $request->file('archivo')->store('actividades', 'public');
                $data['archivo'] = $archivoPath;
            }

            // ✅ Actualizar datos básicos de la actividad
            $actividad->update([
                'titulo' => $data['titulo'],
                'descripcion' => $data['descripcion'],
                'fecha_inicio' => $data['fecha_inicio'],
                'fecha_limite' => $data['fecha_limite'],
                'tipo_actividad_id' => $data['tipo_actividad_id'],
                'archivo' => $data['archivo'] ?? $actividad->archivo, // Mantener archivo existente si no hay nuevo
            ]);

            // ✅ Actualizar tipos de evaluación usando sync() más eficientemente
            $syncData = [];
            foreach ($data['tipos_evaluacion'] as $tipoEvaluacion) {
                $syncData[$tipoEvaluacion['tipo_evaluacion_id']] = [
                    'puntaje_maximo' => $tipoEvaluacion['puntaje_maximo'],
                    'es_obligatorio' => (bool) $tipoEvaluacion['es_obligatorio'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $actividad->tiposEvaluacion()->sync($syncData);

            DB::commit();
            return redirect()->back()->with('success', 'Actividad actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error actualizando actividad: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al actualizar la actividad.')->withInput();
        }
    }

    public function destroy($id)
    {
        $actividad = Actividad::findOrFail($id);
        $actividad->delete();

        return back()->with('success', 'Actividad eliminada correctamente.');
    }




    public function restaurar($id)
    {
        $actividad = Actividad::withTrashed()->findOrFail($id);

        if ($actividad->trashed()) {
            // Restaurar
            $actividad->restore();

            // Reasignar orden al final
            $ordenMaximo = Actividad::where('subtema_id', $actividad->subtema_id)
                ->whereNull('deleted_at')
                ->max('orden');

            $actividad->orden = is_null($ordenMaximo) ? 1 : $ordenMaximo + 1;
            $actividad->save();

            return back()->with('success', 'Actividad restaurada exitosamente.');
        }

        return back()->with('error', 'La actividad no está eliminada.');
    }
}
