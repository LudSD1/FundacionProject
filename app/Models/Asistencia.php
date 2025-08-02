<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class Asistencia extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'asistencia';
    protected $softDelete = true;

    public function cursos(): BelongsTo
    {
        return $this->belongsTo(Cursos::class, 'curso_id');
    }

    public function inscritos(): BelongsTo
    {
        return $this->belongsTo(Inscritos::class, 'inscripcion_id')->withTrashed();
    }

    // Configuración para soft deletes en cascada
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($asistencia) {
            // Puedes agregar lógica de eliminación suave en cascada aquí si hay relaciones adicionales.
        });

        static::restoring(function ($asistencia) {
            // Puedes agregar lógica de restauración suave aquí si es necesario.
        });
    }
}
