<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;

class VerificarRolAdmin
{
    public function handle(Request $request, Closure $next, string $rol)
    {
        $usuario = Auth::guard('sanctum')->user();

        if (!$usuario) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        if ($usuario->rol !== $rol) {
            return response()->json(['error' => 'No tienes permiso para acceder a esta página'], 403);
        }

        return $next($request);
    }
}
