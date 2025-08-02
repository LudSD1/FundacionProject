<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Collection;

class AsistenciasSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $inscritos;

    public function __construct($inscritos)
    {
        $this->inscritos = $inscritos;
    }

    public function collection()
    {
        return collect($this->inscritos)->map(function ($inscrito) {
            $totalAsistencias = $inscrito->asistencia->count();
            $presentes = $inscrito->asistencia->where('tipoAsitencia', 'Presente')->count();
            $retrasos = $inscrito->asistencia->where('tipoAsitencia', 'Retraso')->count();
            $faltas = $inscrito->asistencia->where('tipoAsitencia', 'Falta')->count();
            $licencias = $inscrito->asistencia->where('tipoAsitencia', 'Licencia')->count();
            
            $porcentajeAsistencia = $totalAsistencias > 0 
                ? round((($presentes + $retrasos + $licencias) / $totalAsistencias) * 100, 2)
                : 0;

            return [
                'curso' => $inscrito->cursos->nombreCurso ?? 'Sin curso',
                'nombre' => $inscrito->estudiantes->name ?? 'Sin nombre',
                'apellido_paterno' => $inscrito->estudiantes->lastname1 ?? '',
                'apellido_materno' => $inscrito->estudiantes->lastname2 ?? '',
                'total_asistencias' => $totalAsistencias,
                'presentes' => $presentes,
                'retrasos' => $retrasos,
                'faltas' => $faltas,
                'licencias' => $licencias,
                'porcentaje_asistencia' => $porcentajeAsistencia . '%'
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Curso',
            'Estudiante',
            'Apellido Paterno',
            'Apellido Materno',
            'Total Asistencias',
            'Presentes',
            'Retrasos',
            'Faltas',
            'Licencias',
            'Porcentaje Asistencia'
        ];
    }

    public function title(): string
    {
        return 'Asistencias';
    }
} 