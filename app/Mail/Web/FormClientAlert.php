<?php

namespace App\Mail\Web;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Markdown;

class FormClientAlert extends Mailable
{
    use Queueable, SerializesModels;

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
            ->from($this->data['siteemail'], $this->data['sitename'])
            ->subject('✅ Formulário de Cliente Enviado')
            ->markdown('emails.form-client-alert', [
                //Cliente
                'nome' => $this->data['reply_name'],
                'email' => $this->data['reply_email'],
                'telefone' => $this->data['telefone'],
                'cpf' => $this->data['cpf'],
                //Empresa
                'empresa' => $this->data['empresa'] ?? null,
                'email_empresa' => $this->data['email_empresa'] ?? null,
                'telefone_empresa' => $this->data['telefone_empresa'] ?? null,
                'celular' => $this->data['celular'] ?? null,
                'whatsapp' => $this->data['whatsapp'] ?? null,
                'notasadicionais' => $this->data['notasadicionais'] ?? null
        ]);
    }
}
