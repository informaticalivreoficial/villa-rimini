@extends('adminlte::page')

@section('title', "Editar Reserva")

@php
$config = [
    "height" => "300",
    "fontSizes" => ['8', '9', '10', '11', '12', '14', '18'],
    "lang" => 'pt-BR',
    "toolbar" => [
        // [groupName, [list of button]]
        ['style', ['style']],
        ['fontname', ['fontname']],
        ['fontsize', ['fontsize']],
        ['style', ['bold', 'italic', 'underline', 'clear']],
        //['font', ['strikethrough', 'superscript', 'subscript']],        
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['height', ['height']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video','hr']],
        ['view', ['fullscreen', 'codeview']],
    ],
]
@endphp

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Editar Reserva</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Painel de Controle</a></li>
            <li class="breadcrumb-item"><a href="{{route('reservas.pendentes')}}">Reservas</a></li>
            <li class="breadcrumb-item active">Editar Reserva</li>
        </ol>
    </div>
</div>
@stop

@section('content')
    
        <div class="row">
            <div class="col-12">
                @if($errors->all())
                     @foreach($errors->all() as $error)
                         @message(['color' => 'danger'])
                         {{ $error }}
                         @endmessage
                     @endforeach
                 @endif 
         
                 @if(session()->exists('message'))
                     @message(['color' => session()->get('color')])
                     {{ session()->get('message') }}
                     @endmessage
                 @endif
             </div>            
        </div>
        <form action="{{ route('reservas.update', ['id' => $reserva->id]) }}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="row">            
            <div class="col-12">
                <div class="card card-teal card-outline card-outline-tabs">
                    
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Dados Cadastrais</a>
                            </li>                                                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-four-tabContent">
                            <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                                <div class="row mb-2 text-muted">    
                                    <div class="col-sm-12 text-muted">
                                        <div class="form-group">
                                            <h5><b>Código da reserva: </b> {{$reserva->codigo}}</h5>                                         
                                        </div>
                                    </div>
                                    <hr>                               
                                    <div class="col-12 col-md-4 col-lg-4">   
                                        <div class="form-group">
                                            <label class="labelforms"><b>*Cliente</b></label>
                                            <input type="hidden" class="form-control" name="cliente" value="{{$reserva->userObject->id}}">
                                            <input type="text" class="form-control" name="cliente" value="{{$reserva->userObject->name}}" disabled>
                                        </div>                                                    
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-4">   
                                        <div class="form-group">
                                            <label class="labelforms"><b>Apartamento</b></label>
                                            <select class="form-control" name="apartamento">                                                
                                                @if (!empty($apartamentos) && $apartamentos->count() > 0)
                                                    @foreach($apartamentos as $apartamento)
                                                        <option value="{{ $apartamento->id }}" {{ (old('apartamento') == $apartamento->id ? 'selected' : '') }}>{{ $apartamento->titulo }}</option>
                                                    @endforeach
                                                @else
                                                    <option value="">Cadastre um Apartamento</option>
                                                @endif                                                
                                            </select>
                                        </div>                                                    
                                    </div>                                                                      
                                    <div class="col-12 col-md-4 col-lg-4"> 
                                        <div class="form-group">
                                            <label class="labelforms"><b>Status</b></label>
                                            <select name="status" class="form-control">
                                                <option value="1" {{ (old('status') == '1' ? 'selected' : ($reserva->status == 1 ? 'selected' : '')) }}>Pendente</option>
                                                <option value="0" {{ (old('status') == '0' ? 'selected' : ($reserva->status == 0 ? 'selected' : '')) }}>Finalizado</option>
                                            </select>
                                        </div>
                                    </div>
                                </div> 
                                <div class="row mb-2 text-muted">                                   
                                    <div class="col-12 col-md-4 col-lg-3">   
                                        <div class="form-group">
                                            <label class="labelforms"><b>Check in</b></label>
                                            <div class="input-group">
                                                <input type="text" id="check" class="form-control datepicker-here" data-language='pt-BR' name="checkin" value="{{ old('checkin') ?? $reserva->checkin }}"/>
                                                <div class="input-group-append">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>                                                    
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3">   
                                        <div class="form-group">
                                            <label class="labelforms"><b>Check out</b></label>
                                            <div class="input-group">
                                                <input type="text" id="check" class="form-control datepicker-here" data-language='pt-BR' name="checkout" value="{{ old('checkout') ?? $reserva->checkout }}"/>
                                                <div class="input-group-append">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>                                                    
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3">   
                                        <div class="form-group">
                                            <label class="labelforms"><b>Adultos</b></label>
                                            <select class="form-control" name="adultos">                                                
                                                @for($i = 1; $i <= 5; $i++)
                                                    <option value="{{$i}}" {{(!empty($reserva->adultos) && $i == $reserva->adultos ? 'selected' : ($i == 1 ? 'selected' : ''))}}>
                                                        {{ $i }}
                                                    </option>
                                                @endfor                                               
                                            </select>
                                        </div>                                                    
                                    </div>                                                                      
                                    <div class="col-12 col-md-4 col-lg-3">   
                                        <div class="form-group">
                                            <label class="labelforms"><b>Crianças de 0 a 5</b></label>
                                            <select class="form-control" name="criancas_0_5">                                                
                                                @for($i = 0; $i <= 5; $i++)
                                                    <option value="{{$i}}" {{(!empty($reserva->criancas_0_5) && $i == $reserva->criancas_0_5 ? 'selected' : ($i == 0 ? 'selected' : ''))}}>
                                                        {{ $i }}
                                                    </option>
                                                @endfor                                               
                                            </select>
                                        </div>                                                    
                                    </div> 
                                </div>                             
                                
                            </div>
                            
                        </div>
                        <div class="row text-right">
                            <div class="col-12 mb-4">
                                <button type="submit" class="btn btn-lg btn-success"><i class="nav-icon fas fa-check mr-2"></i> Atualizar Agora</button>
                            </div>
                        </div>  
                                                
                        </form>

                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
                                
        
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
        var $money = $(".mask-money");
        $money.mask('R$ 000.000.000.000.000,00', {reverse: true, placeholder: "R$ 0,00"});
    });

    $(function() {
        $('#check').datepicker({
            dateFormat: 'mm/dd/yyyy',
            minDate: new Date() // Now can select only dates, which goes after today
        })
    });
    
</script> 
@endsection