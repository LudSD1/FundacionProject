<?php

namespace App\Helpers;

class TextHelper
{
    public static function createClickableLinksAndPreviews($text)
    {
           // Convertir URLs de YouTube a iframes
    $text = preg_replace(
        '/https:\/\/www\.youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/',
        '<iframe width="560" height="315" src="https://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>',
        $text
    );

    // Convertir URLs de otros servicios a iframes si es necesario
    // $text = preg_replace('/https:\/\/vimeo\.com\/([0-9]+)/', '<iframe src="https://player.vimeo.com/video/$1" width="640" height="360" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>', $text);

    // Convertir otros enlaces en enlaces clicables
    $text = preg_replace_callback(
        '/(https?:\/\/[^\s]+)/',
        function ($matches) {
            $url = $matches[0];
            // Verificar si el enlace ya fue convertido a iframe para evitar duplicados
            if (strpos($url, 'youtube.com/embed') !== false) {
                return $url;
            }
            return '<a href="' . $url . '" target="_blank" rel="noopener noreferrer">' . $url . '</a>';
        },
        $text
    );

    return $text;
    }









}
