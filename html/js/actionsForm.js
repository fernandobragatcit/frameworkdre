jQuery.noConflict();
/**
 * Validação dos formulários din�micos
 *
 * @author André Coura
 * @since 3.0 - 27/09/2009
 */
//campos obrigtaórios
var arrIdFieldsObrig = new Array();
var arrTxtFields = new Array();
var arrTxtErroFields = new Array();
var arrIdErroFields = new Array();

//campos especiais
var arrIdFieldsEspec = new Array();
var arrTxtFieldsEspec = new Array();
var arrTipoFieldsEspec = new Array();
var arrTxtErroFieldsEspec = new Array();

//campos comparaveis
var arrIdFieldsComp = new Array();
var arrIdCompFields = new Array();
var arrTxtFieldsComp = new Array();
var arrTxtErroFieldsComp = new Array();

//tamnho campos
var arrIdFieldsTam = new Array();
var arrTxtFieldsTam = new Array();
var arrMaxFieldsTam = new Array();
var arrMinFieldsTam = new Array();

var strTxtFields = new String();

var fistFiled = true;

function validaForms(form){
	try{
		setDefaultFields();
		var erro = true;
		this.fistFiled = true;
		erro = validaCamposObrigatorios(erro);
//		erro = validaCamposEspeciais(erro);
		erro = validaCamposComparaveis(erro);
		erro = validaTamanhoCampos(erro);
		/**
		 * Mode o scroll da tela ate o primeiro campo obrigatório com erro
		 */
	  	if(this.arrIdErroFields != null && this.arrIdErroFields.length > 0){
			var targetOffset = jQuery("#"+this.arrIdErroFields[0]).offset().top;
		  	jQuery('html,body').animate({scrollTop: targetOffset-50}, 150);
		  	jQuery("#"+this.arrIdErroFields[0]).focus();
	  	}
	}catch(e){
		alert(e);
		return false;
	}
	return erro;
}

function validaTamanhoCampos(erro){
	try{
		for(var i=0;i<this.arrIdFieldsTam.length;i++){
			if(this.arrMaxFieldsTam[i] > 0 && jQuery('#'+this.arrIdFieldsTam[i]).val().length > this.arrMaxFieldsTam[i]){
				erro = false;
				setErroField(this.arrIdFieldsTam[i],this.arrTxtFieldsTam[i],"O campo pode conter no maximo "+this.arrMaxFieldsTam[i]+" caracteres");
			}
			if(this.arrMinFieldsTam[i] > 0 && jQuery('#'+this.arrIdFieldsTam[i]).val().length < this.arrMinFieldsTam[i]){
				erro = false;
				setErroField(this.arrIdFieldsTam[i],this.arrTxtFieldsTam[i],"O campo deve conter no minimo "+this.arrMinFieldsTam[i]+" caracteres");
			}
		}
	}catch(e){
		throw "Nao foi possivel encontrar o campo: "+ this.arrIdFieldsTam[i] +" do xml no tpl.";
	}
	return erro;
}

function validaCamposComparaveis(erro){
	try{
		for(var i=0;i<this.arrIdFieldsComp.length;i++){
			if(jQuery('#'+this.arrIdFieldsComp[i]).val() != jQuery('#'+this.arrIdCompFields[i]).val()){
				erro = false;
				setErroField(this.arrIdFieldsComp[i],this.arrTxtFieldsComp[i],this.arrTxtErroFieldsComp[i]);
			}
		}
	}catch(e){
		throw "Nao foi possivel encontrar o campo: "+ this.arrIdFieldsComp[i] +" do xml no tpl.";
	}
	return erro;
}

