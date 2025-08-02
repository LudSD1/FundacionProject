<?php

namespace App\Http\Controllers;

use App\Models\CursoCategoria;
use Illuminate\Http\Request;

class CategoriaCursoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:curso_categoria,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'parent_id' => 'nullable|exists:curso_categoria,id',
        ]);

        $categoria = new CursoCategoria();
        $categoria->name = $request->name;
        $categoria->slug = $request->slug;
        $categoria->description = $request->description;
        $categoria->parent_id = $request->parent_id;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categoria_imagenes', 'public');
            $categoria->image = $path;
        }

        $categoria->save();

        return back()->with('success', 'Categoría creada exitosamente.');
    }
}
