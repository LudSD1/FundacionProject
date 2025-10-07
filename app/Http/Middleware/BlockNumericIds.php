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
        $parameters = $request->route()->parameters();

        foreach ($parameters as $key => $value) {
            // Bloquear parámetros que sean numéricos (IDs numerales)
            if (is_numeric($value)) {
                throw new NotFoundHttpException('Acceso no permitido con parámetros numerales.');
            }
        }

        return $next($request);
    }
}
