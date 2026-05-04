<?php
namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DocenteController extends Controller
{
    // 1. INDEX: Listar docentes con su información de usuario
    public function index()
    {
        // 1. Buscamos solo los docentes del colegio del coordinador
        $colegioId = Auth::user()->colegio_id;
        
        $docentes = Docente::where('colegio_id', $colegioId)
                            ->with('user') // Traemos también sus datos de nombre/email
                            ->get();
        // 2. Devolvemos el JSON
        return response()->json($docentes);
    }

    
    // 2. STORE
    public function store(Request $request)
    {
        // 1. Validamos los datos que llegan desde el JS de tu compañero
        $request->validate([
            'nombre'    => 'required|string',
            'apellidos' => 'required|string',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:8',
            'telefono'  => 'required',
            'asignaturas' => 'nullable|string'
        ]);

        try {
        // 2. Transacción: O se crean los dos, o ninguno
        DB::transaction(function () use ($request) {
            
            // A. Creamos el Usuario
            $user = User::create([
                'name'       => $request->nombre,
                'apellidos'  => $request->apellidos,
                'email'      => $request->email,
                'password'   => Hash::make($request->password), // Encriptamos la clave
                'activo'     => true,
            ]);

            // B. Creamos el perfil de Docente
            Docente::create([
                'telefono'       => $request->telefono,
                'colegio_id'     => Auth::user()->colegio_id, //Ponemos el mismo colegio que el coordinador que lo crea
                'coordinador_id' => Auth::user()->coordinador->id ?? null, //Si el coordinador no tiene coordinador_id (por ser admin), ponemos null
                'asignaturas'    => $request->asignaturas,
                'user_id'        => $user->id,
            ]);
        });

        // 3. Le respondemos al Frontend con un JSON
        return response()->json([
            'ok' => true,
            'mensaje' => 'Docente creado y vinculado con éxito.'
        ]);

        } catch (\Exception $e) {
            // Si hay cualquier error (ej. se cae la base de datos), devolvemos el error en JSON
            return response()->json([
                'ok' => false,
                'mensaje' => 'Error al crear el docente: ' . $e->getMessage()
            ], 500);
        }
    }


    // 3. UPDATE: Actualizar datos (y contraseña si se desea)
    public function update(Request $request, int $id)
    {
        // Buscamos el docente
        $docente = Docente::findOrFail($id);

        $user = $docente->user; // Accedemos al usuario vinculado

    $request->validate([
        'nombre'  => 'required|string|max:25',
        'apellidos' => 'required|string|max:60',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'telefono' => 'nullable|string',
        'asignaturas' => 'nullable|string',
    ]);

    try {
            DB::transaction(function () use ($request, $user, $docente) {
                // A. Actualizamos la tabla Users
                $user->update([
                    'name'  => $request->nombre,
                    'apellidos' => $request->apellidos,
                    'email' => $request->email,
                ]);

                // B. Actualizamos la tabla Docentes
                $docente->update([
                    'telefono'    => $request->telefono,
                    'asignaturas' => $request->asignaturas,
                ]);

                if ($request->filled('password')) {
                    $user->password = Hash::make($request->password);
                }
                $user->save();
            });

            return response()->json(['ok' => true, 'mensaje' => 'Docente actualizado correctamente']);

        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'mensaje' => $e->getMessage()], 500);
        }
    }

    // 7. DESTROY: Borrar docente (y su usuario)
    public function destroy(int $id)
    {
        $docente = Docente::findOrFail($id);
        $user = $docente->user;

    try {
        DB::transaction(function () use ($docente, $user) {
            $docente->user->delete();
        });

        return response()->json(['ok' => true, 'mensaje' => 'Docente eliminado por completo']);

        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'mensaje' => 'No se pudo eliminar: ' . $e->getMessage()], 500);
        }
    }
}

?>