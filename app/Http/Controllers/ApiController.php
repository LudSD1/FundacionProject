<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function prueba()
    {
        // Datos de ejemplo para devolver en formato JSON
        $data = [
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'edad' => 30,
        ];

        return response()->json($data);
    }
}
