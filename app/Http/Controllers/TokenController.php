<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class TokenController extends Controller
{

    public function getToken(Request $request)
    {
        // Asegúrate de que el usuario esté autenticado
        $user = Auth::user();

        dd($user);

        if ($user) {
            // Generar o recuperar el token
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => $user->name
            ]);
        }else{

            return response()->json(['error' => 'Unauthenticated'], 401);
        }

    }
}
