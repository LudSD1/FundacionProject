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
            $query->whereHas('inscritos.estudiantes', function ($q) use ($busqueda) {
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
        // Validación general del request
        $request->validate([
            'fecha_asistencia' => 'required|date',
            'asistencia' => 'required|array',
            'asistencia.*.tipo_asistencia' => 'required|in:Presente,Retraso,Licencia,Falta',
            'asistencia.*.curso_id' => 'required|exists:cursos,id',
            'asistencia.*.inscritos_id' => 'required|exists:inscritos,id'
        ], [
            'fecha_asistencia.required' => 'La fecha es obligatoria.',
            'fecha_asistencia.date' => 'La fecha debe ser válida.',
            'asistencia.required' => 'Debe seleccionar al menos una asistencia.',
            'asistencia.*.tipo_asistencia.required' => 'Debe seleccionar el tipo de asistencia para todos los estudiantes.',
            'asistencia.*.tipo_asistencia.in' => 'El tipo de asistencia debe ser válido.',
        ]);

        $asistencias = $request->input('asistencia');
        $fechaAsistencia = $request->fecha_asistencia;

        $errors = [];
        $successCount = 0;
        $duplicateCount = 0;
        $studentNames = [];

        DB::beginTransaction();

        try {
            foreach ($asistencias as $index => $asistencia) {
                // Saltar si no se seleccionó tipo de asistencia
                if (empty($asistencia['tipo_asistencia'])) {
                    continue;
                }

                $curso_id = $asistencia['curso_id'];
                $inscritos_id = $asistencia['inscritos_id'];
                $tipoasistencia = $asistencia['tipo_asistencia'];

                // Verificar si ya existe la asistencia para esta fecha
                $existingRecord = DB::table('asistencia')
                    ->where('curso_id', $curso_id)
                    ->where('inscripcion_id', $inscritos_id)
                    ->whereDate('fechaasistencia', $fechaAsistencia)
                    ->first();

                if (!$existingRecord) {
                    // Insertar nueva asistencia
                    DB::table('asistencia')->insert([
                        'tipoAsitencia' => $tipoasistencia,
                        'fechaasistencia' => $fechaAsistencia,
                        'curso_id' => $curso_id,
                        'inscripcion_id' => $inscritos_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $successCount++;
                } else {
                    // Obtener nombre del estudiante para el mensaje
                    $inscrito = Inscritos::with('estudiantes')->find($inscritos_id);
                    if ($inscrito && $inscrito->estudiantes) {
                        $studentName = $inscrito->estudiantes->name . ' ' .
                            $inscrito->estudiantes->lastname1 . ' ' .
                            $inscrito->estudiantes->lastname2;
                        $studentNames[] = $studentName;
                    }
                    $duplicateCount++;
                }
            }

            DB::commit();

            // Preparar mensajes de respuesta
            $messages = [];

            if ($successCount > 0) {
                $messages[] = "Se registraron {$successCount} asistencias correctamente.";
            }

            if ($duplicateCount > 0) {
                $studentList = !empty($studentNames) ? implode(', ', array_slice($studentNames, 0, 3)) : '';
                if (count($studentNames) > 3) {
                    $studentList .= ' y ' . (count($studentNames) - 3) . ' más';
                }

                if ($duplicateCount == 1) {
                    $messages[] = "Ya existe asistencia registrada para: {$studentList}";
                } else {
                    $messages[] = "Ya existe asistencia registrada para {$duplicateCount} estudiantes" .
                        (!empty($studentList) ? ": {$studentList}" : "");
                }
            }

            if ($successCount > 0 && $duplicateCount == 0) {
                return back()->with('success', implode(' ', $messages));
            } elseif ($successCount > 0 && $duplicateCount > 0) {
                return back()->with('warning', implode(' ', $messages));
            } else {
                return back()->with('error', $duplicateCount > 0 ? implode(' ', $messages) : 'No se pudo registrar ninguna asistencia.');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al registrar las asistencias: ' . $e->getMessage());
        }
    }



    public function edit(Request $request)
    {
        $request->validate([
            'asistencia' => 'required|array',
            'asistencia.*.id' => 'required|exists:asistencia,id',
            'asistencia.*.tipo_asistencia' => 'required|in:Presente,Retraso,Licencia,Falta'
        ]);

        $asistencias = $request->input('asistencia');
        $updateCount = 0;

        DB::beginTransaction();

        try {
            foreach ($asistencias as $asistenciaData) {
                $asistenciaid = $asistenciaData['id'];
                $asistencia = Asistencia::findOrFail($asistenciaid);

                $asistencia->tipoAsitencia = $asistenciaData['tipo_asistencia'];
                $asistencia->save();
                $updateCount++;
            }

            DB::commit();
            return back()->with('success', "Se actualizaron {$updateCount} asistencias correctamente.");
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al actualizar las asistencias: ' . $e->getMessage());
        }
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
            'fecha.date' => 'La fecha debe ser válida.',
            'curso_id.required' => 'El campo curso_id es obligatorio.',
            'asistencia.required' => 'El campo asistencia es obligatorio.',
            'asistencia.in' => 'El tipo de asistencia debe ser válido.',
        ];

        $request->validate([
            'estudiante' => 'required|exists:inscritos,id',
            'fecha' => 'required|date',
            'curso_id' => 'required|exists:cursos,id',
            'asistencia' => 'required|in:Presente,Retraso,Licencia,Falta',
        ], $messages);

        // Verificar si ya existe la asistencia
        $existingRecord = DB::table('asistencia')
            ->where('curso_id', $request->curso_id)
            ->where('inscripcion_id', $request->estudiante)
            ->whereDate('fechaasistencia', $request->fecha)
            ->first();

        if (!$existingRecord) {
            try {
                // Insertar nueva asistencia
                DB::table('asistencia')->insert([
                    'tipoAsitencia' => $request->asistencia,
                    'fechaasistencia' => $request->fecha,
                    'curso_id' => $request->curso_id,
                    'inscripcion_id' => $request->estudiante,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                return back()->with('success', 'Asistencia registrada exitosamente.');
            } catch (\Exception $e) {
                return back()->with('error', 'Error al registrar la asistencia: ' . $e->getMessage());
            }
        } else {
            // Obtener el nombre del estudiante para un mensaje más informativo
            $inscrito = Inscritos::with('estudiantes')->find($request->estudiante);
            $studentName = 'este estudiante';

            if ($inscrito && $inscrito->estudiantes) {
                $studentName = $inscrito->estudiantes->name . ' ' .
                    $inscrito->estudiantes->lastname1 . ' ' .
                    $inscrito->estudiantes->lastname2;
            }

            return back()->withErrors([
                'error' => "Ya existe una asistencia registrada para {$studentName} en la fecha {$request->fecha}."
            ])->withInput();
        }
    }
}
