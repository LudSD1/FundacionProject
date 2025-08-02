<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CursoCalificacion extends BaseModel
{
    use HasFactory;
    protected $table = 'curso_calificaciones';
    protected $fillable = ['curso_id', 'user_id', 'puntuacion', 'comentario'];

    public function curso()
    {
        return $this->belongsTo(Cursos::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
