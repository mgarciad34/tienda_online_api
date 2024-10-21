<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class VerificarRolAdmin
{
    public function handle($request, Closure $next)
    {
/*        if (!Auth::check()) {
            return redirect('/login');
        }*/

        $usuario = Auth::user();

        if ($usuario->rol === 'Administrador') {
            return $next($request);
        }

        abort(403, 'No tienes permiso para acceder como administrador.');
    }
}
