@extends('web.master.master')

@section('content')
<main class="site-main page-spacing">

    <!-- Slider Section -->
    @if (!empty($slides) && $slides->count() > 0)
    <div id="slider-section" class="slider-section container-fluid no-padding">
        <div id="photo-slider" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner" role="listbox">
                @foreach ($slides as $key => $slide)  
                    <div class="item{{($key == 0 ? ' active' : '')}}">
                        @if ($slide->link != null)                        
                            <a href="{{$slide->link}}" {{($slide->target == 1 ? 'target="_blank"' : '')}}>
                                <img src="{{$slide->getimagem()}}" alt="{{$slide->titulo}}" />  
                            </a> 
                        @else
                            <img src="{{$slide->getimagem()}}" alt="{{$slide->titulo}}" />
                        @endif 
                        @if ($slide->exibir_titulo != null)
                            <div class="carousel-caption">
                                <h2 data-animation="animated fadeInDown">{{$slide->titulo}}</h2>
                            </div>
                        @endif
                    </div>
                @endforeach 
            </div>
            <!-- Controles -->
            <a class="left carousel-control" href="#photo-slider" role="button" data-slide="prev">
                <span class="fa fa-angle-left" aria-hidden="true"></span>
            </a>
            <a class="right carousel-control" href="#photo-slider" role="button" data-slide="next">
                <span class="fa fa-angle-right" aria-hidden="true"></span>
            </a>
        </div>
    </div>
    @endif
    <!-- Slider Section /- -->
    
    <!-- container -->
    <div class="container">
        <div class="booking-form container-fluid">
            <?php
            if(isset($_POST['SendReserva'])){        
        
            $f['adultos']      =  $_POST['adultos'];
            $f['cri_0_5']      =  $_POST['cri_0_5'];
            $f['url']          =  $_POST['apartamento'];
            $f['checkini']     =  $_POST['checkini'];
            $f['checkouti']    =  $_POST['checkouti'];    
        
            if(in_array('',$f)){
                echo '<div class="alert alert-danger">
                       <strong>Atenção!</strong> Por favor, Selecione todos os campos!
                      </div>';
            }else{
                header('Location: pagina/reservas&checkouti='.$f['checkouti'].'&checkini='.$f['checkini'].'&adultos='.$f['adultos'].'&criancas='.$f['cri_0_5'].'&apartamento='.$f['url'].'');
            }
          }
        ?>
            <form class="col-md-12 col-sm-12 col-xs-12" action="" method="post">
                <div class="form-group">
                    <i class="fa fa-calendar-minus-o"></i>
                    <input type="text" class="form-control" id="datepicker1" name="checkini" value="" placeholder="Check In" />
                </div>
                <div class="form-group">
                    <i class="fa fa-calendar-minus-o"></i>
                    <input type="text" class="form-control" id="datepicker2" name="checkouti" value="" placeholder="Check Out" />
                </div>
                <div class="form-group">
                    <select class="selectpicker" name="apartamento">
                        <option value="'.$apartSelect['url'].'">'.$apartSelect['apart_nome'].'</option>
                    </select>
                </div>
                <div class="form-group">
                    <select class="selectpicker" name="adultos">
                        <option>Adultos</option>
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                    </select>
                </div>
                <div class="form-group">
                    <select class="selectpicker" name="cri_0_5">
                        <option>Crianças de 0 a 5</option>
                        <option>0</option>
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="submit" name="SendReserva" value="Pré-reserva" title="Pré-reserva" />
                </div>
            </form>
        </div>		
        
    </div><!-- container /- -->
    
    <div class="section-padding"></div>
    
    <!-- Offer Section -->
    <div class="container-fluid offer-section no-padding">
        <!-- container -->
        <div class="container">				
            <div class="offer-list">
                <?php
                //    $readApartament = read('apartamentos',"WHERE status = '1' AND exibir_home = '1' ORDER BY visitas ASC LIMIT 1");
                //    foreach($readApartament as $apartament);
                //    if(!$apartament){
                //         echo '';
                //    }else{
                //     echo '<a href="'.BASE.'/pagina/acomodacoes" title="'.$apartament['apart_nome'].'">';    
                //     echo '<div class="offer-box tall" style="height: auto;">';
                //     if($apartament['capa_da_home'] == ''):
                //         echo '<img src="'.BASE.'/tim.php?src='.BASE.'/admin/images/image.jpg&w=385&h=599&q=100&zc=1" alt="'.$apartament['apart_nome'].'" />';
                //     else:
                //         echo '<img style="offer-box1" src="'.BASE.'/tim.php?src='.BASE.'/uploads/apartamentos/'.$apartament['capa_da_home'].'&w=385&h=599&q=100&zc=1" alt="'.$apartament['apart_nome'].'" />';
                //     endif;                
                //     //echo '<div class="offer-detail">';
                //     //echo '<h3>Acomodações</h3><br />';
                //     //echo '<div class="price-detail">';
                //     //echo '<a title="'.$apartament['apart_nome'].'" href="'.BASE.'/pagina/acomodacoes">Ver Tudo <i class="fa fa-long-arrow-right"></i></a>';
                //     echo '</div>';
                //     //echo '</div>';
                //     //echo '</div>';
                //     echo '</a>';    
                //    }
                ?>
                
                <?php
                //    $readPaginas = read('posts',"WHERE status = '1' AND tipo = 'pagina' AND secao = '0' ORDER BY visitas DESC LIMIT 2");
                //    foreach($readPaginas as $pagina1);
                //    if(!$pagina1){
                //         echo '';
                //    }else{
                //         foreach($readPaginas as $pagina):
                //         echo '<a title="'.$pagina['titulo'].'" href="'.BASE.'/sessao/'.$pagina['url'].'">';
                //         echo '<div class="offer-box wide">';
                //         echo '<img src="'.BASE.'/tim.php?src='.BASE.'/uploads/paginas/capas/'.$pagina['thumb'].'&w=777&h=296&q=100&zc=1" alt="'.$pagina['titulo'].'" />';
                //         //echo '<div class="offer-detail">';
                //         //echo '<h3>'.$pagina['titulo'].'</h3>';
                //         //echo '<div class="price-detail">								';
                //         //echo '<a class="read-more" title="'.$pagina['titulo'].'" href="'.BASE.'/sessao/'.$pagina['url'].'">+ Detalhes <i class="fa fa-long-arrow-right"></i></a>';
                //         //echo '</div>';
                //         //echo '</div>';
                //         echo '</div>';
                //         echo '</a>';
                //         endforeach;
                //    }
                ?>
                
                <?php
                //    $readPagina = read('posts',"WHERE status = '1' AND tipo = 'pagina' AND secao = '0' ORDER BY visitas DESC LIMIT 2,1");
                //    foreach($readPagina as $pagina11);
                //    if(!$pagina11){
                //         echo '';
                //    }else{
                        
                //         echo '<a title="'.$pagina11['titulo'].'" href="'.BASE.'/sessao/'.$pagina11['url'].'">';
                //         echo '<div class="offer-box full">';
                //         echo '<img src="'.BASE.'/tim.php?src='.BASE.'/uploads/paginas/capas/'.$pagina11['thumb'].'&w=1170&h=300&q=100&zc=1" alt="'.$pagina11['titulo'].'" />';
                //         //echo '<div class="offer-detail">';
                //         //echo '<h3>'.$pagina11['titulo'].'</h3>';
                //         //echo '<div class="price-detail">								';
                //         //echo '<a class="read-more" title="'.$pagina11['titulo'].'" href="'.BASE.'/sessao/'.$pagina11['url'].'">+ Detalhes <i class="fa fa-long-arrow-right"></i></a>';
                //         //echo '</div>';
                //         //echo '</div>';
                //         echo '</div>';
                //         echo '</a>';
                        
                //    }
                ?>
                
            </div>
        </div><!-- container /- -->
    </div><!-- Offer Section /- -->
    
    <div class="section-padding" style="padding-top: 100px;"></div>
    
    <div class="container-fluid no-padding" style="background: #ddd;">
    
    <div class="container" id="video">
    
    <iframe src="https://player.vimeo.com/video/243223682?portrait=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
    
    </div>
    </div>
    
    
    
    <?php
       //$readApartamentos = read('apartamentos',"WHERE status = '1'");
    //   foreach($readApartamentos as $apartamento1);
    //   if(!$apartamento1){
    //        echo '';
    //   }else{
    //    
    //    echo '
    //    <!-- Room Section -->
    //    <div id="room-section" class="room-section container-fluid no-padding">
    //    <div class="section-padding"></div>
    //    <!-- Container -->
    //    <div class="container">
    //    <div id="room-carousel" class="carousel slide" data-ride="carousel">				
    //    <div class="carousel-inner" role="listbox">
    //    ';
    //    
    //        foreach($readApartamentos as $key=>$apartamento):
    //        
    //        if($key == 0){$active = ' active';}else{$active = '';}
    //        
    //        echo '
    //            <div class="item'.$active.'">
    //				<div class="col-md-6 no-padding room-img">
    //					<img src="'.BASE.'/tim.php?src='.BASE.'/uploads/apartamentos/'.$apartamento['img'].'&w=532&h=543&q=100&zc=1" alt="'.$apartamento['apart_nome'].'"/>
    //				</div>
    //				<div class="col-md-6 no-padding room-detail">
    //					<h4>'.$apartamento['apart_nome'].'</h4>
    //         ';
    //         $readAcessorios = read('apart_acessorio',"WHERE status = '1'");
    //         if(!$readAcessorios){
    //            echo '';
    //         }else{
    //            $checkExplode = $opcoes_text;            
    //            $checkValor = explode(',',$checkExplode);
    //            
    //            echo '<p>';                            
    //            foreach($readAcessorios as $acessorio):
    //            echo ''.$acessorio['ass_nome'].'<br />';
    //            endforeach;
    //            echo '</p>';                            
    //         } 
    //         echo ' 	
    //					<a class="read-more" title="+ Detalhes" href="'.BASE.'/pagina/apartamento/'.$apartamento['url'].'">+ Detalhes <i class="fa fa-long-arrow-right"></i></a>
    //				</div>
    //			</div>
    //        ';
    //        endforeach;
    //        
    //     echo '
    //     </div>
    //     <!-- Controls -->
    //     <div class="carousel-contorls">
    //     <a class="left carousel-control" href="#room-carousel" role="button" data-slide="prev">
    //	 <span class="fa fa-angle-left" aria-hidden="true"></span>
    //     </a>
    //     <div class="num"></div>
    //     <a class="right carousel-control" href="#room-carousel" role="button" data-slide="next">
    //	 <span class="fa fa-angle-right" aria-hidden="true"></span>				
    //     </a>
    //     </div>
    //     </div>
    //     </div><!-- Container /- -->		
    //     <div class="section-padding"></div>
    //     </div><!-- Room Section /- -->
    //     ';
    //   }
    ?>
        
    <div class="section-padding"></div>
    
    <?php
        // $readDepoimentos = read('depoimentos',"WHERE status = '1' ORDER BY data DESC LIMIT 12");
        // if($readDepoimentos){
        //         echo '<!-- Testimonial Section -->
        //     <div id="testimonial-section" class="testimonial-section container-fluid no-padding">
        //         <!-- section Header -->
        //         <div class="section-header">
        //             <h3>Depoimento de Clientes</h3>
        //         </div><!-- section Header /- -->
        //         <!-- Container -->
        //         <div class="container">
        //             <div class="col-md-1"></div>
        //             <div class="col-md-10 col-sm-12 col-xs-12">
        //                 <div id="testimonial-carousel" class="carousel slide" data-ride="carousel">
        //                     <!-- Wrapper for slides -->
        //                     <div class="carousel-inner" role="listbox">';        
        //             foreach($readDepoimentos as $keyy=>$depoimento):
        //             if($keyy == 0){$active1 = ' active';}else{$active1 = '';}
        //                 echo '<div class="item'.$active1.'" style="height:270px;">';
        //                 echo ''.$depoimento['depoimento'].'';  
        //                 echo '<h4>'.$depoimento['nome'].'</h4>';                  
        //                 echo '<p><strong>'.$depoimento['cidade'].' - '.$depoimento['uf'].'</strong></p>';
        //                 echo '</div>';                    
        //             endforeach;
        //         echo '</div>
        //                     <!-- Indicators -->
        //                     <ol class="carousel-indicators">
        //                         <li data-target="#testimonial-carousel" data-slide-to="0" class="active"></li>
        //                         <li data-target="#testimonial-carousel" data-slide-to="1"></li>
        //                         <li data-target="#testimonial-carousel" data-slide-to="2"></li>
        //                     </ol>
    
        //                     <!-- Controls -->
        //                     <a class="left carousel-control" href="#testimonial-carousel" role="button" data-slide="prev">
        //                         <i class="arrow_carrot-left"></i>
        //                     </a>
        //                     <a class="right carousel-control" href="#testimonial-carousel" role="button" data-slide="next">
        //                         <i class="arrow_carrot-right"></i>
        //                     </a>
        //                 </div>
        //             </div>
        //             <div class="col-md-1"></div>
        //         </div><!-- Container /- -->
        //         <div class="section-padding"></div>
        //     </div><!-- Testimonial Section /- -->';
        // }
    ?> 
    
    
    </main>















