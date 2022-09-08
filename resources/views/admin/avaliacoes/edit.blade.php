@extends('admin.master.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Editar Avaliação</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Painel de Controle</a></li>
                    <li class="breadcrumb-item"><a href="{{route('admin.avaliacoes.index')}}">Avaliações</a></li>
                    <li class="breadcrumb-item active">Editar Avaliação</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
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
        <form action="{{ route('admin.avaliacoes.update', ['avaliaco' => $avaliacao->id]) }}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="row">            
            <div class="col-12">
                <div class="card card-teal card-outline card-outline-tabs">                   
                    
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-four-tabContent">
                            <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                               
                                <div class="row">                                   
                                    <div class="col-12 col-md-6 col-lg-3"> 
                                        <div class="form-group">
                                            <div class="thumb_user_admin">
                                                @php
                                                    if(!empty($avaliacao->avatar) && \Illuminate\Support\Facades\File::exists(public_path() . '/storage/' . $avaliacao->avatar)){
                                                        $cover = url('storage/'.$avaliacao->avatar);
                                                    } else {
                                                        $cover = url(asset('backend/assets/images/image.jpg'));
                                                    }
                                                @endphp
                                                <img id="preview" src="{{$cover}}" alt="{{ old('name') ?? $avaliacao->name }}" title="{{ old('name') ?? $avaliacao->name }}"/>
                                                <input id="img-input" type="file" name="avatar">
                                            </div>                                               
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-9">
                                        <div class="row mb-2">
                                            <div class="col-12 col-md-6 col-lg-6 mb-2"> 
                                                <div class="form-group">
                                                    <label class="labelforms"><b>*Nome</b></label>
                                                    <input type="text" class="form-control" placeholder="Nome" name="name" value="{{ old('name') ?? $avaliacao->name }}">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6 col-lg-6 mb-2"> 
                                                <div class="form-group">
                                                    <label class="labelforms"><b>*Email</b></label>
                                                    <input type="text" class="form-control" placeholder="Email" name="email" value="{{ old('email') ?? $avaliacao->email }}">
                                                </div>
                                            </div>
                                            <div class="col-12">   
                                                <label class="labelforms"><b>Avaliação</b></label>
                                                <textarea id="inputDescription" class="form-control" rows="4" name="content">{{ old('content') ?? $avaliacao->content }}</textarea>                                                      
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                               
                            </div>                            
                            
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
                                
        <div class="row text-right">
            <div class="col-12 mb-4">
                <button type="submit" class="btn btn-success"><i class="nav-icon fas fa-check mr-2"></i> Atualizar Agora</button>
            </div>
        </div>  
                                
        </form>

    </div>
</section>
@endsection

@section('js')
<script>
    $(function () { 
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        function readImage() {
            if (this.files && this.files[0]) {
                var file = new FileReader();
                file.onload = function(e) {
                    document.getElementById("preview").src = e.target.result;
                };       
                file.readAsDataURL(this.files[0]);
            }
        }
        document.getElementById("img-input").addEventListener("change", readImage, false);
        
    });
</script>
@endsection