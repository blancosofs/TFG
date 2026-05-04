<?php
namespace App\Http\Controllers;

use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlumnoController extends Controller
{
    // Listar todos los alumnos
    public function index()
    {
        // 1. Buscamos el colegio del coordinador logueado
        $colegioId = Auth::user()->colegio_id;

        // 2. Buscamos TODOS los alumnos de ese colegio, 
        // e incluimos ("with") la información de su clase y curso para poder pintarla
        $alumnos = Alumno::where('colegio_id', $colegioId)
                        ->with(['clase', 'curso']) 
                        ->get();

        // 3. Se lo escupimos al JavaScript de tu compañero en formato JSON
        return response()->json($alumnos);
    }


    // Guardar el alumno en la base de datos
    public function store(Request $request)
{
    // 1. Validamos los datos (añadimos tutor_id)
    $request->validate([
        'nombre'           => 'required|string|max:25',
        'apellidos'        => 'required|string|max:60',
        'fecha_nacimiento' => 'required|date',
        'curso_id'         => 'required|integer|exists:cursos,id',
        'clase_id'         => 'required|integer|exists:clases,id',
        'tutor_id'         => 'nullable|integer|exists:tutores,id', // <--- Permitimos que llegue el tutor
        'parentesco'       => 'nullable|string'
    ]);

   // OJO AQUÍ: Excluimos tanto 'tutor_id' como 'parentesco' para que 
    // Laravel no intente guardarlos en la tabla 'alumnos' y nos dé error SQL
    $data = $request->except(['tutor_id', 'parentesco']);

    $data['colegio_id'] = Auth::user()->colegio_id; // El colegio del coordinador
    $data['activo']     = true; // Por defecto activo

    // 4. Creamos el alumno
    $alumno = Alumno::create($data);

    // Si nos enviaron un tutor, los vinculamos
    if ($request->filled('tutor_id')) {
        
        $parentescoReal = $request->parentesco ?? 'Tutor legal';
        $alumno->tutores()->attach($request->tutor_id, [
            'parentesco' => $parentescoReal
        ]);
    }

        return response()->json([
            'ok' => true,
            'mensaje' => 'Alumno creado con éxito'
        ]);
    }   

    // Mostrar un alumno específico ---- Ver si se usa

    /*public function show(Alumno $alumno)
    {
        return view('alumnos.show', compact('alumno'));
        //Muestra la información de un solo alumno específico.
    }*/


    // Actualizar los datos
    public function update(Request $request, int $id)
    {
    // 1. Buscamos al alumno
    $alumno = Alumno::findOrFail($id);

    // 2. Validamos (Asegúrate de que los nombres aquí coincidan con el payload de JS)
    $request->validate([
        'nombre'           => 'required|string|max:25',
        'apellidos'        => 'required|string|max:60',
        'fecha_nacimiento' => 'required|date',
        'curso_id'         => 'required|integer',
        'clase_id'         => 'required|integer',
    ]);

    // 3. ACTUALIZACIÓN MANUAL (A prueba de fallos)
    $alumno->nombre           = $request->nombre;
    $alumno->apellidos        = $request->apellidos;
    $alumno->fecha_nacimiento = $request->fecha_nacimiento;
    $alumno->curso_id         = $request->curso_id;
    $alumno->clase_id         = $request->clase_id;
    
    // Guardamos a la fuerza
    $alumno->save(); 

    // 4. Sincronizamos el tutor si existe
    if ($request->has('tutor_id')) {
    if (!empty($request->tutor_id)) {
        $alumno->tutores()->sync([
            $request->tutor_id => ['parentesco' => $request->parentesco]
        ]);
    } else {
        // Si el usuario seleccionó "Sin tutor asignado", vaciamos la relación
        $alumno->tutores()->detach();
    }
}

    return response()->json([
        'ok' => true, 
        'mensaje' => '¡Guardado real en BD!',
        'datos_recibidos' => $request->all() // Esto es para que veas en la consola qué le llegó a Laravel
    ]);
    }

    // Eliminar al alumno
    public function destroy(int $id)
    {
    // Buscamos al alumno (asegurándonos de que sea de nuestro colegio)
    $alumno = Alumno::where('colegio_id', Auth::user()->colegio_id)->findOrFail($id);

    // Borramos cualquier rastro suyo en la tabla tutor_alumno
    // Esto NO borra al tutor, solo borra el "hilo" que los une.
    $alumno->tutores()->detach();

    // Borramos (esto también elimina automáticamente la relación en la tabla pivote si usaste onCascade)
    $alumno->delete();

    return response()->json(['ok' => true, 'mensaje' => 'Alumno eliminado correctamente']);
    }
}

?>

