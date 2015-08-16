<?php
require_once (FWK_MODEL."AbsCruds.class.php");
require_once (FWK_CONTROL."ControlConfiguracoes.class.php");

class TituloArea extends AbsCruds {

	private $objCtrlConfigs;

	 public function executa($get,$post,$file){
		self::setStringClass("".__CLASS__."");
		switch($get["a"]){
			case "altera":
				self::alteraTitulo($post);
				break;
			default:
				self::exibeFormTitulo();
				break;
		}
	 }


	 private function exibeFormTitulo(){
		$objCtrlForm = new ControlForm(FWK_XML."formTituloArea.xml");
		$objCtrlForm->setTplsFile(ADMIN_TPLS);
		$objCtrlForm->setActionForm("".__CLASS__.""."&a=altera");
		$arrDados = array("titulo_area" => utf8_decode(self::getObjCtrlConfigs()->getStrTituloArea()));
		$objCtrlForm->registraFormValues($arrDados);
	 }

	 private function alteraTitulo($post){
		try{
			self::getObjCtrlConfigs()->setStrTituloArea($post["titulo_area"]);
			self::vaiPara("".__CLASS__.""."&msg=Ítem cadastrado com sucesso!");
		}catch(CrudException $e){
			self::vaiPara(self::getStringClass()."&msg=".$e->getMensagem());
		}
	 }

	 private function getObjCtrlConfigs(){
		if($this->objCtrlConfigs == null)
			$this->objCtrlConfigs = new ControlConfiguracoes();
		return $this->objCtrlConfigs;
	 }
}
?>