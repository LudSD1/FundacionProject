<?php

namespace App\Http\Controllers;

use App\Events\InscritoEvent;
use App\Models\Cursos;
use App\Models\Inscritos;
use App\Models\User;
use App\Notifications\InscritoNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\XPService;
use App\Services\AchievementService;
use Illuminate\Support\Facades\Log;

class InscritosController extends Controller
{
    protected $xpService;
    protected $achievementService;

    public function __construct(XPService $xpService, AchievementService $achievementService)
    {
        $this->xpService = $xpService;
        $this->achievementService = $achievementService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Obtener todos los cursos activos
        $cursos = Cursos::where('fecha_fin', '>=', now())->get();

        return view('Administrador.AsignarCursos', [
            'cursos' => $cursos,
        ]);
    }

    public function getEstudiantesNoInscritos($curso_id)
    {
        // Obtener los IDs de los estudiantes inscritos en el curso (excluyendo inscripciones eliminadas)
        $inscritos = Inscritos::withTrashed() // Incluir inscripciones eliminadas lógicamente
            ->where('cursos_id', $curso_id)
            ->pluck('estudiante_id');

        // Obtener los estudiantes que no están inscritos en el curso (excluyendo estudiantes eliminados)
        $estudiantesNoInscritos = User::role('Estudiante')
            ->whereNotIn('id', $inscritos)
            ->whereNull('deleted_at') // Excluir estudiantes eliminados lógicamente
            ->get();

        return response()->json($estudiantesNoInscritos);
    }


