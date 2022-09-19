@extends("web.{$configuracoes->template}.master.master")

@section('content')
<main class="site-main page-spacing">
	
	<div class="container-fluid page-banner about-banner" style="background-color:#3B4C76 !important;">
		<div class="container">
			<h3 style="color: #fff;">Pré-reserva</h3>
			<ol class="breadcrumb">
				<li><a style="color: #fff;" href="{{route('web.home')}}">Início</a></li>
				<li class="active">Pré-reserva</li>
			</ol>
		</div>
	</div>
    
    <div class="section-padding"></div>
    <div id="booking-section" class="booking-section2 container-fluid no-padding">
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="booking-form2 col-md-12 col-sm-12 pull-right">
                    <br />
                    <!--
<div class="img-block">
    					<div class="col-md-6 col-sm-12 col-xs-6 no-padding"><h4>15<sup>%</sup><sub>Off</sub><span>Somente pelo site</span></h4></div>
    					<div class="col-md-6 col-sm-12 col-xs-6 no-padding"><img src="<?php //echo PATCH;?>/images/booking-ic.png" alt="Booking"/></div>
    				</div> 
-->                   
					<h3><strong>Efetuar uma Pré-Reserva</strong></h3>

					<form class="col-md-12 col-sm-12 col-xs-12 no-padding j_formsubmit" action="" method="post" autocomplete="off">
                        @csrf
                        <div class="form-group col-12">
                            <div id="js-contact-result"></div>
                            <!-- HONEYPOT -->
                            <input type="hidden" class="noclear" name="bairro" value="" />
                            <input type="text" class="noclear" style="display: none;" name="cidade1" value="" />    
                        </div>
						
                        <div class="form_hide">
                            <div class="form-group col-md-12 col-sm-12 col-xs-12 col-lg-12">
                                <label>Selecione o Apartamento</label>
                                <select name="apart_id" class="form-control">
                                    @if(!empty($acomodacoes) && $acomodacoes->count() > 0)
                                        <option value="">Selecione</option>
                                        @foreach($acomodacoes as $apartamento)
                                            <option value="{{$apartamento->id}}" {{(!empty($dadosForm) && $dadosForm['apart_id'] == $apartamento->id ? 'selected' : '')}}>{{$apartamento->titulo}}</option>
                                        @endforeach                                                                        
                                    @endif                                
                                 </select>
                            </div>

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
    
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Check-in</label>
                                <i class="fa fa-calendar-minus-o"></i>
                                <input type="text" name="checkin" class="form-control datepicker-here" value="{{(!empty($dadosForm['checkin']) ? $dadosForm['checkin'] : '')}}" data-language='pt-BR'/>
                            </div>
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Check-out</label>
                                <i class="fa fa-calendar-minus-o"></i>
                                <input type="text" class="form-control datepicker-here" name="checkout" value="{{(!empty($dadosForm['checkin']) ? $dadosForm['checkout'] : '')}}" data-language='pt-BR'/>
                            </div>
                            
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Adultos</label>
                                <select name="num_adultos" class="selectpicker">
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{$i}}" {{(!empty($dadosForm['adultos']) && $i == $dadosForm['adultos'] ? 'selected' : ($i == 1 ? 'selected' : ''))}}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label>Crianças de 0 a 5</label>
                                <select name="num_cri_0_5" class="selectpicker">
                                    @for($i = 0; $i <= 5; $i++)
                                        <option value="{{$i}}" {{(!empty($dadosForm['cri_0_5']) && $i == $dadosForm['cri_0_5'] ? 'selected' : ($i == 0 ? 'selected' : ''))}}>
                                            {{ $i }}
                                        </option>
                                    @endfor
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
            
			<div class="col-md-6 col-sm-6 col-xs-12">
                <br />
				{!! $paginareserva->content !!}                
			</div>
            
            <div class="section-padding"></div>
		</div>
    
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