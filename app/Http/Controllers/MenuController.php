<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Aportes;
use App\Models\Categoria;
use App\Models\Certificado;
use App\Models\Horario;
use App\Models\User;
use App\Models\Cursos;
use App\Models\Evaluaciones;
use App\Models\Expositores;
use App\Models\Foro;
use App\Models\Inscritos;
use App\Models\PaymentMethod;
use App\Models\Tareas;
use App\Services\RecommendationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    protected $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    public function detalle(Cursos $curso)
    {
        // Cargar TODAS las relaciones necesarias de una sola vez para evitar N+1 queries
        $curso->load([
            // Calificaciones con usuarios
            'calificaciones.user' => function ($query) {
                $query->select('id', 'name', 'lastname1', 'lastname2');
            },
            // Inscritos activos con certificados
            'inscritos' => function ($query) {
                $query->whereNull('deleted_at')
                    ->with('certificado');
            },
            // Temas ordenados (para la sección de temario)
            'temas' => function ($query) {
                $query->orderBy('orden', 'asc')
                    ->select('id', 'curso_id', 'titulo_tema', 'descripcion', 'orden');
            },
            // Expositores con datos del pivot (para congresos)
            'expositores' => function ($query) {
                $query->select('expositores.id', 'expositores.nombre', 'expositores.imagen')
                    ->orderBy('curso_expositor.orden');
            },
            // Imágenes del curso ordenadas
            'imagenes' => function ($query) {
                $query->where('activo', true)
                    ->orderBy('orden')
                    ->select('id', 'curso_id', 'url', 'titulo', 'orden', 'activo');
            },
        ])
            ->loadAvg('calificaciones', 'puntuacion')
            ->loadCount('calificaciones');

        // Cargar métodos de pago activos solamente
        $metodosPago = PaymentMethod::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Inicializar variables
        $usuarioInscrito = null;
        $usuarioRetirado = null;
        $yaHaPagado = false;
        $pagoAnterior = null;
        $estadoInscripcion = 'no_inscrito';
        $usuarioCalifico = false;
        $calificacionUsuario = null;

        // Solo verificar si el usuario está autenticado
        if (Auth::check()) {
            $userId = Auth::id();

            // Verificar inscripción activa (ya está cargada en la relación)
            $usuarioInscrito = $curso->inscritos
                ->where('estudiante_id', $userId)
                ->first();

            // Verificar si fue retirado anteriormente
            $usuarioRetirado = Inscritos::withTrashed()
                ->where('estudiante_id', $userId)
                ->where('cursos_id', $curso->id)
                ->whereNotNull('deleted_at')
                ->orderBy('deleted_at', 'desc')
                ->first();

            // Determinar estado de inscripción
            if ($usuarioInscrito) {
                $estadoInscripcion = 'activo';
            } elseif ($usuarioRetirado) {
                $estadoInscripcion = 'retirado';
            }

            // Verificar si ya pagó
            if ($curso->precio > 0) {
                $pagoAnterior = Aportes::where('estudiante_id', $userId)
                    ->where('cursos_id', $curso->id)
                    ->where('monto_pagado', '>=', $curso->precio)
                    ->select('id', 'estudiante_id', 'cursos_id', 'monto_pagado')
                    ->first();

                $yaHaPagado = $pagoAnterior !== null;
            }

            // Verificar calificación del usuario (ya está cargada en la relación)
            $calificacionUsuario = $curso->calificaciones
                ->where('user_id', $userId)
                ->first();

            $usuarioCalifico = $calificacionUsuario !== null;
        }

        // Obtener las 5 calificaciones más recientes (ya están cargadas, solo filtrar)
        $calificacionesRecientes = $curso->calificaciones
            ->sortByDesc('created_at')
            ->take(5);

        return view('cursosDetalle', [
            'cursos' => $curso,
            'usuarioInscrito' => $usuarioInscrito,
            'usuarioRetirado' => $usuarioRetirado,
            'estadoInscripcion' => $estadoInscripcion,
            'usuarioCalifico' => $usuarioCalifico,
            'calificacionUsuario' => $calificacionUsuario,
            'yaHaPagado' => $yaHaPagado,
            'pagoAnterior' => $pagoAnterior,
            'calificacionesRecientes' => $calificacionesRecientes,
            'metodosPago' => $metodosPago,
        ]);
    }
    public function home()
    {
        $currentDate = Carbon::now();

        $congresos = Cursos::where('tipo', 'congreso')
            ->where('fecha_fin', '>=', $currentDate)
            ->where('estado', 'Activo')
            ->where('visibilidad', 'Público')
            ->get();

        $categorias = Categoria::whereNull('deleted_at')->get();



        $cursos = Cursos::where('tipo', 'curso')
            ->where('fecha_fin', '>=', $currentDate)
            ->where('estado', 'Activo')
            ->where('visibilidad', 'Público')
            ->get();

        return view('landing')->with('congresos', $congresos)
            ->with('cursos', $cursos)
            ->with('categorias', $categorias);
    }
    public function index()
    {
        $hoy = now()->toDateString();

        // Conteos básicos
        $totalCursos = Cursos::whereNull('deleted_at')->count();
        $totalEstudiantes = User::role('Estudiante')->whereNull('deleted_at')->count();
        $totalDocentes = User::role('Docente')->whereNull('deleted_at')->count();
        $totalInscripciones = Inscritos::whereNull('deleted_at')->count();

        // Nuevas estadísticas
        $totalCategorias = Categoria::whereNull('deleted_at')->count();
        $totalCertificados = Certificado::whereNull('deleted_at')->count();
        $totalForos = Foro::whereNull('deleted_at')->count();
        $totalActividades = Actividad::whereNull('deleted_at')->count();
        $cursosActivos = Cursos::whereNull('deleted_at')
            ->where('fecha_ini', '<=', $hoy)
            ->where('fecha_fin', '>=', $hoy)
            ->count();
        $cursosFinalizados = Cursos::whereNull('deleted_at')
            ->where('fecha_fin', '<', $hoy)
            ->count();

        $metodosPago = PaymentMethod::all();
        $categorias = Categoria::whereNull('deleted_at')->get();
        $certificados = Certificado::whereNull('deleted_at')->get();
        $aportes = Aportes::whereNull('deleted_at')->get();
        $foros = Foro::whereNull('deleted_at')->get();
        $actividades = Actividad::whereNull('deleted_at')->get();
        $expositores = Expositores::whereNull('deleted_at')->get();
        $cursos = Cursos::whereNull('deleted_at')->get();
        $inscritos = Inscritos::whereNull('deleted_at')->get();
        $estudiantes = User::role('Estudiante')->whereNull('deleted_at')->get();
        $docentes = User::role('Docente')->whereNull('deleted_at')->get();

        return view('Inicio', compact(
            'categorias',
            'certificados',
            'aportes',
            'foros',
            'actividades',
            'expositores',
            'cursos',
            'inscritos',
            'estudiantes',
            'docentes',
            'totalCursos',
            'totalEstudiantes',
            'totalDocentes',
            'totalInscripciones',
            'totalCategorias',
            'totalCertificados',
            'totalForos',
            'totalActividades',
            'cursosActivos',
            'cursosFinalizados'
        ))->with('metodosPago', $metodosPago);
    }
    public function ListaDeCursos(Request $request)
    {
        $search = $request->input('search');
        $tipo   = $request->input('tipo');
        $estado = $request->input('estado'); // activo | finalizado | proximo
        $hoy    = now()->toDateString();

        $cursos = Cursos::whereNull('deleted_at')
            ->when($search, fn($q, $s) =>
                $q->where('nombreCurso', 'like', "%$s%")
                  ->orWhereHas('docente', fn($d) =>
                      $d->where('name',      'like', "%$s%")
                        ->orWhere('lastname1', 'like', "%$s%")
                  )
            )
            ->when($tipo, fn($q) => $q->where('tipo', $tipo))
            ->when($estado == 'activo',     fn($q) => $q->where('fecha_ini', '<=', $hoy)
                                                         ->where('fecha_fin', '>=', $hoy))
            ->when($estado == 'finalizado', fn($q) => $q->where('fecha_fin', '<', $hoy))
            ->when($estado == 'proximo',    fn($q) => $q->where('fecha_ini', '>', $hoy))
            ->paginate(10);

        $inscritos = auth()->user()->hasRole('Estudiante')
            ? Inscritos::where('estudiante_id', auth()->id())->with('cursos')->get()
            : collect();

        return view('ListaDeCursos', compact('cursos', 'inscritos'));
    }

    public function ListaDeCursosEliminados()
    {

        $cursos = Cursos::onlyTrashed()->get();

        return view('Administrador.ListadeCursosEliminados')->with('cursos', $cursos);
    }

    public function lista(Request $request)
    {
        // 1. Validación básica de los parámetros de entrada
        $validated = $request->validate([
            'type' => 'nullable|in:curso,congreso',
            'sort' => 'nullable|in:price_asc,price_desc,date_desc,rating_desc',
            'search' => 'nullable|string|max:255',
            'formato' => 'nullable|string',
            'nivel' => 'nullable|string',
            'precio' => 'nullable|in:gratis,pago',
            'mes' => 'nullable|integer|between:1,12',
            'visibilidad' => 'nullable|in:publico,privado',
            'categoria' => 'nullable|exists:categoria,id'
        ]);

        // 2. Crear la consulta base con todas las relaciones necesarias
        $query = Cursos::query()
            ->with([
                'docente',
                'categorias', // Relación muchos a muchos
                'calificaciones' => function ($q) {
                    $q->select('curso_id', 'puntuacion'); // Solo campos necesarios para optimizar
                }
            ])
            ->withAvg('calificaciones', 'puntuacion') // Promedio de calificaciones
            ->withCount('calificaciones') // Cantidad de calificaciones
            ->withCount('inscritos'); // Cantidad de inscritos

        // NUEVO: Filtrar cursos que ya terminaron su fecha
        $currentDate = now();
        $query->where('fecha_fin', '>=', $currentDate);

        // 3. Filtro de visibilidad basado en rol
        $isLoggedIn = auth()->check();
        $isAdmin = $isLoggedIn && auth()->user()->hasRole('Administrador');

        if (!$isAdmin) {
            // Usuarios no admin ven Público, y si están logueados también Solo Registrados
            if ($isLoggedIn) {
                $query->whereIn('visibilidad', ['Público', 'Solo Registrados']);
            } else {
                $query->where('visibilidad', 'Público');
            }
        } elseif ($request->filled('visibilidad')) {
            $query->where('visibilidad', $validated['visibilidad']);
        }

        // 4. Filtro de búsqueda (incluye nombre del curso, descripción y categorías)
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nombreCurso', 'like', '%' . $request->search . '%')
                    ->orWhere('descripcionC', 'like', '%' . $request->search . '%')
                    ->orWhereHas('categorias', function ($catQuery) use ($request) {
                        $catQuery->where('categoria.name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        // 5. Filtros simples
        if ($request->filled('type')) {
            $query->where('tipo', $request->type);
        }

        if ($request->filled('formato')) {
            $query->where('formato', $request->formato);
        }

        if ($request->filled('nivel')) {
            $query->where('nivel', $request->nivel);
        }

        if ($request->filled('precio')) {
            if ($request->precio === 'gratis') {
                $query->where('precio', 0);
            } else {
                $query->where('precio', '>', 0);
            }
        }

        if ($request->filled('mes')) {
            $query->whereMonth('fecha_ini', $request->mes);
        }

        // 6. Filtro por categoría (relación muchos a muchos)
        if ($request->filled('categoria')) {
            $query->whereHas('categorias', function ($catQuery) use ($request) {
                $catQuery->where('categoria.id', $request->categoria);
            });
        }

        // 7. Ordenamiento
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('precio', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('precio', 'desc');
                    break;
                case 'date_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'rating_desc':
                    $query->orderByDesc('calificaciones_avg_puntuacion')
                        ->orderByDesc('calificaciones_count');
                    break;
            }
        } else {
            // Ordenamiento por defecto: mejor calificados primero, luego por fecha
            $query->orderByDesc('calificaciones_avg_puntuacion')
                ->orderBy('created_at', 'desc');
        }

        // 8. Paginación
        $cursos = $query->paginate(9)->withQueryString();

        // 9. Recomendaciones personalizadas para estudiantes
        $recommendations = collect();
        if (auth()->user() && auth()->user()->hasRole('Estudiante')) {
            $recommendations = $this->recommendationService->getRecommendations(auth()->user(), 3);
        }

        // 10. Marcar cursos que están por comenzar
        $cursos->getCollection()->transform(function ($curso) use ($currentDate) {
            if ($curso->fecha_ini > $currentDate) {
                $curso->proximamente = true;
            } else {
                $curso->proximamente = false;
            }
            return $curso;
        });

        // 11. Obtener categorías para el filtro (conteo dinámico)
        // Mostramos categorías que tengan cursos activos y visibles para el usuario actual
        $categorias = Categoria::whereHas('cursos', function ($q) use ($isAdmin, $isLoggedIn, $currentDate) {
                $q->where('fecha_fin', '>=', $currentDate);
                if (!$isAdmin) {
                    if ($isLoggedIn) {
                        $q->whereIn('visibilidad', ['Público', 'Solo Registrados']);
                    } else {
                        $q->where('visibilidad', 'Público');
                    }
                }
            })
            ->withCount(['cursos' => function ($q) use ($isAdmin, $isLoggedIn, $currentDate, $request) {
                $q->where('fecha_fin', '>=', $currentDate);
                if (!$isAdmin) {
                    if ($isLoggedIn) {
                        $q->whereIn('visibilidad', ['Público', 'Solo Registrados']);
                    } else {
                        $q->where('visibilidad', 'Público');
                    }
                }
                if ($request->filled('type')) {
                    $q->where('tipo', $request->type);
                }
                if ($request->filled('formato')) {
                    $q->where('formato', $request->formato);
                }
                if ($request->filled('nivel')) {
                    $q->where('nivel', $request->nivel);
                }
                if ($request->filled('precio')) {
                    if ($request->precio === 'gratis') $q->where('precio', 0);
                    else $q->where('precio', '>', 0);
                }
                if ($request->filled('mes')) {
                    $q->whereMonth('fecha_ini', $request->mes);
                }
                if ($request->filled('search')) {
                    $q->where(function($sq) use ($request) {
                        $sq->where('nombreCurso', 'like', '%' . $request->search . '%')
                          ->orWhere('descripcionC', 'like', '%' . $request->search . '%')
                          ->orWhereHas('categorias', function ($catQuery) use ($request) {
                              $catQuery->where('categoria.name', 'like', '%' . $request->search . '%');
                          });
                    });
                }
            }])
            ->orderBy('name')
            ->get();

        // 12. Estadísticas adicionales para la vista
        $stats = [
            'total_cursos' => $cursos->total(),
            'promedio_general' => $cursos->avg('calificaciones_avg_puntuacion'),
            'categorias_disponibles' => $categorias->count()
        ];

        // 13. Retornar la vista con los datos
        \Illuminate\Support\Facades\Log::info('Categorias found in controller: ' . $categorias->count());
        if ($cursos->isNotEmpty()) {
            \Illuminate\Support\Facades\Log::info('First course categories: ' . $cursos->first()->categorias->count());
        }

        return view('listacursoscongresos', [
            'cursos' => $cursos,
            'categorias' => $categorias,
            'recommendations' => $recommendations,
            'filters' => $validated,
            'stats' => $stats
        ]);
    }

    public function ListaUsuarios(Request $request)
    {
        $search = $request->input('search');
        $role   = $request->input('role'); // Administrador | Docente | Estudiante | null

        $usuarios = User::query()
            ->when($role, fn($q) => $q->role($role))          // filtro Spatie
            ->when(!$role, fn($q) => $q->whereHas('roles'))   // solo usuarios con algún rol
            ->when($search, fn($q, $s) =>
                $q->where('name',      'like', "%$s%")
                  ->orWhere('lastname1', 'like', "%$s%")
                  ->orWhere('lastname2', 'like', "%$s%")
                  ->orWhere('email',     'like', "%$s%")
                  ->orWhere('Celular',   'like', "%$s%")
            )
            ->paginate(10);

        return view('Administrador.ListaUsuarios', compact('usuarios'));
    }
    public function ListaUsuariosEliminados(Request $request)
    {
        $role   = $request->input('role');
        $search = $request->input('search');

        $usuarios = User::onlyTrashed()
            ->when($role, fn($q) => $q->role($role))
            ->when(!$role, fn($q) => $q->whereHas('roles'))
            ->when($search, fn($q, $s) =>
                $q->where('name',       'like', "%$s%")
                  ->orWhere('lastname1', 'like', "%$s%")
                  ->orWhere('lastname2', 'like', "%$s%")
                  ->orWhere('email',     'like', "%$s%")
                  ->orWhere('Celular',   'like', "%$s%")
            )
            ->paginate(10);

        return view('Administrador.ListaUsuariosEliminados', compact('usuarios'));
    }

    public function ListaAportes()
    {
        $aportes = Aportes::all();
        return view('Administrador.ListadeAportes')->with('aportes', $aportes);
    }


    public function storeUIndex()
    {
        return view('Administrador.CrearUsuario');
    }

    public function ListaCursos()
    {

        $cursos = Cursos::whereNull('deleted_at')->get();

        return view('Inicio')->with('cursos', $cursos);
    }

    public function storeCIndex()
    {

        $docente = User::role('Docente')->get();
        $horario = Horario::all();

        return view('Administrador.CrearCursos')->with('docente', $docente)->with('horario', $horario);
    }

    public function calendario()
    {
        $user = Auth::user();
        $esDocente = $user->hasRole('Docente');
        $cursos = $this->obtenerCursosPorRol($user);

        // Si no hay cursos, mostrar calendario vacío con mensaje
        if ($cursos->isEmpty()) {
            return view('calendario', [
                'cursos' => collect(),
                'eventos' => [],
                'esDocente' => $esDocente,
                'estadisticas' => [
                    'total' => 0,
                    'entregadas' => 0,
                    'pendientes' => 0,
                    'proximasVencer' => 0
                ]
            ])->with('warning', 'No tienes cursos asignados.');
        }

        $fechaInicio = now()->subMonths(6);
        $fechaFin = now()->addMonths(6);

        $actividades = $this->obtenerActividadesPorCursos($cursos, $fechaInicio, $fechaFin);
        $eventos = $this->formatearEventosParaFullCalendar($actividades, $user);
        $estadisticas = $this->calcularEstadisticas($actividades, $user);

        return view('calendario', compact('cursos', 'eventos', 'estadisticas', 'esDocente'));
    }

    private function formatearEventosParaFullCalendar($actividades, $user)
    {
        $esDocente = $user->hasRole('Docente');

        // Obtener el inscrito_id del usuario para verificar completitud
        $inscritoIds = [];
        if (!$esDocente) {
            $inscritoIds = Inscritos::where('estudiante_id', $user->id)
                ->pluck('id')
                ->toArray();
        }

        // Para docentes: pre-calcular total de inscritos por curso
        $inscritosPorCurso = [];
        if ($esDocente) {
            $cursosIds = $actividades->map(function ($a) {
                return $a->subtema?->tema?->curso?->id;
            })->filter()->unique();

            foreach ($cursosIds as $cursoId) {
                $inscritosPorCurso[$cursoId] = Inscritos::where('cursos_id', $cursoId)
                    ->whereNull('deleted_at')
                    ->count();
            }
        }

        return $actividades->map(function ($actividad) use ($inscritoIds, $esDocente, $inscritosPorCurso) {
            $fechaLimite = $actividad->fecha_limite;
            if (!$fechaLimite) return null;

            $ahora = now();

            // Determinar si fue completada/entregada por el usuario
            $entregada = false;
            $completada = false;
            $porcentajeCompletado = null;
            $totalInscritos = 0;
            $totalCompletados = 0;

            if (!$esDocente) {
                // ESTUDIANTE: verificar completitud individual
                foreach ($inscritoIds as $inscritoId) {
                    if ($actividad->isCompletedByInscrito($inscritoId)) {
                        $completada = true;
                        $entregada = true;
                        break;
                    }
                }

                // También verificar si tiene entregas (archivos entregados)
                if (!$entregada && $actividad->entregas && $actividad->entregas->isNotEmpty()) {
                    $entregada = true;
                }
            } else {
                // DOCENTE: calcular porcentaje de completitud
                $cursoId = $actividad->subtema?->tema?->curso?->id;
                $totalInscritos = $inscritosPorCurso[$cursoId] ?? 0;

                if ($totalInscritos > 0) {
                    $totalCompletados = $actividad->completions()
                        ->where('completed', true)
                        ->count();
                    $porcentajeCompletado = round(($totalCompletados / $totalInscritos) * 100);
                } else {
                    $porcentajeCompletado = 0;
                }

                // Para docente: considerar "completada" si >80% entregaron
                $completada = $porcentajeCompletado >= 80;
                $entregada = $completada;
            }

            $estado = $entregada ? 'entregada' : 'pendiente';
            $diasRestantes = $fechaLimite->diffInDays($ahora, false);
            $urgente = !$entregada && $diasRestantes >= -2 && $diasRestantes <= 2;

            // Determinar color según estado y urgencia
            $actividadConEstado = (object)[
                'estado' => $estado,
            ];
            $color = $this->determinarColorEvento($actividadConEstado, $fechaLimite, $ahora);

            // Nombre del tipo de actividad
            $tipoNombre = $actividad->tipoActividad
                ? $actividad->tipoActividad->nombre
                : 'Actividad';

            // Curso asociado
            $cursoNombre = '—';
            if ($actividad->subtema && $actividad->subtema->tema && $actividad->subtema->tema->curso) {
                $cursoNombre = $actividad->subtema->tema->curso->nombreCurso;
            }

            // Determinar si tiene cuestionario
            $tieneCuestionario = $actividad->cuestionario !== null;

            // Generar URL según rol y tipo
            $url = $this->generarUrlActividad($actividad, $esDocente, $tieneCuestionario);

            return [
                'id' => $actividad->id,
                'title' => $actividad->titulo ?? 'Sin título',
                'start' => $fechaLimite->format('Y-m-d'),
                'end' => $fechaLimite->format('Y-m-d'),
                'color' => $color,
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'curso' => $cursoNombre,
                    'tipo' => $tipoNombre,
                    'estado' => $estado,
                    'descripcion' => $actividad->descripcion ?? 'Sin descripción',
                    'urgente' => $urgente,
                    'entregada' => $entregada,
                    'completada' => $completada,
                    'esCuestionario' => $tieneCuestionario,
                    'esDocente' => $esDocente,
                    'porcentajeCompletado' => $porcentajeCompletado,
                    'totalInscritos' => $totalInscritos,
                    'totalCompletados' => $totalCompletados,
                    'url' => $url,
                ]
            ];
        })->filter()->values()->toArray();
    }

    /**
     * Genera la URL correcta según el rol del usuario y el tipo de actividad.
     */
    private function generarUrlActividad($actividad, $esDocente, $tieneCuestionario)
    {
        try {
            if ($esDocente) {
                // Docente: va a calificar o a ver el cuestionario
                if ($tieneCuestionario && $actividad->cuestionario) {
                    return route('cuestionarios.index', $actividad->cuestionario->id);
                }
                return route('calificarT', $actividad->id);
            }

            // Estudiante: va a resolver cuestionario o ver actividad
            if ($tieneCuestionario && $actividad->cuestionario) {
                return route('cuestionario.mostrar', encrypt($actividad->cuestionario->id));
            }
            return route('actividad.show', encrypt($actividad->id));
        } catch (\Exception $e) {
            return '#';
        }
    }

    private function determinarColorEvento($actividad, $fechaLimite, $ahora)
    {
        if ($actividad->estado === 'entregada') {
            return '#28a745'; // Verde para entregadas
        }

        $diasRestantes = $ahora->diffInDays($fechaLimite, false);

        if ($diasRestantes < 0) {
            return '#dc3545'; // Rojo para vencidas
        } elseif ($diasRestantes <= 2) {
            return '#ffc107'; // Amarillo para próximas a vencer
        } elseif ($diasRestantes <= 7) {
            return '#fd7e14'; // Naranja para esta semana
        } else {
            return '#17a2b8'; // Azul para normales
        }
    }

    private function obtenerCursosPorRol($user)
    {
        if ($user->hasRole('Docente')) {
            return Cursos::with(['inscritos', 'temas.subtemas.actividades'])
                ->where('docente_id', $user->id)
                ->where('estado', 'Activo')
                ->get();
        }

        if ($user->hasRole('Estudiante')) {
            $cursosIds = Inscritos::where('estudiante_id', $user->id)
                ->pluck('cursos_id');

            return Cursos::with(['docente', 'temas.subtemas.actividades'])
                ->whereIn('id', $cursosIds)
                ->get();
        }

        return collect();
    }

    private function obtenerActividadesPorCursos($cursos, $fechaInicio, $fechaFin)
    {
        $cursosIds = $cursos->pluck('id');
        $user = Auth::user();
        $esDocente = $user->hasRole('Docente');

        // Relaciones base (siempre necesarias)
        $relaciones = [
            'subtema.tema.curso',
            'tipoActividad',
            'cuestionario', // Para determinar URL según tipo
        ];

        // Para estudiantes: cargar entregas y completions filtradas
        if (!$esDocente) {
            $inscritoIds = Inscritos::where('estudiante_id', $user->id)
                ->pluck('id')
                ->toArray();

            $relaciones['entregas'] = function ($query) use ($user) {
                $query->where('user_id', $user->id);
            };
            $relaciones['completions'] = function ($query) use ($inscritoIds) {
                $query->whereIn('inscritos_id', $inscritoIds)
                    ->where('completed', true);
            };
        }

        return Actividad::with($relaciones)
            ->whereHas('subtema.tema', function ($query) use ($cursosIds) {
                $query->whereIn('curso_id', $cursosIds);
            })
            ->whereBetween('fecha_limite', [$fechaInicio, $fechaFin])
            ->orderBy('fecha_limite', 'asc')
            ->get();
    }

    public function mejoresCursosPorCategoria()
    {
        // Obtener todas las categorías activas que tienen cursos
        $categorias = Categoria::whereNull('deleted_at')
            ->whereHas('cursos', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->with(['cursos' => function ($query) {
                $query->whereNull('deleted_at')
                    ->withAvg('calificaciones', 'puntuacion')
                    ->withCount('calificaciones')
                    ->withCount('inscritos')
                    ->orderByDesc('calificaciones_avg_puntuacion')
                    ->orderByDesc('calificaciones_count')
                    ->take(5); // Limitar a los 5 mejores cursos por categoría
            }])
            ->get();

        // Filtrar categorías que no tienen cursos con calificaciones
        $categoriasConCursos = $categorias->filter(function ($categoria) {
            return $categoria->cursos->isNotEmpty();
        });

        // Estadísticas generales
        $stats = [
            'total_categorias' => $categoriasConCursos->count(),
            'total_cursos' => $categoriasConCursos->sum(function ($categoria) {
                return $categoria->cursos->count();
            }),
            'promedio_general' => $categoriasConCursos->flatMap(function ($categoria) {
                return $categoria->cursos;
            })->avg('calificaciones_avg_puntuacion')
        ];

        return view('mejoresCursosPorCategoria', [
            'categorias' => $categoriasConCursos,
            'stats' => $stats
        ]);
    }

    private function formatearEventosParaCalendario($actividades)
    {
        return $actividades->map(function ($actividad) {
            // Verificar si tipoActividad existe antes de acceder a su nombre
            $color = $this->obtenerColorPorTipo($actividad->tipoActividad->nombre ?? 'default');

            // Verificar si la actividad está completada usando ActividadCompletion
            $entregada = $actividad->isCompletedByInscrito(auth()->user()->inscrito->id ?? 0);

            // Verificar si fecha_limite existe y es un objeto DateTime
            if (!$actividad->fecha_limite) {
                return null; // Omitir actividades sin fecha límite
            }

            // Obtener el curso asociado a la actividad
            $curso = $actividad->subtema->tema->curso ?? null;

            return [
                'id' => $actividad->id,
                'title' => $actividad->titulo ?? 'Sin título',
                'start' => $actividad->fecha_limite->format('Y-m-d H:i:s'),
                'end' => $actividad->fecha_limite->addHour()->format('Y-m-d H:i:s'),
                'color' => $entregada ? '#28a745' : $color,
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'nombreCurso' => $curso ? $curso->nombreCurso : 'Sin curso',
                    'curso' => $curso ? $curso->id : '',
                    'tipo' => $actividad->tipoActividad->nombre ?? 'Actividad',
                    'descripcion' => $actividad->descripcion ?? 'Sin descripción',
                    'estado' => $entregada ? 'Entregada' : 'Pendiente',
                    'puntos' => $actividad->puntaje_maximo ?? 0,
                    'url' => route('actividades.show', $actividad->id),
                    // Añadir información de horarios del curso si está disponible
                    'horarios' => $curso ? $this->obtenerHorariosCurso($curso) : []
                ]
            ];
        })->filter()->values()->toArray(); // Filtrar valores nulos
    }

    private function obtenerHorariosCurso($curso)
    {
        if (!$curso || !$curso->horarios) {
            return [];
        }

        return $curso->horarios->map(function ($horario) {
            return [
                'dia' => $horario->dia,
                'hora_inicio' => $horario->hora_inicio,
                'hora_fin' => $horario->hora_fin,
            ];
        })->toArray();
    }

    private function obtenerColorPorTipo($tipo)
    {
        $colores = [
            'Tarea' => '#007bff',
            'Examen' => '#dc3545',
            'Proyecto' => '#ffc107',
            'Quiz' => '#17a2b8',
            'Laboratorio' => '#6f42c1',
            'default' => '#6c757d'
        ];

        return $colores[$tipo] ?? $colores['default'];
    }

    private function calcularEstadisticas($actividades, $user)
    {
        $esDocente = $user->hasRole('Docente');
        $total = $actividades->count();

        if ($esDocente) {
            // Para docentes: estadísticas basadas en porcentaje de compleción
            $entregadas = 0;
            $proximasVencer = 0;

            foreach ($actividades as $a) {
                if (!$a->fecha_limite) continue;

                $cursoId = $a->subtema?->tema?->curso?->id;
                $totalInscritos = $cursoId ? Inscritos::where('cursos_id', $cursoId)->whereNull('deleted_at')->count() : 0;

                if ($totalInscritos > 0) {
                    $completados = $a->completions()->where('completed', true)->count();
                    $porcentaje = ($completados / $totalInscritos) * 100;
                    if ($porcentaje >= 80) $entregadas++;
                }

                // Urgentes: actividades con menos de 3 días y bajo porcentaje
                if ($a->fecha_limite->isBetween(now(), now()->addDays(3))) {
                    $proximasVencer++;
                }
            }

            $pendientes = $total - $entregadas;
            return compact('total', 'entregadas', 'pendientes', 'proximasVencer');
        }

        // Para estudiantes
        $inscritoIds = Inscritos::where('estudiante_id', $user->id)
            ->pluck('id')
            ->toArray();

        $entregadas = $actividades->filter(function ($a) use ($inscritoIds) {
            foreach ($inscritoIds as $inscritoId) {
                if ($a->isCompletedByInscrito($inscritoId)) {
                    return true;
                }
            }
            if ($a->entregas && $a->entregas->isNotEmpty()) {
                return true;
            }
            return false;
        })->count();

        $pendientes = $total - $entregadas;

        $proximasVencer = $actividades->filter(function ($a) use ($inscritoIds) {
            if (!$a->fecha_limite) return false;
            $completada = false;
            foreach ($inscritoIds as $inscritoId) {
                if ($a->isCompletedByInscrito($inscritoId)) {
                    $completada = true;
                    break;
                }
            }
            if (!$completada && $a->entregas && $a->entregas->isNotEmpty()) {
                $completada = true;
            }
            return !$completada && $a->fecha_limite->isBetween(now(), now()->addDays(3));
        })->count();

        return compact('total', 'entregadas', 'pendientes', 'proximasVencer');
    }

    public function obtenerEventos(Request $request)
    {
        $fechaInicio = $request->get('start');
        $fechaFin = $request->get('end');

        $cursos = $this->obtenerCursosPorRol(Auth::user());
        $actividades = $this->obtenerActividadesPorCursos($cursos, $fechaInicio, $fechaFin);

        return response()->json($this->formatearEventosParaCalendario($actividades));
    }

    public function analytics()
    {
        $cursos2 = Cursos::whereNull('deleted_at')->get();

        return view('Docente.analitics')->with('cursos2', $cursos2);
    }
}
