<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expositores extends BaseModel
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'curso_id',
        'nombre',
        'especialidad',
        'empresa',
        'biografia',
        'imagen',
        'linkedin',
    ];

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Cursos::class, 'curso_id');
    }

    // Accesor para la URL de la imagen
    public function getImagenUrlAttribute()
    {
        return $this->imagen ? asset('storage/' . $this->imagen) : asset('assets/img/default-avatar.jpg');
    }

    public function cursos()
    {
        return $this->belongsToMany(Cursos::class)
            ->withPivot(['cargo', 'tema', 'fecha_presentacion', 'orden'])
            ->orderByPivot('fecha_presentacion')
            ->withTimestamps();
    }
}
