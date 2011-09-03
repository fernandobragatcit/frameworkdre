
/**
 * @author André Coura
 * @since 1.0 - 24/08/08
 */
function addListBox(idOrigem,idDestino,strNomeCampo){
	var i;
	ListOrigem = document.getElementById(idOrigem);
	ListDestino = document.getElementById(idDestino);
	for (i = 0; i < ListOrigem.options.length ; i++){
		if (ListOrigem.options[i].selected == true){
			var Op = document.createElement("OPTION");
			Op.text = ListOrigem.options[i].text;
			Op.value = ListOrigem.options[i].value;
			ListDestino.options.add(Op);
			addCampoOculto(Op.value,strNomeCampo);
			try{
				ListOrigem.options.remove(i);
			}catch(e){
				try{ListOrigem.remove(i);}catch(e){}
			}
			i--;
		}
	}
}

function delListBox(idOrigem,idDestino,strNomeCampo){
	var i;
	ListOrigem = document.getElementById(idOrigem);
	ListDestino = document.getElementById(idDestino);
	for (i = 0; i < ListOrigem.options.length ; i++){
		if (ListOrigem.options[i].selected == true){
			var Op = document.createElement("OPTION");
			Op.text = ListOrigem.options[i].text;
			Op.value = ListOrigem.options[i].value;
			ListDestino.options.add(Op);
			removeCampoOculto(Op.value,strNomeCampo);
			try{
				ListOrigem.options.remove(i);
			}catch(e){
				try{ListOrigem.remove(i);}catch(e){}
			}
			i--;
		}
	}
}

function addCampoOculto(strValue,strNomeCampo){
	var strInput = '<input type="hidden" name="'+strNomeCampo+'_'+strValue+'" value="'+strValue+'" id="'+strNomeCampo+'_'+strValue+'" />'
	if(jQuery("#campos_"+strNomeCampo).html() != null)
		jQuery("#campos_"+strNomeCampo).html(jQuery("#campos_"+strNomeCampo).html()+strInput);
	else
		jQuery("#campos_"+strNomeCampo).html(strInput);
}

function removeCampoOculto(strValue,strNomeCampo){
	jQuery("#campos_"+strNomeCampo+" #"+strNomeCampo+"_"+strValue).remove();
}




