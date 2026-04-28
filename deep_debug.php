<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Cursos;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;

echo "--- Diagnóstico Profundo ---\n";

$now = now();
echo "Fecha actual: " . $now . "\n";

// 1. Verificar Categorías
$allCats = DB::table('categoria')->get();
echo "\nCategorías en DB (Raw):\n";
foreach ($allCats as $cat) {
    echo "ID: {$cat->id} | Name: {$cat->name} | Deleted: " . ($cat->deleted_at ?? 'NULL') . "\n";
}

// 2. Verificar Cursos Públicos
$publicCursos = DB::table('cursos')
    ->where('visibilidad', 'Público')
    ->where('fecha_fin', '>=', $now)
    ->get();

echo "\nCursos Públicos Activos (Raw):\n";
foreach ($publicCursos as $curso) {
    echo "ID: {$curso->id} | Nombre: {$curso->nombreCurso} | Fin: {$curso->fecha_fin} | Deleted: " . ($curso->deleted_at ?? 'NULL') . "\n";
}

// 3. Verificar Relaciones
$relaciones = DB::table('curso_categoria')->get();
echo "\nRelaciones curso_categoria (Raw):\n";
foreach ($relaciones as $rel) {
    echo "Curso ID: {$rel->curso_id} | Cat ID: {$rel->categoria_id}\n";
}

// 4. Simular Query de MenuController para Categorías
$isAdmin = false;
$isLoggedIn = false;
$currentDate = $now;

$catsQuery = Categoria::whereHas('cursos', function ($q) use ($isAdmin, $isLoggedIn, $currentDate) {
    $q->where('fecha_fin', '>=', $currentDate);
    if (!$isAdmin) {
        if ($isLoggedIn) {
            $q->whereIn('visibilidad', ['Público', 'Solo Registrados']);
        } else {
            $q->where('visibilidad', 'Público');
        }
    }
});

echo "\nCategorías según Eloquent (Simulando Visitante):\n";
$catsEloquent = $catsQuery->get();
if ($catsEloquent->isEmpty()) {
    echo "¡NINGUNA CATEGORÍA ENCONTRADA POR ELOQUENT!\n";
    
    // Ver si quitando visibilidad aparecen
    $catsNoVis = Categoria::whereHas('cursos', function ($q) use ($currentDate) {
        $q->where('fecha_fin', '>=', $currentDate);
    })->get();
    echo "Categorías si quitamos filtro visibilidad: " . $catsNoVis->count() . "\n";
    
    // Ver si quitando fecha aparecen
    $catsNoDate = Categoria::whereHas('cursos', function ($q) {
        $q->where('visibilidad', 'Público');
    })->get();
    echo "Categorías si quitamos filtro fecha: " . $catsNoDate->count() . "\n";
} else {
    foreach ($catsEloquent as $cat) {
        echo "ID: {$cat->id} | Name: {$cat->name}\n";
    }
}

// 5. Verificar Cursos según Eloquent
$cursosEloquent = Cursos::with('categorias')
    ->where('visibilidad', 'Público')
    ->where('fecha_fin', '>=', $now)
    ->get();

echo "\nCursos según Eloquent (Públicos Activos):\n";
foreach ($cursosEloquent as $c) {
    echo "ID: {$c->id} | Nombre: {$c->nombreCurso} | Cats Count: " . $c->categorias->count() . "\n";
    foreach ($c->categorias as $cat) {
        echo "  - Cat ID: {$cat->id} | Name: {$cat->name}\n";
    }
}
