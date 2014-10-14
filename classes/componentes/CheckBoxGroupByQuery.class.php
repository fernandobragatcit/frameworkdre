<?php
require_once (FWK_COMP."AbsCompHtml.class.php");

class CheckBoxGroupByQuery extends AbsCompHtml {

    public function getComponente($value = ""){
		self::regTagsCustomComp($value);
		self::setValue($value);
		self::regTags();
		self::setHtmlComp(self::getObjSmarty()->fetch(FWK_HTML_FORMS."checkBoxGroupByQuery.tpl"));
		self::setCampos();
    }

    private function indefault(){
    	if($this->objXmlComp->checked != "" && $this->objXmlComp->checked == "true")
    		$this->objSmarty->assign("CHECKED","checked=\"checked\"");
    }

    private function setValueCB($value = ""){
    	if(($value != "" && $value == "true") || ($value != "" && $value == "S") || ($value != "" && is_numeric($value)))
    		$this->objSmarty->assign("CHECKED","checked=\"checked\"");
    	else
    		$this->objSmarty->assign("CHECKED","");
    }

    private function regTagsCustomComp($value){
		$strQuery = (string)parent::getObjXmlCompDados()->query;
		$strClasseDao = (string)parent::getObjXmlCompDados()->classeDao;
		$strMetodoDao = (string)parent::getObjXmlCompDados()->metodo;
		$strParam1 = (string)parent::getObjXmlCompDados()->param1;
		
		$arrResults = array();
		$strCaminho="";
		if(trim($strQuery)!=""){
			$arrResults = ControlDb::getAll($strQuery,1);
		}else if(trim($strClasseDao)!="" && trim($strMetodoDao)!=""){
			if((string)parent::getObjXmlCompDados()->tipo == "MODULO"){
				$strCat = (string)parent::getObjXmlCompDados()->categoria;
				$strCaminho = PASTA_MODULOS.$strCat."/classes/view/";
			}else{
				$strCaminho = CLASSES_DAO;
			}
			require_once($strCaminho."".$strClasseDao.".class.php");
			$objCalsse = new $strClasseDao();
			if(isset($strParam1))
				$arrResults = $objCalsse->$strMetodoDao($strParam1);
			else
				$arrResults = $objCalsse->$strMetodoDao();
		}

		$strQueryAlt = (string)parent::getObjXmlCompDados()->queryAlt;

		if(strpos($strQueryAlt, "#idReferencia#") > 0){
			$strQueryAlt = str_replace("#idReferencia#", parent::getIdReferencia(), $strQueryAlt);
		}
		$strClasseDaoAlt = (string)parent::getObjXmlCompDados()->classeDaoAlt;
		$strMetodoDaoAlt = (string)parent::getObjXmlCompDados()->metodoAlt;
		if(trim($strQueryAlt)!=""){
			$arrResultsAlt = ControlDb::getAll($strQueryAlt,1);
		}
		for($i=0; $i<count($arrResults); $i++){
			for($j=0; $j<count($arrResultsAlt); $j++){
				if($arrResults[$i][0] == $arrResultsAlt[$j][0]){
					$this->objSmarty->assign("CHECKED","checked=\"checked\"");
					$arrResults[$i][2] = "checked=\"checked\"";
				}
			}
		}
    	self::getObjSmarty()->assign("ARR_CHECKS",$arrResults);
    }

}
?>