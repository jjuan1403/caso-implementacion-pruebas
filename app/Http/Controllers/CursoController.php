<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;

class CursoController extends Controller
{
   
    public function index()
    {
        // Eager loading para traer también los grupos de cada curso
        $cursos = Curso::with('grupos')->get();

        return response()->json($cursos);
    }

   
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sigla' => 'required|string|max:10|unique:cursos,sigla',
            'nombreCurso' => 'required|string|max:255',
            'creditos' => 'required|integer|min:1|max:10',
        ]);

        $curso = Curso::create($validated);

        return response()->json([
            'mensaje' => 'Curso creado correctamente',
            'curso' => $curso
        ], 201);
    }

    
    public function show($sigla)
    {
        $curso = Curso::with('grupos')->where('sigla', $sigla)->firstOrFail();

        return response()->json($curso);
    }

   
    public function update(Request $request, $sigla)
    {
        $curso = Curso::where('sigla', $sigla)->firstOrFail();

        $validated = $request->validate([
            'nombreCurso' => 'sometimes|required|string|max:255',
            'creditos' => 'sometimes|required|integer|min:1|max:10',
        ]);

        $curso->update($validated);

        return response()->json([
            'mensaje' => 'Curso actualizado correctamente',
            'curso' => $curso
        ]);
    }

    public function destroy($sigla)
    {
        $curso = Curso::where('sigla', $sigla)->firstOrFail();
        $curso->delete();

        return response()->json([
            'mensaje' => 'Curso eliminado correctamente'
        ]);
    }
}