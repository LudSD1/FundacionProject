<?php

namespace App\Http\Controllers;

use App\Models\Tema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemaController extends Controller
{
    // Lista los temas de un curso
    public function index($cursoId)
    {
        $temas = Tema::where('curso_id', $cursoId)->with('subtemas')->get();
        return view('temas.index', compact('temas', 'cursoId'));
    }

public function store(Request $request, $cursoId)
{
    $request->validate([
        'titulo' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $ultimoOrden = Tema::where('curso_id', $cursoId)->max('orden') ?? 0;



    $rutaImagen = null;

    if ($request->hasFile('imagen')) {
        $rutaImagen = $request->file('imagen')->store('temas', 'public');
    }

    Tema::create([
        'titulo_tema' => $request->titulo,
        'descripcion' => $request->descripcion,
        'imagen' => $rutaImagen, // Guarda la ruta de la imagen
        'curso_id' => $cursoId,
        'orden' => $ultimoOrden+1,
    ]);

    return back()->with('success', 'Tema creado correctamente.');
}

public function update(Request $request, $id)
{
    $request->validate([
        'titulo' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $tema = Tema::findOrFail($id);

    if ($request->hasFile('imagen')) {
        // Eliminar imagen anterior si existe
        if ($tema->imagen) {
            Storage::disk('public')->delete($tema->imagen);
        }

        // Guardar la nueva imagen
        $rutaImagen = $request->file('imagen')->store('temas', 'public');
        $tema->imagen = $rutaImagen;
    }

    $tema->update([
        'titulo_tema' => $request->titulo,
        'descripcion' => $request->descripcion,
        'imagen' => $tema->imagen,
    ]);

    return back()->with('success', 'Tema actualizado correctamente.');
}



public function destroy($id)
{
    $tema = Tema::findOrFail($id);
    $tema->delete();

    return back()->with('success', 'Tema eliminado correctamente.');
}

public function restore($id)
{
    $tema = Tema::onlyTrashed()->findOrFail($id); // Busca el tema en los eliminados
    $tema->restore();

    return back()->with('success', 'Tema restaurado correctamente.');
}

}
