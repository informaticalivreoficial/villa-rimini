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
                @if (!empty($apartamento) && $apartamento->count() > 0)                    
                    <a href="{{route('web.acomodacao', ['slug' => $apartamento[0]->slug])}}" title="{{$apartamento[0]->titulo}}">
                        <div class="offer-box tall" style="height: auto;">
                            <img style="min-height:599px;" style="offer-box1" src="{{$apartamento[0]->cover()}}" alt="{{$apartamento[0]->titulo}}">
                        </div>
                    </a>                                        
                @endif
                
                @if (!empty($paginas) && $paginas->count() > 0)
                    @foreach($paginas as $pagina)
                        <a title="{{$pagina->titulo}}" href="{{route('web.pagina', ['slug' => $pagina->slug])}}">
                            <div class="offer-box wide">
                                <img class="imful1" src="{{$pagina->cover()}}" alt="{{$pagina->titulo}}" />
                                <div class="offer-detail">
                                    {{--<h3>{{$pagina->titulo}}</h3>
                                    <div class="price-detail">								
                                        <a class="read-more" title="{{$pagina->titulo}}" href="">+ Detalhes <i class="fa fa-long-arrow-right"></i></a>
                                    </div>--}}
                                </div>
                            </div>
                        </a>
                    @endforeach
                @endif

                @if (!empty($paginasFull) && $paginas->count() > 0)
                    @foreach($paginasFull as $pagina)
                        <a title="{{$pagina->titulo}}" href="{{route('web.pagina', ['slug' => $pagina->slug])}}">
                            <div class="offer-box full">
                                <img class="imful" src="{{$pagina->cover()}}" alt="{{$pagina->titulo}}" />
                                <div class="offer-detail">
                                    {{--<h3>{{$pagina->titulo}}</h3>
                                    <div class="price-detail">								
                                        <a class="read-more" title="{{$pagina->titulo}}" href="">+ Detalhes <i class="fa fa-long-arrow-right"></i></a>
                                    </div>--}}
                                </div>
                            </div>
                        </a>
                    @endforeach
                @endif
                
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

@endsection
    
     
    
    