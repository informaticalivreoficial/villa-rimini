@extends("web.{$configuracoes->template}.master.master")

@section('content')
<main class="site-main page-spacing">
	
	<div class="container-fluid page-banner about-banner" style="background-color:#3B4C76 !important;">
		<div class="container">
			<h3 style="color: #fff;">Questionário de Avaliação</h3>
			<ol class="breadcrumb">
				<li><a style="color: #fff;" href="{{route('web.home')}}">Início</a></li>
				<li class="active">Questionário de Avaliação</li>
			</ol>
		</div>
	</div>
	
	<div class="container" style="margin-top: 30px;">
		<div class="row booking-section2">			
			<!-- Contact Content -->
			<div class="col-xs-12 booking-form2 container-fluid">
				<p class="form_hide" style="margin: 30px 10px 10px 10px;">Nós valorizamos a seu feedback. Por favor avalie a sua experiência e ajude-nos a melhorar os nossos serviços.</p>
				<form method="post" action="" class="j_formsubmit" autocomplete="off">
                    @csrf
                    <div id="js-contact-result"></div>
                    <!-- HONEYPOT -->
                    <input type="hidden" class="noclear" name="bairro" value="" />
                    <input type="text" class="noclear" style="display: none;" name="cidade" value="" />
                    <div class="form_hide">
    					<div class="form-group col-lg-4">
                            <label>*Nome</label>
    						<input type="text" class="form-control" name="name" value="" />
    					</div>
    					<div class="form-group col-lg-4">
                            <label>Email</label>
    						<input type="email" class="form-control" name="email" value="" />
    					</div>
                        <div class="form-group col-lg-4">
                            <label>*Check-out</label>
                            <i class="fa fa-calendar-minus-o"></i>
                            <input type="text" class="form-control datepicker-here" name="checkout" value="" data-language='pt-BR'/>
    					</div>
                        <div class="col-lg-12">
							<hr>
                            <h3>Quarto</h3>
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label>Limpeza</label> 
									<div class="form-check">
										<input id="exibir_excelente1" class="form-check-input" type="radio" value="4" name="questao_1" {{(old('questao_1') == '4' ? 'checked' : '')}}>
										<label for="exibir_excelente1" class="form-check-label mr-5">Excelente</label>
										<input id="exibir_bom1" class="form-check-input" type="radio" value="3" name="questao_1" {{(old('questao_1') == '3' ? 'checked' : '')}}>
										<label for="exibir_bom1" class="form-check-label mr-5">Bom</label>
										<input id="exibir_suficiente1" class="form-check-input" type="radio" value="2" name="questao_1" {{(old('questao_1') == '2' ? 'checked' : '')}}>
										<label for="exibir_suficiente1" class="form-check-label">Suficiente</label>
										<input id="exibir_ruim1" class="form-check-input" type="radio" value="1" name="questao_1" {{(old('questao_1') == '1' ? 'checked' : '')}}>
										<label for="exibir_ruim1" class="form-check-label">Ruim</label>
										<input id="exibir_pessimo1" class="form-check-input" type="radio" value="0" name="questao_1" {{(old('questao_1') == '0' ? 'checked' : '')}}>
										<label for="exibir_pessimo1" class="form-check-label">Péssimo</label>
									</div>                                   
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>Limpeza Banheiro</label> 
									<div class="form-check">
										<input id="exibir_excelente2" class="form-check-input" type="radio" value="4" name="questao_2" {{(old('questao_2') == '4' ? 'checked' : '')}}>
										<label for="exibir_excelente2" class="form-check-label mr-5">Excelente</label>
										<input id="exibir_bom2" class="form-check-input" type="radio" value="3" name="questao_2" {{(old('questao_2') == '3' ? 'checked' : '')}}>
										<label for="exibir_bom2" class="form-check-label mr-5">Bom</label>
										<input id="exibir_suficiente2" class="form-check-input" type="radio" value="2" name="questao_2" {{(old('questao_2') == '2' ? 'checked' : '')}}>
										<label for="exibir_suficiente2" class="form-check-label">Suficiente</label>
										<input id="exibir_ruim2" class="form-check-input" type="radio" value="1" name="questao_2" {{(old('questao_2') == '1' ? 'checked' : '')}}>
										<label for="exibir_ruim2" class="form-check-label">Ruim</label>
										<input id="exibir_pessimo2" class="form-check-input" type="radio" value="0" name="questao_2" {{(old('questao_2') == '0' ? 'checked' : '')}}>
										<label for="exibir_pessimo2" class="form-check-label">Péssimo</label>
									</div>                                   
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>Geral</label> 
									<div class="form-check">
										<input id="exibir_excelente3" class="form-check-input" type="radio" value="4" name="questao_3" {{(old('questao_3') == '4' ? 'checked' : '')}}>
										<label for="exibir_excelente3" class="form-check-label mr-5">Excelente</label>
										<input id="exibir_bom3" class="form-check-input" type="radio" value="3" name="questao_3" {{(old('questao_3') == '3' ? 'checked' : '')}}>
										<label for="exibir_bom3" class="form-check-label mr-5">Bom</label>
										<input id="exibir_suficiente3" class="form-check-input" type="radio" value="2" name="questao_3" {{(old('questao_3') == '2' ? 'checked' : '')}}>
										<label for="exibir_suficiente3" class="form-check-label">Suficiente</label>
										<input id="exibir_ruim3" class="form-check-input" type="radio" value="1" name="questao_3" {{(old('questao_3') == '1' ? 'checked' : '')}}>
										<label for="exibir_ruim3" class="form-check-label">Ruim</label>
										<input id="exibir_pessimo3" class="form-check-input" type="radio" value="0" name="questao_3" {{(old('questao_3') == '0' ? 'checked' : '')}}>
										<label for="exibir_pessimo3" class="form-check-label">Péssimo</label>
									</div>                                   
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
							<hr>
                            <h3>Colaboradores</h3>
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label>Eficiência</label> 
									<div class="form-check">
										<input id="exibir_excelente4" class="form-check-input" type="radio" value="4" name="questao_4" {{(old('questao_4') == '4' ? 'checked' : '')}}>
										<label for="exibir_excelente4" class="form-check-label mr-5">Excelente</label>
										<input id="exibir_bom4" class="form-check-input" type="radio" value="3" name="questao_4" {{(old('questao_4') == '3' ? 'checked' : '')}}>
										<label for="exibir_bom4" class="form-check-label mr-5">Bom</label>
										<input id="exibir_suficiente4" class="form-check-input" type="radio" value="2" name="questao_4" {{(old('questao_4') == '2' ? 'checked' : '')}}>
										<label for="exibir_suficiente4" class="form-check-label">Suficiente</label>
										<input id="exibir_ruim4" class="form-check-input" type="radio" value="1" name="questao_4" {{(old('questao_4') == '1' ? 'checked' : '')}}>
										<label for="exibir_ruim4" class="form-check-label">Ruim</label>
										<input id="exibir_pessimo4" class="form-check-input" type="radio" value="0" name="questao_4" {{(old('questao_4') == '0' ? 'checked' : '')}}>
										<label for="exibir_pessimo4" class="form-check-label">Péssimo</label>
									</div>                                   
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>Simpatia</label> 
									<div class="form-check">
										<input id="exibir_excelente5" class="form-check-input" type="radio" value="4" name="questao_5" {{(old('questao_5') == '4' ? 'checked' : '')}}>
										<label for="exibir_excelente5" class="form-check-label mr-5">Excelente</label>
										<input id="exibir_bom5" class="form-check-input" type="radio" value="3" name="questao_5" {{(old('questao_5') == '3' ? 'checked' : '')}}>
										<label for="exibir_bom5" class="form-check-label mr-5">Bom</label>
										<input id="exibir_suficiente5" class="form-check-input" type="radio" value="2" name="questao_5" {{(old('questao_5') == '2' ? 'checked' : '')}}>
										<label for="exibir_suficiente5" class="form-check-label">Suficiente</label>
										<input id="exibir_ruim5" class="form-check-input" type="radio" value="1" name="questao_5" {{(old('questao_5') == '1' ? 'checked' : '')}}>
										<label for="exibir_ruim5" class="form-check-label">Ruim</label>
										<input id="exibir_pessimo5" class="form-check-input" type="radio" value="0" name="questao_5" {{(old('questao_5') == '0' ? 'checked' : '')}}>
										<label for="exibir_pessimo5" class="form-check-label">Péssimo</label>
									</div>                                   
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>Cortesia</label> 
									<div class="form-check">
										<input id="exibir_excelente6" class="form-check-input" type="radio" value="4" name="questao_6" {{(old('questao_6') == '4' ? 'checked' : '')}}>
										<label for="exibir_excelente6" class="form-check-label mr-5">Excelente</label>
										<input id="exibir_bom6" class="form-check-input" type="radio" value="3" name="questao_6" {{(old('questao_6') == '3' ? 'checked' : '')}}>
										<label for="exibir_bom6" class="form-check-label mr-5">Bom</label>
										<input id="exibir_suficiente6" class="form-check-input" type="radio" value="2" name="questao_6" {{(old('questao_6') == '2' ? 'checked' : '')}}>
										<label for="exibir_suficiente6" class="form-check-label">Suficiente</label>
										<input id="exibir_ruim6" class="form-check-input" type="radio" value="1" name="questao_6" {{(old('questao_6') == '1' ? 'checked' : '')}}>
										<label for="exibir_ruim6" class="form-check-label">Ruim</label>
										<input id="exibir_pessimo6" class="form-check-input" type="radio" value="0" name="questao_6" {{(old('questao_6') == '0' ? 'checked' : '')}}>
										<label for="exibir_pessimo6" class="form-check-label">Péssimo</label>
									</div>                                   
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
							<hr>
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label>Estava tudo em bom funcionamento no seu quarto?</label> 
									<div class="form-check">
										<input id="exibir_sim7" class="form-check-input" type="radio" value="1" name="questao_7" {{(old('questao_7') == '1' ? 'checked' : '')}}>
										<label for="exibir_sim7" class="form-check-label mr-5">Sim</label>
										<input id="exibir_nao7" class="form-check-input" type="radio" value="0" name="questao_7" {{(old('questao_7') == '0' ? 'checked' : '')}}>
										<label for="exibir_nao7" class="form-check-label mr-5">Não</label>										
									</div>  
									<label style="padding-top: 20px;">Se colocou “Não”, explique o que precisava ser reparado.</label> 
									<textarea id="textarea_message" class="form-control" rows="5" name="questao_7_content"></textarea>  
									<label style="padding-top: 20px;">O responsável da manutenção foi eficiente?</label>     
									<div class="form-check">
										<input id="exibir_sim8" class="form-check-input" type="radio" value="1" name="questao_8" {{(old('questao_8') == '1' ? 'checked' : '')}}>
										<label for="exibir_sim8" class="form-check-label mr-5">Sim</label>
										<input id="exibir_nao8" class="form-check-input" type="radio" value="0" name="questao_8" {{(old('questao_8') == '0' ? 'checked' : '')}}>
										<label for="exibir_nao8" class="form-check-label mr-5">Não</label>										
									</div>                         
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>Ficaria novamente no hotel?</label> 
									<div class="form-check">
										<input id="exibir_sim9" class="form-check-input" type="radio" value="1" name="questao_9" {{(old('questao_9') == '1' ? 'checked' : '')}}>
										<label for="exibir_sim9" class="form-check-label mr-5">Sim</label>
										<input id="exibir_nao9" class="form-check-input" type="radio" value="0" name="questao_9" {{(old('questao_9') == '0' ? 'checked' : '')}}>
										<label for="exibir_nao9" class="form-check-label mr-5">Não</label>										
									</div>  
									<label style="padding-top: 20px;">Recomendaria o nosso hotel?</label>    
									<div class="form-check">
										<input id="exibir_sim10" class="form-check-input" type="radio" value="1" name="questao_10" {{(old('questao_10') == '1' ? 'checked' : '')}}>
										<label for="exibir_sim10" class="form-check-label mr-5">Sim</label>
										<input id="exibir_nao10" class="form-check-input" type="radio" value="0" name="questao_10" {{(old('questao_10') == '0' ? 'checked' : '')}}>
										<label for="exibir_nao10" class="form-check-label mr-5">Não</label>										
									</div>                            
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>Como efetou a sua reserva?</label> 
									<select name="questao_11" class="form-control">
										<option value="---">---</option>                                
										<option value="Website do Hotel">Website do Hotel</option>                                
										<option value="Agência de Viagem">Agência de Viagem</option>                                
										<option value="Booking.com">Booking.com</option>                                
										<option value="Trivago">Trivago</option>                                
										<option value="Hoteis.com">Hoteis.com</option>                                
										<option value="Expedia">Expedia</option>                                
										<option value="Kayak">Kayak</option>                                
										<option value="Outros">Outros</option>                                
									 </select>                                   
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-12">
                            <label>Gostaria de deixar um depoimento?</label>
    						<textarea id="textarea_message" class="form-control" rows="5" name="content" placeholder="Digite seu depoimento"></textarea>
    					</div>                        
    					<div class="form-group col-lg-12">
    						<input class="read-more noclear" id="js-contact-btn" type="submit" value="Enviar Agora" />
    					</div> 
                    </div>
				</form>
			</div>
			
		</div>			
	</div>
	<div class="section-padding"></div>
</main>
@endsection

@section('css')
<style>
	.text-left{
		text-align: left;
	}
</style>
<link href="{{url(asset('backend/plugins/airdatepicker/css/datepicker.min.css'))}}" rel="stylesheet" type="text/css">
@endsection

@section('js')
<script src="{{url(asset('backend/plugins/airdatepicker/js/datepicker.min.js'))}}"></script>
<script src="{{url(asset('backend/plugins/airdatepicker/js/i18n/datepicker.pt-BR.js'))}}"></script>

<script>
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
				url: "{{ route('web.avaliacaoSend') }}",
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