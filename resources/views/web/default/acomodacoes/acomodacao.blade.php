@extends("web.{$configuracoes->template}.master.master")

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
            <div class="col-md-12 col-sm-12 col-xs-12 col-lg-8 content-area">
                <div id="booking-carousel" class="carousel slide booking-carousel" data-ride="carousel">
                    @if ($acomodacao->images()->get()->count())
                        <div class="carousel-inner" role="listbox">
                            @foreach ($acomodacao->images()->get() as $key => $image)
                                <div class="item{{($key == 1 ? ' active' : '')}}">
                                    <img style="max-height: 430px;" src="{{ $image->url_image }}" alt="{{$acomodacao->titulo}}" />
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

                <div class="row">
                    <div class="col-md-12">
                        @if ($acomodacao->descricao)
                        <h3 class="widget-title">Informações</h3>
                        {!!$acomodacao->descricao!!}
                        @endif
                        
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

                        @if ($acomodacao->notasadicionais)
                            <aside class="widget widget_features" style="margin-bottom: 10px;">
                                <h5 class="widget-title">{{$acomodacao->notasadicionais}}</h5>   
                            </aside>
                        @endif
                    </div>                    
                </div>
                
                <!-- Form -->
                <div class="booking-form2">
                    <h3>Efetuar uma Pré-Reserva</h3>
                    <form class="j_formsubmit" action="" method="post" autocomplete="off">
                        @csrf
                        <div class="form-group col-12">
                            <div id="js-contact-result"></div>
                            <input class="noclear" type="hidden" name="apart_id" value="{{$acomodacao->id}}" />
                            <!-- HONEYPOT -->
                            <input type="hidden" class="noclear" name="bairro" value="" />
                            <input type="text" class="noclear" style="display: none;" name="cidade1" value="" />    
                        </div>
                        
                        <div class="form_hide">
                            <div class="form-group col-md-6 col-sm-6 col-xs-12 col-lg-6">
                                <label>Nome</label>
                                <input type="text" name="nome" value="" class="form-control"/>
                            </div>
                            <div class="form-group col-md-6 col-sm-6 col-xs-12 col-lg-6">
                                <label>Email</label>
                                <input type="text" name="email" value="" class="form-control"/>
                            </div>
    
                            <div class="form-group col-md-4 col-sm-4 col-xs-12 col-lg-4">
                                <label>Telefone</label>
                                <input type="text" name="telefone" value="" class="form-control celularmask"/>
                            </div>
    
                            <div class="form-group col-md-4 col-sm-4 col-xs-12 col-lg-4">
                                <label>Estado</label>
                                <select name="uf" class="form-control" id="state-dd">
                                    @if(!empty($estados))
                                        <option value="">Selecione</option>
                                        @foreach($estados as $estado)
                                            <option value="{{$estado->estado_id}}">{{$estado->estado_nome}}</option>
                                        @endforeach                                                                        
                                    @endif                                
                                 </select>
                            </div>
                            
                            <div class="form-group col-md-4 col-sm-4 col-xs-12 col-lg-4">
                                <label>Cidade</label>
                                <select id="city-dd" class="form-control" name="cidade">
                                    <option value="">Selecione o Estado</option>
                                </select>
                            </div>
    
                            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                <label>Check-in</label>
                                <i class="fa fa-calendar-minus-o"></i>
                                <input type="text" name="checkin" class="form-control datepicker-here" value="" data-language='pt-BR'/>
                            </div>
                            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                <label>Check-out</label>
                                <i class="fa fa-calendar-minus-o"></i>
                                <input type="text" class="form-control datepicker-here" name="checkout" value="" data-language='pt-BR'/>
                            </div>
                            
                            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                <label>Adultos</label>
                                <select name="num_adultos" class="selectpicker">
                                    <option value="1" selected>1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option> 
                                </select>
                            </div>
                            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                <label>Crianças de 0 a 5</label>
                                <select name="num_cri_0_5" class="selectpicker">
                                    <option value="0" selected>0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>	
                            
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <textarea placeholder="Informações Adicionais" name="mensagem" class="form-control"></textarea>
                            </div>
                            
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <button class="read-more" id="js-contact-btn" type="submit" title="Efetuar Pré-Reserva">Efetuar Pré-Reserva <i class="fa fa-long-arrow-right"></i></button>
                            </div>
                        </div>
                    
                        
                    </form>
                </div>
            </div>
            
            <!-- Widget Area -->
            <div class="col-md-12 col-sm-12 col-xs-12 col-lg-4 widget-area">
                
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
<link href="{{url(asset('backend/plugins/airdatepicker/css/datepicker.min.css'))}}" rel="stylesheet" type="text/css">
@endsection

@section('js')
<script src="{{url(asset('backend/plugins/airdatepicker/js/datepicker.min.js'))}}"></script>
<script src="{{url(asset('backend/plugins/airdatepicker/js/i18n/datepicker.pt-BR.js'))}}"></script>
<script src="{{url(asset('backend/assets/js/jquery.mask.js'))}}"></script>
<script>
    $(document).ready(function () { 
        var $celularmask = $(".celularmask");
        $celularmask.mask('(99) 99999-9999', {reverse: false});
    });

    $(function(){
         
        $('.datepicker-here').datepicker({
            autoClose: true,            
            minDate: new Date(),
            position: "bottom right", //'right center', 'right bottom', 'right top', 'top center', 'bottom center'
            
        });  

        $("#city-dd").attr("disabled", true);
        $('#state-dd').on('change', function () {
            var idState = this.value;
            $("#city-dd").html('');
            $.ajax({
                url: "{{route('web.fetchCity')}}",
                type: "POST",
                data: {
                    estado_id: idState,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (res) {
                    $("#city-dd").attr("disabled", false);
                    $('#city-dd').html('<option value="">Selecione a cidade</option>');
                    $.each(res.cidades, function (key, value) {
                        $("#city-dd").append('<option value="' + value
                            .cidade_id + '">' + value.cidade_nome + '</option>');
                    });
                }
            });            
        });
             
    });

    $(function(){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.j_formsubmit').submit(function (){
            var form = $(this);
            var dataString = $(form).serialize();

            $.ajax({
                url: "{{ route('web.acomodacaoSend') }}",
                data: dataString,
                type: 'GET',
                dataType: 'JSON',
                beforeSend: function(){
                    form.find("#js-contact-btn").attr("disabled", true);
                    form.find('#js-contact-btn').html("Carregando...");                
                    form.find('.alert').fadeOut(500, function(){
                        $(this).remove();
                    });
                },
                success: function(resposta){
                    $('html, body').animate({scrollTop:$('#js-contact-result').offset().top-130}, 'slow');
                    if(resposta.error){
                        form.find('#js-contact-result').html('<div class="alert alert-danger error-msg">'+ resposta.error +'</div>');
                        form.find('.error-msg').fadeIn();                    
                    }else{
                        form.find('#js-contact-result').html('<div class="alert alert-success error-msg">'+ resposta.sucess +'</div>');
                        form.find('.error-msg').fadeIn(); 
                        form.find('input[class!="noclear"]').val('');
                        form.find('textarea[class!="noclear"]').val('');
                        form.find('.form_hide').fadeOut(500); 
                    }
                },
                complete: function(resposta){
                    form.find("#js-contact-btn").attr("disabled", false);
                    form.find('#js-contact-btn').html('Efetuar Pré-Reserva <i class="fa fa-long-arrow-right"></i>');                                
                }

            });

            return false;
        });
    });
</script>   
@endsection