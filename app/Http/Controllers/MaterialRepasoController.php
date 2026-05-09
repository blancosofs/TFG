<?php

namespace App\Http\Controllers;

use App\Models\MaterialRepaso;
use App\Models\Tutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MaterialRepasoController extends Controller
{
    public function index()
    {
        $docente = Auth::user()->docente;
        $materiales = MaterialRepaso::porDocente($docente->id)
            ->with('tutores')
            ->ordenadasPorFecha()
            ->paginate(15);

        return view('material-repaso.index', compact('materiales'));
    }

    public function create()
    {
        $docente = Auth::user()->docente;
        $tutores = Tutor::whereHas('user', fn($q) => $q->where('colegio_id', $docente->colegio_id)->where('activo', true))
            ->with('user')->get();
        return view('material-repaso.create', compact('tutores'));
    }

    public function store(Request $request)
    {
        $docente = Auth::user()->docente;

        $request->validate([
            'titulo'         => 'required|string|max:255',
            'descripcion'    => 'nullable|string|max:1000',
            'tipo_contenido' => 'required|in:archivo,url_externa',
            'archivo'        => 'nullable|required_if:tipo_contenido,archivo|file|max:51200|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,mp4,zip',
            'url_externa'    => 'nullable|required_if:tipo_contenido,url_externa|url|max:500',
            'materia'        => 'nullable|string|max:100',
            'tema'           => 'nullable|string|max:150',
            'publicado'      => 'nullable|boolean',
            'tutores'        => 'nullable|array',
            'tutores.*'      => 'exists:tutores,id',
        ]);

        $archivoData = [];
        if ($request->hasFile('archivo')) {
            $file = $request->file('archivo');
            $nombreUnico = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $ruta = $file->storeAs("materiales/{$docente->id}", $nombreUnico, 'private');
            $archivoData = [
                'archivo_nombre_original' => $file->getClientOriginalName(),
                'archivo_ruta'            => $ruta,
                'archivo_tamaño'          => $file->getSize(),
            ];
        }

        $material = MaterialRepaso::create(array_merge($archivoData, [
            'docente_id'     => $docente->id,
            'colegio_id'     => $docente->colegio_id,
            'titulo'         => $request->titulo,
            'descripcion'    => $request->descripcion,
            'tipo_contenido' => $request->tipo_contenido,
            'url_externa'    => $request->url_externa,
            'materia'        => $request->materia,
            'tema'           => $request->tema,
            'publicado'      => $request->boolean('publicado', true),
        ]));

        if ($request->filled('tutores')) {
            $material->tutores()->sync($request->tutores);
        }

        return redirect()->route('material-repaso.index')->with('success', 'Material creado correctamente.');
    }

    public function show(MaterialRepaso $materialRepaso)
    {
        $docente = Auth::user()->docente;
        if ($materialRepaso->docente_id !== $docente->id) abort(403);
        $materialRepaso->load('tutores.user');
        return view('material-repaso.show', ['material' => $materialRepaso]);
    }

    public function edit(MaterialRepaso $materialRepaso)
    {
        $docente = Auth::user()->docente;
        if ($materialRepaso->docente_id !== $docente->id) abort(403);
        $tutores = Tutor::whereHas('user', fn($q) => $q->where('colegio_id', $docente->colegio_id)->where('activo', true))
            ->with('user')->get();
        $materialRepaso->load('tutores');
        return view('material-repaso.edit', ['material' => $materialRepaso, 'tutores' => $tutores]);
    }

    public function update(Request $request, MaterialRepaso $materialRepaso)
    {
        $docente = Auth::user()->docente;
        if ($materialRepaso->docente_id !== $docente->id) abort(403);

        $request->validate([
            'titulo'      => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'materia'     => 'nullable|string|max:100',
            'tema'        => 'nullable|string|max:150',
            'publicado'   => 'nullable|boolean',
            'tutores'     => 'nullable|array',
            'tutores.*'   => 'exists:tutores,id',
        ]);

        $materialRepaso->update([
            'titulo'      => $request->titulo,
            'descripcion' => $request->descripcion,
            'materia'     => $request->materia,
            'tema'        => $request->tema,
            'publicado'   => $request->boolean('publicado', true),
        ]);

        $materialRepaso->tutores()->sync($request->tutores ?? []);

        return redirect()->route('material-repaso.index')->with('success', 'Material actualizado.');
    }

    public function destroy(MaterialRepaso $materialRepaso)
    {
        $docente = Auth::user()->docente;
        if ($materialRepaso->docente_id !== $docente->id) abort(403);

        if ($materialRepaso->archivo_ruta) {
            Storage::disk('private')->delete($materialRepaso->archivo_ruta);
        }

        $materialRepaso->delete();
        return redirect()->route('material-repaso.index')->with('success', 'Material eliminado.');
    }
}
