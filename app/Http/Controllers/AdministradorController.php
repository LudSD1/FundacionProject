<?php

namespace App\Http\Controllers;

use App\Events\CursoEvent;
use App\Events\DocenteEvent;
use App\Events\EstudianteEvent;
use App\Events\UsuarioEvent;
use App\Mail\CredencialesAcceso;
use App\Mail\NuevoUsuarioRegistrado;
use App\Models\atributosDocente;
use App\Models\Cursos;
use App\Models\Docente;
use App\Models\Estudiante;
use App\Models\TutorRepresentanteLegal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Services\TwilioService;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use App\Models\Horario;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\Rule;

class AdministradorController extends Controller
{

    public function cambiarRol(Request $request, $usuarioEncriptado)
    {
        try {


            // Desencriptar el ID del usuario
            $usuarioId = $usuarioEncriptado;

            // Validar los datos
            $request->validate([
                'nuevo_rol' => 'required|string|in:Estudiante,Docente,Administrador',
                'confirmar_cambio' => 'required|accepted'
            ]);

            // Buscar el usuario
            $usuario = User::findOrFail($usuarioId);
            $nuevoRol = $request->nuevo_rol;

            // Guardar rol anterior para el mensaje
            $rolAnterior = $usuario->getRoleNames()->first() ?? 'Sin rol';

            // Remover todos los roles actuales y asignar el nuevo
            $usuario->syncRoles([$nuevoRol]);

            // Mensaje de éxito
            $mensaje = "El rol de {$usuario->name} {$usuario->lastname1} ha sido cambiado de '{$rolAnterior}' a '{$nuevoRol}'.";

            return back()->with('success', $mensaje);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al cambiar el rol: ' . $e->getMessage()]);
        }
    }

    public function storeEstudiante(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'lastname1' => 'required',
            'lastname2' => 'required',
            'CI' => 'required|unique:users,CI,except,id',
            'Celular' => 'required|integer',
            'email' => 'required|unique:users,email',
            'fechadenac' => 'required|date|before_or_equal:' . now()->subYears(12)->format('Y-m-d'),
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'lastname1.required' => 'El primer apellido es obligatorio.',
            'lastname2.required' => 'El segundo apellido es obligatorio.',
            'CI.required' => 'El campo de identificación es obligatorio.',
            'CI.unique' => 'Esta identificación ya está en uso.',
            'Celular.required' => 'El número de celular es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'Este correo electrónico ya está en uso.',
            'fechadenac.required' => 'La fecha de nacimiento es obligatoria.',
            'fechadenac.date' => 'La fecha de nacimiento debe ser una fecha válida.',
            'fechadenac.before_or_equal' => 'Debes ser mayor de 12 años para registrarte.',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->lastname1 = $request->lastname1;
        $user->lastname2 = $request->lastname2;
        $user->CI = $request->CI;
        $user->Celular = $request->Celular;
        $user->email = $request->email;
        $check = $request->representante;

        $orgDate = $request->fechadenac;
        $newDate = date("Y-m-d", strtotime($orgDate));

        $user->fechadenac = $newDate;
        $user->CiudadReside = $request->CiudadReside;
        $user->PaisReside = $request->PaisReside;
        $passwordPlain = substr($request->name, 0, 1) . substr($request->lastname1, 0, 1) . substr($request->lastname2, 0, 1) . $request->CI;
        $user->password = bcrypt($passwordPlain);

        try {
            Mail::to($user->email)->send(new CredencialesAcceso($user, $passwordPlain));
        } catch (\Exception $e) {
            // Puedes loggear el error si falla el envío pero no interrumpir el flujo
            Log::error('Error enviando credenciales: ' . $e->getMessage());
        }

        $estudiante =  $user;

        // event(new EstudianteEvent($estudiante,'', 'registro'));

        $user->save();
        $user->assignRole('Estudiante');

        // Log de actividad del administrador
        Log::channel('admin')->info('Estudiante creado por administrador', [
            'admin_id' => auth()->id(),
            'admin_name' => auth()->user()->name ?? 'Sistema',
            'action' => 'create_student',
            'student_data' => [
                'name' => $request->name,
                'lastname1' => $request->lastname1,
                'lastname2' => $request->lastname2,
                'email' => $request->email,
                'CI' => $request->CI,
                'celular' => $request->Celular
            ],
            'timestamp' => now(),
            'ip' => request()->ip()
        ]);

        return redirect()->route('ListaEstudiantes')->with('success', 'Editado exitosamente!');

        // }







    }

