<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class ForoMensaje extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'foros_mensajes';

    public function estudiantes(): BelongsTo
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }

    public function foro(): BelongsTo
    {
        return $this->belongsTo(Foro::class, 'foro_id');
    }
}
