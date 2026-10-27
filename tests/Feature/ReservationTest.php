<?php

namespace Tests\Feature;

use App\Mail\Web\ReservaRetorno;
use App\Mail\Web\ReservaSend;
use App\Models\Reservas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\Concerns\CreatesTestData;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use CreatesTestData;
    use RefreshDatabase;

    public function test_pre_reserva_cria_cliente_e_reserva_e_envia_emails(): void
    {
        Mail::fake();

        $this->createConfig();
        [$estado, $cidade] = $this->createEstadoCidade();
        $apartamento = $this->createApartment();

        $response = $this->post(route('web.acomodacaoSend', [
            'apart_id' => $apartamento->id,
            'nome' => 'Cliente de Teste',
            'email' => 'cliente@example.com',
            'telefone' => '(12) 99138-5030',
            'uf' => $estado->estado_id,
            'cidade' => $cidade->cidade_id,
            'checkin' => '01/02/2026',
            'checkout' => '10/02/2026',
            'num_adultos' => 2,
            'num_cri_0_5' => 1,
            'mensagem' => 'Gostaria de um quarto com vista para o mar.',
        ]));

        $response->assertOk();
        $response->assertJsonStructure(['sucess']);

        $cliente = User::where('email', 'cliente@example.com')->first();
        $this->assertNotNull($cliente);
        $this->assertSame(1, (int) $cliente->client);

        $reserva = Reservas::where('cliente', $cliente->id)->first();
        $this->assertNotNull($reserva);
        $this->assertSame($apartamento->id, (int) $reserva->apartamento);
        $this->assertSame('2026-02-01', $reserva->getAttributes()['checkin']);
        $this->assertSame('2026-02-10', $reserva->getAttributes()['checkout']);
        $this->assertSame(1, (int) $reserva->status);

        Mail::assertSent(ReservaSend::class);
        Mail::assertSent(ReservaRetorno::class);
    }

    public function test_pre_reserva_exige_nome(): void
    {
        Mail::fake();

        $this->createConfig();
        [$estado, $cidade] = $this->createEstadoCidade();
        $apartamento = $this->createApartment();

        $response = $this->post(route('web.acomodacaoSend', [
            'apart_id' => $apartamento->id,
            'email' => 'cliente@example.com',
            'uf' => $estado->estado_id,
            'cidade' => $cidade->cidade_id,
            'checkin' => '01/02/2026',
            'checkout' => '10/02/2026',
        ]));

        $response->assertOk();
        $response->assertJsonStructure(['error']);
        $this->assertDatabaseCount('reservas', 0);
        $this->assertSame(0, User::where('email', 'cliente@example.com')->count());
        Mail::assertNothingSent();
    }

    public function test_pre_reserva_exige_email_valido(): void
    {
        Mail::fake();

        $this->createConfig();
        [$estado, $cidade] = $this->createEstadoCidade();
        $apartamento = $this->createApartment();

        $response = $this->post(route('web.acomodacaoSend', [
            'apart_id' => $apartamento->id,
            'nome' => 'Cliente de Teste',
            'email' => 'email-invalido',
            'uf' => $estado->estado_id,
            'cidade' => $cidade->cidade_id,
            'checkin' => '01/02/2026',
            'checkout' => '10/02/2026',
        ]));

        $response->assertOk();
        $response->assertJsonStructure(['error']);
        $this->assertDatabaseCount('reservas', 0);
        Mail::assertNothingSent();
    }
}
