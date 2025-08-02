<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Foro extends BaseModel
{
    use HasFactory, SoftDeletes;
    protected $softDelete = true;


    public function cursos() :BelongsTo

    {

        return $this->belongsTo(Cursos::class, 'cursos_id');

    }


    public function foromensaje(): HasMany
    {
        return $this->hasMany(ForoMensaje::class, 'foro_id');
    }

        // Relación con el estudiante (usuario)
        public function estudiante()
        {
            return $this->belongsTo(User::class, 'estudiante_id');
        }

        // Relación con el foro
        public function foro()
        {
            return $this->belongsTo(Foro::class, 'foro_id');
        }






    protected static function boot()
    {
        parent::boot();

        // Evento que se dispara al intentar eliminar el modelo
        static::deleting(function ($foro) {
            // Aquí puedes agregar lógica para manejar la eliminación del modelo
            // por ejemplo, eliminar también los mensajes asociados en la relación
            $foro->foromensaje()->delete();
        });

        // Evento que se dispara al intentar restaurar el modelo eliminado
        static::restoring(function ($foro) {
            // Aquí puedes agregar lógica para manejar la restauración del modelo
            // por ejemplo, restaurar también los mensajes asociados en la relación
            $foro->foromensaje()->withTrashed()->restore();
        });
    }

    public function completions()
{
    return $this->morphMany(ActividadCompletion::class, 'completable');
}







}
