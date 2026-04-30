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
        // 1. Intenta autenticar. Si falla, Laravel te devuelve solo al login con error.
        $request->authenticate();

        // 2. Si llega aquí, es que los datos son correctos. Generamos sesión.
        $request->session()->regenerate();

        $user = Auth::user();

        // 3. Lógica de redirección por EMAIL o ROL
        if ($user->email === 'admin@demo.com') {
            return redirect()->intended(route('admin')); // Va a /admin-panel
        }

        if ($user->colegio_id === null) {
            // Si es un usuario sin colegio (como tu super admin original)
            return redirect()->intended(route('admin'));
        }

        // Por defecto para los demás (Docentes, Familias...)
        return redirect()->intended(route('perfil'));
        //configurate esto mas adelante
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
