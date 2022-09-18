@component('mail::layout')

{{-- Header --}}
@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        <!-- header here -->
    @endcomponent
@endslot

@section('css')

@endsection
{{-- Body --}}
    <div style="width:100%;">        
        <div style="background:#20C997; overflow:hidden; padding:15px;color:#fff;">                        
            <div style="float:left; font:20px Trebuchet MS, Arial, Helvetica, sans-serif;font-weight:bold; text-align:right;">
                Pré-reserva - {{ $nome }}
            </div>
            <div style="float:right; font:16px Trebuchet MS, Arial, Helvetica, sans-serif;font-weight:bold;">
                Enviada @php echo date('d/m/Y'); @endphp
            </div>                        
        </div>
        <div style="background:#FFF; font:16px Trebuchet MS, Arial, Helvetica, sans-serif; color:#333; line-height:150%;">       
            <h1 style="font-size:20px; color:#000; background:#F4F4F4; padding:10px;">Código da reserva - {{$codigo}}</h1>
            <table>
                <tr>
                <th style="width: 30%;text-align:left;">Apartamento</th>
                <th style="text-align:center;">Check-in</th> 
                <th style="text-align:center;">Check-out</th>                       
                <th style="width: 25%;text-align:center;">----------</th>                        
                <th style="text-align:center;">Quantidade</th>
                </tr>
                <tr>
                    <td style="text-align:left;">{{$apartamento}}</td>
                    <td style="text-align:center;">{{$checkin}}</td>
                    <td style="text-align:center;">{{$checkout}}</td>
                    <td>Adultos</td>
                    <td style="text-align:center;">{{$adultos}}</td>                
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Crianças de 0 a 5 anos</td>
                    <td style="text-align:center;">{{$criancas}}</td>                                            
                </tr>               
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><b>Total</b></td>
                    <td style="text-align:center;">{{($criancas + $adultos)}}</td>                                           
                </tr>               
            </table>
            <h1 style="font-size:20px; color:#000; background:#F4F4F4; padding:10px;">Dados do Cliente</h1>  
            <p style="padding-left:10px;">
            <strong>Responsável: </strong><strong style="color:#09F;">{{ $nome }}</strong>
            <br />
            <strong>E-mail: </strong><strong style="color:#09F;">{{ $email }}</strong>
            <br />
            <strong>Telefone: </strong><strong style="color:#09F;">{{ $telefone }}</strong>
            <br />       
            <strong>Cidade: </strong><strong style="color:#09F;">{{ $cidade }}/{{ $estado }}</strong> 
            <br />
            <strong>Observações: </strong> 
            </p>   
            <p style="padding-left:10px;font:20px Trebuchet MS, Arial, Helvetica, sans-serif; color:#09F;">@php echo nl2br($mensagem); @endphp</p> 
            <p style="padding-left:10px;">
                <a href="{{route('login')}}">Clique aqui para gerenciar as reservas! </a>
            </p>    
        </div> 
    </div>
{{-- Footer --}}
@slot('footer')
    @component('mail::footer')
        <div style="width:100%; margin:20px 0; text-align:center; font-size:10px;"><pre>Sistema de consultas desenvolvido por {{env('DESENVOLVEDOR')}} <br> <a href="mailto:{{env('DESENVOLVEDOR_EMAIL')}}">{{env('DESENVOLVEDOR_EMAIL')}}</a></pre></div>
    @endcomponent
@endslot

@endcomponent