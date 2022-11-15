<?php

namespace App\Mail\Admin;

use App\Services\ConfigService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AvaliacaoCriada extends Mailable
{
    use Queueable, SerializesModels;

    private $data, $configService;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data, $configService)
    {
        $this->data = $data;
        $this->configService = $configService;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->replyTo($this->configService->email, $this->configService->nomedosite)
            ->to($this->configService->email, $this->configService->nomedosite)
            ->from($this->configService->email, $this->configService->nomedosite)
            ->subject('📢 Avaliação: ' . $this->data['name'])
            ->markdown('emails.avaliacao-send', [
                'nome' => $this->data['name'],
                'email' => $this->data['email'],
                'checkout' => $this->data['checkout'],
                'mensagem' => $this->data['content']
        ]);
    }
}
