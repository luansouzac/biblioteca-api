<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CategoriaTest extends TestCase
{
    use RefreshDatabase;

    private function login(): void
    {
        Sanctum::actingAs(User::create(['name' => 'Luan', 'email' => 'luan@teste.com', 'password' => '123456']));
    }

    public function test_lista_categorias(): void
    {
        Categoria::create(['nome' => 'Romance']);
        Categoria::create(['nome' => 'Fantasia']);

        $this->getJson('/api/categorias')->assertOk()->assertJsonCount(2);
    }

    public function test_mostra_uma_categoria(): void
    {
        $categoria = Categoria::create(['nome' => 'Romance']);

        $this->getJson("/api/categorias/{$categoria->id}")->assertOk()->assertJson(['nome' => 'Romance']);
    }

    public function test_categoria_inexistente_retorna_404(): void
    {
        $this->getJson('/api/categorias/999')->assertStatus(404);
    }

    public function test_cadastra_categoria(): void
    {
        $this->login();

        $response = $this->postJson('/api/categorias', ['nome' => 'Terror', 'descricao' => 'Livros de terror']);

        $response->assertStatus(201)->assertJson(['nome' => 'Terror']);
        $this->assertDatabaseHas('categorias', ['nome' => 'Terror']);
    }

    public function test_nao_cadastra_categoria_repetida(): void
    {
        $this->login();
        Categoria::create(['nome' => 'Romance']);

        $this->postJson('/api/categorias', ['nome' => 'Romance'])->assertStatus(422)->assertJsonValidationErrors('nome');
    }

    public function test_nao_cadastra_categoria_sem_token(): void
    {
        $this->postJson('/api/categorias', ['nome' => 'Terror'])->assertStatus(401);
    }

    public function test_atualiza_categoria(): void
    {
        $this->login();
        $categoria = Categoria::create(['nome' => 'Romance']);

        $response = $this->putJson("/api/categorias/{$categoria->id}", ['nome' => 'Romance', 'descricao' => 'Nova descrição']);

        $response->assertOk()->assertJson(['descricao' => 'Nova descrição']);
    }

    public function test_remove_categoria(): void
    {
        $this->login();
        $categoria = Categoria::create(['nome' => 'Romance']);

        $this->deleteJson("/api/categorias/{$categoria->id}")->assertNoContent();

        $this->assertDatabaseMissing('categorias', ['id' => $categoria->id]);
    }
}
