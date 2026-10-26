<?php

namespace Tests\Concerns;

use App\Models\Apartamento;
use App\Models\CatPost;
use App\Models\Cidades;
use App\Models\Configuracoes;
use App\Models\Estados;
use App\Models\NewsletterCat;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\View;

trait CreatesTestData
{
    protected function createConfig(array $attributes = []): Configuracoes
    {
        $config = Configuracoes::create(array_merge([
            'status' => 1,
            'ano_de_inicio' => 2000,
            'nomedosite' => 'Hotel Villa di Rimini',
            'template' => 'default',
            'descricao' => 'Hotel de teste',
            'email' => 'contato@villarimini.test',
            'rss' => 'rss',
            'sitemap' => 'sitemap',
        ], $attributes));

        View::share('configuracoes', $config);

        return $config;
    }

    protected function createUser(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'status' => 1,
        ], $attributes));
    }

    protected function createApartment(array $attributes = []): Apartamento
    {
        return Apartamento::create(array_merge([
            'titulo' => 'Apartamento Teste',
            'slug' => 'apartamento-teste',
            'status' => 1,
            'exibir_home' => 1,
            'descricao' => 'Descrição do apartamento de teste.',
        ], $attributes));
    }

    protected function createCategory(array $attributes = []): CatPost
    {
        return CatPost::create(array_merge([
            'titulo' => 'Páginas',
            'slug' => 'paginas',
            'tipo' => 'pagina',
            'status' => 1,
        ], $attributes));
    }

    protected function createPage(array $attributes = []): Post
    {
        $user = $this->createUser();
        $category = $this->createCategory();

        return Post::create(array_merge([
            'autor' => $user->id,
            'categoria' => $category->id,
            'tipo' => 'pagina',
            'titulo' => 'Página de Teste',
            'slug' => 'pagina-de-teste',
            'content' => 'Conteúdo da página de teste.',
            'menu' => 1,
            'status' => 1,
        ], $attributes));
    }

    protected function createEstadoCidade(): array
    {
        $estado = Estados::create([
            'estado_nome' => 'São Paulo',
            'estado_uf' => 'SP',
            'estado_regiao' => 'Sudeste',
        ]);

        $cidade = Cidades::create([
            'estado_id' => $estado->estado_id,
            'cidade_nome' => 'Picinguaba',
            'cidade_uf' => 'SP',
        ]);

        return [$estado, $cidade];
    }

    protected function createNewsletterCategory(array $attributes = []): NewsletterCat
    {
        return NewsletterCat::create(array_merge([
            'titulo' => 'Lista padrão',
            'sistema' => 1,
            'status' => 1,
        ], $attributes));
    }

    /**
     * As variáveis compartilhadas pelo AppServiceProvider são calculadas no boot
     * da aplicação, antes das migrations. Depois de criar os dados de teste,
     * atualizamos os valores para que as views reflitam o cenário do teste.
     */
    protected function refreshSharedViewData(): void
    {
        View::share('configuracoes', Configuracoes::find(1));
        View::share('newsletterForm', NewsletterCat::where('sistema', 1)->where('status', 1)->get());
        View::share('viewPaginas', Post::where('tipo', 'pagina')->where('menu', 1)->postson()->get());
    }
}