function validaCamposEspeciais(erro){
	try{
		for(var i=0;i<this.arrIdFieldsEspec.length;i++){
			switch (this.arrTipoFieldsEspec[i]) {
				case "email":
					if(verificaEmail(this.arrIdFieldsEspec[i])){
						erro = false;
						setErroField(this.arrIdFieldsEspec[i],this.arrTxtFieldsEspec[i],this.arrTxtErroFieldsEspec[i]);
					}
					break;
				case "integer":
				case "inteiro":
					try{
						if(jQuery('#'+this.arrIdFieldsEspec[i]).val() == ""){
							jQuery('#'+this.arrIdFieldsEspec[i]).val(null);
						}
						if(!soNumeros(jQuery('#'+this.arrIdFieldsEspec[i]).val())){
							erro = false;
							setErroField(this.arrIdFieldsEspec[i],this.arrTxtFieldsEspec[i],this.arrTxtErroFieldsEspec[i]);
						}
					}catch(e){
					}
					break;
			}
		}
	}catch(e){
		throw "Nao foi possivel encontrar o campo: "+ this.arrIdFieldsEspec[i] +" do xml no tpl.";
	}
	return erro;
}

function validaCamposObrigatorios(erro){
	try{
		for(var i=0;i<this.arrIdFieldsObrig.length;i++){
			if(verificaCampoSeVazio(this.arrIdFieldsObrig[i])){
				erro = false;
				setErroField(this.arrIdFieldsObrig[i],this.arrTxtFields[i],this.arrTxtErroFields[i]);
			}
		}
	}catch(e){
		throw "Nao foi possivel encontrar o campo: "+ this.arrIdFieldsTam[i] +" do xml no tpl.";
	}
	return erro;
}

/**
 * Atribui os ids dos campos a serem validados
 */
function setCampoObrigatorio(id,txt, erro){
	this.arrIdFieldsObrig.push(id);
	this.arrTxtFields.push(txt);
	this.arrTxtErroFields.push(erro);
}
function unsetCampoObrigatorio(id,txt, erro){
	this.arrIdFieldsObrig.pop(id);
	this.arrTxtFields.pop(txt);
	this.arrTxtErroFields.pop(erro);
}

function setCampoEspecial(id,txt,tipo,erro){
	this.arrIdFieldsEspec.push(id);
	this.arrTxtFieldsEspec.push(txt);
	this.arrTipoFieldsEspec.push(tipo);
	this.arrTxtErroFieldsEspec.push(erro);
}

function setCampoComparavel(id,idComp,txt,erro){
	this.arrIdFieldsComp.push(id);
	this.arrIdCompFields.push(idComp);
	this.arrTxtFieldsComp.push(txt);
	this.arrTxtErroFieldsComp.push(erro);
}

function setTamanhoCampos(id,txt,max, min){
	this.arrIdFieldsTam.push(id);
	this.arrTxtFieldsTam.push(txt);
	this.arrMaxFieldsTam.push(max);
	this.arrMinFieldsTam.push(min);
}

/**
 * Verifica se o campo esta correto
 */
function verificaCampoSeVazio(id){
	var boolResult;
	try{
		boolResult = jQuery('#'+id).val() == "";
	}catch(err){
		alert("aqui verificaCampoSeVazio actionsForm");
	}

	return boolResult;
}

/**
 * Definição da String de erros
 */
function setErroField(id,txt, texto){
	jQuery('#'+id+'_erro').hide();
	jQuery('#'+id+'_erro').html(texto);
	jQuery('#'+id+'_erro').fadeIn("slow");
	setFieldErroCampo(id);
  	return;
}

function verificaEmail(id){
	return (jQuery('#'+id).val() == "" || jQuery('#'+id).val().indexOf('@')==-1 || jQuery('#'+id).val().indexOf('.')==-1);
}

/**
 * destaca o campo com erro
 */
function setFieldErroCampo(id){
	jQuery('#'+id).css({border:"1px #FF0000 solid"});
	this.arrIdErroFields.push(id);
}

/**
 * Define os campos defaults
 */
function setDefaultFields(){
	this.arrIdErroFields = new Array();
	jQuery(':input:not(:button)').each(function(i) {
		jQuery(this).css({border:"1px solid #D3C4AB"});
		jQuery('#'+jQuery(this).attr("id")+'_erro').html("");
	});
}

/**
 * Função de redirecionamento de p�gina
 */
function vaiPara(strPag){
	location.replace(strPag);
	return false;
}

