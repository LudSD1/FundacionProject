<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Cursos;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;

echo "--- DIAGNÓSTICO DE CATEGORÍAS ---\n";

$now = now();
$publicCourses = Cursos::where('fecha_fin', '>=', $now)
    ->where('visibilidad', 'Público')
    ->get();

echo "Cursos Públicos Activos: " . $publicCourses->count() . "\n";

foreach ($publicCourses as $curso) {
    echo "Curso ID: {$curso->id} | Título: {$curso->nombreCurso} | Visibilidad: {$curso->visibilidad}\n";
    $cats = $curso->categorias;
    echo "  Categorías vinculadas: " . $cats->count() . "\n";
    foreach ($cats as $cat) {
        echo "    - ID: {$cat->id} | Nombre: {$cat->name} | Deleted At: " . ($cat->deleted_at ?? 'null') . "\n";
    }
}

$allCats = Categoria::all();
echo "\nTotal Categorías en DB: " . $allCats->count() . "\n";
foreach ($allCats as $cat) {
    $count = DB::table('curso_categoria')->where('categoria_id', $cat->id)->count();
    echo "Cat ID: {$cat->id} | Name: {$cat->name} | Pivot entries: {$count}\n";
}

$catsWithActiveCourses = Categoria::whereHas('cursos', function ($q) use ($now) {
    $q->where('fecha_fin', '>=', $now)
      ->where('visibilidad', 'Público');
})->get();

echo "\nCategorías con Cursos Públicos Activos (según Eloquent): " . $catsWithActiveCourses->count() . "\n";
foreach ($catsWithActiveCourses as $cat) {
    echo "  - {$cat->name}\n";
}
