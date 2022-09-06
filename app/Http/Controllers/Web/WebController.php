<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Web\Atendimento;
use App\Mail\Web\AtendimentoRetorno;
use Illuminate\Support\Facades\Storage;

use App\Models\{
    Apartamento,
    Post,
    CatPost,
    Estados,
    Newsletter,
    Parceiro,
    Slide,
    User
};
use App\Services\ConfigService;
use App\Support\Seo;
use Carbon\Carbon;

class WebController extends Controller
{
    protected $configService;
    protected $seo;

    public function __construct(ConfigService $configService)
    {
        $this->configService = $configService;
        $this->seo = new Seo();        
    }

    public function home()
    {
        $apartamento = Apartamento::available()
                    ->where('exibir_home', 1)
                    ->inRandomOrder()
                    ->limit(1)
                    ->get();
        $paginas = Post::orderBy('created_at', 'DESC')->where('tipo', 'pagina')
                    ->where('menu', 1)
                    ->postson()
                    ->limit(2)
                    ->inRandomOrder()
                    ->get();
        $paginasFull = Post::orderBy('created_at', 'DESC')->where('tipo', 'pagina')
                    ->where('menu', 1)
                    ->postson()
                    ->skip(2)->limit(1)->inRandomOrder()
                    ->get();

        $slides = Slide::orderBy('created_at', 'DESC')
                    ->available()
                    ->where('expira', '>=', Carbon::now())
                    ->get();       
        
        $head = $this->seo->render($this->configService->getConfig()->nomedosite ?? 'Informática Livre',
            $this->configService->getConfig()->descricao ?? 'Informática Livre desenvolvimento de sistemas web desde 2005',
            route('web.home'),
            $this->configService->getMetaImg() ?? 'https://informaticalivre.com/media/metaimg.jpg'
        ); 

		return view('web.home',[
            'head' => $head,            
            'slides' => $slides,
            'apartamento' => $apartamento,
            'paginasFull' => $paginasFull,
            'paginas' => $paginas
		]);
    }

    public function quemsomos()
    {
        $paginaQuemSomos = Post::where('tipo', 'pagina')->postson()->where('id', 5)->first();
        $head = $this->seo->render('Quem Somos - ' . $this->configService->getConfig()->nomedosite,
            $this->configService->getConfig()->descricao ?? 'Informática Livre desenvolvimento de sistemas web desde 2005',
            route('web.quemsomos'),
            $this->configService->getMetaImg() ?? 'https://informaticalivre.com/media/metaimg.jpg'
        );
        return view('web.quem-somos',[
            'head' => $head,
            'paginaQuemSomos' => $paginaQuemSomos
        ]);
    }

    public function politica()
    {
        $head = $this->seo->render('Política de Privacidade - ' . $this->configService->getConfig()->nomedosite ?? 'Informática Livre',
            'Política de Privacidade - ' . $this->configService->getConfig()->nomedosite,
            route('web.politica'),
            $this->configService->getMetaImg() ?? 'https://informaticalivre.com/media/metaimg.jpg'
        );

        return view('web.politica',[
            'head' => $head
        ]);
    }

    

    public function pesquisa(Request $request)
    {
        $search = $request->only('search');

        $paginas = Post::where(function($query) use ($request){
            if($request->search){
                $query->orWhere('titulo', 'LIKE', "%{$request->search}%")
                    ->where('tipo', 'pagina')->postson();
                $query->orWhere('content', 'LIKE', "%{$request->search}%")
                    ->where('tipo', 'pagina')->postson();
            }
        })->postson()->limit(10)->get();

        $artigos = Post::where(function($query) use ($request){
            if($request->search){
                $query->orWhere('titulo', 'LIKE', "%{$request->search}%")
                    ->where('tipo', 'artigo')->postson();
                $query->orWhere('content', 'LIKE', "%{$request->search}%")
                    ->where('tipo', 'artigo')->postson();
            }
        })->postson()->limit(10)->get();
        
        $head = $this->seo->render('Pesquisa por ' . $request->search ?? 'Informática Livre',
            'Pesquisa - ' . $this->configService->getConfig()->nomedosite,
            route('web.blog.artigos'),
            $this->configService->getMetaImg() ?? 'https://informaticalivre.com/media/metaimg.jpg'
        );
        
        return view('web.pesquisa',[
            'head' => $head,
            'paginas' => $paginas,
            'artigos' => $artigos
        ]);
    }

    public function pagina($slug)
    {
        $clientesCount = User::where('client', 1)->count();
        $post = Post::where('slug', $slug)->where('tipo', 'pagina')->postson()->first();        
        $post->views = $post->views + 1;
        $post->save();

        $head = $this->seo->render($post->titulo ?? 'Informática Livre',
            $post->titulo,
            route('web.pagina', ['slug' => $post->slug]),
            $post->cover() ?? $this->configService->getMetaImg()
        );

        return view('web.pagina', [
            'head' => $head,
            'post' => $post,
            'clientesCount' => $clientesCount
        ]);
    }    
    
    public function atendimento()
    {
        $head = $this->seo->render('Atendimento - ' . $this->configService->getConfig()->nomedosite,
            'Nossa equipe está pronta para melhor atender as demandas de nossos clientes!',
            route('web.atendimento'),
            $this->configService->getMetaImg() ?? 'https://informaticalivre.com/media/metaimg.jpg'
        );        

        return view('web.atendimento', [
            'head' => $head            
        ]);
    }

    public function acomodacoes()
    {
        $acomodacoes = Apartamento::available()->get();
        $head = $this->seo->render('Acomodações - ' . $this->configService->getConfig()->nomedosite,
            $this->configService->getConfig()->descricao ?? 'Informática Livre desenvolvimento de sistemas web desde 2005',
            route('web.acomodacoes'),
            $this->configService->getMetaImg() ?? 'https://informaticalivre.com/media/metaimg.jpg'
        );
        return view('web.acomodacoes.index',[
            'head' => $head,
            'acomodacoes' => $acomodacoes
        ]);
    }

    public function acomodacao($slug)
    {
        $acomodacao = Apartamento::where('slug', $slug)->available()->first();
        $acomodacoes = Apartamento::where('id', '!=', $acomodacao->id)->available()->get();

        $postsTags = Post::orderBy('views', 'DESC')
            ->where('tags', '!=', '')
            ->where('id', '!=', $acomodacao->id)
            ->postson()
            ->limit(11)
            ->get();

        $acomodacao->views = $acomodacao->views + 1;
        $acomodacao->save();

        $head = $this->seo->render($acomodacao->titulo . ' - ' . $this->configService->getConfig()->nomedosite,
            $acomodacao->descricao ?? 'Informática Livre desenvolvimento de sistemas web desde 2005',
            route('web.acomodacao', ['slug' => $acomodacao->slug]),
            $this->configService->getMetaImg() ?? 'https://informaticalivre.com/media/metaimg.jpg'
        );
        return view('web.acomodacoes.acomodacao',[
            'head' => $head,
            'acomodacao' => $acomodacao,
            'acomodacoes' => $acomodacoes,
            'postsTags' => $postsTags,
        ]);
    }
}
