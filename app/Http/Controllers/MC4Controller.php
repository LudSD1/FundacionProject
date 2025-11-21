<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MC4Controller extends Controller
{
       public function generarToken()
    {
        $response = Http::asForm()->post('https://auth.mc4.com.bo/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => env('MC4_CLIENT_ID'),
            'client_secret' => env('MC4_CLIENT_SECRET'),
        ]);

        return $response->json(); // Devuelve access_token
    }
}
  