/**
 * Função de redirecionamento com confirma��o
 */
function confirmIr(strPag,strMens){
	if(confirm(strMens))
		location.replace(strPag);
	return false;
}

/**
 * Método de preenchimento simples do valor de um campo no formulário
 *
 * @autor André Coura
 * @since 1.0 - 10/08/08
 */
function preencheCampo(strId, strValor){
	jQuery('#'+strId).val(strValor);
}

//jQuery.mask.addPlaceholder('~',"[+-]");

function mascara(o,f){
	v_obj=o;
	v_fun=f;
	setTimeout("execmascara()",1);
}

function mascaraParams(o,f, param1, param2){
    v_obj=o;
    v_fun=f;
    v_param1 = param1;
    v_param2 = param2;
    setTimeout("execmascaraParams()",1);
}

function execmascaraParams(){
	v_obj.value=v_fun(v_obj.value, v_param1, v_param2);
}
function execmascara(){
    v_obj.value=v_fun(v_obj.value);
}

function soNumeros(v){
	//condição para aceitar apenas números e os caracteres especiais
	v=v.replace(/[^NAEI0-9]/g,"");
	//v=v.replace(/^(\A|E|I|NN|NAN|NEN|NIN|NAA|NEE|NII|NAE|NAI|NEA|NEI|NIA|NIE)/,"");
	v=v.replace(/^(\A|E|I|NN)/,"");
	v=v.replace(/^(\D)(\d)/,"$1");
	v=v.replace(/^(\D{2})(\d)/,"$1");
	v=v.replace(/^(\D{2})(\D)/,"$1");
	return v;
}

function telefone(v){
	//condição para aceitar apenas números e os caracteres especiais
	v=v.replace(/[^NAEI0-9]/g,"");
	v=v.replace(/^(\A|E|I|NN)/,"");
	v=v.replace(/^(\D)(\d)/,"$1");
	v=v.replace(/^(\D{2})(\d)/,"$1");
	v=v.replace(/^(\D{2})(\D)/,"$1");

	v=v.replace(/^(\d\d)(\d)/g,"($1) $2");
	v=v.replace(/(\d{4})(\d)/,"$1-$2");
	return v;
}

function cpf(v){
	//condição para aceitar apenas números e os caracteres especiais
	v=v.replace(/[^NAEI0-9]/g,"");
	//v=v.replace(/^(\A|E|I|NN|NAN|NEN|NIN|NAA|NEE|NII|NAE|NAI|NEA|NEI|NIA|NIE)/,"");
	v=v.replace(/^(\A|E|I|NN)/,"");
	v=v.replace(/^(\D)(\d)/,"$1");
	v=v.replace(/^(\D{2})(\d)/,"$1");
	v=v.replace(/^(\D{2})(\D)/,"$1");

	 v=v.replace(/(\d{3})(\d)/,"$1.$2");
	 v=v.replace(/(\d{3})(\d)/,"$1.$2");
	 v=v.replace(/(\d{3})(\d{1,2})$/,"$1-$2");
	 return v;
}

function cep(v){
	//condição para aceitar apenas números e os caracteres especiais
	v=v.replace(/[^NAEI0-9]/g,"");
	//v=v.replace(/^(\A|E|I|NN|NAN|NEN|NIN|NAA|NEE|NII|NAE|NAI|NEA|NEI|NIA|NIE)/,"");
	v=v.replace(/^(\A|E|I|NN)/,"");
	v=v.replace(/^(\D)(\d)/,"$1");
	v=v.replace(/^(\D{2})(\d)/,"$1");
	v=v.replace(/^(\D{2})(\D)/,"$1");
	//aceita a formatação do cep
	v=v.replace(/^(\d{5})(\d)/,"$1-$2");

	return v;
}

function email(v){
	//condição para aceitar apenas números e os caracteres especiais
	v=v.replace(/[^NAEI0-9a-z@\.]/g,"");
	v=v.replace(/^[\.@]/,"");
	v=v.replace(/(\.\.)/,"\.");
	v=v.replace(/(@@)/,"@");

	return v;
}

