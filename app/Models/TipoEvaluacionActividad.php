<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEvaluacionActividad extends BaseModel
{
    use HasFactory;

    protected $table = 'actividad_tipos_evaluacion';
    protected $fillable = [
        'actividad_id',
        'tipo_evaluacion_id',
        'puntaje_maximo',
        'es_obligatorio',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function actividad()
    {
        return $this->belongsTo(Actividad::class);
    }
    public function tipoEvaluacion()
    {
        return $this->belongsTo(TipoEvaluacion::class);
    }


}
