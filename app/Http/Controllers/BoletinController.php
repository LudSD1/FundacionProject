<?php

namespace App\Http\Controllers;

use App\Models\Boletin;
use App\Models\Cursos;
use App\Models\Inscritos;
use App\Models\NotaEntrega;
use App\Models\NotaEvaluacion;
use App\Models\Notas_Boletin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

use Swift_Message;
use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Attachment;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Mail\Message;
use App\Mail\BoletinCorreo;
use App\Models\Actividad;
use Spatie\Browsershot\Browsershot;


class BoletinController extends Controller
{


    public function boletin($id)
    {
        // Obtener el inscrito con todas las relaciones necesarias
        $inscritos = Inscritos::with([
            'cursos.temas.subtemas.actividades.tipoActividad',
            'cursos.temas.subtemas.actividades.calificacionesEntregas',
            'cursos.temas.subtemas.actividades.intentosCuestionarios',
            'asistencia'
        ])->findOrFail($id);

        // Calcular notas y promedios
        $notasActividades = [];
        $actividadesData = [];

        foreach ($inscritos->cursos->temas as $tema) {
            foreach ($tema->subtemas as $subtema) {
                foreach ($subtema->actividades as $actividad) {
                    // Obtener nota de entrega
                    $notaEntrega = $actividad->calificacionesEntregas
                        ->where('inscripcion_id', $inscritos->id)
                        ->first();

                    // Obtener nota de cuestionario
                    $mejorIntento = $actividad->intentosCuestionarios
                        ->where('inscrito_id', $inscritos->id)
                        ->sortByDesc('nota')
                        ->first();

                    $nota = 0;
                    $estado = 'Pendiente';
                    $fecha = null;

                    if ($notaEntrega) {
                        $nota = $notaEntrega->nota;
                        $estado = 'Entregado';
                        $fecha = $notaEntrega->created_at;
                        $notasActividades[] = $nota;
                    } elseif ($mejorIntento) {
                        $nota = $mejorIntento->nota;
                        $estado = 'Cuestionario completado';
                        $fecha = $mejorIntento->created_at;
                        $notasActividades[] = $nota;
                    }

                    $actividadesData[] = [
                        'tema' => $tema->titulo_tema,
                        'subtema' => $subtema->titulo_subtema,
                        'actividad' => $actividad->titulo,
                        'tipo' => $actividad->tipoActividad->nombre ?? 'Sin tipo',
                        'nota' => $nota,
                        'estado' => $estado,
                        'fecha' => $fecha ? $fecha->format('Y-m-d H:i:s') : '-'
                    ];
                }
            }
        }

        // Calcular promedio de actividades (70%)
        $promedioActividades = !empty($notasActividades)
            ? round(array_sum($notasActividades) / count($notasActividades), 2)
            : 0;

        // Calcular porcentaje de asistencia (30%)
        $totalAsistencias = $inscritos->asistencia->count();
        $asistenciasValidas = $inscritos->asistencia
            ->whereIn('tipoAsitencia', ['Presente', 'Retraso', 'Licencia'])
            ->count();
        $porcentajeAsistencia = $totalAsistencias > 0
            ? round(($asistenciasValidas / $totalAsistencias) * 100, 2)
            : 0;

        // Calcular nota final
        $notaFinal = round(($promedioActividades * 0.7) + ($porcentajeAsistencia * 0.3), 2);

        // Determinar estado
        $estado = 'Reprobado';
        if ($notaFinal >= 76) {
            $estado = 'Experto';
        } elseif ($notaFinal >= 66) {
            $estado = 'Habilidoso';
        } elseif ($notaFinal >= 51) {
            $estado = 'Aprendiz';
        }

        $resumen = [
            'promedio_actividades' => $promedioActividades,
            'porcentaje_asistencia' => $porcentajeAsistencia,
            'nota_final' => $notaFinal,
            'estado' => $estado
        ];

        return view('Estudiante.boletin', compact('inscritos', 'actividadesData', 'resumen'));
    }