function cnpj(v){
	//condição para aceitar apenas números e os caracteres especiais
	v=v.replace(/[^NAEI0-9]/g,"");
	//v=v.replace(/^(\A|E|I|NN|NAN|NEN|NIN|NAA|NEE|NII|NAE|NAI|NEA|NEI|NIA|NIE)/,"");
	v=v.replace(/^(\A|E|I|NN)/,"");
	v=v.replace(/^(\D)(\d)/,"$1");
	v=v.replace(/^(\D{2})(\d)/,"$1");
	v=v.replace(/^(\D{2})(\D)/,"$1");

	 v=v.replace(/^(\d{2})(\d)/,"$1.$2");
	 v=v.replace(/^(\d{2})\.(\d{3})(\d)/,"$1.$2.$3");
	 v=v.replace(/\.(\d{3})(\d)/,".$1/$2");
	 v=v.replace(/(\d{4})(\d)/,"$1-$2");
	 return v;
}

function romanos(v){
	 v=v.toUpperCase();
	 v=v.replace(/[^IVXLCDM]/g,"");
	 while(v.replace(/^M{0,4}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/,"")!="");
	 v=v.replace(/.$/,"");
	 return v;
}

function site(v){
	 v=v.replace(/^http:\/\/?/,"");
	 dominio=v;
	 caminho="";
	 if(v.indexOf("/")>-1);
	 dominio=v.split("/")[0];
	 caminho=v.replace(/[^\/]*/,"");
	 dominio=dominio.replace(/[^\w\.\+-:@]/g,"");
	 caminho=caminho.replace(/[^\w\d\+-@:\?&=%\(\)\.]/g,"");
	 caminho=caminho.replace(/([\?&])=/,"$1");
	 if(caminho!="")dominio=dominio.replace(/\.+$/,"");
	 v="http://"+dominio+caminho;
	 return v;
}
/**
 *
 * @param v - TEXTO A SER "MARCARADO"
 * @param u - UNIDADES DA M�?SCARA.EX: 123,45 - uuu.dd
 * @param d - DECIMAIS DA M�?SCARA. EX: 123,45 - uuu.dd
 * @returns string formatada
 */
function double(v, u, d){
	v=v.replace(/[^NAEI0-9\.]/g,"");
	v=v.replace(/^(\A|E|I|NN|N\.)/,"");
	v=v.replace(/^(\D)(\d)/,"$1");
	v=v.replace(/^(\D{2})(\d)/,"$1");
	v=v.replace(/^(\D{2})(\D)/,"$1");

	v=v.replace(/^\./g,"");//para nunca começar com "."(ponto)
	v=v.replace(/(\.\.)/g,".");//para não repetir 2 pontos seguidos(ponto)
	v = v.replace(eval("/^(\\d{"+u+"})(\\d{"+d+"})/"),"$1.$2");
	return v;//
}

function hora(v){
	v=v.replace(/[^NAEI0-9\.]/g,"");
	v=v.replace(/^(\A|E|I|NN|N\.)/,"");
	v=v.replace(/^(\D)(\d)/,"$1");
	v=v.replace(/^(\D{2})(\d)/,"$1");
	v=v.replace(/^(\D{2})(\D)/,"$1");

	v=v.replace(/^\:/g,"");//para nunca comeÃ§ar com ":"(ponto)
	v=v.replace(/(\:\:)/g,":");//para nÃ£o repetir 2 pontos seguidos(ponto)
	v = v.replace(eval("/^(\\d{2})(\\d{2})/"),"$1:$2");
	return v;//
}

function longlat(v){
	//aceito apenas sinais "+" e "-", números e o "."
	v=v.replace(/[^NAEI\+\-0-9\.]/g,"");
	//o primeiro dígito tem que ser o sinal
	v=v.replace(/^[^\+\-NAEI]/g,"");
	v=v.replace(/^(\A|E|I|NN)/,"");
	v=v.replace(/^(\D{2})(\d)/,"$1");
	v=v.replace(/^(\+|\-)(\D)/,"$1");
	v=v.replace(/^(\D)(\+|\-)/,"$1");
	v=v.replace(/^(\D{2})(\D)/,"$1");

	v = v.replace(/(\d{3})(\d{7})(\d)/,"$1.$2");
	return v;//
}

