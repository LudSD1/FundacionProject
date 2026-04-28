<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "--- Valores Reales en la Base de Datos (Tabla cursos) ---\n";
$visibilidades = DB::table('cursos')->select('visibilidad')->distinct()->get();
foreach ($visibilidades as $v) {
    echo "Visibilidad: '" . $v->visibilidad . "' (Length: " . strlen($v->visibilidad) . ")\n";
}

echo "\n--- Conteo de Cursos por Visibilidad y Fecha ---\n";
$now = now()->toDateTimeString();
echo "Ahora: $now\n";

$counts = DB::table('cursos')
    ->select('visibilidad', DB::raw('count(*) as total'))
    ->where('fecha_fin', '>=', $now)
    ->groupBy('visibilidad')
    ->get();

foreach ($counts as $c) {
    echo "Visibilidad: {$c->visibilidad} | Total: {$c->total}\n";
}

echo "\n--- Relaciones en curso_categoria ---\n";
$relCount = DB::table('curso_categoria')->count();
echo "Total relaciones curso_categoria: $relCount\n";

$sample = DB::table('curso_categoria')
    ->join('cursos', 'curso_categoria.curso_id', '=', 'cursos.id')
    ->join('categoria', 'curso_categoria.categoria_id', '=', 'categoria.id')
    ->select('cursos.nombreCurso', 'cursos.visibilidad', 'cursos.fecha_fin', 'categoria.name')
    ->limit(5)
    ->get();

foreach ($sample as $s) {
    echo "Curso: {$s->nombreCurso} | Vis: {$s->visibilidad} | Fin: {$s->fecha_fin} | Cat: {$s->name}\n";
}
