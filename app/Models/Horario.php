<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Horario extends BaseModel
{
    use HasFactory, SoftDeletes;
    protected $softDelete = true;

    protected $fillable = ['dia', 'hora_inicio', 'hora_fin'];

    public function cursos()
    {
        return $this->belongsToMany(Cursos::class, 'curso_horarios'); // Verifica el nombre de la tabla pivote
    }
}
