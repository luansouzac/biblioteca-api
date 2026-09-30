<?php

namespace Database\Seeders;

use App\Models\Autor;
use Illuminate\Database\Seeder;

class AutorSeeder extends Seeder
{
    public function run(): void
    {
        Autor::create(['nome' => 'Machado de Assis', 'nacionalidade' => 'Brasileira', 'data_nascimento' => '1839-06-21']);
        Autor::create(['nome' => 'Clarice Lispector', 'nacionalidade' => 'Brasileira', 'data_nascimento' => '1920-12-10']);
        Autor::create(['nome' => 'Jorge Amado', 'nacionalidade' => 'Brasileira', 'data_nascimento' => '1912-08-10']);
        Autor::create(['nome' => 'J. R. R. Tolkien', 'nacionalidade' => 'Britânica', 'data_nascimento' => '1892-01-03']);
        Autor::create(['nome' => 'George Orwell', 'nacionalidade' => 'Britânica', 'data_nascimento' => '1903-06-25']);
    }
}
