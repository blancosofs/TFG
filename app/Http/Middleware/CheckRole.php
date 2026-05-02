<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Si no está logueado, a la calle
        if (!Auth::check()) {
            return redirect('/login.html'); // Usamos el .html que puso tu compañero
        }

        $user = Auth::user();

        // 2. Comprobamos permisos
        if ($role === 'admin' && is_null($user->colegio_id)) return $next($request);
        if ($role === 'coordinador' && $user->coordinador) return $next($request);
        if ($role === 'docente' && $user->docente) return $next($request);
        if ($role === 'tutor' && $user->tutor) return $next($request);

        // 3. Intento de acceso no autorizado
        abort(403, 'No tienes permiso para ver esta pantalla.');
    }
}