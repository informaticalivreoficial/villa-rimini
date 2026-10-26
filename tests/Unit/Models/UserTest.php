<?php

namespace Tests\Unit\Models;

use App\Models\Reservas;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_formata_cpf_ao_ler(): void
    {
        $user = new User();
        $user->cpf = '12345678901';

        $this->assertSame('123.456.789-01', $user->cpf);
    }

    public function test_formata_rg_ao_ler(): void
    {
        $user = new User();
        $user->rg = '123456789';

        $this->assertSame('12.345.678-9', $user->rg);
    }

    public function test_formata_telefone_celular_e_whatsapp(): void
    {
        $user = new User();

        $user->telefone = '(12) 3456-7890';
        $this->assertSame('(12) 3456-7890', $user->telefone);

        $user->celular = '(12) 99138-5030';
        $this->assertSame('(12) 99138-5030', $user->celular);

        $user->whatsapp = '(12) 99138-5030';
        $this->assertSame('(12) 99138-5030', $user->whatsapp);
    }

    public function test_formata_cep_e_data_de_nascimento(): void
    {
        $user = new User();

        $user->cep = '12345-678';
        $this->assertSame('12345-678', $user->cep);

        $user->nasc = '25/12/1990';
        $this->assertSame('25/12/1990', $user->nasc);
        $this->assertSame('1990-12-25', $user->getAttributes()['nasc']);
    }

    public function test_criptografa_senha_e_mantem_senha_legada(): void
    {
        $user = new User();
        $user->password = 'segredo';

        $this->assertSame('segredo', $user->senha);
        $this->assertTrue(Hash::check('segredo', $user->password));
    }

    public function test_nao_define_senha_quando_valor_vazio(): void
    {
        $user = new User();
        $user->password = '';

        $this->assertArrayNotHasKey('password', $user->getAttributes());
    }

    public function test_define_o_tipo_de_usuario(): void
    {
        $user = new User();

        $user->admin = true;
        $user->client = false;
        $user->editor = false;
        $user->superadmin = false;
        $this->assertSame('Administrador', $user->getFuncao());

        $user->admin = false;
        $user->client = true;
        $this->assertSame('Cliente', $user->getFuncao());

        $user->client = false;
        $user->editor = true;
        $this->assertSame('Editor', $user->getFuncao());

        $user->admin = true;
        $user->client = true;
        $user->editor = false;
        $this->assertSame('Administrador/Cliente', $user->getFuncao());

        $user->superadmin = true;
        $this->assertSame('Super Administrador', $user->getFuncao());
    }

    public function test_scopes_de_disponibilidade(): void
    {
        $user = new User();

        $this->assertSame([1], $user->newQuery()->available()->getBindings());
        $this->assertSame([0], $user->newQuery()->unavailable()->getBindings());
    }

    public function test_reserva_formata_datas_e_valor(): void
    {
        $reserva = new Reservas();

        $reserva->checkin = '01/02/2026';
        $reserva->checkout = '10/02/2026';
        $reserva->valor_venda = '1.234,56';
        $reserva->status = '1';

        $this->assertSame('01/02/2026', $reserva->checkin);
        $this->assertSame('10/02/2026', $reserva->checkout);
        $this->assertSame('1.234,56', $reserva->valor_venda);
        $this->assertEquals(1234.56, $reserva->getAttributes()['valor_venda']);
        $this->assertSame(1, $reserva->status);
    }

    public function test_reserva_formata_data_de_criacao(): void
    {
        $reserva = new Reservas();
        $reserva->setRawAttributes(['created_at' => '2026-01-05 10:00:00']);

        $this->assertSame('05/01/2026', $reserva->created_at);
    }
}
