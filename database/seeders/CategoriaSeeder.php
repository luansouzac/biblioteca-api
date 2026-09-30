<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        Categoria::create(['nome' => 'Romance', 'descricao' => 'Narrativas centradas em personagens e suas relações']);
        Categoria::create(['nome' => 'Fantasia', 'descricao' => 'Histórias com elementos mágicos e mundos imaginários']);
        Categoria::create(['nome' => 'Distopia', 'descricao' => 'Sociedades opressivas ou futuros sombrios']);
    }
}
