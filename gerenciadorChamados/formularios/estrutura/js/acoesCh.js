function mudaCorFundo(name1,name2,name3,name4,name5) { 
    jQuery("#"+name1).css("background-color","#3E6D8E");
    jQuery("#"+name2).css("background-color","#B3C3D0");
    jQuery("#"+name3).css("background-color","#B3C3D0");
    jQuery("#"+name4).css("background-color","#B3C3D0");
    jQuery("#"+name5).css("background-color","#B3C3D0");
} 
function mudarAba(strParams,aba){ 
    var carregando = jQuery('<div class="Progresso"><span>Aguarde...</span><br /><br /><img src="'+ RET_SERVIDOR +'arquivos/imagens/Progress.gif" /><div>');
    jQuery(".conteudoCrmIbs").html(carregando);
    jQuery.getJSON(RET_SERVIDOR +"ConsAjax.class.php?ajx="+strParams+"&aba="+aba+"&jsoncallback=?",function(data){
        if(data.resultado) {
            jQuery(".conteudoCrmIbs").html("");
            jQuery(".conteudoCrmIbs").html(data.retorno);
        } else {
            alert("nao");
        }
    }, "json");        
}