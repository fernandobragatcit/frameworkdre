<?php
require_once (FWK_MODEL."AbsCruds.class.php");
require_once (FWK_CONTROL."ControlConfiguracoes.class.php");

class PalavrasChavesArea extends AbsCruds {

	private $objCtrlConfigs;

	 public function executa($get,$post,$file){
		self::setStringClass("".__CLASS__."");
		switch($get["a"]){
			case "altera":
				self::alteraPalavrasChaves($post);
				break;
			default:
				self::exibeFormPalavrasChaves();
				break;
		}
	 }


	 private function exibeFormPalavrasChaves(){
		$objCtrlForm = new ControlForm(FWK_XML."formPalavrasChavesArea.xml");
		$objCtrlForm->setTplsFile(ADMIN_TPLS);
		$objCtrlForm->setActionForm("".__CLASS__.""."&a=altera");
		$arrDados = array("palavras_chaves_area" => self::getObjCtrlConfigs()->getStrPalavrasChavesArea());
		$objCtrlForm->registraFormValues($arrDados);
	 }
	 
	 public function getPalavrasAtual(){
	 	$arrPalavras = self::getObjCtrlConfigs()->getStrPalavrasChavesArea();
	 	$arrPalavras = explode(", ", $arrPalavras);
	 	foreach ($arrPalavras as $palavra){
	 		$arrDados[]["palavra"] = $palavra;
	 	}
	 	return $arrDados;
	 }

	 private function alteraPalavrasChaves($post){
		try{
			$arrPalavrasChaves = null;
			$strPalavrasChaves = null;
			unset($post["palavras_chaves_area"]["number"]);
			sort($post["palavras_chaves_area"]);
			if($post["palavras_chaves_areaCount"]>0 && $post["palavras_chaves_area"][0]["palavra"] != ""){
				foreach ($post["palavras_chaves_area"] as $palavra){
					$arrPalavrasChaves[] = $palavra["palavra"];
				}
				$strPalavrasChaves = implode($arrPalavrasChaves, ", ");
			}
			self::getObjCtrlConfigs()->setStrPalavrasChavesArea($strPalavrasChaves);
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