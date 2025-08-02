<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CursoProgreso extends BaseModel
{
    protected $table = 'curso_progreso';

    protected $fillable = [
        'curso_id',
        'total_estudiantes',
        'estudiantes_completados',
        'porcentaje_progreso',
        'ultima_actualizacion'
    ];

    protected $casts = [
        'ultima_actualizacion' => 'datetime'
    ];

    public function curso()
    {
        return $this->belongsTo(Cursos::class);
    }

    /**
     * Calcula el progreso general del curso
     * @param int $cursoId
     * @return float
     */
    public static function calcularProgresoCurso($cursoId)
    {
        $curso = Cursos::with(['inscritos'])->find($cursoId);

        if (!$curso) {
            return 0;
        }

        $totalEstudiantes = $curso->inscritos->count();

        if ($totalEstudiantes === 0) {
            return 0;
        }

        // Calcular estudiantes que han completado el curso
        $estudiantesCompletados = $curso->inscritos->filter(function($inscrito) {
            // Aquí puedes definir tu lógica de qué considera un estudiante como "completado"
            // Por ejemplo, si ha completado todas las actividades, exámenes, etc.
            return $inscrito->progreso >= 100;
        })->count();

        // Calcular el porcentaje total de progreso
        $porcentajeTotal = $curso->inscritos->avg('progreso') ?? 0;

        // Actualizar o crear registro de progreso
        $progreso = self::updateOrCreate(
            ['curso_id' => $cursoId],
            [
                'total_estudiantes' => $totalEstudiantes,
                'estudiantes_completados' => $estudiantesCompletados,
                'porcentaje_progreso' => $porcentajeTotal,
                'ultima_actualizacion' => now()
            ]
        );

        return round($porcentajeTotal, 2);
    }

    /**
     * Obtiene estadísticas detalladas del progreso del curso
     * @param int $cursoId
     * @return array
     */
    public static function obtenerEstadisticasCurso($cursoId)
    {
        $curso = Cursos::with(['inscritos'])->find($cursoId);

        if (!$curso) {
            return [
                'porcentaje_total' => 0,
                'estudiantes_total' => 0,
                'estudiantes_completados' => 0,
                'estudiantes_en_progreso' => 0,
                'estudiantes_sin_iniciar' => 0
            ];
        }

        $totalEstudiantes = $curso->inscritos->count();
        $estudiantesCompletados = $curso->inscritos->where('progreso', 100)->count();
        $estudiantesSinIniciar = $curso->inscritos->where('progreso', 0)->count();
        $estudiantesEnProgreso = $totalEstudiantes - $estudiantesCompletados - $estudiantesSinIniciar;
        $porcentajeTotal = $curso->inscritos->avg('progreso') ?? 0;

        return [
            'porcentaje_total' => round($porcentajeTotal, 2),
            'estudiantes_total' => $totalEstudiantes,
            'estudiantes_completados' => $estudiantesCompletados,
            'estudiantes_en_progreso' => $estudiantesEnProgreso,
            'estudiantes_sin_iniciar' => $estudiantesSinIniciar
        ];
    }
}
