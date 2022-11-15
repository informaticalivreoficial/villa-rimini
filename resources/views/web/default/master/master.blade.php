<!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="ie6"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html class="" lang="pt-br"><!--<![endif]-->
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="copyright" content="{{$configuracoes->ano_de_inicio}} - {{$configuracoes->nomedosite}}">
    <meta name="language" content="pt-br" /> 
    <meta name="author" content="{{env('DESENVOLVEDOR')}}"/>
    <meta name="designer" content="Renato Montanari">
    <meta name="publisher" content="Renato Montanari">
    <meta name="url" content="{{$configuracoes->dominio}}" />
    <meta name="keywords" content="{{$configuracoes->metatags}}">
    <meta name="distribution" content="web">
    <meta name="rating" content="general">
    <meta name="date" content="Dec 26">
    
    <meta name="google-site-verification" content="U_EVMDoD67yPddyxfDxdwkm5BHohro-oBGua1GxSCH0" />
    <meta name="msvalidate.01" content="AB238289F13C246C5E386B6770D9F10E" />

    {!! $head ?? '' !!}

    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Standard Favicon -->
    <link rel="icon" type="image/x-icon" href="{{$configuracoes->getfaveicon()}}" />

    <!-- For iPhone 4 Retina display: -->
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{$configuracoes->getfaveicon()}}"/>

    <!-- For iPad: -->
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{$configuracoes->getfaveicon()}}"/>

    <!-- For iPhone: -->
    <link rel="apple-touch-icon-precomposed" href="{{$configuracoes->getfaveicon()}}"/>	

    <!-- Library - Bootstrap v3.3.5 -->
    <link rel="stylesheet" type="text/css" href="{{url('frontend/assets/libraries/lib.css')}}"/>
    
    <!-- Custom - Common CSS -->
    <link rel="stylesheet" type="text/css" href="{{url('frontend/assets/css/plugins.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{url('frontend/assets/css/navigation-menu.css')}}"/>

    <!-- Custom - Theme CSS -->
    <link rel="stylesheet" type="text/css" href="{{url('frontend/assets/css/style.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{url('frontend/assets/css/shortcodes.css')}}" />

    <!-- Renato CSS -->
    <link rel="stylesheet" type="text/css" href="{{url('frontend/assets/css/renato.css')}}" />
        
    <!--[if lt IE 9]>
        <script src="{{url('frontend/assets/js/html5/respond.min.js')}}"></script>
    <![endif]-->
    
    @hasSection('css')
        @yield('css')
    @endif
</head>

