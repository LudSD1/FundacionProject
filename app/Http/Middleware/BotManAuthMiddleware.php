<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BotManAuthMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!Auth  ::check()) {
            return response('Unauthorized', 401);
        }

        return $next($request);
    }
}
