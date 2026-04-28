<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Cursos;

$curso = Cursos::with('categorias')->first();

if ($curso) {
    echo "Curso ID: " . $curso->id . "\n";
    echo "Categorias count (via property): " . $curso->categorias->count() . "\n";
    echo "Categorias count (via method): " . $curso->categorias()->count() . "\n";
    
    foreach ($curso->categorias as $cat) {
        echo " - Cat ID: " . $cat->id . " Name: " . $cat->name . "\n";
    }
} else {
    echo "No courses found.\n";
}
