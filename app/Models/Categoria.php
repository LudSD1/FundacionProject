<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'categoria';
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'parent_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    public function children()
    {
        return $this->hasMany(Categoria::class, 'parent_id');
    }

    // 🔁 Relación: una categoría puede tener una categoría padre
    public function parent()
    {
        return $this->belongsTo(Categoria::class, 'parent_id');
    }

    public function hasActiveChildren()
    {
        return $this->children()->whereNull('deleted_at')->exists();
    }

    // 🔁 Relación: muchas categorías pueden pertenecer a muchos cursos
    public function cursos()
    {
        return $this->belongsToMany(Cursos::class, 'curso_categoria', 'categoria_id', 'curso_id');
    }
    




}
