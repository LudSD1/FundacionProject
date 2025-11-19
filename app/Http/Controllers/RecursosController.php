<?php

namespace App\Http\Controllers;

use App\Events\RecursosEvent;
use App\Models\Cursos;
use App\Models\Recursos;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

class RecursosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function store(Request $request, $id)
    {


        // Validar los datos del formulario
        $validatedData = $request->validate([
            'tituloRecurso' => 'required|string|max:255',
            'descripcionRecurso' => 'required|string',
            'tipoRecurso' => 'nullable|string',
            'archivo' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,zip|max:2048',
        ], [
            'tituloRecurso.required' => 'El campo título del recurso es obligatorio.',
            'descripcionRecurso.required' => 'El campo descripción del recurso es obligatorio.',
            'archivo.mimes' => 'El archivo adjunto debe ser una imagen, PDF, DOC o DOCX.',
            'archivo.max' => 'El archivo adjunto no puede exceder los 2MB.',
        ]);

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
        return redirect(route('Curso', encrypt($id)))->with('success', 'Recurso creado con éxito');
    }

    private function procesarDescripcionConIframe(string $descripcion): string
    {
        $iframe = '';

        if (Str::contains($descripcion, ['youtube.com', 'youtu.be'])) {
            $videoId = '';

            if (Str::contains($descripcion, 'youtu.be')) {
                $videoId = Str::after($descripcion, 'youtu.be/');
                $videoId = Str::before($videoId, '?'); // Remover parámetros adicionales
            } elseif (Str::contains($descripcion, 'youtube.com')) {
                if (Str::contains($descripcion, 'v=')) {
                    $videoId = Str::between($descripcion, 'v=', '&') ?: Str::after($descripcion, 'v=');
                }
            }

            // Limpiar el videoId de caracteres no válidos
            $videoId = preg_replace('/[^a-zA-Z0-9_-]/', '', $videoId);

            if ($videoId && strlen($videoId) === 11) { // Los IDs de YouTube tienen 11 caracteres
                $iframe = '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . $videoId . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';

                // Eliminar el enlace de YouTube del texto para evitar duplicados
                $descripcion = preg_replace('/https?:\/\/(www\.)?youtube\.com\/watch\?v=' . $videoId . '[^\s]*/', '', $descripcion);
                $descripcion = preg_replace('/https?:\/\/youtu\.be\/' . $videoId . '[^\s]*/', '', $descripcion);
            }
        }

        return trim($descripcion) . ($iframe ? "\n\n" . $iframe : '');
    }

    /**
     * Descargar archivo de recurso
     * Función mejorada con mejor manejo de errores y seguridad
     */
    public function descargar($id)
    {
        try {
            // Buscar el recurso por ID en lugar del nombre del archivo
            $recurso = Recursos::findOrFail($id);

            // Verificar que el recurso tenga un archivo asociado
            if (!$recurso->archivoRecurso) {
                return back()->with('error', 'Este recurso no tiene un archivo asociado.');
            }

            // Verificar que el archivo existe en el storage
            if (!Storage::disk('public')->exists($recurso->archivoRecurso)) {
                Log::warning('Archivo no encontrado en storage: ' . $recurso->archivoRecurso);
                return back()->with('error', 'El archivo no se encuentra disponible.');
            }

            // Obtener la ruta completa del archivo
            $rutaArchivo = Storage::disk('public')->path($recurso->archivoRecurso);

            // Verificar que es un archivo válido
            if (!is_file($rutaArchivo)) {
                return back()->with('error', 'El recurso solicitado no es válido.');
            }

            // Generar un nombre amigable para la descarga
            $extension = pathinfo($rutaArchivo, PATHINFO_EXTENSION);
            $nombreDescarga = Str::slug($recurso->nombreRecurso) . '.' . $extension;

            // Obtener el tipo MIME
            $tipoMime = Storage::disk('public')->mimeType($recurso->archivoRecurso);

            // Retornar la descarga
            return Storage::disk('public')->download(
                $recurso->archivoRecurso,
                $nombreDescarga,
                [
                    'Content-Type' => $tipoMime,
                    'Content-Disposition' => 'attachment; filename="' . $nombreDescarga . '"'
                ]
            );

        } catch (\Exception $e) {
            Log::error('Error en descarga de recurso: ' . $e->getMessage());
            return back()->with('error', 'Error al descargar el archivo. Inténtelo nuevamente.');
        }
    }



    public function update(Request $request, $id)
    {

        // Validar los datos del formulario
        $validatedData = $request->validate([
            'tituloRecurso' => 'required|string|max:255',
            'descripcionRecurso' => 'required|string',
            'tipoRecurso' => 'nullable|string',
            'archivo' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,zip|max:2048',
            'cursos_id' => 'required|integer',
            'idRecurso' => 'required|integer',
        ], [
            'tituloRecurso.required' => 'El campo título del recurso es obligatorio.',
            'descripcionRecurso.required' => 'El campo descripción del recurso es obligatorio.',
            'archivo.mimes' => 'El archivo adjunto debe ser una imagen, PDF, DOC o DOCX.',
            'archivo.max' => 'El archivo adjunto no puede exceder los 2MB.',
        ]);

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
            // Eliminar el archivo anterior si existe
            if ($recurso->archivoRecurso && Storage::disk('public')->exists($recurso->archivoRecurso)) {
                Storage::disk('public')->delete($recurso->archivoRecurso);
            }

            // Guardar el nuevo archivo
            $recurso->archivoRecurso = $request->file('archivo')->store('archivo', 'public');
        }

        // Guardar los cambios en la base de datos
        $recurso->save();

        // Redirigir con un mensaje de éxito
        return back()->with('success', 'Recurso editado con éxito');
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

        return view('Docente.ListaRecursosEliminados')
            ->with('recursos', $recursos)
            ->with('cursos', $cursos);
    }

    public function restore($id)
    {
        $recurso = Recursos::onlyTrashed()->find($id);

        if (!$recurso) {
            return back()->with('error', 'Recurso no encontrado.');
        }

        $recurso->restore();
        return back()->with('success', 'Restaurado con éxito');
    }
}
