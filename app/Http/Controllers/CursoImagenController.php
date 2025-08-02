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
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // opcional
        ]);

        // Actualizar campos
        $imagen->titulo = $request->input('titulo');
        $imagen->descripcion = $request->input('descripcion');
        $imagen->orden = $request->input('orden', 0);
        $imagen->activo = $request->has('activo');

        // Si se sube una nueva imagen
        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $path = $file->store('cursos/imagenes', 'public');

            // (Opcional) eliminar la imagen anterior
            if ($imagen->url && \Storage::disk('public')->exists($imagen->url)) {
                \Storage::disk('public')->delete($imagen->url);
            }

            $imagen->url = $path;
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
