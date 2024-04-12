@extends('adminlte::page')

@section('title', "Cadastrar Apartamento")

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
        <h1>Cadastrar Apartamento</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Painel de Controle</a></li>
            <li class="breadcrumb-item"><a href="{{route('apartamentos.index')}}">Apartamentos</a></li>
            <li class="breadcrumb-item active">Cadastrar Apartamento</li>
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
            </div>            
        </div>
        <form action="{{ route('apartamentos.store') }}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <div class="row">            
            <div class="col-12">
                <div class="card card-teal card-outline card-outline-tabs">
                    
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Dados Cadastrais</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">Estrutura</a>
                            </li>                            
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-four-settings-tab" data-toggle="pill" href="#custom-tabs-four-settings" role="tab" aria-controls="custom-tabs-four-settings" aria-selected="false">Fotos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-four-seo-tab" data-toggle="pill" href="#custom-tabs-four-seo" role="tab" aria-controls="custom-tabs-four-seo" aria-selected="false">Seo</a>
                            </li>                            
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-four-tabContent">
                            <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                                <div class="row mb-2 text-muted">                                   
                                    <div class="col-12 col-md-4 col-lg-4">   
                                        <div class="form-group">
                                            <label class="labelforms"><b>*Título</b></label>
                                            <input type="text" class="form-control" name="titulo" value="{{old('titulo')}}">
                                        </div>                                                    
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-4">   
                                        <div class="form-group">
                                            <label class="labelforms"><b>Leitos</b></label>
                                            <input type="text" class="form-control" name="dormitorios" value="{{old('dormitorios')}}">
                                        </div>                                                    
                                    </div>                                                                      
                                    <div class="col-12 col-md-4 col-lg-4"> 
                                        <div class="form-group">
                                            <label class="labelforms"><b>Deseja exibir na Home?</b></label>
                                            <div class="form-check">
                                                <input id="exibir_homesim" class="form-check-input" type="radio" value="1" name="exibir_home" {{(old('exibir_home') == '1' ? 'checked' : '')}}>
                                                <label for="exibir_homesim" class="form-check-label mr-5">Sim</label>
                                                <input id="exibir_homenao" class="form-check-input" type="radio" value="0" name="exibir_home" {{(old('exibir_home') == '0' ? 'checked' : '')}}>
                                                <label for="exibir_homenao" class="form-check-label">Não</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>                              
                                <div id="accordion">
                                    <!-- we are adding the .class so bootstrap.js collapse plugin detects it -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>                          
                                                <a style="border:none;color: #555;" data-toggle="collapse" data-parent="#accordion" href="#collapseValores">
                                                    <i class="nav-icon fas fa-plus mr-2"></i> Precificação e Valores
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseValores" class="panel-collapse collapse show">
                                            <div class="card-body">
                                                <div class="row mb-2">
                                                    <div class="col-12"> 
                                                        <div class="form-group">
                                                            <label class="labelforms"><b>Deseja exibir os valores?</b> <small class="text-info">(valores exibidos no layout do cliente)</small></label>
                                                            <div class="form-check">
                                                                <input id="exibivaloresim" class="form-check-input" type="radio" value="1" name="exibir_valores" {{(old('exibir_valores') == '1' ? 'checked' : '')}}>
                                                                <label for="exibivaloressim" class="form-check-label mr-5">Sim</label>
                                                                <input id="exibivaloresnao" class="form-check-input" type="radio" value="0" name="exibir_valores" {{(old('exibir_valores') == '0' ? 'checked' : '')}}>
                                                                <label for="exibivaloresnao" class="form-check-label">Não</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-3 col-lg-3"> 
                                                        <div class="form-group">
                                                            <label class="labelforms"><b>Valor c/ Café da Manhã</b></label>
                                                            <input type="text" class="form-control mask-money" name="valor_cafe" value="{{old('valor_cafe')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-3 col-lg-3"> 
                                                        <div class="form-group">
                                                            <label class="labelforms"><b>Valor c/ Café da Manhã & Almoço</b></label>
                                                            <input type="text" class="form-control mask-money" name="valor_cafe_almoco" value="{{old('valor_cafe_almoco')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-3 col-lg-3"> 
                                                        <div class="form-group">
                                                            <label class="labelforms"><b>Valor com Café & Janta</b></label>
                                                            <input type="text" class="form-control mask-money" name="valor_cafe_janta" value="{{old('valor_cafe_janta')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-3 col-lg-3"> 
                                                        <div class="form-group">
                                                            <label class="labelforms"><b>Valor 0 a 5 anos</b></label>
                                                            <input type="text" class="form-control mask-money" name="valor_cri_0_5" value="{{old('valor_cri_0_5')}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>
                                                <a style="border:none;color: #555;" data-toggle="collapse" data-parent="#accordion" href="#collapseCaracteristicas">
                                                    <i class="nav-icon fas fa-plus mr-2"></i> Características

                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseCaracteristicas" class="panel-collapse collapse show">
                                            <div class="card-body">                                                
                                                <div class="row mb-2">
                                                    <div class="col-12">   
                                                        <label class="labelforms"><b>Descrição do Apartamento</b></label>
                                                        <x-adminlte-text-editor name="descricao" v placeholder="Descrição do Apartamento..." :config="$config">{{ old('descricao') }}</x-adminlte-text-editor>                                                      
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-12">   
                                                        <label class="labelforms"><b>Notas Adicionais</b></label>
                                                        <textarea id="inputDescription" class="form-control" rows="5" name="notasadicionais">{{ old('notasadicionais') ?? 'Os valores podem ser alterados sem aviso prévio.'}}</textarea>                                                      
                                                    </div>
                                                </div>                                                
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            
                            <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                                <div class="row">
                                    <h4>Estrutura</h4>
                                </div>
                                <div class="row mb-4">                                     
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">                                        
                                        <!-- checkbox -->
                                        <div class="form-group p-3 mb-1">
                                            <div class="form-check mb-2">
                                                <input id="ar_condicionado" class="form-check-input" type="checkbox" name="ar_condicionado" {{ (old('ar_condicionado') == 'on' || old('ar_condicionado') == true ? 'checked' : '' ) }}>
                                                <label for="ar_condicionado" class="form-check-label">Ar Condicionado</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input id="cafe_manha" class="form-check-input" type="checkbox" name="cafe_manha" {{ (old('cafe_manha') == 'on' || old('cafe_manha') == true ? 'checked' : '') }}>
                                                <label for="cafe_manha" class="form-check-label">Café da manhã</label>
                                            </div>                                                                     
                                            <div class="form-check mb-2">
                                                <input id="cofre_individual" class="form-check-input" type="checkbox" name="cofre_individual" {{ (old('cofre_individual') == 'on' || old('cofre_individual') == true ? 'checked' : '') }}>
                                                <label for="cofre_individual" class="form-check-label">Cofre Individual</label>
                                            </div>                                                                     
                                            <div class="form-check mb-2">
                                                <input id="frigobar" class="form-check-input" type="checkbox" name="frigobar" {{ (old('frigobar') == 'on' || old('frigobar') == true ? 'checked' : '') }}>
                                                <label for="frigobar" class="form-check-label">Frigobar</label>
                                            </div>                                                                     
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">                                        
                                        <!-- checkbox -->
                                        <div class="form-group p-3 mb-1">  
                                            <div class="form-check mb-2">
                                                <input id="servico_quarto" class="form-check-input" type="checkbox"  name="servico_quarto" {{ (old('servico_quarto') == 'on' || old('servico_quarto') == true ? 'checked' : '' ) }}>
                                                <label for="servico_quarto" class="form-check-label">Serviço de Quarto</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input id="telefone" class="form-check-input" type="checkbox"  name="telefone" {{ (old('telefone') == 'on' || old('telefone') == true ? 'checked' : '' ) }}>
                                                <label for="telefone" class="form-check-label">Telefone</label>
                                            </div>                                          
                                            <div class="form-check mb-2">
                                                <input id="estacionamento" class="form-check-input" type="checkbox"  name="estacionamento" {{ (old('estacionamento') == 'on' || old('estacionamento') == true ? 'checked' : '' ) }}>
                                                <label for="estacionamento" class="form-check-label">Estacionamento</label>
                                            </div>                                            
                                            <div class="form-check mb-2">
                                                <input id="espaco_fitness" class="form-check-input" type="checkbox"  name="espaco_fitness" {{ (old('espaco_fitness') == 'on' || old('espaco_fitness') == true ? 'checked' : '' ) }}>
                                                <label for="espaco_fitness" class="form-check-label">Espaço Fitness</label>
                                            </div>                                            
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">                                        
                                        <!-- checkbox -->
                                        <div class="form-group p-3 mb-1">                                            
                                            <div class="form-check mb-2">
                                                <input id="lareira" class="form-check-input" type="checkbox"  name="lareira" {{ (old('lareira') == 'on' || old('lareira') == true ? 'checked' : '') }}>
                                                <label for="lareira" class="form-check-label">Lareira</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input id="elevador" class="form-check-input" type="checkbox"  name="elevador" {{ (old('elevador') == 'on' || old('elevador') == true ? 'checked' : '') }}>
                                                <label for="elevador" class="form-check-label">Elevador</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input id="vista_para_mar" class="form-check-input" type="checkbox"  name="vista_para_mar" {{ (old('vista_para_mar') == 'on' || old('vista_para_mar') == true ? 'checked' : '' ) }}>
                                                <label for="vista_para_mar" class="form-check-label">Vista para o Mar</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input id="ventilador_teto" class="form-check-input" type="checkbox"  name="ventilador_teto" {{ (old('ventilador_teto') == 'on' || old('ventilador_teto') == true ? 'checked' : '' ) }}>
                                                <label for="ventilador_teto" class="form-check-label">Ventilador de Teto</label>
                                            </div>                                                                                        
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">                                        
                                        <!-- checkbox -->
                                        <div class="form-group p-3 mb-1"> 
                                            <div class="form-check mb-2">
                                                <input id="wifi" class="form-check-input" type="checkbox"  name="wifi" {{ (old('wifi') == 'on' || old('wifi') == true ? 'checked' : '' ) }}>
                                                <label for="wifi" class="form-check-label">Wifi</label>
                                            </div>                                                
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="tab-pane fade" id="custom-tabs-four-settings" role="tabpanel" aria-labelledby="custom-tabs-four-settings-tab">
                                <div class="row mb-4">                                   
                                    <div class="col-sm-12">                                        
                                        <div class="form-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="exampleInputFile" name="files[]" multiple>
                                                <label class="custom-file-label" for="exampleInputFile">Escolher Fotos</label>
                                            </div>
                                        </div>                                        
                                        <div class="content_image"></div>
                                    </div>
                                </div> 
                            </div>
                            
                            <div class="tab-pane fade" id="custom-tabs-four-seo" role="tabpanel" aria-labelledby="custom-tabs-four-seo-tab">
                                <div class="row mb-2 text-muted">                                   
                                    <div class="col-12 col-md-6 col-lg-6">   
                                        <div class="form-group">
                                            <label class="labelforms"><b>Headline</b></label>
                                            <input type="text" class="form-control" name="headline" value="{{old('headline')}}">
                                        </div>                                                    
                                    </div>                                                                       
                                    <div class="col-12 mb-1"> 
                                        <div class="form-group">
                                            <label class="labelforms"><b>MetaTags</b></label>
                                            <input id="tags_1" class="tags" rows="5" name="metatags" value="{{ old('metatags') }}">
                                        </div>
                                    </div>
                                    <div class="col-12"> 
                                        <div class="form-group">
                                            <label class="labelforms"><b>Youtube Vídeo</b></label>
                                            <textarea id="inputDescription" class="form-control" rows="5" name="youtube_video">{{ old('youtube_video') }}</textarea> 
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            
                        </div>
                        <div class="row text-right">
                            <div class="col-12 mb-4">
                                <button type="submit" class="btn btn-lg btn-success"><i class="nav-icon fas fa-check mr-2"></i> Cadastrar Agora</button>
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
<link rel="stylesheet" href="{{url('backend/plugins/jquery-tags-input/jquery.tagsinput.css')}}" />
<style type="text/css">
    div.tagsinput span.tag {
        background: #65CEA7 !important;
        border-color: #65CEA7;
        color: #fff;
        border-radius: 15px;
        -webkit-border-radius: 15px;
        padding: 3px 10px;
    }
    div.tagsinput span.tag a {
        color: #43886e;    
    }
    /* Lista de ImÃ³veis */
    img {
        max-width: 100%;
    }
    .realty_list_item  {    
        border: 1px solid #F3F3F3;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
    }

    .border-item-imovel{
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        border: 1px solid #F3F3F3;  
        background-color: #F3F3F3;
    }
   
    .property_image, .content_image {
        width: 100%;
        flex-basis: 100%;
        display: flex;
        justify-content: flex-start;
        flex-wrap: wrap;
    }
    .property_image .property_image_item, .content_image .property_image_item {
        flex-basis: calc(25% - 20px) !important;
        margin-bottom: 20px;
        margin-right: 20px;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        position: relative;
    }

    .property_image .property_image_item img, .content_image .property_image_item img {
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
    }
    .property_image .property_image_item .property_image_actions, .content_image .property_image_item .property_image_actions {
        position: absolute;
        top: 10px;
        left: 10px;
    }

    .embed {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
        max-width: 100%;
    }
