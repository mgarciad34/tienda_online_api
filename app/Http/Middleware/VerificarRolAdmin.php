<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class VerificarRolAdmin
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'No estás autenticado'], 401);
        }

        $usuario = Auth::user();

        if ($usuario->rol !== 'administrador') {
            return response()->json(['error' => 'Solo los administradores tienen acceso'], 403);
        }

        return $next($request);
    }
}
