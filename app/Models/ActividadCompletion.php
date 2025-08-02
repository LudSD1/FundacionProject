<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ActividadCompletion extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'actividad_completions';

    protected $fillable = [
        'completable_type',
        'completable_id',
        'inscritos_id',
        'completed',
        'completed_at'
    ];

    protected $casts = [
        'completed' => 'boolean',
        'completed_at' => 'datetime'
    ];

    public function completable()
    {
        return $this->morphTo();
    }

    public function inscrito()
    {
        return $this->belongsTo(Inscritos::class);
    }

    protected static function booted()
    {
        static::saved(function ($actividadCompletion) {
            $inscrito = $actividadCompletion->inscrito;
            if ($inscrito) {
                $inscrito->actualizarProgreso();
            }
        });
    }

    public static function verificarProgresoSubtema($inscritoId, $subtemaId)
    {
        // Obtener el total de actividades del subtema
        $totalActividades = Actividad::where('subtema_id', $subtemaId)->count() +
            RecursoSubtema::where('subtema_id', $subtemaId)->count();

        // Contar actividades completadas
        $actividadesCompletadas = ActividadCompletion::where('inscritos_id', $inscritoId)
            ->whereHas('completable', function ($query) use ($subtemaId) {
                $query->where('subtema_id', $subtemaId);
            })
            ->count();

        // Calcular el porcentaje de progreso
        $porcentajeProgreso = ($totalActividades > 0)
            ? ($actividadesCompletadas / $totalActividades) * 100
            : 0;

        // Actualizar el progreso en la tabla `subtemas_inscritos`
        DB::table('subtemas_inscritos')
            ->where('inscrito_id', $inscritoId)
            ->where('subtema_id', $subtemaId)
            ->update(['progreso' => $porcentajeProgreso]);

        // Si todas las actividades están completas, marcar el subtema como completado
        if ($actividadesCompletadas >= $totalActividades) {
            DB::table('subtemas_inscritos')
                ->where('inscrito_id', $inscritoId)
                ->where('subtema_id', $subtemaId)
                ->update(['completado' => true]);

            // Verificar si el tema debe desbloquearse
            self::verificarProgresoTema($inscritoId, $subtemaId);
        }
    }


}
