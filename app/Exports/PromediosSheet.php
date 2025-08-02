<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Collection;

class PromediosSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $inscritos;
    protected $actividades;

    public function __construct($inscritos, $actividades)
    {
        $this->inscritos = $inscritos;
        $this->actividades = $actividades;
    }

    public function collection()
    {
        return collect($this->inscritos)->map(function ($inscrito) {
            $notasActividades = [];
            
            foreach ($this->actividades as $actividad) {
                $notaEntrega = $actividad->calificacionesEntregas
                    ->where('inscripcion_id', $inscrito->id)
                    ->first();
                if ($notaEntrega) {
                    $notasActividades[] = $notaEntrega->nota;
                }

                if ($actividad->intentosCuestionarios->isNotEmpty()) {
                    $mejorIntento = $actividad->intentosCuestionarios
                        ->where('inscrito_id', $inscrito->id)
                        ->sortByDesc('nota')
                        ->first();
                    if ($mejorIntento) {
                        $notasActividades[] = $mejorIntento->nota;
                    }
                }
            }

            $promedioActividades = !empty($notasActividades) 
                ? round(array_sum($notasActividades) / count($notasActividades), 2)
                : 0;

            $totalAsistencias = $inscrito->asistencia->count();
            $asistenciasValidas = $inscrito->asistencia
                ->whereIn('tipoAsitencia', ['Presente', 'Retraso', 'Licencia'])
                ->count();
            $porcentajeAsistencia = $totalAsistencias > 0 
                ? round(($asistenciasValidas / $totalAsistencias) * 100, 2)
                : 0;

            $notaFinal = round(($promedioActividades * 0.7) + ($porcentajeAsistencia * 0.3), 2);

            $estado = 'Reprobado';
            if ($notaFinal >= 76) {
                $estado = 'Experto';
            } elseif ($notaFinal >= 66) {
                $estado = 'Habilidoso';
            } elseif ($notaFinal >= 51) {
                $estado = 'Aprendiz';
            }

            return [
                'nombre' => $inscrito->estudiantes->name ?? 'Sin nombre',
                'apellido_paterno' => $inscrito->estudiantes->lastname1 ?? '',
                'apellido_materno' => $inscrito->estudiantes->lastname2 ?? '',
                'promedio_actividades' => $promedioActividades,
                'porcentaje_asistencia' => $porcentajeAsistencia . '%',
                'nota_final' => $notaFinal,
                'estado' => $estado
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Estudiante',
            'Apellido Paterno',
            'Apellido Materno',
            'Promedio Actividades',
            'Porcentaje Asistencia',
            'Nota Final',
            'Estado'
        ];
    }

    public function title(): string
    {
        return 'Promedios';
    }
} 