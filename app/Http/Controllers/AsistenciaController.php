<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Cursos;
use App\Models\Inscritos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AsistenciaController extends Controller
{
    public function show($id)
    {
        $cursos = Cursos::findOrFail($id);


        $inscritos = Inscritos::whereNull('deleted_at')->get();

        return view('Docente.ListaAsistencia')->with('cursos', $cursos)->with('inscritos', $inscritos);
    }

    public function historialAsistencia(Request $request, $cursoId)
    {
        $cursos = Cursos::findOrFail($cursoId);

        $query = Asistencia::with(['inscritos.estudiantes'])
                          ->where('curso_id', $cursoId);

        // Aplicar filtros
        if ($request->filled('busqueda')) {
            $busqueda = $request->busqueda;
            $query->whereHas('inscritos.estudiantes', function($q) use ($busqueda) {
                $q->where('name', 'LIKE', "%{$busqueda}%")
                  ->orWhere('lastname1', 'LIKE', "%{$busqueda}%")
                  ->orWhere('lastname2', 'LIKE', "%{$busqueda}%");
            });
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fechaasistencia', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fechaasistencia', '<=', $request->fecha_hasta);
        }

        if ($request->filled('tipo_asistencia')) {
            $query->where('tipoAsitencia', $request->tipo_asistencia);
        }

        // Aplicar ordenamiento
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'estudiante':
                    $query->join('inscritos', 'asistencias.inscrito_id', '=', 'inscritos.id')
                          ->join('users', 'inscritos.estudiante_id', '=', 'users.id')
                          ->orderBy('users.name', $request->get('direction', 'asc'));
                    break;
                case 'fecha':
                    $query->orderBy('fechaasistencia', $request->get('direction', 'desc'));
                    break;
            }
        } else {
            $query->orderBy('fechaasistencia', 'desc');
        }

        $asistencias = $query->paginate(15)->withQueryString();

        return view('Docente.HistorialAsistencia', compact('cursos', 'asistencias'));
    }

    public function store(Request $request)
    {


        $asistencias = $request->input('asistencia');

        $errors = []; // Initialize an array to store validation errors

        foreach ($asistencias as $asistencia) {
            $curso_id = $asistencia['curso_id'];
            $inscritos_id = $asistencia['inscritos_id'];
            $fechaAsistencia = $request->fecha_asistencia;
            $tipoasistencia = $asistencia['tipo_asistencia'];

            // Define validation rules for tipoasistencia
            $tipoasistenciaRules = ['required']; // Customize the allowed types

            // Validate the tipoasistencia field
            $validator = Validator::make(['tipoasistencia' => $tipoasistencia], [
                'tipoasistencia' => $tipoasistenciaRules,
            ]);

            if ($validator->fails()) {
                // Add an error message to the $errors array
                $errors[] = "Validation failed for tipoasistencia: " . implode(', ', $validator->errors()->get('tipoasistencia'));
            } else {
                // Check for an existing record
                $existingRecord = DB::table('asistencia')
                    ->where('curso_id', $curso_id)
                    ->where('inscripcion_id', $inscritos_id)
                    ->where('fechaasistencia', $fechaAsistencia)
                    ->first();

                if (!$existingRecord) {
                    // Insert the record if no matching combination exists
                    DB::table('asistencia')->insert([
                        'tipoAsitencia' => $tipoasistencia,
                        'fechaasistencia' => $fechaAsistencia,
                        'curso_id' =>  $curso_id,
                        'inscripcion_id' => $inscritos_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    // Add an error message to the $errors array
                    $errors[] = "Ya realizaste la asistencia de hoy";
                }
            }
        }

        if (!empty($errors)) {
            return back()->with('error', 'Se produjeron errores de validación. Por favor verifique los datos.');
        } else {
            return back()->with('success', 'Asistencia registrada Correctamente.');
        }
    }



    public function edit(Request $request)
    {

        $asistencias = $request->input('asistencia');

        foreach ($asistencias as $asistencias) {

            $asistenciaid = $asistencias['id'];
            $asistencia = Asistencia::findOrFail($asistenciaid);

            $asistencia->tipoAsitencia = $asistencias['tipo_asistencia'];
            $asistencia->save();
        }

        return back()->with('success', 'Asistencia editada Correctamente');
    }

    public function index2($id)
    {

        $cursos = Cursos::findOrFail($id);
        $inscritos = Inscritos::where('cursos_id', $id)->get();

        return view('Docente.AsignarAsistencia')->with('cursos', $cursos)->with('inscritos', $inscritos);
    }

    public function store2(Request $request)
    {

        $messages = [
            'estudiante.required' => 'El campo estudiante es obligatorio.',
            'fecha.required' => 'El campo fecha es obligatorio.',
            'curso_id.required' => 'El campo curso_id es obligatorio.',
            'asistencia.required' => 'El campo asistencia es obligatorio.',
        ];

        $request->validate([
            'estudiante' => 'required',
            'fecha' => 'required',
            'curso_id' => 'required',
            'asistencia' => 'required',
        ], $messages);

        $existingRecord = DB::table('asistencia')
            ->where('curso_id', $request->curso_id)
            ->where('inscripcion_id', $request->estudiante)
            ->where('fechaasistencia', $request->fecha)
            ->first();

        if (!$existingRecord) {
            // Insert the record if no matching combination exists
            DB::table('asistencia')->insert([
                'tipoAsitencia' => $request->asistencia,
                'fechaasistencia' => $request->fecha,
                'curso_id' => $request->curso_id,
                'inscripcion_id' => $request->estudiante,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return back()
                ->with('success', 'Asistencia registrada exitosamente.');
        } else {
            // Use the Laravel validation error bag for consistency
            return back()->withErrors(['error' => 'Ya realizaste la asistencia de ese estudiante']);
        }
    }
}
