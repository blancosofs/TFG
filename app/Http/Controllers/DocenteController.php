<?php
namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DocenteController extends Controller
{
    // 1. INDEX: Listar docentes con su información de usuario
    public function index()
    {
        // Usamos 'with' para traer los datos del usuario de golpe (si tienes la relación hecha)
        $docentes = Docente::with('user')->get(); 
        return view('docentes.index', compact('docentes'));  //<--- Crea un array a partir de variables que ya existen.
    }

    // 2. CREATE: Formulario para nuevo docente
    public function create()
    {
        return view('docentes.create');
    }

    // 3. STORE: ¡Aquí está la magia de la contraseña!
    public function store(Request $request)
    {
        // Validamos datos de ambas tablas
        $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8',
            'telefono' => 'required'
        ]);

        // Usamos una Transacción por seguridad: o se crean los dos, o ninguno.
        DB::transaction(function () use ($request) {
            // A. Creamos el Usuario (donde va la contraseña)
            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make($request->password), // <--- Encriptación
                'colegio_id'=> $request->colegio_id,
                'activo'    => true,
            ]);

            // B. Creamos el Docente usando el ID del usuario recién creado
            Docente::create([
                'telefono'       => $request->telefono,
                'colegio_id'     => $request->colegio_id,
                'coordinador_id' => $request->coordinador_id,
                'user_id'        => $user->id, // <--- El puente entre ambos
            ]);
        });

        return redirect()->route('docentes.index')->with('success', 'Docente creado con éxito');
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