function verCampoPreenchido(idCampo, idCampoDesab){
	if((jQuery('#'+idCampo).val()!="")){
		jQuery('#'+idCampoDesab).attr('disabled', 'disabled');
		document.getElementById(idCampoDesab).options[0].selected;
		jQuery('#'+idCampoDesab+"_asterisco").hide();
	}else{
		jQuery('#'+idCampoDesab).removeAttr("disabled");
		jQuery('#'+idCampoDesab+"_asterisco").show();
	}
}

function desabilitaCampoNaoPreenchido(idCampo1, idCampo2){
	if(jQuery('#'+idCampo1).val()=="" && jQuery('#'+idCampo2).val()==""){
		return;
	}else if(jQuery('#'+idCampo1).val()!="" && jQuery('#'+idCampo2).val()==""){
		jQuery('#'+idCampo2).attr('disabled', 'disabled');
		jQuery('#'+idCampo1).removeAttr("disabled");
	}else{
		jQuery('#'+idCampo1).attr('disabled', 'disabled');
		jQuery('#'+idCampo2).removeAttr("disabled");
	}
}

function carregaImgCaptcha(idImgCaptcha, endimgCaptcha){
	jQuery('#'+idImgCaptcha).attr("src",endimgCaptcha+'?'+Math.random());
	return false;
}

function setMascaraCampo(id,txt,tipo,mascara,erro){
	//jQuery.mask.addPlaceholder("~","[+-]");
	jQuery("#"+id).mask(mascara);
}


function verificaSenha(strParams, campo){
	jQuery.getJSON(RET_SERVIDOR +"ConsAjax.class.php"+"?ajx="+strParams+"&val="+campo.value+"&jsoncallback=?",function(data){
		if(data.resultado == false){
			jQuery("#"+campo.id+"_erro").html(data.mensagem);
			jQuery("#"+campo.id).css("border", "1px solid rgb(255, 0, 0)");
		}else{
			jQuery("#"+campo.id+"_erro").html("");
			jQuery("#"+campo.id).css("border", "");
		}
	});
}

function alteraIdiomaPag(valor){
	vaiPara( "?c=" +Base64.encode( pagAtual +'&idioma='+valor));
}
var Base64 = {

	// private property
	_keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

	// public method for encoding
	encode : function (input) {
		var output = "";
		var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
		var i = 0;

		input = Base64._utf8_encode(input);

		while (i < input.length) {

			chr1 = input.charCodeAt(i++);
			chr2 = input.charCodeAt(i++);
			chr3 = input.charCodeAt(i++);

			enc1 = chr1 >> 2;
			enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
			enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
			enc4 = chr3 & 63;

			if (isNaN(chr2)) {
				enc3 = enc4 = 64;
			} else if (isNaN(chr3)) {
				enc4 = 64;
			}

			output = output +
			this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
			this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

		}

		return output;
	},

	// public method for decoding
	decode : function (input) {
		var output = "";
		var chr1, chr2, chr3;
		var enc1, enc2, enc3, enc4;
		var i = 0;

		input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

		while (i < input.length) {

			enc1 = this._keyStr.indexOf(input.charAt(i++));
			enc2 = this._keyStr.indexOf(input.charAt(i++));
			enc3 = this._keyStr.indexOf(input.charAt(i++));
			enc4 = this._keyStr.indexOf(input.charAt(i++));

			chr1 = (enc1 << 2) | (enc2 >> 4);
			chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
			chr3 = ((enc3 & 3) << 6) | enc4;

			output = output + String.fromCharCode(chr1);

			if (enc3 != 64) {
				output = output + String.fromCharCode(chr2);
			}
			if (enc4 != 64) {
				output = output + String.fromCharCode(chr3);
			}

		}

		output = Base64._utf8_decode(output);

		return output;

	},

	// private method for UTF-8 encoding
	_utf8_encode : function (string) {
		string = string.replace(/\r\n/g,"\n");
		var utftext = "";

		for (var n = 0; n < string.length; n++) {

			var c = string.charCodeAt(n);

			if (c < 128) {
				utftext += String.fromCharCode(c);
			}
			else if((c > 127) && (c < 2048)) {
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			}
			else {
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}

		}

		return utftext;
	},

	// private method for UTF-8 decoding
	_utf8_decode : function (utftext) {
		var string = "";
		var i = 0;
		var c = c1 = c2 = 0;

		while ( i < utftext.length ) {

			c = utftext.charCodeAt(i);

			if (c < 128) {
				string += String.fromCharCode(c);
				i++;
			}
			else if((c > 191) && (c < 224)) {
				c2 = utftext.charCodeAt(i+1);
				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
				i += 2;
			}
			else {
				c2 = utftext.charCodeAt(i+1);
				c3 = utftext.charCodeAt(i+2);
				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			}

		}

		return string;
	}

};
/**
 * Adicionar novo campo de texto para cadastro multiplo de dados
 *
 * @author André Coura
 * @since 1.0 - 01/08/2010
 */
