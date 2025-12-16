<?php

/**
 * Script de Prueba de Rendimiento - cursosDetalle
 *
 * Este script prueba el rendimiento de la vista cursosDetalle
 * y muestra las consultas SQL ejecutadas.
 *
 * Uso: php artisan tinker < test_performance.php
 * O ejecutar directamente en tinker
 */

// Habilitar el log de consultas
DB::enableQueryLog();

// Obtener un curso de ejemplo (ajusta el ID según tu base de datos)
$cursoId = 1; // Cambia esto por un ID válido de tu base de datos

echo "\n=== PRUEBA DE RENDIMIENTO - cursosDetalle ===\n\n";

// Medir tiempo de inicio
$inicio = microtime(true);

// Simular la llamada al controlador
$controller = new App\Http\Controllers\MenuController();
$curso = App\Models\Cursos::findOrFail($cursoId);

// Ejecutar el método detalle (sin renderizar la vista)
try {
    $resultado = $controller->detalle($curso);
    $tiempoTotal = microtime(true) - $inicio;

    // Obtener las consultas ejecutadas
    $queries = DB::getQueryLog();

    echo "✅ PRUEBA COMPLETADA CON ÉXITO\n\n";
    echo "📊 ESTADÍSTICAS:\n";
    echo "   - Tiempo total: " . round($tiempoTotal * 1000, 2) . " ms\n";
    echo "   - Consultas SQL ejecutadas: " . count($queries) . "\n\n";

    echo "📝 DETALLE DE CONSULTAS:\n";
    echo str_repeat("-", 80) . "\n";

    foreach ($queries as $index => $query) {
        $tiempo = round($query['time'], 2);
        echo ($index + 1) . ". [" . $tiempo . "ms] " . substr($query['query'], 0, 100);
        if (strlen($query['query']) > 100) {
            echo "...";
        }
        echo "\n";
    }

    echo str_repeat("-", 80) . "\n\n";

    // Análisis de rendimiento
    $tiempoPromedio = array_sum(array_column($queries, 'time')) / count($queries);
    $consultaMasLenta = collect($queries)->sortByDesc('time')->first();

    echo "📈 ANÁLISIS:\n";
    echo "   - Tiempo promedio por consulta: " . round($tiempoPromedio, 2) . " ms\n";
    echo "   - Consulta más lenta: " . round($consultaMasLenta['time'], 2) . " ms\n";
    echo "   - Query más lenta: " . substr($consultaMasLenta['query'], 0, 80) . "...\n\n";

    // Verificar que las relaciones estén cargadas
    echo "🔍 RELACIONES CARGADAS:\n";
    $relaciones = [
        'calificaciones' => $curso->relationLoaded('calificaciones'),
        'inscritos' => $curso->relationLoaded('inscritos'),
        'temas' => $curso->relationLoaded('temas'),
        'expositores' => $curso->relationLoaded('expositores'),
        'imagenes' => $curso->relationLoaded('imagenes'),
    ];

    foreach ($relaciones as $nombre => $cargada) {
        $estado = $cargada ? '✅' : '❌';
        echo "   $estado $nombre\n";
    }

    echo "\n";

    // Recomendaciones
    if (count($queries) > 10) {
        echo "⚠️  ADVERTENCIA: Se detectaron más de 10 consultas.\n";
        echo "   Revisa si hay consultas N+1 adicionales.\n\n";
    } else {
        echo "✅ EXCELENTE: Número de consultas optimizado (< 10)\n\n";
    }

    if ($tiempoTotal > 1) {
        echo "⚠️  ADVERTENCIA: Tiempo de ejecución mayor a 1 segundo.\n";
        echo "   Considera implementar caché.\n\n";
    } else {
        echo "✅ EXCELENTE: Tiempo de respuesta rápido (< 1s)\n\n";
    }
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "   Archivo: " . $e->getFile() . "\n";
    echo "   Línea: " . $e->getLine() . "\n\n";

    // Mostrar consultas ejecutadas hasta el error
    $queries = DB::getQueryLog();
    echo "Consultas ejecutadas antes del error: " . count($queries) . "\n";
}

echo "=== FIN DE LA PRUEBA ===\n\n";
