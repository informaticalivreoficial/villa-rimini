<?php

namespace App\Providers;

use App\Models\CatPost;
use App\Models\NewsletterCat;
use App\Models\Post;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Goutte\Client;

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
        URL::forceScheme('https');
        Schema::defaultStringLength(191);
        Blade::aliasComponent('admin.components.message', 'message');

        //Newsletter FORM
        $newsletter = NewsletterCat::where('sistema', 1)->where('status', 1)->get();
        View()->share('newsletterForm', $newsletter);

        //Páginas
        $paginas = Post::where('tipo', 'pagina')->where('menu', 1)->postson()->get();
        View()->share('viewPaginas', $paginas);

        $configuracoes = \App\Models\Configuracoes::find(1); 
        View()->share('configuracoes', $configuracoes);
        
        Paginator::useBootstrap();
    }
}
