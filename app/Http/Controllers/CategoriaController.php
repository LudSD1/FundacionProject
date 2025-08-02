<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use BotMan\BotMan\Storages\Storage;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{

    public function index(Request $request)
    {
        $busqueda = $request->input('busqueda');

        $categorias = Categoria::with('parent')
            ->withTrashed()
            ->when($busqueda, function ($query, $busqueda) {
                $query->where('name', 'like', '%' . $busqueda . '%');
            })
            ->orderBy('id', 'desc')
            ->get();

        return view('categorias.index', compact('categorias'));
    }


    public function create()
    {
        // Aquí puedes mostrar el formulario para crear una nueva categoría
        return view('categorias.create');
    }

    public function store(Request $request)
    {
        // Aquí puedes manejar la lógica para almacenar una nueva categoría
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categoria,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'parent_id' => 'nullable|exists:categoria,id',
        ]);

        $categoria = new Categoria();
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
    public function edit($id)
    {
        // Aquí puedes mostrar el formulario para editar una categoría existente
        $categoria = Categoria::findOrFail($id);
        return view('categorias.edit', compact('categoria'));
    }
    public function update(Request $request, $id)
    {
        // Aquí puedes manejar la lógica para actualizar una categoría existente
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categoria,slug,' . $id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'parent_id' => 'nullable|exists:categoria,id',
        ]);

        $categoria = Categoria::findOrFail($id);
        $categoria->name = $request->name;
        $categoria->slug = $request->slug;
        $categoria->description = $request->description;
        $categoria->parent_id = $request->parent_id;

        if ($request->hasFile('image')) {
            // Eliminar la imagen anterior si existe
            if ($categoria->image) {
                Storage::disk('public')->delete($categoria->image);
            }
            $path = $request->file('image')->store('categoria_imagenes', 'public');
            $categoria->image = $path;
        }

        $categoria->save();

        return back()->with('success', 'Categoría actualizada exitosamente.');
    }
    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->delete();

        return back()->with('success', 'Categoría eliminada exitosamente.');
    }
    public function show($id)
    {
        $categoria = Categoria::findOrFail($id);
        return view('categorias.show', compact('categoria'));
    }
    public function restore($id)
    {
        $categoria = Categoria::withTrashed()->findOrFail($id);
        $categoria->restore();

        return back()->with('success', 'Categoría restaurada exitosamente.');
    }
    public function forceDelete($id)
    {
        $categoria = Categoria::withTrashed()->findOrFail($id);
        $categoria->forceDelete();

        return back()->with('success', 'Categoría eliminada permanentemente.');
    }
    public function trashed()
    {
        $categorias = Categoria::onlyTrashed()->get();
        return view('categorias.trashed', compact('categorias'));
    }
    public function restoreAll()
    {
        Categoria::onlyTrashed()->restore();

        return back()->with('success', 'Todas las categorías restauradas exitosamente.');
    }
    public function forceDeleteAll()
    {
        Categoria::onlyTrashed()->forceDelete();

        return back()->with('success', 'Todas las categorías eliminadas permanentemente.');
    }
    public function search(Request $request)
    {
        $query = $request->input('query');
        $categorias = Categoria::where('name', 'LIKE', '%' . $query . '%')->get();

        return view('categorias.index', compact('categorias'));
    }
}
