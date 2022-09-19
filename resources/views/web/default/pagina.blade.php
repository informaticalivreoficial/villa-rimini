@extends("web.{$configuracoes->template}.master.master")

@section('content')
<main class="site-main page-spacing">
    <!-- Page Banner -->
    <div class="container-fluid page-banner about-banner" style="background-image: url({{$post->cover()}});">
        <div class="container" style="margin-top: -20px !important;">
            <h3 style="color: #fff;">{{$post->titulo}}</h3>
            <ol class="breadcrumb">
                <li><a style="color: #fff;" href="{{route('web.home')}}">Início</a></li>
                <li class="active">{{$post->titulo}}</li>
            </ol>
        </div>
    </div><!-- Page Banner /- -->
    
    <div class="section-padding"></div>
    <!-- container -->
    <div class="container">		
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">                    
                    {!!$post->content!!}									
                    <br />

                    @if($post->images()->get()->count()) 
                        <div id="booking-carousel" class="carousel slide booking-carousel" data-ride="carousel">  
                            <div class="carousel-inner" role="listbox">
                                @foreach($post->images()->get() as $key => $image)
                                    <div class="item{{($key == 1 ? ' active' : '')}}">
                                        <img src="{{ $image->url_image }}" alt="{{ $image->url_image }}" />
                                    </div>
                                @endforeach
                            </div>                      
                            <a class="left carousel-control" href="#booking-carousel" role="button" data-slide="prev">
                                <span class="fa fa-caret-left" aria-hidden="true"></span>
                            </a>
                            <a class="right carousel-control" href="#booking-carousel" role="button" data-slide="next">
                                <span class="fa fa-caret-right" aria-hidden="true"></span>
                            </a>
                        </div>                                                   
                    @endif                                   
                </div>
            </div>
        </div>
    </div><!-- container /- -->
    <div class="section-padding"></div>
    
</main>
@endsection

@section('css')
    
@endsection

@section('js')
    
@endsection