<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pregunta extends BaseModel
{
    use SoftDeletes;

    protected $table = 'preguntas';

    protected $fillable = [
        'cuestionario_id',
        'enunciado',
        'tipo',
        'puntaje',
    ];


    public function cuestionario()
    {
        return $this->belongsTo(Cuestionario::class, 'cuestionario_id');
    }


    public function respuestas()
    {
        return $this->hasMany(Respuesta::class, 'pregunta_id');
    }


}
