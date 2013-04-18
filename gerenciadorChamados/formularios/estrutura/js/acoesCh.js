function mudaCorFundo(name1,name2,name3,name4,name5,name6,name7, name8) { 
    jQuery("#"+name1).css("background-color","#3E6D8E");
    jQuery("#"+name2).css("background-color","#B3C3D0");
    jQuery("#"+name3).css("background-color","#B3C3D0");
    jQuery("#"+name4).css("background-color","#B3C3D0");
    jQuery("#"+name5).css("background-color","#B3C3D0");
    jQuery("#"+name6).css("background-color","#B3C3D0");
    jQuery("#"+name7).css("background-color","#B3C3D0");
    jQuery("#"+name8).css("background-color","#B3C3D0");
} 
function mudaCorAba(name1) { 
    jQuery("#"+name1).css("background-color","#3E6D8E");  
} 
function mudarAba(strParams,aba){ 
    var carregando = jQuery('<div class="Progresso"><span>Aguarde...</span><br /><br /><img src="'+ RET_SERVIDOR +'arquivos/imagens/Progress.gif" /><div>');
    jQuery(".conteudoChamadoIbs").html(carregando);
    jQuery.getJSON(RET_SERVIDOR +"ConsAjax.class.php?ajx="+strParams+"&aba="+aba+"&jsoncallback=?",function(data){
        if(data.resultado) {
            jQuery(".conteudoChamadoIbs").html("");
            jQuery(".conteudoChamadoIbs").html(data.retorno);
        } else {
            alert("nao");
        }
    }, "json");        
}