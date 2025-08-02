<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CursoImagen extends BaseModel
{
    use HasFactory, SoftDeletes;
    protected $table = 'curso_imagenes';
    protected $fillable = [
        'curso_id',
        'url',
        'titulo',
        'descripcion',
        'orden',
        'activo'
    ];

    public function curso()
    {
        return $this->belongsTo(Cursos::class);
    }


}
