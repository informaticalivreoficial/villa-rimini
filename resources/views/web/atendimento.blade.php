@extends('web.master.master')

@section('content')
    
<div class="mainWrapper page">
    <div class="container">
        <div class="pageContentArea">
            <main>
                <header id="alertatendiento">
                    <h1>Atendimento</h1>
                </header>
                
                <div class="pageContent">            					
                    <form action="" method="post" autocomplete="off" class="j_formAtendimento">                
                        <div class="alertas"></div>
                        <input class="noclear" type="hidden" name="action" value="atendimento" />
                        <!-- HONEYPOT -->
                        <input type="hidden" class="noclear" name="bairro" value="" />
                        <input type="text" class="noclear" style="display: none;" name="cidade" value="" />
                        <div class="row form_hide">
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <label for="fe-name" class="required">Seu Nome</label>
                                <input id="fe-name" name="nome" type="text"/>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-lg-6">
                                <label for="fe-email" class="required">Seu E-mail</label>
                                <input id="fe-email" name="email" type="text"/>
                            </div>
                        </div> 
                        
                        <div class="row form_hide">
                            <div class="col-xs-12 col-md-12">
                                <label for="fe-message" class="required">Sua Mensagem</label>
                                <textarea id="fe-message" name="mensagem"></textarea>
                            </div>
                         </div>  
                        
                        <p class="text-center form_hide"><button class="btn btn-default b_cadastro" type="submit" name="submit">Enviar Agora</button></p>
                        
                    </form>
                </div>
            </main><!--main content of current page-->
        </div><!--pageContentArea-->
    </div><!--container-->
    </div><!--container-->
@endsection