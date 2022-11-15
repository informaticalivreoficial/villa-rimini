<?php

namespace App\Mail\Web;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Markdown;

class ReservaSend extends Mailable
{
    use Queueable, SerializesModels;

    private $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->replyTo($this->data['reply_email'], $this->data['reply_name'])
            ->to($this->data['siteemail'], $this->data['sitename'])
            ->cc(['suporte@informaticalivre.com.br','villadirimi@terra.com.br'])
            ->from($this->data['siteemail'], $this->data['sitename'])
            ->subject('✔️ Pré-reserva: ' . $this->data['reply_name'])
            ->markdown('emails.reserva', [
                'nome' => $this->data['reply_name'],
                'email' => $this->data['reply_email'],
                'telefone' => $this->data['telefone'],
                'estado' => $this->data['estado'],
                'cidade' => $this->data['cidade'],
                'checkin' => $this->data['checkin'],
                'checkout' => $this->data['checkout'],
                'adultos' => $this->data['adultos'],
                'criancas' => $this->data['criancas'],
                'mensagem' => $this->data['mensagem'],
                'codigo' => $this->data['codigo'],
                'apartamento' => $this->data['apartamento']
        ]);
    }
}
