<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Achievement;
use Illuminate\Support\Str;
class UserAchievementsController extends Controller
{
    public function storeLogro(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'xp_reward' => 'required|integer|min:0|max:100',
            'requirement_value' => 'required|integer|min:1',
            'type' => 'required',
            'category' => 'required',
            'icon' => 'required|string|max:10',
            'is_secret' => 'sometimes|boolean',
        ], [
            'title.required' => 'El nombre es obligatorio.',
            'title.string' => 'El nombre debe ser texto.',
            'title.max' => 'El nombre no puede tener más de 255 caracteres.',
            'description.required' => 'La descripción es obligatoria.',
            'description.string' => 'La descripción debe ser texto.',
            'xp_reward.required' => 'La recompensa XP es obligatoria.',
            'xp_reward.integer' => 'La recompensa XP debe ser un número entero.',
            'xp_reward.min' => 'La recompensa XP no puede ser menor a 0.',
            'xp_reward.max' => 'La recompensa XP no puede ser mayor a 100.',
            'requirement_value.required' => 'El valor del requisito es obligatorio.',
            'requirement_value.integer' => 'El valor del requisito debe ser un número entero.',
            'requirement_value.min' => 'El valor del requisito debe ser al menos 1.',
            'type.required' => 'El tipo es obligatorio.',
            'category.required' => 'La categoría es obligatoria.',
            'icon.required' => 'El icono es obligatorio.',
            'icon.string' => 'El icono debe ser texto.',
            'icon.max' => 'El icono no puede tener más de 10 caracteres.',
            'is_secret.boolean' => 'El campo secreto debe ser verdadero o falso.',
        ]);





        $validated['is_secret']  = $request->has('is_secret');

        $logro = new Achievement();
        $logro->title = $validated['title'];
        $logro->slug = Str::slug($validated['title']);
        $logro->description = $validated['description'];
        $logro->xp_reward = $validated['xp_reward'];
        $logro->requirement_value = $validated['requirement_value'];
        $logro->type = $validated['type'];
        $logro->category = $validated['category'];
        $logro->icon = $validated['icon'];
        $logro->is_secret = $validated['is_secret'];


        $logro->save();


        return back()->with('success', 'Logro creado exitosamente');
    }

    public function updateLogro(Request $request, $id)
    {
        $achievement = Achievement::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'xp_reward' => 'required|integer|min:0|max:100',
            'requirement_value' => 'required|integer|min:1',
            'type' => 'required',
            'category' => 'required',
            'icon' => 'required|string|max:10',
            'is_secret' => 'sometimes|boolean',
        ]);

        $validated['is_secret'] = $request->has('is_secret');
        $validated['slug'] = Str::slug($validated['title']);

        $achievement->update($validated);

        return back()->with('success', 'Logro actualizado exitosamente');
    }

    public function deleteLogro($id)
    {
        $achievement = Achievement::findOrFail($id);
        $achievement->delete();

        return back()->with('success', 'Logro desactivado exitosamente');
    }

    public function restoreLogro($id)
    {
        $achievement = Achievement::onlyTrashed()->findOrFail($id);
        $achievement->restore();

        return back()->with('success', 'Logro activado exitosamente');
    }
}
