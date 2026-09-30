<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = ['nome', 'descricao'];

    public function livros()
    {
        return $this->hasMany(Livro::class);
    }
}
