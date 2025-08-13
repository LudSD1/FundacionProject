<?php

namespace App\Http\Controllers;

use App\Models\CursoImagen;
use App\Models\Cursos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CursoImagenController extends Controller
{
    public function index(Cursos $curso)
    {
        return view('Cursos.ImagenesCursos', [
            'curso' => $curso,
            'imagenes' => $curso->imagenes()->orderBy('orden')->get()
        ]);
    }
    public function store(Request $request, Cursos $curso)
    {
        $request->validate([
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'titulo' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'orden' => 'nullable|integer'
        ], [
            'imagen.required' => 'La imagen es obligatoria',
            'imagen.image' => 'El archivo debe ser una imagen válida',
            'imagen.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif o webp',
            'imagen.max' => 'La imagen no debe pesar más de 2MB (2048 kilobytes)',
            'titulo.string' => 'El título debe ser texto',
            'titulo.max' => 'El título no debe exceder los 255 caracteres',
            'descripcion.string' => 'La descripción debe ser texto',
            'orden.integer' => 'El orden debe ser un número entero'
        ]);

        $path = $request->file('imagen')->store('cursos', 'public');

        $curso->imagenes()->create([
            'url' => 'storage/' . $path,
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'orden' => $request->orden ?? 0,
            'activo' => true
        ]);

        return back()->with('success', 'Imagen subida con éxito');
    }

    public function update(Request $request, $id)
    {
        $imagen = CursoImagen::findOrFail($id);

        $request->validate([
            'titulo' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'orden' => 'nullable|integer|min:0',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'titulo.string' => 'El título debe ser texto',
            'titulo.max' => 'El título no debe exceder los 255 caracteres',
            'descripcion.string' => 'La descripción debe ser texto',
            'orden.integer' => 'El orden debe ser un número entero',
            'orden.min' => 'El orden debe ser mayor o igual a 0',
            'imagen.image' => 'El archivo debe ser una imagen válida',
            'imagen.mimes' => 'La imagen debe ser de tipo: jpg, jpeg, png, webp',
            'imagen.max' => 'La imagen no debe pesar más de 2MB (2048 kilobytes)',
        ]);

        // Actualizar campos
        $imagen->titulo = $request->input('titulo');
        $imagen->descripcion = $request->input('descripcion');
        $imagen->orden = $request->input('orden', 0);
        $imagen->activo = $request->has('activo');

        // Si se sube una nueva imagen
        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $path = $file->store('cursos', 'public');

            // Eliminar la imagen anterior si existe
            if ($imagen->url) {
                // Remover el prefijo 'storage/' para obtener la ruta real del archivo
                $oldPath = str_replace('storage/', '', $imagen->url);
                if (\Storage::disk('public')->exists($oldPath)) {
                    \Storage ::disk('public')->delete($oldPath);
                }
            }

            // Guardar la nueva ruta con el prefijo 'storage/' para consistencia
            $imagen->url = 'storage/' . $path;
        }

        $imagen->save();

        return redirect()->back()->with('success', 'Imagen actualizada correctamente.');
    }


    public function destroy($id)
    {
        $imagen = CursoImagen::findOrFail($id);
        $imagen->activo = false;
        $imagen->save();

        return redirect()->back()->with('success', 'Imagen desactivada correctamente.');
    }

    public function destroyPermanent($id)
    {
        $imagen = CursoImagen::findOrFail($id);

        // Eliminar la imagen del almacenamiento
        if ($imagen->url && Storage::disk('public')->exists($imagen->url)) {
            Storage::disk('public')->delete($imagen->url);
        }

        $imagen->delete();

        return redirect()->back()->with('success', 'Imagen eliminada correctamente.');
    }

    public function restore($id)
    {
        $imagen = CursoImagen::findOrFail($id);
        $imagen->activo = true;
        $imagen->save();

        return redirect()->back()->with('success', 'Imagen restaurada correctamente.');
    }
}
