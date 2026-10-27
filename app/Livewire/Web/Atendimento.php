<?php

namespace App\Livewire\Web;

use App\Mail\Web\Atendimento as AtendimentoMail;
use App\Services\ConfigService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class Atendimento extends Component
{
    public string $nome = '';

    public string $email = '';

    public string $mensagem = '';

    public string $bairro = '';

    public string $cidade = '';

    public bool $enviado = false;

    private const LIMITE_TENTATIVAS = 3;

    private const JANELA_SEGUNDOS = 600;

    protected function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'min:3', 'max:120'],
            'email' => ['required', 'email', 'max:150'],
            'mensagem' => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }

    protected function messages(): array
    {
        return [
            'nome.required' => 'Informe seu nome.',
            'nome.min' => 'Informe seu nome completo.',
            'nome.max' => 'O nome informado é muito longo.',
            'email.required' => 'Informe seu e-mail.',
            'email.email' => 'Informe um e-mail válido.',
            'email.max' => 'O e-mail informado é muito longo.',
            'mensagem.required' => 'Escreva sua mensagem.',
            'mensagem.min' => 'Sua mensagem deve ter pelo menos 10 caracteres.',
            'mensagem.max' => 'Sua mensagem deve ter no máximo 2000 caracteres.',
        ];
    }

    public function submit(): void
    {
        $this->validate();

        if (trim($this->bairro) !== '' || trim($this->cidade) !== '') {
            Log::warning('Atendimento: envio bloqueado pelo honeypot', [
                'ip' => request()->ip(),
                'campos' => array_keys(array_filter([
                    'bairro' => trim($this->bairro),
                    'cidade' => trim($this->cidade),
                ])),
            ]);

            $this->simularEnvio();

            return;
        }

        $chave = 'atendimento:' . request()->ip();

        if (RateLimiter::tooManyAttempts($chave, self::LIMITE_TENTATIVAS)) {
            Log::warning('Atendimento: envio bloqueado por excesso de tentativas', [
                'ip' => request()->ip(),
            ]);

            $this->addError('form', 'Você fez muitas tentativas. Aguarde alguns minutos e tente novamente.');

            return;
        }

        RateLimiter::hit($chave, self::JANELA_SEGUNDOS);

        $destinatario = $this->destinatario();

        try {
            Mail::send(new AtendimentoMail([
                'sitename' => config('mail.from.name'),
                'siteemail' => config('mail.from.address'),
                'contact_to' => $destinatario,
                'reply_name' => $this->nome,
                'reply_email' => $this->email,
                'mensagem' => $this->mensagem,
            ]));
        } catch (\Throwable $e) {
            Log::error('Falha ao enviar mensagem de atendimento pelo site', [
                'email' => $this->email,
                'erro' => $e->getMessage(),
            ]);

            $this->addError('form', 'Não foi possível enviar sua mensagem agora. Tente novamente em instantes.');

            return;
        }

        Log::info('Atendimento: mensagem enviada', [
            'ip' => request()->ip(),
            'para' => $destinatario,
        ]);

        $this->reset(['nome', 'email', 'mensagem']);
        $this->resetValidation();
        $this->enviado = true;
    }

    public function novoContato(): void
    {
        $this->reset(['nome', 'email', 'mensagem', 'bairro', 'cidade']);
        $this->resetValidation();
        $this->enviado = false;
    }

    private function simularEnvio(): void
    {
        $this->reset(['nome', 'email', 'mensagem', 'bairro', 'cidade']);
        $this->resetValidation();
        $this->enviado = true;
    }

    /**
     * Destinatário do contato: MAIL_CONTACT_TO quando definido; senão o
     * e-mail cadastrado nas configurações do site (ex.: reservas@...).
     */
    private function destinatario(): string
    {
        if (filled($contato = config('mail.contact_to'))) {
            return $contato;
        }

        $configuracao = app(ConfigService::class)->getConfig();

        return $configuracao->email ?: config('mail.from.address');
    }

    public function render()
    {
        return view('livewire.web.atendimento');
    }
}
