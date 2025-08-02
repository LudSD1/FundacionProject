<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cuestionario extends BaseModel
{
    use HasFactory;

    protected $table = 'cuestionarios';

    protected $fillable = [
        'actividad_id',
        'mostrar_resultados',
        'max_intentos',
        'tiempo_limite',
    ];



    /**
     * Relación con el subtema.
     */
    public function subtema()
    {
        return $this->belongsTo(Subtema::class);
    }

    public function actividad() {
        return $this->belongsTo(Actividad::class, 'actividad_id');
    }

    public function preguntas() {
        return $this->hasMany(Pregunta::class);
    }

    public function intentos() {
        return $this->hasMany(IntentoCuestionario::class, 'cuestionario_id');
    }


    // Relación polimórfica con completions
    public function completions()
    {
        return $this->morphMany(ActividadCompletion::class, 'completable');
    }

    public function actividadCompletions()
    {
        return $this->hasMany(ActividadCompletion::class, 'completable_id')
            ->where('completable_type', self::class);
    }

    // Verificar si está completada por un usuario
    public function isCompletedByInscrito($inscritosId)
    {
        return $this->completions()
            ->where('inscritos_id', $inscritosId)
            ->where('completed', true)
            ->exists();
    }


}