<div class="mainWrapper">
    <div class="container">
        <div class="pageContentArea clearfix">            
            <main>    
                @if (!empty($noticiasMain) && $noticiasMain->count() > 0)
                    @foreach ($noticiasMain as $noticia)
                        <article itemscope itemtype="https://schema.org/Article">
                            <figure>
                                <img itemprop="image" title="{{ $noticia->titulo }}" alt="{{ $noticia->titulo }}" src="{{ $noticia->cover() }}">
                            </figure>
                            <header>
                                <h1>
                                    <a itemprop="mainEntytiOfPage" href="{{route('web.noticia', ['slug' => $noticia->slug ])}}">
                                        <span itemprop="headline">{{ $noticia->titulo }}</span>
                                    </a>
                                </h1>
                                <time datetime="{{ Carbon\Carbon::parse($noticia->created_at)->format('Y-m-d') }}" itemprop="datePublished">{{ Carbon\Carbon::parse($noticia->created_at)->formatLocalized('%d, %B %Y') }}</time>
                                <time class="ds_none" datetime="{{ Carbon\Carbon::parse($noticia->updated_at)->format('Y-m-d') }}" itemprop="dateModified">{{ Carbon\Carbon::parse($noticia->updated_at)->format('d/m/Y') }}</time>
                                <span class="ds_none" itemprop="author" itemscope itemtype="https://schema.org/Person"><span itemprop="name">{{$noticia->user->name}}</span></span>
                                <span class="ds_none" itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
                                    <span itemprop="name">{{$configuracoes->nomedosite}}</span>
                                    <span itemprop="Logo" itemscope itemtype="https://schema.org/ImageObject">
                                        <img itemprop="contentUrl" src="{{ $noticia->cover() }}"/>
                                    </span>
                                </span>
                                <div class="specialContent">
                                    <div class="shareWrapper">
                                        <a href="{{route('web.noticia', ['slug' => $noticia->slug ])}}"></a>
                                    </div>
                                    <a class="cat-tag" href="{{route('web.blog.categoria', [ 'slug' => $noticia->categoriaObject->slug ])}}">{{$noticia->categoriaObject->titulo}}</a>
                                </div>
                            </header>
                        </article>
                    @endforeach
                @endif 
                </main>
        
            <aside class="sidebar">                    
                <div class="widget widget_timeline">
                    <div class="timeline-wrap">
                        @if (!empty($noticiasSidebar) && $noticiasSidebar->count() > 0)
                            @foreach ($noticiasSidebar as $noticia)
                            <article itemscope itemtype="https://schema.org/Article">
                                <figure>
                                    <img itemprop="image" title="{{ $noticia->titulo }}" alt="{{ $noticia->titulo }}" src="{{ $noticia->cover() }}">
                                </figure>
                                <header>
                                    <h3>
                                        <a itemprop="mainEntytiOfPage" href="{{route('web.noticia', ['slug' => $noticia->slug ])}}">
                                            <span itemprop="headline">{{ $noticia->titulo }}</span>
                                        </a>
                                    </h3>
                                    <time itemprop="datePublished" datetime="{{ Carbon\Carbon::parse($noticia->created_at)->format('Y-m-d') }}">{{ Carbon\Carbon::parse($noticia->created_at)->formatLocalized('%d, %B %Y') }}</time>                                
                                    <time class="ds_none" datetime="{{ Carbon\Carbon::parse($noticia->updated_at)->format('Y-m-d') }}" itemprop="dateModified">{{ Carbon\Carbon::parse($noticia->updated_at)->format('d/m/Y') }}</time>
                                    <span class="ds_none" itemprop="author" itemscope itemtype="https://schema.org/Person"><span itemprop="name">{{$noticia->user->name}}</span></span>
                                    <span class="ds_none" itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
                                        <span itemprop="name">{{$configuracoes->nomedosite}}</span>
                                        <span itemprop="Logo" itemscope itemtype="https://schema.org/ImageObject">
                                            <img itemprop="url" src="{{ $noticia->cover() }}"/>
                                        </span>
                                    </span>
                                </header>
                                <p>{!! $noticia->content_web !!}</p>
                            </article>
                            @endforeach
                        @endif                                             
                        <a href="{{route('web.noticias')}}" class="loadTimeline">Ver Mais</a>
                    </div>
                </div>
            </aside>
        
        </div>
    </div>   
