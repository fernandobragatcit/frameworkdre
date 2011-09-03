<?php
require_once(FWK_MODEL."AbsCruds.class.php");
require_once(FWK_MODEL."TemplateArea.class.php");

class SelTemplateArea extends AbsCruds {

	private $objTemplateArea;

	public function executa($get,$post,$file){
		self::setStringClass("".__CLASS__."");
		parent::getObjJs()->addJs(FWK_JS."actionsForm.js");
		switch($get["a"]){
			case "altera":
				self::alteraWireframe($post);
				break;
			default:
				self::exibeTelaWires();
				break;
		}
	}

	private function getObjTemplateArea(){
		if($this->objTemplateArea == null)
			$this->objTemplateArea = new TemplateArea();
		return $this->objTemplateArea;
	}

	private function exibeTelaWires(){
		$strAction = self::getObjCrypt()->cryptData(__CLASS__."&a=altera");
		parent::getObjSmarty()->assign("WIRE_AREA",self::getObjTemplateArea()->getTemplateArea());
		parent::getObjSmarty()->assign("URL_IMG",FWK_IMG);
		parent::getObjSmarty()->assign("ACTION_FORM","?c=".$strAction);
		parent::getObjHttp()->escreEm("CORPO",FWK_HTML_DEFAULT."formSelTempArea.tpl");
	}

	private function alteraWireframe($post){
		try{
			self::getObjTemplateArea()->setTemplateArea(null,$post["wireframe"]);
			self::vaiPara(self::getStringClass()."&msg=Ítem alterado com sucesso!");
		}catch(CrudException $e){
			self::vaiPara(self::getStringClass()."&msg=".$e->getMensagem());
		}


	}
}
?>