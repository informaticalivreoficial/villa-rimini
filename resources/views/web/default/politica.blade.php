@extends("web.{$configuracoes->template}.master.master")

@section('content')
<main class="site-main page-spacing">   
    <div class="container-fluid page-banner about-banner" style="background-color:#3B4C76 !important;">
        <div class="container">
            <h3 style="color: #fff;">Política de Privacidade</h3>
            <ol class="breadcrumb">
                <li><a style="color: #fff;" href="{{route('web.home')}}">Início</a></li>
                <li class="active">Política de Privacidade</li>
            </ol>
        </div>
    </div>
    
    <div class="section-padding"></div>
    
    <div class="container">		
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">                    
                    {!! $configuracoes->politicas_de_privacidade !!}								
                    <br />                           
                </div>
            </div>
        </div>
    </div>
    <div class="section-padding"></div>    
</main>
@endsection