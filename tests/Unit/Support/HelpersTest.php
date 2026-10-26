<?php

namespace Tests\Unit\Support;

use App\Helpers\Renato;
use App\Helpers\WhatsApp;
use App\Support\Message;
use App\Support\Seo;
use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    public function test_words_limita_a_quantidade_de_palavras(): void
    {
        $this->assertSame('uma duas...', Words('uma duas três quatro', 2));
        $this->assertSame('uma duas', Words('uma duas', 5));
    }

    public function test_renato_retorna_o_primeiro_nome(): void
    {
        $this->assertSame('Renato', Renato::getPrimeiroNome('Renato Montanari'));
    }

    public function test_renato_limpa_telefone(): void
    {
        $this->assertSame('12991385030', Renato::limpaTelefone('(12) 99138-5030'));
    }

    public function test_renato_limpa_cpf_cnpj(): void
    {
        $this->assertSame('12345678901', Renato::limpaCPF_CNPJ('123.456.789-01'));
    }

    public function test_renato_valida_cpf(): void
    {
        $this->assertTrue(Renato::validaCPF('529.982.247-25'));
        $this->assertFalse(Renato::validaCPF('111.111.111-11'));
        $this->assertFalse(Renato::validaCPF('123'));
    }

    public function test_renato_compara_data_expirada(): void
    {
        $this->assertSame('<span style="color:red;">Expirado</span>', Renato::comparaDataExpira('2000-01-01'));
        $this->assertSame('31/12/2999', Renato::comparaDataExpira('2999-12-31'));
    }

    public function test_whatsapp_monta_link_do_numero(): void
    {
        $link = WhatsApp::getNumZap('(12) 99138-5030', 'Olá');

        $this->assertSame(
            'https://api.whatsapp.com/send?l=pt_pt&phone=5512991385030&text=Olá',
            $link
        );
        $this->assertNull(WhatsApp::getNumZap(''));
    }

    public function test_message_renderiza_sucesso_e_erro(): void
    {
        $message = (new Message())->success('Tudo certo');

        $this->assertSame('success', $message->getType());
        $this->assertSame('Tudo certo', $message->getText());
        $this->assertSame("<div class='message success'>Tudo certo</div>", $message->render());

        $message->error('Deu ruim');
        $this->assertSame("<div class='message error'>Deu ruim</div>", $message->render());
    }

    public function test_seo_renderiza_metatags(): void
    {
        $html = (new Seo())->render(
            'Hotel Villa di Rimini',
            'Descrição de teste',
            'https://villarimini.test',
            'https://villarimini.test/imagem.jpg'
        );

        $this->assertStringContainsString('Hotel Villa di Rimini', $html);
        $this->assertStringContainsString('Descrição de teste', $html);
        $this->assertStringContainsString('https://villarimini.test', $html);
    }
}
