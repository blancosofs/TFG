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

    // 2. CREATE: Formulario para nuevo docente
    public function create()
    {
        return view('docentes.create');
    }

    
    // 3. STORE
    public function store(Request $request)
    {
        // 1. Validamos los datos que llegan desde el JS de tu compañero
        $request->validate([
            'nombre'    => 'required|string',
            'apellidos' => 'required|string',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:8',
            'telefono'  => 'required'
        ]);

        // 2. Transacción: O se crean los dos, o ninguno
        DB::transaction(function () use ($request) {
            
            // A. Creamos el Usuario
            $user = User::create([
                'name'       => $request->nombre,
                'apellidos'  => $request->apellidos,
                'email'      => $request->email,
                'password'   => Hash::make($request->password), // Encriptamos la clave
                'colegio_id' => Auth::user()->colegio_id, //Lo asigna al colegio de este Coordinador
                'activo'     => true,
            ]);

            // B. Creamos el perfil de Docente
            Docente::create([
                'telefono'       => $request->telefono,
                'colegio_id'     => Auth::user()->colegio_id, //Ponemos el mismo colegio que el coordinador que lo crea
                'coordinador_id' => Auth::user()->coordinador->id, // Lo vinculamos al coordinador actual
                'user_id'        => $user->id,
            ]);
        });

        // 3. Le respondemos al Frontend con un JSON
        return response()->json([
            'ok' => true,
            'mensaje' => 'Docente creado con éxito en la Base de Datos'
        ]);
    }

    // 4. SHOW: Ver perfil del docente y su usuario
    public function show(Docente $docente)
    {
        return view('docentes.show', compact('docente'));
    }

    // 5. EDIT: Formulario con datos actuales
    public function edit(Docente $docente)
    {
        return view('docentes.edit', compact('docente'));
    }

    // 6. UPDATE: Actualizar datos (y contraseña si se desea)
    public function update(Request $request, Docente $docente)
    {
        // Actualizamos datos del docente
        $docente->update($request->only('telefono', 'coordinador_id'));

        // Actualizamos datos del usuario vinculado
        $userData = $request->only('name', 'email');
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        $docente->user->update($userData);

        return redirect()->route('docentes.index')->with('success', 'Docente actualizado');
    }

    // 7. DESTROY: Borrar docente (y su usuario)
    public function destroy(Docente $docente)
    {
        $user = $docente->user;
        $docente->delete(); // Borra el registro en 'docentes'
        $user->delete();    // Borra el registro en 'users'
        
        return redirect()->route('docentes.index');
    }
}

?>