<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Actividad extends BaseModel
{

    use HasFactory, SoftDeletes;

    protected $table = 'actividades';
    protected $fillable = [
        'subtema_id',
        'titulo',
        'descripcion',
        'fecha_inicio',
        'fecha_limite',
        'orden',
        'es_publica',
        'es_obligatoria',
        'tipo_actividad_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'fecha_inicio' => 'datetime',
        'fecha_limite' => 'datetime',
    ];

    

    public function subtema()
    {
        return $this->belongsTo(Subtema::class, 'subtema_id');
    }
    public function tipoActividad()
    {
        return $this->belongsTo(TipoActividad::class, 'tipo_actividad_id');
    }





    public function getPuntajeMaximoAttribute()
    {
        return $this->tiposEvaluacion->sum('pivot.puntaje_maximo');
    }

    public function intentosCuestionarios()
    {
        return $this->hasManyThrough(
            IntentoCuestionario::class,
            Cuestionario::class,
            'actividad_id', // Foreign key en Cuestionario
            'cuestionario_id', // Foreign key en IntentoCuestionario
            'id', // Local key en Actividad
            'id' // Local key en Cuestionario
        );
    }




    public function calificacionesEntregas()
    {
        return $this->hasMany(NotaEntrega::class, 'actividad_id');
    }


    public function tiposEvaluacion(): BelongsToMany
    {
        return $this->belongsToMany(
            TipoEvaluacion::class,
            'actividad_tipos_evaluacion', // nombre de la tabla pivot
            'actividad_id',              // foreign key de este modelo
            'tipo_evaluacion_id'         // foreign key del modelo relacionado
        )
        ->withPivot([
            'puntaje_maximo',
            'es_obligatorio'
        ])
        ->withTimestamps(); // incluye created_at y updated_at de la pivot
    }


    public function cuestionarios()
    {
        return $this->hasOne(Cuestionario::class, );
    }


    public function cuestionario()
    {
        return $this->hasOne(Cuestionario::class, );
    }

    public function entregas()
    {
        return $this->hasMany(EntregaArchivo::class);
    }

    public function notaEntrega()
{
    return $this->hasMany(NotaEntrega::class, 'actividad_id');
}

    public function completions()
    {
        return $this->morphMany(ActividadCompletion::class, 'completable');
    }
    public function isCompletedByInscrito($inscritoId)
    {
        return $this->completions()
            ->where('inscritos_id', $inscritoId)
            ->where('completed', true)
            ->exists();
    }
}
