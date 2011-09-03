<?php
class CaminhoRaiz {

	public static function getCaminhoRaiz($arrCaminho = null) {

		if($arrCaminho == null)
			$arrCaminho = CaminhoRaiz::getScriptFileName();

		$arrCaminhoIdx = "";
		if($arrCaminho[0]=="")
			$arrCaminhoIdx .= "/";
		foreach ($arrCaminho as $strDir) {
			if(trim($strDir) != "")
				$arrCaminhoIdx.=$strDir."/";
		}
		//die("to andadno ".$arrCaminhoIdx);
		if (file_exists($arrCaminhoIdx . "indexPrincipal.php")) {
			return $arrCaminhoIdx;
		}
		array_pop($arrCaminho);
		return CaminhoRaiz::getCaminhoRaiz($arrCaminho);
	}

	public static function getRetornoRaiz($strCaminho = "") {

		if (is_dir($strCaminho . "arquivos") && is_dir($strCaminho . "framework")) {
			return $strCaminho;
		}

		$strCaminho.="../";

		if(strlen($strCaminho) > 100)
			die($strCaminho);
		return CaminhoRaiz::getRetornoRaiz($strCaminho);
	}

	public static function getScriptFileName(){
			$arrIndexs = array("index.php","prototype.php","tmp_imagem.php");
 			$_SERVIDOR_FIS = str_replace($arrIndexs,"",$_SERVER["SCRIPT_FILENAME"]);
			$arrCaminho = explode("/",$_SERVIDOR_FIS);
		return $arrCaminho;
	}
	
}
?>