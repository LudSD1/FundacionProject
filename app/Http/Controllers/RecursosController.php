<?php

namespace App\Http\Controllers;

use App\Events\RecursosEvent;
use App\Models\Cursos;
use App\Models\Recursos;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class RecursosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {

        $curso = Cursos::findOrFail($id);
        return view('Docente.CrearRecursos')->with('curso', $curso);
    }




    public function store(Request $request, $id)
    {
        $messages = [
            'tituloRecurso.required' => 'El campo título del recurso es obligatorio.',
            'descripcionRecurso.required' => 'El campo descripción del recurso es obligatorio.',
        ];

        // Validar los datos del formulario
        $validatedData = $request->validate([
            'tituloRecurso' => 'required|string|max:255',
            'descripcionRecurso' => 'required|string',
            'tipoRecurso' => 'nullable|string',
            'archivo' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,zip|max:2048',
        ], $messages);

        // Crear una nueva instancia del modelo Recursos
        $recurso = new Recursos();
        $recurso->nombreRecurso = $validatedData['tituloRecurso'];
        $recurso->cursos_id = $id;

        // Procesar la descripción para detectar y manejar enlaces de YouTube
        $descripcion = $validatedData['descripcionRecurso'];
        $recurso->descripcionRecursos = $this->procesarDescripcionConIframe($descripcion);

        // Asignar el tipo de recurso si existe
        $recurso->tipoRecurso = $validatedData['tipoRecurso'] ?? null;

        // Procesar archivo adjunto si se incluye
        if ($request->hasFile('archivo')) {
            $recurso->archivoRecurso = $request->file('archivo')->store('archivo', 'public');
        }

        // Guardar el recurso en la base de datos
        $recurso->save();

        // Redirigir con un mensaje de éxito
        return redirect(route('Curso', $id))->with('success', 'Recurso creado con éxito');
    }


    private function procesarDescripcionConIframe(string $descripcion): string
    {
        $iframe = '';

        if (Str::contains($descripcion, ['youtube.com', 'youtu.be'])) {
            $videoId = '';

            if (Str::contains($descripcion, 'youtu.be')) {
                $videoId = Str::after($descripcion, 'youtu.be/');
            } elseif (Str::contains($descripcion, 'youtube.com')) {
                $videoId = Str::between($descripcion, 'v=', '&') ?: Str::after($descripcion, 'v=');
            }

            if ($videoId) {
                $iframe = '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . $videoId . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';

                // Eliminar el enlace de YouTube del texto para evitar duplicados
                $descripcion = Str::replace([
                    'https://www.youtube.com/watch?v=' . $videoId,
                    'https://youtu.be/' . $videoId
                ], '', $descripcion);
            }
        }

        return $descripcion . ($iframe ? "\n\n" . $iframe : '');
    }


    public function descargar($archivo)
    {
        // Validar que el archivo no contenga caracteres peligrosos
        if (strpos($archivo, '..') !== false || strpos($archivo, '/') === 0) {
            abort(403, 'Acceso denegado');
        }

        // Construir la ruta completa correctamente
        $rutaArchivo = storage_path('app/' . $archivo);

        // Alternative: si los archivos están en public storage
        // $rutaArchivo = storage_path('app/public/' . $archivo);

        // Verificar que el archivo existe
        if (!file_exists($rutaArchivo)) {
            abort(404, 'Archivo no encontrado');
        }

        // Verificar que es un archivo (no un directorio)
        if (!is_file($rutaArchivo)) {
            abort(404, 'Recurso no válido');
        }

        // Obtener el nombre original del archivo
        $nombreArchivo = basename($archivo);

        // Detectar el tipo MIME
        $tipoMime = mime_content_type($rutaArchivo);

        return response()->download($rutaArchivo, $nombreArchivo, [
            'Content-Type' => $tipoMime,
        ]);
    }
   
    public function edit($id)
    {
        $recurso = Recursos::findOrFail($id);

        return view('Docente.EditarRecursos')->with('recurso', $recurso);

    }

    public function update(Request $request, $id)
    {
        $messages = [
            'tituloRecurso.required' => 'El campo título del recurso es obligatorio.',
            'descripcionRecurso.required' => 'El campo descripción del recurso es obligatorio.',
        ];

        // Validar los datos del formulario
        $validatedData = $request->validate([
            'tituloRecurso' => 'required|string|max:255',
            'descripcionRecurso' => 'required|string',
            'tipoRecurso' => 'nullable|string',
            'archivo' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,zip|max:2048',
            'cursos_id' => 'required|integer',
            'idRecurso' => 'required|integer',
        ], $messages);

        // Buscar el recurso a editar
        $recurso = Recursos::findOrFail($validatedData['idRecurso']);

        // Actualizar los campos del recurso
        $recurso->nombreRecurso = $validatedData['tituloRecurso'];
        $recurso->cursos_id = $validatedData['cursos_id'];

        // Procesar la descripción para detectar y manejar enlaces de YouTube
        $descripcion = $validatedData['descripcionRecurso'];
        $recurso->descripcionRecursos = $this->procesarDescripcionConIframe($descripcion);

        // Actualizar el tipo de recurso si se proporciona
        $recurso->tipoRecurso = $validatedData['tipoRecurso'] ?? $recurso->tipoRecurso;

        // Procesar archivo si se incluye uno nuevo
        if ($request->hasFile('archivo')) {
            // Opcional: eliminar el archivo anterior si aplica
            if ($recurso->archivoRecurso && \Storage::disk('public')->exists($recurso->archivoRecurso)) {
                \Storage::disk('public')->delete($recurso->archivoRecurso);
            }

            // Guardar el nuevo archivo
            $recursosPath = $request->file('archivo')->store('archivo', 'public');
            $recurso->archivoRecurso = $recursosPath;
        }

        // Guardar los cambios en la base de datos
        $recurso->save();

        // Redirigir con un mensaje de éxito
        return redirect(route('Curso', $validatedData['cursos_id']))->with('success', 'Recurso editado con éxito');
    }


    public function delete($id)
    {
        $recurso = Recursos::findOrFail($id);
        $recurso->delete();

        return back()->with('success', 'Eliminado con éxito');
    }

    public function indexE($id)
    {

        $cursos = Cursos::findOrFail($id);

        $recursos = Recursos::withTrashed()
        ->where('cursos_id', $id)
        ->onlyTrashed()
        ->get();


        return view('Docente.ListaRecursosEliminados')->with('recursos', $recursos)->with('cursos', $cursos);
    }




    public function restore($id)
    {
        $recurso = Recursos::onlyTrashed()->find($id);
        $recurso->restore();

        return back()->with('success', 'Restaurado con éxito');
    }




}
