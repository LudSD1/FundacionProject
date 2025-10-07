<?php

namespace App\Services;

use Illuminate\Support\Str;

class YouTubeEmbedService
{
    /**
     * Convierte enlaces de YouTube dentro de una descripción
     * en un iframe embebido y limpia el texto.
     */
    public function procesarDescripcionConIframe(string $descripcion): string
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

            // Limpiar el ID eliminando parámetros adicionales
            $videoId = strtok($videoId, '&');

            if (!empty($videoId)) {
                $iframe = sprintf(
                    '<iframe width="560" height="315" src="https://www.youtube.com/embed/%s" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
                    e($videoId)
                );

                // Eliminar enlaces de YouTube del texto original
                $descripcion = Str::replaceMatches('/https?:\/\/(www\.)?youtube\.com\/watch\?v=[\w-]+(&[\w=]*)*/', '', $descripcion);
                $descripcion = Str::replaceMatches('/https?:\/\/youtu\.be\/[\w-]+(&[\w=]*)*/', '', $descripcion);
                $descripcion = Str::replaceMatches('/https?:\/\/(www\.)?youtube\.com\/shorts\/[\w-]+/', '', $descripcion);
            }
        }

        return trim($descripcion) . ($iframe ? "\n\n" . $iframe : '');
    }


    public function obtenerUrlEmbed(string $url): ?string
    {
        if (empty($url)) return null;

        $videoId = '';

        if (Str::contains($url, 'watch?v=')) {
            $videoId = Str::after($url, 'v=');
        } elseif (Str::contains($url, 'youtu.be/')) {
            $videoId = Str::after($url, 'youtu.be/');
        } elseif (Str::contains($url, 'shorts/')) {
            $videoId = Str::after($url, 'shorts/');
        }

        $videoId = strtok($videoId, '&'); // elimina parámetros extra

        return $videoId ? "https://www.youtube.com/embed/{$videoId}" : null;
    }
}
