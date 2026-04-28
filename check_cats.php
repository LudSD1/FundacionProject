<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Categoria;
use App\Models\Cursos;
use Carbon\Carbon;

$currentDate = Carbon::now();

echo "--- Diagnóstico de Categorías ---\n";

$totalCats = Categoria::count();
echo "Total categorías: $totalCats\n";

$catsWithCursos = Categoria::whereHas('cursos')->get();
echo "Categorías con ALGUN curso (cualquiera): " . $catsWithCursos->count() . "\n";
foreach ($catsWithCursos as $cat) {
    echo " - " . $cat->name . " (ID: " . $cat->id . ") Cursos: " . $cat->cursos()->count() . "\n";
}

$catsVisibles = Categoria::whereHas('cursos', function($q) use ($currentDate) {
    $q->where('fecha_fin', '>=', $currentDate)
      ->whereIn('visibilidad', ['Público', 'Solo Registrados']);
})->get();

echo "\nCategorías VISIBLES (Público/Solo Registrados y fecha_fin >= ahora): " . $catsVisibles->count() . "\n";
foreach ($catsVisibles as $cat) {
    $count = $cat->cursos()
        ->where('fecha_fin', '>=', $currentDate)
        ->whereIn('visibilidad', ['Público', 'Solo Registrados'])
        ->count();
    echo " - " . $cat->name . " (ID: " . $cat->id . ") Cursos visibles: $count\n";
}

echo "\n--- Cursos con sus visibilidades ---\n";
$cursos = Cursos::all();
foreach ($cursos as $curso) {
    echo "Curso ID: {$curso->id}, Nombre: {$curso->nombre}, Visibilidad: {$curso->visibilidad}, Fin: {$curso->fecha_fin}, Categorías: " . $curso->categorias->pluck('name')->implode(', ') . "\n";
}
