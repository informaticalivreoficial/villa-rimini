@extends('adminlte::page')

@section('title', 'Escrever Mensagem')

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
        <h1><i class="fas fa-search mr-2"></i>Escrever Mensagem</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Painel de Controle</a></li>
            <li class="breadcrumb-item active">Escrever Mensagem</li>
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
          
<div class="row">
    <div class="col-12">
        <div class="card card-teal card-outline">
            <form action="{{ route('email.sendEmail') }}" method="post" autocomplete="off" enctype="multipart/form-data">
                @csrf                                           
                <input type="hidden" name="destinatario_nome" value="{{ $destinatario['nome'] ?? '' }}">
                <input type="hidden" name="remetente_nome" value="{{ auth()->user()->name }}">
                <input type="hidden" name="sitename" value="{{ $configuracoes->nomedosite }}">
            <div class="card-header">
                <label class="">Remetente:</label>
                <select class="form-control" name="remetente_email">
                    @if(!empty(auth()->user()->email))
                        <option value="{{ auth()->user()->email }}">{{ auth()->user()->email }}</option>
                    @endif
                    @if(!empty(auth()->user()->email1))
                        <option value="{{ auth()->user()->email1 }}">{{ auth()->user()->email1 }}</option>
                    @endif
                    @if(!empty($configuracoes->email))
                        <option value="{{ $configuracoes->email }}">{{ $configuracoes->email }}</option>
                    @endif
                    @if(!empty($configuracoes->email1))
                        <option value="{{ $configuracoes->email1 }}">{{ $configuracoes->email1 }}</option>
                    @endif
                </select>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="form-group">
                    <label class="">Para:</label>
                    <input type="text" class="form-control" name="destinatario_email" placeholder="Para:" value="{{ $destinatario['email'] ?? '' }}">
                </div>
                <p>
                    <a href="#" class="text-front open_cc">Com Cópia &darr;</a>
                </p>
                <div class="form-group copiapara" style="display: none;">
                    <input class="form-control" name="copiapara" placeholder="Cópia Para:" value="">
                </div>
                <div class="form-group">
                    <input class="form-control" name="assunto" placeholder="Assunto:" value="">
                </div>
                <div class="form-group">
                    <x-adminlte-text-editor name="mensagem" v :config="$config">
                        <p>Olá {{ $destinatario['nome'] ?? '' }},</p> 
                        <p>{{ \App\Helpers\Renato::getPrimeiroNome(auth()->user()->name) }} digite sua mensagem aqui...</p>
                        <p style="font-size:11px;text-align:left;color:#666;margin-top: 40px;line-height:1em !important;">
                        att<br />
                        {{ auth()->user()->name }}
                        <br />
                        @if (auth()->user()->whatsapp)
                            WhatsApp: {{auth()->user()->whatsapp}}
                        @endif
                        <br /> 
                        <b>{{ $configuracoes->nomedosite }}</b><br />
                        {{ $configuracoes->telefone }} {{ $configuracoes->celular }} 
                        @php 
                            if($configuracoes->whatsapp){ 
                                echo '<br />WhatsApp: '.$configuracoes->whatsapp;
                            }
                            if($configuracoes->dominio){
                                echo '<br /><a href="'.$configuracoes->dominio.'">website</a>';
                            }
                            if($configuracoes->facebook){
                                echo ($configuracoes->dominio ? ' - <a href="'.$configuracoes->facebook.'">Facebook</a>' : '<br /><a href="'.$configuracoes->facebook.'">Facebook</a>') ;
                            }
                            if($configuracoes->instagram){
                                echo ($configuracoes->dominio || $configuracoes->facebook ? ' - <a href="'.$configuracoes->instagram.'">Instagram</a>' : '<br /><a href="'.$configuracoes->instagram.'">Instagram</a>') ;
                            }
                            if($configuracoes->linkedin){
                                echo ($configuracoes->dominio || $configuracoes->facebook || $configuracoes->instagram ? ' - <a href="'.$configuracoes->linkedin.'">Linkedin</a>' : '<br /><a href="'.$configuracoes->linkedin.'">Linkedin</a>') ;
                            }
                            if($configuracoes->youtube){
                                echo ($configuracoes->dominio || $configuracoes->facebook || $configuracoes->instagram || $configuracoes->linkedin ? ' - <a href="'.$configuracoes->youtube.'">Youtube</a>' : '<br /><a href="'.$configuracoes->youtube.'">Youtube</a>') ;
                            }
                        @endphp                                
                        </p>
                    </x-adminlte-text-editor>                    
                </div>
                
                <div class="form-group">
                    <div class="btn btn-default btn-file">
                        <i class="fas fa-paperclip"></i> Anexo
                        <input type="file" name="anexos[]" multiple> 
                        <!--<input type="file" name="anexo">-->
                    </div>
                    <p class="help-block">Tamanho Max. 5MB</p>
                    <p class="content_anexo"></p>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <div class="float-right">
                    <button type="submit" class="btn btn-primary btn-lg"><i class="far fa-envelope"></i> Enviar Agora</button>
                </div>
            </div>
            <!-- /.card-footer -->
            </form>
        </div>
    </div>
</div>

@endsection

@section('js')
    <script>
        $(function () {

            // FUNÇÃO PARA EXCLUIR OS ANEXOS DO EMAIL
            $('input[name="anexos[]').change(function (anexos){
                for (var i = 0; i < this.files.length; i++)
                {
                    $('.content_anexo').append(
                    '<span id="list">' + this.files[i].name + 
                    '<a href="javascript:void(0)" class="anexo-remove ml-3"><i class="nav-icon fas fa-times"></i></a>' +
                    '<br /></span>');                    
                }
                $('.anexo-remove').click(function(){
                    $(this).closest('#list').remove()
                });
            });

            $('.open_cc').on('click', function (event) {
                event.preventDefault();
                box = $(".copiapara");
                button = $(this);
                if (box.css("display") !== "none") {
                    button.text("Com cópia ↓");
                } else {
                    button.text("↑ Ocultar");
                }
                box.slideToggle();
            });

        });
    </script>
@endsection