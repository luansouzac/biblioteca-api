<?php

namespace Database\Seeders;

use App\Models\Autor;
use App\Models\Categoria;
use App\Models\Livro;
use Illuminate\Database\Seeder;

class LivroSeeder extends Seeder
{
    public function run(): void
    {
        $livros = [
            ['Dom Casmurro', 1899, 'Machado de Assis', 'Romance'],
            ['Memórias Póstumas de Brás Cubas', 1881, 'Machado de Assis', 'Romance'],
            ['A Hora da Estrela', 1977, 'Clarice Lispector', 'Romance'],
            ['Capitães da Areia', 1937, 'Jorge Amado', 'Romance'],
            ['O Hobbit', 1937, 'J. R. R. Tolkien', 'Fantasia'],
            ['A Sociedade do Anel', 1954, 'J. R. R. Tolkien', 'Fantasia'],
            ['1984', 1949, 'George Orwell', 'Distopia'],
            ['A Revolução dos Bichos', 1945, 'George Orwell', 'Distopia'],
        ];

        foreach ($livros as [$titulo, $ano, $autor, $categoria]) {
            Livro::create([
                'titulo' => $titulo,
                'ano_publicacao' => $ano,
                'autor_id' => Autor::where('nome', $autor)->value('id'),
                'categoria_id' => Categoria::where('nome', $categoria)->value('id'),
            ]);
        }
    }
}
