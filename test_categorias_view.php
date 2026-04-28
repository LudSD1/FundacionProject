<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Cursos;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;

echo "--- TEST CATEGORIAS VIEW LOGIC ---\n";

$currentDate = now();
$isAdmin = false;
$isLoggedIn = false;

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
    ->withCount(['cursos' => function ($q) use ($isAdmin, $isLoggedIn, $currentDate) {
        $q->where('fecha_fin', '>=', $currentDate);
        if (!$isAdmin) {
            if ($isLoggedIn) {
                $q->whereIn('visibilidad', ['Público', 'Solo Registrados']);
            } else {
                $q->where('visibilidad', 'Público');
            }
        }
    }])
    ->orderBy('name')
    ->get();

echo "Total Categorías encontradas: " . $categorias->count() . "\n";
foreach ($categorias as $cat) {
    echo "ID: {$cat->id} | Name: {$cat->name} | Cursos Count: {$cat->cursos_count}\n";
}

echo "\n--- CURSOS ---\n";
$query = Cursos::query()
    ->with(['categorias'])
    ->where('fecha_fin', '>=', $currentDate)
    ->where('visibilidad', 'Público');

$cursos = $query->get();
echo "Total Cursos Públicos Activos: " . $cursos->count() . "\n";
foreach ($cursos as $curso) {
    echo "Curso: {$curso->nombreCurso} | Categorías: " . $curso->categorias->count() . "\n";
    foreach ($curso->categorias as $c) {
        echo "  - {$c->name}\n";
    }
}
