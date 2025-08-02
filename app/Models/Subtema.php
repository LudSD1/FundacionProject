<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subtema extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = "subtemas";

    protected $fillable = ['titulo_subtema', 'descripcion', 'tema_id', 'imagen', 'orden'];

    public function tema()
    {
        return $this->belongsTo(Tema::class);
    }

    public function actividades()
    {
        return $this->hasMany(Actividad::class, 'subtema_id');
    }

    public function recursos()
    {
        return $this->hasMany(RecursoSubtema::class, 'subtema_id');
    }

    public function notas()
{
    return $this->hasManyThrough(
        NotaEntrega::class,
        IntentoCuestionario::class,
        Actividad::class,
        'subtema_id', // Foreign key en Actividad
        'actividad_id', // Foreign key en NotaEntrega
        'id', // Local key en Subtema
        'id' // Local key en Actividad
    );
}

    public function estaDesbloqueado($inscritoId)
    {
        // Si es el primer subtema, está desbloqueado por defecto
        if ($this->esPrimerSubtema()) {
            return true;
        }

        // Obtener el subtema anterior
        $subtemaAnterior = $this->obtenerSubtemaAnterior();

        // Si no hay subtema anterior, el subtema actual no está desbloqueado
        if (!$subtemaAnterior) {
            return false;
        }

        // Verificar si el subtema anterior está desbloqueado y completado
        if (!$subtemaAnterior->estaDesbloqueado($inscritoId)) {
            return false;
        }

        // Verificar si todas las actividades del subtema anterior están completadas
        $actividadesCompletadas = $subtemaAnterior->actividadesCompletadas($inscritoId);
        $totalActividades = $subtemaAnterior->actividades()->count();

        return $actividadesCompletadas->count() === $totalActividades;
    }

    public function esPrimerSubtema()
    {
        return $this->orden === Subtema::where('tema_id', $this->tema_id)
            ->orderBy('orden', 'asc')
            ->value('orden');
    }

    public function obtenerSubtemaAnterior()
    {
        return Subtema::where('tema_id', $this->tema_id)
            ->where('orden', '<', $this->orden)
            ->orderBy('orden', 'desc')
            ->first();
    }

    // En el modelo Subtema
    public function actividadesCompletadas2()
    {
        return $this->hasMany(ActividadCompletion::class, 'completable_id')
            ->where('completable_type', Subtema::class);
    }

    // Modifica el método actividadesCompletadas para que sea más consistente
    public function actividadesCompletadas($inscritoId)
    {
        $inscrito_id = is_object($inscritoId) ? $inscritoId->id : $inscritoId;

        return ActividadCompletion::where('inscritos_id', $inscrito_id)
            ->whereIn('completable_id', $this->actividades()->pluck('id'))
            ->where('completable_type', Actividad::class)
            ->where('completed', true)
            ->get();
    }
}
