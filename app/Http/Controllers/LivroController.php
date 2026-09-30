<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use Illuminate\Http\Request;

class LivroController extends Controller
{
    public function index()
    {
        return Livro::with(['autor', 'categoria'])->get();
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'titulo' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:livros,isbn',
            'ano_publicacao' => 'nullable|integer',
            'paginas' => 'nullable|integer|min:1',
            'autor_id' => 'required|exists:autores,id',
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        $livro = Livro::create($dados);

        return response()->json($livro->load(['autor', 'categoria']), 201);
    }

    public function show(Livro $livro)
    {
        return $livro->load(['autor', 'categoria']);
    }

    public function update(Request $request, Livro $livro)
    {
        $dados = $request->validate([
            'titulo' => 'sometimes|required|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:livros,isbn,' . $livro->id,
            'ano_publicacao' => 'nullable|integer',
            'paginas' => 'nullable|integer|min:1',
            'autor_id' => 'sometimes|required|exists:autores,id',
            'categoria_id' => 'sometimes|required|exists:categorias,id',
        ]);

        $livro->update($dados);

        return $livro->load(['autor', 'categoria']);
    }

    public function destroy(Livro $livro)
    {
        $livro->delete();

        return response()->noContent();
    }
}
