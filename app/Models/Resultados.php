<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resultados extends BaseModel
{
    use HasFactory;


    protected $fillable = [
        'cuestionario_id',
        'estudiante_id',
        'intento',
        'puntaje_obtenido',
        'puntaje_total',
    ];


}
