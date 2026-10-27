<?php

namespace App\Livewire\Web;

use App\Mail\Web\Atendimento as AtendimentoMail;
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

    public int $carregadoEm = 0;

    public bool $enviado = false;

    private const LIMITE_TENTATIVAS = 3;

    private const JANELA_SEGUNDOS = 600;

    private const TEMPO_MINIMO_SEGUNDOS = 3;

    public function mount(): void
    {
        $this->carregadoEm = now()->timestamp;
    }

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
        if ($this->ehSpam()) {
            $this->simularEnvio();

            return;
        }

        $chave = 'atendimento:' . request()->ip();

        if (RateLimiter::tooManyAttempts($chave, self::LIMITE_TENTATIVAS)) {
            $this->addError('form', 'Você fez muitas tentativas. Aguarde alguns minutos e tente novamente.');

            return;
        }

        $this->validate();

        RateLimiter::hit($chave, self::JANELA_SEGUNDOS);

        try {
            Mail::send(new AtendimentoMail([
                'sitename' => config('mail.from.name'),
                'siteemail' => config('mail.from.address'),
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

        $this->reset(['nome', 'email', 'mensagem']);
        $this->resetValidation();
        $this->enviado = true;
    }

    public function novoContato(): void
    {
        $this->reset(['nome', 'email', 'mensagem', 'bairro', 'cidade']);
        $this->resetValidation();
        $this->carregadoEm = now()->timestamp;
        $this->enviado = false;
    }

    private function ehSpam(): bool
    {
        if (trim($this->bairro) !== '' || trim($this->cidade) !== '') {
            return true;
        }

        return now()->timestamp - $this->carregadoEm < self::TEMPO_MINIMO_SEGUNDOS;
    }

    private function simularEnvio(): void
    {
        $this->reset(['nome', 'email', 'mensagem', 'bairro', 'cidade']);
        $this->resetValidation();
        $this->enviado = true;
    }

    public function render()
    {
        return view('livewire.web.atendimento');
    }
}
