<?php

namespace App\Http\Controllers;

use App\Models\EdadDirigida;
use Illuminate\Http\Request;

class EdadDirigidaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Administrador.NuevoEdadDirigida');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|unique:edad_dirigidas,nombre', // Verifica la unicidad en la tabla 'niveles' columna 'nombre'
        ], [
            'nombre.required' => 'El nombre de Edad Recomendada es obligatorio.',
            'nombre.unique' => 'Ya existe la Edad Recomendada con este nombre.',
        ]);

        $nivel = new EdadDirigida();
        $nivel->nombre = strtoupper($request->nombre);
        $nivel->edad1 = strtoupper($request->edad1);
        $nivel->edad2 = strtoupper($request->edad2);
        $nivel->save();

        return back()->with('success', 'Guardado con éxito!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EdadDirigida  $edadDirigida
     * @return \Illuminate\Http\Response
     */
    public function show(EdadDirigida $edadDirigida)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EdadDirigida  $edadDirigida
     * @return \Illuminate\Http\Response
     */
    public function edit(EdadDirigida $edadDirigida)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EdadDirigida  $edadDirigida
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EdadDirigida $edadDirigida)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EdadDirigida  $edadDirigida
     * @return \Illuminate\Http\Response
     */
    public function destroy(EdadDirigida $edadDirigida)
    {
        //
    }
}
