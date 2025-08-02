<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TutorRepresentanteLegal;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;



class EstudianteController extends Controller
{
    public function registerEstudiante(Request $request){
        
   
        // $request->validate([
            
        //     'name' => 'required',
        //     'appaterno' => 'required',
        //     'apmaterno' => 'required',
        //     'CI' => 'required',
        //     'Celular' => 'required',
        //     'email' => 'required',
        //     'fechadenac' => 'required',
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
        $user->assignRole('Estudiante');
        return response($user, Response::HTTP_CREATED);



    }


    public function registerEstudianteMenor(Request $request){


    // $request->validate([

    //     'name' => 'required',
    //     'appaterno' => 'required',
    //     'apmaterno' => 'required',
    //     'CI' => 'required',
    //     'Celular' => 'required',
    //     'email' => 'required',
    //     'fechadenac' => 'required',
    // ]);


    $user = new User();
    $representante = new TutorRepresentanteLegal();




    $user->name = $request->name;
    $user->appaterno = $request->appaterno;
    $user->apmaterno = $request->apmaterno;
    $user->CI = $request->CI;
    $user->Celular = $request->CelularTutor;
    $user->email = $request->emailTutor;
    $user->fechadenac = $request->fechadenac;

    $representante->nombreTutor = $request->nombreTutor;
    $representante->appaternoTutor = $request->appaternoTutor;
    $representante->apmaternoTutor = $request->apmaternoTutor;
    $representante->Celular = $request->CelularTutor;
    $representante->CI= $request->CITutor;
    $representante->Direccion = $request->Direccion;

    $user->password = bcrypt(substr($request->name, 0, 1).substr($request->appaterno, 0, 1).substr($request->apmaterno, 0, 1).$request->CITutor);

    $user->save();

    $representante->estudiante_id = User::latest('id')->first()->id;

    $representante->save();


    $user->assignRole('Estudiante');
    return response($user, Response::HTTP_CREATED);
}    


}



    