    // En CursosController.php
    public function store(Request $request)
    {
        $request->validate([
            'curso_id' => 'required|integer|exists:cursos,id',
            'estudiante_id' => 'required|array',
            'estudiante_id.*' => 'required|string',
        ], [
            'curso_id.required' => 'El campo curso es obligatorio.',
            'curso_id.integer' => 'El campo curso debe ser un número entero.',
            'curso_id.exists' => 'El curso seleccionado no es válido.',
            'estudiante_id.required' => 'El campo estudiante es obligatorio.',
            'estudiante_id.array' => 'El campo estudiante debe ser un array.',
            'estudiante_id.*.required' => 'Cada estudiante es requerido.',
            'estudiante_id.*.string' => 'Cada estudiante debe ser un ID válido.',
        ]);

        $curso_id = $request->input('curso_id');
        $estudiante_ids_encrypted = $request->input('estudiante_id');


        $estudiante_ids = [];
        $ids_invalidos = [];

        foreach ($estudiante_ids_encrypted as $encrypted_id) {
            try {
                $decrypted_id = decrypt($encrypted_id);

                if (is_numeric($decrypted_id) && User::where('id', $decrypted_id)->exists()) {
                    $estudiante_ids[] = (int) $decrypted_id;
                } else {
                    $ids_invalidos[] = $encrypted_id;
                }
            } catch (DecryptException $e) {
                $ids_invalidos[] = $encrypted_id;
            }
        }
        

        if (!empty($ids_invalidos)) {
            Log::channel('admin')->warning('IDs de estudiantes inválidos en inscripción', [
                'admin_id' => auth()->id(),
                'curso_id' => $curso_id,
                'ids_invalidos' => $ids_invalidos
            ]);

            return redirect()->back()
                ->withErrors(['estudiante_id' => 'Algunos IDs de estudiantes son inválidos o no existen.'])
                ->withInput();
        }

        if (empty($estudiante_ids)) {
            return redirect()->back()
                ->withErrors(['estudiante_id' => 'No se proporcionaron estudiantes válidos.'])
                ->withInput();
        }

        $curso = Cursos::find($curso_id);

        try {
            Log::channel('admin')->info('Intento de inscripción masiva', [
                'admin_id' => auth()->id(),
                'curso_id' => $curso_id,
                'curso_nombre' => $curso->nombreCurso,
                'estudiantes_solicitados' => count($estudiante_ids),
                'estudiante_ids' => $estudiante_ids
            ]);

            $inscritos = Inscritos::where('cursos_id', $curso_id)
                ->whereIn('estudiante_id', $estudiante_ids)
                ->pluck('estudiante_id')
                ->toArray();

            if (!empty($inscritos)) {
                $usuariosInscritos = User::whereIn('id', $inscritos)->pluck('name')->toArray();

                Log::channel('admin')->warning('Intento de inscribir estudiantes ya inscritos', [
                    'admin_id' => auth()->id(),
                    'curso_id' => $curso_id,
                    'estudiantes_ya_inscritos' => $inscritos,
                    'nombres_inscritos' => $usuariosInscritos
                ]);

                return redirect()->back()
                    ->withErrors(['estudiante_id' => 'Algunos estudiantes ya están inscritos: ' . implode(', ', $usuariosInscritos)])
                    ->withInput();
            }

            $estudiantesNoInscritos = array_diff($estudiante_ids, $inscritos);

            if ($curso->cupos > 0) {
                $inscripcionesActuales = Inscritos::where('cursos_id', $curso_id)->count();
                $cuposDisponibles = $curso->cupos - $inscripcionesActuales;

                if ($cuposDisponibles <= 0) {
                    return redirect()->back()
                        ->withErrors(['curso_id' => 'El curso no tiene cupos disponibles.'])
                        ->withInput();
                }

                if (count($estudiantesNoInscritos) > $cuposDisponibles) {
                    return redirect()->back()
                        ->withErrors(['estudiante_id' => "Solo hay {$cuposDisponibles} cupos disponibles, pero intentas inscribir " . count($estudiantesNoInscritos) . " estudiantes."])
                        ->withInput();
                }
            }

            $inscritosExitosos = [];
            foreach ($estudiantesNoInscritos as $estudiante_id) {
                $inscrito = Inscritos::create([
                    'estudiante_id' => $estudiante_id,
                    'cursos_id' => $curso_id,
                    'pago_completado' => true,
                    'progreso' => 0,
                ]);

                $xpBase = $curso->tipo == 'congreso' ? 100 : 50;
                $this->xpService->addXP($inscrito, $xpBase, "Inscripción en {$curso->nombreCurso}");

                $inscritosExitosos[] = $estudiante_id;
            }

            $nombresInscritos = User::whereIn('id', $inscritosExitosos)->pluck('name')->toArray();
            Log::channel('admin')->info('Inscripción masiva exitosa', [
                'admin_id' => auth()->id(),
                'curso_id' => $curso_id,
                'curso_nombre' => $curso->nombreCurso,
                'estudiantes_inscritos' => count($inscritosExitosos),
                'estudiante_ids' => $inscritosExitosos,
                'nombres_estudiantes' => $nombresInscritos,
                'cupos_restantes' => $curso->cupos > 0 ? ($curso->cupos - Inscritos::where('cursos_id', $curso_id)->count()) : 'Ilimitado'
            ]);

            return redirect()->back()->with(
                'success',
                'Se inscribieron correctamente ' . count($inscritosExitosos) . ' estudiantes: ' . implode(', ', $nombresInscritos)
            );
        } catch (\Exception $e) {
            Log::channel('admin')->error('Error en inscripción masiva', [
                'admin_id' => auth()->id(),
                'curso_id' => $curso_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Ocurrió un error durante la inscripción.'])
                ->withInput();
        }
    }


    public function update(Request $request, $id)
    {
        $curso = Cursos::findOrFail($id);

        $request->validate([
            'nombreCurso' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'tipo' => 'required|in:curso,congreso,taller,seminario',
            'cupos' => 'nullable|integer|min:0|max:1000',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after:fecha_inicio',
        ]);


        // Validar que no se reduzcan los cupos por debajo de inscritos actuales
        $inscritosActuales = $curso->inscripcionesActuales();
        $nuevosCupos = $request->cupos ?? 0;

        if ($nuevosCupos > 0 && $nuevosCupos < $inscritosActuales) {
            return redirect()->back()
                ->withErrors(['cupos' => "No puedes reducir los cupos a {$nuevosCupos} porque ya hay {$inscritosActuales} estudiantes inscritos."])
                ->withInput();
        }

        try {
            $datosAnteriores = $curso->toArray();

            $curso->update([
                'nombreCurso' => $request->nombreCurso,
                'descripcion' => $request->descripcion,
                'tipo' => $request->tipo,
                'cupos' => $nuevosCupos,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
            ]);

            Log::channel('admin')->info('Curso actualizado', [
                'admin_id' => auth()->id(),
                'curso_id' => $curso->id,
                'datos_anteriores' => $datosAnteriores,
                'datos_nuevos' => $curso->fresh()->toArray(),
            ]);

            return redirect()->route('cursos.index')
                ->with('success', 'Curso actualizado exitosamente.');
        } catch (Exception $e) {
            Log::channel('admin')->error('Error actualizando curso', [
                'admin_id' => auth()->id(),
                'curso_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Error al actualizar el curso.'])
                ->withInput();
        }
    }


    public function storeCongreso($id)
    {



        $cursoId = $id;
        $estudianteId = auth()->user()->id;


        // Verificar si el estudiante ya está inscrito en el curso
        if (Inscritos::where('cursos_id', $cursoId)->where('estudiante_id', $estudianteId)->exists()) {
            // Redirigir con un mensaje de error si ya está inscrito
            return back()->with('error', 'Ya estás inscrito en este curso.');
        }

        // Crear una nueva inscripción
        $inscribir = new Inscritos();
        $inscribir->cursos_id = $cursoId;
        $inscribir->estudiante_id = $estudianteId;
        $inscribir->save();

        // Otorgar XP por inscripción en congreso
        $curso = Cursos::find($cursoId);
        $this->xpService->addXP($inscribir, 100, "Inscripción en congreso - {$curso->nombreCurso}");

        // Obtener el estudiante y el curso para la notificación
        $estudiante = User::find($estudianteId);
        $curso = Cursos::find($cursoId);

        // Disparar el evento de inscripción
        event(new InscritoEvent($estudiante, $curso, 'inscripcion'));

        // Redirigir con un mensaje de éxito
        return back()->with('success', 'Estudiante inscrito exitosamente!');
    }


    public function delete($id)
    {
        $inscritos = Inscritos::find($id);


        event(new InscritoEvent($inscritos->estudiantes, $inscritos->cursos, 'eliminacion'));

        $inscritos->delete();

        return back()->with('success', 'Inscripcion retirada');
    }


    public function restaurarInscrito($id)
    {
        $inscritos = Inscritos::onlyTrashed()->find($id);


        event(new InscritoEvent($inscritos->estudiantes, $inscritos->cursos, 'restauracion'));


        $inscritos->restore();

        return back()->with('success', 'Inscripcion restaurada!');
    }

    public function completado($id)
    {


        $inscritos = Inscritos::findOrFail($id);

        if ($inscritos->cursos->docente_id == auth()->user()->id || auth()->user()->hasRole('administrador')) {
            $inscritos->completado = true;
            $inscritos->progreso = 100;

            $inscritos->save();

            return back()->with('success', 'El estudiante a completado el curso');
        }

        return abort(403);
    }


    public function inscribirse($id, $token)
    {
        $qrToken = DB::table('qr_tokens')
            ->where('curso_id', $id)
            ->where('token', $token)
            ->where('expiracion', '>=', now())
            ->whereColumn('usos_actuales', '<', 'limite_uso')
            ->first();

        if (!$qrToken) {
            return redirect()->route('Inicio')->withErrors('El código QR no es válido o ha expirado.');
        }

        DB::table('qr_tokens')->where('id', $qrToken->id)->increment('usos_actuales');

        $curso = Cursos::findorFail($id);

        if ($curso->fecha_fin) {
            $fechaActual = Carbon::now();
            $fechaFin = Carbon::parse($curso->fecha_fin);

            if ($fechaFin->lt($fechaActual)) {
                return redirect()->route('Inicio')->withErrors('El curso ya finalizó.');
            }
        }

        $usuario = auth()->user();
        if ($usuario->hasRole('Administrador') || $usuario->hasRole('Docente')) {
            return redirect()->route('Inicio')->with('errors', 'No puedes inscribirte es este curso siendo docente o administrador porfavor crear otra cuenta.');
        } elseif ($usuario->id == $curso->docente_id) {
            return redirect()->route('Inicio')->withErrors('Ya eres Docente en este curso.');
        }

        $inscripcion = Inscritos::withTrashed() // Incluir registros eliminados
            ->where('cursos_id', $id)
            ->where('estudiante_id', $usuario->id)
            ->first();

        if ($inscripcion) {
            if ($inscripcion->trashed()) {
                return redirect()->back()->withErrors('Tu inscripción en este curso fue cancelada previamente.');
            }

            return redirect()->back()->withErrors('Ya estás inscrito en este curso.');
        }

        $estudiante = User::find($usuario->id);

        if ($estudiante && $curso) {
            // Enviar la notificación a través del evento
            event(new InscritoEvent($estudiante, $curso, 'inscripcion'));
        }

        $inscritos = new Inscritos();
        $inscritos->cursos_id = $id;
        if ($curso->tipo == 'congreso') {
            $inscritos->pago_completado = true;
            $inscritos->progreso = doubleval(100);
        }
        $inscritos->estudiante_id = $usuario->id;
        $inscritos->save();



        return redirect()->route('Curso', $id)->with('success', '¡Te has inscrito correctamente!');
    }


    public function actualizarPago(Request $request, $inscrito_id)
    {
        // Encuentra el registro de inscripción
        $inscrito = Inscritos::findOrFail($inscrito_id);
        $pagoAnterior = $inscrito->pago_completado;

        // Actualiza el campo 'pago_completado' basado en el valor enviado desde el formulario
        $inscrito->pago_completado = filter_var($request->input('pago_completado'), FILTER_VALIDATE_BOOLEAN);
        $inscrito->save();

        // Si el pago se completó y antes no estaba completado
        if ($inscrito->pago_completado && !$pagoAnterior) {
            // Otorgar XP por completar el pago
            $curso = Cursos::find($inscrito->cursos_id);
            $xpPago = $curso->tipo == 'congreso' ? 150 : 75; // Más XP por pagar un congreso
            $this->xpService->addXP($inscrito, $xpPago, "Pago completado - {$curso->nombreCurso}");
        }

        // Redirige con un mensaje de éxito
        return back()->with('success', 'El estado del pago se ha actualizado correctamente.');
    }
}
