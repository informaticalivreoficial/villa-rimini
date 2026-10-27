<?php

namespace App\Providers;

use App\Models\Configuracoes;
use App\Models\NewsletterCat;
use App\Models\Post;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Schema::defaultStringLength(191);
        Blade::aliasComponent('admin.components.message', 'message');
        Paginator::useBootstrap();

        $newsletter = collect();
        $paginas = collect();
        $configuracoes = null;

        try {
            //Newsletter FORM
            if (Schema::hasTable('newsletter_cat')) {
                $newsletter = NewsletterCat::where('sistema', 1)->where('status', 1)->get();
            }

            //Páginas
            if (Schema::hasTable('posts')) {
                $paginas = Post::where('tipo', 'pagina')->where('menu', 1)->postson()->get();
            }

            if (Schema::hasTable('configuracoes')) {
                $configuracoes = Configuracoes::find(1);
            }
        } catch (\Throwable $e) {
            // No build (railpack roda `php artisan config:cache` antes de existir rede
            // com o banco) a conexão falha. Seguimos com as coleções vazias para não
            // abortar o deploy; em runtime o banco está acessível e os dados carregam.
        }

        View()->share('newsletterForm', $newsletter);
        View()->share('viewPaginas', $paginas);
        View()->share('configuracoes', $configuracoes);
    }
}
