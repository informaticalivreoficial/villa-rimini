@extends("web.{$configuracoes->template}.master.master")

@section('content')
<main class="site-main page-spacing">
	<!-- Page Banner -->
	<div class="container-fluid page-banner about-banner" style="background-color:#3B4C76 !important;">
		<div class="container">
			<h3 style="color: #fff;">Atendimento</h3>
			<ol class="breadcrumb">
				<li><a style="color: #fff;" href="{{route('web.home')}}">Início</a></li>
				<li class="active">Atendimento</li>
			</ol>
		</div>
	</div><!-- Page Banner /- -->	
	<!-- Container -->
	<div class="container" style="margin-top: 30px;">
		<div class="row">			
			<!-- Contact Content -->
			<div class="col-md-7 col-sm-7 col-xs-12 contact-content">
				<h4>Preencha o Formulário</h4>
				<form method="post" action="" name="atendimento" class="contact-form j_formsubmit" autocomplete="off">
                    @csrf
                    <div id="js-contact-result"></div>
                    <!-- HONEYPOT -->
                    <input type="hidden" class="noclear" name="bairro" value="" />
                    <input type="text" class="noclear" style="display: none;" name="cidade" value="" />
                    <div class="form_hide">
    					<div class="form-group">
    						<input type="text" class="form-control" id="input_name" name="nome" placeholder="Seu Nome" value="" />
    					</div>
    					<div class="form-group">
    						<input type="email" class="form-control" id="input_email" name="email" placeholder="E-mail" value="" />
    					</div>
    					<div class="form-group">
    						<textarea id="textarea_message" class="form-control" rows="5" name="mensagem" placeholder="Digite sua mensagem"></textarea>
    					</div>
    					<div class="form-group">
    						<input id="js-contact-btn" class="noclear" type="submit" value="Enviar Agora" />
    					</div> 
                    </div>
				</form>
			</div>
			<!-- Contact Content -->
			<div class="col-md-5 col-sm-5 col-xs-12 contact-content">
				<h4>Atendimento</h4>
				<div class="contact-detail">
					<i class="fa fa-map-marker"></i>
					<h5>Endereço</h5>
					<p>
                        @if($configuracoes->rua)	
                            {{$configuracoes->rua}}
                            @if($configuracoes->num)
                            , {{$configuracoes->num}}
                            @endif
                            @if($configuracoes->bairro)
                            , {{$configuracoes->bairro}}
                            @endif
                            @if($configuracoes->cidade)  
                            - {{\App\Helpers\Cidade::getCidadeNome($configuracoes->cidade, 'cidades')}}
                            @endif
                        @endif
                    </p>
				</div>
				<div class="contact-detail">
					<i class="fa fa-phone"></i>
					<h5>Telefones</h5>
					<p>
                        @if ($configuracoes->telefone1)
                            <span>
                                <a href="tel:{{\App\Helpers\Renato::limpatelefone($configuracoes->telefone1)}}">{{$configuracoes->telefone1}}</a>
                            </span>                        
                        @endif   
                        @if ($configuracoes->telefone2)
                            <span>
                                <a href="tel:{{\App\Helpers\Renato::limpatelefone($configuracoes->telefone2)}}">{{$configuracoes->telefone2}}</a>
                            </span>                        
                        @endif   
                        @if ($configuracoes->telefone3)
                            <span>
                                <a href="tel:{{\App\Helpers\Renato::limpatelefone($configuracoes->telefone3)}}">{{$configuracoes->telefone3}}</a>
                            </span>                        
                        @endif   
                        @if ($configuracoes->whatsapp)
                            <span>
                                <a href="{{\App\Helpers\WhatsApp::getNumZap($configuracoes->whatsapp ,'Atendimento '.$configuracoes->nomedosite)}}">WhatsApp: {{$configuracoes->whatsapp}}</a>
                            </span>                        
                        @endif
                    </p>
				</div>
				<div class="contact-detail">
					<i class="fa fa-envelope"></i>
					<h5>E-mail</h5>
					<p>
                        @if ($configuracoes->email)
                            <a href="mailto:{{$configuracoes->email}}" title="{{$configuracoes->email}}">{{$configuracoes->email}}</a>
                        @endif                                      
                        @if ($configuracoes->email1)
                            <a href="mailto:{{$configuracoes->email1}}" title="{{$configuracoes->email1}}">{{$configuracoes->email1}}</a>
                        @endif                                      
                    </p>
				</div>
				<ul>
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
			</div>
		</div>			
	</div>
	<div class="section-padding"></div>
</main>

@endsection

@section('js')
<script>
    $(function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Seletor, Evento/efeitos, CallBack, Ação
        $('.j_formsubmit').submit(function (){
            var form = $(this);
            var dataString = $(form).serialize();

            $.ajax({
                url: "{{ route('web.sendEmail') }}",
                data: dataString,
                type: 'GET',
                dataType: 'JSON',
                beforeSend: function(){
                    form.find("#js-contact-btn").attr("disabled", true);
                    form.find('#js-contact-btn').val("Carregando...");                
                    form.find('.alert').fadeOut(500, function(){
                        $(this).remove();
                    });
                },
                success: function(resposta){
                    $('html, body').animate({scrollTop:$('#js-contact-result').offset().top-100}, 'slow');
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
                    form.find('#js-contact-btn').val("Enviar Agora");                                
                }
            });

            return false;
        });

    });
</script>   
@endsection