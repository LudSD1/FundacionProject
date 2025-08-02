<?php

namespace App\Models;

use App\Events\UserLeveledUp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserXP extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_xp';

    protected $fillable = [
        'inscrito_id',
        'current_xp',
        'total_xp_earned',
        'current_level',
        'last_activity_at'
    ];

    protected $casts = [
        'last_activity_at' => 'datetime'
    ];

    public function inscrito()
    {
        return $this->belongsTo(Inscritos::class, 'inscrito_id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'current_level', 'level_number');
    }

    public function addXP($amount, $source = null)
    {
        $this->current_xp += $amount;
        $this->total_xp_earned += $amount;
        $this->last_activity_at = now();
        $this->save();

        // Verificar si el usuario subió de nivel
        $this->checkLevelUp();
    }

    protected function checkLevelUp()
    {
        $nextLevel = Level::where('level_number', '>', $this->current_level)
            ->where('required_xp', '<=', $this->current_xp)
            ->orderBy('level_number')
            ->first();

        if ($nextLevel) {
            $this->current_level = $nextLevel->level_number;
            $this->save();

            // Disparar evento de subida de nivel
            event(new UserLeveledUp($this->inscrito, $nextLevel));
        }
    }
}
