<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Collection;

class NotasSheet implements FromCollection, WithHeadings, WithTitle
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
        $data = collect();

        foreach ($this->inscritos as $inscrito) {
            foreach ($this->actividades as $actividad) {
                try {
                    $nota = 0;
                    $estado = 'Pendiente';
                    $fecha = '-';

                    if ($actividad->calificacionesEntregas->isNotEmpty()) {
                        $notaEntrega = $actividad->calificacionesEntregas
                            ->where('inscripcion_id', $inscrito->id)
                            ->first();

                        if ($notaEntrega) {
                            $nota = $notaEntrega->nota ?? 0;
                            $estado = 'Entregado';
                            $fecha = optional($notaEntrega->created_at)->format('Y-m-d H:i:s');
                        }
                    }

                    if (!$nota && $actividad->intentosCuestionarios->isNotEmpty()) {
                        $mejorIntento = $actividad->intentosCuestionarios
                            ->where('inscrito_id', $inscrito->id)
                            ->sortByDesc('nota')
                            ->first();

                        if ($mejorIntento) {
                            $nota = $mejorIntento->nota ?? 0;
                            $estado = 'Mejor intento (#' . $mejorIntento->intento_numero . ')';
                            $fecha = optional($mejorIntento->finalizado_en)->format('Y-m-d H:i:s');
                        }
                    }

                    $data->push([
                        'nombre' => $inscrito->estudiantes->name ?? 'Sin nombre',
                        'apellido_paterno' => $inscrito->estudiantes->lastname1 ?? '',
                        'apellido_materno' => $inscrito->estudiantes->lastname2 ?? '',
                        'tema' => $actividad->subtema->tema->titulo_tema ?? 'Sin tema',
                        'subtema' => $actividad->subtema->titulo_subtema ?? 'Sin subtema',
                        'tipo_actividad' => $actividad->tipoActividad->nombre ?? 'Sin tipo',
                        'actividad' => $actividad->titulo ?? 'Sin título',
                        'nota' => $nota,
                        'estado' => $estado,
                        'fecha' => $fecha
                    ]);

                } catch (\Exception $e) {
                    \Log::error("Error procesando actividad {$actividad->id} para inscrito {$inscrito->id}: " . $e->getMessage());
                    continue;
                }
            }
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Estudiante',
            'Apellido Paterno',
            'Apellido Materno',
            'Tema',
            'Subtema',
            'Tipo Actividad',
            'Actividad',
            'Nota',
            'Estado',
            'Fecha'
        ];
    }

    public function title(): string
    {
        return 'Notas';
    }
} 