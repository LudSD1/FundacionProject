<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tema extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = "temas";

    protected $fillable = ['titulo_tema', 'descripcion','imagen','curso_id'];

    public function subtemas()
    {
        return $this->hasMany(Subtema::class,'tema_id','id');
    }
    public function curso()
{
    return $this->belongsTo(Cursos::class, 'curso_id'); // Asegura que el campo sea correcto
}

public function isDisponible()
{
    return !$this->bloqueado;
}









}