    public function storeDocente(Request $request)
    {


        $request->validate([
            'name' => 'required',
            'lastname1' => 'required',
            'lastname2' => 'required',
            'CI' => 'required|unique:users,CI',
            'Celular' => 'required|integer',
            'email' => 'required|unique:users,email',
            'fechadenac' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'lastname1.required' => 'El primer apellido es obligatorio.',
            'lastname2.required' => 'El segundo apellido es obligatorio.',
            'CI.required' => 'El campo de identificación es obligatorio.',
            'CI.unique' => 'Esta identificación ya está en uso.',
            'Celular.required' => 'El número de celular es obligatorio.',
            'Celular.integer' => 'Debe ser un numero valido.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'Este correo electrónico ya está en uso.',
            'fechadenac.required' => 'La fecha de nacimiento es obligatoria.',
            'fechadenac.date' => 'La fecha de nacimiento debe ser una fecha válida.',
            'fechadenac.before_or_equal' => 'Debes ser mayor de 18 años para registrarte.',
        ]);




        $user = new User();
        $user->name = $request->name;
        $user->lastname1 = $request->lastname1;
        $user->lastname2 = $request->lastname2;
        $user->CI = $request->CI;
        $user->Celular = $request->Celular;
        $user->email = $request->email;
        $orgDate = $request->fechadenac;
        $newDate = date("Y-m-d", strtotime($orgDate));


        $user->fechadenac = $newDate;
        $user->CiudadReside = $request->CiudadReside;
        $user->PaisReside = $request->PaisReside;
        $passwordPlain = substr($request->name, 0, 1) . substr($request->lastname1, 0, 1) . substr($request->lastname2, 0, 1) . $request->CI;
        $user->password = bcrypt($passwordPlain);

        try {
            Mail::to($user->email)->send(new CredencialesAcceso($user, $passwordPlain));
        } catch (\Exception $e) {
            // Puedes loggear el error si falla el envío pero no interrumpir el flujo
            Log::error('Error enviando credenciales: ' . $e->getMessage());
        }

        $user->save();
        $user->assignRole('Docente');

        $atributosDocentes = new atributosDocente();

        $atributosDocentes->formacion = "";
        $atributosDocentes->Especializacion = "";
        $atributosDocentes->ExperienciaL = "";
        $atributosDocentes->docente_id = User::latest('id')->first()->id;
        $atributosDocentes->save();

        // Log de actividad del administrador
        Log::channel('admin')->info('Docente creado por administrador', [
            'admin_id' => auth()->id(),
            'admin_name' => auth()->user()->name ?? 'Sistema',
            'action' => 'create_teacher',
            'teacher_data' => [
                'name' => $request->name,
                'lastname1' => $request->lastname1,
                'lastname2' => $request->lastname2,
                'email' => $request->email,
                'CI' => $request->CI,
                'celular' => $request->Celular
            ],
            'timestamp' => now(),
            'ip' => request()->ip()
        ]);

        return redirect()->route('ListaDocentes')->with('success', 'Docente registrado exitosamente!');
    }



