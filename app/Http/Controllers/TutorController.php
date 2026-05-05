<?php

namespace App\Http\Controllers;

use App\Models\Tutor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class TutorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Identificamos el colegio del coordinador
        $colegioId = Auth::user()->colegio_id;

        // 2. Buscamos a los tutores usando "whereHas" para filtrar por la tabla users
        $tutores = Tutor::whereHas('user', function ($query) use ($colegioId) {
                            $query->where('colegio_id', $colegioId);
                        })
                        ->with(['user', 'alumnos']) // ¡Traemos los datos del usuario y de sus hijos!
                        ->get();

        // 3. Devolvemos el JSON al Frontend
        return response()->json($tutores);
    }

    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tutores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validamos los datos, incluyendo el alumno y el parentesco
        $request->validate([
            'nombre'     => 'required|string',
            'apellidos'  => 'required|string',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|min:8',
            'telefono'   => 'required',
            'alumno_id'  => 'nullable|integer|exists:alumnos,id', // <--- El niño seleccionado
            'parentesco' => 'nullable|string'                     // <--- Qué es del niño
        ]);

        // Usamos DB::transaction para que, si algo falla, no se guarde a medias
        DB::transaction(function () use ($request) {
            
            // A. Creamos el Usuario
            $user = User::create([
                'name'      => $request->nombre,
                'apellidos' => $request->apellidos,
                'email'     => $request->email,
                'password'  => Hash::make($request->password), 
                'colegio_id'=> Auth::user()->colegio_id,
                'activo'    => true,
            ]);

            // B. Creamos el Tutor
            $tutor = Tutor::create([
                'telefono'  => $request->telefono,
                'user_id'   => $user->id, 
            ]);

            // C. Si nos enviaron un alumno, los vinculamos en la tabla intermedia
            if ($request->filled('alumno_id')) {
                $parentescoReal = $request->parentesco ?? 'Tutor legal';
                $tutor->alumnos()->attach($request->alumno_id, ['parentesco' => $parentescoReal]);
            }
        });

        return response()->json([
            'ok' => true, 
            'mensaje' => 'Tutor creado y vinculado con éxito'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tutor $tutor)
    {
        return view('tutores.show', compact('tutor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tutor $tutor)
    {
        return view('tutores.edit', compact('tutor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $tutor = Tutor::findOrFail($id);

        $request->validate([
            'nombre'     => 'required|string|max:25',
            'apellidos'  => 'required|string|max:60',
            'email'      => 'required|email|unique:users,email,' . $tutor->user_id,
            'telefono'   => 'required|string',
            'alumno_id'  => 'nullable|integer|exists:alumnos,id',
            'parentesco' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($request, $tutor) {
                $tutor->user->update([
                    'name'      => $request->nombre,
                    'apellidos' => $request->apellidos,
                    'email'     => $request->email,
                ]);

                $tutor->update(['telefono' => $request->telefono]);

                if ($request->filled('password')) {
                    $tutor->user->password = Hash::make($request->password);
                    $tutor->user->save();
                }

                if ($request->has('alumno_id')) {
                    if ($request->filled('alumno_id')) {
                        $tutor->alumnos()->sync([
                            $request->alumno_id => ['parentesco' => $request->parentesco ?? 'tutor_legal']
                        ]);
                    } else {
                        $tutor->alumnos()->detach();
                    }
                }
            });

            return response()->json(['ok' => true, 'mensaje' => 'Tutor actualizado correctamente']);

        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'mensaje' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tutor = Tutor::findOrFail($id);

        try {
            DB::transaction(function () use ($tutor) {
                $tutor->alumnos()->detach();
                $tutor->user->delete();
            });

            return response()->json(['ok' => true, 'mensaje' => 'Tutor eliminado correctamente']);

        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'mensaje' => $e->getMessage()], 500);
        }
    }
}
