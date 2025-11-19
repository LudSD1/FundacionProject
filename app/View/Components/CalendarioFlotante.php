<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class CalendarioFlotante extends Component
{
    public $eventos;
    public $posicion;
    public $mostrarMiniCalendario;

    /**
     * Create a new component instance.
     *
     * @param string $posicion Posición del calendario ('bottom-right', 'bottom-left', 'top-right', 'top-left')
     * @param bool $mostrarMiniCalendario Mostrar mini calendario en vista colapsada
     */
    public function __construct(
        $posicion = 'bottom-right',
        $mostrarMiniCalendario = true
    ) {
        $this->posicion = $posicion;
        $this->mostrarMiniCalendario = $mostrarMiniCalendario;
        $this->eventos = $this->cargarEventos();
    }

    /**
     * Cargar eventos del usuario
     */
    private function cargarEventos()
    {
        if (!Auth::check()) {
            return collect([]);
        }

        $user = Auth::user();

        $inscripciones = \App\Models\Inscritos::where('estudiante_id', $user->id)
            ->with(['cursos.horarios'])
            ->get();

        $cursoIds = $inscripciones->pluck('cursos.id')->filter()->unique()->values();

        if ($cursoIds->isEmpty()) {
            return collect([]);
        }

        $actividades = \App\Models\Actividad::whereHas('subtema.tema', function ($q) use ($cursoIds) {
                $q->whereIn('curso_id', $cursoIds);
            })
            ->with(['subtema.tema.curso.horarios', 'tipoActividad'])
            ->where('fecha_limite', '>=', now()->subMonths(1))
            ->get();

        $inscritoIds = $inscripciones->pluck('id')->all();

        return $actividades->map(function ($actividad) use ($inscritoIds) {
            $curso = optional(optional($actividad->subtema)->tema)->curso;

            $completada = \App\Models\ActividadCompletion::whereIn('inscritos_id', $inscritoIds)
                ->where('completable_id', $actividad->id)
                ->where('completable_type', \App\Models\Actividad::class)
                ->where('completed', true)
                ->exists();

            return [
                'id' => $actividad->id,
                'title' => $actividad->titulo,
                'start' => $actividad->fecha_limite,
                'url' => route('actividad.show', encrypt($actividad->id)),
                'extendedProps' => [
                    'tipo' => optional($actividad->tipoActividad)->nombre,
                    'estado' => $completada ? 'Entregada' : 'Pendiente',
                    'curso' => optional($curso)->nombreCurso,
                    'curso_id' => optional($curso)->id,
                    'nombreCurso' => optional($curso)->nombreCurso,
                    'descripcion' => $actividad->descripcion,
                    'puntos' => $actividad->puntaje_maximo ?? null,
                    'prioridad' => $this->calcularPrioridadActividad($actividad),
                    'horarios' => optional($curso)->horarios?->map(function ($horario) {
                        return [
                            'dia' => $horario->dia,
                            'hora_inicio' => $horario->hora_inicio,
                            'hora_fin' => $horario->hora_fin,
                        ];
                    })->toArray() ?? [],
                ]
            ];
        });
    }

    /**
     * Calcular prioridad basada en fecha límite
     */
    private function calcularPrioridadActividad($actividad)
    {
        $diasRestantes = now()->diffInDays($actividad->fecha_limite, false);

        if ($diasRestantes < 0) {
            return 'Vencida';
        } elseif ($diasRestantes <= 1) {
            return 'Alta';
        } elseif ($diasRestantes <= 3) {
            return 'Media';
        }

        return 'Baja';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.calendario-flotante');
    }
}