    public function storeEstudianteMenor(Request $request)
    {


        $request->validate([
            'name' => 'required',
            'lastname1' => 'required',
            'lastname2' => 'required',
            'CI' => 'required|unique:users,CI',
            'nombreT' => 'required',
            'appT' => 'required',
            'apmT' => 'required',
            'CIT' => 'required',
            'CelularT' => 'required|integer',
            'fechadenac' => 'required|date|before_or_equal:' . now()->subYears(5)->format('Y-m-d'),

        ], [
            'name.required' => 'El nombre es obligatorio.',
            'lastname1.required' => 'El primer apellido es obligatorio.',
            'lastname2.required' => 'El segundo apellido es obligatorio.',
            'CI.required' => 'El campo de identificación es obligatorio.',
            'CI.unique' => 'Esta identificación ya está en uso.',
            'nombreT.required' => 'El nombre del tutor es obligatorio.',
            'appT.required' => 'El primer apellido del tutor es obligatorio.',
            'apmT.required' => 'El segundo apellido del tutor es obligatorio.',
            'CIT.required' => 'La identificación del tutor es obligatoria.',
            'CelularT.required' => 'El número de celular del tutor es obligatorio.',
            'fechadenac.required' => 'La fecha de nacimiento es obligatoria.',
            'fechadenac.date' => 'La fecha de nacimiento debe ser una fecha válida.',
            'fechadenac.before_or_equal' => 'El estudiante debe tener al menos 10 años.',
        ]);


        $user = new User();

        $user->name = $request->name;
        $user->lastname1 = $request->lastname1;
        $user->lastname2 = $request->lastname2;
        $user->Celular = $request->CelularT;
        $user->email = substr($request->nombreT, 0, 1) . substr($request->appT, 0, 1) . substr($request->appM, 0, 1) . $request->CIT . '@fundvida.com';
        $user->CI = $request->CI;
        $orgDate = $request->fechadenac;



        $newDate = date("Y-m-d", strtotime($orgDate));

        $user->fechadenac = $newDate;
        $user->CiudadReside = $request->CiudadReside;
        $user->PaisReside = $request->PaisReside;
        $user->password = bcrypt(substr($request->nombreT, 0, 1) . substr($request->appT, 0, 1) . substr($request->appM, 0, 1) . $request->CIT);

        $estudiante = $user;



        $tutor = new TutorRepresentanteLegal();

        $tutor->nombreTutor = $request->nombreT;
        $tutor->appaternoTutor = $request->appT;
        $tutor->apmaternoTutor = $request->apmT;
        $tutor->CI = $request->CIT;
        $tutor->Direccion = "";
        $tutor->Celular = $request->CelularT;
        $tutor->CorreoElectronicoTutor = $request->email;
        $tutor->estudiante_id = User::latest('id')->first()->id;


        // event(new EstudianteEvent($estudiante, $tutor, 'registro'));
        $user->save();
        $tutor->save();

        $user->assignRole('Estudiante');

        // Log de actividad del administrador
        Log::channel('admin')->info('Estudiante menor creado por administrador', [
            'admin_id' => auth()->id(),
            'admin_name' => auth()->user()->name ?? 'Sistema',
            'action' => 'create_minor_student',
            'student_data' => [
                'name' => $request->name,
                'lastname1' => $request->lastname1,
                'lastname2' => $request->lastname2,
                'CI' => $request->CI,
                'tutor_name' => $request->nombreT,
                'tutor_lastname1' => $request->appT,
                'tutor_lastname2' => $request->apmT,
                'tutor_CI' => $request->CIT,
                'tutor_celular' => $request->CelularT
            ],
            'timestamp' => now(),
            'ip' => request()->ip()
        ]);

        return redirect()->route('ListaEstudiantes')->with('success', 'Estudiante registrado exitosamente!');
    }









    public function storeCurso(Request $request)
    {
        $this->validarCurso($request);

        $curso = $this->crearCurso($request);

        event(new CursoEvent($curso, 'crear'));

        // Log de actividad del administrador
        Log::channel('admin')->info('Curso creado por administrador', [
            'admin_id' => auth()->id(),
            'admin_name' => auth()->user()->name ?? 'Sistema',
            'action' => 'create_course',
            'course_data' => [
                'nombre' => $request->nombre,
                'docente_id' => $request->docente_id,
                'fecha_ini' => $request->fecha_ini,
                'fecha_fin' => $request->fecha_fin,
                'duracion' => $request->duracion,
                'visibilidad' => $request->visibilidad,
                'cupos' => $request->cupos,
                'precio' => $request->precio
            ],
            'timestamp' => now(),
            'ip' => request()->ip()
        ]);

        return redirect(route('Inicio'))->with('success', 'Curso registrado exitosamente!');
    }

