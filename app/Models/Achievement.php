<?php

namespace App\Models;

use App\Services\XPService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Achievement extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'achievements';

    protected $fillable = [
        'title',
        'description',
        'icon',
        'type',
        'requirement_value',
        'xp_reward',
        'is_secret'
    ];

    protected $casts = [
        'is_secret' => 'boolean',
        'requirement_value' => 'integer',
        'xp_reward' => 'integer'
    ];

    /**
     * Los usuarios que han desbloqueado este logro
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'achievement_user', 'achievement_id', 'user_id')
                    ->withPivot('earned_at')
                    ->withTimestamps();
    }

    /**
     * Los inscritos que han desbloqueado este logro
     */
    public function inscritos()
    {
        return $this->belongsToMany(Inscritos::class, 'achievement_inscrito', 'achievement_id', 'inscrito_id')
                    ->withPivot('earned_at')
                    ->withTimestamps();
    }

    /**
     * Verifica si un usuario ha desbloqueado este logro
     */
    public function isUnlockedBy(User $user)
    {
        return $this->users()->where('user_id', $user->id)->exists();
    }

    /**
     * Desbloquea el logro para un usuario
     */
    public function unlockFor(User $user)
    {
        if (!$this->isUnlockedBy($user)) {
            $this->users()->attach($user->id, [
                'earned_at' => now()
            ]);

            // Si el usuario tiene XP, le añadimos la recompensa
            if ($user->userXP) {
                $user->userXP->addXP($this->xp_reward, 'achievement');
            }

            // Aquí podrías disparar eventos o notificaciones
            // event(new AchievementUnlocked($this, $user));
        }
    }

    /**
     * Verifica si un inscrito ha desbloqueado este logro
     */
    public function isUnlockedByInscrito(Inscritos $inscrito)
    {
        return $this->inscritos()->where('inscrito_id', $inscrito->id)->exists();
    }

    /**
     * Otorga el logro a un inscrito
     */
    public function award(Inscritos $inscrito)
    {
        if (!$this->isUnlockedByInscrito($inscrito)) {
            $this->inscritos()->attach($inscrito->id, [
                'earned_at' => now()
            ]);

            // Añadir XP al inscrito
            if ($this->xp_reward > 0) {
                // Asumiendo que tienes un servicio de XP
                app(XPService::class)->addXP($inscrito, $this->xp_reward, "Logro desbloqueado: {$this->title}");
            }

            // Aquí podrías disparar eventos o notificaciones si lo necesitas
            // event(new AchievementUnlocked($this, $inscrito));
        }
    }

    public static function createWithSlug(array $data)
    {
        if (!isset($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        return static::create($data);
    }
}
