<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntregaArchivo extends BaseModel
{
    use HasFactory;

    protected $table = "entregas_archivos";


    protected $fillable = [
        'user_id',
        'actividad_id',
        'archivo',
        'comentario',
        'comentario',
        'fecha_entrega',
    ];

    protected $softDelete = true;
    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function notas()
{
    return $this->hasMany(NotaEntrega::class, 'entrega_id');
}

    public function actividad(): BelongsTo
    {
        return $this->belongsTo(Actividad::class, 'actividad_id');
    }
}
