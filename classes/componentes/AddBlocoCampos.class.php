<?php
require_once (FWK_COMP."AbsCompHtml.class.php");

class AddBlocoCampos extends AbsCompHtml {

	public function getComponente($value = ""){
		try{
			self::regTagsCustomComp();
			self::setValue($value);
			self::regTags();
			self::registraOptions();
			self::setHtmlComp(self::getObjSmarty()->fetch(FWK_HTML_FORMS."addBlocoCamposDre.tpl"));
			self::setCampos();
		}catch(ElementsException $ex){
			die("ERRO: ".$ex->__toString());
		}
	}

	private function regTagsCustomComp(){
		self::getObjSmarty()->assign("WIDTH",(string)self::getObjXmlCompDados()->width);
		self::getObjSmarty()->assign("IDCOMPONENTE",(string)self::getObjXmlCompDados()->id);
		self::getObjSmarty()->assign("INICIA",((integer)self::getObjXmlCompDados()->inicio)-1);
		self::getObjSmarty()->assign("CLASS",(string)self::getObjXmlCompDados()->class);
		self::getObjSmarty()->assign("CSSAREA",(string)self::getObjXmlCompDados()->cssArea);

		$linhas = (string)self::getObjXmlCompDados()->linhas;

		if(isset($this->objXmlCompChilds->campo)){
			$arrOptions = array();
			$arrHidden = array();
			foreach ($this->objXmlCompChilds->campo as $opcao) {
				$type = strtolower((string)$opcao->tipo);
				$strObrig = ((boolean)$opcao->obrigatorio)?" <span class=\"campoObrigatorio\">*</span> ":"";

				switch ($type){
					case "inteiro":
						$keypress = "onkeypress=\"mascara(this,soNumeros)\"";
						break;
					case "double":
					$strUnidade = 3;
					$strDecimais = 2;
					if(isset($opcao->masckNum) && (string)$opcao->masckNum !="" )
						$strUnidade = (string)$opcao->masckNum;
					if(isset($opcao->masckDec) && (string)$opcao->masckDec !="" )
						$strDecimais = (string)$opcao->masckDec;
					$keypress = "onkeypress=\"mascaraParams(this,double, ".$strUnidade.", ".$strDecimais.")\"";
					break;
				}

				if($type == 'text' || $type == 'data' || $type == 'inteiro' || $type == 'double'){
					$tipoAux = ($type == 'inteiro' || $type == 'text'|| $type == 'double')?'text':'data';
					$arrOptions[]=array('linha' => (integer)$opcao->linha, 'label' => (string)$opcao->label,'tipo' => $tipoAux, 'keypress' => $keypress,
						'id' => (string)$opcao->id,'obrigatorio' => $strObrig,'maxlenght' => (string)$opcao->maxlenght, 'class' => (string)$opcao->class,
						'onchange' => "onchange=\"".(string)$opcao->onchange."\"");
				}
				elseif($type == 'select'){

					$strQuery = (string)$opcao->query;
					$strLoop = $opcao->loop;

					if($strQuery != ""){
						if(strpos($strQuery, "#idReferencia#") > 0){
							$strQuery = str_replace("#idReferencia#", parent::getIdReferencia(), $strQuery);
						}
						self::getBanco()->SetFetchMode(ADODB_FETCH_NUM);
						$optionsSelect = $arrQueryComp = self::getBanco()->GetAll($strQuery);
						$query = "S";
					}elseif($strLoop != ""){
						for($i=(integer)$strLoop->start; $i<=(integer)$strLoop->stop; $i++){
							$optionLoop[]= array('label' => $i, 'value' => $i);
						}
						$optionsSelect = $optionLoop;
						$query = "N";
					}else{
						for($i=0; $i<count($opcao->option); $i++){
							$optionPassados[] = (array)$opcao->option[$i];
						}
						$optionsSelect = $optionPassados;
						$query = "N";
					}

					$arrOptions[]=array('linha' => (integer)$opcao->linha, 'label' => (string)$opcao->label,'tipo' => strtolower((string)$opcao->tipo),
					'id' => (string)$opcao->id,'obrigatorio' => $strObrig, 'option' => $optionsSelect, 'query' => $query, 'keypress' => $keypress,
					'onchange' => "onchange=\"".(string)$opcao->onchange."\"");
				}elseif($type == 'textarea'){
					$arrOptions[]=array('linha' => (integer)$opcao->linha, 'label' => (string)$opcao->label,'tipo' => strtolower((string)$opcao->tipo),
						'id' => (string)$opcao->id,'obrigatorio' => $strObrig,'maxlenght' => (string)$opcao->maxlenght);
				}elseif($type == 'file'){
					$arrOptions[]=array('linha' => (integer)$opcao->linha, 'label' => (string)$opcao->label,'tipo' => strtolower((string)$opcao->tipo),
						'id' => (string)$opcao->id,'obrigatorio' => $strObrig, 'class' => (string)$opcao->class);
				}elseif($type == 'hidden'){
					$arrHidden[]=array('tipo' => $type,	'id' => (string)$opcao->id, 'class' => (string)$opcao->class);
				}elseif($type == 'checkbox'){
					$arrOptions[]=array('linha' => (integer)$opcao->linha, 'label' => (string)$opcao->label,'tipo' => 'checkbox', 'value'=> (string)$opcao->value,
						'id' => (string)$opcao->id, 'class' => (string)$opcao->class,
						'onchange' => "onchange=\"".(string)$opcao->onchange."\"");
				}
			}
		}

		$linhas = array();
		$linhasAux = array();
		for($i=0; $i<count($arrOptions); $i++){
			if($arrOptions[$i]["tipo"] == "select"){
				$strQuery = (string)$opcao->query;
				$options = array();
				foreach ($arrOptions[$i]["option"] as $option) {
					$type = (string)$option->tipo;
					if($arrOptions[$i]["query"] == "S"){
						$options[]= array('label' => $option[1], 'value' => $option[0]);
					}else
						$options[]= array('label' => $option['label'], 'value' => $option['value']);
				}
				$arrOptions[$i]["option"] = $options;
			}
			$linha = $arrOptions[$i]["linha"];
			$linhasAux[$linha]["linha"] = $linhas[$linha] = $linha;
			$linhasAux[$linha]["contador"] += 1;
		}
		for($i=0; $i<count($arrOptions); $i++){
			for($y=1; $y<=count($linhasAux); $y++){
				if($arrOptions[$i]["linha"] == $linhasAux[$y]["linha"]){
					$arrOptions[$i]["cont"] = $linhasAux[$y]["contador"];
				}
			}
		}

		self::getObjSmarty()->assign("ARR_HIDDEN", $arrHidden);
		self::getObjSmarty()->assign("ARR_LINHAS", $arrOptions);
		self::getObjSmarty()->assign("CONT_LINHAS", count($linhas)+1);
	}

