<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CursoReporteExport implements WithMultipleSheets
{
    protected $inscritos;
    protected $actividades;

    public function __construct($inscritos, $actividades)
    {
        $this->inscritos = $inscritos;
        $this->actividades = $actividades;
    }

    public function sheets(): array
    {
        return [
            'Asistencias' => new AsistenciasSheet($this->inscritos),
            'Notas' => new NotasSheet($this->inscritos, $this->actividades),
            'Promedios' => new PromediosSheet($this->inscritos, $this->actividades)
        ];
    }
} 