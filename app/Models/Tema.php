<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tema extends BaseModel
{
    use SoftDeletes, HasFactory;

    protected $table = "temas";

    protected $fillable = ['titulo_tema', 'descripcion', 'imagen', 'curso_id', 'orden'];

    public function subtemas()
    {
        return $this->hasMany(Subtema::class , 'tema_id');
    }

    public function curso()
    {
        return $this->belongsTo(Cursos::class, 'curso_id');
    }

    // En el modelo Tema
    public function estaDesbloqueado($inscritoId)
    {
        // Asegurarse de que $inscritoId es un ID válido
        $inscrito_id = is_object($inscritoId) ? $inscritoId->id : $inscritoId;

        // Si es el primer tema, está desbloqueado por defecto
        if ($this->esPrimerTema()) {
            return true;
        }

        // Obtener el tema anterior
        $temaAnterior = $this->obtenerTemaAnterior();

        // Si no hay tema anterior, el tema actual no está desbloqueado
        if (!$temaAnterior) {
            return false;
        }

        // Verificar si el tema anterior está desbloqueado
        if (!$temaAnterior->estaDesbloqueado($inscrito_id)) {
            return false;
        }

        // Verificar la completitud de los subtemas del tema anterior
        foreach ($temaAnterior->subtemas as $subtema) {
            // Verificar si todas las actividades del subtema están completadas
            $actividadesCompletadas = $subtema->actividadesCompletadas($inscrito_id);
            $totalActividades = $subtema->actividades()->count();

            if ($actividadesCompletadas->count() !== $totalActividades) {
                return false;
            }
        }

        return true;
    }

    public function actividadesCompletadas($inscritoId)
    {
        $subtemas = $this->subtemas; // Obtener los subtemas del tema
        $completadas = collect();

        foreach ($subtemas as $subtema) {
            $completadas = $completadas->merge($subtema->actividadesCompletadas($inscritoId));
        }

        return $completadas;
    }


    public function esPrimerTema()
    {
        return $this->orden === Tema::where('curso_id', $this->curso_id)
            ->orderBy('orden', 'asc')
            ->value('orden');
    }

    public function obtenerTemaAnterior()
    {
        return Tema::where('curso_id', $this->curso_id)
            ->where('orden', '<', $this->orden)
            ->orderBy('orden', 'desc')
            ->first();
    }




}
