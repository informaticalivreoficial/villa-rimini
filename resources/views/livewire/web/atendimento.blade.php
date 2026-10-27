<div class="contact-form">
    @if ($enviado)
        <div class="alert alert-success contact-alert" role="status">
            <strong>Mensagem enviada!</strong> Em breve nossa equipe entrará em contato.
        </div>

        <p>
            <a href="#" wire:click.prevent="novoContato">Enviar outra mensagem</a>
        </p>
    @else
        <form wire:submit="submit" autocomplete="off" novalidate>
            <div class="hp-wrapper" aria-hidden="true" tabindex="-1">
                <input type="text" id="hp-a" name="hp_campo_a" tabindex="-1" autocomplete="off"
                    data-lpignore="true" data-1p-ignore data-bwignore data-form-type="other" wire:model="bairro">
                <input type="text" id="hp-b" name="hp_campo_b" tabindex="-1" autocomplete="off"
                    data-lpignore="true" data-1p-ignore data-bwignore data-form-type="other" wire:model="cidade">
            </div>

            @if ($errors->has('form'))
                <div class="alert alert-danger contact-alert" role="alert">
                    {{ $errors->first('form') }}
                </div>
            @endif

            <div class="form-group">
                <input type="text" class="form-control @error('nome') is-invalid @enderror" id="input_name"
                    placeholder="Seu Nome" wire:model="nome" autocomplete="name"
                    @error('nome') aria-invalid="true" aria-describedby="erro-nome" @enderror>
                @error('nome')
                    <span id="erro-nome" class="help-block text-danger" role="alert">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="input_email"
                    placeholder="E-mail" wire:model="email" autocomplete="email"
                    @error('email') aria-invalid="true" aria-describedby="erro-email" @enderror>
                @error('email')
                    <span id="erro-email" class="help-block text-danger" role="alert">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <textarea id="textarea_message" class="form-control @error('mensagem') is-invalid @enderror"
                    rows="5" placeholder="Digite sua mensagem" wire:model="mensagem"
                    @error('mensagem') aria-invalid="true" aria-describedby="erro-mensagem" @enderror></textarea>
                @error('mensagem')
                    <span id="erro-mensagem" class="help-block text-danger" role="alert">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <input type="submit" id="js-contact-btn" value="Enviar Agora" wire:loading.remove wire:target="submit">
                <input type="submit" id="js-contact-btn-loading" value="Enviando..." wire:loading wire:target="submit" disabled>
            </div>
        </form>
    @endif

    <style>
        .contact-form .hp-wrapper {
            position: absolute !important;
            left: -9999px !important;
            top: auto !important;
            width: 1px !important;
            height: 1px !important;
            overflow: hidden !important;
        }

        /* Mensagens de validação do formulário de atendimento.
           O tema carrega .help-block depois de .text-danger no lib.css,
           então a cor cinza sobrescreve o vermelho — daí o seletor mais
           específico abaixo. */
        .contact-form .help-block.text-danger {
            display: block;
            color: #a94442;
            font-size: 13px;
            font-weight: 600;
            line-height: 1.4;
            margin: -14px 0 18px;
            padding-left: 2px;
            letter-spacing: 0;
        }

        .contact-form .form-control.is-invalid {
            border: 1px solid #d9534f;
            background-color: #fff8f8;
        }

        .contact-form .form-control.is-invalid:focus {
            border-color: #a94442;
            box-shadow: 0 0 0 2px rgba(169, 68, 66, 0.15);
        }

        .contact-form .contact-alert {
            border-radius: 0;
            font-size: 14px;
            margin-bottom: 20px;
        }
    </style>
</div>
