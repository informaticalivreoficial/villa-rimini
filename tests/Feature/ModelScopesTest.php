<?php

namespace Tests\Feature;

use App\Models\Apartamento;
use App\Models\NewsletterCat;
use App\Models\Post;
use App\Models\Reservas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTestData;
use Tests\TestCase;

class ModelScopesTest extends TestCase
{
    use CreatesTestData;
    use RefreshDatabase;

    public function test_apartamentos_filtram_por_status(): void
    {
        $disponivel = $this->createApartment(['titulo' => 'Disponível', 'slug' => 'disponivel', 'status' => 1]);
        $indisponivel = $this->createApartment(['titulo' => 'Indisponível', 'slug' => 'indisponivel', 'status' => 0]);

        $this->assertTrue(Apartamento::available()->whereKey($disponivel->id)->exists());
        $this->assertFalse(Apartamento::available()->whereKey($indisponivel->id)->exists());
        $this->assertTrue(Apartamento::unavailable()->whereKey($indisponivel->id)->exists());
    }

    public function test_posts_filtram_por_status(): void
    {
        $publicado = $this->createPage(['titulo' => 'Publicado', 'slug' => 'publicado', 'status' => 1]);
        $rascunho = $this->createPage(['titulo' => 'Rascunho', 'slug' => 'rascunho', 'status' => 0]);

        $this->assertTrue(Post::postson()->whereKey($publicado->id)->exists());
        $this->assertFalse(Post::postson()->whereKey($rascunho->id)->exists());
        $this->assertTrue(Post::postsoff()->whereKey($rascunho->id)->exists());
    }

    public function test_reservas_filtram_por_status(): void
    {
        $cliente = $this->createUser();
        $apartamento = $this->createApartment();

        $pendente = Reservas::create([
            'cliente' => $cliente->id,
            'apartamento' => $apartamento->id,
            'status' => 1,
        ]);

        $finalizada = Reservas::create([
            'cliente' => $cliente->id,
            'apartamento' => $apartamento->id,
            'status' => 0,
        ]);

        $this->assertTrue(Reservas::available()->whereKey($pendente->id)->exists());
        $this->assertFalse(Reservas::available()->whereKey($finalizada->id)->exists());
        $this->assertTrue(Reservas::unavailable()->whereKey($finalizada->id)->exists());
    }

    public function test_newsletter_cat_filtra_por_status(): void
    {
        $ativa = $this->createNewsletterCategory(['titulo' => 'Ativa']);
        $inativa = $this->createNewsletterCategory(['titulo' => 'Inativa', 'status' => 0]);

        $this->assertTrue(NewsletterCat::available()->whereKey($ativa->id)->exists());
        $this->assertFalse(NewsletterCat::available()->whereKey($inativa->id)->exists());
        $this->assertTrue(NewsletterCat::unavailable()->whereKey($inativa->id)->exists());
    }
}
