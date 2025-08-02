<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certificado extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['curso_id', 'inscrito_id', 'codigo_certificado', 'ruta_certificado'];

    public function curso()
    {
        return $this->belongsTo(Cursos::class, 'inscrito_id');
    }

    public function inscrito()
    {
        return $this->belongsTo(Inscritos::class, 'inscrito_id');
    }
}
