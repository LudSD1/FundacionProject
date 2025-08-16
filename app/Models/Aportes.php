<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aportes extends BaseModel
{
    use HasFactory, SoftDeletes;
    protected $softDelete = true;


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Cursos::class,  'cursos_id')->withTrashed();
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($aporte) {
            // Puedes agregar lógica de eliminación suave en cascada aquí si hay relaciones adicionales.
        });

        static::restoring(function ($aporte) {
            // Puedes agregar lógica de restauración suave aquí si es necesario.
        });
    }



}
