<div class="contact-form">
    @if ($enviado)
        <div class="alert alert-success" role="status">
            <strong>Mensagem enviada!</strong> Em breve nossa equipe entrará em contato.
        </div>

        <p>
            <a href="#" wire:click.prevent="novoContato">Enviar outra mensagem</a>
        </p>
    @else
        <form wire:submit="submit" autocomplete="off" novalidate>
            <div class="hp-wrapper" aria-hidden="true" tabindex="-1">
                <label for="hp-bairro">Bairro</label>
                <input type="text" id="hp-bairro" name="bairro" tabindex="-1" autocomplete="off" wire:model="bairro">
                <label for="hp-cidade">Cidade</label>
                <input type="text" id="hp-cidade" name="cidade" tabindex="-1" autocomplete="off" wire:model="cidade">
            </div>

            @if ($errors->has('form'))
                <div class="alert alert-danger" role="alert">
                    {{ $errors->first('form') }}
                </div>
            @endif

            <div class="form-group">
                <input type="text" class="form-control" id="input_name" placeholder="Seu Nome" wire:model="nome" autocomplete="name">
                @error('nome')
                    <span class="help-block text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <input type="email" class="form-control" id="input_email" placeholder="E-mail" wire:model="email" autocomplete="email">
                @error('email')
                    <span class="help-block text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <textarea id="textarea_message" class="form-control" rows="5" placeholder="Digite sua mensagem" wire:model="mensagem"></textarea>
                @error('mensagem')
                    <span class="help-block text-danger">{{ $message }}</span>
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
    </style>
</div>
