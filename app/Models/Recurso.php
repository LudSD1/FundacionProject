<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCompletions;

class Recurso extends BaseModel
{
 

    protected $fillable = [
        'nombreRecurso',
        'descripcionRecursos',
        'archivoRecurso',
        'tipoRecurso',
        'subtema_id'
    ];

    public function subtema()
    {
        return $this->belongsTo(Subtema::class);
    }

    public function isViewedByInscrito($inscritoId)
    {
        return $this->completions()
            ->where('inscrito_id', $inscritoId)
            ->exists();
    }
}
