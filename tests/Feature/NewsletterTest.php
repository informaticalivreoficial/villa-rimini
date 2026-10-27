<?php

namespace Tests\Feature;

use App\Models\Newsletter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTestData;
use Tests\TestCase;

class NewsletterTest extends TestCase
{
    use CreatesTestData;
    use RefreshDatabase;

    public function test_cadastra_novo_email_na_lista_padrao(): void
    {
        $this->createConfig();
        $categoria = $this->createNewsletterCategory();

        $response = $this->post(route('web.sendNewsletter', [
            'email' => 'novo@example.com',
            'nome' => 'Novo Visitante',
        ]));

        $response->assertOk();
        $response->assertJsonStructure(['sucess']);
        $this->assertDatabaseHas('newsletter', [
            'email' => 'novo@example.com',
            'nome' => 'Novo Visitante',
            'status' => 1,
            'categoria' => $categoria->id,
        ]);
    }

    public function test_reativa_email_ja_cadastrado_sem_duplicar(): void
    {
        $this->createConfig();
        $categoria = $this->createNewsletterCategory();

        Newsletter::create([
            'email' => 'antigo@example.com',
            'nome' => 'Antigo',
            'status' => 0,
            'categoria' => $categoria->id,
        ]);

        $response = $this->post(route('web.sendNewsletter', [
            'email' => 'antigo@example.com',
        ]));

        $response->assertOk();
        $response->assertJsonStructure(['sucess']);
        $this->assertSame(1, Newsletter::where('email', 'antigo@example.com')->count());
        $this->assertSame(1, Newsletter::where('email', 'antigo@example.com')->first()->status);
    }

    public function test_rejeita_email_invalido(): void
    {
        $this->createConfig();
        $this->createNewsletterCategory();

        $response = $this->post(route('web.sendNewsletter', [
            'email' => 'email-invalido',
        ]));

        $response->assertOk();
        $response->assertJsonStructure(['error']);
        $this->assertDatabaseCount('newsletter', 0);
    }

    public function test_rejeita_honeypot(): void
    {
        $this->createConfig();
        $this->createNewsletterCategory();

        $response = $this->post(route('web.sendNewsletter', [
            'email' => 'spam@example.com',
            'bairro' => 'preenchido por robô',
        ]));

        $response->assertOk();
        // Honeypot responde com sucesso falso, para não revelar a checagem ao bot.
        $response->assertJsonStructure(['sucess']);
        $this->assertDatabaseCount('newsletter', 0);
    }
}
