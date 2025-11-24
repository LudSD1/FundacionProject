<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Inscritos extends BaseModel
{
    use HasFactory, SoftDeletes;



    protected $fillable = [
        'estudiante_id',
        'cursos_id',
        'progreso',
        'completado',
        'pago_completado'
    ];
    protected $softDelete = true;

    public function estudiantes(): BelongsTo
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }

    public function intentosCuestionarios(): HasMany
    {
        return $this->hasMany(IntentoCuestionario::class, 'inscrito_id');
    }

    public function notaEntrega(): HasMany
    {
        return $this->hasMany(NotaEntrega::class,  'inscripcion_id');
    }

    public function cursos(): BelongsTo
    {
        return $this->belongsTo(Cursos::class, 'cursos_id');
    }

    public function asistencia(): HasMany
    {
        return $this->hasMany(Asistencia::class,  'inscripcion_id');
    }




    public function boletin()
    {
        return $this->hasOne(Boletin::class, 'inscripcion_id');
    }

    public function certificado()
    {
        return $this->hasOne(Certificado::class, 'inscrito_id');
    }



    public function actividadCompletions()
    {
        return $this->hasMany(ActividadCompletion::class, 'inscritos_id');
    }


    public static function desbloquearSiguienteSubtema($inscritoId, $subtemaId)
    {
        $subtemaActual = Subtema::findOrFail($subtemaId);

        // Verificar si el subtema actual ya está completado
        if (!$subtemaActual->estaCompletado($inscritoId)) {
            return;
        }

        // Marcar el subtema como completado en la tabla intermedia
        DB::table('subtema_inscritos')
            ->where('inscrito_id', $inscritoId)
            ->where('subtema_id', $subtemaId)
            ->update(['completado' => true]);

        // Buscar el siguiente subtema del mismo tema
        $siguienteSubtema = Subtema::where('tema_id', $subtemaActual->tema_id)
            ->where('id', '>', $subtemaActual->id)
            ->orderBy('id', 'asc')
            ->first();

        if ($siguienteSubtema) {
            // Crear un registro en subtema_inscritos para desbloquearlo
            DB::table('subtema_inscritos')->updateOrInsert([
                'inscrito_id' => $inscritoId,
                'subtema_id' => $siguienteSubtema->id
            ], [
                'completado' => false
            ]);
        }

        // Actualizar el progreso general
        $inscrito = Inscritos::find($inscritoId);
        $inscrito->actualizarProgreso();
    }

    public function allAchievements()
    {
        return $this->hasManyThrough(
            UserAchievement::class,
            Inscritos::class,
            'estudiante_id', // Foreign key on Inscritos table
            'inscrito_id',   // Foreign key on UserAchievement table
            'id',            // Local key on User table
            'id'             // Local key on Inscritos table
        );
    }

    public function allXps()
    {
        return $this->hasManyThrough(
            UserXp::class,
            Inscritos::class,
            'estudiante_id', // Foreign key on Inscritos table
            'inscrito_id',   // Foreign key on UserXp table
            'id',            // Local key on User table
            'id'             // Local key on Inscritos table
        );
    }




}
