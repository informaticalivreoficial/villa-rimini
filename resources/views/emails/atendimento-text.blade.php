Contato via site

Nome: {{ $nome }}
E-mail: {{ $email }}
Recebido em: {{ now()->format('d/m/Y H:i') }}

Mensagem:
{{ $mensagem }}

Responder: {{ $email }}

--
{{ config('mail.from.name') }}
