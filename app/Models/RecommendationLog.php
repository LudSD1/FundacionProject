<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecommendationLog extends Model
{
    protected $fillable = [
        'user_id',
        'curso_id',
        'score',
        'rules_applied',
        'clicked',
        'clicked_at',
    ];

    protected $casts = [
        'rules_applied' => 'array',
        'clicked'       => 'boolean',
        'clicked_at'    => 'datetime',
        'score'         => 'decimal:4',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function curso()
    {
        return $this->belongsTo(Cursos::class, 'curso_id');
    }
}
