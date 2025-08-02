<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocentesTrabajos extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $softDelete = true;
    protected $fillable = ['docente_id', 'empresa', 'cargo', 'fecha_inicio', 'fecha_fin', 'contacto_ref'];

    public function docente(): BelongsTo {

        return $this->belongsTo(User::class, 'docente_id');

    }

}
