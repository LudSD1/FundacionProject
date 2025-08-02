<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class IntentoCuestionario extends BaseModel
{
    use HasFactory;
    protected $table = 'intentos_cuestionarios';
    protected $fillable = [
        'inscrito_id',
        'cuestionario_id',
        'intento_numero',
        'iniciado_en',
        'finalizado_en',
        'nota',
        'aprobado',
    ];

    protected $casts = [
        'iniciado_en' => 'datetime',
        'finalizado_en' => 'datetime',
        'aprobado' => 'boolean',
    ];
    public function respuestasEst() {
        return $this->hasMany(RespuestaEstudiante::class, 'intento_id');
    }

    public function cuestionario() {
        return $this->belongsTo(Cuestionario::class);
    }

    public function inscrito() {
        return $this->belongsTo(Inscritos::class, 'inscrito_id');
    }

    public static function intentosPerfectos($inscritoId, $cuestionarioId)
    {
        return DB::table('intentos_cuestionarios')
            ->where('inscrito_id', $inscritoId)
            ->where('cuestionario_id', $cuestionarioId)
            ->whereRaw('nota = (
                SELECT SUM(preguntas.puntaje)
                FROM preguntas
                WHERE preguntas.cuestionario_id = intentos_cuestionarios.cuestionario_id
            )')
            ->count();
    }

    // Método alternativo usando Query Builder
    public static function intentosPerfectosAlternativo($inscritoId, $cuestionarioId)
    {
        return DB::table('intentos_cuestionarios as ic')
            ->join('preguntas as p', 'p.cuestionario_id', '=', 'ic.cuestionario_id')
            ->where('ic.inscrito_id', $inscritoId)
            ->where('ic.cuestionario_id', $cuestionarioId)
            ->groupBy('ic.id', 'ic.nota')
            ->havingRaw('ic.nota = SUM(p.puntaje)')
            ->count();
    }
}
