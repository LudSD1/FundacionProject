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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{


  public function detalle($id)
{
    $curso = Cursos::with([
        'calificaciones.user',
        'inscritos' => function ($query) {
            $query->whereNull('deleted_at');
        }
    ])
        ->withAvg('calificaciones', 'puntuacion')
        ->withCount('calificaciones')
        ->findOrFail($id);

    $metodosPago = PaymentMethod::all();

    // Inicializar variables por defecto
    $usuarioInscrito = null;
    $usuarioRetirado = null;
    $yaHaPagado = false;
    $pagoAnterior = null;
    $estadoInscripcion = 'no_inscrito'; // no_inscrito, activo, retirado
    $usuarioCalifico = false;
    $calificacionUsuario = null;

    // Solo verificar inscripciones y pagos si el usuario está autenticado
    if (Auth::check()) {
        // Verificar inscripción activa
        $usuarioInscrito = Inscritos::where('estudiante_id', auth()->user()->id)
            ->where('cursos_id', $id)
            ->whereNull('deleted_at')
            ->first();

        // Verificar si fue retirado anteriormente (eliminado)
        $usuarioRetirado = Inscritos::withTrashed()
            ->where('estudiante_id', auth()->user()->id)
            ->where('cursos_id', $id)
            ->whereNotNull('deleted_at')
            ->orderBy('deleted_at', 'desc')
            ->first();

        // Determinar estado de inscripción
        if ($usuarioInscrito) {
            $estadoInscripcion = 'activo';
        } elseif ($usuarioRetirado) {
            $estadoInscripcion = 'retirado';
        }

        // Verificar si ya pagó este curso anteriormente (incluso si fue desinscrito)
        if ($curso->precio > 0) {
            $pagoAnterior = Aportes::where('estudiante_id', Auth::id())
                ->where('cursos_id', $id)
                ->where('monto_pagado', '>=', $curso->precio)
                ->first();

            $yaHaPagado = $pagoAnterior !== null;
        }

        // Verificar calificación del usuario
        $calificacionUsuario = $curso->calificaciones->where('user_id', Auth::id())->first();
        $usuarioCalifico = $calificacionUsuario !== null;
    }

    return view('cursosDetalle', [
        'cursos' => $curso,
        'usuarioInscrito' => $usuarioInscrito,
        'usuarioRetirado' => $usuarioRetirado,
        'estadoInscripcion' => $estadoInscripcion,
        'usuarioCalifico' => $usuarioCalifico,
        'calificacionUsuario' => $calificacionUsuario,
        'yaHaPagado' => $yaHaPagado,
        'pagoAnterior' => $pagoAnterior,
        'calificacionesRecientes' => $curso->calificaciones()
            ->with('user')
            ->latest()
            ->take(5)
            ->get(),
        'metodosPago' => $metodosPago,
    ]);
}






    public function home()
    {
        $currentDate = Carbon::now(); // Fecha actual

        // Filtrar congresos cuya fecha_fin no ha pasado
        $congresos = Cursos::where('tipo', 'congreso')
            ->where('fecha_fin', '>=', $currentDate)
            ->get();

        // Filtrar cursos cuya fecha_fin no ha pasado
        $cursos = Cursos::where('tipo', 'curso')
            ->where('fecha_fin', '>=', $currentDate)
            ->get();

        return view('landing')->with('congresos', $congresos)->with('cursos', $cursos);
    }
    public function index()
    {
        $totalCursos = Cursos::whereNull('deleted_at')->count();
        $totalEstudiantes = User::role('Estudiante')->whereNull('deleted_at')->count();
        $totalDocentes = User::role('Docente')->whereNull('deleted_at')->count();
        $totalInscritos = Inscritos::whereNull('deleted_at')->count();
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
        // $logPath = storage_path('logs/laravel.log');
        // $logs = file_exists($logPath) ? collect(explode("\n", file_get_contents($logPath)))->take(-100)->implode("\n") : 'No hay logs disponibles.';

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
            'totalInscritos',
        ))->with('metodosPago', $metodosPago);
    }


    public function ListaDeCursos()
    {

        $cursos = Cursos::whereNull('deleted_at')->get();
        $inscritos = Inscritos::all();
        return view('ListaDeCursos')->with('cursos', $cursos)->with('inscritos', $inscritos);
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
            'formato' => 'nullable|in:Presencial,Virtual,Híbrido',
            'nivel' => 'nullable|string',
            'visibilidad' => 'nullable|in:publico,privado',
            'categoria' => 'nullable|exists:categoria,id' // Filtro por categoría
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

        // 3. Filtro de visibilidad basado en rol
        $isAdmin = auth()->user() && auth()->user()->hasRole('Administrador');
        if (!$isAdmin) {
            $query->where('visibilidad', 'publico');
        } elseif ($request->filled('visibilidad')) {
            $query->where('visibilidad', $validated['visibilidad']);
        }

        // 4. Filtro de búsqueda (incluye nombre del curso, descripción y categorías)
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nombreCurso', 'like', '%' . $request->search . '%')
                    ->orWhere('descripcionC', 'like', '%' . $request->search . '%')
                    ->orWhereHas('categorias', function ($catQuery) use ($request) {
                        $catQuery->where('name', 'like', '%' . $request->search . '%');
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

        // 6. Filtro por categoría (relación muchos a muchos)
        if ($request->filled('categoria')) {
            $query->whereHas('categorias', function ($catQuery) use ($validated) {
                $catQuery->where('categoria.id', $validated['categoria']);
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

        // 9. Obtener categorías para el filtro (solo las que tienen cursos públicos)
        $categorias = Categoria::whereHas('cursos', function ($q) use ($isAdmin) {
            if (!$isAdmin) {
                $q->where('visibilidad', 'publico');
            }
        })
            ->withCount(['cursos' => function ($q) use ($isAdmin) {
                if (!$isAdmin) {
                    $q->where('visibilidad', 'publico');
                }
            }])
            ->orderBy('name')
            ->get();

        // 10. Estadísticas adicionales para la vista
        $stats = [
            'total_cursos' => $cursos->total(),
            'promedio_general' => $cursos->avg('calificaciones_avg_puntuacion'),
            'categorias_disponibles' => $categorias->count()
        ];

        // 11. Retornar la vista con los datos
        return view('listacursoscongresos', [
            'cursos' => $cursos,
            'categorias' => $categorias,
            'filters' => $validated,
            'stats' => $stats
        ]);
    }




    public function ListaDocentes(Request $request)
    {
        $search = $request->input('search');

        $docentes = User::role('Docente')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%$search%")
                    ->orWhere('lastname1', 'like', "%$search%")
                    ->orWhere('lastname2', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('Celular', 'like', "%$search%");
            })
            ->paginate(10);
        return view('Administrador.ListadeDocentes')->with('docentes', $docentes);
    }
    public function ListaDocentesEliminados()
    {
        $docente = User::role('Docente')->onlyTrashed()->get();
        return view('Administrador.ListadeDocentesEliminados')->with('docente', $docente);
    }

    public function ListaAportes()
    {
        $aportes = Aportes::all();
        return view('Administrador.ListadeAportes')->with('aportes', $aportes);
    }


    public function ListaEstudiantes(Request $request)
    {
        $search = $request->input('search');

        $estudiantes = User::role('Estudiante')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%$search%")
                    ->orWhere('lastname1', 'like', "%$search%")
                    ->orWhere('lastname2', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('Celular', 'like', "%$search%");
            })
            ->paginate(10);


        return view('Administrador.ListadeEstudiantes')->with('estudiantes', $estudiantes);
    }
    public function ListaEstudiantesEliminados(Request $request)
    {
        $estudiantes = User::role('Estudiante')->onlyTrashed()->get();

        return view('Administrador.ListadeEstudiantesEliminados')->with('estudiantes', $estudiantes);
    }



    public function storeDIndex()
    {
        return view('Administrador.CrearDocente');
    }
    public function storeETIndex()
    {
        return view('Administrador.CrearEstudianteConTutor');
    }
    public function storeEIndex()
    {
        return view('Administrador.CrearEstudiante');
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

    public function calendario(Request $request)
    {
        $user = Auth::user();
        $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth());
        $fechaFin = $request->get('fecha_fin', now()->endOfMonth());

        // Obtener cursos según el rol (optimizado con eager loading)
        $cursos = $this->obtenerCursosPorRol($user);

        if ($cursos->isEmpty()) {
            return view('calendario', [
                'actividades' => collect(),
                'cursos' => collect(),
                'eventos' => []
            ]);
        }

        // Obtener actividades con filtros y eager loading optimizado
        $actividades = $this->obtenerActividadesPorCursos($cursos, $fechaInicio, $fechaFin);

        // Formatear eventos para el calendario
        $eventos = $this->formatearEventosParaCalendario($actividades);

        // Estadísticas adicionales
        $estadisticas = $this->calcularEstadisticas($actividades, $user);

        return view('calendario', compact('actividades', 'cursos', 'eventos', 'estadisticas'));
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
                ->where('estado', 'Activo')
                ->get();
        }

        return collect();
    }

    private function obtenerActividadesPorCursos($cursos, $fechaInicio, $fechaFin)
    {
        $cursosIds = $cursos->pluck('id');

        return Actividad::with([
            'subtema.tema.curso',
            'tipoActividad',
            'entregas' => function ($query) {
                $query->where('user_id', Auth::id());
            }
        ])
            ->whereHas('subtema.tema', function ($query) use ($cursosIds) {
                $query->whereIn('curso_id', $cursosIds);
            })
            ->whereBetween('fecha_limite', [$fechaInicio, $fechaFin])
            ->orderBy('fecha_limite', 'asc')
            ->get();
    }

    private function formatearEventosParaCalendario($actividades)
    {
        return $actividades->map(function ($actividad) {
            $color = $this->obtenerColorPorTipo($actividad->tipoActividad->nombre ?? 'default');
            $entregada = $actividad->entregas->isNotEmpty();

            return [
                'id' => $actividad->id,
                'title' => $actividad->nombre,
                'start' => $actividad->fecha_limite->format('Y-m-d H:i:s'),
                'end' => $actividad->fecha_limite->addHour()->format('Y-m-d H:i:s'),
                'color' => $entregada ? '#28a745' : $color,
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'curso' => $actividad->subtema->tema->curso->nombre,
                    'tipo' => $actividad->tipoActividad->nombre ?? 'Actividad',
                    'descripcion' => $actividad->descripcion,
                    'estado' => $entregada ? 'Entregada' : 'Pendiente',
                    'puntos' => $actividad->puntos,
                    'url' => route('actividades.show', $actividad->id)
                ]
            ];
        })->values()->toArray();
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
        $total = $actividades->count();
        $entregadas = $actividades->filter(fn($a) => $a->entregas->isNotEmpty())->count();
        $pendientes = $total - $entregadas;
        $proximasVencer = $actividades->filter(function ($a) {
            return $a->fecha_limite->isBetween(now(), now()->addDays(3)) && $a->entregas->isEmpty();
        })->count();

        return compact('total', 'entregadas', 'pendientes', 'proximasVencer');
    }

    // API para obtener eventos vía AJAX
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

    public function quizz()
    {

        return view('quizzprueba');
    }
}