    private function validarCurso($request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'docente_id' => 'required|integer|exists:users,id',
            'fecha_ini' => 'required|date|date_format:Y-m-d',
            'fecha_fin' => 'required|date|date_format:Y-m-d|after_or_equal:fecha_ini',
            'duracion' => 'required|integer|min:1',
            'visibilidad' => 'required|in:publico,privado',
            'cupos' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0',
        ]);
    }

    private function crearCurso($request)
    {
        $curso = new Cursos;
        $curso->nombreCurso = $request->nombre;
        $curso->codigoCurso = $request->nombre . '_' . $request->docente_id . '_' . date("Ymd", strtotime($request->fecha_ini));
        $curso->descripcionC = $request->descripcion ?? '';
        $curso->fecha_ini = $request->fecha_ini . ' ' . $request->hora_ini;
        $curso->fecha_fin = $request->fecha_fin . ' ' . $request->hora_fin;
        $curso->duracion = $request->duracion;
        $curso->formato = $request->formato;
        $curso->tipo = $request->tipo;
        $curso->docente_id = $request->docente_id;
        $curso->edad_dirigida = $request->edad_id;
        $curso->nivel = $request->nivel_id;
        $curso->notaAprobacion = 51;
        $curso->cupos = $request->cupos;
        $curso->visibilidad = $request->visibilidad;
        $curso->estado = Carbon::parse($curso->fecha_fin)->isPast() ? 'Finalizado' : 'Activo';

        $curso->save();

        return $curso;
    }

    public function EditUserIndex($id)
    {

        $usuario = User::findOrFail($id);

        $atributosD = DB::table('atributos_docentes')
            ->where('docente_id', '=', $id) // joining the contacts table , where user_id and contact_user_id are same
            ->select('atributos_docentes.*')
            ->get();

        $atributosTutor = DB::table('tutor_representante_legals')
            ->where('estudiante_id', '=', $id) // joining the contacts table , where user_id and contact_user_id are same
            ->select('tutor_representante_legals.*')
            ->get();



        return view('EditarUsuario', ['atributosD' => $atributosD], ['atributosTutor' => $atributosTutor])->with('usuario', $usuario)->with('success', 'Editado exitosamente!');
    }

    public function EditUser($id, Request $request)
    {


        $request->validate([
            'name' => 'required',
            'lastname1' => 'required',
            'Celular' => 'required',
            'email' => 'required|unique:users,email,' . $id,
            'fechadenac' => 'required|date|before_or_equal:today',
        ], [
            'name.required' => 'El campo nombre es obligatorio.',
            'lastname1.required' => 'El campo primer apellido es obligatorio.',
            'Celular.required' => 'El campo celular es obligatorio.',
            'email.required' => 'El campo correo electrónico es obligatorio.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'fechadenac.required' => 'El campo fecha de nacimiento es obligatorio.',
            'fechadenac.before_or_equal' => 'El campo fecha de nacimiento debe ser valido.',
        ]);


        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->lastname1 = $request->lastname1;
        $user->lastname2 = $request->lastname2 ?? '';
        $user->CI = $request->CI;
        $user->email = $request->email;
        $user->Celular = $request->Celular;
        $user->fechadenac = $request->fechadenac;
        $user->PaisReside = $request->PaisReside ?? '';
        $user->CiudadReside = $request->CiudadReside ?? '';
        $user->updated_at = now();






        if ($user->hasRole('Docente') || $user->hasRole('Administrador')) {


            $atributosDocentes = [
                'formacion' => $request->formacion ?? '',
                'Especializacion' => $request->Especializacion ?? '',
                'ExperienciaL' => $request->ExperienciaL ?? '',
                'updated_at' => now(),
            ];

            DB::table('atributos_docentes')
                ->where('docente_id', '=', $id)
                ->update($atributosDocentes);

            // Log de actividad del administrador para docente
            Log::channel('admin')->info('Docente editado por administrador', [
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name ?? 'Sistema',
                'action' => 'edit_teacher',
                'user_id' => $id,
                'user_data' => [
                    'name' => $request->name,
                    'lastname1' => $request->lastname1,
                    'email' => $request->email,
                    'CI' => $request->CI
                ],
                'timestamp' => now(),
                'ip' => request()->ip()
            ]);

            event(new UsuarioEvent($user, 'modificacion'));

            $user->save();
        } elseif ($user->tutor) {

            $request->validate([
                'nombreT' => 'required|string|max:255',
                'appT' => 'required|string|max:255',
                'apmT' => 'required|string|max:255',  // Puede ser nulo
                'Direccion' => 'nullable|string|max:255',  // Puede ser nulo
            ], [
                'nombreT.required' => 'El campo nombre del tutor es obligatorio.',
                'nombreT.string' => 'El campo nombre del tutor debe ser una cadena de texto.',
                'nombreT.max' => 'El campo nombre del tutor no debe exceder los :max caracteres.',

                'appT.required' => 'El campo apellido paterno del tutor es obligatorio.',
                'appT.string' => 'El campo apellido paterno del tutor debe ser una cadena de texto.',
                'appT.max' => 'El campo apellido paterno del tutor no debe exceder los :max caracteres.',

                'apmT.string' => 'El campo apellido materno del tutor debe ser una cadena de texto.',
                'apmT.max' => 'El campo apellido materno del tutor no debe exceder los :max caracteres.',



                'direccion.string' => 'El campo dirección del tutor debe ser una cadena de texto.',
                'direccion.max' => 'El campo dirección del tutor no debe exceder los :max caracteres.',
            ]);

            $tutor = [
                'nombreTutor' => $request->nombreT,
                'appaternoTutor' => $request->appT,
                'apmaternoTutor' => $request->apmT,
                'CI' => $request->CIT,
                'Celular' => $request->Celular,
                'Direccion' => $request->direccion ?? '',
                'updated_at' => now(),
            ];

            DB::table('tutor_representante_legals')
                ->where('estudiante_id', '=', $id)
                ->update($tutor);

            // Log de actividad del administrador para estudiante con tutor
            Log::channel('admin')->info('Estudiante con tutor editado por administrador', [
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name ?? 'Sistema',
                'action' => 'edit_student_with_tutor',
                'user_id' => $id,
                'user_data' => [
                    'name' => $request->name,
                    'lastname1' => $request->lastname1,
                    'email' => $request->email,
                    'CI' => $request->CI
                ],
                'tutor_data' => [
                    'nombreT' => $request->nombreT,
                    'appT' => $request->appT,
                    'apmT' => $request->apmT,
                    'CIT' => $request->CIT
                ],
                'timestamp' => now(),
                'ip' => request()->ip()
            ]);

            event(new UsuarioEvent($user, 'modificacion'));

            $user->save();
        } else {

            // Log de actividad del administrador para estudiante regular
            Log::channel('admin')->info('Estudiante editado por administrador', [
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name ?? 'Sistema',
                'action' => 'edit_student',
                'user_id' => $id,
                'user_data' => [
                    'name' => $request->name,
                    'lastname1' => $request->lastname1,
                    'email' => $request->email,
                    'CI' => $request->CI
                ],
                'timestamp' => now(),
                'ip' => request()->ip()
            ]);

            event(new UsuarioEvent($user, 'modificacion'));

            $user->save();
        }

        return redirect()->route('Inicio')->with('success', 'Editado exitosamente!');
    }
    public function viewLogs()
    {
        // Buscar el archivo de log más reciente
        $logPath = storage_path('logs');
        $logFiles = glob($logPath . '/admin-*.log');

        $logs = [];

        if (!empty($logFiles)) {
            // Ordenar por fecha de modificación (más reciente primero)
            usort($logFiles, function ($a, $b) {
                return filemtime($b) - filemtime($a);
            });

            $logFile = $logFiles[0]; // Tomar el más reciente

            if (file_exists($logFile)) {
                $logContent = file_get_contents($logFile);
                $logLines = explode("\n", $logContent);

                // Procesar las últimas 100 líneas
                $logs = array_slice(array_reverse($logLines), 0, 100);
            }
        }

        return view('Administrador.logs', compact('logs'));
    }

    public function testLog()
    {
        // Método de prueba para verificar que los logs funcionen
        Log::channel('admin')->info('Prueba de log del administrador', [
            'admin_id' => auth()->id(),
            'admin_name' => auth()->user()->name ?? 'Sistema',
            'action' => 'test_log',
            'message' => 'Esta es una prueba para verificar que el sistema de logs funciona correctamente',
            'timestamp' => now(),
            'ip' => request()->ip()
        ]);

        return redirect()->route('admin.logs')->with('success', 'Log de prueba creado exitosamente');
    }

    public function listBackups()
    {
        $backupPath = storage_path('backups');
        $backups = [];

        if (is_dir($backupPath)) {
            $files = glob($backupPath . '/*.{sql,sql.gz}', GLOB_BRACE);

            foreach ($files as $file) {
                $backups[] = [
                    'name' => basename($file),
                    'path' => $file,
                    'size' => filesize($file),
                    'size_mb' => round(filesize($file) / 1024 / 1024, 2),
                    'date' => date('Y-m-d H:i:s', filemtime($file)),
                    'timestamp' => filemtime($file),
                    'is_compressed' => str_ends_with($file, '.gz')
                ];
            }

            // Ordenar por fecha (más reciente primero)
            usort($backups, function ($a, $b) {
                return $b['timestamp'] - $a['timestamp'];
            });
        }

        return view('Administrador.backups', compact('backups'));
    }

    public function createBackup(Request $request)
    {
        try {
            $compress = $request->has('compress');

            // Ejecutar comando de backup
            $command = 'backup:database';
            if ($compress) {
                $command .= ' --compress';
            }

            Artisan::call($command);
            $output = Artisan::output();

            // Log de la acción
            Log::channel('admin')->info('Manual backup requested', [
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name ?? 'Sistema',
                'action' => 'create_backup',
                'compressed' => $compress,
                'timestamp' => now(),
                'ip' => request()->ip()
            ]);

            if (str_contains($output, '✅')) {
                return redirect()->back()->with('success', 'Backup creado exitosamente');
            } else {
                return redirect()->back()->with('error', 'Error creando el backup: ' . $output);
            }
        } catch (\Exception $e) {
            Log::channel('admin')->error('Backup failed', [
                'error' => $e->getMessage(),
                'admin_id' => auth()->id(),
                'timestamp' => now()
            ]);

            return redirect()->back()->with('error', 'Error creando el backup: ' . $e->getMessage());
        }
    }

    public function downloadBackup($filename)
    {
        $backupPath = storage_path('backups/' . $filename);

        if (file_exists($backupPath)) {
            // Log de descarga
            Log::channel('admin')->info('Backup downloaded', [
                'filename' => $filename,
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name ?? 'Sistema',
                'action' => 'download_backup',
                'timestamp' => now(),
                'ip' => request()->ip()
            ]);

            return response()->download($backupPath);
        }

        return redirect()->back()->with('error', 'Archivo no encontrado');
    }

    public function deleteBackup($filename)
    {
        $backupPath = storage_path('backups/' . $filename);

        if (file_exists($backupPath)) {
            $fileSize = filesize($backupPath);

            if (unlink($backupPath)) {
                // Log de eliminación
                Log::channel('admin')->info('Backup deleted', [
                    'filename' => $filename,
                    'size' => $fileSize,
                    'admin_id' => auth()->id(),
                    'admin_name' => auth()->user()->name ?? 'Sistema',
                    'action' => 'delete_backup',
                    'timestamp' => now(),
                    'ip' => request()->ip()
                ]);

                return redirect()->back()->with('success', 'Backup eliminado exitosamente');
            } else {
                return redirect()->back()->with('error', 'Error eliminando el backup');
            }
        }

        return redirect()->back()->with('error', 'Archivo no encontrado');
    }
}
