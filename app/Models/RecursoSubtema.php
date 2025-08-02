<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecursoSubtema extends BaseModel
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'recurso_subtemas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombreRecurso',
        'descripcionRecursos',
        'tipoRecurso',
        'archivoRecurso',
        'cursos_id',
        'subtema_id',
        'progreso',
        'clics',
    ];

    public function isViewedByInscrito($inscritoId)
    {
        return ActividadCompletion::where('completable_type', RecursoSubtema::class)
            ->where('completable_id', $this->id)
            ->where('inscritos_id', $inscritoId)
            ->where('completed', true)
            ->exists();
    }


    protected $casts = [
        'progreso' => 'boolean',
        'clics' => 'integer',
    ];


    public function subtema()
    {
        return $this->belongsTo(Subtema::class, 'subtema_id');
    }

    // Relación polimórfica con completions
    public function completions()
    {
        return $this->morphMany(ActividadCompletion::class, 'completable');
    }

    // Verificar si está completada por un usuario
    public function isCompletedByUser($inscritosId)
    {
        return $this->completions()
            ->where('inscritos_id', $inscritosId)
            ->where('completed', true)
            ->exists();
    }
}