function addCampoText(strNome,strId,strStyle,strClass,strTitle, strNomeBtn,proxBtn){
	var strComponente = "";
	//campo de texto
	strComponente += "<input type=\"text\" name=\""+strNome+"_"+proxBtn+"\" id=\""+strId+"_"+proxBtn+"\"";
	if(strStyle!="")
		strComponente += "style=\""+strStyle+"\"";
	if(strClass!="")
		strComponente += "class=\""+strClass+"\"";
	if(strTitle!="")
		strComponente += "title=\""+strTitle+"\" />";
	//botao
	strComponente += "<button type=\"button\" name=\"addField\" class=\"btnPeq\"";
	strComponente += "onClick=\"javascript:addCampoText('"+strNome+"','"+strId+"','"+strStyle+"','"+strClass+"','"+strTitle+"', '"+strNomeBtn+"','"+(proxBtn + 1)+"');\">";
	strComponente += strNomeBtn+"</button>";
	//div para o proximo campo
	strComponente += "<div id=\"contentAddMultCampoText_"+(proxBtn + 1)+"\"  class=\"spacoComponente\"></div>";
	jQuery("#contentAddMultCampoText_"+proxBtn).html(strComponente);
}

/**
 * Adicionar novo bloco de campos para cadastro multiplo de dados
 *
 * @author Matheus Vieira
 * @since 1.0 - 21/06/2011
 */
function addBlocoCampo( strId ){
	var html = jQuery("#"+strId+"Template").html();
	var count = jQuery("#"+strId+"Count").val();
	jQuery("#"+strId+"Count").val(parseInt(count)+1);
	html = html.split("number").join(""+parseInt(count));
	jQuery("#"+strId+"Add").append(html);
}

function removeBlocoCampo(strId, id){
	var count2 = jQuery("#"+strId+"Count").val();
	jQuery(id).parent().remove('div');
	jQuery("#"+strId+"Count").val(parseInt(count2)-1);
}

function exibeCampos(objCB, idCampo){
	if(objCB.checked){
		jQuery("#" + idCampo ).show();
	}else{
		jQuery("#" + idCampo ).hide();
	}
}

function verificaCheckVisibilidade(charVal, idCampo){
	if(charVal == 'S'){
		jQuery("#"+idCampo).show();
	}else{

		jQuery("#"+idCampo).hide();
	}
}

function verificaVisibilidade(charVal, idCampo){
	if(charVal){
		jQuery("#"+idCampo).show();
	}else{

		jQuery("#"+idCampo).hide();
	}
}

function verificaCombo(objCombo, valor, idCampo){
	if(objCombo.value == valor){
		jQuery("#" + idCampo ).show();
	}else{
		jQuery("#" + idCampo ).hide();
	}
}

