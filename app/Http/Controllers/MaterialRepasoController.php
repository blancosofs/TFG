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
    /* ═══════════════════════════════════════════════════════════
       VISTAS SHELL
       Devuelven la página vacía. El JS carga los datos via API.
    ═══════════════════════════════════════════════════════════ */

    // Página principal: lista de materiales del docente (shell sin datos)
    public function index()
    {
        return view('material-repaso.index');
    }

    // Página de creación (shell sin datos; el JS carga la lista de tutores)
    public function create()
    {
        return view('material-repaso.create');
    }

    // Página de detalle: solo pasa el ID al JS para que pida los datos
    public function show(MaterialRepaso $materialRepaso)
    {
        $docente = Auth::user()->docente;
        if ($materialRepaso->docente_id !== $docente->id) abort(403);
        return view('material-repaso.show', ['id' => $materialRepaso->id]);
    }

    // Página de edición: solo pasa el ID al JS para que pida los datos
    public function edit(MaterialRepaso $materialRepaso)
    {
        $docente = Auth::user()->docente;
        if ($materialRepaso->docente_id !== $docente->id) abort(403);
        return view('material-repaso.edit', ['id' => $materialRepaso->id]);
    }

    /* ═══════════════════════════════════════════════════════════
       RUTAS JSON — LECTURA
       Llamadas desde JS con fetch. Devuelven JSON.
    ═══════════════════════════════════════════════════════════ */

    // Devuelve la lista paginada de materiales del docente logueado
    public function listar()
    {
        $docente    = Auth::user()->docente;
        $materiales = MaterialRepaso::porDocente($docente->id)
            ->with('tutores.user')
            ->ordenadasPorFecha()
            ->paginate(15);

        return response()->json([
            'data' => $materiales->map(fn($m) => $this->formato($m))->values(),
            'meta' => [
                'current_page' => $materiales->currentPage(),
                'last_page'    => $materiales->lastPage(),
                'total'        => $materiales->total(),
            ],
        ]);
    }

    // Devuelve la lista de tutores del mismo colegio (para los checkboxes del formulario)
    public function tutoresDelColegio()
    {
        $docente = Auth::user()->docente;
        $tutores = Tutor::whereHas('user', fn($q) => $q->where('colegio_id', $docente->colegio_id)->where('activo', true))
            ->with('user')
            ->get()
            ->map(fn($t) => [
                'id'     => $t->id,
                'nombre' => $t->user->name . ' ' . $t->user->apellidos,
            ]);

        return response()->json($tutores);
    }

    // Devuelve el detalle completo de un material (incluye tutores asignados)
    public function detallar(MaterialRepaso $materialRepaso)
    {
        $docente = Auth::user()->docente;
        if ($materialRepaso->docente_id !== $docente->id) abort(403);
        $materialRepaso->load('tutores.user');
        return response()->json($this->formato($materialRepaso));
    }

    /* ═══════════════════════════════════════════════════════════
       RUTAS JSON — ESCRITURA
       Llamadas desde JS con fetch. Devuelven JSON en vez de redirect.
    ═══════════════════════════════════════════════════════════ */

    // Crea un nuevo material. Acepta multipart/form-data para subir archivos.
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
            $file        = $request->file('archivo');
            $nombreUnico = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $ruta        = $file->storeAs("materiales/{$docente->id}", $nombreUnico, 'private');
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
            'publicado'      => $request->boolean('publicado'),
        ]));

        if ($request->filled('tutores')) {
            $material->tutores()->sync($request->tutores);
        }

        return response()->json(['ok' => true, 'id' => $material->id], 201);
    }

    // Actualiza los metadatos de un material existente (no permite cambiar el archivo)
    public function update(Request $request, MaterialRepaso $materialRepaso)
    {
        $docente = Auth::user()->docente;
        if ($materialRepaso->docente_id !== $docente->id) abort(403);

        $request->validate([
            'titulo'      => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'url_externa' => 'nullable|url|max:500',
            'materia'     => 'nullable|string|max:100',
            'tema'        => 'nullable|string|max:150',
            'publicado'   => 'nullable|boolean',
            'tutores'     => 'nullable|array',
            'tutores.*'   => 'exists:tutores,id',
        ]);

        $materialRepaso->update([
            'titulo'      => $request->titulo,
            'descripcion' => $request->descripcion,
            'url_externa' => $request->url_externa,
            'materia'     => $request->materia,
            'tema'        => $request->tema,
            'publicado'   => $request->boolean('publicado'),
        ]);

        // sync reemplaza los tutores asignados con los nuevos seleccionados
        $materialRepaso->tutores()->sync($request->tutores ?? []);

        return response()->json(['ok' => true]);
    }

    // Elimina un material y su archivo físico del disco privado
    public function destroy(MaterialRepaso $materialRepaso)
    {
        $docente = Auth::user()->docente;
        if ($materialRepaso->docente_id !== $docente->id) abort(403);

        if ($materialRepaso->archivo_ruta) {
            Storage::disk('private')->delete($materialRepaso->archivo_ruta);
        }

        $materialRepaso->delete();
        return response()->json(['ok' => true]);
    }

    /* ═══════════════════════════════════════════════════════════
       HELPER PRIVADO
    ═══════════════════════════════════════════════════════════ */

    // Serializa un MaterialRepaso a array limpio para el JSON de respuesta
    private function formato(MaterialRepaso $m): array
    {
        return [
            'id'                      => $m->id,
            'titulo'                  => $m->titulo,
            'descripcion'             => $m->descripcion,
            'tipo_contenido'          => $m->tipo_contenido,
            'archivo_nombre_original' => $m->archivo_nombre_original,
            'tamano_legible'          => $m->tamañoLegible,
            'url_externa'             => $m->url_externa,
            'materia'                 => $m->materia,
            'tema'                    => $m->tema,
            'publicado'               => $m->publicado,
            'created_at'              => $m->created_at->format('d/m/Y'),
            'tutores'                 => $m->tutores->map(fn($t) => [
                'id'     => $t->id,
                'nombre' => $t->user->name . ' ' . $t->user->apellidos,
            ])->values()->toArray(),
        ];
    }
}
