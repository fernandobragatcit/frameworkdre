<?php
require_once (FWK_MODEL."AbsViewClass.class.php");
require_once (FWK_MODEL."ConteudoAreas.class.php");
require_once(FWK_CONTROL."ControlForms.class.php");
require_once(FWK_CONTROL."ControlPost.class.php");

abstract class AbsConteudosArea extends AbsViewClass{

	private $strXmlAreal;
	private $objConteudoAreas;
	private $strClass;

	protected function setXmlConteudo($strXmlCont){
		$this->strXmlAreal = $strXmlCont;
	}

	protected function getXmlConteudo(){
		return $this->strXmlAreal;
	}

	protected function setStrClass($strClass){
		$this->strClass = $strClass;
	}

	protected function getStrClass(){
		return $this->strClass;
	}

	public function setNomeForm($strNomeForm){
		$this->strNomeForm = $strNomeForm;
	}

	public function getNomeForm(){
		return $this->strNomeForm;
	}

	/**
	 * Método responsável pela chamada do formulário e exibilo na tela
	 * para cadastro dos usuários no portal.
	 */
	protected function getFormConteudo($get){
		parent::getObjSmarty()->assign("PAG_ATUAL",self::getStrClass());
		$objCtrlForm = new ControlForm(FWK_XML."formConteudoAreas.xml");
		$objCtrlForm->setNomeForm(self::getNomeForm());
		$objCtrlForm->setTplsFile(ADMIN_TPLS);
		$objCtrlForm->setActionForm(self::getStrClass()."&a=altera");
		$strIdioma = $get["idioma"];
		$strConteudo = self::getConteudoAreas()->getConteudoArea(self::getXmlConteudo(), $strIdioma);
		$arrDados = array("idioma" => $strIdioma, "conteudo_area" => $strConteudo);
		parent::getObjSmarty()->assign("EDIT_VALUE",$strConteudo);
		
		//parent::debuga($arrDados);
		
		
		$objCtrlForm->registraFormValues($arrDados);
	}

	private function getConteudoAreas(){
		if($this->objConteudoAreas == null)
			$this->objConteudoAreas = new ConteudoAreas();
		return $this->objConteudoAreas;
	}

	protected function salvaConteudo($post){
		try{
			
			self::getConteudoAreas()->setConteudoArea($post,self::getXmlConteudo());
			parent::vaiPara(parent::getObjCrypt()->cryptData(self::getStrClass()."&idioma=".$post["idioma"]."&msg=Conteúdo da área alterado com sucesso."),"c");
		}catch(CrudException $e){
			parent::vaiPara(parent::getObjCrypt()->cryptData(self::getStrClass()."&idioma=".$post["idioma"]."&msg=Erro ao alterar o conteúdo."),"c");
		}
	}


}
?>