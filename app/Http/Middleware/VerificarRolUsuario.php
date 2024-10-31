<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class VerificarRolUsuario
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'No estás autenticado'], 401);
        }

        $usuario = Auth::user();

        if ($usuario->rol !== 'usuario') {
            return response()->json(['error' => 'Solo los usuarios tienen acceso'], 403);
        }

        return $next($request);
    }
}
