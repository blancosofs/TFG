<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->preventRequestForgery(except: [
            '/api/*'
        ]);

        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // Si el usuario ya está autenticado e intenta ir al login, redirigirlo a su panel
        $middleware->redirectUsersTo(function () {
            $user = auth()->user();
            if (!$user)                      return '/';
            if (is_null($user->colegio_id)) return route('admin');
            if ($user->coordinador)          return route('coordinador');
            if ($user->docente)              return route('calendario');
            if ($user->tutor)               return route('perfilFamilia');
            return '/';
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

    
