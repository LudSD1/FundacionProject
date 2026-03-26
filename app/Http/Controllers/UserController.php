<?php

namespace App\Http\Controllers;

use App\Events\UsuarioEvent;
use App\Models\atributosDocente;
use App\Models\DocentesTrabajos;
use App\Models\TutorRepresentanteLegal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Events\EstudianteEvent;
use App\Events\UsuarioRegistrado;
use App\Services\AdminLogger;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{


    public function authenticate(Request $request)
    {
        $maxAttempts = 5;
        $decayMinutes = 1;

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $key = 'login-attempts:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => "Has excedido el número de intentos. Inténtalo nuevamente en {$seconds} segundos.",
            ]);
        }

        if (Auth::attempt($credentials)) {
            // --- CORRECCIÓN AQUÍ: Definir el usuario ---
            $user = Auth::user();

            RateLimiter::clear($key);

            AdminLogger::info('Usuario inicio sesión', [
                // Agregué espacios entre comillas para que el nombre no salga pegado
                'nombre_completo' => "{$user->name} {$user->lastname1} {$user->lastname2}",
                'email' => $user->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->intended('/Inicio');
        }

        // Incrementa los intentos fallidos
        RateLimiter::hit($key, $decayMinutes * 60);

        return back()->withErrors([
            'email' => 'Parece que algo no coincide. ¿Podrías revisar tu correo y contraseña?',
        ])->onlyInput('email');
    }


    public function getUser(Request $request)
    {
        return response()->json(Auth::user());
    }
    public function logout(Request $request)
    {
        $user = auth()->user();


        AdminLogger::info('Usuario cerró sesión', [
            'user_id' => $user->name .'' . $user->lastname1 .'' . $user->lastname2 ?? null,
            'email' => $user->email ?? null,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.signin');
    }



    public function Profile($id)
    {

        $usuario = User::findOrFail($id);
        $trabajos = DocentesTrabajos::where('docente_id', $usuario->id)->get();
        $tutor = TutorRepresentanteLegal::where('estudiante_id', $usuario->id)->get();
        $atributosD = atributosDocente::where('docente_id', $usuario->id)->get();

        return view('PerfilUsuario', [
            'usuario' => $usuario,
            'atributosD' => $atributosD,
            'trabajos' => $trabajos,
            'tutor' => $tutor,
        ]);
    }


    public function UserProfile()
    {
        $usuario = Auth::user();
        $atributosD = atributosDocente::where('docente_id', $usuario->id)->get();
        $trabajos = DocentesTrabajos::where('docente_id', $usuario->id)->get();
        $tutor = TutorRepresentanteLegal::where('estudiante_id', $usuario->id)->get();

        return view('PerfilUsuario', [
            'usuario' => $usuario,
            'atributosD' => $atributosD,
            'tutor' => $tutor,
            'trabajos' => $trabajos
        ]);
    }

    public function UserProfileEdit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'lastname1' => 'required|string|max:255',
            'lastname2' => 'nullable|string|max:255',
            'Celular' => 'required|string|max:20',
            'fecha_nac' => 'required|date',
            'PaisReside' => 'required|string|max:255',
            'CiudadReside' => 'required|string|max:255',
            'confirmpassword' => 'required|string',
        ], [
            'name.required' => 'El campo Nombre es obligatorio.',
            'lastname1.required' => 'El campo Apellido Paterno es obligatorio.',
            'Celular.required' => 'El campo Celular es obligatorio.',
            'fecha_nac.required' => 'El campo Fecha de Nacimiento es obligatorio.',
            'PaisReside.required' => 'El campo País es obligatorio.',
            'CiudadReside.required' => 'El campo Ciudad es obligatorio.',
            'confirmpassword.required' => 'Confirma la contraseña para realizar los cambios.',
        ]);

        $user = User::findOrFail(Auth::user()->id);

        $this->updateUserData($request, $user);

        $confirmpassword = $request->confirmpassword;

        if ($this->validatePassword($confirmpassword)) {
            if (Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Estudiante')) {
                event(new UsuarioEvent($user, 'modificacion'));
                $this->saveUser($user);
                return back()->with('success', 'Editado Correctamente');
            }

            if (Auth::user()->hasRole('Docente')) {
                event(new UsuarioEvent($user, 'modificacion'));

                $this->updateDocenteData($request, $user);
                return back()->with('success', 'Editado Correctamente');
            }
        }

        return back()->with('error', 'Contraseña incorrecta');
    }

    protected function updateUserData(Request $request, User $user)
    {
        $user->name = $request->name;
        $user->lastname1 = $request->lastname1;
        $user->lastname2 = $request->lastname2 ?? ''; // Opcional
        $user->Celular = $request->Celular;
        $user->fechadenac = $request->fecha_nac; // Fecha de nacimiento
        $user->PaisReside = $request->PaisReside ?? '';
        $user->CiudadReside = $request->CiudadReside ?? '';
        $user->updated_at = now();
    }


    protected function updateUserAvatar(Request $request)
    {
        $user = User::findOrFail(Auth::user()->id);


        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->save();

        return redirect(route('Miperfil'));
    }



    public function storeUsuario(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'lastname1' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'lastname1.required' => 'El primer apellido es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'Este correo electrónico ya está en uso.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        if (!app()->environment('local')) {
            $request->validate([
                'g-recaptcha-response' => 'required|captcha',
            ], [
                'g-recaptcha-response.required' => 'Debes completar el reCAPTCHA.',
                'captcha' => 'Error en la validación de reCAPTCHA.',
            ]);

            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => env('RECAPTCHA_SECRET_KEY'),
                'response' => $request->input('g-recaptcha-response')
            ]);

            $success = $response->json()['success'] ?? false;

            if (!$success) {
                return back()->withErrors(['g-recaptcha-response' => 'ReCAPTCHA no válido, intenta de nuevo.']);
            }
        }

        // Crear usuario
        $user = new User();
        $user->name = $request->name;
        $user->lastname1 = $request->lastname1;
        $user->lastname2 = $request->lastname2 ?? '';
        $user->CI = Str::random(10);
        $user->Celular = 0;
        $user->fechadenac = Carbon::parse('2000-01-01');
        $user->email = $request->email;
        $user->PaisReside = $request->country;
        $user->password = bcrypt($request->password);
        $user->save();

        // Asignar rol de estudiante
        $user->assignRole('Estudiante');

        event(new UsuarioRegistrado($user));

        // Iniciar sesión automáticamente
        Auth::login($user);

        return redirect()->route('Inicio')->with('success', '¡Bienvenido! Tu cuenta ha sido creada y has iniciado sesión.');
    }

    protected function validatePassword($confirmpassword)
    {
        $password = Auth::user()->password;
        return Hash::check($confirmpassword, $password);
    }

    protected function saveUser(User $user)
    {
        $user->save();
    }

    protected function updateDocenteData(Request $request, User $user)
    {

        $this->updateAtributosDocentes($request);

        $user->save();
    }

    protected function updateAtributosDocentes(Request $request)
    {
        DB::table('atributos_docentes')
            ->where('docente_id', '=', Auth::user()->id)
            ->update([
                'atributos_docentes.formacion' => $request->formacion ?? '',
                'atributos_docentes.Especializacion' => $request->Especializacion ?? '',
                'atributos_docentes.ExperienciaL' => $request->ExperienciaL ?? '',
                'atributos_docentes.updated_at' => now()
            ]);

        $trabajos = $request->input('trabajos');

        foreach ($trabajos as $trabajosItem) {

            if (isset($trabajosItem['id'])) {
                $trabajoid = $trabajosItem['id'];
                $trabajo = DocentesTrabajos::findOrFail($trabajoid);

                $trabajo->update([
                    'empresa' => $trabajosItem['empresa'] ?? '',
                    'cargo' => $trabajosItem['cargo'] ?? '',
                    'fecha_inicio' => date("Y-m-d", strtotime($trabajosItem['fechain'])) ?? '',
                    'fecha_fin' => date("Y-m-d", strtotime($trabajosItem['fechasal'])) ?? '',
                    'contacto_ref' => $trabajosItem['contacto'] ?? '',
                ]);
            } elseif ($trabajosItem['id'] = 'null') {
                DocentesTrabajos::create([
                    'docente_id' => Auth::user()->id,
                    'empresa' => $trabajosItem['empresa'] ?? '',
                    'cargo' => $trabajosItem['cargo'] ?? '',
                    'fecha_inicio' => date("Y-m-d", strtotime($trabajosItem['fechain'])) ?? '',
                    'fecha_fin' => date("Y-m-d", strtotime($trabajosItem['fechasal'])) ?? '',
                    'contacto_ref' => $trabajosItem['contacto'] ?? '',
                ]);
            }
        }
    }


    public function EditProfileIndex()
    {


        $atributosD = atributosDocente::where('docente_id', Auth::user()->id)->get();
        $ultimosTrabajos = DocentesTrabajos::where('docente_id', Auth::user()->id)->get();


        return view('EditarPerfil')->with('ultimosTrabajos', $ultimosTrabajos)->with('atributosD', $atributosD);
    }



    public function EditPasswordIndex($id)
    {


        $usuario = User::findOrFail($id);

        if (auth()->user()->id == $id  || auth()->user()->hasRole('Administrador')) {
            return view('EditarContrasena')->with('usuario', $usuario);
        } else {

            abort(403);
        }
    }



    public function CambiarContrasena(Request $request)
    {

        $request->validate([
            'oldpassword' => 'required',
            'password' => 'required|min:8|regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d!$%@#£€*?&]{8,}$/',
            'password_confirmation' => 'required_with:password|same:password'
        ], [
            'oldpassword.required' => 'La contraseña actual es obligatoria.',
            'password.required' => 'La nueva contraseña es obligatoria.',
            'password.min' => 'La nueva contraseña debe tener al menos :min caracteres.',
            'password.regex' => 'La nueva contraseña debe contener al menos una letra mayúscula, una letra minúscula y un número.',
            'password_confirmation.required_with' => 'Debes confirmar la nueva contraseña.',
            'password_confirmation.same' => 'La confirmación de la contraseña no coincide con la nueva contraseña.',
        ]);
        $usuario = User::findOrFail(Auth::user()->id);
        $password = Auth::user()->password;
        $oldpassword = $request->oldpassword;


        if (Hash::check($oldpassword, $password)) {

            $usuario->password = bcrypt($request->password);
            event(new UsuarioEvent($usuario, 'modificacion'));
            $usuario->save();

            return back()->with('success', 'Se ha cambiado la contraseña correctamente');
        } else {
            return back()->with('error', 'La contraseña antigua es incorrecta.');
        }
    }

    public function delete($id)
    {
        try {
        } catch (DecryptException $e) {
            return back()->with('error', 'ID inválido');
        }

        $usuario = User::findOrFail($id);

        event(new UsuarioEvent($usuario, 'eliminacion'));

        $usuario->delete();

        return back()->with('success', 'Usuario Dado de Baja');
    }


    public function restaurarUsuario($id)
    {

        $usuarioEliminado = User::onlyTrashed()->find($id);
        event(new UsuarioEvent($usuarioEliminado, 'restaurar'));

        $usuarioEliminado->restore();

        return back()->with('success', 'Usuario dado de baja');;
    }

    public function notificaciones()
    {
        return view('notificaciones');
    }
}
