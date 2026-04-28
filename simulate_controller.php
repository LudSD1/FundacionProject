<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Cursos;
use App\Models\Categoria;

$currentDate = now();
$isAdmin = false;
$isLoggedIn = false;

echo "--- Simulating MenuController@lista (Guest) ---\n";
echo "Current Date: $currentDate\n";

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

echo "Categorias found: " . $categorias->count() . "\n";
foreach ($categorias as $cat) {
    echo "ID: {$cat->id} | Name: {$cat->name} | Cursos Count: {$cat->cursos_count}\n";
}

$cursos = Cursos::query()
    ->where('fecha_fin', '>=', $currentDate)
    ->where('visibilidad', 'Público')
    ->with('categorias')
    ->get();

echo "\nCursos found: " . $cursos->count() . "\n";
foreach ($cursos as $curso) {
    echo "ID: {$curso->id} | Name: {$curso->nombreCurso} | Categorias: " . $curso->categorias->count() . "\n";
}