</style>
@endsection

@section('js')
<!--tags input-->
<script src="{{url('backend/plugins/jquery-tags-input/jquery.tagsinput.js')}}"></script>
<script src="{{url(asset('backend/assets/js/jquery.mask.js'))}}"></script>
<script>
    $(document).ready(function () { 
        var $zipcode = $(".mask-zipcode");
        $zipcode.mask('00.000-000', {reverse: true});
        var $money = $(".mask-money");
        $money.mask('R$ 000.000.000.000.000,00', {reverse: true, placeholder: "R$ 0,00"});
    });
</script> 
<script>
    $(function () {
        
        $('input[name="files[]"]').change(function (files) {

            $('.content_image').text('');

            $.each(files.target.files, function (key, value) {
                var reader = new FileReader();
                reader.onload = function (value) {
                    $('.content_image').append(
                        '<div id="list" class="property_image_item">' +
                        '<div class="embed radius" style="background-image: url(' + value.target.result + '); background-size: cover; background-position: center center;"></div>' +
                        '<div class="property_image_actions">' +
                            '<a href="javascript:void(0)" class="btn btn-danger btn-xs image-remove px-2"><i class="nav-icon fas fa-times"></i> </a>' +
                        '</div>' +
                        '</div>');

                    $('.image-remove1').click(function(){
                        $(this).closest('#list').remove()
                    });
                };
                reader.readAsDataURL(value);
            });
        });
        
        //tag input
        function onAddTag(tag) {
            alert("Adicionar uma Tag: " + tag);
        }
        function onRemoveTag(tag) {
            alert("Remover Tag: " + tag);
        }
        function onChangeTag(input,tag) {
            alert("Changed a tag: " + tag);
        }
        $(function() {
            $('#tags_1').tagsInput({
                width:'auto',
                height:200
            });
        });
    });
</script>
@endsection