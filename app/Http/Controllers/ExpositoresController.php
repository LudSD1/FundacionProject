<?php

namespace App\Http\Controllers;

use App\Models\Cursos;
use App\Models\Expositores;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ExpositoresController extends Controller
{
    public function ListaExpositores()
    {
        $expositores = Expositores::withTrashed()->get();
        return view('Administrador.ListaExpositores', compact('expositores'));
    }

    public function edit($id)
    {
        $expositor = Expositores::findOrFail($id);
        return response()->json($expositor);
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'especialidad' => 'required|string|max:100',
            'empresa' => 'required|string|max:100',
            'biografia' => 'nullable|string',
            'imagen' => 'nullable|image|max:2048',
            'linkedin' => 'nullable|url',
        ]);

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('expositores', 'public');
        }

        Expositores::create($data);

        return back()->with('success', 'Expositor agregado correctamente');
    }

    public function update(Request $request, $id)
    {
        $expositor = Expositores::findOrFail($id);

        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'especialidad' => 'required|string|max:100',
            'empresa' => 'required|string|max:100',
            'biografia' => 'nullable|string',
            'imagen' => 'nullable|image|max:2048',
            'linkedin' => 'nullable|url'
        ], [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser un texto válido.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.',

            'especialidad.required' => 'La especialidad es obligatoria.',
            'especialidad.string' => 'La especialidad debe ser un texto válido.',
            'especialidad.max' => 'La especialidad no puede tener más de 100 caracteres.',

            'empresa.required' => 'La empresa es obligatoria.',
            'empresa.string' => 'La empresa debe ser un texto válido.',
            'empresa.max' => 'La empresa no puede tener más de 100 caracteres.',

            'biografia.string' => 'La biografía debe ser un texto válido.',

            'imagen.image' => 'El archivo debe ser una imagen.',
            'imagen.max' => 'La imagen no puede superar los 2MB.',

            'linkedin.url' => 'La URL de LinkedIn no es válida.'
        ]);

        if ($request->hasFile('imagen')) {
            if ($expositor->imagen && Storage::disk('public')->exists($expositor->imagen)) {
                Storage::disk('public')->delete($expositor->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('expositores', 'public');
        }

        $expositor->update($data);

        return back()->with('success', 'Expositor actualizado correctamente');
    }



    public function destroy($id)
    {
        $expositor = Expositores::findOrFail($id);
        $expositor->delete();

        return back()->with(['success' => 'Expositor desactivado correctamente']);
    }

    public function restore($id)
    {
        $expositor = Expositores::withTrashed()->findOrFail($id);
        $expositor->restore();

        return back()->with(['success' => 'Expositor activado correctamente']);
    }


    public function asignarExpositores(Request $request, $cursoId)
    {
        $curso = Cursos::findOrFail($cursoId);

        // Validar entrada mínima
        $validated = $request->validate([
            'expositores' => 'required|array',
            'expositores.*.id' => 'required|exists:expositores,id',
            'expositores.*.cargo' => 'nullable|string',
            'expositores.*.tema' => 'nullable|string',
            'expositores.*.orden' => 'nullable|integer',
            'expositoresSeleccionados' => 'required|array',
        ]);

        // Solo procesar los seleccionados
        $syncData = [];

        foreach ($validated['expositoresSeleccionados'] as $index) {
            $expositor = $validated['expositores'][$index];
            $syncData[$expositor['id']] = [
                'cargo' => $expositor['cargo'] ?? null,
                'tema' => $expositor['tema'] ?? null,
                'orden' => $expositor['orden'] ?? 0,
            ];
        }

        $curso->expositores()->sync($syncData);

        return back()->with(['message' => 'Expositores asignados correctamente']);
    }


    public function quitarExpositor($cursoId, $expositorId)
    {
        $curso = Cursos::findOrFail($cursoId);
        $curso->expositores()->detach($expositorId);

        return back()->with('success', 'Expositor quitado correctamente');
    }
}