function verificaComboOposto(objCombo, valor, idCampo){
	if(objCombo.value == valor){
		jQuery("#" + idCampo ).hide();
	}else{
		jQuery("#" + idCampo ).show();
	}
}


function copyText( idText ) {
	jQuery('.datePickerImage').css('text-indent','-99999px');

	selectInput = jQuery('div.'+idText+' .datePickerImage').attr('id');

	jQuery('#'+idText).val(jQuery('.'+idText+' .datePickerImage').val());

	//alert(jQuery('.'+idText+' .datePickerImage').val());

}

function habilitaCampo( idCampo ) {
	if(jQuery('#'+idCampo).attr('disabled')){
		jQuery('#'+idCampo).attr('disabled', '');
	}else{
		jQuery('#'+idCampo).attr('disabled', 'disabled');
	}
}

// Funcaoo para formatar datas.

/*
 * Date Format 1.2.3
 * (c) 2007-2009 Steven Levithan <stevenlevithan.com>
 * MIT license
 *
 * Includes enhancements by Scott Trenda <scott.trenda.net>
 * and Kris Kowal <cixar.com/~kris.kowal/>
 *
 * Accepts a date, a mask, or a date and a mask.
 * Returns a formatted version of the given date.
 * The date defaults to the current date/time.
 * The mask defaults to dateFormat.masks.default.
 */

