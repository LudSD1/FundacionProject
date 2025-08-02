<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AtributosDocentes;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
   


    public function login(Request $request){

        $credentials = $request->validate([
            'email' => ['required', 'email'], 
            'password' => ['required']]);
            
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $request->user()->createToken('token')->plainTextToken; 
            $cookie = cookie('cookie_token', $token, 60 * 24);
        return response(["token"=>$token], Response::HTTP_OK)->withoutCookie($cookie); } else {
        return response (Response::HTTP_UNAUTHORIZED);
        }
    }

    public function UserProfile(){

        

        $atributos = DB::table('atributos_docentes')
        ->where('docente_id', '=', Auth::user()->id)// joining the contacts table , where user_id and contact_user_id are same
        ->select('atributos_docentes.*')
        ->get();
        
        return response()->json([
            "message" => "userProfile OK",
            "userData" => auth()->user(),
            "Rol" => Auth::user()->roles->pluck('name'),
            "Atributos" =>  $atributos,

        ],
        Response::HTTP_OK
        
    );
    } 


    public function show($id)
    {
        $usuario = User::findOrFail($id);

        $atributos = DB::table('atributos_docentes')
        ->where('docente_id', '=', $id)// joining the contacts table , where user_id and contact_user_id are same
        ->select('atributos_docentes.*')
        ->get();

        if($usuario->roles->pluck('name') == "Docente"){
            return response()->json([
                "message" => "UserProfile OK",
                "userData" => $usuario,
                "Rol" => $usuario->roles->pluck('name'),
                "atributos" => $usuario->atributosdocente,
    
            ],);
        }else{

            return response()->json([
                "message" => "UserProfile OK",
                "userData" => $usuario,
                "Rol" => $usuario->roles->pluck('name'),
    
            ],);
        }

    }


    public function UserProfileEdit(Request $request){

        $request->validate([
            
            'Celular' => 'required',
            'fechadenac' => 'required',
            'confirmpassword' => 'required'
        
        ]);


        $user= User::findOrFail(Auth::user()->id); 
        $user->Celular = $request->Celular;
        $user->fechadenac = $request->fechadenac;

        $confirmpassword =  $request->confirmpassword;
        $password = Auth::user()->password;

        
        if(Hash::check($confirmpassword, $password)){
            if(auth()->user()->role = "Docente")
            {
    
    
                $request->validate([
                    'formacion' => 'required',
                    'Especializacion' => 'required',
                    'ExperienciaL' => 'required',
                ]);
    
            
                
                DB::table('atributos_docentes')
                ->where('docente_id', '=', Auth::user()->id)// joining the contacts table , where user_id and contact_user_id are same
                ->update(['atributos_docentes.formacion' => $request->formacion]);
                DB::table('atributos_docentes')
                ->where('docente_id', '=', Auth::user()->id)// joining the contacts table , where user_id and contact_user_id are same
                ->update(['atributos_docentes.Especializacion' => $request->Especializacion]);
                DB::table('atributos_docentes')
                ->where('docente_id', '=', Auth::user()->id)// joining the contacts table , where user_id and contact_user_id are same
                ->update(['atributos_docentes.ExperienciaL'  => $request->ExperienciaL]);
                
                $user->save();
    
                $atributos = DB::table('atributos_docentes')
                ->where('docente_id', '=', Auth::user()->id)// joining the contacts table , where user_id and contact_user_id are same
                ->select('atributos_docentes.*')
                ->get();
            
                return response()->json([
                    "message" => "UserUpdateProfile OK",
                    "userData" => $user,
                    "Rol" => Auth::user()->roles->pluck('name'),
                    "Atributos" => $atributos,
                ],
                Response::HTTP_OK);
                
            }
            else
            {
                $user->save();  
                return response()->json([
                    "message" => "UserUpdateProfile OK",
                    "userData" => auth()->user(),
                    "Rol" => Auth::user()->roles->pluck('name'),
                ],
                Response::HTTP_OK);
            }


        }

        return response()->json([
            "message" => 'CONTRASEÑA INCORRECTA',

        ],
        Response::HTTP_LOCKED);

    } 



    
    public function logout(){
        $cookie = Cookie::forget('cookie_token');
        return response(["message" => "Cerro Sesión"], Response::HTTP_OK)->withCookie($cookie);

    }

}
