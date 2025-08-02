<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoEvaluacion extends BaseModel
{
    use HasFactory;
    protected $table = 'tipo_evaluaciones';
    protected $fillable = [
        'nombre',
        'slug',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public function actividades()
    {
        return $this->belongsToMany(Actividad::class, 'actividad_tipos_evaluacion')
            ->withPivot('puntaje_maximo', 'es_obligatorio')
            ->withTimestamps();
    }
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            $query->where('nombre', 'like', '%' . $search . '%');
        });
    }
    public function scopeOrderByNombre($query, $direction = 'asc')
    {
        return $query->orderBy('nombre', $direction);
    }
    public function scopeOrderByCreatedAt($query, $direction = 'asc')
    {
        return $query->orderBy('created_at', $direction);
    }
    public function scopeOrderByUpdatedAt($query, $direction = 'asc')
    {
        return $query->orderBy('updated_at', $direction);
    }
}
