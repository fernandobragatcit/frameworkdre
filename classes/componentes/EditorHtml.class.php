<?php
require_once (FWK_COMP."AbsCompHtml.class.php");

class EditorHtml extends AbsCompHtml {

	public function getComponente($value = ""){
		self::addJsEditor();
		self::regOpcoesEditor();
		$this->objSmarty->assign("CAMINHO_FWK",FWK_FIS_JS);
		//SERVIDOR_FISICO
		$this->objSmarty->assign("SERVIDOR_FISICO",SERVIDOR_FISICO);
		//URL_SERVIDOR
		$this->objSmarty->assign("PATH_SERVIDOR",RET_SERVIDOR);
		self::setValorEditor($value);
		self::regTags();
		self::setIdEditor();
		self::setStylesEditor();
		self::setHtmlComp($this->objSmarty->fetch(FWK_HTML_FORMS."editorDre.tpl"));
		self::setCampos();
    }

    private function addJsEditor(){
//		$objCtrlCss = ControlJS::getJS();
//		$objCtrlCss->addJs(FWK_JS."jquery.form.js");
//		$objCtrlCss->addJs(FWK_JS."jquery-validate/lib/jquery.metadata.js");
//		$objCtrlCss->addJs(FWK_JS."jquery.FCKEditor.js");
//		
		
		$objCtrlCss = ControlJS::getJS();
		$objCtrlCss->addJs(FWK_JS."tinymce/jscripts/tiny_mce/tiny_mce.js");
    }

    private function setValorEditor($value){
		if($value != ""){
    		$this->objSmarty->assign("EDIT_VALUE",$value);
		}else{
    		$this->objSmarty->assign("EDIT_VALUE", null);
		}
    }

    private function setIdEditor(){
		$this->objSmarty->assign("ID_EDITOR",(string)$this->objXmlComp->id);
    }

    private function setStylesEditor(){
    	if((string)$this->objXmlComp->tplStylesEditor != ""){
			$strTela = $this->objSmarty->fetch(DEPOSITO_TPLS.(string)$this->objXmlComp->tplStylesEditor);
			$this->objSmarty->assign("EXIBE_STYLES", true);
			$this->objSmarty->assign("INCLUDE_STYLES", $strTela);
    	}else{
			$this->objSmarty->assign("EXIBE_STYLES", false);
    	}
    }

    private function regOpcoesEditor(){
    	$this->objSmarty->assign("ALTURA_EDITOR",self::getAlturaEditor());
    	$this->objSmarty->assign("LARGURA_EDITOR",self::getLarguraEditor());
    }

    private function getAlturaEditor(){
    	if(!$this->objXmlComp->altura || (string)$this->objXmlComp->altura == "" )
    		return 200;
    	return (string)$this->objXmlComp->altura;
    }
    private function getLarguraEditor(){
    	if(!$this->objXmlComp->largura || (string)$this->objXmlComp->largura == "" )
    		return 300;
    	return (string)$this->objXmlComp->largura;
    }
}
?>