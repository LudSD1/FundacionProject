<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Cursos;
use App\Models\Categoria;
use Carbon\Carbon;

$now = now();
echo "Current Time: " . $now . "\n";

$cursos = Cursos::where('fecha_fin', '>=', $now)
    ->with('categorias')
    ->get();

echo "Total cursos activos (fecha_fin >= now): " . $cursos->count() . "\n";

foreach ($cursos as $c) {
    echo "Curso ID: {$c->id} | Nombre: {$c->nombreCurso} | Visibilidad: {$c->visibilidad} | Categorias: " . $c->categorias->count() . "\n";
    foreach ($c->categorias as $cat) {
        echo "  - ID: {$cat->id} | Name: {$cat->name} | Trashed: " . ($cat->trashed() ? 'YES' : 'NO') . "\n";
    }
}

echo "\n--- Categorias Globales ---\n";
$categorias = Categoria::all();
echo "Total Categorias: " . $categorias->count() . "\n";
foreach ($categorias as $cat) {
    echo "ID: {$cat->id} | Name: {$cat->name}\n";
}
