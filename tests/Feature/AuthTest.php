<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_pode_se_registrar(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Luan',
            'email' => 'luan@teste.com',
            'password' => '123456',
        ]);

        $response->assertStatus(201)->assertJsonStructure(['user' => ['id', 'name', 'email'], 'token']);
        $this->assertDatabaseHas('users', ['email' => 'luan@teste.com']);
    }

    public function test_nao_registra_email_repetido(): void
    {
        User::create(['name' => 'Luan', 'email' => 'luan@teste.com', 'password' => '123456']);

        $response = $this->postJson('/api/register', [
            'name' => 'Outro',
            'email' => 'luan@teste.com',
            'password' => '123456',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors('email');
    }

    public function test_usuario_pode_fazer_login(): void
    {
        User::create(['name' => 'Luan', 'email' => 'luan@teste.com', 'password' => '123456']);

        $response = $this->postJson('/api/login', [
            'email' => 'luan@teste.com',
            'password' => '123456',
        ]);

        $response->assertOk()->assertJsonStructure(['user', 'token']);
    }

    public function test_login_com_senha_errada(): void
    {
        User::create(['name' => 'Luan', 'email' => 'luan@teste.com', 'password' => '123456']);

        $response = $this->postJson('/api/login', [
            'email' => 'luan@teste.com',
            'password' => 'errada',
        ]);

        $response->assertStatus(401);
    }

    public function test_retorna_usuario_logado(): void
    {
        $user = User::create(['name' => 'Luan', 'email' => 'luan@teste.com', 'password' => '123456']);
        $token = $user->createToken('api')->plainTextToken;

        $response = $this->withToken($token)->getJson('/api/user');

        $response->assertOk()->assertJson(['email' => 'luan@teste.com']);
    }

    public function test_logout_invalida_o_token(): void
    {
        $user = User::create(['name' => 'Luan', 'email' => 'luan@teste.com', 'password' => '123456']);
        $token = $user->createToken('api')->plainTextToken;

        $this->withToken($token)->postJson('/api/logout')->assertOk();

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_rota_protegida_sem_token(): void
    {
        $this->getJson('/api/user')->assertStatus(401);
    }
}
