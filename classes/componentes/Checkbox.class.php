<?php
require_once (FWK_COMP."AbsCompHtml.class.php");

class CheckBox extends AbsCompHtml {

	public function getComponente($value = ""){
		self::setValueCB($value);
		parent::regTags();
		self::indefault();
		parent::setHtmlComp($this->objSmarty->fetch(FWK_HTML_FORMS."checkBoxDre.tpl"));
		parent::setCampos();
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
}
?>