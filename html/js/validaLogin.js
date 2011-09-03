// -------------------------------- JS FWK DRE ---------------------------------//
/**
 * Função de validação de login
 *
 * @author André Coura
 * @since 1.0 - 05/07/2008
 * @return boolean
 */
jQuery(document).ready(function() { jQuery(":text").focus(); });

function validaLogin(){
    var retorno = true;
    setDefaultFields();
    if(jQuery("#login").val() == ""){
		jQuery("#log_erro").html("Usu&aacute;rio em branco!");
		jQuery("#log_erro").fadeIn("slow");
		retorno = false;
    }else{
    	jQuery("#log_erro").fadeOut("slow");
    }
    if(jQuery("#passwd").val() == ""){
		jQuery("#psw_erro").html("Senha em branco!");
		jQuery("#psw_erro").fadeIn("slow");
		retorno = false;
    }else if(jQuery("#passwd").val().length < 4){
    	jQuery("#psw_erro").html("Senha Inv&aacute;lida!");
    	jQuery("#psw_erro").fadeIn("slow");
    	retorno = false;
    }

    if(retorno){
    	jQuery('#formLogin').submit();
    }
}
/**
 * Função de exibição do erro para o usuário
 *
 * @author André Coura
 * @since 1.0 - 05/07/2008
 * @return VOID
 */
function setErroCampo(objHtml){
	objHtml.css({background:"#444", border:"1px red solid"});
}
/**
 * Define os campos defaults
 *
 * @author André Coura
 * @since 1.0 - 05/07/2008
 * @return Void
 */
function setDefaultFields(){
	jQuery(':input', this).each(function() {
		jQuery(this).css({background:"#000", border:"1px solid #888888"});
	});
}

function exibeMens(strMens, strDiv){
	jQuery('#'+ strDiv).css("display","none");
	jQuery('#'+ strDiv).text(strMens);
	jQuery('#'+strDiv).fadeIn("slow")
}

function escondeBarra(barra){
	jQuery(barra).slideUp("slow");
}

function verificaKey(event){
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;

//	if(window.event) // IE
//	{
//		keyCode = e.keyCode
//	}
//	else if(e.which) // Netscape/Firefox/Opera
//	{
//		keyCode = e.which
//	}

	if (keyCode == 13){
		validaLogin();
	}
	//alert("oi");
}

function confirmaSairLogin(strPag,strMens){
	if(confirm(strMens))
		location.replace(strPag);
	return false;
}