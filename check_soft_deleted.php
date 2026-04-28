<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Categoria;
use Illuminate\Support\Facades\DB;

echo "--- CHECK SOFT DELETED CATEGORIES ---\n";

$all = Categoria::withTrashed()->get();
echo "Total (including trashed): " . $all->count() . "\n";

foreach ($all as $cat) {
    echo "ID: {$cat->id} | Name: {$cat->name} | Trashed: " . ($cat->trashed() ? 'YES' : 'NO') . "\n";
}

$pivot = DB::table('curso_categoria')->get();
echo "\nPivot table entries: " . $pivot->count() . "\n";
foreach ($pivot as $entry) {
    echo "Curso ID: {$entry->curso_id} | Categoria ID: {$entry->categoria_id}\n";
}
