<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Actividad;
use App\Models\Inscritos;
use App\Notifications\ActividadNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class NotificarCierreActividades extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'actividades:notificar-cierre';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notifica a los estudiantes sobre actividades que cerrarán pronto (dentro de las próximas 24 horas)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $manana = Carbon::now()->addDay();
        $ahora = Carbon::now();

        // Buscar actividades que vencen mañana y no han sido notificadas recientemente
        // Nota: Podrías añadir una columna 'cierre_notificado' a la tabla actividades si quieres ser más preciso
        $actividades = Actividad::whereNotNull('fecha_limite')
            ->where('fecha_limite', '>', $ahora)
            ->where('fecha_limite', '<=', $manana)
            ->where('es_publica', true)
            ->get();

        $this->info("Encontradas " . $actividades->count() . " actividades por vencer.");

        foreach ($actividades as $actividad) {
            $curso = $actividad->subtema->tema->curso;
            $inscritos = Inscritos::where('cursos_id', $curso->id)->with('estudiantes')->get();

            foreach ($inscritos as $inscrito) {
                // Verificar si ya completó la actividad para no molestar
                if (!$actividad->isCompletedByInscrito($inscrito->id)) {
                    if ($inscrito->estudiantes) {
                        $inscrito->estudiantes->notify(new ActividadNotification($actividad, 'cierre_proximo'));
                    }
                }
            }
        }

        $this->info('Notificaciones de cierre enviadas con éxito.');
        return Command::SUCCESS;
    }
}
