<?php

namespace App\Http\Controllers;

use App\Models\Subtema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubtemaController extends Controller
{
    public function store(Request $request, $temaId)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Obtener el último número de orden dentro del mismo tema
        $ultimoOrden = Subtema::where('tema_id', $temaId)->max('orden') ?? 0;

        $rutaImagen = null;
        if ($request->hasFile('imagen')) {
            $rutaImagen = $request->file('imagen')->store('subtemas', 'public');
        }

        Subtema::create([
            'titulo_subtema' => $request->titulo,
            'descripcion' => $request->descripcion,
            'tema_id' => $temaId,
            'imagen' => $rutaImagen, // Guarda la ruta de la imagen
            'orden' => $ultimoOrden + 1, // Asignar el siguiente número de orden
        ]);

        return back()->with('success', 'Subtema creado correctamente.');
    }



    // Actualiza un subtema existente
    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación de la imagen
        ]);

        $subtema = Subtema::findOrFail($id);
        $rutaImagen = $subtema->imagen; // Mantén la imagen actual por defecto

        if ($request->hasFile('imagen')) {
            // Elimina la imagen anterior si existe
            if ($rutaImagen && Storage::disk('public')->exists($rutaImagen)) {
                Storage::disk('public')->delete($rutaImagen);
            }

            // Guarda la nueva imagen
            $rutaImagen = $request->file('imagen')->store('subtemas', 'public');
        }

        // Actualiza los campos del subtema
        $subtema->update([
            'titulo_subtema' => $request->titulo,
            'descripcion' => $request->descripcion,
            'imagen' => $rutaImagen,
        ]);

        return back()->with('success', 'Subtema actualizado correctamente.');
    }
    // Elimina (soft delete) un subtema
    public function delete($id)
    {
        $subtema = Subtema::findOrFail($id);

        // Soft delete del subtema
        $subtema->delete();

        return back()->with('success', 'Subtema eliminado correctamente.');
    }
    public function restore($id)
    {
        $subtema = Subtema::withTrashed()->findOrFail($id);

        // Restaurar el subtema
        $subtema->restore();

        return back()->with('success', 'Subtema restaurado correctamente.');
    }
}