</div>

<div class="container">   
    @if (!empty($noticiasVistos) && $noticiasVistos->count() > 0)
        <section class="most_visited">
            <header><h2>Mais Visualizados</h2></header>
            <div class="row">
                @foreach ($noticiasVistos as $noticia)
                    <div class="col-xs-12 col-sm-4">
                        <article>
                            <figure>
                                <img itemprop="image" title="{{ $noticia->titulo }}" alt="{{ $noticia->titulo }}" src="{{ $noticia->cover() }}">
                            </figure>
                            <header>
                                <h3>
                                    <a href="{{route('web.noticia', ['slug' => $noticia->slug ])}}">
                                        {{ $noticia->titulo }}
                                    </a>
                                </h3>
                                <time datetime="{{ Carbon\Carbon::parse($noticia->created_at)->format('Y-m-d') }}">{{ Carbon\Carbon::parse($noticia->created_at)->formatLocalized('%d, %B %Y') }}</time>                                
                            </header>
                            <div class="article_content">
                                <p>{!! $noticia->content_web !!}</p>
                            </div>
                            <footer>
                            <a href="{{route('web.noticia', ['slug' => $noticia->slug ])}}" class="readMore">Leia Mais</a> 
                            </footer>
                        </article>
                    </div>
                @endforeach 
            </div>
        </section>
    @endif
</div>

@endsection
    
     
    
    