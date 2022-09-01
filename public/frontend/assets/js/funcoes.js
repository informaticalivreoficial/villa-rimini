// Função de envio do form de atendimento  
$(function(){ 
    
    $("#btn_submit").click(function(){
        
        var msg	 = $('#success');
        //var diva = $('#escondea');
        
        function carregandoa(){
    		msg.empty().html('<p class="mensagem load">Carregando<br /><img src="http://villadirimini.com.br/template/default/images/loading.gif"/></p>').fadeIn("fast");
    	}
        
        function errosenda(){
    		msg.empty().html('<p class="mensagem erro"><strong>Erro inesperado,</strong> Favor contate o administrador!</p>').fadeIn("fast");
    	}
        
       $.ajax({
           dataType:'html',
           url:"http://villadirimini.com.br/template/default/ajax/atendimento.php",
           type:"POST",
           data: ({nome:$("input[name='nome']").val(),email:$("input[name='email']").val(),mensagem:$("textarea[name='mensagem']").val(),soma:$("input[name='soma']").val(),hidden:$("input[name='hidden']").val()}),
    
    
           beforeSend: carregandoa,
           
           success:function(data){
                $('#success').html(data);
                //$('#escondea').hide();
            }, 
            
            complete: function(data){
                $('#limpa').click();                
            },
            
            error: 		errosenda


           });
           return false;
    });
    
    // FORM NEWSLETTER
    $("#j_buttom").click(function(){
    
        var msgn	 = $('#successn');
        //var divn     = $('#esconden');
        
        function carregandon(){
    		msgn.empty().html('<p class="mensagem load">Carregando<br /><img src="http://villadirimini.com.br/template/default/images/loading.gif"/></p>').fadeIn("fast");
    	}
        
        function errosendn(){
    		msgn.empty().html('<p class="mensagem erro"><strong>Erro inesperado,</strong> Favor contate o administrador!</p>').fadeIn("fast");
    	}
        
       $.ajax({
           dataType:'html',
           url:"http://villadirimini.com.br/template/default/ajax/newsletter.php",
           type:"POST",
           data: ({email:$("input[name='email1']").val()}),
    
    
           beforeSend: carregandon,
           
           success:function(data){
                $('#successn').html(data);
            }, 
            
            complete: function(data){
                $('#limpan').click();
                //$('#esconden').fadeOut("slow");  
                //e.preventDefault();
            },
            
            error: 		errosendn


           });
           
           return false;
    });
    // FIM FORM NEWSLETTER
    
    
    
    
});