    public function guardar_boletin(Request $request)
    {


        $request->validate([
            'estudiante' => 'required',
            'notafinal' => 'required|numeric',
            'comentario' => 'required|string',
            'evaluaciones' => 'required|string',
            'notaEvaluacion' => 'required|numeric',
            'tareas' => 'required|string',
            'notaTarea' => 'required|numeric',
        ], [
            'estudiante.required' => 'El campo estudiante es obligatorio.',
            'notafinal.required' => 'El campo nota final es obligatorio.',
            'notafinal.numeric' => 'El campo nota final debe ser numérico.',
            'comentario.required' => 'El campo comentario es obligatorio.',
            'comentario.string' => 'El campo comentario debe ser una cadena de texto.',
            'evaluaciones.required' => 'No se encontraron notas para este estudiante.',
            'evaluaciones.string' => 'No se encontraron notas para este estudiante.',
            'notaEvaluacion.required' => 'No se encontraron notas para este estudiante.',
            'notaEvaluacion.numeric' => 'No se encontraron notas para este estudiante.',
            'tareas.required' => 'No se encontraron notas para este estudiante.',
            'tareas.string' => 'El campo tareas debe ser una cadena de texto.',
            'notaTarea.required' => 'El campo nota de tarea es obligatorio.',
            'notaTarea.numeric' => 'El campo nota de tarea debe ser numérico.',
        ]);

        $estudianteId = $request->estudiante;


        $boletin = Boletin::where('inscripcion_id', $estudianteId)->first();

        if (!$boletin) {
            $boletin = new Boletin;
            $boletin->inscripcion_id = $estudianteId;
            $boletin->nota_final = $request->notafinal;
            $boletin->comentario_boletin = $request->comentario;
            $boletin->save();
        } else {
            $boletin->nota_final = $request->notafinal;
            $boletin->comentario_boletin = $request->comentario;
            $boletin->save();
        }

        if ($boletin->notasBoletin->isEmpty()) {
            $boletinNotas1 = new Notas_Boletin;
            $boletinNotas1->boletin_id = $boletin->id;
            $boletinNotas1->nota_nombre = $request->evaluaciones;
            $boletinNotas1->nota = $request->notaEvaluacion;
            $boletinNotas1->save();

            $boletinNotas2 = new Notas_Boletin;
            $boletinNotas2->boletin_id = $boletin->id;
            $boletinNotas2->nota_nombre = $request->tareas;
            $boletinNotas2->nota = $request->notaTarea;
            $boletinNotas2->save();
        } else {
            $boletinNotas1 = $boletin->notasBoletin->firstWhere('nota_nombre', $request->evaluaciones);
            if ($boletinNotas1) {
                $boletinNotas1->nota = $request->notaEvaluacion;
                $boletinNotas1->save();
            }

            $boletinNotas2 = $boletin->notasBoletin->firstWhere('nota_nombre', $request->tareas);
            if ($boletinNotas2) {
                $boletinNotas2->nota = $request->notaTarea;
                $boletinNotas2->save();
            }
        }

        return back()->with('success', 'El boletin se ha guardado correctamente, puede verlo en ver calificaciones finales');
    }


