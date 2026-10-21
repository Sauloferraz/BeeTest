<?php

namespace Tests\Feature;

use App\Models\Entrega;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EntregaTest extends TestCase
{
    use RefreshDatabase;

    public function test_listar_exige_autenticacao(): void
    {
        $this->getJson('/api/entregas')->assertStatus(401);
    }

    public function test_usuario_autenticado_lista_entregas(): void
    {
        Sanctum::actingAs(User::factory()->create());

        Entrega::create([
            'descricao' => 'Pizza grande',
            'endereco_coleta' => 'Rua A, 10',
            'endereco_entrega' => 'Rua B, 20',
            'valor' => 12.50,
        ]);

        $this->getJson('/api/entregas')
            ->assertStatus(200)
            ->assertJsonCount(1);
    }

    public function test_criar_entrega(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/entregas', [
            'descricao' => 'Pizza grande',
            'endereco_coleta' => 'Rua A, 10',
            'endereco_entrega' => 'Rua B, 20',
            'valor' => 12.50,
        ])
            ->assertStatus(201)
            ->assertJsonFragment(['descricao' => 'Pizza grande']);

        $this->assertDatabaseHas('entregas', ['descricao' => 'Pizza grande']);
    }

    public function test_criar_entrega_sem_descricao_retorna_422(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/entregas', ['valor' => 10])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['descricao']);
    }
}
