<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Cursos;

foreach(Cursos::all() as $c) {
    echo "ID: {$c->id} | Tipo: {$c->tipo}\n";
}
