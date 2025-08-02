<?php

namespace App\Http\Controllers;

use App\Events\EstudianteEvent;
use App\Models\Aportes;
use App\Models\Cursos;
use App\Models\Inscritos;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Mail\ReciboPagoMail;
use Illuminate\Support\Facades\Mail;

class AportesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $aportes = Aportes::where('estudiante_id', auth()->user()->id)->paginate(10);

        return view('FundacionPlantillaUsu.aportes')->with('aportes', $aportes);
    }
    public function habilitarCurso($aporte)
    {
        // Validar que el aporte exista
        $aporte = Aportes::with(['user', 'curso'])->findOrFail($aporte);

        // Buscar la inscripción correspondiente
        $inscrito = Inscritos::where('cursos_id', $aporte->cursos_id)
            ->where('estudiante_id', $aporte->estudiante_id)
            ->first(); // Cambiado de get() a first()

        // Si no existe la inscripción, retornar con error
        if (!$inscrito) {
            return back()->with('error', 'No se encontró la inscripción correspondiente para este aporte');
        }

        // Verificar que no esté ya habilitado
        if ($inscrito->pago_completado) {
            return back()->with('info', 'La inscripción ya estaba habilitada');
        }

        // Actualizar el estado de pago
        $inscrito->update([
            'pago_completado' => true,
        ]);

        // Generar URL del recibo
        $reciboUrl = route('recibo.generar', $aporte->id);

        // Enviar email al usuario
        try {
            Mail::to($aporte->user->email)->send(new ReciboPagoMail($aporte, $reciboUrl));
        } catch (\Exception $e) {
            // Log del error pero no interrumpir el flujo
            \Log::error('Error enviando email de recibo: ' . $e->getMessage());
        }

        // Generar y mostrar el recibo
        return view('Administrador.recibo-pago', ['pago' => $aporte]);
    }

    public function generarRecibo($id)
    {
        try {

            $aporte = Aportes::with(['user', 'curso'])->findOrFail($id);

            // Validación: solo el dueño o un admin puede ver el recibo
            if (auth()->user()->id !== $aporte->estudiante_id && !auth()->user()->hasRole('Administrador')) {
                abort(403, 'No tienes permiso para ver este recibo.');
            }

            // Mostrar vista según el rol
            if (auth()->user()->hasRole('Administrador')) {
                return view('Administrador.recibo-pago', ['pago' => $aporte]);
            } else {
                return view('aportes.recibo', ['aporte' => $aporte]);
            }
        } catch (\Exception $e) {
            \Log::error('Error generando recibo: ' . $e->getMessage());
            return back()->with('error', 'Error al generar el recibo: ' . $e->getMessage());
        }
    }

    public function reenviarRecibo($id)
    {
        try {
            \Log::info('Iniciando reenvío de recibo para ID: ' . $id);

            // Verificar que el aporte existe
            $aporte = Aportes::with(['user', 'curso'])->findOrFail($id);

            \Log::info('Aporte encontrado: ' . $aporte->codigopago . ' para usuario: ' . $aporte->user->email);

            // Verificar que el usuario tiene email
            if (!$aporte->user->email) {
                throw new \Exception('El usuario no tiene email registrado');
            }

            // Generar URL del recibo
            $reciboUrl = route('recibo.generar', $aporte->id);

            \Log::info('URL del recibo generada: ' . $reciboUrl);

            // Enviar email
            Mail::to($aporte->user->email)->send(new ReciboPagoMail($aporte, $reciboUrl));

            \Log::info('Email enviado exitosamente a: ' . $aporte->user->email);

            return response()->json([
                'success' => true,
                'message' => 'Email enviado exitosamente a ' . $aporte->user->email,
                'email' => $aporte->user->email,
                'codigo' => $aporte->codigopago
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Aporte no encontrado con ID: ' . $id);
            return response()->json([
                'success' => false,
                'message' => 'No se encontró el pago especificado'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Error reenviando email de recibo: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error al enviar el email: ' . $e->getMessage()
            ], 500);
        }
    }

    public function testEmail($id)
    {
        try {
            $aporte = Aportes::with(['user', 'curso'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Datos del aporte encontrados',
                'data' => [
                    'id' => $aporte->id,
                    'codigo' => $aporte->codigopago,
                    'estudiante' => $aporte->user->name . ' ' . $aporte->user->lastname1,
                    'email' => $aporte->user->email,
                    'curso' => $aporte->curso->nombreCurso ?? 'N/A'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function indexAdmin()
    {
        $estudiantes = User::role('Estudiante')->get();


        $cursos = Cursos::with('inscritos')->whereDate('fecha_fin', '>=', Carbon::today())->get();


        return view('registraraporte')
            ->with('estudiantes', $estudiantes)
            ->with('cursos', $cursos);
    }


    public function registrarpagoPost(Request $request)
    {
        $estudiante_id = $request->input('estudiante_id');
        $estudiante = User::find($estudiante_id);

        // Luego puedes acceder a los detalles del estudiante, por ejemplo:
        return view('registraraporte')->with('estudiante', $estudiante);
    }

    public function factura($id)
    {

        $aportes = Aportes::find($id);


        // Luego puedes acceder a los detalles del estudiante, por ejemplo:
        return view('Aportes.factura')->with('aportes', $aportes);
    }



    public function indexStore()
    {

        $cursos = Cursos::all();
        $estudiantes = User::role('Estudiante')->get();


        return view('registraraporte')
            ->with('cursos', $cursos)
            ->with('estudiantes', $estudiantes);
    }

    public function store(Request $request)
    {
        $request->validate([

            'nombre' => 'required',
            'monto' => 'required',
            'descripcion' => 'required',
            'archivo' => 'required',

        ]);

        $aportes = new Aportes();

        $aportes->datosEstudiante = $request->nombre;
        $aportes->DescripcionDelPago = $request->descripcion;

        $aportes->monto = $request->monto;

        $aportes->estudiante_id = $request->estudiante_id;

        if ($request->hasFile('archivo')) {
            $aportesPath = $request->file('archivo')->store('aportes', 'public');
            $aportes->comprobante = $aportesPath;
        }

        $aportes->save();



        return redirect(route('Inicio'))->with('success', 'Pago registrado exitosamente!');
    }


    public function storeadmin(Request $request)
    {
        $request->validate([
            'pagante' => 'required',
            'paganteci' => 'required',
            'montopagar' => 'required|numeric|min:0', // Validar que el monto a pagar sea un número no negativo
            'montocancelado' => 'required|numeric|min:0', // Validar que el monto cancelado sea un número no negativo
            'descripcion' => 'required',
        ], [
            'pagante.required' => 'El nombre del pagante es obligatorio.',
            'paganteci.required' => 'La cédula del pagante es obligatoria.',
            'montopagar.required' => 'El monto a pagar es obligatorio.',
            'montopagar.numeric' => 'El monto a pagar debe ser un número.',
            'montopagar.min' => 'El monto a pagar no puede ser negativo.',
            'montocancelado.required' => 'El monto cancelado es obligatorio.',
            'montocancelado.numeric' => 'El monto cancelado debe ser un número.',
            'montocancelado.min' => 'El monto cancelado no puede ser negativo.',
            'descripcion.required' => 'La descripción del pago es obligatoria.',
        ]);

        $aportes = new Aportes();
        $aportes->codigopago = uniqid();

        $aportes->pagante = $request->pagante;
        $aportes->paganteci = $request->paganteci;


        $estudiante_id = $request->input('estudiante_id');
        $estudiante = User::find($estudiante_id);

        $aportes->datosEstudiante = $estudiante->name . ' ' . $estudiante->lastname1 . ' ' . $estudiante->lastname2 . ' // ' . $estudiante->CI;
        $aportes->DescripcionDelPago = $request->descripcion;

        $aportes->monto_pagado = $request->montocancelado;
        $aportes->monto_a_pagar = $request->montopagar;


        // Calcular el restante a pagar utilizando el método max para evitar números negativos
        $aportes->restante_a_pagar = max(0, $request->montopagar - $request->montocancelado);

        // Calcular el cambio (si tiene sentido en tu lógica de negocio)
        $aportes->saldo = max(0, $request->montocancelado - $request->montopagar);

        $aportes->estudiante_id = $estudiante_id;

        $aportes->comprobante = '';

        $aportes->save();



        return redirect(route('Inicio'))->with('success', 'Pago registrado exitosamente!');
    }


    public function comprarCurso(Request $request)
    {
        $curso = Cursos::find($request->curso_id);
        if (!$curso) {
            return redirect()->back()->with('error', 'Curso no encontrado');
        }

        $user = auth()->user();

        // VERIFICAR SI YA PAGÓ ESTE CURSO ANTERIORMENTE
        if ($curso->precio > 0) {
            $pagoAnteriorCompleto = Aportes::where('estudiante_id', $user->id)
                ->where('cursos_id', $request->curso_id)
                ->where('monto_pagado', '>=', $curso->precio)
                ->first();

            if ($pagoAnteriorCompleto) {
                // Si ya pagó, solo crear inscripción sin cobrar nuevamente
                $inscripcionExistente = Inscritos::where('estudiante_id', $user->id)
                    ->where('cursos_id', $request->curso_id)
                    ->whereNull('deleted_at')
                    ->first();

                if (!$inscripcionExistente) {
                    $inscripcion = new Inscritos();
                    $inscripcion->estudiante_id = $user->id;
                    $inscripcion->cursos_id = $request->curso_id;
                    $inscripcion->pago_completado = true; // Ya pagó anteriormente
                    $inscripcion->save();

                    return redirect(route('Inicio'))->with('success', 'Te has reinscrito al curso. Tu pago anterior sigue siendo válido.');
                }

                return redirect()->back()->with('info', 'Ya estás inscrito en este curso y tu pago está completo.');
            }
        }

        // Verificar inscripción activa
        $inscripcionExistente = Inscritos::where('estudiante_id', $user->id)
            ->where('cursos_id', $request->curso_id)
            ->whereNull('deleted_at')
            ->first();

        // Si ya está inscrito y es de un modal de pago pendiente
        if ($inscripcionExistente && $request->has('inscrito_id')) {

            // Verificar si ya existe un aporte pendiente
            $aportePendiente = Aportes::where('estudiante_id', $user->id)
                ->where('cursos_id', $request->curso_id)
                ->where('monto_pagado', 0)
                ->where('restante_a_pagar', '>', 0)
                ->first();

            if ($aportePendiente) {
                return redirect()->back()->with('warning', 'Ya tienes un pago en proceso de validación para este curso. Por favor espera la confirmación del administrador.');
            }

            if ($curso->precio > 0) {
                $request->validate([
                    'comprobante' => 'required|file|mimes:pdf,jpg,png|max:2048',
                    'montopagar' => 'required|numeric|min:0',
                    'descripcion' => 'required',
                    'curso_id' => 'required|exists:cursos,id',
                    'inscrito_id' => 'required|exists:inscritos,id'
                ]);

                // Crear registro de aporte
                $aportes = new Aportes();
                $aportes->codigopago = uniqid();
                $aportes->pagante = $user->name . ' ' . $user->lastname1 . ' ' . $user->lastname2;
                $aportes->paganteci = $user->CI;
                $aportes->estudiante_id = $user->id;
                $aportes->datosEstudiante = $user->name . ' ' . $user->lastname1 . ' ' . $user->lastname2 . ' ' . $user->CI;
                $aportes->DescripcionDelPago = $request->input('descripcion');
                $aportes->monto_pagado = 0;
                $aportes->monto_a_pagar = $request->montopagar;
                $aportes->restante_a_pagar = $request->montopagar;
                $aportes->cursos_id = $request->curso_id;
                $aportes->tipopago = 'Comprobante';
                $aportes->saldo = 0;

                if ($request->hasFile('comprobante')) {
                    $rutaArchivo = $request->file('comprobante')->store('comprobantes', 'public');
                    $aportes->comprobante = $rutaArchivo;
                }

                $aportes->save();


                return redirect(route('Inicio'))->with('success', 'Tu pago será validado, por favor espere!');
            }
        }

        // Nueva inscripción
        if ($inscripcionExistente) {
            return redirect()->back()->with('error', 'Ya estás inscrito en este curso');
        }

        // Validaciones para nueva inscripción
        if ($curso->precio > 0) {
            // Verificar aportes pendientes también para nuevas inscripciones
            $aportePendiente = Aportes::where('estudiante_id', $user->id)
                ->where('cursos_id', $request->curso_id)
                ->where('monto_pagado', 0)
                ->where('restante_a_pagar', '>', 0)
                ->first();

            if ($aportePendiente) {
                return redirect()->back()->with('warning', 'Ya tienes un pago en proceso de validación para este curso.');
            }

            $request->validate([
                'comprobante' => 'required|file|mimes:pdf,jpg,png|max:2048',
                'montopagar' => 'required|numeric|min:0',
                'descripcion' => 'required',
                'curso_id' => 'required|exists:cursos,id'
            ]);

            // Crear registro de aporte
            $aportes = new Aportes();
            $aportes->codigopago = uniqid();
            $aportes->pagante = $user->name . ' ' . $user->lastname1 . ' ' . $user->lastname2;
            $aportes->paganteci = $user->CI;
            $aportes->estudiante_id = $user->id;
            $aportes->datosEstudiante = $user->name . ' ' . $user->lastname1 . ' ' . $user->lastname2 . ' ' . $user->CI;
            $aportes->DescripcionDelPago = $request->input('descripcion');
            $aportes->monto_pagado = 0;
            $aportes->monto_a_pagar = $request->montopagar;
            $aportes->restante_a_pagar = $request->montopagar;
            $aportes->cursos_id = $request->curso_id;
            $aportes->tipopago = 'Comprobante';
            $aportes->saldo = 0;

            if ($request->hasFile('comprobante')) {
                $rutaArchivo = $request->file('comprobante')->store('comprobantes', 'public');
                $aportes->comprobante = $rutaArchivo;
            }

            $aportes->save();
        } else {
            $request->validate([
                'curso_id' => 'required|exists:cursos,id'
            ]);
        }

        // Crear la inscripción
        $inscripcion = new Inscritos();
        $inscripcion->estudiante_id = $user->id;
        $inscripcion->cursos_id = $request->curso_id;
        $inscripcion->pago_completado = ($curso->precio == 0);
        $inscripcion->save();

        return redirect(route('Inicio'))->with('success', $curso->precio > 0
            ? 'Tu pago será validado, por favor espere!'
            : '¡Inscripción al curso gratuito realizada con éxito!');
    }





    public function actualizarPago(Request $request, $codigopago)
    {
        $request->validate([
            'monto_pagado' => 'required|numeric|min:0',
        ]);

        $pago = Aportes::where('codigopago', $codigopago)->firstOrFail();

        if ($pago->monto_pagado > 0) {
            return redirect()->back()->with('error', 'Este pago ya fue procesado anteriormente');
        }

        if ($request->monto_pagado > $pago->restante_a_pagar) {
            return redirect()->back()->with('error', 'El monto excede el saldo pendiente');
        }

        $pago->monto_pagado = $request->monto_pagado;
        $pago->restante_a_pagar = $pago->monto_a_pagar - $pago->monto_pagado;
        $pago->Saldo = max(0, $pago->restante_a_pagar);
        $pago->save();

        // Solo si ya terminó de pagar
        if ($pago->restante_a_pagar == 0) {
            $this->cambiarEstadoIns($pago->estudiante_id, $pago->cursos_id);
        }

        return redirect()->back()->with('success', 'Pago registrado exitosamente');
    }

    protected function cambiarEstadoIns($estudiante_id, $curso_id)
    {
        $inscrito = Inscritos::where('cursos_id', $curso_id)
            ->where('estudiante_id', $estudiante_id)
            ->first();

        if (!$inscrito) {
            \Log::warning("No se encontró inscripción del estudiante $estudiante_id en el curso $curso_id");
            return; // salir silenciosamente
        }

        // Ejemplo: cambiar estado
        $inscrito->pago_completado = true;
        $inscrito->save();
    }

    public function verRecibo($id)
    {
        $aporte = Aportes::findOrFail($id);

        // Validación: solo el dueño o un admin puede ver el recibo
        if (auth()->user()->id !== $aporte->estudiante_id && !auth()->user()->hasrole('Administrador')) {
            abort(403, 'No tienes permiso para ver este recibo.');
        }

        // Retorna la vista del recibo (ajusta el nombre de la vista si es necesario)
        return view('aportes.recibo', compact('aporte'));
    }
}
