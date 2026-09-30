<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use Illuminate\Http\Request;

class AutorController extends Controller
{
    public function index()
    {
        return Autor::all();
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:255',
            'nacionalidade' => 'nullable|string|max:255',
            'data_nascimento' => 'nullable|date',
        ]);

        $autor = Autor::create($dados);

        return response()->json($autor, 201);
    }

    public function show(Autor $autor)
    {
        return $autor->load('livros');
    }

    public function update(Request $request, Autor $autor)
    {
        $dados = $request->validate([
            'nome' => 'sometimes|required|string|max:255',
            'nacionalidade' => 'nullable|string|max:255',
            'data_nascimento' => 'nullable|date',
        ]);

        $autor->update($dados);

        return $autor;
    }

    public function destroy(Autor $autor)
    {
        $autor->delete();

        return response()->noContent();
    }
}
