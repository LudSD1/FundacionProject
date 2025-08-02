<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Opcion extends BaseModel
{
    use SoftDeletes;

    protected $table = "opciones";

    protected $fillable = [
        'pregunta_id',
        'texto',
        'es_correcta' // Booleano para indicar si es la respuesta correcta
    ];

    /**
     * Relación con la pregunta.
     */
    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class);
    }
}
