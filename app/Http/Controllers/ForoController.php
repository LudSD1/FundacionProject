<?php

namespace App\Http\Controllers;

use App\Events\ForoEvent;
use App\Models\Cursos;
use App\Models\Foro;
use App\Models\ForoMensaje;
use App\Models\Achievement;
use App\Models\UserXP;
use App\Models\Inscritos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\XPService;
use App\Services\AchievementService;

class ForoController extends Controller
{
    protected $xpService;
    protected $achievementService;

    public function __construct(XPService $xpService, AchievementService $achievementService)
    {
        $this->xpService = $xpService;
        $this->achievementService = $achievementService;
    }

    // Constantes para XP
    const XP_NUEVO_MENSAJE = 50;
    const XP_NUEVA_RESPUESTA = 30;
    const XP_PRIMER_MENSAJE_DIA = 20;

    public function Crearforo($id){

        $cursos = Cursos::findOrFail($id);

        return(view('Docente.CrearForo'))->with('cursos', $cursos);


    }

    public function store(Request $request){

        $messages = [
            'nombreForo.required' => 'El campo nombre del foro es obligatorio.',
            'descripcionForo.required' => 'El campo descripción del foro es obligatorio.',
            'fechaFin.required' => 'El campo fecha de fin es obligatorio.',
        ];

        $request->validate([
            'nombreForo' => 'required',
            'descripcionForo' => 'required',
            'fechaFin' => 'required',
        ], $messages);

        $foro = new Foro();

        $foro->nombreForo = $request->nombreForo;
        $foro->descripcionForo = $request->descripcionForo;
        $foro->SubtituloForo = $request->SubtituloForo;
        $foro->fechaFin = date("Y-m-d", strtotime($request->fechaFin));
        $foro->cursos_id = $request->curso_id;
        $foro->created_at = now();
        event(new ForoEvent($foro, 'crear'));
        $foro->save();

        return redirect(route('Curso', $request->curso_id))->with('success', 'Foro Creado Correctamente');


    }

    public function index($id){
        $foro = Foro::findOrFail($id);
        $forosmensajes = ForoMensaje::where('foro_id', $foro->id)
            ->whereNull('respuesta_a')
            ->with('respuestas.estudiantes') // Cargar respuestas y sus autores
            ->get();


        return view('Foro')->with('foro', $foro)->with('forosmensajes', $forosmensajes);

    }