var dateFormat = function () {
	var	token = /d{1,4}|m{1,4}|yy(?:yy)?|([HhMsTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g,
		timezone = /\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,
		timezoneClip = /[^-+\dA-Z]/g,
		pad = function (val, len) {
			val = String(val);
			len = len || 2;
			while (val.length < len) val = "0" + val;
			return val;
		};

	// Regexes and supporting functions are cached through closure
	return function (date, mask, utc) {
		var dF = dateFormat;

		// You can't provide utc if you skip other args (use the "UTC:" mask prefix)
		if (arguments.length == 1 && Object.prototype.toString.call(date) == "[object String]" && !/\d/.test(date)) {
			mask = date;
			date = undefined;
		}

		// Passing date through Date applies Date.parse, if necessary
		date = date ? new Date(date) : new Date;
		if (isNaN(date)) throw SyntaxError("invalid date");

		mask = String(dF.masks[mask] || mask || dF.masks["default"]);

		// Allow setting the utc argument via the mask
		if (mask.slice(0, 4) == "UTC:") {
			mask = mask.slice(4);
			utc = true;
		}

		var	_ = utc ? "getUTC" : "get",
			d = date[_ + "Date"](),
			D = date[_ + "Day"](),
			m = date[_ + "Month"](),
			y = date[_ + "FullYear"](),
			H = date[_ + "Hours"](),
			M = date[_ + "Minutes"](),
			s = date[_ + "Seconds"](),
			L = date[_ + "Milliseconds"](),
			o = utc ? 0 : date.getTimezoneOffset(),
			flags = {
				d:    d,
				dd:   pad(d),
				ddd:  dF.i18n.dayNames[D],
				dddd: dF.i18n.dayNames[D + 7],
				m:    m + 1,
				mm:   pad(m + 1),
				mmm:  dF.i18n.monthNames[m],
				mmmm: dF.i18n.monthNames[m + 12],
				yy:   String(y).slice(2),
				yyyy: y,
				h:    H % 12 || 12,
				hh:   pad(H % 12 || 12),
				H:    H,
				HH:   pad(H),
				M:    M,
				MM:   pad(M),
				s:    s,
				ss:   pad(s),
				l:    pad(L, 3),
				L:    pad(L > 99 ? Math.round(L / 10) : L),
				t:    H < 12 ? "a"  : "p",
				tt:   H < 12 ? "am" : "pm",
				T:    H < 12 ? "A"  : "P",
				TT:   H < 12 ? "AM" : "PM",
				Z:    utc ? "UTC" : (String(date).match(timezone) || [""]).pop().replace(timezoneClip, ""),
				o:    (o > 0 ? "-" : "+") + pad(Math.floor(Math.abs(o) / 60) * 100 + Math.abs(o) % 60, 4),
				S:    ["th", "st", "nd", "rd"][d % 10 > 3 ? 0 : (d % 100 - d % 10 != 10) * d % 10]
			};

		return mask.replace(token, function ($0) {
			return $0 in flags ? flags[$0] : $0.slice(1, $0.length - 1);
		});
	};
}();

// Some common format strings
dateFormat.masks = {
	"default":      "ddd mmm dd yyyy HH:MM:ss",
	shortDate:      "m/d/yy",
	mediumDate:     "mmm d, yyyy",
	longDate:       "mmmm d, yyyy",
	fullDate:       "dddd, mmmm d, yyyy",
	shortTime:      "h:MM TT",
	mediumTime:     "h:MM:ss TT",
	longTime:       "h:MM:ss TT Z",
	isoDate:        "yyyy-mm-dd",
	isoTime:        "HH:MM:ss",
	isoDateTime:    "yyyy-mm-dd'T'HH:MM:ss",
	isoUtcDateTime: "UTC:yyyy-mm-dd'T'HH:MM:ss'Z'"
};

// Internationalization strings
dateFormat.i18n = {
	dayNames: [
		"Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat",
		"Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"
	],
	monthNames: [
		"Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec",
		"January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
	]
};

// For convenience...
Date.prototype.format = function (mask, utc) {
	return dateFormat(this, mask, utc);
};

var ua = new navigator.userAgent.toLowerCase();
if (ua.indexOf(" chrome/") >= 0 || ua.indexOf(" firefox/") >= 0 || ua.indexOf(' gecko/') >= 0) {
	var StringMaker = function () {
		this.str = "";
		this.length = 0;
		this.append = function (s) {
			this.str += s;
			this.length += s.length;
		};
		this.prepend = function (s) {
			this.str = s + this.str;
			this.length += s.length;
		};
		this.toString = function () {
			return this.str;
		};
	};
} else {
	var StringMaker = function () {
		this.parts = [];
		this.length = 0;
		this.append = function (s) {
			this.parts.push(s);
			this.length += s.length;
		};
		this.prepend = function (s) {
			this.parts.unshift(s);
			this.length += s.length;
		};
		this.toString = function () {
			return this.parts.join('');
		};
	};
}

// This code was written by Tyler Akins and has been placed in the
// public domain.  It would be nice if you left this header intact.
// Base64 code from Tyler Akins -- http://rumkin.com

var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";

function encode64(input) {
	var output = new StringMaker();
	var chr1, chr2, chr3;
	var enc1, enc2, enc3, enc4;
	var i = 0;

	while (i < input.length) {
		chr1 = input.charCodeAt(i++);
		chr2 = input.charCodeAt(i++);
		chr3 = input.charCodeAt(i++);

		enc1 = chr1 >> 2;
		enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
		enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
		enc4 = chr3 & 63;

		if (isNaN(chr2)) {
			enc3 = enc4 = 64;
		} else if (isNaN(chr3)) {
			enc4 = 64;
		}

		output.append(keyStr.charAt(enc1) + keyStr.charAt(enc2) + keyStr.charAt(enc3) + keyStr.charAt(enc4));
   }

   return output.toString();
}

function decode64(input) {
	var output = new StringMaker();
	var chr1, chr2, chr3;
	var enc1, enc2, enc3, enc4;
	var i = 0;

	// remove all characters that are not A-Z, a-z, 0-9, +, /, or =
	input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

	while (i < input.length) {
		enc1 = keyStr.indexOf(input.charAt(i++));
		enc2 = keyStr.indexOf(input.charAt(i++));
		enc3 = keyStr.indexOf(input.charAt(i++));
		enc4 = keyStr.indexOf(input.charAt(i++));

		chr1 = (enc1 << 2) | (enc2 >> 4);
		chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
		chr3 = ((enc3 & 3) << 6) | enc4;

		output.append(String.fromCharCode(chr1));

		if (enc3 != 64) {
			output.append(String.fromCharCode(chr2));
		}
		if (enc4 != 64) {
			output.append(String.fromCharCode(chr3));
		}
	}

	return output.toString();
}

