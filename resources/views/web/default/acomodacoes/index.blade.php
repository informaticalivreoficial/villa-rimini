@extends("web.{$configuracoes->template}.master.master")

@section('content')
<main class="site-main page-spacing">
	
    <!-- Page Banner -->
    <div class="container-fluid page-banner about-banner" style="height:auto;background-color:#3B4C76 !important;padding-top: 200px;">
        <div class="container">
            <h3 style="color: #fff;">Acomodações</h3>
            <ol class="breadcrumb">
                <li><a style="color: #fff;" href="{{route('web.home')}}">Início</a></li>
                <li class="active">Acomodações</li>
            </ol>
        </div>
    </div>
    
    
    <div id="accmmodation-section" class="accmmodation-section container-fluid no-padding">        
        <div class="section-padding"></div>        
        <div class="container">
            <div class="accmmodation-carousel">                            
                @if (!empty($acomodacoes) && $acomodacoes->count() > 0)
                    @foreach ($acomodacoes as $apartamento)
                        <div class="accmmodation-box" style="height:360px;">
                            <img style="max-height: 178px;" src="{{$apartamento->cover()}}" alt="{{$apartamento->titulo}}">
                            
                            <h3>{{$apartamento->titulo}}</h3>
                            @if ($apartamento->exibir_valores == 1)
                                <h2><span>R$</span>'.number_format($apartamento['valor'], 2 , ',' , '.').'</h2>
                            @endif
                            
                            @if ($apartamento->descricao)
                                {{$apartamento->descricao}}
                            @else
                                <p><br /><br /></p>
                            @endif 
                            
                            <div class="price-detail">                    								
                                <a style="bottom: 0;right:0;position: absolute;" class="read-more" title="{{$apartamento->titulo}}" href="{{route('web.acomodacao', ['slug' => $apartamento->slug])}}">+ Detalhes <i class="fa fa-long-arrow-right"></i></a>
                            </div>	
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="section-padding"></div>
    </div>

</main>
@endsection

@section('css')
    
@endsection

@section('js')
    
@endsection