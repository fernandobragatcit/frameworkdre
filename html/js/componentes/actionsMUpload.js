/**
 * Ações Multi-upload
 *
 * @author André Coura
 * @since 1.0 22/12/2008
 */

function selCampoMUpLoad(valor,strNomeCampo){
	var strAddCampo = new String();
	for(var i=0; i<valor; i++){
		strAddCampo+=strCampo(i,strNomeCampo);
	}
	jQuery("#camposMultUpload").html(strAddCampo);
	jQuery("#numArquivosSel").val(valor);
}

function strCampo(idHtml,strNomeCampo){
	var strHtml = '<label>Arquivo '+(idHtml+1)+' :<span class="small" id="'+strNomeCampo+'_'+idHtml+'_erro"></span></label>';
	strHtml +='<input type="file" name="'+strNomeCampo+'_'+idHtml+'" id="'+strNomeCampo+'_'+idHtml+'" />';
	return strHtml;
}