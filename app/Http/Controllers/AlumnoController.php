<?php
namespace App\Http\Controllers;

use App\Models\Alumno;
use Illuminate\Http\Request;

class AlumnoController extends Controller
{
    // Listar todos los alumnos
    public function index()
    {
        $alumnos = Alumno::all();
        return view('alumnos.index', compact('alumnos'));

        //Va a MySQL, coge todos los alumnos y se los pasa a una vista.
    }

    // Mostrar formulario para crear uno nuevo
    public function create()
    {
        return view('alumnos.create');
        
        //Muestra el archivo HTML del formulario de registro.
    }

    // Guardar el alumno en la base de datos
    public function store(Request $request)
    {
        // Validamos solo los campos de texto y IDs
        $request->validate([
            'nombre'     => 'required|string|max:25',
            'apellidos'    => 'required|string|max:60',
            'colegio_id' => 'required|integer|exists:colegios,id',
            'curso_id' => 'required|integer|exists:cursos,id',
            'clase_id' => 'required|integer|exists:clases,id',
            'activo' => 'required|boolean'

        // Laravel usará el $fillable que definiste para filtrar
        Alumno::create($request->all()); 

        return redirect("/")->route('alumnos.index')->with('info', 'Alumno registrado con éxito');

        //Recibe los datos que el usuario escribió en create(), los valida y los mete en la base de datos.
    }

    // Mostrar un alumno específico
    public function show(Alumno $alumno)
    {
        return view('alumnos.show', compact('alumno'));
        //Muestra la información de un solo alumno específico.
    }

    // Formulario para editar
    public function edit(Alumno $alumno)
    {
        return view('alumnos.edit', compact('alumno'));
        /*Le pasa los datos del alumno que quieres cambiar, el usuario ya 
        ve escrito el nombre actual del alumno en los cuadros de texto para poder corregirlos.*/
    }

    // Actualizar los datos
    public function update(Request $request, Alumno $alumno)
    {
        $alumno->update($request->all());
        return redirect()->route('alumnos.index')->with('info', 'Datos del alumno actualizados');
        //Recibe los nuevos datos del formulario de edit() y sobrescribe los antiguos en MySQL.
    }

    // Eliminar al alumno
    public function destroy(Alumno $alumno)
    {
        $alumno->delete();
        return redirect()->route('alumnos.index')->with('info', 'Alumno eliminado');
        //Busca al alumno por su ID y lo elimina de la tabla de MySQL.
    }
}

/* Ruta web
use App\Http\Controllers\AlumnoController;
Route::resource('alumnos', AlumnoController::class);
*/

?>

