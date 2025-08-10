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
        $tab = $request->input('tab', 'activas'); // Por defecto mostrar activas

        $query = Categoria::with('parent');

        // Aplicar filtro según el tab seleccionado
        if ($tab === 'eliminadas') {
            $query->onlyTrashed();
        } else {
            $query->whereNull('deleted_at');
        }

        // Aplicar búsqueda si existe
        if ($busqueda) {
            $query->where('name', 'like', '%' . $busqueda . '%');
        }

        $categorias = $query->orderBy('id', 'desc')->get();

        // Contar para los tabs
        $countActivas = Categoria::whereNull('deleted_at')->count();
        $countEliminadas = Categoria::onlyTrashed()->count();

        return view('categorias.index', compact('categorias', 'tab', 'countActivas', 'countEliminadas'));
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

public function destroy(Request $request, $id)
{
    try {
        $categoria = Categoria::findOrFail($id);

        if ($categoria->hasActiveChildren()) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar esta categoría porque tiene subcategorías activas.',
                'children_count' => $categoria->children->count()
            ], 422);
        }

        $categoria->cursos()->detach();
        $categoria->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Categoría eliminada exitosamente.',
                'categoria_id' => $categoria->id,
                'categoria_name' => $categoria->name
            ]);
        }

        return redirect()->back()->with('success', 'Categoría eliminada exitosamente.');

    } catch (\Exception $e) {
        dd($e->getMessage()); // Esto mostrará el error real

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la categoría: ' . $e->getMessage()
            ], 500);
        }

        return redirect()->back()->with('error', 'Error al eliminar la categoría.');
    }
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
