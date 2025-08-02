<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XpEvent extends BaseModel
{
    protected $table = 'xp_events';

    protected $fillable = [
        'users_id',
        'curso_id',
        'type',
        'xp'
    ];

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    // Relación con el curso
    public function curso()
    {
        return $this->belongsTo(Cursos::class, 'curso_id');
    }
}
