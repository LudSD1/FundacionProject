<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cursos extends BaseModel
{
    use HasFactory, SoftDeletes;
    protected $softDelete = true;


    protected $fillable = [
        'nombreCurso',
        'codigoCurso',
        'descripcionC',
        'fecha_ini',
        'fecha_fin',
        'archivoContenidodelCurso',
        'notaAprobacion',
        'formato',
        'estado',
        'tipo',
        'docente_id',
        'edadDir_id',
        'niveles_id',
        'precio',
        'imagen',
        'certificados_activados',
        'duracion',
        'cupos',
        'visibilidad',
        'youtube_url',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'estado' => 'boolean',
        'certificado' => 'boolean',
    ];
    protected $dates = [
        'fecha_ini',
        'fecha_fin',
        'deleted_at',
    ];

    protected $appends = [
        'duracion_formateada',
    ];
    public function getDuracionFormateadaAttribute()
    {
        return gmdate("H:i:s", $this->duracion);
    }

    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'curso_categoria', 'curso_id', 'categoria_id');
    }

    public function getCertificadoAttribute($value)
    {
        return $value === '1' ? 'Con certificado' : 'Sin certificado';
    }
    public function setCertificadoAttribute($value)
    {
        $this->attributes['certificado'] = $value === 'Con certificado' ? '1' : '0';
    }
    protected $table = 'cursos';


    public function getCertificadosDisponiblesAttribute()
    {
        return $this->certificados_activados && now()->lessThanOrEqualTo(Carbon::parse($this->fecha_fin)->endOfDay());
    }

    public function certificateTemplate()
    {
        return $this->hasOne(CertificateTemplate::class, 'curso_id');
    }

    public function docente(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function horarios()
    {
        return $this->belongsToMany(Horario::class, 'curso_horarios');
    }
    public function inscritos(): HasMany
    {
        return $this->hasMany(Inscritos::class,  'cursos_id', 'id');
    }
    public function foros(): HasMany
    {
        return $this->hasMany(Foro::class,  'id', 'cursos_id');
    }

    public function recursos(): HasMany
    {
        return $this->hasMany(Recursos::class,  'id', 'cursos_id');
    }
    public function asistencia(): HasMany
    {
        return $this->hasMany(Asistencia::class,  'id', 'curso_id');
    }

    public function temas()
    {
        return $this->hasMany(Tema::class, 'curso_id'); // Asegura que el campo sea correcto
    }
    public function calcularProgreso($inscrito_id)
    {
        // Obtener el registro del inscrito
        $inscrito = Inscritos::find($inscrito_id);

        if (!$inscrito) {
            throw new \Exception("Inscrito no encontrado.");
        }

        // Obtener todos los subtemas de los temas del curso
        $subtemas = $this->temas->flatMap(fn($tema) => $tema->subtemas);

        // Obtener todas las actividades y recursos de los subtemas
        $actividades = $subtemas->flatMap(fn($subtema) => $subtema->actividades->pluck('id'));
        $recursos = $subtemas->flatMap(fn($subtema) => $subtema->recursos->pluck('id'));

        // Total de elementos (actividades + recursos)
        $totalElementos = $actividades->count() + $recursos->count();

        if ($totalElementos === 0) {
            $progreso = 0; // Evita división por cero
        } else {
            // Contar actividades completadas por el estudiante
            $actividadesCompletadas = ActividadCompletion::where('inscritos_id', $inscrito_id)
                ->whereIn('completable_id', $actividades)
                ->where('completable_type', Actividad::class)
                ->count();

            // Contar recursos completados por el estudiante
            $recursosCompletados = ActividadCompletion::where('inscritos_id', $inscrito_id)
                ->whereIn('completable_id', $recursos)
                ->where('completable_type', Recursos::class)
                ->count();

            // Calcular el progreso
            $progreso = round((($actividadesCompletadas + $recursosCompletados) / $totalElementos) * 100, 2);
        }

        // Actualizar la columna `progreso` en la tabla `inscritos`
        $inscrito->progreso = $progreso;
        $inscrito->save();

        return $progreso;
    }


    // app/Models/Cursos.php
    public function calificaciones()
    {
        return $this->hasMany(CursoCalificacion::class, 'curso_id');
    }

    public function expositores()
    {
        return $this->belongsToMany(Expositores::class, 'curso_expositor', 'curso_id', 'expositor_id')
            ->withPivot(['cargo', 'tema', 'orden'])
            ->orderBy('curso_expositor.orden')
            ->withTimestamps();
    }


    // Método para calcular el promedio
    public function getRatingAttribute()
    {
        return $this->calificaciones()->avg('puntuacion') ?? 0;
    }

    // Método para contar valoraciones
    public function getRatingsCountAttribute()
    {
        return $this->calificaciones()->count();
    }

    public function getEstadoAttribute()
    {
        $hoy = Carbon::today();

        if ($this->fecha_ini && $this->fecha_fin) {
            if ($hoy->lt($this->fecha_ini)) {
                return 'Inactivo'; // aún no inicia
            } elseif ($hoy->between($this->fecha_ini, $this->fecha_fin)) {
                return 'Activo'; // en curso
            } else {
                return 'Finalizado'; // ya pasó
            }
        }

        return 'Sin fechas';
    }


    public function imagenes()
    {
        return $this->hasMany(CursoImagen::class, 'curso_id')->orderBy('orden');
    }

    public function progreso()
    {
        return $this->hasOne(CursoProgreso::class, 'curso_id');
    }

    /**
     * Obtiene las estadísticas del progreso del curso basado en los inscritos
     * @return array
     */
    public function obtenerEstadisticasProgreso()
    {
        $inscritos = $this->inscritos;

        if ($inscritos->isEmpty()) {
            return [
                'porcentaje_total' => 0,
                'estudiantes_total' => 0,
                'estudiantes_completados' => 0,
                'estudiantes_en_progreso' => 0,
                'estudiantes_sin_iniciar' => 0
            ];
        }

        $totalEstudiantes = $inscritos->count();
        $estudiantesCompletados = $inscritos->where('progreso', 100)->count();
        $estudiantesSinIniciar = $inscritos->where('progreso', 0)->count();
        $estudiantesEnProgreso = $totalEstudiantes - $estudiantesCompletados - $estudiantesSinIniciar;
        $porcentajeTotal = $inscritos->avg('progreso') ?? 0;

        return [
            'porcentaje_total' => round($porcentajeTotal, 2),
            'estudiantes_total' => $totalEstudiantes,
            'estudiantes_completados' => $estudiantesCompletados,
            'estudiantes_en_progreso' => $estudiantesEnProgreso,
            'estudiantes_sin_iniciar' => $estudiantesSinIniciar
        ];
    }
}
