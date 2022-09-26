@extends('adminlte::page')

@section('title', 'Cadastrar Avaliação')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Cadastrar Avaliação</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Painel de Controle</a></li>
            <li class="breadcrumb-item"><a href="{{route('avaliacoes.index')}}">Avaliações</a></li>
            <li class="breadcrumb-item active">Cadastrar Avaliação</li>
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

        <form action="{{ route('avaliacoes.store') }}" method="post" autocomplete="off">
        @csrf
        <div class="row">            
            <div class="col-12">
                <div class="card card-teal card-outline card-outline-tabs">                   
                    
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-four-tabContent">
                            <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                               
                                <div class="row"> 
                                    <div class="col-12 col-md-4 col-lg-5 mb-2"> 
                                        <div class="form-group">
                                            <label class="labelforms text-muted"><b>*Nome</b></label>
                                            <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-4 col-lg-4"> 
                                        <div class="form-group">
                                            <label class="labelforms text-muted"><b>*E-mail:</b></label>
                                            <input type="email" class="form-control" name="email" value="{{old('email')}}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3 mb-2"> 
                                        <div class="form-group">
                                            <label class="labelforms text-muted"><b>Vai exibir no site?</b></label>
                                            <select name="status" class="form-control">
                                                <option value="1" {{ (old('status') == '1' ? 'selected' : '') }}>Sim</option>
                                                <option value="0" {{ (old('status') == '0' ? 'selected' : '') }}>Não</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-4 mb-2"> 
                                        <div class="form-group">
                                            <label class="labelforms text-muted"><b>Região</b></label>
                                            <input type="text" class="form-control" name="regiao" value="{{ old('regiao') }}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-4 mb-2"> 
                                        <div class="form-group">
                                            <label class="labelforms text-muted"><b>Estado:</b></label>
                                            <select id="state-dd" class="form-control" name="uf">
                                                @if(!empty($estados))
                                                    @foreach($estados as $estado)
                                                    <option value="{{$estado->estado_id}}" {{ (old('uf') == $estado->estado_id ? 'selected' : '') }}>{{$estado->estado_nome}}</option>
                                                    @endforeach                                                                        
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-4 mb-2"> 
                                        <div class="form-group">
                                            <label class="labelforms text-muted"><b>Cidade:</b></label>
                                            <select id="city-dd" class="form-control" name="cidade">
                                                @if(!empty($cidades))
                                                    <option value="">Selecione o Estado</option>
                                                    @foreach($cidades as $cidade)
                                                        <option value="{{$cidade->cidade_id}}" 
                                                                {{ (old('cidade') == $cidade->cidade_id ? 'selected' : '') }}>{{$cidade->cidade_nome}}</option>                                                                   
                                                    @endforeach                                                                        
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-2">   
                                        <label class="labelforms text-muted"><b>Avaliação</b></label>
                                        <textarea id="inputDescription" class="form-control" rows="4" name="content">{{ old('content') }}</textarea>                                                      
                                    </div>                                                                            
                                </div>
                                <div class="row text-right">
                                    <div class="col-12 mb-4 mt-2">
                                        <button type="submit" class="btn btn-success"><i class="nav-icon fas fa-check mr-2"></i> Cadastrar Agora</button>
                                    </div>
                                </div>
                               
                            </div>                            
                            
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
                                
        </form>

@endsection

@section('js')
<script>
    $(function () { 
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $('#state-dd').on('change', function () {
            var idState = this.value;
            $("#city-dd").html('');
            $.ajax({
                url: "{{route('avaliacoes.fetchCity')}}",
                type: "POST",
                data: {
                    estado_id: idState,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (res) {
                    $('#city-dd').html('<option value="">Selecione a cidade</option>');
                    $.each(res.cidades, function (key, value) {
                        $("#city-dd").append('<option value="' + value
                            .cidade_id + '">' + value.cidade_nome + '</option>');
                    });
                }
            });
        });
        
    });
</script>
@endsection