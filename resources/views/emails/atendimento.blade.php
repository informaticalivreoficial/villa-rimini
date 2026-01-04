<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Atendimento</title>
</head>
<body style="margin:0; padding:0; background:#f0f2f5; font-family:Trebuchet MS, Arial, Helvetica, sans-serif;">

<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="padding:20px 0;">
    <tr>
        <td align="center">

            <table cellpadding="0" cellspacing="0" width="650" style="background:#ffffff; border-radius:10px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,0.08);">

                <!-- HEADER -->
                <tr>
                    <td style="background:#1f8f63; color:#ffffff; padding:20px 25px; font-size:22px; font-weight:bold;">
                        <table width="100%">
                            <tr>
                                <td style="color:#ffffff; font-size:22px; font-weight:bold;">
                                    Atendimento — {{ $nome }}
                                </td>
                                <td align="right" style="color:#ffffff; font-size:16px;">
                                    {{ date('d/m/Y H:i') }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- TÍTULO SEÇÃO -->
                <tr>
                    <td style="background:#e6f4ef; padding:12px 20px; color:#1f8f63; font-size:18px; font-weight:bold; border-bottom:1px solid #d1e8df;">
                        Detalhes da Mensagem
                    </td>
                </tr>

                <!-- CONTEÚDO -->
                <tr>
                    <td style="padding:20px 25px; color:#333333; font-size:16px; line-height:1.6;">
                        <p>
                            <span style="font-weight:bold; color:#1f8f63;">Mensagem:</span><br>
                            {!! nl2br(e($mensagem)) !!}
                        </p>
                    </td>
                </tr>

                <!-- TÍTULO SEÇÃO -->
                <tr>
                    <td style="background:#e6f4ef; padding:12px 20px; color:#1f8f63; font-size:18px; font-weight:bold; border-bottom:1px solid #d1e8df;">
                        Dados do Cliente
                    </td>
                </tr>

                <!-- DADOS DO CLIENTE -->
                <tr>
                    <td style="padding:20px 25px; color:#333333; font-size:16px; line-height:1.6;">
                        <p>
                            <span style="font-weight:bold; color:#1f8f63;">Nome:</span> {{ $nome }}
                        </p>
                        <p>
                            <span style="font-weight:bold; color:#1f8f63;">E-mail:</span> {{ $email }}
                        </p>
                    </td>
                </tr>

                <!-- FOOTER -->
                <tr>
                    <td style="background:#f7f7f7; text-align:center; padding:15px; color:#777777; font-size:12px; border-top:1px solid #e0e0e0;">
                        Sistema desenvolvido por {{ env('DESENVOLVEDOR') }}<br>
                        <a href="mailto:{{ env('DESENVOLVEDOR_EMAIL') }}" style="color:#1f8f63; text-decoration:none;">
                            {{ env('DESENVOLVEDOR_EMAIL') }}
                        </a>
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
