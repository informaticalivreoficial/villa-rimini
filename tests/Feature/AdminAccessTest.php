<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTestData;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use CreatesTestData;
    use RefreshDatabase;

    public function test_visitante_e_redirecionado_para_o_login(): void
    {
        $this->get('/admin')->assertRedirect('/login');
        $this->get('/admin/reservas-pendentes')->assertRedirect('/login');
    }

    public function test_tela_de_login_e_renderizada(): void
    {
        $this->createConfig();

        $this->get('/login')->assertOk();
    }

    public function test_usuario_autenticado_acessa_lista_de_reservas(): void
    {
        $this->createConfig();
        $usuario = $this->createUser();

        $response = $this->actingAs($usuario)->get(route('reservas.pendentes'));

        $response->assertOk();
        $response->assertSee('Reservas Pendentes');
    }
}
