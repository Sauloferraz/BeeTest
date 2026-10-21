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
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $user->entregas()->create([
            'descricao' => 'Pizza grande',
            'endereco_coleta' => 'Rua A, 10',
            'endereco_entrega' => 'Rua B, 20',
            'valor' => 12.50,
        ]);

        $this->getJson('/api/entregas')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data');
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

    public function test_usuario_ve_apenas_suas_entregas(): void
    {
        $outro = User::factory()->create();
        $outro->entregas()->create([
            'descricao' => 'Entrega de outro usuário',
            'endereco_coleta' => 'Rua X',
            'endereco_entrega' => 'Rua Y',
            'valor' => 20,
        ]);

        Sanctum::actingAs(User::factory()->create());

        $this->getJson('/api/entregas')
            ->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    public function test_usuario_nao_acessa_entrega_de_outro(): void
    {
        $dono = User::factory()->create();
        $entrega = $dono->entregas()->create([
            'descricao' => 'Entrega privada',
            'endereco_coleta' => 'Rua X',
            'endereco_entrega' => 'Rua Y',
            'valor' => 20,
        ]);

        Sanctum::actingAs(User::factory()->create());

        $this->getJson("/api/entregas/{$entrega->id}")->assertStatus(403);
        $this->putJson("/api/entregas/{$entrega->id}", ['status' => 'entregue'])->assertStatus(403);
        $this->deleteJson("/api/entregas/{$entrega->id}")->assertStatus(403);
    }

    public function test_resposta_nao_expoe_user_id(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $entrega = $user->entregas()->create([
            'descricao' => 'Pizza grande',
            'endereco_coleta' => 'Rua A, 10',
            'endereco_entrega' => 'Rua B, 20',
            'valor' => 12.50,
        ]);

        $this->getJson("/api/entregas/{$entrega->id}")
            ->assertStatus(200)
            ->assertJsonPath('data.descricao', 'Pizza grande')
            ->assertJsonMissingPath('data.user_id');
    }

    public function test_cache_da_listagem_e_limpo_ao_criar_entrega(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->getJson('/api/entregas')->assertJsonCount(0, 'data');

        $this->postJson('/api/entregas', [
            'descricao' => 'Pizza grande',
            'endereco_coleta' => 'Rua A, 10',
            'endereco_entrega' => 'Rua B, 20',
            'valor' => 12.50,
        ])->assertStatus(201);

        $this->getJson('/api/entregas')->assertJsonCount(1, 'data');
    }
}
