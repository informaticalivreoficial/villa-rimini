<x-mail::message>
# ✅ Contato via site

**Nome:** {{ $nome }}  
**E-mail:** {{ $email }}  
**Recebido em:** {{ now()->format('d/m/Y H:i') }}

<x-mail::panel>
@php
    $texto = str_replace(["\r\n", "\r"], "\n", $mensagem);
    $texto = str_replace("\n", "  \n", $texto);
@endphp
{!! e($texto) !!}
</x-mail::panel>

<x-mail::button :url="'mailto:'.$email" color="success">
Responder para {{ $nome }}
</x-mail::button>

Atenciosamente,<br>
{{ config('mail.from.name') }}
</x-mail::message>
