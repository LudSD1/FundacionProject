<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespuestaEstudiante extends BaseModel
{
    use HasFactory;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'respuestas_estudiantes';

    protected $fillable = [

        'inscrito_id',
        'intento_id',
        'pregunta_id',
        'respuesta',
        'es_correcta',
    ];


    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class);
    }
    public function inscrito()
    {
        return $this->belongsTo(Inscritos::class);
    }

    public function intento()
    {
        return $this->belongsTo(IntentoCuestionario::class, 'intento_id');
    }

    public function scopeCorrectas($query)
    {
        return $query->where('es_correcta', true);
    }

    public function scopeIncorrectas($query)
    {
        return $query->where('es_correcta', false);
    }



}
