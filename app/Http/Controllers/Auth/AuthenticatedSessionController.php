<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

   /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Autentica al usuario
        $request->authenticate();
        $request->session()->regenerate();
        
        $user = Auth::user();

        // 2. Forzamos la redirección a su panel principal

        if (is_null($user->colegio_id)) {
            return redirect()->route('admin'); 
        }

        // Si es Coordinador
        if ($user->coordinador) {
            return redirect()->route('coordinador'); 
        }

        // Si es Docente
        if ($user->docente) {
            return redirect()->route('calendario'); 
        }

        // Si es Tutor/Familiar
        if ($user->tutor) {
            return redirect()->route('perfilFamilia'); 
        }

        // Por si acaso hubiera un usuario sin rol, lo mandamos al inicio
        return redirect('/');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
