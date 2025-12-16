<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Ruta de Prueba de Rendimiento
|--------------------------------------------------------------------------
|
| Esta ruta temporal permite probar el rendimiento de cursosDetalle
| Acceder a: /test-performance-detalle/{curso_id}
|
*/

Route::get('/test-performance-detalle/{curso}', function (App\Models\Cursos $curso) {
    // Habilitar el log de consultas
    DB::enableQueryLog();

    // Medir tiempo de inicio
    $inicio = microtime(true);

    // Ejecutar el método del controlador
    $controller = new App\Http\Controllers\MenuController();
    $resultado = $controller->detalle($curso);

    // Calcular tiempo total
    $tiempoTotal = microtime(true) - $inicio;

    // Obtener las consultas ejecutadas
    $queries = DB::getQueryLog();

    // Preparar estadísticas
    $stats = [
        'tiempo_total_ms' => round($tiempoTotal * 1000, 2),
        'total_consultas' => count($queries),
        'tiempo_promedio_ms' => count($queries) > 0 ? round(array_sum(array_column($queries, 'time')) / count($queries), 2) : 0,
        'consulta_mas_lenta' => collect($queries)->sortByDesc('time')->first(),
        'relaciones_cargadas' => [
            'calificaciones' => $curso->relationLoaded('calificaciones'),
            'inscritos' => $curso->relationLoaded('inscritos'),
            'temas' => $curso->relationLoaded('temas'),
            'expositores' => $curso->relationLoaded('expositores'),
            'imagenes' => $curso->relationLoaded('imagenes'),
        ],
        'consultas' => array_map(function ($query) {
            return [
                'tiempo_ms' => round($query['time'], 2),
                'query' => substr($query['query'], 0, 200),
            ];
        }, $queries),
    ];

    // Determinar estado
    $estado = 'EXCELENTE';
    $alertas = [];

    if (count($queries) > 10) {
        $estado = 'ADVERTENCIA';
        $alertas[] = 'Más de 10 consultas detectadas. Posible problema N+1.';
    }

    if ($tiempoTotal > 1) {
        $estado = 'ADVERTENCIA';
        $alertas[] = 'Tiempo de ejecución mayor a 1 segundo. Considera implementar caché.';
    }

    $stats['estado'] = $estado;
    $stats['alertas'] = $alertas;

    // Retornar JSON con las estadísticas
    return response()->json($stats, 200, [], JSON_PRETTY_PRINT);
})->name('test.performance.detalle');
