<?php

namespace Tests\Feature;

use App\Models\Autor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AutorTest extends TestCase
{
    use RefreshDatabase;

    private function login(): void
    {
        Sanctum::actingAs(User::create(['name' => 'Luan', 'email' => 'luan@teste.com', 'password' => '123456']));
    }

    public function test_lista_autores(): void
    {
        Autor::create(['nome' => 'Machado de Assis']);
        Autor::create(['nome' => 'Clarice Lispector']);

        $response = $this->getJson('/api/autores');

        $response->assertOk()->assertJsonCount(2);
    }

    public function test_mostra_um_autor(): void
    {
        $autor = Autor::create(['nome' => 'Machado de Assis']);

        $response = $this->getJson("/api/autores/{$autor->id}");

        $response->assertOk()->assertJson(['nome' => 'Machado de Assis'])->assertJsonStructure(['livros']);
    }

    public function test_autor_inexistente_retorna_404(): void
    {
        $this->getJson('/api/autores/999')->assertStatus(404);
    }

    public function test_cadastra_autor(): void
    {
        $this->login();

        $response = $this->postJson('/api/autores', [
            'nome' => 'Jorge Amado',
            'nacionalidade' => 'Brasileira',
            'data_nascimento' => '1912-08-10',
        ]);

        $response->assertStatus(201)->assertJson(['nome' => 'Jorge Amado']);
        $this->assertDatabaseHas('autores', ['nome' => 'Jorge Amado']);
    }

    public function test_nao_cadastra_autor_sem_nome(): void
    {
        $this->login();

        $this->postJson('/api/autores', [])->assertStatus(422)->assertJsonValidationErrors('nome');
    }

    public function test_nao_cadastra_autor_sem_token(): void
    {
        $this->postJson('/api/autores', ['nome' => 'Jorge Amado'])->assertStatus(401);
    }

    public function test_atualiza_autor(): void
    {
        $this->login();
        $autor = Autor::create(['nome' => 'Machado']);

        $response = $this->putJson("/api/autores/{$autor->id}", ['nome' => 'Machado de Assis']);

        $response->assertOk()->assertJson(['nome' => 'Machado de Assis']);
        $this->assertDatabaseHas('autores', ['id' => $autor->id, 'nome' => 'Machado de Assis']);
    }

    public function test_remove_autor(): void
    {
        $this->login();
        $autor = Autor::create(['nome' => 'Machado de Assis']);

        $this->deleteJson("/api/autores/{$autor->id}")->assertNoContent();

        $this->assertDatabaseMissing('autores', ['id' => $autor->id]);
    }
}