    public function boletinEstudiantes($id)
    {
        $curso = Cursos::with([
            'temas.subtemas.actividades.tipoActividad',
            'temas.subtemas.actividades.calificacionesEntregas',
            'temas.subtemas.actividades.intentosCuestionarios'
        ])->findOrFail($id);

        $inscritos = Inscritos::where('cursos_id', $id)
            ->where('estudiante_id', auth()->user()->id)
            ->with('asistencia')
            ->first();

        if (!$inscritos) {
            return back()->with('error', 'No se encontró la inscripción.');
        }

        // Calcular notas y promedios
        $notasActividades = [];
        $actividadesData = [];

        foreach ($curso->temas as $tema) {
            foreach ($tema->subtemas as $subtema) {
                foreach ($subtema->actividades as $actividad) {
                    // Obtener nota de entrega
                    $notaEntrega = $actividad->calificacionesEntregas
                        ->where('inscripcion_id', $inscritos->id)
                        ->first();

                    // Obtener nota de cuestionario
                    $mejorIntento = $actividad->intentosCuestionarios
                        ->where('inscrito_id', $inscritos->id)
                        ->sortByDesc('nota')
                        ->first();

                    $nota = 0;
                    $estado = 'Pendiente';
                    $fecha = null;

                    if ($notaEntrega) {
                        $nota = $notaEntrega->nota;
                        $estado = 'Entregado';
                        $fecha = $notaEntrega->created_at;
                        $notasActividades[] = $nota;
                    } elseif ($mejorIntento) {
                        $nota = $mejorIntento->nota;
                        $estado = 'Cuestionario completado';
                        $fecha = $mejorIntento->created_at;
                        $notasActividades[] = $nota;
                    }

                    $actividadesData[] = [
                        'tema' => $tema->titulo_tema,
                        'subtema' => $subtema->titulo_subtema,
                        'actividad' => $actividad->titulo,
                        'tipo' => $actividad->tipoActividad->nombre ?? 'Sin tipo',
                        'nota' => $nota,
                        'estado' => $estado,
                        'fecha' => $fecha ? $fecha->format('Y-m-d H:i:s') : '-'
                    ];
                }
            }
        }

        // Calcular promedio de actividades (70%)
        $promedioActividades = !empty($notasActividades)
            ? round(array_sum($notasActividades) / count($notasActividades), 2)
            : 0;

        // Calcular porcentaje de asistencia (30%)
        $totalAsistencias = $inscritos->asistencia->count();
        $asistenciasValidas = $inscritos->asistencia
            ->whereIn('tipoAsitencia', ['Presente', 'Retraso', 'Licencia'])
            ->count();
        $porcentajeAsistencia = $totalAsistencias > 0
            ? round(($asistenciasValidas / $totalAsistencias) * 100, 2)
            : 0;

        // Calcular nota final
        $notaFinal = round(($promedioActividades * 0.7) + ($porcentajeAsistencia * 0.3), 2);

        // Determinar estado
        $estado = 'Reprobado';
        if ($notaFinal >= 76) {
            $estado = 'Experto';
        } elseif ($notaFinal >= 66) {
            $estado = 'Habilidoso';
        } elseif ($notaFinal >= 51) {
            $estado = 'Aprendiz';
        }

        $resumen = [
            'promedio_actividades' => $promedioActividades,
            'porcentaje_asistencia' => $porcentajeAsistencia,
            'nota_final' => $notaFinal,
            'estado' => $estado
        ];

        // Guardar o actualizar el boletín
        $boletin = Boletin::updateOrCreate(
            ['inscripcion_id' => $inscritos->id],
            [
                'nota_final' => $notaFinal,
                'comentario_boletin' => "Actualizado automáticamente. Estado: $estado"
            ]
        );

        return view('Estudiante.boletin', compact('curso', 'inscritos', 'actividadesData', 'resumen', 'boletin'));
    }





    public function boletinEstudiantes2($id)
    {
        $inscritos = Inscritos::with([
            'cursos.temas.subtemas.actividades.tipoActividad',
            'cursos.temas.subtemas.actividades.calificacionesEntregas',
            'cursos.temas.subtemas.actividades.intentosCuestionarios',
            'asistencia'
        ])->findOrFail($id);

        // Calcular notas y promedios
        $notasActividades = [];
        $actividadesData = [];

        foreach ($inscritos->cursos->temas as $tema) {
            foreach ($tema->subtemas as $subtema) {
                foreach ($subtema->actividades as $actividad) {
                    // Obtener nota de entrega
                    $notaEntrega = $actividad->calificacionesEntregas
                        ->where('inscripcion_id', $inscritos->id)
                        ->first();

                    // Obtener nota de cuestionario
                    $mejorIntento = $actividad->intentosCuestionarios
                        ->where('inscrito_id', $inscritos->id)
                        ->sortByDesc('nota')
                        ->first();

                    $nota = 0;
                    $estado = 'Pendiente';
                    $fecha = null;

                    if ($notaEntrega) {
                        $nota = $notaEntrega->nota;
                        $estado = 'Entregado';
                        $fecha = $notaEntrega->created_at;
                        $notasActividades[] = $nota;
                    } elseif ($mejorIntento) {
                        $nota = $mejorIntento->nota;
                        $estado = 'Cuestionario completado';
                        $fecha = $mejorIntento->created_at;
                        $notasActividades[] = $nota;
                    }

                    $actividadesData[] = [
                        'tema' => $tema->titulo_tema,
                        'subtema' => $subtema->titulo_subtema,
                        'actividad' => $actividad->titulo,
                        'tipo' => $actividad->tipoActividad->nombre ?? 'Sin tipo',
                        'nota' => $nota,
                        'estado' => $estado,
                        'fecha' => $fecha ? $fecha->format('Y-m-d H:i:s') : '-'
                    ];
                }
            }
        }

        // Calcular promedio de actividades (70%)
        $promedioActividades = !empty($notasActividades)
            ? round(array_sum($notasActividades) / count($notasActividades), 2)
            : 0;

        // Calcular porcentaje de asistencia (30%)
        $totalAsistencias = $inscritos->asistencia->count();
        $asistenciasValidas = $inscritos->asistencia
            ->whereIn('tipoAsitencia', ['Presente', 'Retraso', 'Licencia'])
            ->count();
        $porcentajeAsistencia = $totalAsistencias > 0
            ? round(($asistenciasValidas / $totalAsistencias) * 100, 2)
            : 0;

        // Calcular nota final
        $notaFinal = round(($promedioActividades * 0.7) + ($porcentajeAsistencia * 0.3), 2);

        // Determinar estado
        $estado = 'Reprobado';
        if ($notaFinal >= 76) {
            $estado = 'Experto';
        } elseif ($notaFinal >= 66) {
            $estado = 'Habilidoso';
        } elseif ($notaFinal >= 51) {
            $estado = 'Aprendiz';
        }

        $resumen = [
            'promedio_actividades' => $promedioActividades,
            'porcentaje_asistencia' => $porcentajeAsistencia,
            'nota_final' => $notaFinal,
            'estado' => $estado
        ];

        // Obtener o crear el boletín
        $boletin = Boletin::firstOrCreate(
            ['inscripcion_id' => $inscritos->id],
            [
                'nota_final' => $notaFinal,
                'comentario_boletin' => "Generado automáticamente. Estado: $estado"
            ]
        );

        return view('Estudiante.boletin2', compact('inscritos', 'actividadesData', 'resumen', 'boletin'));
    }














