<?php
require_once (FWK_MODEL."AbsCruds.class.php");
require_once (FWK_CONTROL."ControlConfiguracoes.class.php");

class DescricaoArea extends AbsCruds {

	private $objCtrlConfigs;

	 public function executa($get,$post,$file){
		self::setStringClass("".__CLASS__."");
		switch($get["a"]){
			case "altera":
				self::alteraDescricao($post);
				break;
			default:
				self::exibeFormDescricao();
				break;
		}
	 }


	 private function exibeFormDescricao(){
		$objCtrlForm = new ControlForm(FWK_XML."formDescricaoArea.xml");
		$objCtrlForm->setTplsFile(ADMIN_TPLS);
		$objCtrlForm->setActionForm("".__CLASS__.""."&a=altera");
		$arrDados = array("descricao_area" => utf8_decode(self::getObjCtrlConfigs()->getStrDescricaoArea()));
		$objCtrlForm->registraFormValues($arrDados);
	 }

	 private function alteraDescricao($post){
		try{
			self::getObjCtrlConfigs()->setStrDescricaoArea($post["descricao_area"]);
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