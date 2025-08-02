<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class ForoMensaje extends BaseModel
{



    use HasFactory,SoftDeletes;
    protected $softDelete = true;

    protected $fillable = [
        'tituloMensaje',
        'mensaje',
        'foro_id',
        'estudiante_id',
        'respuesta_a',
    ];


    protected $table = 'foros_mensajes';

    public function estudiantes(): BelongsTo
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }

    public function foro(): BelongsTo
    {
        return $this->belongsTo(Foro::class, 'foro_id');
    }

    public function mensajePrincipal()
    {
        return $this->belongsTo(ForoMensaje::class, 'respuesta_a');
    }

    // Respuestas a este mensaje
    public function respuestas()
    {
        return $this->hasMany(ForoMensaje::class, 'respuesta_a');
    }
}