<body data-offset="200" data-spy="scroll" data-target=".ow-navigation">  

    <a id="top"></a> 

    <header id="header" class="header-section header-position container-fluid no-padding">        
        <div class="top-header container-fluid no-padding">
            <!-- Container -->
            <div class="container">
                <div class="row">
                    <div class="logo-block col-md-3">
                        <a href="{{route('web.home')}}" title="{{$configuracoes->nomedosite}}">
                            <img src="{{$configuracoes->getLogomarca()}}" alt="{{$configuracoes->nomedosite}}" />
                        </a>
                    </div>
                    <div class="col-md-9 contact-detail">
                        @if ($configuracoes->telefone1)
                            <div class="phone">
                                <img src="{{url('frontend/assets/images/phone-ic.png')}}" alt="{{$configuracoes->telefone1}}" />
                                <h6>Atendimento</h6>
                                <a href="tell:{{\App\Helpers\Renato::limpaTelefone($configuracoes->telefone1)}}" title="{{$configuracoes->telefone1}}">{{$configuracoes->telefone1}}</a>
                            </div>
                        @endif
                        @if ($configuracoes->whatsapp)
                            <div class="address">
                                <img src="{{url('frontend/assets/images/address-ic.png')}}" alt="{{$configuracoes->whatsapp}}" />
                                <h6>Atendimento</h6>
                                <a target="_blank" href="{{\App\Helpers\WhatsApp::getNumZap($configuracoes->whatsapp ,'Atendimento '.$configuracoes->nomedosite)}}" title="{{$configuracoes->whatsapp}}">{{$configuracoes->whatsapp}}</a>
                            </div>                            
                        @endif
                                                                
                        <div class="menu-search">
                            <div id="sb-search" class="sb-search">
                                <form action="" method="post">                                
                                    <input class="sb-search-input" placeholder="Digite o que você procura..." type="text" value="" name="search" id="search" />
                                    <button class="sb-search-submit">
                                        <img src="{{url('frontend/assets/images/search-ic.png')}}" alt="Pesquisar" />
                                    </button>
                                    <span class="sb-icon-search"></span>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="menu-block">            
            <div class="container">
                <div class="row">
                    <div class="col-md-10">
                        <nav class="navbar navbar-default ow-navigation">                            
                            <div class="navbar-header">
                                <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <a href="{{route('web.home')}}" title="{{$configuracoes->nomedosite}}" class="navbar-brand">{{$configuracoes->nomedosite}}</a>
                            </div>                            
                            <div class="navbar-collapse collapse" id="navbar">
                                <ul class="nav navbar-nav">								
                                    <li><a href="{{route('web.acomodacoes')}}" title="Acomodações">Acomodações</a></li> 
                                    @if (!empty($viewPaginas) && $viewPaginas->count() > 0)
                                        @foreach($viewPaginas as $paginas)
                                            <li><a href="{{route('web.pagina', ['slug' => $paginas->slug])}}" title="Acomodações">{{$paginas->titulo}}</a></li>
                                        @endforeach
                                    @endif                                   
                                    <li class="dropdown active">
                                        <a href="{{route('web.atendimento')}}" title="Atendimento" class="dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false">Atendimento</a>									
                                    </li>
                                </ul>
                            </div>                            
                        </nav>
                    </div>
                    <div class="col-md-2 book-now">
                        <a href="{{route('web.reservar')}}" title="Pré-reserva">Pré-reserva</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- INÍCIO DO CONTEÚDO DO SITE -->
    @yield('content')
    <!-- FIM DO CONTEÚDO DO SITE -->
    
    <div class="footer-section container-fluid no-padding">
        <!-- Top Footer -->
        <div class="top-footer container-fluid no-padding">
            <!-- Container -->
            <div class="container">
                
                <div class="row">
                    <aside class="col-md-4 col-sm-12 col-xs-12 widget text_widget">
                        <h4 class="widget_title">{{$configuracoes->nomedosite}}</h4>
                        <p>{{$configuracoes->descricao}}</p>
                        <ul class="social_widget">
                            @if ($configuracoes->facebook)
                                <li><a target="_blank" href="{{$configuracoes->facebook}}" title="Facebook"><i class="fa fa-facebook"></i></a></li>
                            @endif
                            @if ($configuracoes->twitter)
                                <li><a target="_blank" href="{{$configuracoes->twitter}}" title="Twitter"><i class="fa fa-twitter"></i></a></li>
                            @endif
                            @if ($configuracoes->instagram)
                                <li><a target="_blank" href="{{$configuracoes->instagram}}" title="Instagram"><i class="fa fa-instagram"></i></a></li>
                            @endif
                            @if ($configuracoes->linkedin)
                                <li><a target="_blank" href="{{$configuracoes->linkedin}}" title="linkedin"><i class="fa fa-linkedin"></i></a></li>
                            @endif
                            @if ($configuracoes->youtube)
                                <li><a target="_blank" href="{{$configuracoes->youtube}}" title="Youtube"><i class="fa fa-youtube-play"></i></a></li>
                            @endif                            						
                        </ul>
                    </aside>
                    @if (!empty($newsletterForm))
                        <aside class="col-md-4 col-sm-6 col-xs-6 widget widget_newsletter">
                            <h4 class="widget_title">Receba Nossas Ofertas</h4>
                            <form method="post" action="" name="newsletter" class="j_submitnewsletter">
                                @csrf                                
                                <div id="js-newsletter-result"></div>
                                <!-- HONEYPOT -->
                                <input type="hidden" class="noclear" name="bairro" value="" />
                                <input type="text" class="noclear" style="display: none;" name="cidade" value="" />
                                <input type="hidden" class="noclear" name="status" value="1" />
                                <input type="hidden" class="noclear" name="nome" value="#Cadastrado pelo Site" />                                
                                <div class="form_hide">
                                <input type="text" style="margin-top: 10px;color: #FFFFFF;" class="form-control" name="email" placeholder="Digite Seu E-mail" />
                                <input type="submit" class="btn j_buttom noclear" id="js-subscribe-btn" value="Cadastrar" />
                                </div>
                            </form>
                        </aside>
                    @endif
                    
                    <!-- Galeria -->
                    <aside class="col-md-4 col-sm-6 col-xs-6 widget widget_gallery" style="text-align: center;">
                    <h4 class="widget_title">Instagram</h4>                         
                                               
                    <!-- Galeria /- -->
                    <div style="width: 100%; height: auto; display: table;" >
                        
                    <!-- LightWidget WIDGET --><script src="//lightwidget.com/widgets/lightwidget.js"></script><iframe src="//lightwidget.com/widgets/bbdd12f62db7558e83498c362b13ad7f.html" scrolling="no" allowtransparency="true" class="lightwidget-widget" style="width: 100%; border: 0; overflow: hidden;"></iframe></div>
                    </aside>
                    
                </div>
                
            </div>
        </div>
        <div class="bottom-footer container-fluid">
            <p><a style="color:#fff;" href="{{route('web.politica')}}">Política de privacidade</a> - <a style="color:#fff;" href="{{route('web.atendimento')}}">Atendimento</a></p>
            <p>© {{$configuracoes->ano_de_inicio}} - {{date('Y')}} {{$configuracoes->nomedosite}} - Todos os direitos reservados.</p>
            <p class="font-accent">
                <span class="small text-silver-dark">Feito com <i style="color:red;" class="fa fa-heart"></i> por <a style="color:#fff;" target="_blank" href="{{env('DESENVOLVEDOR_URL')}}">{{env('DESENVOLVEDOR')}}</a></span>
            </p>
        </div>
    </div><!-- Footer Section /- -->
    
    <!-- JQuery v1.11.3 -->
    <script src="{{url('frontend/assets/js/jquery.min.js')}}"></script>

    <!-- Library JS -->
    <script src="{{url('frontend/assets/libraries/lib.js')}}"></script>
    <script src="{{url('frontend/assets/js/jquery-migrate-1.0.0.js')}}"></script>
        
    <!-- Library - Theme JS -->	
    <script src="{{url('frontend/assets/js/functions.js')}}"></script>

    <!--bootstrap input mask-->
    <script src="{{url('frontend/assets/libraries/bootstrap-inputmask/bootstrap-inputmask.min.js')}}"></script>     

    <script>
        $(function () {
    
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
    
            // Seletor, Evento/efeitos, CallBack, Ação
            $('.j_submitnewsletter').submit(function (){
                var form = $(this);
                var dataString = $(form).serialize();
    
                $.ajax({
                    url: "{{ route('web.sendNewsletter') }}",
                    data: dataString,
                    type: 'GET',
                    dataType: 'JSON',
                    beforeSend: function(){
                        form.find("#js-subscribe-btn").attr("disabled", true);
                        form.find('#js-subscribe-btn').val("Carregando...");                
                        form.find('.alert').fadeOut(500, function(){
                            $(this).remove();
                        });
                    },
                    success: function(response){
                        $('html, body').animate({scrollTop:$('#js-newsletter-result').offset().top-40}, 'slow');
                        if(response.error){
                            form.find('#js-newsletter-result').html('<div class="alert alert-danger error-msg">'+ response.error +'</div>');
                            form.find('.error-msg').fadeIn();                    
                        }else{
                            form.find('#js-newsletter-result').html('<div class="alert alert-success error-msg">'+ response.sucess +'</div>');
                            form.find('.error-msg').fadeIn();                    
                            form.find('input[class!="noclear"]').val('');
                            form.find('.form_hide').fadeOut(500);
                        }
                    },
                    complete: function(response){
                        form.find("#js-subscribe-btn").attr("disabled", false);
                        form.find('#js-subscribe-btn').val("Cadastrar");                                
                    }
    
                });
    
                return false;
            });
    
        });
    </script>

    @hasSection('js')
        @yield('js')
    @endif    

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{$configuracoes->tagmanager_id}}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
    
        gtag('config', '{{$configuracoes->tagmanager_id}}');
    </script>
</body>
</html>