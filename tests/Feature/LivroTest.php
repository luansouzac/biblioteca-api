<?php

namespace Tests\Feature;

use App\Models\Autor;
use App\Models\Categoria;
use App\Models\Livro;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LivroTest extends TestCase
{
    use RefreshDatabase;

    private Autor $autor;

    private Categoria $categoria;

    protected function setUp(): void
    {
        parent::setUp();

        $this->autor = Autor::create(['nome' => 'Machado de Assis']);
        $this->categoria = Categoria::create(['nome' => 'Romance']);
    }

    private function login(): void
    {
        Sanctum::actingAs(User::create(['name' => 'Luan', 'email' => 'luan@teste.com', 'password' => '123456']));
    }

    private function criarLivro(): Livro
    {
        return Livro::create([
            'titulo' => 'Dom Casmurro',
            'autor_id' => $this->autor->id,
            'categoria_id' => $this->categoria->id,
        ]);
    }

    public function test_lista_livros_com_autor_e_categoria(): void
    {
        $this->criarLivro();

        $response = $this->getJson('/api/livros');

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.autor.nome', 'Machado de Assis')
            ->assertJsonPath('0.categoria.nome', 'Romance');
    }

    public function test_mostra_um_livro_com_autor_e_categoria(): void
    {
        $livro = $this->criarLivro();

        $response = $this->getJson("/api/livros/{$livro->id}");

        $response->assertOk()
            ->assertJsonPath('titulo', 'Dom Casmurro')
            ->assertJsonPath('autor.nome', 'Machado de Assis')
            ->assertJsonPath('categoria.nome', 'Romance');
    }

    public function test_livro_inexistente_retorna_404(): void
    {
        $this->getJson('/api/livros/999')->assertStatus(404);
    }

    public function test_cadastra_livro(): void
    {
        $this->login();

        $response = $this->postJson('/api/livros', [
            'titulo' => 'Memórias Póstumas de Brás Cubas',
            'isbn' => '9788535910664',
            'ano_publicacao' => 1881,
            'paginas' => 208,
            'autor_id' => $this->autor->id,
            'categoria_id' => $this->categoria->id,
        ]);

        $response->assertStatus(201)->assertJsonPath('autor.nome', 'Machado de Assis');
        $this->assertDatabaseHas('livros', ['titulo' => 'Memórias Póstumas de Brás Cubas']);
    }

    public function test_nao_cadastra_livro_com_autor_inexistente(): void
    {
        $this->login();

        $response = $this->postJson('/api/livros', [
            'titulo' => 'Livro',
            'autor_id' => 999,
            'categoria_id' => $this->categoria->id,
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors('autor_id');
    }

    public function test_nao_cadastra_livro_sem_campos_obrigatorios(): void
    {
        $this->login();

        $this->postJson('/api/livros', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['titulo', 'autor_id', 'categoria_id']);
    }

    public function test_nao_cadastra_livro_sem_token(): void
    {
        $this->postJson('/api/livros', ['titulo' => 'Livro'])->assertStatus(401);
    }

    public function test_atualiza_livro(): void
    {
        $this->login();
        $livro = $this->criarLivro();

        $response = $this->putJson("/api/livros/{$livro->id}", ['ano_publicacao' => 1899]);

        $response->assertOk()->assertJsonPath('ano_publicacao', 1899);
    }

    public function test_remove_livro(): void
    {
        $this->login();
        $livro = $this->criarLivro();

        $this->deleteJson("/api/livros/{$livro->id}")->assertNoContent();

        $this->assertDatabaseMissing('livros', ['id' => $livro->id]);
    }

    public function test_remover_autor_remove_seus_livros(): void
    {
        $this->login();
        $livro = $this->criarLivro();

        $this->deleteJson("/api/autores/{$this->autor->id}")->assertNoContent();

        $this->assertDatabaseMissing('livros', ['id' => $livro->id]);
    }
}