    public function listarNotasActividad($actividadId)
    {
        // Obtener la actividad con sus relaciones
        $actividad = Actividad::with(['entregasNotas', 'intentosEstudiante'])->findOrFail($actividadId);

        // Notas de las entregas
        $notasEntregas = $actividad->entregasNotas->map(function ($nota) {
            return [
                'tipo' => 'Entrega',
                'nota' => $nota->nota,
                'comentario' => $nota->retroalimentacion,
                'fecha' => $nota->created_at,
            ];
        });

        // Notas de los intentos de cuestionarios
        $notasCuestionarios = $actividad->intentosEstudiante->map(function ($intento) {
            return [
                'tipo' => 'Cuestionario',
                'nota' => $intento->calificacion,
                'comentario' => $intento->comentario ?? 'Sin comentario',
                'fecha' => $intento->created_at,
            ];
        });

        // Combinar ambas ramas
        $notasCombinadas = $notasEntregas->merge($notasCuestionarios);

        return view('Estudiante.NotasActividad', [
            'actividad' => $actividad,
            'notas' => $notasCombinadas,
        ]);
    }






    public function enviarBoletin(Request $request, $id)
    {
        $inscritos = Inscritos::findOrFail($id);
        $boletinNotas = [];
        $boletin = null;

        if ($inscritos) {
            $boletin = Boletin::where('inscripcion_id', $inscritos->id)->first();
            // Verificar si $boletin es null antes de buscar notas del boletín
            if ($boletin) {
                $boletinNotas = Notas_Boletin::where('boletin_id', $boletin->id)->get();
            }
        }


        if ($inscritos) {
            $imageUrl = secure_asset('assets/img/logof.png');
            $imageUrl2 = secure_asset('assets/img/logoedin.png');
            $imageUrl3 = secure_asset('assets/img/firma digital.png');

            $htmlContent = view('Estudiante.boletin3')
                ->with('imageUrl', $imageUrl)
                ->with('imageUrl2', $imageUrl2)
                ->with('imageUrl3', $imageUrl3)
                ->with('inscritos', $inscritos)
                ->with('boletin', $boletin)
                ->with('boletinNotas', $boletinNotas)
                ->render();

            $tempHtmlFile = tempnam(sys_get_temp_dir(), 'boletin');
            file_put_contents($tempHtmlFile, $htmlContent);

            $transport = (new Swift_SmtpTransport('smtp-relay.sendinblue.com', 587))
                ->setUsername('correopruebas015@gmail.com')
                ->setPassword('KAFrt15YxhU6Oc4y');

            $mailer = new Swift_Mailer($transport);

            $message = (new Swift_Message('Asunto del correo'))
                ->setFrom(['educarparalavida.fund@gmail.com' => 'Fundacion Educar Para la Vida'])
                ->setTo([$inscritos->estudiantes->email])
                ->setBody("Boletin de notas") // Puedes personalizar esto según tus necesidades.
                ->attach(Swift_Attachment::fromPath($tempHtmlFile, 'text/html'));

            $result = $mailer->send($message);






            unlink($tempHtmlFile);




            if ($result) {


                return 'Correo con contenido HTML como adjunto enviado con éxito.';
            } else {


                return 'Error al enviar el correo con contenido HTML como adjunto.';
            }
        } else {
            return 'Error al enviar el correo. Correo de destino no disponible.';
        }
    }
}
