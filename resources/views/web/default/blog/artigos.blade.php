@extends("web.{$configuracoes->template}.master.master")

@section('content')
<div class="container">
	<div class="pageContentArea archive-content">
        <header class="h">
            <h2 style="color: #fff;">{{(!empty($posts) && $posts[0]->tipo == 'noticia' ? 'Notícias' : 'Blog')}}</h2>
        </header>  
        @if(!empty($posts) && $posts->count() > 0)
            <div class="row"> 
                @foreach($posts as $post)
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="padding: 3%;">
                    <article style="min-height: 330px;">
                        <figure>
                            <img src="{{$post->cover()}}" alt="{{$post->titulo}}">
                        </figure>
                        <header>
                            <h5 style="text-transform: lowercase;">
                                <a href="{{route(($post->tipo == 'noticia' ? 'web.noticia' : 'web.blog.artigo'), [ 'slug' => $post->slug ])}}">
                                    {{$post->titulo}}
                                </a>
                            </h5>
                        </header>
                        {!! Words($post->content, 21) !!} 
                        <b><a href="{{route(($post->tipo == 'noticia' ? 'web.noticia' : 'web.blog.artigo'), [ 'slug' => $post->slug ])}}" class="readMore">Leia+</a></b>
                    </article>
                </div>
                @endforeach
            </div>
            
            <div class="row" style="padding: 20px;">
                <div class="col-sm-12">
                    @if (isset($filters))
                        {{ $posts->appends($filters)->links() }}
                    @else
                        {{ $posts->links() }}
                    @endif
                </div>                
            </div>
        @endif
    </div>        
</div>
@endsection

@section('css')
    <style>
        .h {
            padding: 20px 20px 0 40px;
            background: #53c1ef;
            min-height: 75px;
            position: relative;
        }
        .pagination-custom{
            margin: 0;
            display: -ms-flexbox;
            display: flex;
            padding-left: 0;
            list-style: none;
            border-radius: 0.25rem;
        }
        .pagination-custom li a {
            border-radius: 30px;
            margin-right: 8px;
            color:#7c7c7c;
            border: 1px solid #ddd;
            position: relative;
            float: left;
            padding: 6px 12px;
            width: 40px;
            height: 40px;
            text-align: center;
            line-height: 25px;
            font-weight: 600;
        }
        .pagination-custom>.active>a, .pagination-custom>.active>a:hover, .pagination-custom>li>a:hover {
            color: #fff;
            background: #007bff;
            border: 1px solid transparent;
        }
    </style>
@endsection