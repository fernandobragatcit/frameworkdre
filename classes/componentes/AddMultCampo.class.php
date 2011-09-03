<?php
require_once (FWK_COMP."AbsCompHtml.class.php");

class AddMultCampo extends AbsCompHtml {

	public function getComponente($value = ""){
		try{
			self::regTagsCustomComp();
			self::setValue($value);
			self::regTags();
			self::registraOptions();
			self::setHtmlComp(self::getObjSmarty()->fetch(FWK_HTML_FORMS."addMultCampoDre.tpl"));
			self::setCampos();
		}catch(ElementsException $ex){
			die("ERRO: ".$ex->__toString());
		}
	}

	//NOME_BTN_ADD
	private function regTagsCustomComp(){
		self::getObjSmarty()->assign("NOME_BTN_ADD",(string)self::getObjXmlCompDados()->nomeBtn);
	}

	private function registraOptions(){
	 
		try{
			if(!isset(self::getObjXmlCompDados()->classe) || self::getObjXmlCompDados()->classe == "")
				throw new ElementsException("Não foi passado o parametro 'classe' para o componente AddMultCampo");

			if(!isset(self::getObjXmlCompDados()->caminho) || self::getObjXmlCompDados()->caminho == "")
				throw new ElementsException("Não foi passado o parametro 'caminho' para o componente AddMultCampo");

			if(!isset(self::getObjXmlCompDados()->metodo) || self::getObjXmlCompDados()->metodo == "")
				throw new ElementsException("Não foi passado o parametro 'metodo' para o componente AddMultCampo");

			eval('$caminho = '.self::getObjXmlCompDados()->caminho.";");
			$classe = (string)self::getObjXmlCompDados()->classe;
			$metodo = (string)self::getObjXmlCompDados()->metodo;

			if(!file_exists($caminho.$classe.".class.php"))
				throw new ElementsException("Não foi a classe passada no caminho correspondente para o componente AddMultCampo");
				
			//tudo ok, chamo o dao
			require_once($caminho.$classe.".class.php");
			$objDao = new $classe();

			if(parent::getIdReferencia() != "")
				$arrCampos = $objDao->$metodo(parent::getIdReferencia());
			else
				$arrCampos = $objDao->$metodo();
			$this->objSmarty->assign("ARR_CAMPOS",$arrCampos);
		}catch (ElementsException $e){
			throw new ElementsException($e->getMensagem());
		}
		 
		 
	}
}
?>