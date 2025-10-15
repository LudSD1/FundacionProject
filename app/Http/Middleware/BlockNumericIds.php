<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlockNumericIds
{
    /**
     * Manejar una solicitud entrante
     */
    public function handle(Request $request, Closure $next)
    {
        $parameters = $request->route()?->parameters() ?? [];

        foreach ($parameters as $key => $value) {
            // Asegurarse de que sea una cadena o número simple
            if (is_scalar($value)) {
                // Solo bloquear si es un número entero puro (ej: "123", 456)
                if (preg_match('/^\d+$/', (string) $value)) {
                    throw new NotFoundHttpException('Acceso no permitido con IDs numéricos.');
                }
            }
        }

        return $next($request);
    }
}
