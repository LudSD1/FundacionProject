<?php

namespace App\Http\Controllers;

use App\Models\Cursos_Horario;
use App\Models\Horario;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'dia' => 'required|string|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
        ]);

        // Crear el horario
        $horario = Horario::create([
            'dia' => $request->dia,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
        ]);

        // Asociar el horario al curso
        Cursos_Horario::create([
            'curso_id' => $request->curso_id,
            'horario_id' => $horario->id,
        ]);

        return redirect()->route('Curso', $request->curso_id)
            ->with('success', 'Horario agregado correctamente.');
    }

    /**
     * Mostrar la vista para editar un horario.
     */
    public function edit($id)
    {
        $horario = Horario::findOrFail($id);
        return view('horarios.edit', compact('horario'));
    }


    public function update(Request $request, $id)
    {


        // Validar los datos del formulario
        $request->validate([
            'dia' => 'required|string|in:lunes,martes,miércoles,jueves,viernes,sábado,domingo',
            'hora_inicio' => 'required',
            'hora_fin' => 'required|after:hora_inicio',
        ], [
            'dia.required' => 'El campo Día es obligatorio.',
            'dia.in' => 'El Día seleccionado no es válido.',

            'hora_inicio.required' => 'La Hora de Inicio es obligatoria.',

            'hora_fin.required' => 'La Hora de Fin es obligatoria.',
            'hora_fin.after' => 'La Hora de Fin debe ser posterior a la Hora de Inicio.',
        ]);

            $horario = Horario::findOrFail($id);

            $horario->dia = $request->dia;
            $horario->hora_inicio = $request->hora_inicio;
            $horario->hora_fin = $request->hora_fin;
            $horario->save();

            // Redireccionar con un mensaje de éxito
            return redirect()->back()->with('success', 'Horario actualizado correctamente.');
            // Manejar cualquier excepción que ocurra durante el proceso
    }

    /**
     * Eliminar un horario.
     */
    public function delete($id)
    {
        $horario = Cursos_Horario::findOrFail($id);
        $horario->delete();

        return redirect()->back()->with('success', 'Horario eliminado correctamente.');
    }

    public function restore($id)
    {
        $horario = Cursos_Horario::withTrashed()->findOrFail($id);
        $horario->restore();

        return redirect()->back()->with('success', 'Horario restaurado correctamente.');
    }

}
