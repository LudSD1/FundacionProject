<?php

namespace App\Http\Controllers;

use App\Models\ActividadCompletion;
use App\Models\RecursoSubtema;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class RecursoSubtemaController extends Controller
{


    public function marcarRecursoComoVisto(Request $request, $recursoId)
    {
        $request->validate([
            'inscritos_id' => 'required|exists:inscritos,id',
        ]);

        ActividadCompletion::updateOrCreate(
            [
                'completable_type' => RecursoSubtema::class,
                'completable_id' => $recursoId,
                'inscritos_id' => $request->inscritos_id,
            ],
            [
                'completed' => true,
                'completed_at' => now(),
            ]
        );

        return back()->with('success', 'Recurso marcado como visto.');
    }

    public function store(Request $request, $id)
    {
        try {
            $messages = [
                'tituloRecurso.required' => 'El título del recurso es obligatorio.',
                'tituloRecurso.max' => 'El título no puede superar los 255 caracteres.',
                'descripcionRecurso.required' => 'La descripción del recurso es obligatoria.',
                'archivo.file' => 'El archivo debe ser un archivo válido.',
                'archivo.mimes' => 'El archivo debe ser de uno de los tipos permitidos.',
                'archivo.max' => 'El archivo no debe superar los 10MB.',
            ];

            // Validar los datos del formulario
            $validatedData = $request->validate([
                'tituloRecurso' => 'required|string|max:255',
                'descripcionRecurso' => 'required|string',
                'tipoRecurso' => 'nullable|string',
                'archivo' => 'nullable|file|mimes:jpg,jpeg,png,gif,doc,docx,xls,xlsx,ppt,pptx,pdf,txt,mp4,mp3,wav,ogg,zip,rar|max:10240',
            ], $messages);

            // Crear una nueva instancia del modelo Recursos
            $recurso = new RecursoSubtema();
            $recurso->nombreRecurso = $validatedData['tituloRecurso'];
            $recurso->subtema_id = $id;

            // Procesar la descripción para detectar y manejar enlaces de YouTube
            $descripcion = $validatedData['descripcionRecurso'];
            $recurso->descripcionRecursos = $this->procesarDescripcionConIframe($descripcion);
            $recurso->tipoRecurso = $validatedData['tipoRecurso'] ?? null;

            // Procesar archivo adjunto si se incluye
            if ($request->hasFile('archivo')) {
                $file = $request->file('archivo');

                // Generar un nombre único para el archivo
                $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

                // Determinar el subdirectorio según el tipo de archivo
                $subDirectory = $this->getSubDirectoryByMimeType($file->getMimeType());

                // Crear la ruta completa
                $path = $subDirectory . '/' . $fileName;

                // Intentar subir el archivo
                try {
                    Storage::disk('public')->putFileAs($subDirectory, $file, $fileName);
                    $recurso->archivoRecurso = $path;
                } catch (\Exception $e) {
                    Log::error('Error al subir archivo: ' . $e->getMessage());
                    return back()->with('error', 'Error al subir el archivo. Por favor, inténtelo de nuevo.');
                }
            }

            // Guardar el recurso en la base de datos
            $recurso->save();

            return back()->with('success', 'Recurso creado con éxito');
        } catch (\Exception $e) {
            Log::error('Error al crear recurso: ' . $e->getMessage());
            return back()->with('error', 'Error al crear el recurso. Por favor, inténtelo de nuevo.');
        }
    }

    /**
     * Determina el subdirectorio según el tipo MIME del archivo
     */
    private function getSubDirectoryByMimeType($mimeType)
    {
        $baseDir = 'recursos';

        if (Str::startsWith($mimeType, 'image/')) {
            return $baseDir . '/imagenes';
        } elseif (Str::startsWith($mimeType, 'video/')) {
            return $baseDir . '/videos';
        } elseif (Str::startsWith($mimeType, 'audio/')) {
            return $baseDir . '/audios';
        } elseif (Str::contains($mimeType, ['pdf', 'msword', 'vnd.openxmlformats-officedocument'])) {
            return $baseDir . '/documentos';
        } elseif (Str::contains($mimeType, ['zip', 'rar', 'x-compressed'])) {
            return $baseDir . '/comprimidos';
        }

        return $baseDir . '/otros';
    }

    private function procesarDescripcionConIframe(string $descripcion): string
    {
        $iframe = '';
        $videoId = '';

        if (Str::contains($descripcion, ['youtube.com', 'youtu.be'])) {

            // Extraer ID desde "youtu.be/VIDEO_ID"
            if (Str::contains($descripcion, 'youtu.be')) {
                $videoId = Str::after($descripcion, 'youtu.be/');
            }
            // Extraer ID desde "youtube.com/watch?v=VIDEO_ID"
            elseif (Str::contains($descripcion, 'youtube.com/watch')) {
                $videoId = Str::after($descripcion, 'v=');
            }
            // Extraer ID desde "youtube.com/shorts/VIDEO_ID"
            elseif (Str::contains($descripcion, 'youtube.com/shorts/')) {
                $videoId = Str::after($descripcion, 'youtube.com/shorts/');
            }

            // Limpiar el ID eliminando parámetros adicionales (&list=, &index=, etc.)
            $videoId = strtok($videoId, '&');

            if (!empty($videoId)) {
                $iframe = '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . $videoId . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';

                // Eliminar enlaces de YouTube del texto original
                $descripcion = Str::replaceMatches('/https?:\/\/(www\.)?youtube\.com\/watch\?v=[\w-]+(&[\w=]*)*/', '', $descripcion);
                $descripcion = Str::replaceMatches('/https?:\/\/youtu\.be\/[\w-]+(&[\w=]*)*/', '', $descripcion);
                $descripcion = Str::replaceMatches('/https?:\/\/(www\.)?youtube\.com\/shorts\/[\w-]+/', '', $descripcion);
            }
        }

        return trim($descripcion) . ($iframe ? "\n\n" . $iframe : '');
    }


    public function descargar($nombreArchivo)
    {
        $rutaArchivo = storage_path('/app/public/' . $nombreArchivo);

        return response()->download($rutaArchivo);
    }

    public function update(Request $request, $id)
    {
        try {
            $messages = [
                'tituloRecurso.required' => 'El título del recurso es obligatorio.',
                'tituloRecurso.max' => 'El título no puede superar los 255 caracteres.',
                'descripcionRecurso.required' => 'La descripción del recurso es obligatoria.',
                'archivo.file' => 'El archivo debe ser un archivo válido.',
                'archivo.mimes' => 'El archivo debe ser de uno de los tipos permitidos.',
                'archivo.max' => 'El archivo no debe superar los 10MB.',
            ];

            // Validar los datos del formulario
            $validatedData = $request->validate([
                'tituloRecurso' => 'required|string|max:255',
                'descripcionRecurso' => 'required|string',
                'tipoRecurso' => 'nullable|string',
                'archivo' => 'nullable|file|mimes:jpg,jpeg,png,gif,doc,docx,xls,xlsx,ppt,pptx,pdf,txt,mp4,mp3,wav,ogg,zip,rar|max:10240',
                'eliminarArchivo' => 'nullable|boolean',
            ], $messages);

            // Buscar el recurso a editar
            $recurso = RecursoSubtema::findOrFail($id);

            // Actualizar los campos del recurso
            $recurso->nombreRecurso = $validatedData['tituloRecurso'];
            $recurso->descripcionRecursos = $this->procesarDescripcionConIframe($validatedData['descripcionRecurso']);
            $recurso->tipoRecurso = $validatedData['tipoRecurso'] ?? $recurso->tipoRecurso;

            // Eliminar el archivo actual si el checkbox está marcado
            if ($request->has('eliminarArchivo') && $request->eliminarArchivo) {
                if ($recurso->archivoRecurso && Storage::disk('public')->exists($recurso->archivoRecurso)) {
                    Storage::disk('public')->delete($recurso->archivoRecurso);
                }
                $recurso->archivoRecurso = null;
            }

            // Procesar archivo si se incluye uno nuevo
            if ($request->hasFile('archivo')) {
                $file = $request->file('archivo');

                // Generar un nombre único para el archivo
                $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

                // Determinar el subdirectorio según el tipo de archivo
                $subDirectory = $this->getSubDirectoryByMimeType($file->getMimeType());

                // Crear la ruta completa
                $path = $subDirectory . '/' . $fileName;

                // Eliminar el archivo anterior si existe
                if ($recurso->archivoRecurso && Storage::disk('public')->exists($recurso->archivoRecurso)) {
                    Storage::disk('public')->delete($recurso->archivoRecurso);
                }

                // Intentar subir el archivo
                try {
                    Storage::disk('public')->putFileAs($subDirectory, $file, $fileName);
                    $recurso->archivoRecurso = $path;
                } catch (\Exception $e) {
                    Log::error('Error al subir archivo: ' . $e->getMessage());
                    return back()->with('error', 'Error al subir el archivo. Por favor, inténtelo de nuevo.');
                }
            }

            // Guardar los cambios en la base de datos
            $recurso->save();

            return back()->with('success', 'Recurso actualizado con éxito.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar recurso: ' . $e->getMessage());
            return back()->with('error', 'Error al actualizar el recurso. Por favor, inténtelo de nuevo.');
        }
    }

    public function delete($id)
    {
        $recurso = RecursoSubtema::findOrFail($id);
        $recurso->delete();

        return back()->with('success', 'Eliminado con éxito');
    }


    public function restore($id)
    {
        $recurso = RecursoSubtema::onlyTrashed()->find($id);
        $recurso->restore();

        return back()->with('success', 'Restaurado con éxito');
    }
}
