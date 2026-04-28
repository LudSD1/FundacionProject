<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Cursos;
use App\Models\Categoria;

echo "--- Verificando Curso ID 3 ---\n";
$curso = Cursos::with('categorias')->find(3);
if ($curso) {
    echo "ID: " . $curso->id . "\n";
    echo "Nombre: " . $curso->nombreCurso . "\n";
    echo "Visibilidad: " . $curso->visibilidad . "\n";
    echo "Fecha Fin: " . $curso->fecha_fin . "\n";
    echo "Fecha Fin >= Ahora: " . ($curso->fecha_fin >= now() ? 'SÍ' : 'NO') . "\n";
    echo "Categorías vinculadas: " . $curso->categorias->count() . "\n";
    foreach ($curso->categorias as $cat) {
        echo " - ID: " . $cat->id . " | Nombre: " . $cat->name . "\n";
    }
} else {
    echo "Curso 3 no encontrado.\n";
}

echo "\n--- Verificando Categorías en General ---\n";
$categorias = Categoria::all();
foreach ($categorias as $cat) {
    $count = $cat->cursos()->count();
    $publicCount = $cat->cursos()->where('visibilidad', 'Público')->count();
    echo "Cat ID: {$cat->id} | Nombre: {$cat->name} | Total Cursos: {$count} | Públicos: {$publicCount}\n";
}
