<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AtributosDocentes;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DocenteController extends Controller
{

    public function index()
    {
        $docente = User::role('Docente')->get();
        return response()->json([
            "results" => $docente,
        ],
        Response::HTTP_OK);

    }


    public function registerDocente(Request $request){
        
        
        // $request->validate([
        // 'name' => 'required',
        // 'appaterno' => 'required',
        // 'apmaterno'=> 'required',
        // 'CI' => 'required|CI|unique:users',
        // 'Celular' => 'required',
        // 'email' => 'required|email|unique:users',
        // 'fechadenac' => 'required',
        // ]);
        
        
        
        $user = new User();
        $user->name = $request->name;
        $user->appaterno = $request->appaterno;
        $user->apmaterno = $request->apmaterno;
        $user->CI = $request->CI;
        $user->Celular = $request->Celular;
        $user->email = $request->email;
        $user->fechadenac = $request->fechadenac;
        $user->password = bcrypt(substr($request->name,0,1).substr($request->appaterno,0,1).substr($request->apmaterno,0,1).$request->CI);
        $user->save();
        $user->assignRole('Docente');

        $atributosDocentes = new AtributosDocentes();

        $atributosDocentes->formacion = ""; 
        $atributosDocentes->Especializacion = ""; 
        $atributosDocentes->ExperienciaL = ""; 
        $atributosDocentes->docente_id = User::latest('id')->first()->id;; 
        $atributosDocentes->save();
        
            
        return response($user, Response::HTTP_CREATED);


    }




    public function edit($id)
    {
        
    }

    public function update(Request $request, $id)
    {

    }


    public function destroy($id)
    {
        //
    }
}
