<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTestData;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use CreatesTestData;
    use RefreshDatabase;

    public function test_home_exibe_configuracao_apartamento_e_paginas(): void
    {
        $this->createConfig();
        $apartamento = $this->createApartment();
        $pagina = $this->createPage(['titulo' => 'Sobre o Hotel', 'slug' => 'sobre-o-hotel']);
        $this->refreshSharedViewData();

        $response = $this->get(route('web.home'));

        $response->assertOk();
        $response->assertSee('Hotel Villa di Rimini');
        $response->assertSee($apartamento->titulo);
        $response->assertSee($pagina->titulo);
    }

    public function test_pagina_de_acomodacoes_lista_apartamentos_disponiveis(): void
    {
        $this->createConfig();
        $apartamento = $this->createApartment();
        $this->createApartment([
            'titulo' => 'Apartamento Oculto',
            'slug' => 'apartamento-oculto',
            'status' => 0,
        ]);

        $response = $this->get(route('web.acomodacoes'));

        $response->assertOk();
        $response->assertSee($apartamento->titulo);
        $response->assertDontSee('Apartamento Oculto');
    }

    public function test_detalhe_da_acomodacao_exibe_o_apartamento(): void
    {
        $this->createConfig();
        $apartamento = $this->createApartment();
        $this->createReservationPage();

        $response = $this->get(route('web.acomodacao', ['slug' => $apartamento->slug]));

        $response->assertOk();
        $response->assertSee($apartamento->titulo);
    }

    public function test_pagina_de_pre_reserva_e_renderizada(): void
    {
        $this->createConfig();
        $apartamento = $this->createApartment();
        $this->createReservationPage();

        $response = $this->get(route('web.reservar'));

        $response->assertOk();
        $response->assertSee('Efetuar uma Pré-Reserva');
        $response->assertSee($apartamento->titulo);
    }

    public function test_paginas_institucionais_respondem_com_sucesso(): void
    {
        $this->createConfig();

        $this->get(route('web.atendimento'))->assertOk();
        $this->get(route('web.politica'))->assertOk();
        $this->get(route('web.pesquisa', ['search' => 'hotel']))->assertOk();
    }

    public function test_urls_sao_geradas_com_https_atras_de_proxy(): void
    {
        $this->createConfig();
        $this->refreshSharedViewData();

        $response = $this->withServerVariables(['HTTP_X_FORWARDED_PROTO' => 'https'])
            ->get(route('web.home'));

        $response->assertOk();
        $response->assertSee('https://', false);
        $response->assertDontSee('http://localhost', false);
    }

    public function test_feed_rss_responde_como_xml(): void
    {
        $this->createConfig();

        $response = $this->get(route('web.feed'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/xml');
        $response->assertSee('<rss version="2.0">', false);
    }

    public function test_fetch_city_retorna_cidades_do_estado(): void
    {
        $this->createConfig();
        [$estado, $cidade] = $this->createEstadoCidade();
        $this->createEstadoCidade();

        $response = $this->get(route('web.fetchCity', ['estado_id' => $estado->estado_id]));

        $response->assertOk();
        $response->assertJsonFragment([
            'cidade_id' => $cidade->cidade_id,
            'cidade_nome' => $cidade->cidade_nome,
        ]);
    }

    private function createReservationPage(): Post
    {
        $user = $this->createUser();
        $category = $this->createCategory();

        return Post::forceCreate([
            'id' => 5,
            'autor' => $user->id,
            'categoria' => $category->id,
            'tipo' => 'pagina',
            'titulo' => 'Pré-reserva',
            'slug' => 'pre-reserva',
            'content' => 'Página de pré-reserva',
            'menu' => 0,
            'status' => 1,
        ]);
    }
}
