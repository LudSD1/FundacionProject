<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotaEntrega extends BaseModel
{
    use HasFactory;

    protected $table = "calificaciones_entregas";
    protected $fillable = [
        'inscripcion_id',
        'actividad_id',
        'nota',
        'retroalimentacion',
    ];
    protected $softDelete = true;


    public function actividad(): BelongsTo
    {
        return $this->belongsTo(Actividad::class, 'actividad_id');
    }

    public function inscripcion(): BelongsTo
    {
        return $this->belongsTo(Inscritos::class, 'inscripcion_id');
    }

}
