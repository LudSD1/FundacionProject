<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respuesta extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'pregunta_id',
        'contenido',
        'es_correcta',
    ];

    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class);
    }


}
