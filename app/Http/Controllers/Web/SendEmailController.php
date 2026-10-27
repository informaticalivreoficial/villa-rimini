<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mail\Admin\AvaliacaoCriada;
use App\Mail\Web\ReservaRetorno;
use App\Mail\Web\ReservaSend;
use App\Models\Apartamento;
use App\Models\Newsletter;
use App\Models\NewsletterCat;
use App\Models\Reservas;
use App\Models\User;
use App\Services\CidadeService;
use App\Services\ConfigService;
use App\Services\EstadoService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SendEmailController extends Controller
{
    private const LIMITE_TENTATIVAS = 5;

    private const JANELA_SEGUNDOS = 600;

    protected $configService, $estadoService, $cidadeService;

    public function __construct(
        ConfigService $configService,
        EstadoService $estadoService,
        CidadeService $cidadeService
    ) {
        $this->configService = $configService;
        $this->cidadeService = $cidadeService;
        $this->estadoService = $estadoService;
    }

    public function sendNewsletter(Request $request)
    {
        if ($this->honeypotPreenchido($request, ['bairro', 'cidade'])) {
            return response()->json(['sucess' => 'Obrigado! Cadastro realizado com sucesso.']);
        }

        if ($this->excedeuTentativas('newsletter')) {
            return response()->json(['error' => 'Muitas tentativas. Aguarde alguns minutos e tente novamente.']);
        }

        $validador = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:150'],
        ], [
            'email.required' => 'Informe seu e-mail.',
            'email.email' => 'O campo <strong>Email</strong> está vazio ou não tem um formato válido!',
            'email.max' => 'O e-mail informado é muito longo.',
        ]);

        if ($validador->fails()) {
            return response()->json(['error' => $validador->errors()->first()]);
        }

        $this->registrarTentativa('newsletter');

        try {
            $email = $request->email;

            if (Newsletter::where('email', $email)->exists()) {
                Newsletter::where('email', $email)->update(['status' => 1]);

                return response()->json(['sucess' => 'Obrigado! Cadastro realizado com sucesso.']);
            }

            $categoria = NewsletterCat::where('sistema', 1)->first();

            if (! $categoria) {
                return response()->json(['error' => 'Cadastro indisponível no momento. Tente novamente mais tarde.']);
            }

            Newsletter::create([
                'email' => $email,
                'nome' => $request->nome ?: '#Cadastrado pelo Site',
                'status' => 1,
                'autorizacao' => 1,
                'categoria' => $categoria->id,
            ]);

            return response()->json(['sucess' => 'Obrigado! Cadastro realizado com sucesso.']);
        } catch (\Throwable $e) {
            Log::error('Falha ao cadastrar newsletter pelo site', [
                'email' => $request->email,
                'erro' => $e->getMessage(),
            ]);

            return response()->json(['error' => 'Não foi possível concluir o cadastro agora. Tente novamente.']);
        }
    }

    public function acomodacaoSend(Request $request)
    {
        if ($this->honeypotPreenchido($request, ['bairro', 'cidade1'])) {
            return response()->json(['sucess' => 'Obrigado! Sua solicitação de pré-reserva foi enviada com sucesso!']);
        }

        if ($this->excedeuTentativas('reserva')) {
            return response()->json(['error' => 'Muitas tentativas. Aguarde alguns minutos e tente novamente.']);
        }

        $validador = Validator::make($request->all(), [
            'apart_id' => ['required'],
            'nome' => ['required', 'string', 'min:3', 'max:120'],
            'email' => ['required', 'email', 'max:150'],
            'telefone' => ['required', 'string', 'max:20'],
            'uf' => ['required'],
            'cidade' => ['required'],
            'checkin' => ['required'],
            'checkout' => ['required'],
            'num_adultos' => ['required'],
        ], [
            'apart_id.required' => 'Por favor escolha um <strong>apartamento</strong>.',
            'nome.required' => 'Por favor preencha o campo <strong>Nome</strong>.',
            'email.required' => 'Informe seu <strong>E-mail</strong>.',
            'email.email' => 'O campo <strong>Email</strong> está vazio ou não tem um formato válido!',
            'telefone.required' => 'Por favor preencha o campo <strong>Telefone</strong>.',
            'uf.required' => 'Por favor selecione o <strong>Estado</strong>.',
            'cidade.required' => 'Por favor selecione a <strong>Cidade</strong>.',
            'checkin.required' => 'Por favor selecione uma <strong>Data</strong> para seu CheckIn!',
            'checkout.required' => 'Por favor selecione uma <strong>Data</strong> para seu CheckOut!',
            'num_adultos.required' => 'Por favor informe a quantidade de <strong>Adultos</strong>.',
        ]);

        if ($validador->fails()) {
            return response()->json(['error' => $validador->errors()->first()]);
        }

        $this->registrarTentativa('reserva');

        $apartamento = Apartamento::find($request->apart_id);
        $estado = $this->estadoService->getEstado($request->uf);
        $cidade = $this->cidadeService->getCidadeById((int) $request->cidade);

        if (! $apartamento) {
            return response()->json(['error' => 'O <strong>apartamento</strong> selecionado não está disponível.']);
        }

        if (! $estado || ! $cidade) {
            return response()->json(['error' => 'Não foi possível identificar o <strong>Estado</strong> ou a <strong>Cidade</strong> informados.']);
        }

        try {
            $codigo = '00' . rand(1, 100000);

            $userCreate = User::firstOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->nome,
                    'telefone' => $request->telefone,
                    'email_verified_at' => Carbon::now(),
                    'password' => bcrypt(Carbon::now()),
                    'senha' => Str::random(20),
                    'remember_token' => Str::random(20),
                    'client' => true,
                    'status' => 1,
                    'notasadicionais' => 'Cliente cadastrado pelo site',
                ]
            );

            Reservas::create([
                'cliente' => $userCreate->id,
                'apartamento' => $apartamento->id,
                'status' => 1,
                'adultos' => $request->num_adultos,
                'criancas_0_5' => $request->num_cri_0_5,
                'codigo' => $codigo,
                'checkin' => $request->checkin,
                'checkout' => $request->checkout,
                'notasadicionais' => $request->mensagem,
            ]);
        } catch (\Throwable $e) {
            Log::error('Falha ao registrar pré-reserva pelo site', [
                'email' => $request->email,
                'erro' => $e->getMessage(),
            ]);

            return response()->json(['error' => 'Não foi possível registrar sua pré-reserva agora. Tente novamente.']);
        }

        $data = [
            'sitename' => $this->configService->getConfig()->nomedosite,
            'siteemail' => $this->configService->getConfig()->email ?: config('mail.from.address'),
            'reply_name' => $request->nome,
            'reply_email' => $request->email,
            'telefone' => $request->telefone,
            'estado' => $estado->estado_nome,
            'cidade' => $cidade->cidade_nome,
            'checkin' => $request->checkin,
            'checkout' => $request->checkout,
            'adultos' => $request->num_adultos,
            'criancas' => $request->num_cri_0_5,
            'mensagem' => $request->mensagem,
            'codigo' => $codigo,
            'apartamento' => $apartamento->titulo,
        ];

        $retorno = [
            'sitename' => $this->configService->getConfig()->nomedosite,
            'siteemail' => $this->configService->getConfig()->email ?: config('mail.from.address'),
            'reply_name' => $request->nome,
            'reply_email' => $request->email,
        ];

        try {
            Mail::send(new ReservaSend($data));
            Mail::send(new ReservaRetorno($retorno));
        } catch (\Throwable $e) {
            Log::error('Falha ao enviar e-mails da pré-reserva', [
                'email' => $request->email,
                'erro' => $e->getMessage(),
            ]);
        }

        return response()->json(['sucess' => "Obrigado {$request->nome} sua solicitação de pré-reserva foi enviada com sucesso!"]);
    }

    public function avaliacaoSend(Request $request)
    {
        if ($this->honeypotPreenchido($request, ['bairro', 'cidade'])) {
            return response()->json(['sucess' => 'Obrigado! Sua avaliação foi enviada com sucesso!']);
        }

        if ($this->excedeuTentativas('avaliacao')) {
            return response()->json(['error' => 'Muitas tentativas. Aguarde alguns minutos e tente novamente.']);
        }

        $validador = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'email' => ['required', 'email', 'max:150'],
            'checkout' => ['required'],
        ], [
            'name.required' => 'Por favor preencha o campo <strong>Nome</strong>.',
            'email.required' => 'Informe seu <strong>E-mail</strong>.',
            'email.email' => 'O campo <strong>Email</strong> está vazio ou não tem um formato válido!',
            'checkout.required' => 'Por favor selecione a <strong>Data</strong> do seu Check out!',
        ]);

        if ($validador->fails()) {
            return response()->json(['error' => $validador->errors()->first()]);
        }

        $this->registrarTentativa('avaliacao');

        $data = $request->except(['_token', 'bairro', 'cidade', 'cidade1']);

        try {
            Mail::send(new AvaliacaoCriada($data, $this->configService->getConfig()));
        } catch (\Throwable $e) {
            Log::error('Falha ao enviar avaliação pelo site', [
                'email' => $request->email,
                'erro' => $e->getMessage(),
            ]);

            return response()->json(['error' => 'Não foi possível enviar sua avaliação agora. Tente novamente.']);
        }

        return response()->json(['sucess' => "Obrigado {$request->name} sua avaliação foi enviada com sucesso!"]);
    }

    private function honeypotPreenchido(Request $request, array $campos): bool
    {
        foreach ($campos as $campo) {
            if (filled($request->input($campo))) {
                return true;
            }
        }

        return false;
    }

    private function excedeuTentativas(string $contexto): bool
    {
        return RateLimiter::tooManyAttempts($contexto . ':' . request()->ip(), self::LIMITE_TENTATIVAS);
    }

    private function registrarTentativa(string $contexto): void
    {
        RateLimiter::hit($contexto . ':' . request()->ip(), self::JANELA_SEGUNDOS);
    }
}
