@extends('web.master.master')

@section('content')
<main class="site-main page-spacing">
    
    <div class="container-fluid page-banner about-banner" style="background-image: url({{$acomodacao->cover()}});">
        <div class="container">
            <h3 style="color: #fff;">{{$acomodacao->titulo}}</h3>
            <ol class="breadcrumb">
                <li><a style="color: #fff;" href="{{route('web.home')}}">Início</a></li>
                <li class="active">{{$acomodacao->titulo}}</li>
            </ol>
        </div>
    </div>
    
    <div class="section-padding"></div>    
    
    <div class="container">
        <div class="row">
            <!-- Contenta Area -->
            <div class="col-md-8 col-sm-8 col-xs-12 content-area">
                <div id="booking-carousel" class="carousel slide booking-carousel" data-ride="carousel">
                    @if ($acomodacao->images()->get()->count())
                        <div class="carousel-inner" role="listbox">
                            @foreach ($acomodacao->images()->get() as $key => $image)
                                <div class="item{{($key == 1 ? ' active' : '')}}">
                                    <img src="{{ $image->url_image }}" alt="{{$acomodacao->titulo}}" />
                                </div>
                            @endforeach			
                        </div>
                    @endif
                    
                    <!-- Controls -->
                    <a class="left carousel-control" href="#booking-carousel" role="button" data-slide="prev">
                        <span class="fa fa-caret-left" aria-hidden="true"></span>
                    </a>
                    <a class="right carousel-control" href="#booking-carousel" role="button" data-slide="next">
                        <span class="fa fa-caret-right" aria-hidden="true"></span>
                    </a>
                </div>
                
                <!-- Form -->
                <div class="booking-form2">
                    <h3>Efetuar uma Pré-Reserva</h3>
                    <form class="row j_formsubmitconsulta" action="" method="post">
                        <div class="alertas"></div>
                        <input class="noclear" type="hidden" name="action" value="prereserva" />
                        <input class="noclear" type="hidden" name="apart_id" value="{{$acomodacao->id}}" />
                        <!-- HONEYPOT -->
                        <input type="hidden" class="noclear" name="bairro" value="" />
                        <input type="text" class="noclear" style="display: none;" name="cidade1" value="" />
                        
                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <label>Check-in</label>
                            <i class="fa fa-calendar-minus-o"></i>
                            <input type="text" id="datepicker1" name="checkin" class="form-control" value=""/>
                        </div>
                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <label>Check-out</label>
                            <i class="fa fa-calendar-minus-o"></i>
                            <input type="text" id="datepicker2" class="form-control" name="checkout" value=""/>
                        </div>
                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <input type="text" placeholder="Nome" name="cliente_nome" value="" class="form-control"/>
                        </div>
                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <input type="text" placeholder="E-mail" name="email" value="" class="form-control"/>
                        </div>
                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <label>Adultos</label>
                            <select name="num_adultos" class="selectpicker">
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>                                    
                            </select>
                            </select>
                        </div>
                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <label>Crianças</label>
                            <select name="num_cri_0_5" class="selectpicker">
                                <option value="1">1</option>
                                          <option value="2">2</option>
                                          <option value="3">3</option>
                                          <option value="4">4</option>
                                          <option value="5">5</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <input type="text" placeholder="Telefone" data-mask="(99) 99999-9999" name="telefone" value="" class="form-control"/>
                        </div>						
                        
                        <div class="form-group col-md-4 col-sm-4 col-xs-12">
                            <input type="text" placeholder="Cidade" name="cidade" value="" class="form-control"/>
                        </div>
                        <div class="form-group col-md-2 col-sm-2 col-xs-12">
                            <select name="uf" class="selectpicker">
                                <option>AC</option>
                                <option>AL</option>
                                <option>AM</option>
                                <option>AP</option>
                                <option>BA</option>
                                <option>CE</option>
                                <option>DF</option>
                                <option>ES</option>
                                <option>GO</option>
                                <option>MA</option>
                                <option>MG</option>
                                <option>MS</option>
                                <option>MT</option>
                                <option>PA</option>
                                <option>PB</option>
                                <option>PE</option>
                                <option>PI</option>
                                <option>PR</option>
                                <option>RJ</option>
                                <option>RN</option>
                                <option>RO</option>
                                <option>RR</option>
                                <option>RS</option>
                                <option>SC</option>
                                <option>SE</option>
                                <option>SP</option>
                                <option>TO</option>
                             </select>
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <textarea placeholder="Informações Adicionais" name="descricao" class="form-control"></textarea>
                        </div>
                        
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <button class="read-more b_nome" type="submit" title="Efetuar Pré-Reserva">Efetuar Pré-Reserva <i class="fa fa-long-arrow-right"></i></button>
                        </div>
                    </form>
                </div><!-- Form /- -->
            </div><!-- Contenta Area /- -->
            
            <!-- Widget Area -->
            <div class="col-md-4 col-sm-4 col-xs-12 widget-area">
                
                <aside class="widget widget_features" style="margin-bottom: 10px;">
                    <h3 class="widget-title">Informações</h3>
                    {!!$acomodacao->descricao!!}
                    
                    @if(!empty($postsTags) && $postsTags->count() > 0)
                        <ul class="tags">
                            @foreach($postsTags as $posttags) 
                                @php
                                    $array = explode(",", $posttags->tags);
                                    foreach($array as $tags){
                                        $tag = trim($tags);                                                       
                                        echo '<li>';
                                        echo '<img src="{{url(frontend/assets/images/bullet-apartamento.png)}}" alt="'.$ass['ass_nome'].'" />';
                                        echo $tag;
                                        echo '</a></li>';
                                    }
                                @endphp                                                     
                            @endforeach              
                        </ul>                        
                    @endif
                </aside>
                
                @if ($acomodacao->notasadicionais)
                    <aside class="widget widget_features" style="margin-bottom: 10px;">
                        <h3 class="widget-title">{{$acomodacao->notasadicionais}}</h3>   
                    </aside>
                @endif                
                
                @if (!empty($acomodacoes) && $acomodacoes->count() > 0)
                    <aside class="widget widget_room">
                        <h3 class="widget-title">Veja Também</h3>
                        @foreach($acomodacoes as $aparts)
                            <div class="single-room">
                                <a href="{{route('web.acomodacao',['slug' => $aparts->slug])}}" tilte="{{$aparts->titulo}}">
                                    <img style="max-width: 100px;max-height:100px;" src="{{$aparts->cover()}}" alt="{{$aparts->titulo}}">
                                </a>
                                <h4>{{$aparts->titulo}} 
                                    @if ($aparts->exibir_valor == 1)
                                    <b> R${{(number_format($aparts->valor_cafe, 2 , ',' , '.'))}}</b>
                                    @endif                                                      
                                <a href="{{route('web.acomodacao',['slug' => $aparts->slug])}}" tilte="{{$aparts->titulo}}"><span style="margin-top: 10px;">+ Detalhes</span></a>
                                </h4>
                            </div>
                        @endforeach
                    </aside>
                @endif
               
            </div>
        </div>
    </div>
    
    <div class="section-padding"></div>
</main>
@endsection

@section('css')
    
@endsection

@section('js')
    
@endsection