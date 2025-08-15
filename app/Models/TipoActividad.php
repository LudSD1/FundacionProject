<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoActividad extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
    ];

    protected $table = 'tipo_actividades';

    public function actividades()
    {
        return $this->hasMany(Actividad::class, 'tipo_actividad_id');
    }
}
