<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAchievement extends BaseModel
{
    protected $table = 'user_achievements';

    protected $fillable = [
        'user_id',
        'achievement_id',
        'earned_at'
    ];

    protected $casts = [
        'earned_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function achievement()
    {
        return $this->belongsTo(Achievement::class);
    }
}