	private function registraOptions(){
		try{

			if(!isset(self::getObjXmlCompDados()->classe) || self::getObjXmlCompDados()->classe == "")
				throw new ElementsException("Não foi passado o parametro 'classe' para o componente AddBlocoCampos");

			if(!isset(self::getObjXmlCompDados()->caminho) || self::getObjXmlCompDados()->caminho == "")
				throw new ElementsException("Não foi passado o parametro 'caminho' para o componente AddBlocoCampos");

			if(!isset(self::getObjXmlCompDados()->metodo) || self::getObjXmlCompDados()->metodo == "")
				throw new ElementsException("Não foi passado o parametro 'metodo' para o componente AddBlocoCampos");
			eval('$caminho = '.self::getObjXmlCompDados()->caminho.";");
			$classe = (string)self::getObjXmlCompDados()->classe;
			$metodo = (string)self::getObjXmlCompDados()->metodo;

			if(!file_exists($caminho.$classe.".class.php"))
				throw new ElementsException("Não foi a classe passada no caminho correspondente para o componente AddBlocoCampos");

			//tudo ok, chamo o dao
			require_once($caminho.$classe.".class.php");
			$objDao = new $classe();
			if(parent::getIdReferencia() != "")
				$arrCampos = $objDao->$metodo(parent::getIdReferencia());
			else
				$arrCampos = $objDao->$metodo();


			$this->objSmarty->assign("ARR_CAMPOS",$arrCampos);
			self::getObjSmarty()->assign("DADOS_AUX",(integer)self::getObjXmlCompDados()->inicio);
			self::getObjSmarty()->assign("DADOS",$arrCampos);

		}catch (ElementsException $e){
			throw new ElementsException($e->getMensagem());
		}


	}
}
?>