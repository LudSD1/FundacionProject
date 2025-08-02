<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cursos_Horario extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = "cursos_horarios";
    protected $fillable = ['curso_id', 'horario_id'];

    public function curso()
    {
        return $this->belongsTo(Cursos::class, 'curso_id');
    }


    public function horario()
    {
        return $this->belongsTo(Horario::class, 'horario_id');
    }

}
