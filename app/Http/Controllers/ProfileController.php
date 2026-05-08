<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function me()
    {
        $user = User::findOrFail(Auth::id());

        $rol = 'sin_rol';
        if (is_null($user->colegio_id))  $rol = 'admin';
        elseif ($user->coordinador)      $rol = 'coordinador';
        elseif ($user->docente)          $rol = 'docente';
        elseif ($user->tutor)            $rol = 'tutor';

        $colegio  = $user->colegio_id ? \App\Models\Colegio::find($user->colegio_id)?->nombre : null;
        $telefono = $user->docente?->telefono ?? $user->tutor?->telefono ?? null;

        return response()->json([
            'id'         => $user->id,
            'nombre'     => $user->name,
            'apellidos'  => $user->apellidos,
            'email'      => $user->email,
            'colegio_id' => $user->colegio_id,
            'colegio'    => $colegio,
            'telefono'   => $telefono,
            'rol'        => $rol,
        ]);
    }

    public function actualizarDatos(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:25',
            'apellidos' => 'required|string|max:60',
            'telefono'  => 'nullable|string|max:20',
        ]);

        $user = User::findOrFail(Auth::id());
        $user->update(['name' => $request->nombre, 'apellidos' => $request->apellidos]);

        if ($user->tutor) {
            $user->tutor->update(['telefono' => $request->telefono]);
        } elseif ($user->docente) {
            $user->docente->update(['telefono' => $request->telefono]);
        }

        return response()->json(['ok' => true]);
    }

    public function actualizarPassword(Request $request)
    {
        $request->validate([
            'passwordActual' => 'required',
            'passwordNueva'  => 'required|min:8',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->passwordActual, $user->password)) {
            return response()->json(['error' => 'La contraseña actual no es correcta.'], 422);
        }

        $user->update(['password' => Hash::make($request->passwordNueva)]);

        return response()->json(['ok' => true]);
    }
}
