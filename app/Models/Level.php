<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Level extends BaseModel
{
    use SoftDeletes;

    protected $table = 'levels';

    protected $fillable = [
        'level_number',
        'required_xp',
        'title',
        'description',
        'badge_image'
    ];

    /**
     * Obtiene los usuarios que están en este nivel
     */
    public function users()
    {
        return $this->hasMany(UserXP::class, 'current_level', 'level_number');
    }

    /**
     * Obtiene el siguiente nivel
     */
    public function nextLevel()
    {
        return static::where('level_number', '>', $this->level_number)
                    ->orderBy('level_number', 'asc')
                    ->first();
    }

    /**
     * Calcula el XP necesario para el siguiente nivel
     */
    public function xpToNextLevel()
    {
        $nextLevel = $this->nextLevel();
        return $nextLevel ? $nextLevel->required_xp - $this->required_xp : 0;
    }

    public static function getNextLevel($currentXp)
    {
        return self::where('required_xp', '>', $currentXp)
            ->orderBy('required_xp', 'asc')
            ->first();
    }

    public static function getCurrentLevel($currentXp)
    {
        return self::where('required_xp', '<=', $currentXp)
            ->orderBy('required_xp', 'desc')
            ->first();
    }
}
