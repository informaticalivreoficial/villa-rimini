@extends("web.{$configuracoes->template}.master.master")

@section('content')
<div class="container">
	<div class="pageContentArea clearfix">
        <main style="width: 100%;">
            <article class="single-article clearfix">
            	<figure>
                    <img src="{{$post->cover()}}" alt="{{$post->titulo}}">                                                          
                </figure>
                <p>{{$post->thumb_legenda}}</p>
                <header>
                    <h3 style="width: 100%;">{{$post->titulo}}</h3>                    
                </header>
                <div class="row">
                    <div class="col-sm-12" style="text-align: center;">                    
                        <div class="shareInner">
                            <!-- Social list -->
                            <div id="shareIcons"></div>
                        </div>                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">{!!$post->content!!}</div>
                </div>
                
                <div class="row">
                    <div class="col-sm-6">                      
                        <section class="widget widget_author">
                            <figure>
                                <img src="{{$post->user->getUrlAvatarAttribute()}}" width="76" height="76" alt="{{$post->user->name}}"/>
                            </figure>
                            <h3>{{$post->user->name}}</h3>
                            <time datetime="{{ Carbon\Carbon::parse($post->created_at)->format('Y-m-d') }}">{{ Carbon\Carbon::parse($post->created_at)->formatLocalized('%d, %B %Y') }}</time>
                        </section>   
                    </div>
                    <div class="col-sm-6">
                        <section class="widget widget_author">
                            <!-- INICIO FORMULARIO BOTAO PAGSEGURO -->
                            <form action="https://pagseguro.uol.com.br/checkout/v2/donation.html" method="post">
                            <!-- NÃO EDITE OS COMANDOS DAS LINHAS ABAIXO -->
                            <input type="hidden" name="currency" value="BRL" />
                            <input type="hidden" name="receiverEmail" value="financeiro@informaticalivre.com" />
                            <input type="hidden" name="iot" value="button" />
                            <input type="image" src="https://stc.pagseguro.uol.com.br/public/img/botoes/doacoes/120x53-doar.gif" name="submit" alt="Faça uma doação e ajude a manter nosso site" />
                            </form>
                            <!-- FINAL FORMULARIO BOTAO PAGSEGURO -->
                        </section>
                    </div>
                </div>
                @if($post->images()->get()->count()) 
                    <div class="row">                   
                        @foreach($post->images()->get() as $image)  
                            <figure style="float:left;padding-left:10px;padding-top:10px;margin-bottom:20px;">
                                <a href="{{ $image->url_image }}" rel="shadowbox[Galeria]">
                                    <img width="150" src="{{ $image->url_cropped }}"  alt="{{ $post->titulo }}"/>
                                </a>
                            </figure>                                             
                        @endforeach 
                    </div>                               
                @endif

                @if (!empty($parceiros) && $parceiros->count() > 0)
                    <div class="row">
                        <div class="col-sm-12">
                            <section class="widget widget_author">
                                <p><b>Parceiros</b></p>
                                @foreach ($parceiros as $parceiro)
                                    <a href="{{$parceiro->link}}" target="_blank">
                                        <img style="margin-bottom:20px;" src="{{$parceiro->cover()}}" title="{{$parceiro->name}}" alt="{{$parceiro->name}}" />
                                    </a>
                                @endforeach
                            </section> 
                        </div>
                    </div>
                @endif
            </article>            
        </main>        
    </div>

    @if (!empty($postsMais) && $postsMais->count() > 0)
        <section class="related-articles">
            <header><h2><span>Recentes</span></h2></header>        	
                <div class="row"> 
                    @foreach ($postsMais as $postmais)
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                            <article>
                                <figure>
                                    <img title="{{$postmais->titulo}}" alt="{{$postmais->titulo}}" src="{{$postmais->cover()}}">
                                </figure>
                                <header>
                                    <h5>
                                        <a href="{{route(($postmais->tipo == 'artigo' ? 'web.blog.artigo' : 'web.noticia'), ['slug' => $postmais->slug] )}}">
                                            {{$postmais->titulo}}
                                        </a>
                                    </h5>
                                </header>
                                <footer>
                                    <a href="{{route(($postmais->tipo == 'artigo' ? 'web.blog.artigo' : 'web.noticia'), ['slug' => $postmais->slug] )}}" class="readMore">Ler Agora >></a>
                                </footer>
                            </article>
                        </div>
                    @endforeach
                </div>            
        </section>
    @endif    
</div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{url('frontend/assets/js/jsSocials/jssocials.css')}}" />
    <link rel="stylesheet" href="{{url('frontend/assets/js/jsSocials/jssocials-theme-flat.css')}}" />
    <link rel="stylesheet" href="{{url('frontend/assets/js/shadowbox/shadowbox.css')}}"/>
@endsection

@section('js')
    <script src="{{url('frontend/assets/js/shadowbox/shadowbox.js')}}"></script>       
    <script type="text/javascript">
        Shadowbox.init({
            language: 'pt',
            players: ['img', 'html', 'iframe', 'qt', 'swf', 'flv'],
        });
    </script>

    <script src="{{url('frontend/assets/js/jsSocials/jssocials.min.js')}}"></script>
    <script>
        (function ($) {
            
            $('#shareIcons').jsSocials({
                //url: "http://www.google.com",
                showLabel: false,
                showCount: false,
                shareIn: "popup",
                shares: ["email", "twitter", "facebook", "whatsapp"]
            });
            $('.shareIcons').jsSocials({
                //url: "http://www.google.com",
                showLabel: false,
                showCount: false,
                shareIn: "popup",
                shares: ["email", "twitter", "facebook", "whatsapp"]
            });  
        
        })(jQuery); 
    </script>    
@endsection