    public function storeMensaje(Request $request)
    {
        $messages = [
            'tituloMensaje.required' => 'El campo título del mensaje es obligatorio.',
            'mensaje.required' => 'El campo mensaje es obligatorio.',
            'foro_id.required' => 'El foro al que pertenece el mensaje es obligatorio.',
            'foro_id.exists' => 'El foro especificado no existe.',
            'estudiante_id.required' => 'El identificador del estudiante es obligatorio.',
            'estudiante_id.exists' => 'El estudiante especificado no existe.',
            'respuesta_a.exists' => 'El mensaje al que respondes no existe.',
        ];

        $validated = $request->validate([
            'tituloMensaje' => 'required|string|max:255',
            'mensaje' => 'required|string',
            'foro_id' => 'required|exists:foros,id',
            'estudiante_id' => 'required|exists:users,id',
            'respuesta_a' => 'nullable|exists:foros_mensajes,id',
        ], $messages);

        try {
            DB::beginTransaction();

            // Crear el mensaje
            $mensaje = ForoMensaje::create($validated);

            // Obtener el foro y el curso
            $foro = Foro::findOrFail($request->foro_id);

            // Obtener el inscrito correspondiente
            $inscrito = Inscritos::where('estudiante_id', $request->estudiante_id)
                               ->where('cursos_id', $foro->cursos_id)
                               ->first();

            if (!$inscrito) {
                throw new \Exception('El estudiante no está inscrito en este curso.');
            }

            // XP base por el mensaje
            $xpToAdd = $request->respuesta_a ? self::XP_NUEVA_RESPUESTA : self::XP_NUEVO_MENSAJE;

            // Bonus por primer mensaje del día
            $ultimoMensajeHoy = ForoMensaje::where('estudiante_id', $request->estudiante_id)
                ->whereDate('created_at', today())
                ->where('id', '!=', $mensaje->id)
                ->exists();

            if (!$ultimoMensajeHoy) {
                $xpToAdd += self::XP_PRIMER_MENSAJE_DIA;
            }

            // Añadir XP usando XPService
            $this->xpService->addXP(
                $inscrito,
                $xpToAdd,
                $request->respuesta_a ? "Nueva respuesta en foro" : "Nuevo mensaje en foro"
            );

            // Verificar logros usando AchievementService
            $totalMensajes = ForoMensaje::where('estudiante_id', $request->estudiante_id)->count();
            $this->achievementService->checkAndAwardAchievements($inscrito, 'FORUM_CONTRIBUTOR', $totalMensajes);

            DB::commit();

            // Retornar con los mensajes de éxito y XP ganado
            return back()->with([
                'success' => 'Mensaje enviado exitosamente.',
                'xp_earned' => $xpToAdd
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating mensaje: ' . $e->getMessage());
            return back()->with('error', 'Hubo un problema al enviar el mensaje: ' . $e->getMessage());
        }
    }

    protected function checkAchievements(Inscritos $inscrito)
    {
        try {
            // Contar mensajes totales del usuario
            $totalMensajes = ForoMensaje::where('estudiante_id', $inscrito->estudiante_id)->count();

            // Logros por cantidad de mensajes
            $messageAchievements = [
                ['count' => 1, 'title' => 'Primer Mensaje', 'xp' => 100],
                ['count' => 5, 'title' => 'Participante Activo', 'xp' => 200],
                ['count' => 10, 'title' => 'Contribuidor Frecuente', 'xp' => 300],
                ['count' => 25, 'title' => 'Experto en Discusiones', 'xp' => 500],
                ['count' => 50, 'title' => 'Maestro del Foro', 'xp' => 1000],
            ];

            foreach ($messageAchievements as $achievement) {
                if ($totalMensajes >= $achievement['count']) {
                    // Buscar o crear el logro
                    $achievementModel = Achievement::firstOrCreate(
                        ['title' => $achievement['title']],
                        [
                            'description' => "Publicar {$achievement['count']} mensajes en el foro",
                            'type' => 'foro',
                            'requirement_value' => $achievement['count'],
                            'xp_reward' => $achievement['xp'],
                            'is_secret' => false
                        ]
                    );

                    // Otorgar el logro si no lo tiene
                    if (!$achievementModel->isUnlockedByInscrito($inscrito)) {
                        $achievementModel->award($inscrito);

                        // Guardar información del logro para la notificación
                        session()->flash('achievement', [
                            'title' => $achievement['title'],
                            'description' => "¡Has publicado {$achievement['count']} mensajes en el foro!",
                            'xp_reward' => $achievement['xp']
                        ]);
                    }
                }
            }

            // Logro por respuestas
            $totalRespuestas = ForoMensaje::where('estudiante_id', $inscrito->estudiante_id)
                ->whereNotNull('respuesta_a')
                ->count();

            if ($totalRespuestas >= 10) {
                $supporterAchievement = Achievement::firstOrCreate(
                    ['title' => 'Colaborador Destacado'],
                    [
                        'description' => 'Ayudar a otros respondiendo 10 mensajes en el foro',
                        'type' => 'foro',
                        'requirement_value' => 10,
                        'xp_reward' => 400,
                        'is_secret' => false
                    ]
                );

                if (!$supporterAchievement->isUnlockedByInscrito($inscrito)) {
                    $supporterAchievement->award($inscrito);

                    // Guardar información del logro para la notificación
                    session()->flash('achievement', [
                        'title' => 'Colaborador Destacado',
                        'description' => '¡Has ayudado a otros respondiendo 10 mensajes!',
                        'xp_reward' => 400
                    ]);
                }
            }

        } catch (\Exception $e) {
            \Log::error('Error checking achievements: ' . $e->getMessage());
            // No lanzamos la excepción para no interrumpir el flujo principal
        }
    }

    public function EditarForoIndex($id){

        $foro = Foro::findOrFail($id);

        return view('Docente.EditarForo')->with('foro', $foro);

    }

    public function update(Request $request){

        $messages = [
            'nombreForo.required' => 'El campo nombre del foro es obligatorio.',
            'descripcionForo.required' => 'El campo descripción del foro es obligatorio.',
            'fechaFin.required' => 'El campo fecha de fin es obligatorio.',
        ];

        $request->validate([
            'nombreForo' => 'required',
            'descripcionForo' => 'required',
            'fechaFin' => 'required',
        ], $messages);


        $foro = Foro::findOrFail($request->idForo);

        $foro->nombreForo = $request->nombreForo;
        $foro->descripcionForo = $request->descripcionForo;
        $foro->SubtituloForo = $request->SubtituloForo;
        $foro->fechaFin = date("Y-m-d", strtotime($request->fechaFin));
        $foro->cursos_id = $request->curso_id;

        $foro->save();



        return redirect(route('Curso', $request->curso_id))->with('success', 'Foro editado correctamente');

    }





    public function indexE($id){


        $cursos = Cursos::findOrFail($id);

        $foro = Foro::withTrashed()
        ->where('cursos_id', $id)
        ->onlyTrashed()
        ->get();


        return view('Docente.ListaForosEliminados')->with('foro', $foro)->with('cursos', $cursos);






    }



    public function restore($id)
    {
        $foro = Foro::onlyTrashed()->find($id);
        $foro->restore();

        return back()->with('succes', 'Foro restaurado exitosamente');

    }

    public function editMensaje(Request $request, $id)
{
    $mensaje = ForoMensaje::findOrFail($id);

    $messages = [
        'tituloMensaje.required' => 'El campo título del mensaje es obligatorio.',
        'mensaje.required' => 'El campo mensaje es obligatorio.',
    ];

    $request->validate([
        'tituloMensaje' => 'required',
        'mensaje' => 'required',
    ], $messages);

    $mensaje->update([
        'tituloMensaje' => $request->tituloMensaje,
        'mensaje' => $request->mensaje,
    ]);

    return back()->with('success', 'Mensaje actualizado correctamente.');
}

public function editRespuesta(Request $request, $id)
{
    $respuesta = ForoMensaje::findOrFail($id); // Encuentra la respuesta

    $messages = [
        'tituloMensaje.required' => 'El campo título de la respuesta es obligatorio.',
        'mensaje.required' => 'El campo mensaje es obligatorio.',
    ];

    $request->validate([
        'tituloMensaje' => 'required',
        'mensaje' => 'required',
    ], $messages);

    $respuesta->update([
        'tituloMensaje' => $request->tituloMensaje,
        'mensaje' => $request->mensaje,
    ]);

    return back()->with('success', 'Respuesta actualizada correctamente.');
}

public function deleteRespuesta($id)
{
    $respuesta = ForoMensaje::findOrFail($id);
    $respuesta->delete(); // Soft delete
    return back()->with('success', 'Respuesta eliminada correctamente.');
}


public function deleteMensaje($id)
{
    $mensaje = ForoMensaje::findOrFail($id);
    $mensaje->delete(); // Soft delete
    return back()->with('success', 'Mensaje eliminado correctamente.');
}







    public function delete($id)
    {
        $foro = Foro::findOrFail($id);
        $foro->delete();

        return back()->with('success', 'Foro eliminado exitosamente.');
    }
}
