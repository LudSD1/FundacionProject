<?php

namespace App\Http\Controllers;

use App\Models\TareasEntrega;
use Illuminate\Http\Request;

class TareasEntregaController extends Controller
{

    public function store(Request $request)
    {
        $request -> validate([
            'entrega' => 'required'
        ]);

        $entrega = new TareasEntrega();

        $entrega->estudiante_id = $request->estudiante_id;
        $entrega->tarea_id = $request->tarea_id;

        if ($request->hasFile('entrega')) {
            $tareaEntrega = $request->file('entrega')->store('entrega', 'public');
            $entrega->archivo_entregado = $tareaEntrega;
        }

        $entrega ->save();

        return back()->with('success', 'Tarea subida correctamente');

    }



    public function delete($id)
    {

        $entrega = TareasEntrega::find($id);
        $entrega->delete();
        return back()->with('success', 'Tarea eliminada correctamente');

    }


}
