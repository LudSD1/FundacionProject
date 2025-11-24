<?php

namespace App\Http\Controllers;

use App\Models\Certificado;
use App\Models\CertificateTemplate;
use App\Models\Cursos;
use App\Models\Inscritos;
use App\Models\Progreso;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Notifications\CertificadoGeneradoNotification;
use Illuminate\Support\Facades\Cache;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use App\Services\XPService;
use App\Services\AchievementService;


class CertificadoController extends Controller
{
    protected $xpService;
    protected $achievementService;

    public function __construct(XPService $xpService, AchievementService $achievementService)
    {
        $this->xpService = $xpService;
        $this->achievementService = $achievementService;
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'template_front' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            'template_back' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            'primary_color' => 'nullable|string',
            'font_family' => 'nullable|string',
            'font_size' => 'nullable|integer|min:6|max:108',
        ]);

        $pathFront = $request->file('template_front')->store('certificates/templates', 'public');
        $pathBack = $request->file('template_back')->store('certificates/templates', 'public');

        CertificateTemplate::create([
            'curso_id' => $id,
            'template_front_path' => $pathFront,
            'template_back_path' => $pathBack,
            'primary_color' => $request->primary_color ?? '#000000',
            'font_family' => $request->font_family ?? 'Arial',
            'font_size' => $request->font_size ?? 12,
        ]);

        return back()->with('success', 'Plantilla subida correctamente');
    }

    public function generarCertificadoCongreso($id)
    {
        // Buscar el curso 
        $curso = Cursos::findOrFail($id);

        if ($curso->tipo != 'congreso') {
            return back()->with('error', 'No se puede generar certificados.');
        }

        $plantilla = CertificateTemplate::where('curso_id', $id)->first();
        if (!$plantilla) {
            return back()->with('error', 'No se encontró la plantilla del certificado para este curso.');
        }

        // Obtener los inscritos que aún no tienen certificado, en lotes de 20
        Inscritos::where('cursos_id', $id)
            ->whereDoesntHave('certificado')
            ->with('estudiantes')
            ->chunk(20, function ($inscritos) use ($id, $curso) {
                foreach ($inscritos as $inscrito) {
                    $certificado = $this->generarCertificadoIndividual(
                        $id,
                        $inscrito->id,
                        $inscrito->estudiantes
                    );

                    // Otorgar XP adicional por certificado de congreso
                    if ($certificado->wasRecentlyCreated) {
                        $this->xpService->addXP($inscrito, 300, "Certificado de congreso - {$curso->nombreCurso}");
                    }
                }
            });

        return back()->with('success', 'Certificados generados correctamente.');
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'template_front' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'template_back' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'primary_color' => 'nullable|string',
            'font_family' => 'nullable|string',
            'font_size' => 'nullable|integer|min:6|max:108',
        ]);

        $certificateTemplate = CertificateTemplate::where('curso_id', $id)->firstOrFail();

        if ($request->hasFile('template_front')) {
            Storage::disk('public')->delete($certificateTemplate->template_front_path);
            $pathFront = $request->file('template_front')->store('certificates/templates', 'public');
            $certificateTemplate->template_front_path = $pathFront;
        }

        if ($request->hasFile('template_back')) {
            Storage::disk('public')->delete($certificateTemplate->template_back_path);
            $pathBack = $request->file('template_back')->store('certificates/templates', 'public');
            $certificateTemplate->template_back_path = $pathBack;
        }

        // Actualizar los nuevos campos
        $certificateTemplate->primary_color = $request->primary_color ?? $certificateTemplate->primary_color;
        $certificateTemplate->font_family = $request->font_family ?? $certificateTemplate->font_family;
        $certificateTemplate->font_size = $request->font_size ?? $certificateTemplate->font_size;

        $certificateTemplate->save();

        return back()->with('success', 'Plantilla actualizada correctamente');
    }




    // Eliminar una plantilla
    public function destroy($id)
    {
        $template = CertificateTemplate::findOrFail($id);

        // Eliminar el archivo físico
        Storage::disk('public')->delete($template->template_path);

        // Eliminar el registro
        $template->delete();

        return back()->with('success', 'Plantilla eliminada correctamente');
    }



    public function generarCertificado($id)
    {
        set_time_limit(300);
        $inscritos = Inscritos::findOrFail($id);
        $progreso = $inscritos->progreso;

        if (intval($progreso) == 0) {
            return response()->json(['error' => 'El curso no está completado'], 400);
        }

        $codigo = strtoupper(Str::random(10));
        $qrCodeSvg = QrCode::format('svg')->size(300)->generate($codigo);
        $qrCode = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);

        return view('certificados.plantilla', [
            'curso' => $inscritos->cursos,
            'estudiante' => $inscritos->estudiantes,
            'codigo' => $codigo,
            'fecha' => now()->format('d/m/Y'),
            'qrCode' => $qrCode
        ]);
    }


    public function generarCertificadoAdmin($inscrito_id)
    {

        $inscrito = Inscritos::findOrFail($inscrito_id);

        // Buscar el curso
        $curso = Cursos::findOrFail($inscrito->cursos_id);

        // Verificar que el curso sea de tipo "congreso"
        if ($curso->tipo != 'congreso') {
            return back()->with('error', 'No se puede generar certificados para este tipo de curso.');
        }

        if (!$curso->certificados_disponibles) {
            return back()->with('error', 'El certificado no está Disponible.');
        }

        if (!$inscrito) {
            return back()->with('error', 'El usuario no está inscrito en este curso.');
        }

        // Verificar que exista una plantilla de certificado
        $plantilla = CertificateTemplate::where('curso_id', $curso->id)->first();
        if (!$plantilla) {
            return back()->with('error', 'No se encontró la plantilla del certificado para este curso.');
        }

        // Verificar si ya existe un certificado
        $certificadoExistente = Certificado::where('curso_id', $curso->id)
            ->where('inscrito_id', $inscrito->id)
            ->first();

        if ($certificadoExistente) {
            $codigo_certificado = $certificadoExistente->codigo_certificado;
            $regenerando = true;

            // Generar el código QR solo si no existe o si se está regenerando
            $qrCode = QrCode::format('svg')
                ->size(200)
                ->generate(route('verificar.certificado', ['codigo' => $codigo_certificado]));

            // Guardar el código QR en el almacenamiento
            $qrPath = "certificados/{$inscrito->cursos_id}/qrcode_{$inscrito->id}.svg";
            Storage::put("public/$qrPath", $qrCode);

            // Actualizar la ruta del certificado en la base de datos
            $certificadoExistente->update([
                'ruta_certificado' => route('verificar.certificado', ['codigo' => $codigo_certificado])
            ]);
        } else {
            // Si no existe, generamos un nuevo código
            $codigo_certificado = Str::uuid();
            $regenerando = false;

            // Generar la ruta del certificado (URL de verificación)
            $ruta_certificado = route('verificar.certificado', ['codigo' => $codigo_certificado]);

            // Crear registro de certificado
            $certificado = Certificado::create([
                'curso_id' => $curso->id,
                'inscrito_id' => $inscrito->id,
                'codigo_certificado' => $codigo_certificado,
                'ruta_certificado' => $ruta_certificado,
            ]);

            // Generar y guardar el código QR
            $qrCode = QrCode::format('svg')
                ->size(200)
                ->generate($ruta_certificado);
            $qrPath = "certificados/{$inscrito->cursos_id}/qrcode_{$inscrito->id}.svg";
            Storage::put("public/$qrPath", $qrCode);

            // Otorgar XP por obtener certificado de congreso
            $this->xpService->addXP($inscrito, 300, "Certificado de congreso - {$curso->nombreCurso}");

            // Verificar logro de certificados
            $totalCertificados = Certificado::where('inscrito_id', $inscrito->id)->count();
            $this->achievementService->checkAndAwardAchievements($inscrito, 'CERTIFICATE_EARNED', $totalCertificados);
        }

        // Enviar notificación con el link de verificación solo si no se está regenerando
        if (!$regenerando && $inscrito->estudiantes && $inscrito->estudiantes->email) {
            $inscrito->estudiantes->notify(new CertificadoGeneradoNotification($inscrito, $codigo_certificado));
        }

        return back()->with('success', 'Certificado generado. Se ha enviado un enlace de verificación.');
    }

    public function vistaPreviaCertificado($curso_id)
    {
        $curso = Cursos::findOrFail($curso_id);
        $plantilla = CertificateTemplate::where('curso_id', $curso_id)->first();

        if (!$plantilla) {
            return back()->with('error', 'No hay plantilla de certificado para este curso.');
        }

        $inscrito = (object) [
            'id' => 999,
            'estudiantes' => (object) [
                'name' => 'Juan',
                'lastname1' => 'Pérez',
                'lastname2' => 'Gómez',
                'email' => 'juan@example.com',
            ],
            'cursos_id' => $curso->id,
        ];

        // Generar QR para vista previa
        $qrCodeSvg = QrCode::format('svg')
            ->size(200)
            ->generate(route('verificar.certificado', ['codigo' => 'PREVIEW-123456']));
        $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);

        $data = [
            'inscrito' => $inscrito,
            'nombre' => 'Nombre de Prueba',
            'curso' => $curso->nombre,
            'fecha' => now()->format('d/m/Y'),
            'plantillaf' => $plantilla->template_front_path,
            'plantillab' => $plantilla->template_back_path,
            'dni' => '00000000', // Dato simulado
            'codigo_certificado' => 'PREVIEW-CERT-1234', // Dato de prueba
            'qr_code' => $qrCodeBase64, // Usar QR en base64
            'primary_color' => $plantilla->primary_color,
            'font_family' => $plantilla->font_family,
            'font_size' => $plantilla->font_size
        ];


        $pdf = Pdf::loadView('certificados.plantilla', $data)->setPaper('A4', 'landscape');
        $pdf->setOption('dpi', 250);

        return $pdf->stream('vista-previa-certificado.pdf');
    }


    public function verificarCertificado($codigo)
    {
        // Buscar el certificado por su código único
        $certificado = Certificado::where('codigo_certificado', $codigo)->firstOrFail();

        // Cargar solo lo necesario
        $curso = Cursos::select('id', 'nombreCurso', 'fecha_fin')->findOrFail($certificado->curso_id);
        $inscrito = Inscritos::findOrFail($certificado->inscrito_id);
        $plantilla = CertificateTemplate::select('template_front_path', 'template_back_path', 'primary_color', 'font_family', 'font_size')
            ->where('curso_id', $certificado->curso_id)
            ->first();

        // Verificar si ya existe un QR guardado en el servidor
        $qrPath = "certificados/{$curso->id}/qrcode_{$inscrito->id}.svg";
        $qrExists = Storage::disk('public')->exists($qrPath);

        if (!$qrExists) {
            // Si no existe, generar nuevo QR como SVG y guardarlo
            $qrCodeSvg = QrCode::format('svg')
                ->size(200)
                ->generate(route('verificar.certificado', ['codigo' => $codigo]));

            // Guardar el QR en el servidor
            Storage::disk('public')->put($qrPath, $qrCodeSvg);
        }

        // Usar la ruta absoluta del archivo para el PDF
        $qrFilePath = storage_path('app/public/' . $qrPath);

        set_time_limit(300);
        ini_set('memory_limit', '256M'); // Aumenta la memoria disponible

        // Crear PDF con DomPDF
        $pdf = Pdf::loadView('certificados.plantilla', [
            'curso' => $curso->nombreCurso,
            'inscrito' => $inscrito,
            'codigo_certificado' => $codigo,
            'fecha_emision' => $certificado->created_at->format('d/m/Y'),
            'fecha_finalizacion' => Carbon::parse($curso->fecha_fin)->format('d/m/Y'),
            'qr_file_path' => $qrFilePath, // Usar la ruta del archivo
            'tipo' => 'Congreso',
            'plantillaf' => $plantilla->template_front_path,
            'plantillab' => $plantilla->template_back_path,
            'primary_color' => $plantilla->primary_color,
            'font_family' => $plantilla->font_family,
            'font_size' => $plantilla->font_size
        ]);

        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('dpi', 250);

        return $pdf->stream('certificado.pdf', ['Attachment' => false]);
    }


    public function register(Request $request, $congresoId)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'lastname1' => 'required|string|max:255',
            'lastname2' => 'nullable|string|max:255', // Hacerlo opcional
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'country' => 'required|string|max:100',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser un texto válido.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',

            'lastname1.required' => 'El apellido paterno es obligatorio.',
            'lastname1.string' => 'El apellido paterno debe ser un texto válido.',
            'lastname1.max' => 'El apellido paterno no puede tener más de 255 caracteres.',

            'lastname2.string' => 'El apellido materno debe ser un texto válido.',
            'lastname2.max' => 'El apellido materno no puede tener más de 255 caracteres.',

            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ingresar un correo electrónico válido.',
            'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
            'email.unique' => 'Este correo electrónico ya está registrado.',

            'password.required' => 'La contraseña es obligatoria.',
            'password.string' => 'La contraseña debe ser un texto válido.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',

            'country.required' => 'El país es obligatorio.',
            'country.string' => 'El país debe ser un texto válido.',
            'country.max' => 'El país no puede tener más de 100 caracteres.',
        ]);


        // Crear el usuario
        $user = User::create([
            'name' => $validatedData['name'],
            'lastname1' => $validatedData['lastname1'],
            'lastname2' => $validatedData['lastname2'] ?? '',
            'email' => $validatedData['email'],
            'CI' => Str::random(10),
            'Celular' => 0,
            'fechadenac' => Carbon::parse('2000-01-01'),
            'password' => Hash::make($validatedData['password']),
            'PaisReside' => $validatedData['country'],
        ]);

        $user->assignRole('Estudiante');


        // Inscribir al usuario en el congreso
        $inscripcion = Inscritos::create([
            'cursos_id' => $congresoId,
            'estudiante_id' => $user->id,
            'estado' => 'activo',
        ]);

        // Generar el certificado automáticamente
        $this->generarCertificadoIndividual($congresoId, $inscripcion->id, $user);

        // Autenticar al usuario
        Auth::login($user);

        return redirect()->route('Inicio')
            ->with('success', '¡Registro exitoso! Has sido inscrito en el congreso y tu certificado está listo. Por favor, verifica tu correo electrónico para activar tu cuenta.');
    }

    private function generarCertificadoIndividual($cursoId, $inscritoId, $user)
    {
        $inscrito = Inscritos::findOrFail($inscritoId);
        $curso = Cursos::findOrFail($cursoId);

        // Verificar si ya existe un certificado
        $certificadoExistente = Certificado::where('curso_id', $cursoId)
            ->where('inscrito_id', $inscritoId)
            ->first();

        if (!$certificadoExistente) {
            // Generar código único
            $codigo_certificado = Str::uuid();

            // Generar la ruta del certificado (URL de verificación)
            $ruta_certificado = route('verificar.certificado', ['codigo' => $codigo_certificado]);

            // Crear registro de certificado
            $certificado = Certificado::create([
                'curso_id' => $cursoId,
                'inscrito_id' => $inscritoId,
                'codigo_certificado' => $codigo_certificado,
                'ruta_certificado' => $ruta_certificado, // Agregar esta línea
            ]);

            // Otorgar XP por obtener certificado
            $this->xpService->addXP($inscrito, 200, "Certificado obtenido - {$curso->nombreCurso}");

            // Verificar logro de primer certificado
            $totalCertificados = Certificado::where('inscrito_id', $inscritoId)->count();
            $this->achievementService->checkAndAwardAchievements($inscrito, 'CERTIFICATE_EARNED', $totalCertificados);

            // Enviar notificación
            if ($user && $user->email) {
                $user->notify(new CertificadoGeneradoNotification($inscrito, $codigo_certificado));
            }

            return $certificado;
        }

        return $certificadoExistente;
    }


    public function ObtenerCertificado($congresoId)
    {
        $user = auth()->user();
        $curso = Cursos::find($congresoId);

        // Validar que el congreso exista
        if (!$curso) {
            return redirect()->route('Inicio')->with('error', 'El congreso no existe');
        }

        // Verificar si ya está inscrito
        $inscripcion = Inscritos::firstOrCreate(
            ['cursos_id' => $congresoId, 'estudiante_id' => $user->id],
            ['estado' => 'activo', 'fecha_inscripcion' => now()]
        );

        // Verificar si ya tiene certificado
        if ($inscripcion->certificado) {
            return redirect()->route('Inicio')->with('error', 'Ya tienes un certificado para este congreso');
        }

        // Generar nuevo certificado
        $certificadoGenerado = $this->generarCertificadoIndividual($congresoId, $inscripcion->id, $user);

        if ($certificadoGenerado) {
            return redirect()->route('Inicio')
                ->with('success', 'Certificado generado exitosamente');
        }

        return redirect()->route('Inicio')
            ->with('error', 'No se pudo generar el certificado');
    }

    public function inscribir(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:100',
            'congreso_id' => 'required|exists:cursos,id'
        ]);

        // Buscar usuario con sus inscripciones filtradas
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withInput()
                ->with('error', 'No existe una cuenta con este email. Por favor regístrate primero.');
        }

        // Buscar inscripción existente para este congreso
        $inscripcionExistente = Inscritos::with('certificado')
            ->where('cursos_id', $request->congreso_id)
            ->where('estudiante_id', $user->id)
            ->first();

        if ($inscripcionExistente) {
            if ($inscripcionExistente->certificado) {
                return back()
                    ->with('info', 'Ya estás inscrito en este congreso y tu certificado fue generado anteriormente.');
            }

            $this->generarCertificadoIndividual($request->congreso_id, $inscripcionExistente->id, $user);
            return back()
                ->with('success', '¡Certificado generado exitosamente! Revisa tu email.');
        }

        // Crear nueva inscripción
        $nuevaInscripcion = Inscritos::create([
            'cursos_id' => $request->congreso_id,
            'estudiante_id' => $user->id,
            'estado' => 'activo',
            'fecha_inscripcion' => now(),
        ]);

        $this->generarCertificadoIndividual($request->congreso_id, $nuevaInscripcion->id, $user);

        return back()
            ->with('success', 'Inscripción completada. Tu certificado ha sido enviado por email.');
    }

    public function reenviarCertificadoPorEmail($inscrito_id)
    {
        try {
            // Buscar inscripción con la relación del estudiante
            $inscripcion = Inscritos::with('estudiantes')->findOrFail($inscrito_id);

            // Verificar que el estudiante tenga email
            if (!$inscripcion->estudiantes || !$inscripcion->estudiantes->email) {
                return back()->with('error', 'El estudiante no tiene un email registrado.');
            }

            // Buscar certificado
            $certificado = Certificado::where('curso_id', $inscripcion->cursos_id)
                ->where('inscrito_id', $inscripcion->id)
                ->first();

            if (!$certificado) {
                return back()->with('error', 'Este inscrito no tiene un certificado generado.');
            }


            // Reenviar notificación usando el email del estudiante
            $inscripcion->estudiantes->notify(new CertificadoGeneradoNotification($inscripcion, $certificado->codigo_certificado));

            return back()->with('success', "Correo de certificado reenviado exitosamente a {$inscripcion->estudiantes->email}.");
        } catch (\Exception $e) {
            Log::error('Error al reenviar certificado por email usando inscrito ID: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al reenviar el correo del certificado.');
        }
    }
}
