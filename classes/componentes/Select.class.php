<?php
require_once (FWK_COMP."AbsCompHtml.class.php");

class Select extends AbsCompHtml {

	public function getComponente($value = ""){
		try{
			self::setValue($value);
			self::regTags();
			self::registraOptions();
			self::setHtmlComp($this->objSmarty->fetch(FWK_HTML_FORMS."selectDre.tpl"));
			self::setCampos();
		}catch(ElementsException $ex){
			die("ERRO: ".$ex->__toString());
		}
    }

    private function registraOptions(){
		$this->objSmarty->assign("OPTIONS_SELECT_DRE",self::getDadosOptions());
    }

    private function getDadosOptions(){
		$this->objSmarty->assign("TIPO_OPTIONS","Select");
		if(isset($this->objXmlCompChilds->query) && (string)$this->objXmlCompChilds->query != ""){
			$strQuery = (string)$this->objXmlCompChilds->query;
			if(strpos($strQuery, "#idReferencia#") > 0){
				$strQuery = str_replace("#idReferencia#", parent::getIdReferencia(), $strQuery);
			}
			if(strpos($strQuery, "#idReferencia2#") > 0){
				$strQuery = str_replace("#idReferencia2#", parent::getIdReferencia2(), $strQuery);
			}
			self::getBanco()->SetFetchMode(ADODB_FETCH_NUM);
			$arrQueryComp = self::getBanco()->GetAll($strQuery);
			//if(self::getValue() != ""){
				$this->objSmarty->assign("OPTION_SELECTED",self::getValue());
			//}

			for($i=0;$i<count($arrQueryComp);$i++){
				if((string)$this->objXmlCompChilds->valueCrypt){
					$arrQueryComp[$i][0] =Cryptografia::cryptData($arrQueryComp[$i][0]);
				}
				$arrQueryComp[$i][0] = utf8_encode($arrQueryComp[$i][0]);
				$arrQueryComp[$i][1] = utf8_encode($arrQueryComp[$i][1]);
			}



			$this->objSmarty->assign("ARR_OPTIONS",$arrQueryComp);
		}else if(isset($this->objXmlCompChilds->array)){
			if(isset($this->objXmlCompChilds->array->option)){
				$arrOptions = array();
				foreach ($this->objXmlCompChilds->array->option as $option) {
					$arrOptions[]=array((string)$option->value,(string)$option->text);
				}
				$this->objSmarty->assign("OPTION_SELECTED",self::getValue());
				$this->objSmarty->assign("ARR_OPTIONS",$arrOptions);
			}
		}else if(isset($this->objXmlCompChilds->dao)){
			try{
				if(!isset($this->objXmlCompChilds->dao->classe) || $this->objXmlCompChilds->dao->classe == "")
					throw new ElementsException("Não foi passado o parametro 'classe' para o componente select");
				
				if(!isset($this->objXmlCompChilds->dao->caminho) || $this->objXmlCompChilds->dao->caminho == "")
					throw new ElementsException("Não foi passado o parametro 'caminho' para o componente select");
				
				if(!isset($this->objXmlCompChilds->dao->metodo) || $this->objXmlCompChilds->dao->metodo == "")
					throw new ElementsException("Não foi passado o parametro 'metodo' para o componente select");
				
				eval('$caminho = '.$this->objXmlCompChilds->dao->caminho.";");
				$classe = (string)$this->objXmlCompChilds->dao->classe;
				$metodo = (string)$this->objXmlCompChilds->dao->metodo;
				
				if(!file_exists($caminho.$classe.".class.php"))
					throw new ElementsException("Não foi a classe passada no caminho correspondente para o componente select");
					
				//tudo ok, chamo o dao
				require_once($caminho.$classe.".class.php");
				$objDao = new $classe();
				
				if(parent::getIdReferencia() != "")
					$arrOptions = $objDao->$metodo(parent::getIdReferencia());
				else
					$arrOptions = $objDao->$metodo();

					$this->objSmarty->assign("OPTION_SELECTED",self::getValue());
				$this->objSmarty->assign("ARR_OPTIONS",$arrOptions);
			}catch (ElementsException $e){
				throw new ElementsException($e->getMensagem());
			}

		}else if(isset($this->objXmlCompChilds->loop)){
			if(isset($this->objXmlCompChilds->loop)){
				$arrOptions = array();
				$start = 0;
				$sufixo = "";
				$prefixo = "";
				$step = 1;
				if(isset($this->objXmlCompChilds->loop->start) && $this->objXmlCompChilds->loop->start != "")
					$start = (int)$this->objXmlCompChilds->loop->start;
				if(isset($this->objXmlCompChilds->loop->sufixo) && $this->objXmlCompChilds->loop->sufixo != "")
					$sufixo = (string)$this->objXmlCompChilds->loop->sufixo;
				if(isset($this->objXmlCompChilds->loop->prefixo) && $this->objXmlCompChilds->loop->prefixo != "")
					$prefixo = (string)$this->objXmlCompChilds->loop->prefixo;
				if(isset($this->objXmlCompChilds->loop->step) && $this->objXmlCompChilds->loop->step != "")
					$step = (int)$this->objXmlCompChilds->loop->step;

				for ($i = $start; $i <= (int)$this->objXmlCompChilds->loop->stop; $i += $step) {
					$arrOptions[]=array($i,$prefixo.$i.$sufixo);
				}
				$this->objSmarty->assign("OPTION_SELECTED",self::getValue());
				$this->objSmarty->assign("ARR_OPTIONS",$arrOptions);
			}
		}
		return $this->objSmarty->fetch(FWK_HTML_FORMS."optionsSelectDre.tpl");
    }

}
?>