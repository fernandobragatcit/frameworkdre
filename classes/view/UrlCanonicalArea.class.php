<?php
require_once (FWK_MODEL."AbsCruds.class.php");
require_once (FWK_CONTROL."ControlConfiguracoes.class.php");

class UrlCanonicalArea extends AbsCruds {

	private $objCtrlConfigs;

	 public function executa($get,$post,$file){
		self::setStringClass("".__CLASS__."");
		switch($get["a"]){
			case "altera":
				self::alteraUrlCanonical($post);
				break;
			default:
				self::exibeFormUrlCanonical();
				break;
		}
	 }


	 private function exibeFormUrlCanonical(){
		$objCtrlForm = new ControlForm(FWK_XML."formUrlCanonicalArea.xml");
		$objCtrlForm->setTplsFile(ADMIN_TPLS);
		$objCtrlForm->setActionForm("".__CLASS__.""."&a=altera");
		$arrDados = array("url_canonical_area" => utf8_decode(self::getObjCtrlConfigs()->getStrUrlCanonical()));
		$objCtrlForm->registraFormValues($arrDados);
	 }

	 private function alteraUrlCanonical($post){
		try{
			self::getObjCtrlConfigs()->setStrUrlCanonical($post["url_canonical_area"]);
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